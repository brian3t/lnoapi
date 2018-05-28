<?php

namespace brian\yiiplus;

use \Yii;
use yii\i18n\Formatter;

class ViewFormatter extends Formatter
{
    public function asView($value)
    {
        // translate your int value to something else...
        switch ($value) {
            case 0:
                return 'White';
            case 1:
                return 'Black';
            default:
                return 'Unknown color';
        }
    }
}