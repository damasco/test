<?php

namespace app\commands;

use Faker\Factory;
use yii\console\Controller;
use app\models\Client;
use Yii;

class ClientController extends Controller
{
    /**
     * @param int $count
     * @return void
     */
    public function actionInit($count = 500)
    {
        $faker = Factory::create('ru_RU');
        $rows = [];

        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                $faker->firstName,
                $faker->lastName,
                $faker->phoneNumber,
                $faker->randomElement(Client::getStatuses()),
                $faker->dateTimeBetween($startDate = '-6 months', $endDate = 'now')->format('Y-m-d H:i:s')
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(Client::tableName(), ['name', 'surname', 'phone', 'status', 'datetime'], $rows)
            ->execute();
    }
}