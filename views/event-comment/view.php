<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\EventComment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Event Comment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-comment-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Event Comment'.' '. Html::encode($this->title) ?></h2>
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
            'attribute' => 'editedBy.username',
            'label' => 'Edited By',
        ],
        'edited_at',
        'comment',
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
        'unconfirmed_email',
        'registration_ip',
        'flags',
        'confirmed_at',
        'blocked_at',
        'last_login_at',
        'last_login_ip',
        'auth_tf_key',
        'auth_tf_enabled',
        'auth_tf_type',
        'auth_tf_mobile_phone',
        'password_changed_at',
        'gdpr_consent',
        'gdpr_consent_date',
        'gdpr_deleted',
        'first_name',
        'last_name',
        'note',
        'phone_number_type',
        'phone_number',
        'birthdate',
        'favorite_genres',
        'favorite_venue_types',
        'twitter_id',
        'facebook_id',
        'instagram_id',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country',
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
        'unconfirmed_email',
        'registration_ip',
        'flags',
        'confirmed_at',
        'blocked_at',
        'last_login_at',
        'last_login_ip',
        'auth_tf_key',
        'auth_tf_enabled',
        'auth_tf_type',
        'auth_tf_mobile_phone',
        'password_changed_at',
        'gdpr_consent',
        'gdpr_consent_date',
        'gdpr_deleted',
        'first_name',
        'last_name',
        'note',
        'phone_number_type',
        'phone_number',
        'birthdate',
        'favorite_genres',
        'favorite_venue_types',
        'twitter_id',
        'facebook_id',
        'instagram_id',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country',
    ];
    echo DetailView::widget([
        'model' => $model->editedBy,
        'attributes' => $gridColumnUser    ]);
    ?>
</div>
