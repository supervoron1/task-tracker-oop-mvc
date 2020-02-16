<div class="header">
    <h1>Task Tracker</h1>
    <a href="/tasks/task/" class="add">Новая задача</a>
</div>

<!--Блок селекторов-->
<div class="selectors">
    <select name="authors" id="author-selector" class="author-selector">
        <option value="default" selected>Все авторы (<?= count($authors) ?>)</option>
			<? foreach ($authors as $author): ?>
          <option value="<?= $author['author_name'] ?>"
                  class="author-option"><?= $author['author_name'] ?></option>
			<? endforeach; ?>
    </select>
    <select name="status" id="status-selector">
        <option value="default" selected>Все статусы (<?= count($status) ?>)</option>
			<? foreach ($status as $stat): ?>
          <option value="<?= $stat['status_name'] ?>"
                  class="status-option"><?= $stat['status_name'] ?></option>
			<? endforeach; ?>
    </select>

    <div class="showBy">Показать на странице:
			<? foreach ($perPageChoice as $perPage): ?>
          <a href="/tasks/list/?show=<?= $perPage ?>" class="showBy-icon"><?= $perPage ?></a>
			<? endforeach; ?>
        <a href="/tasks/list/?show=<?= $count ?>" class="showBy-icon big">Все</a>
    </div>
</div>

<!--Блок с задачами-->
<div class="tasks-container">
    <div class="task-div">
        <div id='flex-container'>
            <div class="task-header">Название задачи</div>
            <div class="task-header">Автор</div>
            <div class="task-header">Статус</div>
            <div class="task-header"></div>
            <div class="task-header"></div>
        </div>
    </div>

	<? foreach ($tasks as $task): ?>
      <div id="<?= $task['id'] ?>" class="task task-div" data-author="<?= $task['author_name'] ?>"
           data-status="<?= $task['status_name'] ?>">
          <div id='flex-container'>
              <div class="task-specs"><p><?= $task['title'] ?></p></div>
              <div class="task-specs"><p><?= $task['author_name'] ?></p></div>
              <div class="task-specs"><p><?= $task['status_name'] ?></p></div>
              <div class="task-specs btn">
                  <a href="/tasks/task/?id=<?= $task['id'] ?>" class="edit fas fas fa-pencil-alt"></a>
              </div>
              <div class="task-specs btn">
                  <button data-id="<?= $task['id'] ?>" class="delete fas fas fa-times"></button>
              </div>
          </div>
      </div>
	<? endforeach; ?>
</div>

<!--Блок пагинации-->
<div class="pagination">
	<? if ($page != 1): ?>
      <a href="/tasks/list/?page=<?= $page - 1 ?>&show=<?= $itemsPerPage ?>" class="pag-icon">Previous</a>
	<? endif; ?>
	<? for ($i = 1; $i <= $pagesCount; $i++) {
		if ($page == $i) {
			$class = ' active';
		} else {
			$class = '';
		}
		echo "<a href='/tasks/list/?page=$i&show=$itemsPerPage' class='pag-icon$class'>$i</a> ";
	} ?>
	<? if ($page != $pagesCount): ?>
      <a href="/tasks/list/?page=<?= $page + 1 ?>&show=<?= $itemsPerPage ?>"
         class="pag-icon">Next</a>
	<? endif; ?>
</div>

<!--Скрипт удаления задачи-->
<script>
  let buttons = document.querySelectorAll('.delete');
  buttons.forEach((elem) => {
    elem.addEventListener('click', () => {
      if (confirm('Вы уверены что хотите удалить эту задачу?')) {
        let id = elem.getAttribute('data-id');
        (
          async () => {
            const response = await fetch('/tasks/DeleteTask/', {
              method: 'POST',
              headers: new Headers({
                'Content-Type': 'application/json'
              }),
              body: JSON.stringify({
                id: id
              })
            });
            const answer = await response.json();
            document.getElementById(id).remove();
            document.location.href = "/tasks/list/?page=<?=$page?>&show=<?= $itemsPerPage ?>";
            console.log(answer);
          }
        )();
      }
    })
  });
</script>

<!--Обработка фильтров-->
<script>
  let selector = document.getElementById('author-selector');
  selector.addEventListener('change', (e) => {
    let filteredAuthor = e.target.value;
    let task = document.querySelectorAll('.task');
    task.forEach((elem) => {
      elem.classList.remove('hide');
      let taskAuthor = elem.dataset.author;
      if (filteredAuthor !== taskAuthor) elem.classList.add('hide');
      if (filteredAuthor === 'default') elem.classList.remove('hide');
    });

  });
</script>

<script>
  let select = document.getElementById('status-selector');
  select.addEventListener('change', (e) => {
    let filteredStatus = e.target.value;
    console.log(filteredStatus);
    let status = document.querySelectorAll('.task');
    status.forEach((elem) => {
      elem.classList.remove('hide');
      let taskStatus = elem.dataset.status;
      if (filteredStatus !== taskStatus) elem.classList.add('hide');
      if (filteredStatus === 'default') elem.classList.remove('hide');
    });
  });
</script>

