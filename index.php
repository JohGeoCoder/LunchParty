<!DOCTYPE html>
<?php
	require 'functions/user_validation.php';
?>

<?php //Active code. Runs each page load.
	if(isset($_GET['username']))
	{
		echo "Thank you for registering. You may now log in.<br />";
	}
	else if(isset($_GET['logout']))
	{
		echo "You have been logged out due to inactivity. Your actions have not been completed.<br />";
	}
	else if(isset($_GET['login_status']))
	{
	
	}

	$validUser = isValidUserActive();
?>

<html>
<head>

<link rel="stylesheet" type="text/css" href="css/styles.css" />

<script>
	function hashThings(login_form)
	{
		//Getting the two input objects
		var inputUsername = login_form['username'];
		var inputPassword = login_form['password'];

		//Hashing the values before submitting
		inputUsername.value = inputUsername.value;
		inputPassword.value = inputPassword.value;

		//Submitting
		return true;
	}
</script>

</head>
<body>
	<?php
		if($validUser)
		{
			//Display the party options.
	?>
		<?php include 'welcome_header.php' ?>
		<div id="main_wrapper">
			<h1>Let the party begin!</h1>
			<div id="add_view_option_wrapper">
				<div id="view_button_wrapper">
					<a class="div_button" href="view.php">
						View Lunch Parties
					</a>
				</div>
				
				<div id="add_button_wrapper">
					<a class="div_button" href="add.php">
						Add Your Lunch Party
					</a>
				</div>
			</div>
		</div>
		<div id="back_banner" />
	<?php		
		}
		else
		{
			//Display the login screen.
	?>
	
			<div id="login_wrapper">
				<form action="login.php" method="post" onsubmit="hashThings(this);">
				<table id="login_table">
					<tr>
						<td colspan="2"><h2>Log In</h2></td>
					</tr>
					<tr>
						<td>
							<label class="input_label" for="username">Username:</label>
						</td>
						<td>
							<input class="text_box" type="text" name="username" id="username" />
						</td>
					</tr>
					<tr>
						<td>
							<label class="input_label" for="password">Password:</label>
						</td>
						<td>
							<input class="text_box" type="password" name="password" id="password" />
							<?php //Error message for the password confirm
								if(isset($_GET['login_status']))
								{
									if($_GET['login_status'] == false)
									{
										echo "<div class='error'>" . "Either the username or password is incorrect" . "</div>";
									}
								}
							?>
							<br />
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="submit_button" value="Login" /></td>
					</tr>
					<tr>
						<td colspan="2"><a id="register_link" href="register.php">Register</a></td>
					</tr>
				</table>
				</form>
				
			</div>
	<?php
		}
	?>
</body>
</html>

<?php
	closeDatabaseConnection($con);
?>