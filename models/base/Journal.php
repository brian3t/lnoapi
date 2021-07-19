<?php

namespace app\models\base;

/**
 * This is the base model class for table "journal".
 *
 * @property integer $id
 * @property string $username
 * @property string $created_at
 * @property string $entity
 * @property string $the_action
 * @property string $ent_before
 * @property integer $ent_after
 * @property integer $action_status
 * @property string $action_msg
 */
class Journal extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            ''
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['entity', 'the_action'], 'required'],
            [['ent_after', 'action_status'], 'integer'],
            [['username', 'the_action'], 'string', 'max' => 80],
            [['entity'], 'string', 'max' => 50],
            [['ent_before', 'action_msg'], 'string', 'max' => 2000],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'journal';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'entity' => 'Entity',
            'the_action' => 'The Action',
            'ent_before' => 'Ent Before',
            'ent_after' => 'Ent After',
            'action_status' => 'Action Status',
            'action_msg' => 'Action Msg',
        ];
    }
}
