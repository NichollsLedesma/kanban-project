<?php

namespace frontend\models;

use common\models\ChecklistOption;
use Yii;


class CreateChecklistOptionForm extends ChecklistOption {

	public function createChecklistOption() {
        $this->owner_id = Yii::$app->getUser()->getId();
        $this->save();
        return true;
    }

}
