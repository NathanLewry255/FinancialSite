<?php
	session_start();
	$passHash = "$2y$10\$ldN1U/8b1mF5.myigQLorOd0E0DFeQ3.S0TPesy7YFTWopzbqMC86";
	if(password_verify($_POST['password'], $passHash)){
		$_SESSION['loggedIn'] = true;
		header("Location: ../index.php");
		exit();
	}
	else{
		header("Location: ../Pages/login.php?IncorrectPass");
		exit();
	}
?>