<?php

namespace app\models;

use Yii;
use \app\models\base\EventComment as BaseEventComment;

/**
 * This is the model class for table "event_comment".
 */
class EventComment extends BaseEventComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['created_at', 'updated_at', 'edited_at'], 'safe'],
            [['created_by'], 'required'],
            [['created_by', 'edited_by'], 'integer'],
            [['comment'], 'string', 'max' => 800],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
}
