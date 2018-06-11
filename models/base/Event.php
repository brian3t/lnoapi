<?php

namespace app\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "event".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $user_id
 * @property integer $venue_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property string $name
 * @property string $description
 * @property string $cost
 * @property string $min_cost
 * @property string $max_cost
 * @property integer $is_charity
 * @property string $twitter
 * @property string $facebook
 * @property string $website
 *
 * @property \app\models\BandEvent[] $bandEvents
 * @property \app\models\User $createdBy
 * @property \app\models\User $user
 * @property \app\models\Venue $venue
 * @property \app\models\UserEvent[] $userEvents
 */
class Event extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'bandEvents',
            'createdBy',
            'user',
            'venue',
            'userEvents'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'date', 'start_time', 'end_time'], 'safe'],
            [['created_by', 'user_id', 'venue_id'], 'integer'],
            [['description'], 'string'],
            [['min_cost', 'max_cost'], 'number'],
            [['name', 'cost', 'twitter', 'facebook', 'website'], 'string', 'max' => 255],
            [['is_charity'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'venue_id' => 'Venue ID',
            'date' => 'Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'name' => 'Name',
            'description' => 'Description',
            'cost' => 'Cost',
            'min_cost' => 'Min Cost',
            'max_cost' => 'Max Cost',
            'is_charity' => 'Is Charity',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'website' => 'Website',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBandEvents()
    {
        return $this->hasMany(\app\models\BandEvent::className(), ['event_id' => 'id'])->inverseOf('event');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'created_by'])->inverseOf('events');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id'])->inverseOf('events');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(\app\models\Venue::className(), ['id' => 'venue_id'])->inverseOf('events');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserEvents()
    {
        return $this->hasMany(\app\models\UserEvent::className(), ['event_id' => 'id'])->inverseOf('event');
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }
}
