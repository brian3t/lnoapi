<?php

namespace app\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "event_comment".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property integer $edited_by
 * @property string $edited_at
 * @property string $comment
 *
 * @property \app\models\Event $event
 * @property \app\models\User $createdBy
 * @property \app\models\User $editedBy
 */
class EventComment extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'event',
            'createdBy',
            'editedBy'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'created_by'], 'required'],
            [['event_id', 'created_by', 'edited_by'], 'integer'],
            [['created_at', 'created_by', 'updated_at', 'edited_at'], 'safe'],
            [['comment'], 'string', 'max' => 800, 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_comment';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'edited_by' => 'Edited By',
            'edited_at' => 'Edited At',
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\app\models\Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'edited_by']);
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'edited_by',
            ],
        ];
    }
}
