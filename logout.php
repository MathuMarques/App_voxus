<?php 
	session_start();
	unset($_SESSION['email']);
	session_destroy();
	unset($_COOKIE['full_name']);
	unset($_COOKIE['email']);
	header('Location: login.php');
?>