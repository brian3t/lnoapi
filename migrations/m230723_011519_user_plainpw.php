<?php

use yii\db\Migration;


/**
 * Class m230723_011519_user_plainpw
 */
class m230723_011519_user_plainpw extends Migration
{
  const TABLE = 'user';

  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $table = Yii::$app->db->schema->getTableSchema($this::TABLE);
    if (!isset($table->columns['plain_pw'])) {
      $this->addColumn('user', 'plain_pw', 'varchar(50) NULL default NULL');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    echo "m230723_011519_user_plainpw cannot be reverted.\n";

    return false;
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

  }

  public function down()
  {
      echo "m230723_011519_user_plainpw cannot be reverted.\n";

      return false;
  }
  */
}
