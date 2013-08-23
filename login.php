<?php
	require 'functions/user_validation.php';
?>

<?php

	$validUser = false;
	if(isset($_POST['submit_button']) && $_POST['submit_button'] == "Login")
	{
		//Validate the submitted username and password.
		$username = "";
		$password = "";
		if(isset($_POST['username']) && isset($_POST['password']))
		{
			$username = strtoupper(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
			$password = $_POST['password'];
		
			if($username != "" && $password != "")
			{
				$validUser = validateUserCredentials($username, $password);
			}
		}
	}
	
	closeDatabaseConnection($con);
	header("Location: index.php?login_status=$validUser");
?>