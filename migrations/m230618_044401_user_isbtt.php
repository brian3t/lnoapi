<?php

use yii\db\Migration;

const TABLE = 'user';

/**
 * Class m230618_044401_user_isbtt
 */
class m230618_044401_user_isbtt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema(TABLE);
        if (!isset($table->columns['is_btt'])) {
            $this->addColumn('user', 'is_btt', 'bool NULL default FALSE');
        }
        $this->execute('alter table user
    modify updated_at int default null null;
update user set updated_at=null;
alter table user
    modify updated_at timestamp default null null
        ON UPDATE CURRENT_TIMESTAMP;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230618_044401_user_isbtt cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230618_044401_user_isbtt cannot be reverted.\n";

        return false;
    }
    */
}
