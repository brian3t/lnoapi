<?php

namespace app\commands;

use app\models\Event;
use app\models\Venue;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use yii\console\Controller;
use yii\db\Exception;

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
     * @throws Exception
     */
    public function actionScrape()
    {
        $records = 0;
        $client = new Client();
//        $crawler = $client->request('GET', SDREADER, ['start_date' => '2018-07-04', 'end_date' => '2018-07-04']);
        $crawler = $client->request('GET', SDREADER_LOCAL, ['start_date' => '2018-07-04', 'end_date' => '2018-07-04']);
        $crawler->filter('table.event_list tr')->each(function ($node){
            /** @var Crawler $node */
            //find out if venue already exists
            $venue_name = $node->extract('h5.place');
            $venue_exist = Venue::findOne(['name' => $venue_name]);
            $venue = new Venue();
            $venue->setAttributes(['created_by' => $this->SCRAPER_ID]);

            $event = new Event();
            $event->setAttributes(['created_by' => $this->SCRAPER_ID]);
        });
        echo "Pulled this much: " . $records . " records." . PHP_EOL;
        return 1;
    }
}
