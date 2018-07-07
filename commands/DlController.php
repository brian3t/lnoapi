<?php

namespace app\commands;

use app\models\Event;
use app\models\Venue;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Yii;
use yii\console\Controller;

define('SDREADER', 'https://www.sandiegoreader.com/events/search/?category=Genre');//&start_date=2018-07-04&end_date=2018-07-04
define('SDREADER_LOCAL', 'http://lnoapi/tmp/Eventsearch_SanDiegoReader.html');

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
//        $crawler = $client->request('GET', SDREADER, ['start_date' => '2018-07-04', 'end_date' => '2018-07-04']);
        $crawler = $client->request('GET', SDREADER, ['start_date' => $date_str, 'end_date' => $date_str]);
        $crawler->filter('table.event_list tr')->each(function ($event_and_venue) use (&$records, $date_str) {
            /** @var Crawler $event_and_venue */
            [$venue_name, $venue_href] = current($event_and_venue->filter('h5.place > a')->extract(['_text', 'href']));
            //find out if venue already exists
            $venue_exist = Venue::findOne(['name' => $venue_name]);
            if (!$venue_exist instanceof Venue) {
                $venue = new Venue();
                $venue->setAttributes(['name' => $venue_name, 'sdr_name' => $venue_href, 'system_note' => 'https://www.sandiegoreader.com' . $venue_href]);
                $venue->save();
                $venue_id = $venue->id;
            } else {
                $venue_id = $venue_exist->id;
            }
            if (!is_int($venue_id)) {
                return;
            }
            Yii::$app->db->createCommand("UPDATE venue SET `created_by` = :created_by WHERE `id` = :venue_id")->bindValues([':created_by' => $this->SCRAPER_ID, ':venue_id' => $venue_id])->execute();
            $records++;
            [$event_name, $event_href] = current($event_and_venue->filter('h4 > a')->extract(['_text', 'href']));
            //find out if event already exists
            $event = new Event();
            $event_exist = Event::findOne(['name' => $event_name]);
            if (!$event_exist instanceof Event) {
                $event = new Event();
                $event->setAttributes(['created_by' => $this->SCRAPER_ID, 'name' => $event_name, 'sdr_name' => $event_href, 'system_note' => 'https://www.sandiegoreader.com/' . $event_href]);
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
            }
        });
        echo "Pulled this much: " . $records . " records." . PHP_EOL;
    }
}
