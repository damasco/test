<?php

namespace app\services;

use yii\helpers\ArrayHelper;

class ClientData
{
    private $connection;
    private $period;
    private $startDay;

    /**
     * ClientData constructor.
     * @param int $period
     */
    public function __construct($period)
    {
        $this->connection = \Yii::$app->db;
        $this->period = $period;

        $firstClient = $this->connection->createCommand('SELECT date(datetime) as date FROM client ORDER BY datetime LIMIT 1')->queryOne();
        $this->startDay = $firstClient['date'];
    }

    /**
     * @return array
     */
    public function run()
    {
        $startDate = strtotime($this->startDay);
        $lastDate = strtotime($this->getLastDay());

        $members = ArrayHelper::index($this->getMembers(), 'sort');
        $all = ArrayHelper::index($this->getAll(), 'sort');

        $result = [
            'labels' => [],
            'data' => [],
        ];
        $i = 0;
        $period = 60 * 60 * 24 * $this->period;

        while ($startDate <= $lastDate) {
            if (!isset($all[$startDate]) || !isset($members[$startDate])) {
                $result['data'][$i] = 0;
            } else {
                $result['data'][$i] = (int)ceil(($members[$startDate]['count'] / $all[$startDate]['count']) * 100);
            }
            $i++;
            $startDate += $period;
        }
        $result['labels'] = array_keys($result['data']);
        return $result;
    }

    /**
     * @return array
     */
    private function getMembers()
    {
        return $this->connection->createCommand('
            SELECT
            COUNT(*) AS count,
            CONCAT(
                DATE_ADD(\'' . $this->startDay . '\', INTERVAL FLOOR((TO_DAYS(`datetime`)-TO_DAYS(\'' . $this->startDay . '\'))/' . $this->period . ')*' . $this->period . ' DAY),
                \' - \',
                DATE_ADD(DATE_ADD(\'' . $this->startDay . '\', INTERVAL FLOOR((TO_DAYS(`datetime`)-TO_DAYS(\'' . $this->startDay . '\'))/' . $this->period . ')*' . $this->period . ' DAY), INTERVAL ' . ($this->period - 1) . ' DAY)
            ) AS period,
            FLOOR(UNIX_TIMESTAMP(date_add(\'' . $this->startDay . '\', INTERVAL FLOOR((TO_DAYS(`datetime`)-TO_DAYS(\'' . $this->startDay . '\'))/' . $this->period . ')*' . $this->period . ' DAY))) AS sort
            FROM client
            WHERE status=\'member\'
            GROUP BY period, sort
        ')->queryAll();
    }

    /**
     * @return array
     */
    private function getAll()
    {
        return $this->connection->createCommand('
            SELECT
            COUNT(*) AS count,
            CONCAT(
                DATE_ADD(\'' . $this->startDay . '\', INTERVAL FLOOR((TO_DAYS(`datetime`)-TO_DAYS(\'' . $this->startDay . '\'))/' . $this->period . ')*' . $this->period . ' DAY),
                \' - \',
                DATE_ADD(DATE_ADD(\'' . $this->startDay . '\', INTERVAL FLOOR((TO_DAYS(`datetime`)-TO_DAYS(\'' . $this->startDay . '\'))/' . $this->period . ')*' . $this->period . ' DAY), INTERVAL ' . ($this->period - 1) . ' DAY)
            ) AS period,
            FLOOR(UNIX_TIMESTAMP(date_add(\'' . $this->startDay . '\', interval FLOOR((TO_DAYS(`datetime`)-TO_DAYS(\'' . $this->startDay . '\'))/' . $this->period . ')*' . $this->period . ' DAY))) AS sort
            FROM client
            GROUP BY period, sort
        ')->queryAll();
    }

    /**
     * @return string
     */
    private function getLastDay()
    {
        $lastClient = $this->connection->createCommand('SELECT date(datetime) as date FROM client ORDER BY datetime DESC LIMIT 1')->queryOne();
        return $lastClient['date'];
    }

}