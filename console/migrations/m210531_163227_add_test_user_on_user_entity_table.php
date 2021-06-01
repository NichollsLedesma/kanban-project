<?php

use common\models\Entity;
use common\models\LoginForm;
use common\models\User;
use common\models\UserEntity;
use yii\db\Migration;

/**
 * Class m210531_163227_add_test_user_on_user_entity_table
 */
class m210531_163227_add_test_user_on_user_entity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $userTest = \Yii::createObject([
            'class'    => User::class,
            'username' => 'test_user',
            'auth_key' =>  Yii::$app->getSecurity()->generateRandomString(),
            'password' => '12345678',
            'email'    => 'test1@@example.com',
            'status' => 10,
            'verification_token' =>  Yii::$app->getSecurity()->generateRandomString(),
        ]);
        $userTest->insert();

        // $entityTest = \Yii::createObject([
        //     'class'    => Entity::class,
        //     'name' => 'name',
        //     'owner_id' =>  $userTest->id,
        //     'created_by' =>  $userTest->id,
        //     'updated_by' =>  $userTest->id,
        // ]);
        // $entityTest->insert();
     
        // $userEntityTest = \Yii::createObject([
        //     'class'    => UserEntity::class,
        //     'user_id' => $userTest->id,
        //     'entity_id' => $entityTest->id,
        // ]);
        // $userEntityTest->insert();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210531_163227_add_test_user_on_user_entity_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210531_163227_add_test_user_on_user_entity_table cannot be reverted.\n";

        return false;
    }
    */
}
