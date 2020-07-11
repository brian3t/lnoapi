<?php

namespace app\models;

use usv\yii2helper\PHPHelper;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Event]].
 *
 * @see Event
 * @property array $query_params
 */
class EventQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

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
        $a = 1;
    }

    /**
     * Set query params
     * This method also prepares all the neccessary where_s
     * @param $params
     */
    public function set_query_params($params)
    {
        foreach ($params as $param => $val) {
            if (in_array($param, PHPHelper::$K_NON_SQL_PARAMS)) continue;
            if (is_array($val)) {
                $this->andWhere(['in', $param, $val]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_from')) {
                $param = str_replace('_from', '',$param);
                $this->andWhere(['>=', $param, $val]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_to')) {
                $param = str_replace('_to', '',$param);
                $this->andWhere(['<=', $param, $val]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_start')) {
                $param = str_replace('_start', '',$param);
                $val = intval($val);
                $this->andWhere(['>=', $param, new Expression("DATE_SUB(CURDATE(), INTERVAL $val DAY)")]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_end')) {
                $param = str_replace('_end', '',$param);
                $val = intval($val);
                $this->andWhere(['<=', $param, new Expression("DATE_ADD(CURDATE(), INTERVAL $val DAY)")]);
                continue;
            }
            $this->andWhere([$param => $val]);
        }
    }

}
