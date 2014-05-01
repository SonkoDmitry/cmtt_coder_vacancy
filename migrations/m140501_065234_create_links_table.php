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
				'news_title' => Schema::TYPE_STRING . ' NULL',
				'news_description' => Schema::TYPE_TEXT . ' NULL',
				'news_pic' => Schema::TYPE_TEXT . ' NULL',
				'news_vk_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'news_fb_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'news_tw_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'news_total_shares' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'updated' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
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
