<?php

namespace app\models\base;

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
 * @property string $type
 * @property string $address1
 * @property string $address2
 * @property string $county
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $lat
 * @property string $lng
 * @property string $description
 * @property string $phone
 * @property string $cost
 * @property string $website
 * @property string $twitter
 * @property string $facebook
 * @property string $system_note
 * @property string $sdr_name
 * @property string $source
 * @property array $attr
 * @property integer $scrape_status
 * @property string $scrape_msg
 * @property string $scrape_url
 * @property string $tz
 *
 * @property \app\models\Event[] $events
 * @property \app\models\Event[] $eventsNonInverse
 * @property \app\models\User $createdBy
 * @property \app\models\User $user
 * @property string $scrape_dt [datetime]
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
            [['created_at', 'updated_at', 'scrape_dt'], 'safe'],
            [['created_by', 'user_id', 'scrape_status'], 'integer'],
            [['lat', 'lng', 'cost'], 'number'],
            [['source', 'attr'], 'string'],
            [['name', 'address1', 'address2', 'website', 'twitter', 'facebook', 'sdr_name'], 'string', 'max' => 255],
            [['type', 'description', 'scrape_msg'], 'string', 'max' => 800],
            [['county', 'city'], 'string', 'max' => 80],
            [['state'], 'string', 'max' => 8],
            [['zip'], 'string', 'max' => 25],
            [['phone'], 'string', 'max' => 18],
            [['system_note'], 'string', 'max' => 8000],
            [['scrape_url'], 'string', 'max' => 200],
            [['tz'], 'string', 'max' => 120]
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
            'type' => 'Type',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'county' => 'County',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'description' => 'Description',
            'phone' => 'Phone',
            'cost' => 'Cost',
            'website' => 'Website',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'system_note' => 'System Note',
            'sdr_name' => 'Sdr Name',
            'source' => 'Source',
            'attr' => 'Attr',
            'scrape_status' => 'Scrape Status',
            'scrape_dt' => 'Scrape Last DateTime',
            'scrape_msg' => 'Scrape Msg',
            'scrape_url' => 'Scrape Url',
            'tz' => 'Tz',
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
    public function getEventsNonInverse()
    {
        $events_array = $this->hasMany(\app\models\Event::className(), ['venue_id' => 'id']);
        $events_array->asArray = true;
        return $events_array;
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
