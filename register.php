<!DOCTYPE html>
<?php
	require 'functions/user_validation.php';
?>
<?php //Active code. Runs each page load.
	$usernameValidity = "";
	$passwordLengthValidity = "";
	$passwordMatchValidity = "";
	if(isset($_POST['submit_button']))
	{
		if($_POST['submit_button'] == "Register")
		{
			//Retrieve the entered user information.
			$username = "";
			$password = "";
			$passwordConfirm = "";
			if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']))
			{
				$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
				$password = $_POST['password'];
				$passwordConfirm = $_POST['password_confirm'];
			}
			
			$usernameValidity = checkUsernameValidity($username);
			$passwordLengthValidity = checkPasswordValidity($password);
			$passwordMatchValidity = checkPasswordMatchValidity($password, $passwordConfirm);
			
			
			if($usernameValidity == "" && $passwordLengthValidity == "" && $passwordMatchValidity == "")
			{
				$hashedPassword = create_hash($password);
				addUserToTable($username, $hashedPassword);
				
				closeDatabaseConnection($con);
				header('Location: ' . "index.php?username=$username");
				exit();
			}
		}
		else if($_POST['submit_button'] == "Cancel")
		{
			//Redirect back to the index.
			closeDatabaseConnection($con);
			header('Location: ' . "index.php");
			exit();
		}
	}
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>


<div id="registration_wrapper">
	<form action="register.php" method="post">
	<table id="registration_table">
		<tr>
			<td colspan="2"><h2>Register New User</h2></td>
		</tr>
		<tr>
			<td>
				<label class="input_label">Desired Username:</label>
			</td>
			<td>
				<input class="text_box" type="text" name="username" id="username" />
				<?php //Error message for the username
					if(isset($_POST['submit_button']))
					{
						if($_POST['submit_button'] == "Register")
						{
							if($usernameValidity != "")
							{
								echo "<span class='error'>$usernameValidity</span>";
							}
						}
					}
				?>
				<br />
			</td>
		</tr>
		<tr>
			<td>
				<label class="input_label">Desired Password:</label>
			</td>
			<td>
				<input class="text_box" type="password" name="password" id="password" />
				<?php //Error message for the password
					if(isset($_POST['submit_button']))
					{
						if($_POST['submit_button'] == "Register")
						{
							if($passwordLengthValidity != "")
							{
								echo "<span class='error'>$passwordLengthValidity</span>";
							}
						}
					}
				?>
				<br />
			</td>
		</tr>
		<tr>
			<td>
				<label class="input_label">Confirm Password:</label>
			</td>
			<td>
				<input class="text_box" type="password" name="password_confirm" id="password_confirm" />
				<?php //Error message for the password confirm
					if(isset($_POST['submit_button']))
					{
						if($_POST['submit_button'] == "Register")
						{
							if($passwordMatchValidity != "")
							{
								echo "<span class='error'>$passwordMatchValidity</span>";
							}
						}
					}
				?>
				<br />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="half_width_button_holder"><input type="submit" name="submit_button" value="Register" /></div>
				<div class="half_width_button_holder"><input type="submit" name="submit_button" value="Cancel" /></div>
			</td>
		</tr>
	</table>
	</form>
	
</div>
</body>
</html>

<?php
	closeDatabaseConnection($con);
?>