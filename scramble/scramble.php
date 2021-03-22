<?php
	session_start();
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location:login.php");
    exit;
    
}	
	$username=$_SESSION['username'];
	require_once "config.php";
	

   	
	if(!isset($_SESSION['newword'])) $_SESSION['newword']=true;
	if(!isset($_SESSION['letters'])) $_SESSION['letters']=[];
	if (!isset($_SESSION['help'])) $_SESSION['help']=0;
	if(!isset($_SESSION['helpletter'])) $_SESSION['helpletter']=[];
	if (!isset($_SESSION['score'])) $_SESSION['score']=0;
	$score=$_SESSION['score'];
	
	
	
	if(isset($_POST["find"])){	
		$firsttry=$_POST["firsttry"];
		$firsttry=mb_strtoupper($firsttry);
		if ($firsttry==$_SESSION['word1']) {
			$_SESSION['newword']=true;
			$times=1;
			$_SESSION['help']=0;
			$_SESSION['helpletter']=[];
		}else{
			$_SESSION['newword']=false;
		}
	}
	
	if(isset($_POST["new_game"])){
			$_SESSION['newword']=true;
			$_SESSION['help']=0;
			$_SESSION['helpletter']=[];
			}		

	if ($_SESSION['newword']==true) {
		$randword = mysqli_query($link,"SELECT * FROM words order by RAND() LIMIT 1");
		mb_internal_encoding("UTF-8");
		while($row1 = mysqli_fetch_array($randword)){
			$word=$row1['word'];
			$_SESSION["score"]=$row1['score'];
			function str_split_unicode($word){
					$len = mb_strlen($word, 'UTF-8');
					$temp = [];
					for ($i = 0; $i < $len; $i++) {
				   		$temp[] = mb_substr($word, $i, 1, 'UTF-8');
					}
					$rand=shuffle($temp);
					$word = join("", $temp);
					$split=[];
					foreach($temp as $let){
						array_push($split,$let);
					}
				return $split;
			}
		$_SESSION['letters']=[];
		$_SESSION['word1']=$word;
		$_SESSION['split']= str_split_unicode($_SESSION['word1']);
		$_SESSION['newword']=false;
	}
}

	$wordarray=preg_split('//u',$_SESSION['word1'],null, PREG_SPLIT_NO_EMPTY);

	$sql = "UPDATE users SET appear_time =appear_time+$times, final_score=final_score+$score WHERE  username ='$username' ";
		if ($link->query($sql) === TRUE) {
			//echo "Record updated successfully";
		}
	$result = mysqli_query($link,"SELECT * FROM users WHERE  username ='$username' ");
		while($row = mysqli_fetch_array($result)){
			$appearing=$row['appear_time'];
			$final=$row['final_score'];}

	if (isset($_POST['help'])) {
		array_push($_SESSION['helpletter'],$wordarray[$_SESSION['help']]);
		$_SESSION['help']+=1;
		$_SESSION['score']-=10;
	}
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device width, initial scale = 1.0">
	<title>Scramble</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="mystyle.css">
	<style type="text/css">
        body{ 
            font: 16px Arial, sans-serif bold;
            background-image: url("back1.jpg");
            height: 100%;
            background-size: cover;
            }
        </style>
	<?php error_reporting(0); ?>
</head>
<body>
	
   

	<div class="wrapper">
		<h1 style="font-size:60px;">Scramble</h1>
		<h3>Καλωσήρθες στο παιχνίδι <?php echo $username;?></h3>
		<?php
			echo "Αριθμός παιχνιδιών: " ,$appearing, "<br>", "Συνολικό σκορ παίκτη:" ,$final, "<br>", "<br>";?>
		<h3 id="countdown" style="color: red;"></h3>
		<?php
		for($i=0; $i < count($_SESSION['split']); $i++){
			$key=$_SESSION['split'][$i]; 
	 	if (in_array($key, $_SESSION['letters'])){
	 		echo "<a href= '?key=$key'><button class='btn btn-primary'>$key</button></a>";
	 	}
	 	else{ 
			echo "<a href= '?key=$key'><button class='btn btn-primary'>$key</button></a>";
			}
		}
	?>
		<h3>Βοήθεια: <?php $string=implode(",",$_SESSION['helpletter']);
							echo $string;
							if (count($_SESSION['helpletter'])==3) {
								echo "</br> Δεν έχετε άλλη βοήθεια!";

							} ?></h3>		

		<h3>Πόντοι λέξης:<?php echo $_SESSION['score']; ?> </h3>


<form method="POST" action="">
	<h3>Πληκτρολογήστε την λέξη</h3>
	
    <input type="text" name="firsttry" class="form-control" placeholder="Πληκτρολογήστε εδώ"value="<?php echo $firsttry; ?>">
	<input type="submit" name="find" id="find" value="Το βρήκα!" />
	<input type="submit" name="help" id="help" value="Βοήθεια" <?php if (count($_SESSION['helpletter']) == 3){ ?> disabled <?php   } ?>  />
	<h4 id="timeisup"></h4>
	
</form>




<?php $sql = "INSERT INTO login.users (appear_time) VALUES ('$times')"; ?>

<script type="text/javascript">
		var seconds = localStorage.getItem('remainTime'); //gettting saved time from localstorage
		

	    if(seconds == null || seconds == undefined){
	    	seconds = 60;
	    }


	    function firstPass() {
	      var word = <?php echo json_encode($_SESSION['word1']); ?>;
	      var minutes = Math.round((seconds - 30)/60);
	      var remainingSeconds = seconds % 60;
	      if (remainingSeconds < 10) {
	          remainingSeconds = "0" + remainingSeconds; 
	      }
	      document.getElementById('countdown').innerHTML = minutes + ":" +    remainingSeconds;
	      if (seconds == 0) {
	          clearInterval(countdownTimer);
	          document.getElementById('timeisup').innerHTML = '<input type="submit" name="new_game" id="new_game" value="Νέο παιχνίδι" /></br> Δυστυχώς χάσατε. Η σωστή λέξη ήταν....' +word;
	      } else {    
	          seconds--;
	          localStorage.setItem('remainTime',seconds); 
	      }
	    }
	    var countdownTimer = setInterval('firstPass()', 1000);
	$('#find').click((e) => {
		clearInterval(countdownTimer);
		seconds=60;
  	firstPass();
  })
	<?php if (isset($_POST['new_game'])) {?>
		seconds=60;
  	firstPass();
<?php } ?>

</script>


</div>
</body>
</html>
