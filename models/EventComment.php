<?php

namespace app\models;

use soc\yii2helper\models\ModelB3tTrait;
use Yii;
use \app\models\base\EventComment as BaseEventComment;

/**
 * This is the model class for table "event_comment".
 */
class EventComment extends BaseEventComment
{
  use ModelB3tTrait;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['event_id', 'created_by'], 'required'],
            [['event_id', 'created_by', 'edited_by'], 'integer'],
            [['created_at', 'updated_at', 'edited_at'], 'safe'],
            [['comment'], 'string', 'max' => 800],
        ]);
    }

    public function beforeValidate() {
    if ($this->created_by > 0 || $this->edited_by > 0){
      $this->detachBehavior('blameable');
    }
      return parent::beforeValidate();
    }

}
