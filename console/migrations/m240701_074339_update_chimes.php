<?php

use yii\db\Migration;

/**
 * Class m240701_074339_update_chimes
 */
class m240701_074339_update_chimes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%chime}}', 'instrument', 'VARCHAR(254) NOT NULL AFTER `title`');
        $this->createIndex('instrument', '{{%chime}}', 'instrument');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%chime}}', 'instrument');
    }
}
