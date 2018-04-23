
<!DOCTYPE html>
<html>

<head>
	<meta charset = "utf-8"/>
	<title>
		孙伟杰的图书馆
	</title>
</head>

<body bgcolor = "pink">
	<h1 align = "center">欢迎来访</h1>
	<hr color = "lightskyblue"/>

<form name="adm_login" action="" method="post" align="center"> <!--administrator login-->
	Administrator:<input type="text" name="username"><br>
	Password:<input type="password" name="pwd"><br>
	<input type="submit" name="submit1" value="Log in">
</form>
<br>

<form name="usr_login" action="" method="post" align="center"> <!--user login-->
	cno:<input type="text" name="username"><br>
	<input type="submit" name="submit2" value="Log in">
</form>
<br>

<?php
error_reporting(E_ALL || ~E_NOTICE);
session_start();//store global variable
if(isset($_POST["submit1"])&&$_POST["submit1"]){//administrator login
	$_SESSION['username'] = $_POST["username"];
	$_SESSION['pwd'] = $_POST["pwd"];
	$mydb= mysqli_connect("localhost:3308",$_POST["username"],$_POST["pwd"]);// connect mysql
	if (!$mydb){
  		echo '<script>alert("wrong!")</script>';
  		die();
	}
	else{
		header("location: admin_book.php");// jump to the book manage site
		mysqli_close($mydb);
	}
}
else if(isset($_POST["submit2"])&&$_POST["submit2"]){// user login
	$_SESSION['username'] = $_POST["username"];
	$mydb= mysqli_connect("localhost:3308",$_POST["username"],'');
	if (!$mydb){
  		echo '<script>alert("wrong!")</script>';
  		die();
	}
	else{
		header("location: user_book.php");// jumpt to the book search site
		mysqli_close($mydb);
	}
}
?>

</body>
</html>
