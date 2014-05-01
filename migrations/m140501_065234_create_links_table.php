<?php

use yii\db\Schema;

class m140501_065234_create_links_table extends \yii\db\Migration
{
	public function up()
	{
		$this->createTable('{{%links}}', [
				'id' => 'pk',
				'link' => Schema::TYPE_STRING . ' NOT NULL',
				'site_id' => Schema::TYPE_INTEGER . ' NULL',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
			]
		);
		$this->addForeignKey('links_sites', '{{%links}}', 'site_id', '{{%sites}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey('links_sites', '{{%links}}');
		$this->dropTable('{{%links}}');
	}
}
