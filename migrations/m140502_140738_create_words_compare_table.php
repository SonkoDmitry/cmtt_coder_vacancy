<?php

use yii\db\Schema;

class m140502_140738_create_words_compare_table extends \yii\db\Migration
{
	public function up()
	{
		$this->createTable('{{%words_compare}}', [
				'id' => 'pk',
				'word_first_id' => Schema::TYPE_INTEGER . ' NOT NULL',
				'word_second_id' => Schema::TYPE_INTEGER . ' NOT NULL',
				'compare' => Schema::TYPE_MONEY . '(5,2) NOT NULL',
				'added' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
			]
		);
		$this->addForeignKey('words_compare_to_words_first', '{{%words_compare}}', 'word_first_id', '{{%words}}', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('words_compare_to_words_second', '{{%words_compare}}', 'word_second_id', '{{%words}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey('words_compare_to_words_first', '{{%words_compare}}');
		$this->dropForeignKey('words_compare_to_words_second', '{{%words_compare}}');
		$this->dropTable('{{%words_compare}}');
	}
}
