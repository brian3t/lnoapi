<?php

namespace app\commands;

use yii\console\Controller;
use yii\db\Exception;

/**
 * The infamous maid
 *
 * @author Brian Nguyen
 */
class OshinController extends Controller
{
    /**
     * Clean up data
     * @throws Exception
     */
    public function actionCleanData()
    {
        $db = \Yii::$app->db;
        $db->createCommand("UPDATE event SET temp = CAST(REGEXP_REPLACE(system_note, '(?<!https:)//', '/') AS CHAR)")->execute();
        $db->createCommand("UPDATE event SET website = temp WHERE temp IS NOT NULL ")->execute();
        echo "Cleanup done" . PHP_EOL;
        return 1;
    }
}
