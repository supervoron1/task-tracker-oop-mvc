<?php


namespace app\models;


class Authors extends DbModel
{
	protected $id = null;
	protected $title;

	protected $props = [
		'title' => false,

	];

	public function __construct($title)
	{
		$this->title = $title;
	}

	public static function getTableName()
	{
		return "authors";
	}
}
