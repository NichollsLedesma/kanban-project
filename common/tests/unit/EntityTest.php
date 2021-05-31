<?php

namespace common\tests;

use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\models\Entity;
use common\models\LoginForm;

class EntityTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'entity' => [
                'class' => EntityFixture::class,
                'dataFile' => codecept_data_dir() . 'entity.php'
            ],
        ]);
    }

    protected function _after()
    {
    }

    // tests
    public function testSaveEntity()
    {
        $entity = $this->getMockBuilder(Entity::class)
            ->getMock();

        $entity->method('save')
            ->willReturn(true);

        $this->assertNotFalse($entity->save());
    }

    // tests
    public function testCreateEntity()
    {
        $user = (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = $this->make(Entity::class, [
            'owner_id' => 1,
            'name' => 'someone',
        ]);

        $this->assertTrue($entity->save());
    }

    // tests
    public function testNonexistentUserCantCreateEntity()
    {
        $user = (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = $this->make(Entity::class, [
            'owner_id' => 1000,
            'name' => 'someone',
        ]);

        $this->assertFalse($entity->save());
    }

    // tests
    public function testUpdateEntity()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = Entity::findOne(1);
        $entity->name = 'new name';

        $this->assertTrue($entity->save());
    }

    // tests
    public function testDeleteEntity()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = Entity::findOne(1);

        $entity->delete();

        $this->assertTrue($entity->is_deleted);
    }

    // tests
    public function testRestoreEntity()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $entity = Entity::findOne(2);

        $entity->restore();

        $this->assertFalse($entity->is_deleted);
    }
}
