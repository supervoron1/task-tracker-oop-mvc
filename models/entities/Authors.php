<?php


namespace app\models\entities;


use app\models\Model;

class Authors extends Model
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
}
