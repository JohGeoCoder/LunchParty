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
	
	if(isset($_POST['submit_command']))
	{
		$submitCommand = $_POST['submit_command'];
		$id = $_POST['party_or_reservation_id'];
		
		if($submitCommand == "Abandon")
		{
			deleteUserReservationFromParty($_COOKIE['user_id'], $id);
		}
		else if($submitCommand == "Join")
		{
			addReservationToTable($_COOKIE['user_id'], $id);
		}
	}
?>

<html>
	<script>
		function eatCheese()
		{
			alert("cheese");
		}
	
		function doSubmit(command, id)
		{
			document.getElementsByName("submit_command")[0].value = command;
			document.getElementsByName("party_or_reservation_id")[0].value = parseInt(id);
			document.getElementById("reservation_form").submit();
		}
	</script>
<head>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script>
	$(document).ready(function(){	
		$(".view_attendance_button").click(function(event) {
			var targetID = event.target.id;
			$("#attendees_" + targetID).show();
		});
		
		$(".attendees_cover").click(function(event){
			$(this).hide();
		});
	});
	
	function message(message)
	{
		alert(message);
	}

</script>

</head>
<body>
<?php
	if($validUser)
	{
		//display add form
?>
		<?php include 'welcome_header.php' ?>
		<div id="main_wrapper">
			<h1>The party doesn't start 'til I walk in!</h1>
			<form id="reservation_form" action="view.php" method="post">
				<input type="hidden" name="submit_command" value="Nothing" />
				<input type="hidden" name="party_or_reservation_id" value="0" />
				<?php
					$partyCount = 0;
					
					//Begin the first row
					echo "<div class='party_row'>";
					
					$previousReservation = retrieveUpcomingReservationByUserId($_COOKIE['user_id']);
					$hasPreviousReservation = ($previousReservation != null);
					$previousReservationPartyID = null;
					$abandonJoinHtml = "";
					if($hasPreviousReservation)
					{
						$command = "Abandon";
						$previousReservationPartyID = $previousReservation['Reservation_Party_ID'];
						$abandonJoinHtml = "<a href='#' onclick='doSubmit(\"" . $command . "\",\"" . $previousReservationPartyID . "\")'>Abandon</a>";
					}
					else
					{
						$command = "Join";
					}
					
					$parties = retrieveUpcomingPartiesFromDatabase();
					while($row = $parties->fetch_array(MYSQLI_BOTH))
					{
						if($partyCount % 3 == 0 && $partyCount != 0)
						{
							//Close the row and re-open a new row
							echo "</div><div class='party_row'>";
						}
						$partyID = $row['Party_ID'];
						$location = retrieveLocationNameById($row['Party_Location_ID']);
						$time = $row['Party_Time'];
						$meetPlace = $row['Party_Meet_Place'];
						$creator = retrieveUserNameById($row['Party_Creator_ID']);
						$attendanceCount = retrievePartyAttendanceCount($partyID);
						
						$timeObject = new DateTime($time);
						$formattedTime = $timeObject->format('m/d g:iA');
						
						if(!$hasPreviousReservation)
						{
							$abandonJoinHtml = "<a href='#' onclick='doSubmit(\"" . $command . "\",\"" . $partyID . "\")'>Join</a>";
						}
							
				?>
						<div class="party_block">
							<div id="attendees_<?php echo $partyID;?>" class="attendees_cover">
								<div class="attendees_cover_header">Party Attendees: Click to close</div>
								<div class="attendees">
								<?php
									$partyAttendees = retrieveReservationsByPartyID($partyID);
									while($attendeeRow = $partyAttendees->fetch_array(MYSQLI_BOTH))
									{
										$attendeeName = $attendeeRow["User_Name"];
										echo "<div class='attendee'>$attendeeName</div>";
									}
								?>
								</div>
							</div>
							<table>
								<tr>
									<td class="label_column"><label class="input_label">Location:</label></td>
									<td><?php echo $location;?></td>
								</tr>
								<tr>
									<td class="label_column"><label class="input_label">Time:</label></td>
									<td><?php echo $formattedTime;?></td>
								</tr>
								<tr>
									<td class="label_column"><label class="input_label">Meeting Place:</label></td>
									<td><?php echo $meetPlace;?></td>
								</tr>
								<tr>
									<td class="label_column"><?php echo $attendanceCount; ?> going (<?php echo "<a class='view_attendance_button' id='" . $partyID . "'>view</a>"?>)</td>
									<td class="label_column text_center"><?php if($hasPreviousReservation && $partyID == $previousReservationPartyID){echo $abandonJoinHtml;} else if(!$hasPreviousReservation){echo $abandonJoinHtml;} ?></td>
								</tr>
							</table>
							
						</div>
				<?php
						$partyCount++;
					}
					
					//Close the final row.
					echo "</div>";
				?>
			</form>
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