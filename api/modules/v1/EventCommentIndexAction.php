<?php

namespace app\api\modules\v1;

use app\api\base\BaseIndexAction;
use app\models\EventComment;
use soc\yii2helper\PHPHelper;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

/**
 * 6/26/23 b3t Add operator gt lt
 */
class EventCommentIndexAction extends BaseIndexAction
{
  public $modelClass = 'app\models\EventComment';

  /*public function __construct($id, $controller, $config = []) {
    parent::__construct($id, $controller, $config);
    $this->modelClass = 'app\models\EventComment';
  }

  public function init() {
    $this->modelClass = 'app\models\EventComment';
    parent::init();
  }*/
  public function prepareDataProvider() {
    $params = \Yii::$app->request->queryParams;
    unset($params['expand']);
    unset($params['_']);
    unset($params['page']);
    unset($params['page_size']);
    $maxrows = intval($params['maxrows'] ?? -1);//if no limit; default limit to 100
    unset($params['maxrows']);
    $qr_cols = $params['cols'] ?? false;//query columns
    unset($params['cols']);
    $qr = $params['qr'] ?? false;//query raw
    unset($params['qr']);

    if ($params['get_bot_only'] ?? false) {
      unset($params['get_bot_only']);
      $query = EventComment::find()->innerJoin('user', 'event_comment.created_by=user.id')
        ->where(['is_btt' => 1])->andWhere($params);
    } else {
      $query = parent::prepareQuery();
    }
    $page_size = $params['page_size'] ?? false;

    $dp = new ActiveDataProvider(
      [
        'query' => $query,
        'pagination' => [
          'pageSize' => $page_size,
        ],
      ]
    );
    $dp->setSort(['defaultOrder' => ['id' => SORT_DESC]]);
    if (YII_DEBUG) {
      $dp->pagination = false;
    }
//        return $query->createCommand()->rawSql;//zsdf
    return $dp;
  }

}

