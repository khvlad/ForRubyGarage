<?php
	$id = abs((int)$_GET["done"]);
	if ($id) {
		$res = $tm->markDone($id);
	}
	header("Location: taskManager.php");
?>