<?php

use yii\db\Migration;
use yii\db\Schema;

class m141013_130838_admin_user_and_session extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%admin_user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(32)',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'real_name' => Schema::TYPE_STRING,
            'avatar' => Schema::TYPE_STRING,
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'last_visit_at' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);
        $this->createIndex($this->db->tablePrefix.'admin_user_username_uq', '{{%admin_user}}', 'username', true);
        $this->createIndex($this->db->tablePrefix.'admin_user_email_uq', '{{%admin_user}}', 'email', true);

        $this->createTable('{{%admin_session}}', [
            'id' => Schema::TYPE_STRING. '(40) NOT NULL',
            'expire' => Schema::TYPE_INTEGER,
            'data' => Schema::TYPE_BINARY,
        ], $tableOptions);
        $this->addPrimaryKey($this->db->tablePrefix.'admin_session_pk', '{{%admin_session}}', 'id');
    }

    public function safeDown()
    {
        $this->dropPrimaryKey($this->db->tablePrefix.'admin_session_pk', '{{%admin_session}}');
        $this->dropTable('{{%admin_session}}');

        $this->dropIndex($this->db->tablePrefix.'admin_user_email_uq', '{{%admin_user}}');
        $this->dropIndex($this->db->tablePrefix.'admin_user_username_uq', '{{%admin_user}}');
        $this->dropTable('{{%admin_user}}');
    }
}
