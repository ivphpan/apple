<?php

use yii\db\Migration;

/**
 * Class m210127_014428_apple_table
 */
class m210127_014428_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->comment('Цвет'),
            'size' => $this->decimal(3, 2)->defaultValue(1)->comment('Размер'),
            'datetimeCreate' => $this->dateTime()->comment('Дата и время создания'),
            'datetimeGround' => $this->dateTime()->comment('Дата и время падения'),
            'isEat' => $this->tinyInteger()->comment('Доступно для еды'),
            'isOnTree' => $this->tinyInteger()->comment('Висит на дереве'),
            'isExpired' => $this->tinyInteger()->comment('Испорчено')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{apple}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210127_014428_apple_table cannot be reverted.\n";

        return false;
    }
    */
}
