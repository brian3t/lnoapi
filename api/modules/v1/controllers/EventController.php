<?php
/**
 * Event Controller REST
 */

namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;

require_once realpath(dirname(dirname(dirname(dirname(__DIR__))))) . "/models/constants.php";

class EventController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Event';

    public function actions() {
        $actions = parent::actions();
        // override the default REST actions
//        unset($actions['index']);

        $actions['index']['class'] = 'app\api\modules\v1\EventIndexAction';
        return $actions;
    }

}
