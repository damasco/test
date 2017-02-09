<?php

use yii\db\Migration;

/**
 * Handles the creation of table `client`.
 */
class m170208_112434_create_client_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'surname' => $this->string(50)->notNull(),
            'phone' => $this->string(50)->notNull(),
            'status' => 'ENUM(\'new\', \'member\', \'refused\', \'not_available\') DEFAULT \'new\' NOT NULL',
            'datetime' => $this->datetime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('client');
    }
}
