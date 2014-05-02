<?php

use yii\db\Schema;

class m140502_121556_create_words_table extends \yii\db\Migration
{
    public function up()
    {
		$this->createTable('{{%words}}', [
				'id' => 'pk',
				'word' => Schema::TYPE_STRING . ' NOT NULL',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
			]
		);
    }

    public function down()
    {
		$this->dropTable('{{%words}}');
    }
}
