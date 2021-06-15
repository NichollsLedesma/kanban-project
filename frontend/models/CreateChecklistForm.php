<?php

namespace frontend\models;

use common\models\Checklist;
use Yii;


class CreateChecklistForm extends Checklist {

	public function createChecklist() {
        $this->owner_id = Yii::$app->getUser()->getId();
        $this->save();
        return true;
    }

}
