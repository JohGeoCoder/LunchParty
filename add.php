<!DOCTYPE html>
<?php
	require 'functions/user_validation.php';
?>

<?php //Active code. Runs each page load.
	$validUser = isValidUserActive();
	if(!$validUser)
	{
		closeDatabaseConnection($con);
		header('Location: ' . "index.php?logout=true");
		exit;
	}
	
	$userID = null;
	$hasPreviousReservation = false;
	if(isset($_COOKIE['user_id']))
	{
		$hasPreviousReservation = retrieveUpcomingReservationByUserId($_COOKIE['user_id']) != null;
	}
	
	$placeValidity = "";
	$timeValidity = "";
	if(isset($_POST['submit_button']))
	{
		if($_POST['submit_button'] == "Logout")
		{
			closeDatabaseConnection($con);
			header("Location: logout.php");
			exit;
		}
		else if($_POST['submit_button'] == "Create")
		{
			//Call method to parse input and call another method to add the party to the database
			if(isset($_COOKIE['user_id']))
			{
				
				$userID = $_COOKIE['user_id'];
				$location = $_POST['location'];
				$hour = $_POST['hour'];
				$minute = $_POST['minute'];
				$meetPlace = trim(filter_input(INPUT_POST, 'place', FILTER_SANITIZE_STRING));
				
				$placeValidity = checkPlaceValidity($meetPlace);
				$timeValidity = checkTimeValidity($hour, $minute);
				
				if($placeValidity == "" && $timeValidity == "" && !$hasPreviousReservation)
				{
					addPartyToTable($userID, $location, $hour, $minute, $meetPlace);
					$partyID = retrieveLatestPartyCreatedByUserId($userID)['Party_ID'];
					
					addReservationToTable($userID, $partyID);
					
					closeDatabaseConnection($con);
					header("Location: view.php");
					exit;
				}
				else
				{

				}
			}
			else
			{
				closeDatabaseConnection($con);
				header("Location: index.php?logout=true");
				exit;
			}
			
			
		}
		else if($_POST['submit_button'] == "Cancel")
		{
			closeDatabaseConnection($con);
			header("Location: index.php");
			exit;
		}
	}
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
<?php
	if($validUser)
	{
		//display add form
?>
		<?php include 'welcome_header.php' ?>
		<div id="main_wrapper">
			<h1>Let's get this party started!</h1>
			<?php
				if($hasPreviousReservation)
				{
					echo "<p class='text_center'>Well well well. It appears that you have a reservation already today.</p>";
					echo "<p class='text_center'>Click <a href='view.php'>here</a> to adjust where your loyalties reside.</p>";
				}
				else
				{
			?>
					<form action="add.php" method="post">
						<table id="party_add_form_table">
							<tr>
								<td><label class="input_label">Location:</label></td>
								<td>
									<select name="location">
									<?php
										$locationRows = retrieveAllLocationsFromDatabase($con);
										while($row = mysqli_fetch_array($locationRows))
										{
											$locationID = $row['Location_ID'];
											$locationName = $row['Location_Name'];
											echo "<option value='$locationID'>$locationName</option>\n";
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td><label class="input_label">Time:</label></td>
								<td>
									<select name="hour">
										<option value="6">6 AM</option>
										<option value="7">7 AM</option>
										<option value="8">8 AM</option>
										<option value="9">9 AM</option>
										<option value="10">10 AM</option>
										<option value="11">11 AM</option>
										<option value="12">12 PM</option>
										<option value="13">1 PM</option>
										<option value="14">2 PM</option>
										<option value="15">3 PM</option>
										<option value="16">4 PM</option>
										<option value="17">5 PM</option>
										<option value="18">6 PM</option>
										<option value="19">7 PM</option>
										<option value="20">8 PM</option>
									</select>
									<span>Hr   </span>
									<select name="minute">
										<option value="0">00</option>
										<option value="5">05</option>
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="30">30</option>
										<option value="35">35</option>
										<option value="40">40</option>
										<option value="45">45</option>
										<option value="50">50</option>
										<option value="55">55</option>
									</select>
									<span>Min</span>
									<?php
										if(isset($_POST['submit_button']) && $_POST['submit_button'] == "Create")
										{
											if($timeValidity != "")
											{
												echo "<span class='error'>$timeValidity</span>";
											}
										}
									?>
									<br />
								</td>
							</tr>
							<tr>
								<td><label class="input_label">Meeting Place:</label></td>
								<td>
									<input type="text" name="place" maxlength="50" />
									<?php
										if(isset($_POST['submit_button']) && $_POST['submit_button'] == "Create")
										{
											if($placeValidity != "")
											{
												echo "<span class='error'>$placeValidity</span>";
											}
										}
									?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div class="half_width_button_holder">
										<input class="party_add_button" type="submit" name="submit_button" value="Create" />
									</div>
									<div class="half_width_button_holder">
										<input class="party_add_button" type="submit" name="submit_button" value="Cancel" />
									</div>
								</td>
							</tr>
						</table>
						
						<p class="clear_float" />
					</form>
			<?php
				}
			?>
		</div>
		<div id="back_banner" />
<?php
	}
?>
</body>
</html>

<?php
	closeDatabaseConnection($con);
?>