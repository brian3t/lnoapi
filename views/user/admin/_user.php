<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

/**
 * @var yii\widgets\ActiveForm $form
 * @var \Da\User\Model\User $user
 */
?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'first_name')->textInput(['maxlength' => 80]) ?>
<?= $form->field($user, 'last_name')->textInput(['maxlength' => 80]) ?>
<?= $form->field($user, 'password')->passwordInput() ?>
<?php
  $profile_img_filename = strval($user->id);
  $profile_img_filename = str_pad($profile_img_filename, 4, '0', STR_PAD_LEFT);
  $profile_img_filename .= '.jpg';
?>
<img src="https://socalappsolutions.com/p/<?= $profile_img_filename ?>" alt="profileimg">
