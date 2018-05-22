<?php

namespace app\models\base;

use Yii;

/**
 * This is the base model class for table "band".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property integer $user_id
 * @property string $logo
 * @property string $lno_score
 * @property string $type
 * @property string $similar_to
 * @property string $hometown_city
 * @property string $hometown_state
 * @property string $description
 * @property string $website
 * @property string $facebook
 * @property string $twitter
 *
 * @property \app\models\User $user
 * @property \app\models\BandComment[] $bandComments
 * @property \app\models\BandEvent[] $bandEvents
 * @property \app\models\BandFollow[] $bandFollows
 * @property \app\models\BandRate[] $bandRates
 */
class Band extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'user',
            'bandComments',
            'bandEvents',
            'bandFollows',
            'bandRates'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'integer'],
            [['lno_score'], 'number'],
            [['type', 'description'], 'string'],
            [['name', 'hometown_city'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 300],
            [['similar_to', 'website', 'facebook', 'twitter'], 'string', 'max' => 255],
            [['hometown_state'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'band';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'user_id' => 'User ID',
            'logo' => 'Logo',
            'lno_score' => 'Lno Score',
            'type' => 'Type',
            'similar_to' => 'Similar To',
            'hometown_city' => 'Hometown City',
            'hometown_state' => 'Hometown State',
            'description' => 'Description',
            'website' => 'Website',
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id'])->inverseOf('bands');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBandComments()
    {
        return $this->hasMany(\app\models\BandComment::className(), ['band_id' => 'id'])->inverseOf('band');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBandEvents()
    {
        return $this->hasMany(\app\models\BandEvent::className(), ['band_id' => 'id'])->inverseOf('band');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBandFollows()
    {
        return $this->hasMany(\app\models\BandFollow::className(), ['band_id' => 'id'])->inverseOf('band');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBandRates()
    {
        return $this->hasMany(\app\models\BandRate::className(), ['band_id' => 'id'])->inverseOf('band');
    }
    }
