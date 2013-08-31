<?php
	$name = $tm->clearData($_POST['name']);
	$id = $_POST['id']*1;
	if (!empty($name)) {
		$res = $tm->saveProject($name, $id);
		if($res) {
			header("Location: taskManager.php");
		} else {
			$errMsg = "An error has occurred when adding project";
		}
	}
	else {
		$errMsg = "Please enter the project's name";
	}
?>