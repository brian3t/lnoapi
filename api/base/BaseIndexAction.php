<?php

namespace app\api\base;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\rest\IndexAction;

class BaseIndexAction extends IndexAction
{
  /**
   * Prepares the data provider that should return the requested collection of the models.
   * Acceptable params:
   * ?qr=query_request, JSON string of queries in array format, such as
   * {'ytlink_first':null}
   *
   * or a stringified object as a result of JSON.stringify(filter)
   * , such as ?qr=[[%22not%22,{%22ytlink_first%22:null}]]
   *
   * It will be converted to Yii2 where condition, such as
   * [
   *  ['not', ['column' => value]],
   *  ['col2' => 'val2']
   * ]
   * Read Yii2 where condition for format
   *
   * Also accept ?cols = col1,col2,col3
   *
   * @return ActiveDataProvider
   */
  protected function prepareDataProvider() {
    $dp_query = $this->prepareQuery();

    $ap = new \yii\data\ActiveDataProvider([
      'query' => $dp_query,
    ]);
    $page_size = $params['page_size'] ?? false;

    if ($page_size) {
      $ap->pagination->setPageSize($page_size);
    }
    $maxrows = intval($params['maxrows'] ?? -1);//if no limit; default limit to 100
    if (is_int($maxrows) && $maxrows !== -1) {
      $ap->pagination->setPageSize($maxrows);
    }

    return $ap;
  }

  protected function prepareQuery(): ActiveQuery {
    $modelClass = new $this->modelClass();

    /* @var $modelClass \yii\db\ActiveRecord */
    $params = \Yii::$app->request->queryParams;
    $params = array_filter($params, function ($param) {
      return (!(str_starts_with($param, 'xq_') ||
        str_ends_with($param, '_from') ||
        str_ends_with($param, '_to') ||
        str_ends_with($param, '_offset_bk') ||
        str_ends_with($param, '_offset_fwd')
      )
      );//remove extra query params
    }, ARRAY_FILTER_USE_KEY);
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
    $qr = str_replace("'", "\"", $qr);
    $qr = str_replace("\\\"", "\"", $qr);//JSON.stringify created redundant \"
    try {
      $qr_array = json_decode($qr, JSON_OBJECT_AS_ARRAY);
    } catch (\Exception $e) {
      $qr_array = false;
    }
    $dp_query = $modelClass::find()->where($params);
    if ($qr_array) {
      if ($qr_array[0] == 'not') {
        $qr_array = [$qr_array];//form client side we can pass 1 single condition. Here we convert it into a list of conditions
      }
      foreach ($qr_array as $qr_cond) {//loop through the list of conditions
        $dp_query = $dp_query->andWhere($qr_cond);//e.g. ['not', ['column' => value]]
      }
    }
    //end query request

    if ($qr_cols) {
      $qr_cols = explode(',', $qr_cols);
    }

    if ($qr_cols) {
      $dp_query->select($qr_cols);
    }
    if (is_int($maxrows) && $maxrows !== -1) {
      $dp_query->limit($maxrows);
    }
    return $dp_query;
  }

}
