<?php
	require 'functions/database_functions.php'; //Contains stored procedures for interacting with the database.
	require 'functions/encryption.php'; //Functions for encrypting and validating passwords.
?>

<?php //Validation of user
	function setUserCookies($userID, $passhash)
	{
		setcookie("user_id", $userID, time() + 3600);
		setcookie("passhash", $passhash, time() + 3600);
	}
	
	function unsetUserCookies()
	{
		setcookie("user_id", "", time() - 10);
		setcookie("passhash", "", time() - 10);
		unset($_COOKIE['user_id']);
		unset($_COOKIE['passhash']);
	}
	
	function validateUserCredentials($username, $password)
	{
		//Connect to the database and retrieve the password
		//hash of the given username.
		$userRow = retrieveUserRowByUsername($username);
		$correctHash = $userRow['User_Passhash'];
		$userID = $userRow['User_ID'];
		
		if(validate_password($password, $correctHash))
		{
			setUserCookies($userID, $correctHash);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function validateActiveUser($userID, $passhash)
	{
		$row = retrieveUserRowByUserID($userID);
		if(!$row)
		{
			echo "user rows null<br />";
			return false;
		}
		
		return slow_equals($row['User_Passhash'], $passhash);
	}

	function isValidUserActive()
	{
		$userIdCookie = "";
		$passhashCookie = "";
		
		if(isset($_COOKIE['user_id']) && isset($_COOKIE['passhash']))
		{
			$userIdCookie = $_COOKIE['user_id'];
			$passhashCookie = $_COOKIE['passhash'];
		}
		
		//Check username-passhash pair from User table.
		if($userIdCookie == "" || $passhashCookie == "")
		{
			return false;
		}
		else
		{		
			return validateActiveUser($userIdCookie, $passhashCookie);
		}
	}

	function checkUserNameValidity($username)
	{
		if(!(preg_match("/[^A-Za-z0-9\-_]/i", $username) == 0))
		{
			return "Username may only contain lowercase and uppercase letters, numbers, '-', and '_'";
		}
		
		if(strlen($username) == 0)
		{
			return "Please enter a username";
		}
		
		if(strlen($username) >= 50)
		{
			return "Your username must be less than 50 characters in length.";
		}
		
		//Check if the username exists already in the database.
		if(retrieveUserRowByUserName($username) != null)
		{
			return "That username already exists";
		}
		return "";
	}
	
	function checkPasswordValidity($password)
	{
		if(strlen($password) < 4)
		{
			return "The password must be at least 4 characters in length.";
		}
		
		return "";
	}
	
	function checkPasswordMatchValidity($password, $passwordConfirm)
	{
		if($password != $passwordConfirm)
		{
			return "The passwords must match";
		}
		
		return "";
	}
	
	function checkPlaceValidity($place)
	{
		if($place == "")
		{
			return "Please enter a place to meet";
		}
		
		return "";
	}
	
	function checkTimeValidity($hour, $min)
	{
		$now = new DateTime();
		$then = new DateTime($hour . ":" . $min . ":00");
		
		if($then < $now)
		{
			return "It would be silly to set your lunch party in the past";
		}
		
		return "";
	}

	function getUserName()
	{
		if(!isset($_COOKIE['user_id']))
		{
			echo "no user name cookie<br />";
			return null;
		}
	
		return retrieveUserNameByID($_COOKIE['user_id']);
	}
?>
