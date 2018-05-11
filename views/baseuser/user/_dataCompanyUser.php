<?php
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;

$gridColumn = [
    ['attribute' => 'id', 'hidden' => true],
    [
        'label' => 'Name',
        'format'=>'raw',
        'value' => $model->company?\yii\helpers\BaseHtml::a($model->company->name, "/company/view?id=" . $model->company->id,['target'=>'_blank']):''
    ],
    'role',
];
echo DetailView::widget([
    'model' => $model->companyUser,
    'attributes' => $gridColumn,
]);

