<?php

namespace common\widgets\BoardColumn;

use yii\base\Widget;

/**
 * Description of BoardColumn
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class BoardColumn extends Widget
{

    public $name = '';
    public $id = null;
    public $idPrefix = '';
    public $boardUuid = '';
    public $cards = [];

    public function run(): string {
        return $this->render('_boardColumn', ['name' => $this->name, 'id' => $this->id, 'idPrefix' => $this->idPrefix, 'boardUuid' => $this->boardUuid, 'cards' => $this->cards]);
    }

}
