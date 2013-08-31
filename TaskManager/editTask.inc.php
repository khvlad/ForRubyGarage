<?php
	$id = abs((int)$_GET["edit"]);
	if ($id) {
		$task = $tm->editTask($id);
	} else{
		header("Location: taskManager.php");
	}
?>