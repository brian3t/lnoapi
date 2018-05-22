<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Band */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Band', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="band-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Band'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'name',
        [
            'attribute' => 'user.username',
            'label' => 'User',
        ],
        'logo',
        'lno_score',
        'type',
        'similar_to',
        'hometown_city',
        'hometown_state',
        'description:ntext',
        'website',
        'facebook',
        'twitter',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'email',
        'password_hash',
        'auth_key',
        'confirmed_at',
        'unconfirmed_email',
        'blocked_at',
        'registration_ip',
        'flags',
        'first_name',
        'last_name',
        'note',
        'phone_number_type',
        'phone_number',
        'birthdate',
        'birth_month',
        'birth_year',
        'favorite_genres',
        'favorite_venue_types',
        'website_url',
        'twitter_id',
        'facebook_id',
        'instagram_id',
        'google_id',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country',
        'last_login_at',
    ];
    echo DetailView::widget([
        'model' => $model->user,
        'attributes' => $gridColumnUser    ]);
    ?>
    
    <div class="row">
<?php
if($providerBandComment->totalCount){
    $gridColumnBandComment = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        'comment',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerBandComment,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-band-comment']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Band Comment'),
        ],
        'export' => false,
        'columns' => $gridColumnBandComment
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerBandEvent->totalCount){
    $gridColumnBandEvent = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'event.name',
                'label' => 'Event'
            ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerBandEvent,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-band-event']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Band Event'),
        ],
        'export' => false,
        'columns' => $gridColumnBandEvent
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerBandFollow->totalCount){
    $gridColumnBandFollow = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'user.username',
                'label' => 'User'
            ],
                ];
    echo Gridview::widget([
        'dataProvider' => $providerBandFollow,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-band-follow']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Band Follow'),
        ],
        'export' => false,
        'columns' => $gridColumnBandFollow
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerBandRate->totalCount){
    $gridColumnBandRate = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'user.username',
                'label' => 'User'
            ],
                        'rate',
            'comment',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerBandRate,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-band-rate']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Band Rate'),
        ],
        'export' => false,
        'columns' => $gridColumnBandRate
    ]);
}
?>

    </div>
</div>
