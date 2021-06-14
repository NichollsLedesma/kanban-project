<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\LoginForm;
use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\fixtures\BoardFixture;
use common\fixtures\ColumnFixture;
use common\fixtures\UserBoardFixture;
use common\fixtures\UserEntityFixture;
use common\models\Column;

class ColumnCest
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
            'user_board' => [
                'class' => UserBoardFixture::class,
                'dataFile' => codecept_data_dir() . 'user_board.php',
            ],
            'column' => [
                'class' => ColumnFixture::class,
                'dataFile' => codecept_data_dir() . 'column.php',
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
        $I->amOnRoute('column/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testNonAdminUserCantAccessIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'test.test',
            'password' => 'Test1234',
        ]))->login();

        $I->amOnRoute('column/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testAdminUserCanAccessIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = Column::find()->one();

        $I->amOnRoute('column/index');

        $I->see('Columns', 'h1');
        $I->see($column->title, 'td');
    }

    // tests
    public function testAdminViewColumn(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = Column::find()->one();

        $I->amOnRoute('column/view?id=' . $column->id);

        $I->see($column->title, 'h1');
    }

    // tests
    public function testAdminUpdateColumn(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $column = Column::findOne(1);

        $I->amOnRoute('column/update?id=' . $column->id);

        $I->see('Update Column: ' . $column->title, 'h1');

        $I->submitForm('#column-form', ['Column[board_id]' => $column->board_id, 'Column[owner_id]' => $column->owner_id, 'Column[title]' => 'new', 'Column[order]' => 0]);

        $I->seeRecord(Column::class, [
            'id' => $column->id,
            'title' => 'new',
        ]);
    }

    // tests
    // public function testAdminDeleteUser(FunctionalTester $I)
    // {
    // }
}
