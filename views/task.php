<div class="header">
    <h1>Добавление новой задачи</h1>
</div>

<form action="/tasks/list/" id="addTaskBtn" class="addForm">
    <input type="text" id="title" placeholder="Название задачи" required>
    <input type="text" id="author" placeholder="Имя автора" required>
    <select name="status" id="status" required>
        <option value disabled selected>Выбрать статус</option>
			<? foreach ($status as $stat): ?>
          <option value="<?= $stat['id'] ?>"><?= $stat['title'] ?></option>
			<? endforeach; ?>
    </select>
</form>
<button form="addTaskBtn" class="add">Добавить задачу</button>

<script>
  document.querySelector('.add').addEventListener('click', () => {
    let title = document.getElementById('title').value;
    let author = document.getElementById('author').value;
    let status = document.getElementById('status').value;
    let data = {title, author, status};
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
