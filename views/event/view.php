<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Event', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">
    <div class="col-md-6">
        <div class="col-sm-9">
            <h2><?= 'Event'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">

            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this Event?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-6">
<?php
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'user.username',
            'label' => 'Event Owner',
        ],
        [
            'attribute' => 'venue.name',
            'label' => 'Venue',
        ],
        'date',
        'start_time',
        'end_time',
        'name',
        'description:ntext',
        'cost',
        'min_cost',
        'max_cost',
        'is_charity',
        'twitter',
        'facebook',
        'website',
        'system_note',
        'sdr_name',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>

    <div class="col-md-6">
<?php
if($providerBandEvent->totalCount){
    $gridColumnBandEvent = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'band.name',
                'label' => 'Band\'s name'
            ],
                ];
    echo Gridview::widget([
        'dataProvider' => $providerBandEvent,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-band-event']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Bands Performing'),
        ],
        'export' => false,
        'columns' => $gridColumnBandEvent
    ]);
}
?>

    </div>
    <div class="col-md-6">
        <h4>Created By</h4>
    </div>
    <?php
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'email',
        'first_name',
    ];?>
    <div class="col-md-6">
    <?php
    echo DetailView::widget([
        'model' => $model->createdBy,
        'attributes' => $gridColumnUser    ]);
    ?>
    </div>
    <div class="col-md-6">
        <h4>Venue</h4>
    </div>
    <div class="col-md-6">
    <?php
    $gridColumnVenue = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function($model){
                return "<a href='/venue/view?id={$model->id}'>{$model->name}</a>" ;
            }
        ],
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'phone',
        'cost',
        'website',
        'twitter',
        'facebook',
    ];
    if (is_object($model->venue)) {
        echo DetailView::widget([
            'model' => $model->venue,
            'attributes' => $gridColumnVenue    ]);
    }
    ?>
    </div>
    <div class="col-md-6">
<?php
if($providerUserEvent->totalCount){
    $gridColumnUserEvent = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'user.username',
                'label' => 'User'
            ],
                        'is_going',
            'comment',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerUserEvent,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-user-event']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('User Event'),
        ],
        'export' => false,
        'columns' => $gridColumnUserEvent
    ]);
}
?>

    </div>
</div>
