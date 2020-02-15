<?php

namespace app\models\entities;

use app\models\Model;

class Tasks extends Model
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

	public function __construct($id = null, $title = null, $author_id = null, $status_id = null)
	{
		$this->id = $id;
		$this->title = $title;
		$this->author_id = $author_id;
		$this->status_id = $status_id;
	}
}
