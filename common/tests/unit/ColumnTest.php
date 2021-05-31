<?php

namespace common\tests;

use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\fixtures\BoardFixture;
use common\fixtures\UserEntityFixture;
use common\fixtures\UserBoardFixture;
use common\fixtures\ColumnFixture;
use common\models\Column;
use common\models\LoginForm;

class ColumnTest extends \Codeception\Test\Unit
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
            'user_entity' => [
                'class' => UserEntityFixture::class,
                'dataFile' => codecept_data_dir() . 'user_entity.php'
            ],
            'board' => [
                'class' => BoardFixture::class,
                'dataFile' => codecept_data_dir() . 'board.php'
            ],
            'user_boar' => [
                'class' => UserBoardFixture::class,
                'dataFile' => codecept_data_dir() . 'user_board.php'
            ],
            'column' => [
                'class' => ColumnFixture::class,
                'dataFile' => codecept_data_dir() . 'column.php'
            ],
        ]);
    }

    protected function _after()
    {
    }

    // tests
    public function testSaveColumn()
    {
        $column = $this->getMockBuilder(Column::class)
            ->getMock();

        $column->method('save')
            ->willReturn(true);

        $this->assertNotFalse($column->save());
    }

    // tests
    public function testCreateColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = $this->make(Column::class, [
            'board_id' => 1,
            'owner_id' => 1,
            'title' => 'someone',
            'order' => 0
        ]);

        $this->assertTrue($column->save());
    }

    // tests
    public function testNonexistentUserCantCreateColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = $this->make(Column::class, [
            'board_id' => 1,
            'owner_id' => 1000,
            'title' => 'someone',
            'order' => 0
        ]);

        $this->assertFalse($column->save());
    }

    // tests
    public function testNonBoardUserCantCreateColumn()
    {
        (new LoginForm([
            'username' => 'test2.test',
            'password' => 'Test1234',
        ]))->login();

        $column = $this->make(Column::class, [
            'board_id' => 1,
            'owner_id' => 3,
            'title' => 'someone',
            'order' => 0
        ]);

        $this->assertFalse($column->save());
    }

    // tests
    public function testUpdateColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = Column::findOne(1);
        $column->title = 'new name';

        $this->assertTrue($column->save());
    }

    // tests
    public function testDeleteColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = Column::findOne(1);

        $column->delete();

        $this->assertTrue($column->is_deleted);
    }

    // tests
    public function testRestoreColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = Column::findOne(2);

        $column->restore();

        $this->assertFalse($column->is_deleted);
    }
}
