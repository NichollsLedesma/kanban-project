<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Boards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Board', ['create'], ['class' => 'btn btn-success']) ?>
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
                'headerOptions' => ['style' => 'width:15%'],
                'template' => '{column} {user} {view} {update} {delete}',
                'buttons' => [
                    'user' => function ($url, $model) {
                        return Html::a('<i class="fa fa-user"></i>', $url, [
                            'title' => Yii::t('app', 'user')
                        ]);
                    },
                    'column' => function ($url, $model) {
                        return Html::a('<i class="fa fa-columns"></i>', $url, [
                            'title' => Yii::t('app', 'column')
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
                    if ($action == "user") {
                        return Url::to(['@web/user-board', 'UserEntitySearch[entity_id]' => $model->id]);
                    }
                    if ($action == "column") {
                        return Url::to(['@web/column', 'ColumnSearch[board_id]' => $model->id]);
                    }
                    return Url::to([$action, 'id' => $model->id]);
                },
            ],

            // 'id',
            'uuid',
            'entity_id',
            'owner_id',
            'title',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
            'is_deleted:boolean',
            //'deleted_at',

        ],
    ]); ?>


</div>