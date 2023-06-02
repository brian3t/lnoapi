<?php

use yii\db\Migration;

const TABLE = 'user';

/**
 * Class m230601_035046_user_birthyear_favgen
 */
class m230601_035046_user_birthyear_favgen extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $table = Yii::$app->db->schema->getTableSchema(TABLE);
    if (!isset($table->columns['first_name'])) {
      $this->addColumn('user', 'first_name', 'nvarchar(80) NULL default ""');
    }
    if (!isset($table->columns['last_name'])) {
      $this->addColumn('user', 'last_name', 'nvarchar(80) NULL default ""');
    }
    if (!isset($table->columns['note'])) {
      $this->addColumn('user', 'note', 'TEXT default NULL');
    }
    if (!isset($table->columns['phone_number_type'])) {
      $this->addColumn('user', 'phone_number_type', 'varchar(8) default "cell"');
    }
    if (!isset($table->columns['phone_number'])) {
      $this->addColumn('user', 'phone_number', 'varchar(20) default NULL');
    }
    if (!isset($table->columns['birthdate'])) {
      $this->addColumn('user', 'birthdate', 'char(8) default NULL');
    }
    if (!isset($table->columns['favorite_genres'])) {
      $this->addColumn('user', 'favorite_genres', 'varchar(500) default ""');
    }
    if (!isset($table->columns['favorite_venue_types'])) {
      $this->addColumn('user', 'favorite_venue_types', 'varchar(500) default ""');
    }
    if (!isset($table->columns['twitter_id'])) {
      $this->addColumn('user', 'twitter_id', 'bigint default NULL');
    }
    if (!isset($table->columns['facebook_id'])) {
      $this->addColumn('user', 'facebook_id', 'bigint default NULL');
    }
    if (!isset($table->columns['instagram_id'])) {
      $this->addColumn('user', 'instagram_id', 'bigint default NULL');
    }
    if (!isset($table->columns['address1'])) {
      $this->addColumn('user', 'address1', 'varchar(255) default ""');
    }
    if (!isset($table->columns['address2'])) {
      $this->addColumn('user', 'address2', 'varchar(255) default ""');
    }
    if (!isset($table->columns['city'])) {
      $this->addColumn('user', 'city', 'varchar(25) default ""');
    }
    if (!isset($table->columns['state'])) {
      $this->addColumn('user', 'state', 'varchar(25) default ""');
    }
    if (!isset($table->columns['zipcode'])) {
      $this->addColumn('user', 'zipcode', 'varchar(25) default ""');
    }
    if (!isset($table->columns['country'])) {
      $this->addColumn('user', 'country', 'char(2) default "us"');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    echo "m230601_035046_user_birthyear_favgen cannot be reverted.\n";

    return false;
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

  }

  public function down()
  {
      echo "m230601_035046_user_birthyear_favgen cannot be reverted.\n";

      return false;
  }
  */
}
