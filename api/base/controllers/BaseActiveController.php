<?php

namespace app\api\base\controllers;

use app\api\base\RequestBody;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class BaseActiveController
 * @package app\api\base\controllers
 * @property RequestBody $requestbody
 * @property string $message
 */
class BaseActiveController extends ActiveController
{
  public $requestbody;
  public $message;

  public function init() {
    parent::init();
  }

  public function behaviors() {
    $behaviors = parent::behaviors();
    $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

    return ArrayHelper::merge([
      [
        'class' => Cors::className(),
        'cors' => [
          'Origin' => ['*'],
          'Access-Control-Request-Methods' => ['GET', 'POST', 'OPTIONS', 'DELETE', 'PUT', 'PATCH'],
        ],
      ],
      // 'authenticator' => ['class' => HttpBasicAuth::className()]
    ], $behaviors);
  }

  public function actions() {
    $actions = parent::actions();
    return array_replace_recursive($actions, [
      'index' => [
        'class' => 'app\api\base\BaseIndexAction',
        'modelClass' => $this->modelClass,
        'checkAccess' => [$this, 'checkAccess'],
      ],
    ]);
  }

  /** Error with an error message
   * @param $err_mess
   * @param int $status_code
   */
  protected function err($err_mess, $status_code = 500) {
    Yii::$app->getResponse()->statusCode = $status_code;
    return $err_mess;
  }

  /**
   * @throws InvalidConfigException
   * @throws BadRequestHttpException
   */
  public function beforeAction($action) {
    $headers = Yii::$app->request->headers;
    $method = Yii::$app->request->method;
    $userid = intval($headers->get('userid'));
    $body_params = Yii::$app->request->getBodyParams();

    if ($userid > 0) {
      if ($method == 'POST') $body_params['user_id'] = $userid;
      if ($method == 'GET') $_GET['user_id'] = $userid;
    }
    $created_by = intval($headers->get('created_by'));
    if ($created_by > 0) {
      if ($method == 'POST') $body_params['created_by'] = $created_by;
      if ($method == 'GET') $_GET['created_by'] = $created_by;
    }

    if ($method == 'POST') Yii::$app->request->setBodyParams($body_params);

    if (Yii::$app->request->method !== 'GET') return parent::beforeAction($action);
    $query_params = Yii::$app->request->getQueryParams();
    return parent::beforeAction($action);
  }
}
