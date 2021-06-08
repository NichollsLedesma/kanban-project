<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\LoginForm;
use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\fixtures\BoardFixture;
use common\fixtures\UserEntityFixture;
use common\models\Board;
use common\models\Entity;

class BoardCest
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
            'board' => [
                'class' => BoardFixture::class,
                'dataFile' => codecept_data_dir() . 'board.php',
            ],
            'user_entity' => [
                'class' => UserEntityFixture::class,
                'dataFile' => codecept_data_dir() . 'user_entity.php',
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
        $I->amOnRoute('board/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testNonAdminUserCantAccessIndexIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'test.test',
            'password' => 'Test1234',
        ]))->login();

        $I->amOnRoute('board/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testAdminUserCanAccessIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = Board::find()->one();

        $I->amOnRoute('board/index');

        $I->see('Boards', 'h1');
        $I->see($board->title, 'td');
    }

    // tests
    public function testAdminViewBoard(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = Board::find()->one();

        $I->amOnRoute('board/view?id=' . $board->id);

        $I->see($board->title, 'h1');
    }

    // tests
    public function testAdminUpdateBoard(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $board = Board::findOne(3);

        $I->amOnRoute('board/update?id=' . $board->id);

        $I->see('Update Board: ' . $board->title, 'h1');

        $I->submitForm('#board-form', ['Board[entity_id]' => $board->entity_id, 'Board[title]' => 'new', 'Board[owner_id]' => $board->owner_id]);

        $I->seeRecord(Board::class, [
            'id' => $board->id,
            'title' => 'new',
        ]);
    }

    // tests
    // public function testAdminDeleteUser(FunctionalTester $I)
    // {
    // }
}
