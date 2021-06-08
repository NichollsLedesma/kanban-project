<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\LoginForm;
use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\models\Entity;

class EntityCest
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
            ],
            'entity' => [
                'class' => EntityFixture::class,
                'dataFile' => codecept_data_dir() . 'entity.php',
            ],
        ];
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testLoggedOutUserCantAccessIndex(FunctionalTester $I)
    {
        $I->amOnRoute('entity/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testNonAdminUserCantAccessIndexIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'test.test',
            'password' => 'Test1234',
        ]))->login();

        $I->amOnRoute('entity/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testAdminUserCanAccessIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = Entity::find()->one();

        $I->amOnRoute('entity/index');

        $I->see('Entities', 'h1');
        $I->see($entity->name, 'td');
    }

    // tests
    public function testAdminViewEntity(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = Entity::find()->one();

        $I->amOnRoute('entity/view?id=' . $entity->id);

        $I->see($entity->name, 'h1');
    }

    // tests
    public function testAdminUpdateEntity(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = Entity::findOne(3);

        $I->amOnRoute('entity/update?id=' . $entity->id);

        $I->see('Update entity: ' . $entity->name, 'h1');

        $I->submitForm('#entity-form', ['Entity[name]' => 'new']);

        $I->seeRecord(Entity::class, [
            'id' => $entity->id,
            'name' => 'new',
        ]);
    }

    // tests
    // public function testAdminDeleteUser(FunctionalTester $I)
    // {
    // }
}
