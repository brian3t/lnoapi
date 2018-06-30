<?php

namespace app\models;

use app\models\base\Venue as BaseVenue;

/**
 * This is the model class for table "venue".
 */
class Venue extends BaseVenue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'user_id'], 'integer'],
            [['lat', 'lng', 'cost'], 'number'],
            [['name', 'address1', 'address2', 'website', 'twitter', 'facebook'], 'string', 'max' => 255],
            [['city'], 'string', 'max' => 80],
            [['state'], 'string', 'max' => 8],
            [['zip'], 'string', 'max' => 25],
            [['description'], 'string', 'max' => 800],
            [['phone'], 'string', 'max' => 18]
        ]);
    }

    public function pull_address(){
        $full = implode(', ', [$this->address1, $this->address2, $this->city, $this->state, $this->zip]);
        return $full;
    }
}
