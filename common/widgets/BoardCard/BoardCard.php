<?php

namespace common\widgets\BoardCard;

use yii\base\Widget;

/**
 * Description of BoardCard
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class BoardCard extends Widget
{

    public $id = '';
    public $title = '';
    public $content = '';
    public $isForm = false;
    public $columnId = null;
    public $boardUuid = null;

    public function run(): string {
        return $this->render('_boardCard', ['id' => $this->id ?? null, 'title' => $this->title, 'content' => $this->content ?? null, 'isForm' => $this->isForm, 'boardUuid' => $this->boardUuid, 'columnId' => $this->columnId]);
    }

}
