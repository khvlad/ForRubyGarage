<?php
	$pid = abs((int)$_GET["editp"]);
	if ($pid) {
		$project = $tm->editProject($pid);
	} else{
		header("Location: taskManager.php");
	}
?>