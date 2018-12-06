<?php

use yii\db\Migration;

use app\models\User;
use yii\base\Security;

/**
 * Class m181205_150911_users
 * Создаём таблицу с пользователями
 */
class m181205_150911_users extends Migration
{


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('users',[
            'id'=>$this->PrimaryKey()->comment('Ключик таблички'),
            'username'=>$this->string(20)->notNull()->comment('Имя пользователя'),
            'password'=>$this->string(60)->notNull()->comment('Пароль'),
            'auth_key'=>$this->string(32)->notNull()->comment('автологин'),
            'status'=>$this->boolean()->NotNull()->defaultValue(true)->comment('статус'),
        ]);
        $this->createIndex('usernamei','users',['username'],true);
        $this->createIndex('atokeni','users',['auth_key'],true);
        // создадим админа .. 
        
        $u=new User(['username'=>'admin','password'=>Yii::$app->security->generatePasswordHash('admin')]);
        $u->save();
        echo "Пользователь admin:admin\n";
    }

    public function down()
    {
        //echo "m181205_150911_users cannot be reverted.\n";
        $this->dropTable('users');
        return true;
    }

}
