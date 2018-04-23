
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
		<a href = "./index.php" onclick="<?php mysqli_close($mydb);?>">log out</a><br><!--return to the login site-->
		<a href = "./user_book.php" onclick="<?php mysqli_close($mydb);?>">book</a><!--jump to the book search site-->
	</div>

	<h2 align = "center">借还记录</h2>
	<form name="search_form" action="" method="post" align="center">
		bno:<input type="text" name="ret_bno">
		<input type="submit" name="return" value="return">
	</form><br>
<?php
	error_reporting(E_ALL || ~E_NOTICE);
	session_start();
	$mydb = mysqli_connect("localhost:3308",$_SESSION['username'],'');
	mysqli_select_db($mydb,"library");
	$query = "SELECT * FROM BORROW where cno = '$_SESSION[username]'";

	if(isset($_POST["return"])&&$_POST["return"]){
		$result = mysqli_query($mydb,"SELECT * FROM BORROW where bno = '$_POST[ret_bno]'and cno = '$_SESSION[username]'and return_date=''");
		$row = mysqli_fetch_array($result);
		if($row){// borrowed and haven't return
			echo '<script>alert("succeed in returning!")</script>';
			$string = "update borrow set return_date = '" . date(Y.m.d) . "'where cno = '$row[cno]'and bno = '$row[bno]'";
			mysqli_query($mydb,$string);
			mysqli_query($mydb,"update book set stock = stock+1 where bno = '$row[bno]'");
		}
		else
			echo '<script>alert("Haven\'t borrowed it!")</script>';
		
		unset($_POST["borrow"]);	
	}	

	echo "<table border='1' align='center'>
	<tr>
	<th>cno</th>
	<th>bno</th>
	<th>borrow_date</th>
	<th>return_date</th>
	</tr>
	";

	$result = mysqli_query($mydb,$query);
	while($row = mysqli_fetch_array($result)){
	  echo "<tr>";
	  echo "<td>" . $row['cno'] . "</td>";
	  echo "<td>" . $row['bno'] . "</td>";
	  echo "<td>" . $row['borrow_date'] . "</td>";
	  echo "<td>" . $row['return_date'] . "</td>";
	  echo "</tr>";
	}
	echo "</table>";
?>

</body>
</html>
