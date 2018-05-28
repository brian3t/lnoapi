<?php

namespace app\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "venue".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $user_id
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $description
 * @property string $phone
 * @property string $cost
 * @property string $website
 * @property string $twitter
 * @property string $facebook
 *
 * @property \app\models\Event[] $events
 * @property \app\models\User $createdBy
 * @property \app\models\User $user
 */
class Venue extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'events',
            'createdBy',
            'user'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'user_id'], 'integer'],
            [['cost'], 'number'],
            [['name', 'address1', 'address2', 'website', 'twitter', 'facebook'], 'string', 'max' => 255],
            [['city'], 'string', 'max' => 80],
            [['state'], 'string', 'max' => 8],
            [['zip'], 'string', 'max' => 25],
            [['description'], 'string', 'max' => 800],
            [['phone'], 'string', 'max' => 18]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'venue';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'description' => 'Description',
            'phone' => 'Phone',
            'cost' => 'Cost',
            'website' => 'Website',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\app\models\Event::className(), ['venue_id' => 'id'])->inverseOf('venue');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'created_by'])->inverseOf('venues');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id'])->inverseOf('venues');
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
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }
}