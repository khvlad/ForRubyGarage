<?php
	define("DEBUG", 0);
	if (DEBUG) {
		error_reporting(E_ALL ^ E_NOTICE);
	} else {
		error_reporting(0);
	}

	require "TaskDB.class.php";
	//Создаем объект для работы с базой данных менеджера задач
	$tm = new TaskDB();
	$errMsg = '';
	$id = NULL;
	$task = array();

	//Проверим произошло ли какое-то событие, если да, подключим файл с кодом для него
	if ($_SERVER["REQUEST_METHOD"] == 'POST') {
		if ($_POST['type'] == 'task') {
			require "saveTask.inc.php";
		} elseif ($_POST['type'] == 'project') {
			require "saveProject.inc.php";
		}
	} elseif (isset($_GET["done"])) {
		require "markDone.inc.php";
	} elseif (isset($_GET["edit"])) {
		require "editTask.inc.php";
	} elseif (isset($_GET["editp"])) {
		require "editProject.inc.php";
	} elseif (isset($_GET["pr"])) {
		require "changePriority.inc.php";
	} elseif (isset($_GET["del"])) {
		require "deleteTask.inc.php";
	} elseif (isset($_GET["delp"])) {
		require "deleteProject.inc.php";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title>Task Manager</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<body>

<h1>Task Manager</h1>
<?php
	if ($errMsg) {
		echo $errMsg;
	}
?>

<!-- Создадим две формы: для проекта и для задачи. Они будут одновременно служить и для создания новой и если понадобиться отредактировать существующую -->
<h3>Add Project</h3>
<form action="taskManager.php" method="post">
	<input type="text" name="type" value="project" style="display:none" />
	<?php
		if ($pid) {
			?>
			<input type="text" name="id" value="<?=$pid?>" style="display:none" />
			<?php
		}
	?>
	Project name:<br />
	<input type="text" name="name" value="<?=$project['name']?>" /><br />
	<input type="submit" value="Save" />
</form>
<hr />

<h3>Add Task</h3>
<form action="taskManager.php" method="post">
	<input type="text" name="type" value="task" style="display:none" />
	<?php
		if ($id) {
			?>
			<input type="text" name="id" value="<?=$id?>" style="display:none" />
			<input type="text" name="priority" value="<?=$task['priority']?>" style="display:none" />
			<?php
		}
	?>
	Task name:<br />
	<input type="text" name="name" value="<?=$task['name']?>" /><br />
	Description:<br />
	<textarea name="msg" cols="30" rows="3"><?=$task['msg']?></textarea><br />
	Project:<br />
	<select name="project">
	    <option selected value></option>
	    <?php
	    	//Получим список всех проектов и выведем его в выпадающий список
	    	$projects = $tm->getProjects();
	    	foreach ($projects as $project) {
	    		echo "<option ";
	    		if ($project['id'] == $task['project_id']) {
	    			echo "selected ";
	    		}
	    		echo "value=\"{$project['id']}\">{$project['name']}</option>";
	    	}
	    ?>
	</select><br />
	Deadline:<br />
	<input type="date" name="deadline" value="<?=$task['deadline']?>" />
	<input type="time" name="deadlineTime" value="<?=$task['deadlineTime']?>" /><br />
	<input type="submit" value="Save" />
</form>
<hr />

<?php
	require "getall.inc.php";
?>

</body>
</html>
