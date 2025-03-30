<?php

use yii\db\Migration;

/**
 * Class m240701_091010_update_chimes_bpm
 */
class m240701_091010_update_chimes_bpm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%chime}}', 'bpm', 'VARCHAR(254) NOT NULL AFTER `instrument`');
        $this->createIndex('bpm', '{{%chime}}', 'bpm');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%chime}}', 'bpm');
    }
}
