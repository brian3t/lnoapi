<?php

namespace app\models;

use app\models\base\Event as BaseEvent;
use soc\yii2helper\models\ModelB3tTrait;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

const POPULATE_MODE = true;
/**
 * This is the model class for table "event".
 */
class Event extends BaseEvent
{
    use ModelB3tTrait;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBands()
    {
        return $this->hasMany(\app\models\Band::className(), ['id' => 'band_id'])->via('bandEvents')->inverseOf('events');
    }

    public function fields()
    {
//        return parent::fields();
        return ArrayHelper::merge(parent::fields(), ['band_events' => 'bandEvents', 'venue']);
    }

    public function beforeSave($insert)
    {
        if ($this->start_datetime_utc != null && $this->start_time == null) {
            $this->start_time_utc = (new \DateTime($this->start_datetime_utc))->format('H:i:s');
        }
        return parent::beforeSave($insert);
    }

    public function getFirst_band(){
        $band_events = $this->bandEvents;
        if (sizeof($band_events) < 1) return [];
        $first_band_event = $band_events[0];
        if (!$first_band_event instanceof BandEvent) return [];
        $first_band = $first_band_event->getBand();
        return $first_band;
    }

    public function getStartDate(){
        if (!$this->start_datetime_utc) return 'N/A';
        try {
            return (new \DateTime($this->start_datetime_utc, (new \DateTimeZone('UTC'))))->format('Y-m-d');
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

  /**
   * @throws Exception
   */
  public function afterSave($insert, $changedAttributes): void {
      parent::afterSave($insert, $changedAttributes);
      if (!$insert) return;
      if (POPULATE_MODE !== true) return;
      //Populate mode: if new record; populate comments
      $count_comments = EventComment::find()->where(['event_id' => $this->id])->count();
      if ($count_comments > 7) return;
      $user_ids = [rand(6,100), rand(6,100), rand(6,100), rand(6,100), rand(6,100), rand(6,100), rand(6,100)];//7 commenters
      $conn = \Yii::$app->db;
      foreach ($user_ids as $user_id){
        $event_comment = new EventComment();
        $event_comment->detachBehavior('blameable');
        $event_comment->event_id = $this->id;
        $event_comment->created_by = $user_id;
        $event_comment->comment = "I'm going to this event";
        $event_comment->saveAndLogError();
        //to overwrite the blameable behavior:
        /*$set_commenter_cmd = $conn->createCommand('UPDATE event_comment set edited_by=null,created_by=:created_by');
        $set_commenter_cmd->bindParam(':created_by', $user_id)->queryAll();*/
      }
    }

  public function extraFields()
    {
        return ['bands','first_band'];
    }
}
