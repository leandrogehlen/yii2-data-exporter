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
            'id'        => $this->primaryKey(),
            'firstName' => $this->string()->notNull(),
            'lastName'  => $this->string()->notNull(),
            'birthDate' => $this->dateTime(),
            'salary'    => $this->decimal(18, 2),
            'active'    => $this->boolean(),
        ], $tableOptions);

        $this->createTable('product', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->createTable('invoice', [
            'id'          => $this->primaryKey(),
            'person_id'   => $this->integer()->notNull(),
            'created_at'  => $this->date()->notNull(),
            'number'      => $this->string(30),
            'description' => $this->text(),
        ], $tableOptions);

        $this->createTable('invoice_details', [
            'id'         => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity'   => $this->integer()->notNull(),
            'price'      => $this->decimal(18, 2)->notNull(),
            'total'      => $this->decimal(18, 2)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('invoice_details');
        $this->dropTable('invoice');
        $this->dropTable('product');
        $this->dropTable('person');
    }
}
