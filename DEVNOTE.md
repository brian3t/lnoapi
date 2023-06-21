#### 1
vendor/mootensai/yii2-relation-trait/RelationTrait.php:
`$relModel->deleteAll(['and', $condition]);`  
=>  
`//                                        $relModel->deleteAll(['and', $condition]); b3t NEVER DO THIS. BE SPECIFIC IF WANT TO DELETE. LET FK CASCADE HANDLE DELETION`
