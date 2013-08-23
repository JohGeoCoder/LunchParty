<?php
	require 'functions/user_validation.php';
?>

<?php
	unsetUserCookies();
	
	header("Location: index.php");
	exit;
?>