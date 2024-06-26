<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 * @var app\models\user\Profile $model
 */

$this->title = 'Profile settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php //= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
  <div class="col-md-3">
    <?= $this->render('_menu') ?>
  </div>
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <?= Html::encode($this->title) ?>
      </div>
      <div class="panel-body">
        <?php $form = \yii\widgets\ActiveForm::begin([
          'id' => 'profile-form',
          'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
          ],
          'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
            'labelOptions' => ['class' => 'col-lg-3 control-label'],
          ],
          'enableAjaxValidation' => true,
          'enableClientValidation' => false,
          'validateOnBlur' => false,
        ]); ?>

        <?= $form->field($model, 'name')->label('Full Name') ?>

        <?php
        if (!empty($model->avatar)):
          ?>
          <div class="form-group ">
            <label class="col-lg-3 control-label" for="profile-display-avatar">Current profile
              picture</label>

            <div class="col-lg-9">
              <img src="<?= API_BASE ?>img/avatar/<?= \Yii::$app->user->id . '/' . $model->avatar ?>"
                   class="avatar"
                   alt="avatar">
            </div>
            <div class="col-sm-offset-3 col-lg-9">
              <div class="help-block"></div>
            </div>
          </div>
        <?php
        endif;
        ?>
        <!--                --><?php //= $form->field($model, 'avatarFile')->fileInput()->label('Upload new profile picture') ?>

        <div class="form-group">
          <div class="col-lg-offset-3 col-lg-9">
            <?= \yii\helpers\Html::submitButton('Save',
              ['class' => 'btn btn-block btn-success']) ?><br>
          </div>
        </div>

        <?php \yii\widgets\ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
