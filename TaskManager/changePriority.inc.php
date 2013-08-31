<?php
	$priority = abs((int)$_GET["pr"]);
	$project_id = abs((int)$_GET["pid"]);
	if ($_GET["up"]) {
		$id = abs((int)$_GET["up"]);
		if ($id) {
			$res = $tm->priorityUp($id, $priority-1, $project_id);
		}
	}
	if ($_GET["dw"]) {
		$id = abs((int)$_GET["dw"]);
		if ($id) {
			$res = $tm->priorityDown($id, $priority+1, $project_id);
		}
	}
	header("Location: taskManager.php");
?>