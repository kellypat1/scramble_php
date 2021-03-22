<?php
require_once "config.php";

if ($_GET['id']) {


	$result = mysqli_query($link, "DELETE FROM words WHERE id = '{$_GET['id']}'");
	if (mysqli_affected_rows($link) > 0) {
		echo "ROW deleted.";
		header("location:admin.php");
	}

}

?>