<?php
/**
 * Created by IntelliJ IDEA.
 * User: tri
 * Date: 8/10/16
 */

namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\controllers\user\SettingsController;
use app\models\User;
use Yii;
use yii\console\Exception;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends BaseActiveController
{
  public $modelClass = 'app\models\User';


  protected function verbs()
  {
    $verbs = parent::verbs();
    array_push($verbs['update'], 'POST');
  }

  public function actions()
  {
    $actions = parent::actions();

    // disable the default REST actions
    unset($actions['update']);

    return $actions;
  }

  public function actionLogin()
  {
    // Yii::$container
    $response = ['status' => 'failed'];
    $module = Yii::$app->getModule('user');
    if ($module == null) {
      $response['message'] = 'Module user not loaded';
      return $response;
    }
    $settingsController = $module->createController('settings/profile');
    if (!$settingsController || count($settingsController) != 2) {
      $response['message'] = 'Settings controller not found';
    }
    $settingsController = $settingsController[0];
    /** @var SettingsController $settingsController */

    /** @var LoginForm $model */
    $model = Yii::createObject(LoginForm::className());
    $search_model = Yii::createObject(UserSearch::className());
    /** @var UserSearch $search_model */

    if ($model->load(Yii::$app->getRequest()->post())) {
      $finder = $settingsController->getFinder();
      /** @var Finder $finder */
      $user_found = $finder->findUserByEmail($model->login);
      if (is_null($user_found)) {
        throw new UnauthorizedHttpException('Username does not exist');
      }
      if ($model->login()) {
        $cuser = Yii::$app->user->identity;
        $profile = $settingsController->actionApiProfile();
        $response['status'] = 'ok';
        $response['id'] = $user_found->id;
      } else {
        throw new UnauthorizedHttpException('Wrong password');
      }
    }
    return $response;

  }

  public function actionUpdate()
  {
    $request = Yii::$app->request;
    $entity_body = Yii::$app->request->post();
    $id = $request->get('id');

    $user = \app\models\User::findOne($id);
    if ($user->id != $id) {
      return "Cannot find this user";
    }
    if (isset($entity_body['union_memberships'])) {
      if (count($entity_body['union_memberships']) == 0) {
        $entity_body['union_memberships'] = '';
      } else {
        $entity_body['union_memberships'] = implode(',', $entity_body['union_memberships']);
      }
    }
    if ($user->loadAll(['User' => $entity_body]) && $user->save(false)) {
      return json_encode($user->getAttributes());
    } else {
      return json_encode($user->errors);
    }

//        $order_new = $this->requestbody->order;
//
//        $order = Order::find()->where(['retailops_order_id' => $order_new->retailops_order_id]);
//        if (!$order->exists()) {
//            $this->rop_response->add_error('Can not find order using retailops_order_id');
//            $order = Order::find()->where(['channel_refnum' => $this->requestbody->order->channel_order_refnum]);
//            if (!$order->exists()) {
//                $this->rop_response->add_error('Can not find order using channel_order_refnum');
//            }
//        }
//        if ($order->exists()) {
//            $order = $order->one();/* @var Order $order */;
//
//            $order->unlinkAll('orderReturns', true);
//            $rmas = ArrayHelper::toArray($this->requestbody->rmas);
//            foreach ($rmas as $rma_data) {
//                $return_items = $rma_data['items'];
//                unset($rma_data['items']);
//
//                $rma = new OrderReturn();
//                $rma->loadAll(['OrderReturn' => $rma_data, 'OrderReturnItem' => $return_items]);
//                $rma->link('order', $order);
//                if (!$rma->saveAll()) {
//                    $this->rop_response->pull_error($rma);
//                };
//            }
//
//            //updating order
//            $order->setAttribute('product_total', $order_new->grand_total);
//            $order->save();
//            $order->unlinkAll('orderShipments', true);
//        }
//
//
//        return $this->rop_response->print();
  }

  /** Sign up using fullname, email, password
   * 5/25/20 BN using vendor/2amigos/yii2-usuario
   * Send params in request body:
   * {
   *  append_id : username = username + new_gen_id. If guest_mode, this is a must
   *  password: if not exist (guest_mode), it's reverse of username, but without the ID appendix
   *  email: if guest_mode: someids@yahoo.com . User can claim their email later.
   * }
   */
  public function actionSignup()
  {
    $vars = Yii::$app->getRequest()->getBodyParams();
    $now = new \DateTime();
    $username = $vars['username'] ?? ('guest' . $now->format('ymd'));
    $guest_mode = str_starts_with($username, 'guest');
    $append_id = $vars['append_id'] ?? $guest_mode;
    $email = $vars['email'] ?? ($guest_mode ? 'someids@yahoo.com' : null);
    $password = $vars['password'] ?? null;
    if (!$password) {//guest mode
      $password = strrev($username);
    }
    if (strlen($password) < 6) return $this->err("Error: password must be at least 6 characters");
    if (strlen($username) < 3) return $this->err("Error: username must be at least 3 characters");
    $exists = \app\models\User::findBySql("SELECT 1 FROM user WHERE username = '${username}' OR email = '${email}' ")->exists();
    if ($exists) {
      return $this->err('Error: username or email already exists', 400);
    }
    $oldApp = \Yii::$app;
    new \yii\console\Application([
        'id' => 'Command runner',
        'name' => 'SD Events',
        'basePath' => '@app',
        'components' => [
          'db' => $oldApp->db,
          'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
              'class' => 'Swift_SmtpTransport',
              'host' => 'smtp.gmail.com',
              'username' => 'ceo@socalappsolutions.com',
              'password' => 'wmqtmhewukftlixm',
              'port' => 587,
              'encryption' => 'tls',
            ],
          ],
          'urlManager' => [
            'scriptUrl' => $oldApp->getHomeUrl(),
            'hostInfo' => ''
          ]
        ],
        'modules' => [
          'user' => \Da\User\Module::class,
        ],
      ]
    );
    try {
      $result = Yii::$app->runAction('user/create/index', [$email, $username, $password, 'interactive' => false]);
    } catch (Exception $e) {
      return $this->err('fail user/create/index');
    }
    \Yii::$app = $oldApp;
    $user = User::findOne(['username' => $username]);
    if (!($user instanceof User)) {
      return $this->err('Error creating user. Please try again or contact our support. Thank you');
    }
    if ($append_id === true) {
      $user->username = $user->username . $user->id;
    }
    if ($guest_mode) $user->email = $user->username . '@notconfirmed.com';
    $user->registration_ip = Yii::$app->request->userIP;
    try {
      $user->save();
    } catch (\Exception $e) {
      Yii::error("uc 209 " . $e->getMessage() . json_encode($user->errors));
      //future report error
    }
    $profile = \app\models\Profile::findOrCreate(['user_id' => $user->id]);
    if ($profile instanceof \app\models\Profile) {
      $profile->name = $vars['name'] ?? $user->username;
      try {
        $profile->save();
      } catch (\Exception $exception) {
        Yii::error($exception);
      }
    }
    return ['username' => $user->username, 'pw' => $password];
  }

  /**
   * Sign in via API
   * username in param, pw in header
   */
  public function actionSignin()
  {
    $username = Yii::$app->request->get('username');
    $pw = Yii::$app->request->headers->get('pw');
    if (!$pw || !$username) return $this->err('Must provide username in param, password in header pw', 401);
    $identity = User::findOne(["username" => $username]);
    if (!($identity instanceof User)) return $this->err("Username not found. Please double check.", 401);
    $hash = $identity->password_hash;
    $login_result = Yii::$app->getSecurity()->validatePassword($pw, $hash);
    if (!$login_result) return $this->err('Wrong password, please try again', 401);
    return ['msg' => 'Login validated', 'id' => $identity->id];
  }
}
