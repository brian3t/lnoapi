<?php

namespace app\api\base;

use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class BaseIndexAction extends IndexAction
{
    /**
     * Prepares the data provider that should return the requested collection of the models.
     * Acceptable params:
     * ?qa=query_array, JSON string of queries in array format, such as
     * [
     *  ['not', ['column' => value]]
     * ]
     * Read Yii2 where condition for format
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider() {
        $modelClass = new $this->modelClass();
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $params = \Yii::$app->request->queryParams;
        unset($params['expand']);
        unset($params['_']);
        unset($params['page']);
        $page_size = $params['page_size'] ?? false;
        unset($params['page_size']);
        $qr = $params['qr'] ?? false;//query raw
        unset($params['qr']);
        try {
            $qr_array = json_decode($qr, JSON_OBJECT_AS_ARRAY);
        } catch (\Exception $e) {
            $qr_array = false;
        }
        $dp_query = $modelClass::find()->where($params);
        if ($qr_array) {
            foreach ($qr_array as $qr_cond) {
                $dp_query = $dp_query->andWhere($qr_cond);//e.g. ['not', ['column' => value]]
            }
        }
        $ap = new ActiveDataProvider([
            'query' => $dp_query,
        ]);
        if ($page_size) {
            $ap->pagination->setPageSize($page_size);
        }
        return $ap;
    }

}
