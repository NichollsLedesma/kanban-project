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
    public $formCard = null;
    public $enableCardCreation = true;
    public $enableColumnCreation = false;
    public $withHeader = true;
    public $updateForm = null;

    public function run(): string {
        return $this->render(
        	'_boardColumn',
        	[
        		'name' => $this->name,
        		'id' => $this->id,
                'idPrefix' => $this->idPrefix,
                'boardUuid' => $this->boardUuid,
        		'withHeader' => $this->withHeader,
        		'enableCardCreation' => $this->enableCardCreation,
        		'enableColumnCreation' => $this->enableColumnCreation,
        		'cards' => $this->cards,
                'updateForm' => $this->updateForm,
                'formCard'=>$this->formCard,
        	]
        );
    }

}
