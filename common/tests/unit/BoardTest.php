<?php

namespace common\tests;

use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\fixtures\BoardFixture;
use common\fixtures\UserEntityFixture;
use common\models\Board;
use common\models\LoginForm;

class BoardTest extends \Codeception\Test\Unit
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
        ]);
    }

    protected function _after()
    {
    }

    // tests
    public function testSaveBoard()
    {
        $board = $this->getMockBuilder(Board::class)
            ->getMock();

        $board->method('save')
            ->willReturn(true);

        $this->assertNotFalse($board->save());
    }

    // tests
    public function testCreateBoard()
    {
        $user = (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = $this->make(Board::class, [
            'entity_id' => 1,
            'owner_id' => 1,
            'title' => 'someone',
        ]);

        $this->assertTrue($board->save());
    }

    // tests
    public function testNonexistentUserCantCreateBoard()
    {
        $user = (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = $this->make(Board::class, [
            'entity_id' => 1,
            'owner_id' => 1000,
            'title' => 'someone',
        ]);

        $this->assertFalse($board->save());
    }

    // tests
    public function testNonEntityUserCantCreateBoard()
    {
        $user = (new LoginForm([
            'username' => 'test2.test',
            'password' => 'Test1234',
        ]))->login();

        $board = $this->make(Board::class, [
            'entity_id' => 1,
            'owner_id' => 2,
            'title' => 'someone',
        ]);

        $this->assertFalse($board->save());
    }

    // tests
    public function testUpdateBoard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = Board::findOne(1);
        $board->title = 'new name';

        $this->assertTrue($board->save());
    }

    // tests
    public function testDeleteBoard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = Board::findOne(1);

        $board->delete();

        $this->assertTrue($board->is_deleted);
    }

    // tests
    public function testRestoreBoard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = Board::findOne(2);

        $board->restore();

        $this->assertFalse($board->is_deleted);
    }
}
