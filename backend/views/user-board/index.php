<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserBoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Boards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-board-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User Board', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_id',
            'board_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
