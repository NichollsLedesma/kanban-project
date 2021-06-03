<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Entities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Entity', ['create'], ['class' => 'btn btn-success']) ?>
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
                'template' => '{board} {user} {view} {update} {delete}',
                'buttons' => [
                    'user' => function ($url, $model) {
                        return Html::a('<i class="fa fa-user"></i>', $url, [
                            'title' => Yii::t('app', 'user')
                        ]);
                    },
                    'board' => function ($url, $model) {
                        return Html::a('<i class="fa fa-clipboard"></i>', $url, [
                            'title' => Yii::t('app', 'board')
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
                        return Url::to(['@web/user-entity', 'UserEntitySearch[entity_id]' => $model->id]);
                    }
                    if ($action == "board") {
                        return Url::to(['@web/board', 'BoardSearch[entity_id]' => $model->id]);
                    }
                    return Url::to([$action, 'id' => $model->id]);
                },
            ],

            // 'id',
            'uuid',
            'owner_id',
            'name',
            'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
            'is_deleted:boolean',
            //'deleted_at',

        ],
    ]); ?>


</div>