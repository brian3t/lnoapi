<?php

use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

$dataProvider = new ArrayDataProvider([
    'allModels' => $model->bandEvents,
    'key' => 'id'
]);
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    ['attribute' => 'id', 'visible' => false],
    [
        'attribute' => 'band.name',
        'format' => 'raw',
        'value' => function ($model) {
            if (!$model->band) return 'N/A';
            $band_name = $model->band->name;
            $band_url = Url::to(['band/view', 'id' => $model->band->id]);
            return "<a href='$band_url' target='_blank' data-pjax='0' >$band_name</a>";
        },
        'label' => 'Band'
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'controller' => 'band-event'
    ],
];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'],
        'pjax' => true,
        'beforeHeader' => [
            [
                'options' => ['class' => 'skip-export']
            ]
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => false,
        'persistResize' => false,
    ]);
