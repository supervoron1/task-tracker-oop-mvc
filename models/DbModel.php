<?php

namespace app\models;

use app\engine\Db;
use app\interfaces\IModel;

abstract class DbModel extends Model
{

	public static function getOneWhere($field, $value)
	{
		$tableName = static::getTableName();
		$sql = "SELECT * FROM {$tableName} WHERE `$field`=:value";
		return Db::getInstance()->queryObject($sql, ["value" => $value], static::class);
	}

	public static function getCount(){
		$tableName = static::getTableName();
		$sql = "SELECT count(*) AS count FROM {$tableName}";
		return Db::getInstance()->queryOne($sql);
	}

	public static function getOne($id)
	{
		$tableName = static::getTableName();
		$sql = "SELECT * FROM {$tableName} WHERE id = :id";
		return Db::getInstance()->queryObject($sql, ['id' => $id], static::class);
	}

	public static function getAll()
	{
		$tableName = static::getTableName();
		$sql = "SELECT * FROM `{$tableName}`";
		return Db::getInstance()->queryAll($sql);
	}

	public static function getTable($table)
	{
		$sql = "SELECT * FROM {$table}";
		return Db::getInstance()->queryAll($sql);
	}

	public function insert()
	{
		$params = [];
		$columns = [];

		foreach ($this->props as $key => $value) {

			$params[":{$key}"] = $this->$key;
			$columns[] = "`$key`";
		}
		$columns = implode(", ", $columns);
		$values = implode(", ", array_keys($params));

		$sql = "INSERT INTO `{$this->getTableName()}`({$columns}) VALUES ({$values})";
		Db::getInstance()->execute($sql, $params);
		$this->id = Db::getInstance()->lastInsertId();
	}

	public function update()
	{
		$params = [];
		$colums = [];
		foreach ($this->props as $key => $value) {
			if (!$value) continue;
			$params[":{$key}"] = $this->$key;
			$colums[] .= "`" . $key . "` = :" . $key;
			$this->props[$key] = false;
		}
		$colums = implode(", ", $colums);
		$params[':id'] = $this->id;
		$tableName = static::getTableName();
		$sql = "UPDATE `{$tableName}` SET {$colums} WHERE `id` = :id";

		Db::getInstance()->execute($sql, $params);
	}

	public function save()
	{
		if (is_null($this->id))
			$this->insert();
		else
			$this->update();
	}

	public function delete()
	{
		$tableName = static::getTableName();
		$sql = "DELETE FROM `{$tableName}` WHERE `id` = :id";
		return Db::getInstance()->execute($sql, ['id' => $this->id]);
	}

	abstract public static function getTableName();
}
