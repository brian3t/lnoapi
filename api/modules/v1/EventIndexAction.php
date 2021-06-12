<?php

namespace app\api\modules\v1;

use app\models\EventQuery;
use usv\yii2helper\PHPHelper;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class EventIndexAction extends IndexAction
{

    // prepare and return a data provider for "index" action
    public function prepareDataProvider() {
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
        $page_size = $params['page_size'] ?? false;
        unset($params['page_size']);
        $limit = $params['limit'] ?? 30;
        unset($params['limit']);
        $query = new EventQuery($this->modelClass);
        $query->limit = $limit;
        $query->set_query_params($params);
        $query->andWhere(['>=', 'start_datetime_utc', $date_from]);
        $query->andWhere(['<=', 'start_datetime_utc', $date_to]);
        // get the total number of articles (but do not fetch the article data yet)
//        $count = $query->count();
        $dp = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => $page_size ?? 20,
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
