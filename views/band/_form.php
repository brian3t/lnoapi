<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Band */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'BandComment', 
        'relID' => 'band-comment', 
        'value' => \yii\helpers\Json::encode($model->bandComments),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'BandEvent', 
        'relID' => 'band-event', 
        'value' => \yii\helpers\Json::encode($model->bandEvents),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'BandFollow', 
        'relID' => 'band-follow', 
        'value' => \yii\helpers\Json::encode($model->bandFollows),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'BandRate', 
        'relID' => 'band-rate', 
        'value' => \yii\helpers\Json::encode($model->bandRates),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="band-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'user_id')->label('Band administrator account')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
        'options' => ['placeholder' => 'Choose User'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => true, 'placeholder' => 'Paste url to logo image here. Use image hosting services such as imgur or wikimedia ']) ?>

    <!--?= $form->field($model, 'lno_score')->textInput(['maxlength' => true, 'placeholder' => 'Lno Score']) --?>

    <?= $form->field($model, 'type')->dropDownList([ 'covers' => 'Covers', 'originals' => 'Originals', 'covers & originals' => 'Covers & originals', 'unknown' => 'Unknown', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'similar_to')->textInput(['maxlength' => true, 'placeholder' => 'Similar To']) ?>

    <?= $form->field($model, 'hometown_city')->textInput(['maxlength' => true, 'placeholder' => 'Hometown City']) ?>

    <?= $form->field($model, 'hometown_state')->textInput(['maxlength' => true, 'placeholder' => 'Hometown State']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true, 'placeholder' => 'Website']) ?>

    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true, 'placeholder' => 'Facebook']) ?>

    <?= $form->field($model, 'twitter')->textInput(['maxlength' => true, 'placeholder' => 'Twitter']) ?>

    <?php
    $forms = [
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('BandComment'),
            'content' => $this->render('_formBandComment', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->bandComments),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('BandEvent'),
            'content' => $this->render('_formBandEvent', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->bandEvents),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('BandFollow'),
            'content' => $this->render('_formBandFollow', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->bandFollows),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('BandRate'),
            'content' => $this->render('_formBandRate', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->bandRates),
            ]),
        ],
    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => true,
            'sideways' => true,
            'enableCache' => false,
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
