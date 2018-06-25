<?php

namespace app\models;

use app\models\base\Band as BaseBand;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "band".
 */
class Band extends BaseBand
{
    public function beforeValidate()
    {
        $genre_array = \Yii::$app->request->getBodyParam('genre_array');
        if (is_array($genre_array)){
            $this->genre = implode(',', $genre_array);
        }
        return parent::beforeValidate();
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
