<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ColumnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Columns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="column-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Column', ['create'], ['class' => 'btn btn-success']) ?>
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
                'template' => '{card} {view} {update} {delete}',
                'buttons' => [
                    'card' => function ($url, $model) {
                        return Html::a('<i class="fa fa-address-card"></i>', $url, [
                            'title' => Yii::t('app', 'card')
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => Yii::t('app', 'view')
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fa fa-edit"></i>', $url, [
                            'title' => Yii::t('app', 'edit')
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fa fa-trash"></i>', $url, [
                            'title' => Yii::t('app', 'delete'),
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this collection and images?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action == "card") {
                        return Url::to(['@web/card', 'CardSearch[column_id]' => $model->id]);
                    }
                    return Url::to([$action, 'id' => $model->id]);
                },
            ],

            // 'id',
            'uuid',
            'board_id',
            'owner_id',
            'title',
            //'order',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
            'is_deleted:boolean',
            //'deleted_at',

        ],
    ]); ?>


</div>