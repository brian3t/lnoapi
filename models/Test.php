<?php

namespace app\models;

use Yii;
use \app\models\base\Test as BaseTest;

/**
 * This is the model class for table "test".
 */
class Test extends BaseTest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['desc'], 'string', 'max' => 800]
        ]);
    }
	
}
