<?php
	$id = abs((int)$_GET["del"]);
	if ($id) {
		$res = $tm->deleteTask($id);
	}
	header("Location: taskManager.php");
?>