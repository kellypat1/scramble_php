<?php
require_once "config.php";
header('Content-Type: text/html; charset=utf-8');
?>

<html>
<head>
	 <meta charset="UTF-8">
	<style>
	table{
    	border: 1px solid black;
    	margin-top:-900px; 
    	margin-right:200px;
    	float:right;
}
</style>
</head>
<body>

<?php
if (isset ($_POST['submit'])) {
	
	$word = mb_strtoupper($_POST["word"]);
	$score = $_POST["score"];
	
	mysqli_query($link, "INSERT INTO words VALUES(NULL,'$word', '$score')") or
			die("Query error: " . mysqli_connect_error());
			
		if(mysqli_affected_rows($link) ==1) {
		echo "<font color =green size =14>Επιτυχής Υποβολή! :)</font><br />";
		header("location:admin.php");
		}
		else {
		echo "<font color =red size =14>Αποτυχία Υποβολής :(</font> <br />";
		}	
	
 }else
 {
?>
	<form  method="POST" action="">
			<p>Εισαγωγή λέξης: <input type="text" name="word" value="" placeholder="type your new word" /> </p>
			
			 <p>Σκορ: <input type="number" name="score"/> 
			 </p>
			 
		 <p><input type="submit" name="submit" value="Submit" /> </p>
		</form>

<?php
}
$result = mysqli_query($link, "SELECT * FROM words");
		while ($fields = mysqli_fetch_array($result)) {
		echo "Λέξη: $fields[1] | Σκορ: $fields[2] <a href='deleteword.php?id=$fields[0]'>Διαγραφή </a><a href='updateword.php?id=$fields[0]'>Τροποποίηση </a><br />";
		}
$users = mysqli_query($link, "SELECT username,final_score FROM users");
		if ($users->num_rows > 0) {
 			 echo "<table><tr><th>Όνομα χρήστη</th><th>Τελικό σκόρ</th></tr>";
  			// output data of each row
  			while($row = $users->fetch_assoc()) {
    		echo "<tr><td>".$row["username"]."</td><td>".$row["final_score"]."</td></tr>";
  }
  echo "</table>";
}
mysqli_close($link);
?>
</body>
</html>