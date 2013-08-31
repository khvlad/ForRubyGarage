<?php
	//функция которая выводит все задачи проекта переданного вторым аргументом. Первым агрументом передается объект базы данных, в которой функция осуществяет поиск задач.
	function displayTask($tm, $id) {
		//сначала выводим активные задачи
		$tasks = $tm->getTasks('active', $id);
		if ($tasks) {
			foreach ($tasks as $task) {
				++$GLOBALS['countTasks'];
				$msg = nl2br($task["msg"]);
				?>
				<div>
				<b><?=$task["name"]?></b><br />
				<?=$msg?>
				<?php
					if ($task["deadline"] or $task["deadlineTime"]) {
						?>
						<div aling='left'>
						<i>Deadline:</i> <?=$task["deadline"]?> <?=$task["deadlineTime"]?>
						</div>
						<?php	
					}
				?>
				<div align='left'>
					<a href='taskManager.php?done=<?=$task["id"]?>'>Done</a>
					<a href='taskManager.php?edit=<?=$task["id"]?>'>Edit</a>
					<?php
						if (!($task["priority"] == 1)) {
							?>
							<a style="text-decoration: none" href='taskManager.php?pr=<?=$task["priority"]?>&up=<?=$task["id"]?>&pid=<?=$id?>'>&uarr;</a>
							<?php
						}
						$maxPr = $tm->getMaxPriorityOfProject($id);
						if (!($task["priority"] == $maxPr)) {
							?>
							<a style="text-decoration: none" href='taskManager.php?pr=<?=$task["priority"]?>&dw=<?=$task["id"]?>&pid=<?=$id?>'>&darr;</a>
							<?php
						}
					?>				
					<a href='taskManager.php?del=<?=$task["id"]?>'>Delete</a>
				</div></div><br />
				<?php
			}
		}
		//теперь выведем задачи с меткой "Done"
		$tasks = $tm->getTasks('done', $id);
		if ($tasks) {
			foreach ($tasks as $task) {
				$msg = nl2br($task["msg"]);
				?>
				<font color="grey">
				<div>
				<b><?=$task["name"]?></b> - Done<br />
				<?=$msg?>
				<?php
					if ($task["deadline"] or $task["deadlineTime"]) {
						?>
						<div aling='left'>
						<i>Deadline:</i> <?=$task["deadline"]?> <?=$task["deadlineTime"]?>
						</div>
						<?php	
					}
				?>
				<div align='left'>
					<a href='taskManager.php?del=<?=$task["id"]?>'>Delete</a>
				</div></div></font><br />
				<?php
			}
		}
	}

	$countTasks = 0; //суммарное число активных задач
	$projects = $tm->getProjects();
	foreach ($projects as $project) {
		//выводим названия проекта
		echo "<h3>{$project['name']} <small><small><a href='taskManager.php?editp={$project["id"]}'>(Edit</a>, <a href='taskManager.php?delp={$project["id"]}'>Delete)</a></small></small></h3>";
		//вызываем функцию, для вывода задач этого проекта
		displayTask($tm, $project["id"]);
		echo "<hr />";
	}
	//выведем задачи не принадлежащие к проектам, передавая вместо id проекта NULL
	displayTask($tm, NULL);
	echo "<hr />";

	if ($countTasks) {
		echo "Total tasks: $countTasks";
	} else {
		echo "You don't have any tasks. Rejoice!";
	}
?>