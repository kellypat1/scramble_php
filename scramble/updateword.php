<!DOCTYPE html>
<html>
<body>

</body>
</html>
<?php
require_once "config.php";

if ($_GET['id']) {
	if (isset ($_POST['submit'])){
		$word=$_POST['word'];
		$score=$_POST['score'];
		$result = mysqli_query($link, "UPDATE words SET word='$word',score='$score' WHERE id = '{$_GET['id']}'");
		if (mysqli_affected_rows($link) > 0) {
			echo "ROW updated.";
			header("location:admin.php");
		}
}
}
?>
<form  method="POST" action="">
			<p>Μορφοποίηση λέξης: <input type="text" name="word" value="" placeholder="type your new word" /> </p>
			
			 <p>Αλλαγή σκορ: <input type="number" name="score"/> 
			 </p>
			 
		 <p><input type="submit" name="submit" value="Submit" /> </p>
		</form>
</body>
</html>