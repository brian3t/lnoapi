<?php

namespace app\models;

use soc\yii2helper\PHPHelper;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Event]].
 *
 * @see Event
 * @property array $query_params
 * @property \yii\db\ActiveRecord $model
 */
class EventQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/
    private static $model;
    /**
     * @inheritdoc
     * @return Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function init()
    {
        $model_class_name = $this->modelClass;
        $this::$model = ($model_class_name)::getTableSchema();
        $this->alias('e');
    }

    /**
     * Set query params
     * This method also prepares all the necessary where_s
     * e.g.: date_from, gt, date_to, date_offset_bk
     * bn Future: this will become part of Core ( YiiHelper )
     * @param $params
     */
    public function set_query_params($params)
    {
        if (method_exists($this, 'set_query_params_pre')) {
            $this->set_query_params_pre($params);
        }
        foreach ($params as $param => $val) {
            if (in_array($param, PHPHelper::$K_NON_SQL_PARAMS)) continue;
            if (is_array($val)) {
                $this->andWhere(['in', "`e`.$param", $val]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '__from')) {
                $param = str_replace('__from', '', $param);
                $this->andWhere(['>=', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (str_ends_with($param, '__gt')) {
                $param = str_replace('__gt', '', $param);
                $this->andWhere(['>', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (str_ends_with($param, '__lt')) {
                $param = str_replace('__lt', '', $param);
                $this->andWhere(['<', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '__to')) {
                $param = str_replace('__to', '', $param);
                $this->andWhere(['<=', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '__offset_bk')) {
                $param = str_replace('__offset_bk', '', $param);
                $val = intval($val);
                $this->andWhere(['>=', $param, new Expression("DATE_SUB(CURDATE(), INTERVAL $val DAY)")]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '__offset_fwd')) {
                $param = str_replace('__offset_fwd', '', $param);
                $val = intval($val);
                $this->andWhere(['<=', $param, new Expression("DATE_ADD(CURDATE(), INTERVAL $val DAY)")]);
                unset($params[$param]);
                continue;
            }
            if (!isset($this::$model->columns[$param])){
                unset($params[$param]);
                continue;
            }
            $this->andWhere(["`e`.$param" => $val]);
            unset($params[$param]);
        }

    }

    /**
     * class-specific code, pre set_query_params
     */
    public function set_query_params_pre(&$params)
    {
        if (isset($params['cen_lat'])) {
            $cen_lat = $params['cen_lat'];
            unset($params['cen_lat']);
        }
        if (isset($params['cen_lng'])) {
            $cen_lng = $params['cen_lng'];
            unset($params['cen_lng']);
        }
        if (isset($params['miles_away'])) {
            $mile = $params['miles_away'];
            unset($params['miles_away']);
        }
        if (! isset($cen_lat) || ! isset($cen_lng) || ! is_numeric($cen_lat) || ! is_numeric($cen_lng)) return;
        if (!isset($mile) || ! is_numeric($mile)) return;
        $delta = PHPHelper::rev_haversin_simple($mile);
        $this->joinWith(['venue as v' => function ($q) use ($cen_lat, $cen_lng, $delta) {
            $q->andFilterWhere(['between', '`v`.lat', $cen_lat - $delta, $cen_lat + $delta]);
            $q->andFilterWhere(['between', '`v`.lng', $cen_lng - 2 * $delta, $cen_lng + 2 * $delta]);
        }]);

    }
}
