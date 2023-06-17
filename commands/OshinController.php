<?php

namespace app\commands;

use app\models\Band;
use app\models\Event;
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
    public function actionAsdf() {
        $db = \Yii::$app->db;
        echo "Asdf" . PHP_EOL;
        return 1;
    }

  /**
     * Clean up data
     * Clean up sdr systemnote url
     * @throws Exception
     */
    public function actionCleanData() {
        $db = \Yii::$app->db;
        $db->createCommand("UPDATE event SET temp = CAST(REGEXP_REPLACE(system_note, '(?<!https:)//', '/') AS CHAR) WHERE source='sdr'")->execute();
        $db->createCommand("UPDATE event SET system_note = temp WHERE temp IS NOT NULL AND source='sdr'")->execute();
        $db->createCommand("UPDATE event SET created_by = 34 WHERE created_by IS NULL;")->execute();
        $db->createCommand("DELETE FROM event WHERE source='unknown'")->execute();
        echo "Cleanup done" . PHP_EOL;
        return 1;
    }

    /**
     * Clean up data
     * Clean up sdr systemnote url
     * @throws Exception
     */
    public function actionRandomRateBand() {
        $db = \Yii::$app->db;
        $db->createCommand("UPDATE band SET lno_score = ROUND((RAND() * (10-5)) + 5 )")->execute();
        echo "Random rate done" . PHP_EOL;
        return 1;
    }


    /**
     * Clean event SDR
     */
    public function actionCleanEvent() {
        \Yii::beginProfile('select');
        $events = Event::find()->where(['not', ['cost' => null]])->all();
        \Yii::endProfile('select');
        foreach ($events as $event) {
            if (stripos($event->cost, 'Age limit:') !== false) {
                $event->age_limit = trim(str_replace('Age limit:', '', $event->cost));
                $event->cost = null;
                $event->saveAndLogError();
            }
            if (stripos($event->cost, 'When:') !== false) {
                $event->when = trim(str_replace('When:', '', $event->cost));
                $event->cost = null;
                $event->saveAndLogError();
            }
        }
        echo 'Clean event done';

        //now delete events older than 2 months
        \Yii::$app->db->createCommand("DELETE FROM event WHERE date < DATE_SUB(CURDATE(), INTERVAL 2 MONTH) ");
        //now delete events named `event`
        \Yii::$app->db->createCommand("DELETE FROM event WHERE name='event' ");
        echo 'Prune events done';
    }

    /**
     * Clean up data
     * Clean up sdr systemnote url
     * @throws Exception
     */
    public function actionPruneData() {
        $db = \Yii::$app->db;
        $db->createCommand("DELETE FROM event WHERE date < DATE_SUB(CURDATE(), INTERVAL 2 MONTH) ")->execute();
        echo "Prune done" . PHP_EOL;
        return 1;
    }

    /**
     * Daily tasks, normally consists of all tasks
     */
    public function actionDailyTasks() {
        $controller = new MagicController(\Yii::$app->controller->id, \Yii::$app);
        $controller->actionPullLatLng();
//        $controller->actionPullGenreFromGoogle();
    }

    /**
     * export data into csv
     * @param string $date Cutoff day to export
     * @param string $output_mode
     * - local
     * - remote or
     * - sql
     */
    public function actionExportData(string $date = '', string $output_mode = 'local') {
        if (empty($date)) {
            $date = (new \DateTime('now'))->format('Y-m-d');
        }
        //exporting bands
        $bands = Band::find()->where(['>=', 'created_at', $date])->asArray()->all();
        $schema_cols = Band::getTableSchema()->columnNames;
        $schema_cols = array_filter($schema_cols, function ($col) {
            return ($col !== 'created_at');
        });
        $csv_data = [];
        array_push($csv_data, $schema_cols);
        foreach ($bands as &$band) {
            $csv_row = [];
            unset($band['created_at']);
            foreach ($schema_cols as $schema_col) {
                array_push($csv_row, $band[$schema_col]);
            }
            array_push($csv_data, $csv_row);
        }
        //ready for fwrite
        $f = fopen('bands.csv', 'w');
        $write_res =false;
        foreach ($csv_data as $csv_row) {
            $write_res = fputcsv($f, $csv_row);
        }
        fclose($f);
        echo "Write res: " . $write_res;
    }
}


