<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m210125_125300_user_create
 */
class m210125_125300_user_create extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = 'ivphpan';
        $user->password = 123;
        $user->email = 'ivphpan@gmail.com';
        $user->status = User::STATUS_ACTIVE;
        $user->auth_key = \Yii::$app->security->generateRandomString();
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $user = User::findOne(['username' => 'ivphpan']);
        if ($user) {
            $user->delete();
        }
        echo "m210125_125300_user_create cannot be reverted.\n";
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210125_125300_user_create cannot be reverted.\n";

        return false;
    }
    */
}
