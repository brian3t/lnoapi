<?php

namespace app\models;

use app\models\base\Band as BaseBand;

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

}
