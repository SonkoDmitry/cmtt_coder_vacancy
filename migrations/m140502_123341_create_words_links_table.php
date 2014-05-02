<?php

use yii\db\Schema;

class m140502_123341_create_words_links_table extends \yii\db\Migration
{
    public function up()
    {
		$this->createTable('{{%words_links}}', [
				'id' => 'pk',
				'word_id' => Schema::TYPE_INTEGER . ' NOT NULL',
				'link_id' => Schema::TYPE_INTEGER . ' NOT NULL',
				'type' => Schema::TYPE_STRING . ' NOT NULL',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
			]
		);
		$this->addForeignKey('words_links_to_words', '{{%words_links}}', 'word_id', '{{%words}}', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('words_links_to_links', '{{%words_links}}', 'link_id', '{{%links}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('words_links_to_words', '{{%words_links}}');
		$this->dropForeignKey('words_links_to_links', '{{%words_links}}');
		$this->dropTable('{{%words_links}}');
    }
}
