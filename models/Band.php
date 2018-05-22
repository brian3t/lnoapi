<?php

namespace app\models;

use Yii;
use \app\models\base\Band as BaseBand;

/**
 * This is the model class for table "band".
 */
class Band extends BaseBand
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'integer'],
            [['lno_score'], 'number'],
            [['type', 'description'], 'string'],
            [['name', 'hometown_city'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 300],
            [['similar_to', 'website', 'facebook', 'twitter'], 'string', 'max' => 255],
            [['hometown_state'], 'string', 'max' => 8]
        ]);
    }
	
}
