<?php
/**
 * Event Comment Controller REST
 */

namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\EventComment;
use soc\yii2helper\PHPHelper;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

require_once realpath(dirname(__DIR__, 4)) . "/models/constants.php";

class EventCommentController extends BaseActiveController
{
  // We are using the regular web app modules:
  public $modelClass = 'app\models\EventComment';

  public function actions() {
    $actions = parent::actions();
    // override the default REST actions
    unset($actions['index']);
    $actions['index']['class'] = 'app\api\modules\v1\EventCommentIndexAction';
    return $actions;
  }

}
