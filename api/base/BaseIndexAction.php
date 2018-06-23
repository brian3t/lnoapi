<?php

namespace app\api\base;

use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class BaseIndexAction extends IndexAction
{
    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        $modelClass=new $this->modelClass();
        if($this->prepareDataProvider!==null)
        {
            return call_user_func($this->prepareDataProvider,$this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $ap=new ActiveDataProvider([
            'query'=>$modelClass::find()->where(\Yii::$app->request->queryParams),
        ]);
        return $ap;
    }

}