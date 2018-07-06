<?php

namespace app\models;

use app\models\base\Venue as BaseVenue;

/**
 * This is the model class for table "venue".
 */
class Venue extends BaseVenue
{

    public function pull_address(){
        $full = implode(', ', [$this->address1, $this->address2, $this->city, $this->state, $this->zip]);
        return $full;
    }
}
