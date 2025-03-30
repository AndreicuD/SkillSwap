<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240518_142815_base_structure
 */
class m240518_142815_base_structure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $now = date('Y-m-d h:m:s');

        //first we need to adjust the default user table
        $this->alterColumn('{{%user}}', 'created_at', 'DATETIME NOT NULL AFTER `verification_token`');
        $this->alterColumn('{{%user}}', 'updated_at', 'TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `created_at`');
        $this->addColumn('{{%user}}', 'firstname', 'VARCHAR(254) NOT NULL AFTER `email`');
        $this->addColumn('{{%user}}', 'lastname', 'VARCHAR(254) NOT NULL AFTER `firstname`');
        $this->addColumn('{{%user}}', 'sex', "ENUM('F','M') NULL DEFAULT NULL AFTER `lastname`");
        $this->addColumn('{{%user}}', 'phone', 'VARCHAR(32) NULL DEFAULT NULL AFTER `sex`');
        $this->addColumn('{{%user}}', 'birth_date', 'DATE NULL DEFAULT NULL AFTER `phone`');
        $this->alterColumn('{{%user}}', 'username', 'VARCHAR(254) NULL DEFAULT NULL');
        $this->alterColumn('{{%user}}', 'auth_key', 'VARCHAR(32) NULL DEFAULT NULL');
        $this->alterColumn('{{%user}}', 'password_hash', 'VARCHAR(255) NULL DEFAULT NULL');
        $this->alterColumn('{{%user}}', 'email', 'VARCHAR(254) NOT NULL AFTER `username`');
        $this->alterColumn('{{%user}}', 'auth_key', 'VARCHAR(32) NULL DEFAULT NULL AFTER `status`');
        $this->alterColumn('{{%user}}', 'password_hash', 'VARCHAR(254) NULL DEFAULT NULL AFTER `auth_key`');
        $this->alterColumn('{{%user}}', 'password_reset_token', 'VARCHAR(254) NULL DEFAULT NULL AFTER `password_hash`');
        $this->alterColumn('{{%user}}', 'verification_token', 'VARCHAR(254) NULL DEFAULT NULL AFTER `password_reset_token`');
        $this->createIndex('auth_key', '{{%user}}', 'auth_key', true);

        //then we need to install the base rbac tables for future use
        $this->createTable('{{%auth_rule}}', [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull(),
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
            'KEY `idx-auth_item-type` (`type`)',
            'FOREIGN KEY (rule_name) REFERENCES auth_rule (name) 
                ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%auth_item_child}}', [
            'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
            'child' => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES auth_item (name) 
                ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES auth_item (name) 
                ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%auth_assignment}}', [
            'item_name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES auth_item (name) 
                ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%chime}}', [
            'id' => $this->primaryKey(11)->unsigned(),
            'public_id' => $this->string(36)->notNull()->defaultExpression('UUID()')->unique(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'active' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(1),
            'public' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(0),
            'likes_count' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'title' => $this->string(254)->notNull(),
            'content' => 'MEDIUMTEXT NOT NULL',
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('current_timestamp() ON UPDATE current_timestamp()'),
        ], $tableOptions);
        $this->createIndex('user_id_active_public', '{{%chime}}', 'user_id,active,public');

        $this->createTable('{{%chime_like}}', [
            'id' => $this->primaryKey(11)->unsigned(),
            'chime_id' => $this->integer(11)->unsigned()->notNull(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('current_timestamp()'),
        ], $tableOptions);
        $this->createIndex('chime_user', '{{%chime_like}}', 'chime_id,user_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240518_142815_base_structure cannot be reverted.\n";

        return false;
    }
}
