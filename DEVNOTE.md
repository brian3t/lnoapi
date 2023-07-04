#### 1
vendor/mootensai/yii2-relation-trait/RelationTrait.php:
`$relModel->deleteAll(['and', $condition]);`  
=>  
`//                                        $relModel->deleteAll(['and', $condition]); b3t NEVER DO THIS. BE SPECIFIC IF WANT TO DELETE. LET FK CASCADE HANDLE DELETION`

(four instances)

#### 2
vendor/2amigos/yii2-usuario/src/User/Model/User.php
```
public function behaviors()
    {
        $behaviors = [
//            TimestampBehavior::class, //disable timestamp; let db handle
        ];
```
