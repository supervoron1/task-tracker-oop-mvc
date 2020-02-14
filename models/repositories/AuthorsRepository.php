<?php


namespace app\models\repositories;


use app\models\entities\Authors;
use app\models\Repository;

class AuthorsRepository extends Repository
{
	public function getEntityClass()
	{
		return Authors::class;
	}

	public function getTableName()
	{
		return "authors";
	}
}
