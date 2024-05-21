<?php

namespace app\api\modules\v1;

use app\models\EventQuery;
use soc\yii2helper\PHPHelper;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

/**
 * 6/26/23 b3t Add operator gt lt
 */
class EventIndexAction extends IndexAction
{
  // prepare and return a data provider for "index" action
  public function prepareDataProvider()
  {
    $params = \Yii::$app->getRequest()->getQueryParams();
    PHPHelper::api_param_pre($params);
//        $date_from = $params['date_from'] ?? -3;
    $two_weeks_ago = (new \DateTime())->sub(new \DateInterval('P14D'))->format('Y-m-d h:i:s');
    $date_from = $params['date_from'] ?? $two_weeks_ago;//two weeks ago
    unset($params['date_from']);
    $three_weeks_ahead = (new \DateTime())->add(new \DateInterval('P21D'))->format('Y-m-d h:i:s');
    $date_to = $params['date_to'] ?? $three_weeks_ahead;
    unset($params['date_to']);
//        $searchModel = new EventSearch();
//        $searchModel->search($params);
//        $dataProvider = $searchModel->search($params);
    unset($params['page']);
    $page_size = $params['page_size'] ?? 50;//default 50
    unset($params['page_size']);
    $limit = $params['limit'] ?? 50;
    unset($params['limit']);
    foreach ($params as $col_and_operator => $cond) {
      if (!str_contains($col_and_operator, '__')) continue;
      [$col_only, $operator] = explode('__', $col_and_operator);
      switch ($operator) {
        case 'gt':
        {
          //future, this can replace the _from and _to from
          break;
        }
        default:
          break;
      }
    }
    /*$page_num = $params['page_num'] ?? null;
    $pagination = false;
    if ($page_num > 0){
      $pagination = ['pageSize' => $page_size];
    }*/
    $query = new EventQuery($this->modelClass);
    $query->limit = $limit;
    $query->set_query_params($params);
    $query->andWhere(['>=', 'start_datetime_utc', $date_from]);
    $query->andWhere(['<=', 'start_datetime_utc', $date_to]);
    // get the total number of articles (but do not fetch the article data yet)
//        $count = $query->count();
    $query_sql = $query->createCommand()->getRawSql();
    $dp = new ActiveDataProvider(
      [
        'query' => $query,
        'pagination' => [
          'pageSize' => $page_size,
//                    'totalCount' => $count
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
