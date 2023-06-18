<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\db\Exception;

/**
 * The infamous maid
 *
 * @author Brian Nguyen
 */
class BotController extends Controller
{
  /**
   * Clean up data
   * Clean up sdr systemnote url
   */
  public function actionAsdf() {
    $db = \Yii::$app->db;
    echo "Asdf" . PHP_EOL;
    return 1;
  }

  function genRandString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }

    return $randomString;
  }

  /**
   * Clean up data
   * Clean up sdr systemnote url
   * @throws Exception
   */
  public function actionGenusers() {
    $db = \Yii::$app->db;
    //make sure id goes from 6 upwards; up to 223 times
    $now = new \DateTime('now');
    $now = $now->getTimestamp();
    for ($i = 1; $i <= 2; $i++) {
      $user = new \app\models\base\User();
      $user->username = $this->genRandString(rand(8, 12));
      $mail_len = rand(8, 12);
      $user->email = $this->genRandString($mail_len);
      $user->is_btt = 1;
      $user->confirmed_at = $now;
      $user->auth_key = '7fjsLFp_FL3qZI4EqJfw3-BxuMIgbwxF';
      $user->password_hash = '$2y$10$A/CCCDQUrzNrfKECQNs2a.Sf1TtwNR35aXbBecz0moAChE/25kvnK';
      $user->save();
      $err = $user->errors;
      if ($err){
        echo "error saving user: " . json_encode($err);
      }
    }
    echo "Gen users done" . PHP_EOL;
    return 1;
  }

}


