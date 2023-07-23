<?php

namespace app\models;

//use app\models\base\User as BaseUser;
use Da\User\Model\SocialNetworkAccount;
use Da\User\Model\User as BaseUser;
use yii\behaviors\TimestampBehavior;

/**
 * User ActiveRecord model.
 *
 * @property bool $isAdmin
 * @property bool $isBlocked
 * @property bool $isConfirmed  whether user account has been confirmed or not
 * @property bool $gdpr_deleted whether user requested deletion of his account
 * @property bool $gdpr_consent whether user has consent personal data processing
 * @property string $first_name
 * @property string $last_name
 * @property string $note
 * @property string $phone_number_type
 * @property string $phone_number
 * @property integer $birthdate
 * @property string $favorite_genres
 * @property string $favorite_venue_types
 * @property string $twitter_id
 * @property string $facebook_id
 * @property string $instagram_id
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $country
 * @property integer $last_login_at
 * @property integer $is_btt
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $unconfirmed_email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $auth_tf_key
 * @property int $auth_tf_enabled
 * @property string $auth_tf_type
 * @property string $auth_tf_mobile_phone
 * @property string $registration_ip
 * @property int $confirmed_at
 * @property int $blocked_at
 * @property int $flags
 * @property string $created_at
 * @property string $updated_at
 * @property int $gdpr_consent_date     date of agreement of data processing
 * @property string $last_login_ip
 * @property int $password_changed_at
 * @property int $password_age
 * @property string $plain_pw
 *                                                         Defined relations:
 * @property SocialNetworkAccount[] $socialNetworkAccounts
 * @property Profile $profile
 */
class User extends BaseUser
{
//    private $_union_memberships;
  public static $order_by_col = 'first_name';

  /**
   * @inheritdoc
   */
  public function rules(): array {
    $rules = parent::rules();
    $rules = array_merge($rules, [[['first_name', 'last_name', 'plain_pw'], 'string', 'max' => 80],
      [['first_name', 'last_name', 'email'], 'filter', 'filter' => 'trim'],
    ]);
    return $rules;
  }


  public function getName() {
    return $this->username;
  }


  public function fields() {
    $parent_fields = parent::fields();
    $parent_fields = array_diff($parent_fields,
      ['password_hash', 'registration_ip', 'unconfirmed_email', 'blocked_at', 'updated_at']);
    return array_merge($parent_fields, [
      'name',
    ]);
  }


  public function beforeValidate() {
    return parent::beforeValidate();
  }

  public function behaviors() {
    /*   if ($this->module->enableGdprCompliance) {
         $behaviors['GDPR'] = [
           'class' => TimestampBehavior::class,
           'createdAtAttribute' => 'gdpr_consent_date',
           'updatedAtAttribute' => false
         ];
       }*/
    return [];//disable created_at updated_at
  }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\app\models\Event::class, ['user_id' => 'id'])->inverseOf('user');
    }

  public function beforeSave($insert) {
    if (!$insert && ($this->is_btt > 0)) {
      $this->username = strtolower($this->first_name . '.' . $this->last_name . '.ny.' . strval(rand(3, 10)));
    }
  }
}
