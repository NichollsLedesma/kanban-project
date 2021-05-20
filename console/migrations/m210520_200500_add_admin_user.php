<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m210520_200500_add_admin_user
 */
class m210520_200500_add_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = \Yii::createObject([
            'class'    => User::class,
            'username' => 'admin',
            'auth_key' =>  Yii::$app->getSecurity()->generateRandomString(),
            'password' => '12345678',
            'email'    => 'admin@@example.com',
            'status' => 10,
            'is_admin' => 1,
        ]);
        $user->insert();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210520_200500_add_admin_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210520_200500_add_admin_user cannot be reverted.\n";

        return false;
    }
    */
}
