<?php

namespace app\commands;

/** Begin Google custom search constants */
define('GOOGLE_CUSTOM_SEARCH_API_KEY', 'AIzaSyAJZ-L82eenDxcIogZihEBXWxd4Noi7CUE');
define('SEARCH_ENGINE_ID', '016413111986528629647:bssckogzvpk');

/** End Google custom search constants */

use app\models\Journal;
use app\models\Venue;
use Geocoder\Query\GeocodeQuery;
use Goutte\Client as GoutteClient;
use yii\console\Controller;


/**
 * The behind the scenes magic happens here
 *
 * @author Brian Nguyen
 */
class MagicController extends Controller
{
    /**
     * Pull lat lng for all venues that don't have it yet
     * @param string $limit e.g. ' LIMIT 5 '
     * @return bool
     */
    public function actionPullLatLng(string $limit = ''): bool {
        $limit = ' LIMIT 5 ';//asdf
        define('GPLACE_KEY', 'AIzaSyBPeYraJ4H0BiuD1IQanQFlY1npx114ZpM');
        $venues_no_latlng = Venue::findBySql("SELECT * FROM venue 
            WHERE lat IS NULL AND lng IS NULL AND address1 IS NOT NULL $limit ")->all();
        $httpClient = new \Http\Adapter\Guzzle7\Client();
        $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, null, GPLACE_KEY);
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

//        $result = $geocoder->reverseQuery(ReverseQuery::fromCoordinates(...));
        $affected_rows = 0;
        $failed_rows = 0;
        echo sizeof($venues_no_latlng) . " venues with no lat/lng:" . PHP_EOL;
        foreach ($venues_no_latlng as $venue) {
            if ($failed_rows > 20) {
                echo "Failed 20 rows, exiting";
                break;
            }
            $full_addr = $venue->pull_address();
            if (empty($full_addr)) {
                continue;
            }

            $geo_result = false;
            try {
                $geo_result = $geocoder->geocodeQuery(GeocodeQuery::create($full_addr));
            } catch (\Geocoder\Exception\Exception $e) {
                echo $e->getMessage();
            }
            if (! $geo_result instanceof \Geocoder\Model\AddressCollection) {
                $failed_rows++;
                continue;
            }
            if ($geo_result->count() < 1) {
                echo 'No result, skipping..' . PHP_EOL;
                continue;
            }
            $geo_result = $geo_result->first()->getCoordinates();
            $lat = $geo_result->getLatitude();
            $lng = $geo_result->getLongitude();

            if (! is_numeric($lat)) {
                echo 'Bad lat';
                echo PHP_EOL;
                $failed_rows++;
                continue;
            }
            $now = (new \DateTime())->format('Y-m-d H:i:s');
            $venue->setAttributes(['lat' => $lat, 'lng' => $lng,'gmap_last_utc' => $now, 'gmap_status' => 1, 'gmap_msg'=>'success']);
            $affected_rows += $venue->saveAndLogError();
        }

//        $affected_rows = $command->execute();
//        \Yii::warning("Warning, affected rows:". $affected_rows);
        echo "Updated for: " . $affected_rows . " records." . PHP_EOL;
        $j = new Journal();
        $j->username = 'bot';
        $j->the_action='magic/pull-lat-lng';
        $j->action_msg=sizeof($venues_no_latlng) . " venues without latlng. Updated for: " . $affected_rows . " records.";
        return true;
    }

    /**
     * Pull genre from google
     * For bands that doesn't have genre populated
     */
    public function actionPullGenreFromGoogle() {
        $id = null;
        $genre = null;
        $sql = "SELECT `id`, `name` FROM lno.band WHERE genre IS NULL OR genre = ''";
        $guzzle_client = new GoutteClient();
        $base_url = 'https://google.com/search?q=';
        $bands_wo_genres = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);
        $update_sql = "UPDATE lno.band SET genre = :genre WHERE id = :id";
        $update_stmt = \Yii::$app->db->createCommand($update_sql);
        $update_stmt->bindParam(':id', $id);
        $update_stmt->bindParam(':genre', $genre);
        $update_stmt->prepare();
        foreach ($bands_wo_genres as $bands_wo_genre) {
            $band_name = $bands_wo_genre['name'];
            //todob debugging
            $band_name = 'Ozzy Osbourne';
            $band_name = str_replace(' ', '+', $band_name);
            $url = $base_url . "$band_name+genre";
            $crawler = $guzzle_client->request('get', $url);
            $crawler_html = $crawler->html();
//            $try_genre = $crawler->filter('div:contains("Musical genre")');
            $genre_div = $crawler->filterXPath('//div[text()="Musical genre"]');
            $genre_span = $genre_div->parents();
            try {
                $genre_text = $genre_span->siblings()->text();
            } catch (\InvalidArgumentException $e) {
            }
            if (! empty($genre_text)) {
                $genre = $genre_text;
                $id = $bands_wo_genre['id'];
                $update_stmt->execute();
            }
            $b = 1;
        }
        $a = 1;

        $sql = "UPDATE lno.band SET gg_last_attempt = CURRENT_TIMESTAMP WHERE genre IS NULL OR genre = ''";
        \Yii::$app->db->createCommand($sql)->execute();
    }
}
