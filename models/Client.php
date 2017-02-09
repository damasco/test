<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string $status
 * @property string $datetime
 */
class Client extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_MEMBER = 'member';
    const STATUS_REFUSED = 'refused';
    const STATUS_NOT_AVAILABLE = 'not_available';

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_MEMBER,
            self::STATUS_REFUSED,
            self::STATUS_NOT_AVAILABLE
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'phone', 'datetime'], 'required'],
            [['status'], 'string'],
            [['datetime'], 'safe'],
            [['name', 'surname', 'phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'phone' => 'Phone',
            'status' => 'Status',
            'datetime' => 'Datetime',
        ];
    }
}
