
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
		<a href = "./admin_card.php" onclick="<?php mysqli_close($mydb);?>">card</a><!--jump to the card manage site-->
	</div>

	<h2 align = "center">书籍管理</h2>
	<form name="search_form" action="" method="post" align="center">
		bno:<input type="text" name="bno">
		category:<input type="text" name="category">
		title:<input type="text" name="title"><br>
		press:<input type="text" name="press">
		year:<input type="text" name="year">
		author:<input type="text" name="author"><br>
		price:<input type="text" name="price">
		total:<input type="text" name="total">
		stock:<input type="text" name="stock"><br>
		<input type="submit" name="insert" value="insert/update"><!--do insert when no corresponding bno exits; do update when bno exits-->
	</form><br>

<form name="batch_form" action="" method="post" align="center"><!--batch insertion-->
	address<input type="text" name="address">
	<input type="submit" name="batch" value="batch insertion">
</form><br>

<?php
	error_reporting(E_ALL || ~E_NOTICE);
	session_start();
	$mydb = mysqli_connect("localhost:3308",$_SESSION['username'],$_SESSION['pwd']);
	mysqli_select_db($mydb,"library");

	if(isset($_POST["batch"])&&$_POST["batch"]){// batch insertion
		$fp = fopen("$_POST[address]", "r") or die("Unable to open file!");// read file
		while(!feof($fp)){
			$string = explode(', ', fgets($fp));// transfer the line into an array
			$result = mysqli_query($mydb,"SELECT * FROM BOOK where bno = '$string[0]'");
			$row = mysqli_fetch_array($result);
			if($row){// if bno exits, do updating
				$string[8] = $string[7] + $row['stock'];// stock += num
				$string[7] += $row['total'];// total += num
				$query = "update book set category='$string[1]', title='$string[2]', press='$string[3]', year='$string[4]', author='$string[5]', price='$string[6]', total='$string[7]', stock='$string[8]' where bno='$string[0]'";
			}
			else// bno not exits, do inserting
				$query = "insert into book values('$string[0]', '$string[1]', '$string[2]', '$string[3]', '$string[4]', '$string[5]', '$string[6]', '$string[7]', '$string[7]')";
			mysqli_query($mydb,$query);
		}
		unset($_POST["address"]);
		unset($_POST["batch"]);
		fclose($myfile);
	}
	
	if(isset($_POST["insert"])&&$_POST["insert"]){// single insertion or updating
		$result = mysqli_query($mydb,"SELECT * FROM BOOK where bno = '$_POST[bno]'");
		$row = mysqli_fetch_array($result);
		if(!$row){//no bno, insert
			echo '<script>alert("succeed in inserting!")</script>';
			mysqli_query($mydb,"insert into book values('$_POST[bno]', '$_POST[category]', '$_POST[title]', '$_POST[press]', '$_POST[year]', '$_POST[author]', '$_POST[price]', '$_POST[total]', '$_POST[stock]')");
		}
		else{//bno exits, update
			echo '<script>alert("succeed in updating!")</script>';
			mysqli_query($mydb,"update book set category='$_POST[category]', title='$_POST[title]', press='$_POST[press]', year='$_POST[year]', author='$_POST[author]', price='$_POST[price]', total='$_POST[total]', stock='$_POST[stock]' where bno='$_POST[bno]'");
		}
		unset($_POST["insert"]);
	}
	

	echo "<table border='1' align='center'>
	<tr>
	<th>bno</th>
	<th>category</th>
	<th>title</th>
	<th>press</th>
	<th>year</th>
	<th>author</th>
	<th>price</th>
	<th>total</th>
	<th>stock</th>
	</tr>
	";

	$query = "SELECT * FROM BOOK";	
	$result = mysqli_query($mydb,$query);
	while($row = mysqli_fetch_array($result)){
	  echo "<tr>";
	  echo "<td>" . $row['bno'] . "</td>";
	  echo "<td>" . $row['category'] . "</td>";
	  echo "<td>" . $row['title'] . "</td>";
	  echo "<td>" . $row['press'] . "</td>";
	  echo "<td>" . $row['year'] . "</td>";
	  echo "<td>" . $row['author'] . "</td>";
	  echo "<td>" . $row['price'] . "</td>";
	  echo "<td>" . $row['total'] . "</td>";
	  echo "<td>" . $row['stock'] . "</td>";
	  echo "</tr>";
	}
	echo "</table>";
?>

</body>
</html>
