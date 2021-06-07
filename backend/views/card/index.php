<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Card', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:10%'],
            ],

            'id',
            'uuid',
            'column_id',
            'owner_id',
            'title',
            //'description',
            //'order',
            //'color',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
            'is_deleted:boolean',
            //'deleted_at',
        ],
    ]); ?>


</div>