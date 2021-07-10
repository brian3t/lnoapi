<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

//use yii\widgets\ActiveForm;
//use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VenueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-venue-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2']] // spacing form field groups
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::class, [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => 'Choose User'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true, 'placeholder' => 'Type']) ?>

    <?= $form->field($model, 'address1')->textInput(['maxlength' => true, 'placeholder' => 'Address1']) ?>
    <!--<div class="form-group field-venuesearch-type">
      <label class="control-label" for="has_latlng">Has Lat/Lng</label>
      <input type="checkbox" id="has_latlng" class="form-control" name="has_latlng">

      <div class="help-block"></div>
    </div>-->
  <div class="form-group mr-2 highlight-addon hide-errors field-has_latlng-inl">
    <div class="custom-control custom-checkbox">
      <input type="checkbox" id="has_latlng" class="custom-control-input" name="has_latlng" value="1">
      <label class="has-star custom-control-label" for="has_latlng">Has LatLng</label>
    </div>
  </div>
    <?php /* echo $form->field($model, 'address2')->textInput(['maxlength' => true, 'placeholder' => 'Address2']) */ ?>

    <?php /* echo $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => 'City']) */ ?>

    <?php /* echo $form->field($model, 'state')->textInput(['maxlength' => true, 'placeholder' => 'State']) */ ?>

    <?php /* echo $form->field($model, 'zip')->textInput(['maxlength' => true, 'placeholder' => 'Zip']) */ ?>

    <?php /* echo $form->field($model, 'lat')->textInput(['maxlength' => true, 'placeholder' => 'Lat']) */ ?>

    <?php /* echo $form->field($model, 'lng')->textInput(['maxlength' => true, 'placeholder' => 'Lng']) */ ?>

    <?php /* echo $form->field($model, 'description')->textInput(['maxlength' => true, 'placeholder' => 'Description']) */ ?>

    <?php /* echo $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone']) */ ?>

    <?php /* echo $form->field($model, 'cost')->textInput(['maxlength' => true, 'placeholder' => 'Cost']) */ ?>

    <?php /* echo $form->field($model, 'website')->textInput(['maxlength' => true, 'placeholder' => 'Website']) */ ?>

    <?php /* echo $form->field($model, 'twitter')->textInput(['maxlength' => true, 'placeholder' => 'Twitter']) */ ?>

    <?php /* echo $form->field($model, 'facebook')->textInput(['maxlength' => true, 'placeholder' => 'Facebook']) */ ?>

    <?php /* echo $form->field($model, 'system_note')->textInput(['maxlength' => true, 'placeholder' => 'System Note']) */ ?>

    <?php /* echo $form->field($model, 'sdr_name')->textInput(['maxlength' => true, 'placeholder' => 'Sdr Name']) */ ?>

    <?php /* echo $form->field($model, 'source')->dropDownList([ 'sdr' => 'Sdr', 'ticketfly' => 'Ticketfly', 'other' => 'Other', 'unknown' => 'Unknown', 'reverb' => 'Reverb', 'tickmas' => 'Tickmas', ], ['prompt' => '']) */ ?>

    <?php /* echo $form->field($model, 'attr')->textInput(['placeholder' => 'Attr']) */ ?>
<div class="w-100">&nbsp;</div>
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary mr-1']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>

    <?php ActiveForm::end(); ?>

</div>
<br>
