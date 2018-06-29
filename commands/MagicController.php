<?php

namespace app\commands;

use app\models\Venue;
use GoogleMapsGeocoder;
use yii\console\Controller;
use yii\db\Exception;

/**
 * The behind the scenes magic happens here
 *
 * @author Brian Nguyen
 */
class MagicController extends Controller
{
    /**
     * Pull lat lng for all venues that don't have it yet
     * @throws Exception
     */
    public function actionPullLatLng()
    {
        define('GPLACE_KEY', 'AIzaSyBPeYraJ4H0BiuD1IQanQFlY1npx114ZpM');
        $venues_no_latlng = Venue::findAll(['lat' => null, 'lng' => null]);
        $geocoder = new GoogleMapsGeocoder();
        foreach ($venues_no_latlng as $venue) {
            $geocoder->setAddress($venue->pull_address());
        }

//        $affected_rows = $command->execute();
//        \Yii::warning("Warning, affected rows:". $affected_rows);
        echo "Updated for: ". $affected_rows . " records." . PHP_EOL;
        return 1;
    }
}
