<?php

namespace console\controllers;

use common\models\Board;
use common\models\Entity;
use common\models\User;
use common\models\UserBoard;
use common\models\UserEntity;
use Faker\Factory;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use const PHP_EOL;

/**
 * Seed database with aleatorious data
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class SeedController extends Controller
{

    /**
     * seeder Entities/Boards/Columns/Cards
     * @param array $usersname
     * @param int $entities
     * @param int $boards
     * @param int $cols
     * @param int $cards
     * @return type
     */
    public function actionKanban(array $usersname = ['admin', 'test_user'], int $entities = 6, int $boards = 6, int $cols = 6, int $cards = 6)
    {
        $usersDb = User::find()->where(['username' => $usersname])->select(['id', 'username']);
        $usersCnt = $usersDb->count();
        if ($usersCnt == 0) {
            $this->stderr('You must specify valid usernames that exists in your database' . PHP_EOL);
            return ExitCode::DATAERR;
        }
        if ($usersCnt != count($usersname)) {
            $u = array_diff($usersname, array_column($usersDb->asArray()->all(), 'username'));
            $this->stderr("One or more usernames doesn't exists in your database: " . implode(', ', $u) . PHP_EOL);
            return ExitCode::DATAERR;
        }
        if ($boards <= 0 || $boards > 50) {
            $boards = 5;
        }

        if ($cols <= 0 || $cols > 50) {
            $cols = 5;
        }

        $faker = Factory::create();
        $usersArr = $usersDb->asArray()->all();

        $ownerFn = function ($type) use ($usersArr) {
            static $ownerIdx = ['entity' => 0, 'board' => 0, 'card' => 0];
            $ownerId = $usersArr[$ownerIdx[$type]]['id'];
            $ownerIdx[$type]++;
            if ($ownerIdx[$type] == count($usersArr)) {
                $ownerIdx[$type] = 0;
            }
            return $ownerId;
        };
        for ($i = 0; $i < $entities; $i++) {
            $newEntity = new Entity();
            $newEntity->owner_id = $ownerFn('entity');
            Yii::$app->user->setIdentity(User::findOne(['id' => $newEntity->owner_id]));
            $newEntity->name = 'Company_' . ($i + 1) . '-' . $faker->word();
            $newEntity->save(false);
            for ($j = 0; $j < $boards; $j++) {
                $newBoard = new Board();
                $newBoard->owner_id = $ownerFn('board');
                Yii::$app->user->setIdentity(User::findOne(['id' => $newBoard->owner_id]));
                $newBoard->title = 'Board_' . ($j + 1) . '-' . $faker->word();
                $newBoard->link('entity', $newEntity);
                $newBoard->save(false);
                for ($k = 0; $k < count($usersArr); $k++) {
                    if ($j == 0) {
                        $newUserEntity = new UserEntity();
                        $newUserEntity->user_id = $usersArr[$k]['id'];
                        $newUserEntity->link('entity', $newEntity);
                        $newUserEntity->save(false);
                    }
                    $newUserBoard = new UserBoard();
                    $newUserBoard->user_id = $usersArr[$k]['id'];
                    $newUserBoard->link('board', $newBoard);
                    $newUserBoard->save(false);
                }
                for ($m = 0; $m < $cols; $m++) {
                    $newColumn = new \common\models\Column();
                    $newColumn->title = 'Column_' . ($m + 1) . '-' . $faker->word();
                    $newColumn->order = $m;
                    $newColumn->owner_id = $newBoard->owner_id;
                    Yii::$app->user->setIdentity(User::findOne(['id' => $newBoard->owner_id]));
                    $newColumn->link('board', $newBoard);
                    $newColumn->save(false);
                    for ($n = 0; $n < $cards; $n++) {
                        $newCard = new \common\models\Card();
                        $newCard->column_id = $newColumn->id;
                        $newCard->owner_id = $ownerFn('card');
                        $newCard->order = $n;
                        $newCard->title = $faker->word();
                        $newCard->description = substr($faker->paragraph(), 0, 255);
                        Yii::$app->user->setIdentity(User::findOne(['id' => $newCard->owner_id]));
                        $newCard->link('column', $newColumn);
                        $newCard->save(false);
                    }
                }
            }
        }
        $this->stdout("Process completed: Entities: {$entities}, Boards: {$boards} per entity, Columns: {$cols} per board, Cards: {$cards} per column, Users: " . count($usersArr) . \PHP_EOL, \yii\helpers\Console::FG_GREEN);
        return ExitCode::OK;
    }

}
