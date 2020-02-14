<?php


namespace app\models;


use app\engine\Db;
use app\interfaces\IModel;

abstract class Repository implements IModel
{
	public function getCount(){
		$tableName = $this->getTableName();
		$sql = "SELECT count(*) AS count FROM {$tableName}";
		return Db::getInstance()->queryOne($sql);
	}

	public function getOne($id)
	{
		$tableName = $this->getTableName();
		$sql = "SELECT * FROM {$tableName} WHERE id = :id";
		return Db::getInstance()->queryObject($sql, ['id' => $id], $this->getEntityClass());
	}

	public function getAll()
	{
		$tableName = $this->getTableName();
		$sql = "SELECT * FROM `{$tableName}`";
		return Db::getInstance()->queryAll($sql);
	}

	public function getTable($table)
	{
		$sql = "SELECT * FROM {$table}";
		return Db::getInstance()->queryAll($sql);
	}

	public function insert(Model $entity)
	{
		$params = [];
		$columns = [];

		foreach ($entity->props as $key => $value) {

			$params[":{$key}"] = $entity->$key;
			$columns[] = "`$key`";
		}
		$columns = implode(", ", $columns);
		$values = implode(", ", array_keys($params));

		$sql = "INSERT INTO `{$this->getTableName()}`({$columns}) VALUES ({$values})";
		Db::getInstance()->execute($sql, $params);
		$entity->id = Db::getInstance()->lastInsertId();
	}

	public function update(Model $entity)
	{
		$params = [];
		$colums = [];
		foreach ($entity->props as $key => $value) {
			if (!$value) continue;
			$params[":{$key}"] = $entity->$key;
			$colums[] .= "`" . $key . "` = :" . $key;
			$entity->props[$key] = false;
		}
		$colums = implode(", ", $colums);
		$params[':id'] = $entity->id;
		$tableName = $this->getTableName();
		$sql = "UPDATE `{$tableName}` SET {$colums} WHERE `id` = :id";

		Db::getInstance()->execute($sql, $params);
	}

	public function save(Model $entity)
	{
		if (is_null($entity->id))
			$this->insert($entity);
		else
			$this->update($entity);
	}

	public function delete(Model $entity)
	{
		$tableName = $this->getTableName();
		$sql = "DELETE FROM `{$tableName}` WHERE `id` = :id";
		return Db::getInstance()->execute($sql, ['id' => $entity->id]);
	}

	abstract public function getTableName();
}
