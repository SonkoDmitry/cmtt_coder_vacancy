<?php

use yii\db\Schema;

class m140502_144418_add_fields_to_compares extends \yii\db\Migration
{
	public function up()
	{
		$this->addColumn('{{%words_compare}}', 'link_first_id', Schema::TYPE_INTEGER . ' NOT NULL AFTER `id`');
		$this->addColumn('{{%words_compare}}', 'link_second_id', Schema::TYPE_INTEGER . ' NOT NULL AFTER `word_first_id`');
		$this->addColumn('{{%words_compare}}', 'type', Schema::TYPE_STRING . ' NOT NULL AFTER `word_second_id`');
		$this->addForeignKey('words_compare_to_links_first', '{{%words_compare}}', 'link_first_id', '{{%links}}', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('words_compare_to_links_second', '{{%words_compare}}', 'link_second_id', '{{%links}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey('words_compare_to_links_first', '{{%words_compare}}');
		$this->dropForeignKey('words_compare_to_links_second', '{{%words_compare}}');
		$this->dropColumn('{{%words_compare}}', 'link_first_id');
		$this->dropColumn('{{%words_compare}}', 'link_second_id');
		$this->dropColumn('{{%words_compare}}', 'type');
	}
}
