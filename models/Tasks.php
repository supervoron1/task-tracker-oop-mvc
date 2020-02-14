<?php


namespace app\models;

use app\engine\Db;

class Tasks extends DbModel
{
	protected $id = null;
	protected $title;
	protected $author_id;
	protected $status_id;

	protected $props = [
		'title' => false,
		'author_id' => false,
		'status_id' => false,
	];

	public function __construct($title = null, $author_id = null, $status_id = null)
	{
		$this->title = $title;
		$this->author_id = $author_id;
		$this->status_id = $status_id;
	}

	public static function getAllTasks($from, $num)
	{
		$sql = "SELECT tasks.id, tasks.title, 
       			authors.id AS author_id, authors.title AS author_name, status.title AS status_name 
						FROM tasks 
						JOIN authors ON tasks.author_id = authors.id 
						JOIN status ON tasks.status_id = status.id
						LIMIT ?,?";
		return Db::getInstance()->executeLimit($sql, $from, $num);
	}

	public static function getTableName()
	{
		return "tasks";
	}
}
