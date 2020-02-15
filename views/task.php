<div class="header">
  <?if (empty($task)) :?>
    <h1>Добавление новой задачи</h1>
   <? else :?>
    <h1>Редактирование задачи</h1>
  <?endif;?>
</div>

<!--Блок формы новой задачи-->
<form action="/tasks/list/" id="addTaskBtn" class="addForm">
    <input type="text" id="title" value="<?=$task->title?>" placeholder="Название задачи" required>
    <input type="text" id="author" value="<?=$task->author_name?>" placeholder="Имя автора" required>
    <select name="status" id="status" required>
	    <?if (empty($task)) :?>
          <option value disabled selected>Выбрать статус</option>
	    <? else :?>
          <option value="<?=$task->status_id?>" selected><?=$task->status_name?></option>
        <?endif; ?>

        <? foreach ($status as $stat): ?>
          <?if ($stat['title'] != $task->status_name):?>
            <option value="<?= $stat['id'] ?>"><?= $stat['title'] ?></option>
          <?endif; ?>
        <? endforeach; ?>
    </select>
</form>
<button form="addTaskBtn" id="" class="add">Добавить задачу</button>

<!--Скрипт добавления новой задачи-->
<script>
  document.querySelector('.add').addEventListener('click', () => {
    let id = <?=$task->id?>;
    let title = document.getElementById('title').value;
    let author = document.getElementById('author').value;
    let status = document.getElementById('status').value;
    let data = {id, title, author, status};
    console.log(data);
    (
      async () => {
        const response = await fetch('/tasks/addTask/', {
          method: 'POST',
          headers: new Headers({
            'Content-Type': 'application/json',
          }),
          body: JSON.stringify(data)
        });
        const answer = await response.json();
        document.location.href = "/tasks/list/";
        console.log(answer);
      }
    )();
  });
</script>
