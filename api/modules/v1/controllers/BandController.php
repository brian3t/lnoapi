<?php

namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;
use app\models\Band;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\rest\IndexAction;


class BandController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Band';

    public function actions()
    {
        $actions = parent::actions();

        // disable the default REST actions
//        unset($actions['index']);

//        $actions['index']['class']='app\api\modules\v1\controllers\BandRestIndex';
        $actions['index']['prepareDataProvider'] = [$this, 'indexPrepareDataProvider'];
        return $actions;
    }

    // prepare and return a data provider for the "pull" action
    public function indexPrepareDataProvider($event_date_start = null, $event_date_end = null)
    {
        $where = [];
        if ($event_date_end == null && $event_date_end == null) {
            $where = [">=", "event.date", "2018-06-10"];
        }
//        $b = Band::find()->where(['id' => 3])->one();
//        $events = $b->getEvents();
//        var_dump($events->all());
//        return;
        $query = Band::find()
            ->with('events')
//            ->via('bandEvents')
//            ->joinWith('events')//
                ->join('left', 'event')
            ->where($where);
        ;
        /** @var ActiveQuery $query */

        $dp = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 100,
                ],
            ]
        );
        if (YII_DEBUG) {
            $dp->pagination = false;
        }
        return $dp;

    }


    public function indexAction()
    {

    }

}

class BandRestIndex extends IndexAction
{

}