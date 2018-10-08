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
     * Clean up sdr systemnote url
     * @throws Exception
     */
    public function actionCleanData()
    {
        $db = \Yii::$app->db;
        $db->createCommand("UPDATE event SET temp = CAST(REGEXP_REPLACE(system_note, '(?<!https:)//', '/') AS CHAR) WHERE source='sdr'")->execute();
        $db->createCommand("UPDATE event SET system_note = temp WHERE temp IS NOT NULL AND source='sdr'")->execute();
        $db->createCommand("update event set created_by = 35 where created_by is null;")->execute();
        echo "Cleanup done" . PHP_EOL;
        return 1;
    }
}
