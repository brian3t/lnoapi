<?php

namespace app\models;

use Yii;
use \app\models\base\Event as BaseEvent;

/**
 * This is the model class for table "event".
 */
class Event extends BaseEvent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['created_at', 'updated_at', 'date', 'start_time', 'end_time'], 'safe'],
            [['created_by', 'user_id', 'venue_id'], 'integer'],
            [['description'], 'string'],
            [['min_cost', 'max_cost'], 'number'],
            [['name', 'cost', 'twitter', 'facebook', 'website'], 'string', 'max' => 255],
            [['is_charity'], 'string', 'max' => 4]
        ]);
    }
	
}
