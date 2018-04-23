
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
		<a href = "./user_record.php" onclick="<?php mysqli_close($mydb);?>">record</a><!--jump to the record check site-->
	</div>

	<h2 align = "center">书籍管理</h2>
	<form name="search_form" action="" method="post" align="center">
		bno:<input type="text" name="bno">
		category:<input type="text" name="category">
		title:<input type="text" name="title"><br>
		press:<input type="text" name="press">
		year: from<input type="text" name="f_year">
		to<input type="text" name="t_year"><br>
		author:<input type="text" name="author">
		price: from<input type="text" name="f_price">
		to:<input type="text" name="t_price"><br>
		total:<input type="text" name="total">
		stock:<input type="text" name="stock">
		<input type="submit" name="search" value="search">
	</form><br>
	<form name="borrow_form" action="" method="post" align="center">
		bno:<input type="text" name="bor_bno">
		<input type="submit" name="borrow" value="borrow">
	</form><br>
<?php
	error_reporting(E_ALL || ~E_NOTICE);
	session_start();
	$mydb = mysqli_connect("localhost:3308",$_SESSION['username'],'');
	mysqli_select_db($mydb,"library");
	$query = "SELECT * FROM BOOK";

	if(isset($_POST['search'])&&$_POST['search']){
		$query .= " where";
		if(isset($_POST['bno'])&&$_POST['bno']!='')
			$query .= " bno='$_POST[bno]'&&";
		if(isset($_POST['category'])&&$_POST['category']!='')
			$query .= " category='$_POST[category]'&&";
		if(isset($_POST['title'])&&$_POST['title']!='')
			$query .= " title='$_POST[title]'&&";
		if(isset($_POST['press'])&&$_POST['press']!='')
			$query .= " press='$_POST[press]'&&";
		if(isset($_POST['f_year'])&&$_POST['f_year']!=''&&isset($_POST['t_year'])&&$_POST['t_year']!='')
			$query .= " year>='$_POST[f_year]'&& year<='$_POST[t_year]'&&";
		if(isset($_POST['author'])&&$_POST['author']!='')
			$query .= " author='$_POST[author]'&&";
		if(isset($_POST['f_price'])&&$_POST['f_price']!=''&&isset($_POST['t_price'])&&$_POST['t_price']!='')
			$query .= " price>='$_POST[f_price]'&& price<='$_POST[t_price]'&&";
		if(isset($_POST['total'])&&$_POST['total']!='')
			$query .= " total='$_POST[total]'&&";
		if(isset($_POST['stock'])&&$_POST['stock']!='')
			$query .= " stock='$_POST[stock]'&&";
		if($query=="SELECT * FROM BOOK where"){
			echo '<script>alert("no input!")</script>';
			$query = rtrim($query," where");
		}
		else
			$query = rtrim($query,"&&");
		unset($_POST['search']);
	}

	if(isset($_POST["borrow"])&&$_POST["borrow"]){
		$result1 = mysqli_query($mydb,"SELECT * FROM BOOK where bno = '$_POST[bor_bno]'");
		$row1 = mysqli_fetch_array($result1);
		if($row1){//bno eixts
			$result2 = mysqli_query($mydb,"SELECT * FROM BORROW where bno = '$_POST[bor_bno]'&& cno='$_SESSION[username]'");
			$row2 = mysqli_fetch_array($result2);
			if($row1['stock']>0){//bno has stocks
				if(!$row2){//haven't borrowed before, insert into borrow
					echo '<script>alert("!!succeed in borrowing!")</script>';
					$string = "insert into borrow values('$_SESSION[username]','$row1[bno]','" . date(Y.m.d) . "','')";
					mysqli_query($mydb,$string);
					mysqli_query($mydb,"update book set stock = stock-1 where bno = '$row1[bno]'");
				}
				else if($row2['return_date']!=0){//have borrowed and returned before, update borrow
					echo '<script>alert("succeed in borrowing!")</script>';
					$string = "update borrow set borrow_date ='" . date(Y.m.d) . "', return_date = '' where cno = $row2[cno] and bno = $row2[bno]";
					mysqli_query($mydb,$string);
					mysqli_query($mydb,"update book set stock = stock-1 where bno = '$row1[bno]'");
				}
				else
					echo '<script>alert("Haven\'t return it yet!")</script>';
			}
			else
				echo '<script>alert("No stock yet!")</script>';
		}
		else
			echo '<script>alert("No corresponding book!")</script>';
		
		unset($_POST["borrow"]);	
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
