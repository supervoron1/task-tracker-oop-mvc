<?php

namespace app\models\repositories;


use app\engine\Db;
use app\models\Repository;
use app\models\entities\Tasks;

class TasksRepository extends Repository
{
	public function getEntityClass()
	{
		return Tasks::class;
	}

	public function getAllTasks($from, $num)
	{
		$sql = "SELECT tasks.id, tasks.title, 
       			authors.id AS author_id, authors.title AS author_name, status.title AS status_name 
						FROM tasks 
						JOIN authors ON tasks.author_id = authors.id 
						JOIN status ON tasks.status_id = status.id
						LIMIT ?,?";
		return Db::getInstance()->executeLimit($sql, $from, $num);
	}

	public function getTableName()
	{
		return "tasks";
	}
}
