<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\LoginForm;
use common\fixtures\UserFixture;
use common\fixtures\EntityFixture;
use common\fixtures\BoardFixture;
use common\fixtures\CardFixture;
use common\fixtures\ColumnFixture;
use common\fixtures\UserBoardFixture;
use common\fixtures\UserEntityFixture;
use common\models\Card;
use common\models\Column;

class CardCest
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
            'card' => [
                'class' => CardFixture::class,
                'dataFile' => codecept_data_dir() . 'card.php',
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
        $I->amOnRoute('card/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testNonAdminUserCantAccessIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'test.test',
            'password' => 'Test1234',
        ]))->login();

        $I->amOnRoute('card/index');

        $I->seeCurrentUrlEquals('/index-test.php/site/login');
    }

    // tests
    public function testAdminUserCanAccessIndex(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $card = Card::find()->one();

        $I->amOnRoute('card/index');

        $I->see('Cards', 'h1');
        $I->see($card->title, 'td');
    }

    // tests
    public function testAdminViewCard(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $card = Card::find()->one();

        $I->amOnRoute('card/view?id=' . $card->id);

        $I->see($card->title, 'h1');
    }

    // tests
    public function testAdminUpdateCard(FunctionalTester $I)
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
        ]))->login();

        $card = Card::findOne(1);

        $I->amOnRoute('card/update?id=' . $card->id);

        $I->see('Update Card: ' . $card->title, 'h1');

        $I->submitForm('#card-form', [
            'Card[column_id]' => $card->column_id,
            'Card[owner_id]' => $card->owner_id,
            'Card[title]' => 'new',
            'Card[description]' => $card->description,
            'Card[order]' => $card->order,
            'Card[color]' => '#' . $card->color,
        ]);

        $I->seeRecord(Card::class, [
            'id' => $card->id,
            'title' => 'new',
        ]);
    }

    // tests
    // public function testAdminDeleteCard(FunctionalTester $I)
    // {
    // }
}
