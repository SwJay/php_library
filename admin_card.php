
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8"/>
	<title>
		孙伟杰的图书馆
	</title>
</head>

<body bgcolor = "pink">
	<h1 align = "center"><?php session_start();echo $_SESSION['username'] ?>：你好！</h1>
	<hr color = "lightskyblue"/>

	<div>
		<a href = "./index.php" onclick="<?php mysqli_close($mydb);?>">log out</a><br>
		<a href = "./admin_book.php" onclick="<?php mysqli_close($mydb);?>">book</a>
	</div>

	<h2 align = "center">借书证管理</h2>
	<form name="search_form" action="" method="post" align="center">
		cno:<input type="text" name="cno">
		name:<input type="text" name="name">
		department:<input type="text" name="department">
		type: S<input type="radio" name="type" value="S">
		T<input type="radio" name="type" value="T">
		<input type="submit" name="insert" value="insert/update">
	</form><br>
	<form name="delete_form" action="" method="post" align="center">
		cno:<input type="text" name="del_cno">
		<input type="submit" name="delete" value="delete">
	</form><br>

<?php
	error_reporting(E_ALL || ~E_NOTICE);
	session_start();
	$mydb = mysqli_connect("localhost:3308",$_SESSION['username'],$_SESSION['pwd']);
	mysqli_select_db($mydb,"library");
	
	if(isset($_POST["insert"])&&$_POST["insert"]){
		$result = mysqli_query($mydb,"SELECT * FROM CARD where cno = '$_POST[cno]'");
		$row = mysqli_fetch_array($result);
		if(!$row){
			echo '<script>alert("succeed in inserting!")</script>';
			mysqli_query($mydb,"insert into card values('$_POST[cno]', '$_POST[name]', '$_POST[department]', '$_POST[type]')");
			mysqli_query($mydb,"create user '$_POST[cno]'@'localhost' identified by ''");
			mysqli_query($mydb,"grant select,insert,update on library.* to '$_POST[cno]'@'localhost'");
		}
		else{
			echo '<script>alert("succeed in updating!")</script>';
			mysqli_query($mydb,"update card set name='$_POST[name]', department='$_POST[department]', type='$_POST[type]' where cno='$_POST[cno]'");
		}

		unset($_POST["insert"]);
	}
	
	if(isset($_POST["delete"])&&$_POST["delete"]){
		$result = mysqli_query($mydb,"SELECT * FROM BORROW where cno = '$_POST[del_cno]'");
		$row = mysqli_fetch_array($result);
		if(!$row||$row["return_date"]!=""){//delete
			echo '<script>alert("succeed in deleting!")</script>';
			mysqli_query($mydb,"delete from borrow where cno = '$_POST[del_cno]'");
			mysqli_query($mydb,"delete from card where cno = '$_POST[del_cno]'");
			mysqli_query($mydb,"drop user '$_POST[del_cno]'@'localhost'");
			unset($_POST["delete"]);
			}
		else{//borrowed book and haven't return yet, can't delete
			echo '<script>alert("Can\'t remove this user until all borrowed books are returned!")</script>';
		}
	}		

	echo "<table border='1' align='center'>
	<tr>
	<th>cno</th>
	<th>name</th>
	<th>department</th>
	<th>type</th>
	</tr>
	";

	$query = "SELECT * FROM CARD";	
	$result = mysqli_query($mydb,$query);
	while($row = mysqli_fetch_array($result)){
	  echo "<tr>";
	  echo "<td>" . $row['cno'] . "</td>";
	  echo "<td>" . $row['name'] . "</td>";
	  echo "<td>" . $row['department'] . "</td>";
	  echo "<td>" . $row['type'] . "</td>";
	  echo "</tr>";
	}
	echo "</table>";
?>

</body>
</html>
