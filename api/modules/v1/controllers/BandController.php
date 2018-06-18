<?php
namespace app\api\modules\v1\controllers;

use app\api\base\controllers\BaseActiveController;


class BandController extends BaseActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Band';

}