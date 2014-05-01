<?php

use yii\db\Schema;

class m140501_124200_create_log_table extends \yii\db\Migration
{
	public function up()
	{
		$this->createTable('{{%logs}}', [
				'id' => 'pk',
				'link_id' => Schema::TYPE_INTEGER . ' NOT NULL',
				'news_vk_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'news_fb_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'news_tw_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'news_total_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
			]
		);
		$this->addForeignKey('logs_links', '{{%logs}}', 'link_id', '{{%links}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey('logs_links', '{{%logs}}');
		$this->dropTable('{{%logs}}');
	}
}
