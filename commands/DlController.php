<?php

namespace app\commands;
require_once dirname(__DIR__) . "/yii2helper/PHPHelper.php";

use app\models\Band;
use app\models\BandEvent;
use app\models\Event;
use app\models\Venue;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use yii\console\Controller;
use yii2helper\PHPHelper;

define('SDRCOM', 'https://www.sandiegoreader.com/');
define('SDREADER', 'https://www.sandiegoreader.com/events/search/?category=Genre');//&start_date=2018-07-04&end_date=2018-07-04
define('SDREADER_LOCAL', 'http://lnoapi/scrape/Eventsearch_SanDiegoReader.html');
define('SDREVENT_LOCAL', 'http://lnoapi/scrape/gingercowgirl.html');
define('SDRBAND_LOCAL', 'http://lnoapi/scrape/band_gg_cowgirl.html');

/**
 * The behind the scenes magic happens here
 *
 * @author Brian Nguyen
 */
class DlController extends Controller
{
    public $SCRAPER_ID = null;

    public function init()
    {
        $this->SCRAPER_ID = \Yii::$app->params['scraper_id'];
        parent::init();
    }

    /**
     * Pull events from SDReader
     * @param int $days_forward lookahead
     * @throws \Exception
     */
    public function actionScrapeSdr($days_forward = 7)
    {
        $date = (new \DateTime())->add(new \DateInterval("P{$days_forward}D"));
        $date_str = $date->format('Y-m-d');
        $records = 0;
        $client = new Client();
        $event_client = new Client();
        $band_client = new Client();
        $crawler = $client->request('GET', SDREADER, ['start_date' => $date_str, 'end_date' => $date_str]);
//        $crawler = $client->request('GET', SDREADER_LOCAL, ['start_date' => '2018-07-04', 'end_date' => '2018-07-04']);
        $crawler->filter('table.event_list tr')->each(function ($event_and_venue) use (&$records, $date_str, $event_client, $band_client) {
            /** @var Crawler $event_and_venue */
            [$venue_name, $venue_href] = current($event_and_venue->filter('h5.place > a')->extract(['_text', 'href']));
            if (empty($venue_name)) {
                return;
            }
            [$event_name, $event_href] = current($event_and_venue->filter('h4 > a')->extract(['_text', 'href']));
            //see if it has local artist page
            $event_crawler = $event_client->request('GET', SDRCOM . $event_href);
            $h4_local_artist = $event_crawler->filter('h4:contains("Local artist page:")');
            $has_local_artist = $h4_local_artist->count() > 0;
            //find out if venue already exists
            $venue_exist = Venue::findOne(['name' => $venue_name]);
            if (!$venue_exist instanceof Venue) {
                $venue = new Venue();
                $venue->setAttributes(['name' => $venue_name,
                    'sdr_name' => str_replace('https://www.sandiegoreader.com', '', $venue_href),
                    'system_note' => $venue_href]);//'https://www.sandiegoreader.com' .
                $venue->save();
                $venue_id = $venue->id;
            } else {
                $venue_id = $venue_exist->id;
            }
            if (!is_int($venue_id)) {
                return;//when failed saving new venue / pulling existing venue
            }
            Yii::$app->db->createCommand("UPDATE venue SET `created_by` = :created_by WHERE `id` = :venue_id")
                ->bindValues([':created_by' => $this->SCRAPER_ID, ':venue_id' => $venue_id])->execute();
            $records++;
            //find out if event already exists
            $event = new Event();
            $event_exist = Event::findOne(['name' => $event_name]);
            if (!$event_exist instanceof Event) {
                $event = new Event();
                $event->setAttributes(['created_by' => $this->SCRAPER_ID, 'name' => $event_name,
                    'sdr_name' => str_replace('https://www.sandiegoreader.com/', '', $event_href), 'system_note' => $event_href]);//'https://www.sandiegoreader.com/' .
                $time = $event_and_venue->filter('td.time')->text();
                $city = $event_and_venue->filter('td.city>ul>li>a')->text();
                $description = implode(', ', $event_and_venue->filter('td.category > ul li')->extract(['_text']));
                $event->venue_id = $venue_id;
                $event->date = $date_str;
                $event->setAttributes(compact(['time', 'city', 'description']));
                $event->save();
                $event_id = $event->id;
                if (is_int($event_id)) {
                    Yii::$app->db->createCommand("UPDATE event SET `created_by` = :created_by WHERE `id` = :id")->bindValues([':created_by' => $this->SCRAPER_ID, ':id' => $event_id])->execute();
                }
                $records++;
            } else {
                $event_id = $event->id;
            }

            if (!$has_local_artist) {
                return;
            }
            //saving band info too
            $band_href = $h4_local_artist->nextAll()->filter('div.image_grid a')->attr('href');
            $band_crawler = $band_client->request('GET', SDRCOM . $band_href);
//            $band_crawler = $band_client->request('GET', SDRBAND_LOCAL);
            $band_content = $band_crawler->filter('#content');
            $name = $band_content->filter('div.content_title > h2')->text();
            if (empty($name)) {
                return;
            }
            try {
                $name = strtolower($name);
                $hometown_city = 'San Diego';
                $hometown_state = 'CA';
                $logo = $band_content->filter('img.lead_photo')->attr('src');
                if (empty($logo)){
                    return;
                }
                $genre = $band_content->filter('strong:contains("Genre:")')->parents()->text();
                $genre = strtolower(str_replace(['Genre: ', ', '], ['', ','], $genre));
                $similar_to = $band_content->filter('strong:contains("RIYL:")')->parents()->text();
                $similar_to = strtolower(str_replace(['RIYL: ', ', '], ['', ','], $similar_to));
                $description = $band_content->filter('h3#history')->parents()->text();
                $related = $band_content->filter('h3#related')->parents();
                $website = $related->filter('a:contains("website")')->attr('href');
                $facebook = $related->filter('a:contains("Facebook")')->attr('href');
            } catch (\Exception $e) {
                $band_content = null;
                $logo = null;
                $genre = null;
                $similar_to = null;
                $description = null;
                $website = null;
                $facebook = null;
            }
            $band = Band::findOne(['name' => $name]);
            if (!$band instanceof Band) {
                $band = new Band();
                $band->setAttributes(compact(['name', 'logo', 'genre', 'similar_to', 'hometown_city', 'hometown_state', 'description', 'website', 'facebook']));
                $band->type='originals';
                $band->lno_score = random_int(5, 10);
                $band->save();
            }
            $band_id = $band->id;
            if (is_int($band_id) && is_int($event_id)) {
                $band_event = new BandEvent();
                $band_event->band_id = $band_id;
                $band_event->event_id = $event_id;
                $band_event->save();
            }
        });
        echo "Pulled this much: " . $records . " records." . PHP_EOL;
    }

    /**
     * Pull address for all venues without address
     */
    public function actionVenueAddrSdr()
    {
        $updated = 0;
        $venues_wo_addr = Venue::find()->where(['not', ['sdr_name' => null]])->andWhere(['address1' => null])->all();
        $venue_client = new Client();
        foreach ($venues_wo_addr as $venue_wo_addr) {
            $sdr_url = SDRCOM . $venue_wo_addr->sdr_name;
            $sdr_url = str_replace(SDRCOM . SDRCOM, SDRCOM, $sdr_url);//remove duplicates
            $crawler = $venue_client->request('GET', $sdr_url);
            $type_a = $crawler->filter('ul.categories > li:first-child > a');
            if ($type_a instanceof Crawler) {
                $type = $type_a->text();
            } else {
                $type = null;
            }
            $addr_a = $crawler->filter('a:contains("Directions")');
            if ($addr_a instanceof Crawler) {
                $addr = $addr_a->parents()->text();
            }
            if (empty($addr)) {
                return false;
            }
            $addr = str_replace(' | Directions', '', $addr);
            $addr = str_replace(' (NCC)', '', $addr);
            $address_variables = PHPHelper::parse_address($addr);
            $venue_wo_addr->setAttributes($address_variables);
            if ($venue_wo_addr->save()) {
                $updated++;
            }
        }
        echo "Updated $updated rows\n";
        return true;
    }
}
