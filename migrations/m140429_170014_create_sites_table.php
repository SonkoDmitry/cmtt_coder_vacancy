<?php

use yii\db\Schema;

class m140429_170014_create_sites_table extends \yii\db\Migration
{
	public function up()
	{
		$this->createTable('{{%sites}}', [
				'id' => 'pk',
				'name' => Schema::TYPE_STRING . ' NOT NULL',
				'domain' => Schema::TYPE_STRING . ' NOT NULL',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
			]
		);
	}

	public function down()
	{
		$this->dropTable('{{%sites}}');
	}
}
