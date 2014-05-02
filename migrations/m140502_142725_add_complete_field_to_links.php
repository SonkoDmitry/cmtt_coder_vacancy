<?php

use yii\db\Schema;

class m140502_142725_add_complete_field_to_links extends \yii\db\Migration
{
    public function up()
    {
		$this->addColumn('{{%links}}', 'completed', Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0');
    }

    public function down()
    {
		$this->dropColumn('{{%links}}', 'completed');
    }
}
