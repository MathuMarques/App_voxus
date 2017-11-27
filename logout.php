<?php 

	session_start();
	$_SESSION = array();
	unset($_SESSION['email']);
	session_destroy();
	unset($_COOKIE['full_name']);
	unset($_COOKIE['email']);

?>

<script>
	
	setCookie('full_name', '', date() - 1500);
	setCookie('email', '', date() - 1500);
</script>


<?php
header('Location: login.php');
?>