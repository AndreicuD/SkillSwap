<?php

use yii\db\Migration;

/**
 * Class m240519_150049_initial_datas
 */
class m240519_150049_initial_datas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $now = date('Y-m-d h:m:s');

        // the first user, the owner of the app
        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'andrei',
            'email' => 'urecheatu007@gmail.com',
            'firstname' => 'Andrei',
            'lastname' => 'Hutanu',
            'sex' => 'M',
            'phone' => '',
            'birth_date' => NULL,
            'status' => 10,
            'auth_key' => '62XosHOiCccwkrTCij676SF_rXyUQLl2',
            'password_hash' => '$2y$13$M8mo4D3ct94rBqDMcqr2uuq8Yz3CTujfxeEg7a13yHETP3NS/apRi',
            'password_reset_token' => NULL,
            'verification_token' => NULL,
            'created_at' => $now,
        ]);

        // RBAC - the access roles
        $this->batchInsert(
            '{{%auth_item}}',
            ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
            [
                ['admin', 1, 'Administrator, some minor restrictions', NULL, NULL, NULL, NULL],
                ['member', 1, 'Registered users, members of this site', NULL, NULL, NULL, NULL],
                ['superadmin', 1, 'Unlimited powa!!!', NULL, NULL, NULL, NULL],
            ]
        );

        // RBAC - the hierarchy of the roles
        $this->batchInsert(
            '{{%auth_item_child}}',
            ['parent', 'child'],
            [
                ['superadmin', 'admin'],
            ]
        );

        // the first user is superadmin
        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'superadmin',
            'user_id' => 1,
            'created_at' => NULL,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240519_150049_initial_datas cannot be reverted.\n";

        return false;
    }
}
