<?php
	$result = null;
	if(isset($_POST['Submit'])){
		$user = $_POST['userName'];
		$pass = $_POST['userPass'];
		$result = $explDb->query("select * from `explsuser` where login ='$user'");
		
		if ($result) {
			foreach ($result as $row) {
				$p =  $row['password'];
				if ($pass == $p) {
					$_SESSION['auth'] = $user;
					$_SESSION['fio'] = $row['fio'];
					$_SESSION['ip'] = $row['ip'];
					$_SESSION['email'] = $row['email'];
					$_SESSION['policy'] = $row['policy'];
					$_SESSION['login'] = $row['login'];
					$_SESSION['password'] = $row['password'];
					header("Location: index.php?".$_SESSION['auth']);
					exit;
					//echo( "<script>console.log('ok')</script>" );
				}
			}
		}
		header("Location: index.php?fail");

	} else if (isset($_GET['logout'])) {
		unset($_SESSION['auth']);
		session_start();
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit();
		
	} else if (isset($_SESSION['auth'])) {
		header("Location: index.php?".$_SESSION['auth']);
		
	} ;
?>

<?php if (isset($_GET['fail'])){ echo "<div>НЕВЕРНЫЙ ПАРОЛЬ</div>";} ?>
<form action="index.php" method="post">
	<table>
		<tr><td>Логин:</td><td> <input type="text" name="userName" autofocus></td></tr>
		<tr><td>Пароль:</td><td> <input type="password" name="userPass"></td></tr>
		<tr><td colspan=2 align='center'><input type="submit" name="Submit"></td></tr>
	</table>
</form>
