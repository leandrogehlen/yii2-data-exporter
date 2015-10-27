<?php

use yii\db\Migration;

class m151025_073418_tests_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB';
        }

        $this->createTable('person', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string()->notNull(),
            'lastName' => $this->string()->notNull(),
            'birthDate' => $this->dateTime(),
            'salary' => $this->decimal(18,2)
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('person');
    }
}
