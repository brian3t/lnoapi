<?php

namespace app\models;

use app\models\base\Band as BaseBand;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "band".
 */
class Band extends BaseBand
{
    public function beforeSave($insert)
    {
        $this->genre = strtolower(preg_replace('/\s+/i', '', $this->genre));
        return parent::beforeSave($insert);
    }

    public function fields()
    {

        return ArrayHelper::merge(parent::fields(), ['band_events' => 'bandEvents']);
    }

    public function extraFields()
    {
        return ['event'];
    }

}
