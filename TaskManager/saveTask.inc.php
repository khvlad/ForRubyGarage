<?php
	$name = $tm->clearData($_POST['name']);
	$msg = $tm->clearData($_POST['msg']);
	$project = $_POST['project']*1;
	$id = $_POST['id']*1;
	$priority = $_POST['priority']*1;
	$deadline = $tm->clearData($_POST['deadline']);
	$deadlineTime = $tm->clearData($_POST['deadlineTime']);
	if (!empty($name)) {
		$res = $tm->saveTask($name, $msg, $project, $deadline, $deadlineTime, $id, $priority);
		if($res) {
			header("Location: taskManager.php");
		} else {
			$errMsg = "An error has occurred when adding task";
		}
	}
	else {
		$errMsg = "Please enter the task's name";
	}
?>