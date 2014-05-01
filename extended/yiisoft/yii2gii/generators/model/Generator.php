<?php

namespace app\extended\yiisoft\yii2gii\generators\model;

class Generator extends \yii\gii\generators\model\Generator
{
	/**
	 * @inheritdoc
	 */
	public function generate()
	{
		$files = [];
		$relations = $this->generateRelations();
		$db = $this->getDbConnection();
		foreach ($this->getTableNames() as $tableName) {
			$className = $this->generateClassName($tableName);
			$tableSchema = $db->getTableSchema($tableName);
			if ($db->tablePrefix && substr($tableName, 0, strlen($db->tablePrefix)) === $db->tablePrefix) {
				$tableName = '{{' . str_replace($db->tablePrefix, '%', $tableName) . '}}';
			}
			$params = [
				'tableName' => $tableName,
				'className' => $className,
				'tableSchema' => $tableSchema,
				'labels' => $this->generateLabels($tableSchema),
				'rules' => $this->generateRules($tableSchema),
				'relations' => isset($relations[$className]) ? $relations[$className] : [],
			];
			$files[] = new \yii\gii\CodeFile(
				\Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php',
				$this->render('model.php', $params)
			);
		}
		return $files;
	}

	public function formView()
	{
		return \Yii::getAlias('@vendor/yiisoft/yii2-gii/generators/model/form.php');
	}
}