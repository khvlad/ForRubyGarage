<?php
	$id = abs((int)$_GET["delp"]);
	if ($id) {
		$res = $tm->deleteProject($id);
	}
	header("Location: taskManager.php");
?>