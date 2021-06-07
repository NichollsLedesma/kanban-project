<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\LoginForm;
use common\models\User;
use common\fixtures\UserFixture;

class UserCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ]
        ];
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testLoggedOutUserIndex(FunctionalTester $I)
    {
        $user = User::find()->one();

        $I->amOnRoute('user/index');
        $I->dontSee($user->email, 'td');
    }

    // tests
    public function testNonAdminUserIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'test.test',
            'password' => 'Test1234',
        ]))->login();

        $user = User::find()->one();

        $I->amOnRoute('user/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testAdminUserIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $user = User::find()->one();

        $I->amOnRoute('user/index');

        $I->see('Users', 'h1');
        $I->see($user->email, 'td');
    }

    // tests
    public function testAdminViewUser(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $user = User::findOne(2);

        $I->amOnRoute('user/view?id=' . $user->id);

        $I->see($user->username, 'h1');
        $I->see($user->email, 'span');
    }

    // tests
    public function testAdminUpdateUser(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $user = User::findOne(3);

        $I->amOnRoute('user/update?id=' . $user->id);

        $I->see('Update User: ' . $user->username, 'h1');

        $I->submitForm('#user-form', ['User[status]' => '9']);

        $I->seeRecord(User::class, [
            'id' => $user->id,
            'status' => 9,
        ]);
    }

    // tests
    // public function testAdminDeleteUser(FunctionalTester $I)
    // {
    // }
}
