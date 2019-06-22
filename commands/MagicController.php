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
            if (empty($full_addr)) {
                continue;
            }
            $geocoder->setAddress($full_addr);

            $latlng = $geocoder->geocode(true);
            if ($latlng['status'] == 'ZERO_RESULTS') {
                continue;
            }
            if ($latlng['status'] == 'OK') {
                $geometry = array_shift($latlng['results'])['geometry']['location'];
                $venue->setAttributes(['lat' => $geometry['lat'], 'lng' => $geometry['lng']]);
                $affected_rows += $venue->save();
            }
        }

//        $affected_rows = $command->execute();
//        \Yii::warning("Warning, affected rows:". $affected_rows);
        echo "Updated for: " . $affected_rows . " records." . PHP_EOL;
        return 1;
    }

    /**
     * Pull genre from google
     * For bands that doesn't have genre populated
     */
    public function actionPullGenreFromGoogle()
    {
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
