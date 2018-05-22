<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Venue */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Venue', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venue-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Venue'.' '. Html::encode($this->title) ?></h2>
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
        [
            'attribute' => 'user.username',
            'label' => 'User',
        ],
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'description',
        'phone',
        'cost',
        'website',
        'twitter',
        'facebook',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    
    <div class="row">
<?php
if($providerEvent->totalCount){
    $gridColumnEvent = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'user.username',
                'label' => 'User'
            ],
                        'date',
            'start_time',
            'end_time',
            'name',
            'description:ntext',
            'cost',
            'is_charity',
            'twitter',
            'facebook',
            'website',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerEvent,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-event']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Event'),
        ],
        'export' => false,
        'columns' => $gridColumnEvent
    ]);
}
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
        'model' => $model->createdBy,
        'attributes' => $gridColumnUser    ]);
    ?>
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
</div>
