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

	// Функция рендера списка задач
	public function actionList()
	{
		// получение номера текущей страницы
		if (isset((new Request())->getParams()['page'])) {
			$page = (int)(new Request())->getParams()['page'];
		} else {
			$page = 1;
		}
		// получение кол-ва задач на странице
		if (isset((new Request())->getParams()['show'])) {
			$itemsPerPage = (int)(new Request())->getParams()['show'];
		} else {
			$itemsPerPage = 5;
		}
		// определение необходимых параметров для отображения кол-ва записей на странице
		$perPageChoice = [2, 3, 5];
		// получение первого элемента для LIMIT в SQL запросе
		$from = ($page - 1) * $itemsPerPage;
		// получение списка задач на страницу из диапозона LIMIT
		$tasks = (new TasksRepository())->getAllTasks($from, $itemsPerPage);
		// получение общего количества задач из БД
		$count = (int)(new TasksRepository())->getCount()['count'];
		// получение кол-ва страниц для блока пагинации
		$pagesCount = ceil($count / $itemsPerPage);
		// получение списка авторов только тех заданий, что отображенны на странице
		$authors = $this->unique_multidim_array($tasks, 'author_name');
		// получение списка статусов только тех заданий, что отображенны на странице
		$status = $this->unique_multidim_array($tasks, 'status_name');
		// рендер страницы и передача параметров для работы на странице
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

	// Функция рендера страницы с добавлением задачи
	public function actionTask()
	{
		// Получение задачи для редактирования
		$id = (int)(new Request())->getParams()['id'];
		$task = (new TasksRepository())->getTask($id);
		// получение списка статусов
		$status = (new TasksRepository())->getTable('status');
		// рендер страницы и передача параметров для работы на странице
		echo $this->render('task', [
			'status' => $status,
			'task' => $task,
		]);
	}

	// Функция добавления нового задания или редактирования существующего
	public function actionAddTask()
	{
		// Получение данных пришедших из формы со страницы добавления/рекактирования задания
		$id = (new Request())->getParams()['id'];
		$title = (new Request())->getParams()['title'];
		$status = (new Request())->getParams()['status'];
		$author = (new Request())->getParams()['author'];

		// Проверка что все данные были введены.
		if ($title == '') unset($title);
		if ($author == '') unset($author);
		if ($status == '') unset($status);
		if (empty($title) || empty($author) || empty($status)) exit('Заполните все поля!');

		// Удаление пробелов и др.символов из начала и конца строки, удаление всех NULL-байты, HTML- и PHP-теги
		// Удаление экранирования символов, преобразование специальных символов в HTML-сущности
		$title = trim(htmlspecialchars(strip_tags(stripslashes($title))));
		$author = trim(htmlspecialchars(strip_tags(stripslashes($author))));
		$status = trim(htmlspecialchars(strip_tags(stripslashes($status))));

		// Преобразования первой буквы имени автора в заглавную, в том числе кириллица
		$author = $this->mb_ucfirst($author);
		// Получение всех авторов из БД
		$authors = (new AuthorsRepository())->getAll();
		// Проверка существует ли полученный из формы автор в БД
		// Если да, то $author присваивается индекс автора из БД
		// Если нет, то создается новая запись в БД и $author присваивается новых индекс
		$key = array_search($author, array_column($authors, 'title'));
		if ($key) {
			$author = $authors[$key]['id'];
		} else {
			$author = new Authors($author);
			(new AuthorsRepository())->save($author);
			$authors = (new AuthorsRepository())->getAll();
			$author = $authors[array_key_last($authors)]['id'];
		}

		// Сохранение новой задачи в БД
		$task = new Tasks($id, $title, $author, $status);
		(new TasksRepository())->save($task);

		header('Content-Type: application/json');
		echo json_encode(['message' => 'Новая задача успешно добавлена.']);
		die;
	}

	// Функция удаления задания
	public function actionDeleteTask()
	{
		$id = (new Request())->getParams()['id'];
		$task = (new TasksRepository())->getOne($id);
		(new TasksRepository())->delete($task);

		header('Content-Type: application/json');
		echo json_encode(['status' => 'Задание удалено']);
		die;
	}


}
