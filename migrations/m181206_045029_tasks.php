<?php

use yii\db\Migration;

/**
 * Class m181206_045029_tasks
 *
 * миграция статусов и заданий ..
 */
class m181206_045029_tasks extends Migration
{
   
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // создаём таблицу статусов 
        $this->createTable('statuses',[
            'id'=>$this->primaryKey()->comment('ключик'),
            'name'=>$this->string(255)->notNull()->comment('Название'),
            'weight'=>$this->integer()->defaultValue(0)->notNull()->comment('Вес'),
        ]);

        // таблица заданий 
        $this->createTable('tasks',[
            'id'=>$this->primaryKey()->comment('Ключик'),
            'name'=>$this->string(255)->notNull()->comment('Название'),
            'descr'=>$this->text()->comment('Описание'),
            'created'=>$this->dateTime()->notNull()->defaultExpression('now()')->comment('Дата создания'),
            'updated'=>$this->dateTime()->notNull()->defaultExpression('now()')->comment('Дата обновления'),
            'termin'=>$this->dateTime()->notNull()->comment('Срок выполнения'),
            'status'=>$this->integer()->notNull()->comment('Статус'),
            'uid'=>$this->integer()->notNull()->comment('Ссылка на юзера'),
        ]);
        // при удлении юзера . режутся задачки .. 
        $this->addForeignKey('fktaskuser','tasks','uid','users','id','cascade','cascade');
    }


    public function down()
    {
        //echo "m181206_045029_tasks cannot be reverted.\n";
        $this->dropTable('tasks');
        $this->dropTable('statuses');
        //return false;
        return true;
    }
    
}
