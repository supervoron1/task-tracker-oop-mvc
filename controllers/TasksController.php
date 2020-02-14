<?php


namespace app\controllers;

use app\engine\Request;
use app\models\entities\Authors;
use app\models\entities\Tasks;
use app\models\repositories\AuthorsRepository;
use app\models\repositories\TasksRepository;

class TasksController extends Controller
{

	public function actionIndex()
	{
		echo $this->render('index');
	}

	public function actionList()
	{
		if (isset((new Request())->getParams()['page'])) {
			$page = (int)(new Request())->getParams()['page'];
		} else {
			$page = 1;
		}

		if (isset((new Request())->getParams()['show'])) {
			$itemsPerPage = (int)(new Request())->getParams()['show'];
		} else {
			$itemsPerPage = 5;
		}

		$from = ($page - 1) * $itemsPerPage;

		$tasks = (new TasksRepository())->getAllTasks($from, $itemsPerPage);
		$count = (int)(new TasksRepository())->getCount()['count'];
		$perPageChoice = [2, 3, 5];
		$pagesCount = ceil($count / $itemsPerPage);
		$authors = $this->unique_multidim_array($tasks, 'author_name');
		$status = $this->unique_multidim_array($tasks, 'status_name');
		echo $this->render('tasks', [
			'tasks' => $tasks,
			'status' => $status,
			'authors' => $authors,
			'pagesCount' => $pagesCount,
			'itemsPerPage' => $itemsPerPage,
			'perPageChoice' => $perPageChoice,
			'count' => $count,
			'page' => $page,
		]);
	}

	public function actionTask()
	{
		$status = (new TasksRepository())->getTable('status');
		$authors = (new AuthorsRepository())->getAll();
		echo $this->render('task', [
			'status' => $status,
			'authors' => $authors
		]);
	}

	public function actionAddTask()
	{
		$title = (new Request())->getParams()['title'];
		$status = (new Request())->getParams()['status'];
		$author = (new Request())->getParams()['author'];

		if ($title == '') unset($title);
		if ($author == '') unset($author);
		if ($status == '') unset($status);
		if (empty($title) || empty($author) || empty($status)) exit('Заполните все поля!');


		$title = trim(htmlspecialchars(strip_tags(stripslashes($title))));
		$author = trim(htmlspecialchars(strip_tags(stripslashes($author))));
		$status = trim(htmlspecialchars(strip_tags(stripslashes($status))));

		$author = $this->mb_ucfirst($author);
		$authors = (new AuthorsRepository())->getAll();
		$key = array_search($author, array_column($authors, 'title'));
		if ($key) {
			$author = $authors[$key]['id'];
		} else {
			$author = new Authors($author);
			(new AuthorsRepository())->save($author);
			$authors = (new AuthorsRepository())->getAll();
			$author = $authors[array_key_last($authors)]['id'];
		}

		$task = new Tasks($title, $author, $status);
		(new TasksRepository())->save($task);

		header('Content-Type: application/json');
		echo json_encode(['message' => 'Новая задача успешно добавлена.']);
		die;
	}

	public function actionDeleteTask()
	{
		$id = (new Request())->getParams()['id'];
		$task = (new TasksRepository())->getOne($id);
		(new TasksRepository())->save($task);

		header('Content-Type: application/json');
		echo json_encode(['status' => 'Задание удалено']);
		die;
	}


}
