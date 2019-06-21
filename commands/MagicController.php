<?php

namespace app\commands;

/** Begin Google custom search constants */
define('GOOGLE_CUSTOM_SEARCH_API_KEY', 'AIzaSyAJZ-L82eenDxcIogZihEBXWxd4Noi7CUE');
define('SEARCH_ENGINE_ID', '016413111986528629647:bssckogzvpk');
/** End Google custom search constants */

use app\models\Venue;
use GoogleMapsGeocoder;
use Goutte\Client as GoutteClient;
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
        $affected_rows = 0;
        foreach ($venues_no_latlng as $venue) {
            $full_addr = $venue->pull_address();
            if (empty($full_addr)){
                continue;
            }
            $geocoder->setAddress($full_addr);

            $latlng = $geocoder->geocode(true);
            if ($latlng['status'] == 'ZERO_RESULTS'){
                continue;
            }
            if ($latlng['status'] == 'OK'){
                $geometry = array_shift($latlng['results'])['geometry']['location'];
                $venue->setAttributes(['lat' => $geometry['lat'], 'lng'=>$geometry['lng']]);
                $affected_rows += $venue->save();
            }
        }

//        $affected_rows = $command->execute();
//        \Yii::warning("Warning, affected rows:". $affected_rows);
        echo "Updated for: ". $affected_rows . " records." . PHP_EOL;
        return 1;
    }

    /**
     * Pull genre from google
     * For bands that doesn't have genre populated
     */
    public function actionPullGenreFromGoogle(){
        $sql = "SELECT `name` FROM lno.band WHERE genre IS NULL OR genre = ''";
        $guzzle_client = new GoutteClient();
        $base_url = 'https://google.com?q=';
        $bands_wo_genres = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_COLUMN);
        foreach ($bands_wo_genres as $bands_wo_genre) {
            $url = $base_url . "$bands_wo_genre+genre";
            $crawler = $guzzle_client->request('get', $url);
            $details = $crawler->filter('div.mnr-c');
$b=1;
        }
        $a = 1;
    }
}
