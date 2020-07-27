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
     * bn Future: this will become part of YiiHelper
     * @param $params
     */
    public function set_query_params($params)
    {
        if (method_exists($this, 'set_query_params_post')) {
            $this->set_query_params_pre($params);
        }
        foreach ($params as $param => $val) {
            if (in_array($param, PHPHelper::$K_NON_SQL_PARAMS)) continue;
            if (is_array($val)) {
                $this->andWhere(['in', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_from')) {
                $param = str_replace('_from', '', $param);
                $this->andWhere(['>=', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_to')) {
                $param = str_replace('_to', '', $param);
                $this->andWhere(['<=', $param, $val]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_offset_bk')) {
                $param = str_replace('_offset_bk', '', $param);
                $val = intval($val);
                $this->andWhere(['>=', $param, new Expression("DATE_SUB(CURDATE(), INTERVAL $val DAY)")]);
                unset($params[$param]);
                continue;
            }
            if (PHPHelper::ends_with($param, '_offset_fwd')) {
                $param = str_replace('_offset_fwd', '', $param);
                $val = intval($val);
                $this->andWhere(['<=', $param, new Expression("DATE_ADD(CURDATE(), INTERVAL $val DAY)")]);
                unset($params[$param]);
                continue;
            }
            $this->andWhere([$param => $val]);
            unset($params[$param]);
        }

    }

    /**
     * class-specific code, POST set_query_params
     */
    public function set_query_params_pre($params)
    {
        if (isset($params['cen_lat'])) {
            $cen_lat = $params['cen_lat'];
            unset($params['cen_lat']);
        }
        if (isset($params['cen_lng'])) {
            $cen_lng = $params['cen_lng'];
            unset($params['cen_lng']);
        }
        if (!is_numeric($cen_lat) || !is_numeric($cen_lng)) return;
        if (isset($params['miles_away'])) {
            $mile = $params['miles_away'];
        }
        if (!is_numeric($mile)) return;
    }
/*
    function lat_lng_distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $radlat1 = M_PI * $lat1 / 180;
        $radlat2 = M_PI * $lat2 / 180;
        $theta = $lon1 - $lon2;
        $radtheta = M_PI * $theta / 180;
        $dist = sin($radlat1) * sin($radlat2) + cos($radlat1) * cos($radlat2) * cos($radtheta);
        $dist = acos($dist);
        $dist = $dist * 180 / M_PI;
        $dist = $dist * 60 * 1.1515;
        if (! $unit) {
            $unit = 'N';
        }
        if ($unit == "K") {
            $dist = $dist * 1.609344;
        }
        if ($unit == "N") {
            $dist = $dist * 0.8684;
        }
        return $dist;
    }

//    "Given a distance north, return the change in latitude."
    function change_in_latitude($miles){
        return (miles / earth_radius) * radians_to_degrees;
    }

    function distance_to_latlng($dist)
    {
        $earth_radius = 3960.0
$degrees_to_radians = math . pi / 180.0
$radians_to_degrees = 180.0 / math . pi

def
def change_in_longitude(latitude, miles):
    "Given a latitude and a distance west, return the change in longitude."
    # Find the radius of a circle around the earth at given latitude.
    r = earth_radius * math . cos(latitude * degrees_to_radians)
    return (miles / r) * radians_to_degrees

    }
*/
}
