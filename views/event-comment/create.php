<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EventComment */

$this->title = 'Create Event Comment';
$this->params['breadcrumbs'][] = ['label' => 'Event Comment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
