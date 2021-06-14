<?php

namespace common\tests\unit;

use Codeception\Test\Unit;
use common\fixtures\BoardFixture;
use common\fixtures\CardFixture;
use common\fixtures\ColumnFixture;
use common\fixtures\EntityFixture;
use common\fixtures\UserBoardFixture;
use common\fixtures\UserEntityFixture;
use common\fixtures\UserFixture;
use common\models\Card;
use common\models\CardRepository;
use common\models\LoginForm;
use common\tests\UnitTester;
use function codecept_data_dir;

class CardTest extends Unit
{

    /**
     * @var UnitTester
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
            'card' => [
                'class' => CardFixture::class,
                'dataFile' => codecept_data_dir() . 'card.php'
            ],
        ]);
    }

    protected function _after()
    {
        
    }

    // tests
    public function testSaveCard()
    {
        $card = $this->getMockBuilder(Card::class)
                ->getMock();

        $card->method('save')
                ->willReturn(true);

        $this->assertNotFalse($card->save());
    }

    // tests
    public function testCreateCard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();

        $column = $this->make(Card::class, [
            'column_id' => 1,
            'owner_id' => 1,
            'title' => 'someone',
            'description' => 'this is the description',
            'order' => 0,
            'color' => 'FFFFFF'
        ]);

        $this->assertTrue($column->save());
    }

    // tests
    public function testNonexistentUserCantCreateCard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();

        $column = $this->make(Card::class, [
            'column_id' => 1,
            'owner_id' => 1000,
            'title' => 'someone',
            'description' => 'some description',
            'order' => 0,
            'color' => 'FFFFFF',
        ]);

        $this->assertFalse($column->save());
    }

    // tests
    public function testNonBoardUserCantCreateCard()
    {
        (new LoginForm([
            'username' => 'test2.test',
            'password' => 'Test1234',
                ]))->login();

        $card = $this->make(Card::class, [
            'column_id' => 1,
            'owner_id' => 1000,
            'title' => 'someone',
            'description' => 'some description',
            'order' => 0,
            'color' => 'FFFFFF',
        ]);

        $this->assertFalse($card->save());
    }

    // tests
    public function testWrongColorCantCreateCard()
    {
        (new LoginForm([
            'username' => 'test2.test',
            'password' => 'Test1234',
                ]))->login();

        $card = $this->make(Card::class, [
            'column_id' => 1,
            'owner_id' => 1,
            'title' => 'someone',
            'description' => 'some description',
            'order' => 0,
            'color' => 'FFFFFI',
        ]);

        $this->assertFalse($card->save());
    }

    // tests
    public function testUpdateCard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();

        $column = Card::findOne(1);
        $column->title = 'new name';

        $this->assertTrue($column->save());
    }

    // tests
    public function testDeleteCard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();

        $column = Card::findOne(1);

        $column->delete();

        $this->assertTrue($column->is_deleted);
    }

    // tests
    public function testRestoreCard()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();

        $column = Card::findOne(2);

        $column->restore();

        $this->assertFalse($column->is_deleted);
    }

    public function testArrageCardSameColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();
        CardRepository::reArrageByCardId(2, 0, 1);
        $r = Card::find()->where(['column_id' => 1])->orderBy('order ASC')->asArray()->all();
        $this->assertEquals($r[0]['id'], 2);
        $this->assertEquals($r[0]['order'], 0);
        $this->assertEquals($r[1]['id'], 1);
        $this->assertEquals($r[1]['order'], 1);
    }

    public function testArrageCardToAnotherColumn()
    {
        (new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'password_0',
                ]))->login();
        CardRepository::reArrageByCardId(2, 2, 2);
        $r = Card::find()->where(['column_id' => 2])->orderBy('order ASC')->asArray()->all();
        $this->assertEquals($r[0]['id'], 3);
        $this->assertEquals($r[0]['order'], 0);
        $this->assertEquals($r[1]['id'], 4);
        $this->assertEquals($r[1]['order'], 1);
        $this->assertEquals($r[2]['id'], 2);
        $this->assertEquals($r[2]['order'], 2);
        $this->assertEquals($r[3]['id'], 5);
        $this->assertEquals($r[3]['order'], 3);
    }

}
