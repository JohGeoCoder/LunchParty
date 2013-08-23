<?php
	require 'functions/database_connection.php'; //Connects to the database with the variable $con.
?>

<?php //Database functions	
	function retrieveAllLocationsFromDatabase()
	{
		global $con;
		$result = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT Location_ID, Location_Name FROM location";
			$stmt = $con->stmt_init();

			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->execute();
				$result = $stmt->get_result();
			}			
		}
		return $result;
	}
	
	function retrieveUserRowByUserName($username)
	{
		global $con;
		$row = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT User_ID, User_Name, User_Passhash FROM user WHERE LOWER(User_Name) = ?";
			$stmt = $con->stmt_init();

			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$usernameLowercase = strtolower($username);;
				$stmt->bind_param("s", $usernameLowercase);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_array(MYSQLI_BOTH);
			}			
		}
		return $row;
	}
	
	function retrievePasshash($username)
	{
		global $con;
		$passhash = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT User_Name, User_Passhash FROM user WHERE User_Name = ?";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("s", $username);
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_array(MYSQLI_BOTH))
				{
					$passhash = $row['User_Passhash'];
				}
			}			
		}
		return $passhash;
	}
	
	function retrieveUserRowByUserID($userID)
	{
		global $con;
		$userRow = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT * FROM user WHERE User_ID = ?";
			$stmt = $con->stmt_init();
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("d", $userID);
				$stmt->execute();
				$result = $stmt->get_result();
				
				$userRow = $result->fetch_array(MYSQLI_BOTH);
			}			
		}
		return $userRow;
	}

	function addPartyToTable($userID, $location, $hour, $minute, $meetPlace)
	{
		global $con;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$dateString = date("Y\-m\-d") . " $hour:$minute:00";
			
			$query = "INSERT INTO party (Party_ID, Party_Location_ID, Party_Time, Party_Meet_Place, Party_Creator_ID) VALUES(null, ?, ?, ?, ?)";
			$stmt = $con->stmt_init();
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("dssd", $location, $dateString, $meetPlace, $userID);
				$stmt->execute();
			}
		}
	}
	
	function addUserToTable($username, $passhash)
	{
		global $con;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "INSERT INTO user (User_Name,User_Passhash) VALUES(?,?)";
			$stmt = $con->stmt_init();
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("ss", $username, $passhash);
				$stmt->execute();
			}			
		}
	}
	
	function retrieveAllPartiesFromDatabase()
	{
		global $con;
		$results = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT * FROM party";
			$stmt = $con->stmt_init();

			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->execute();
				$results = $stmt->get_result();
			}			
		}
		return $results;
	}
	
	function retrieveUpcomingPartiesFromDatabase()
	{
		global $con;
		$results = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT * FROM party WHERE NOW() < Party_Time";
			$stmt = $con->stmt_init();

			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{ 
				$stmt->execute();
				$results = $stmt->get_result();
			}			
		}
		return $results;
	}

	function retrieveLocationNameById($locationID)
	{
		global $con;
		$locationName = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT Location_Name FROM location WHERE Location_ID = ?";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("s", $locationID);
				$stmt->execute();
				$results = $stmt->get_result();
				$row = $results->fetch_array(MYSQLI_BOTH);
				
				$locationName = $row['Location_Name'];
			}			
		}
		return $locationName;
	}
	
	function retrieveUserNameById($userID)
	{
		global $con;
		$userName = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT User_Name FROM user WHERE User_ID = ?";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("s", $userID);
				$stmt->execute();
				$results = $stmt->get_result();
				$row = $results->fetch_array(MYSQLI_BOTH);
				
				$userName = $row['User_Name'];
			}			
		}
		return $userName;
	}
	
	function addReservationToTable($userID, $partyID)
	{
		global $con;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "INSERT INTO reservation (Reservation_User_ID,Reservation_Party_ID) VALUES(?,?)";
			$stmt = $con->stmt_init();
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("dd", $userID, $partyID);
				$stmt->execute();
			}			
		}
	}
	
	function retrieveUpcomingReservationByUserId($userID)
	{
		global $con;
		$reservationRow = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT reservation.Reservation_User_ID, reservation.Reservation_Party_ID, party.Party_Time FROM reservation INNER JOIN party ON reservation.Reservation_Party_ID = party.Party_ID WHERE reservation.Reservation_User_ID = ? AND NOW() < party.Party_Time";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("d", $userID);
				$stmt->execute();
				
				if($stmt->error)
				{
					echo $results->error;
				}
				
				$results = $stmt->get_result();
				
				
				
				$reservationRow = $results->fetch_array(MYSQLI_BOTH);
			}
		}

		return $reservationRow;
	}
	
	//Reservations for today and future dates.
	function retrieveReservationsByPartyId($partyID)
	{
		global $con;
		$reservations = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT reservation.Reservation_User_ID AS User_ID, user.User_Name, reservation.Reservation_Party_ID AS Party_ID, party.Party_Time FROM reservation INNER JOIN party ON reservation.Reservation_Party_ID = party.Party_ID INNER JOIN user ON reservation.Reservation_User_ID = user.User_ID WHERE reservation.Reservation_Party_ID = ?";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("d", $partyID);
				$stmt->execute();
				$reservations = $stmt->get_result();
			}
		}
		return $reservations;
	}
	
	function retrieveLatestPartyCreatedByUserId($userID)
	{
		global $con;
		$latestParty = null;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT Party_ID, Party_Location_ID, Party_Time, Party_Meet_Place, Party_Creator_ID FROM party WHERE Party_Creator_ID = ? ORDER BY Party_ID DESC";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("d", $userID);
				$stmt->execute();
				$parties = $stmt->get_result();
				$latestParty = $parties->fetch_array(MYSQLI_BOTH);
			}
		}
		return $latestParty;
	}
	
	function deleteUserReservationFromParty($userID, $partyID)
	{
		global $con;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "DELETE FROM reservation where Reservation_User_ID = ? AND Reservation_Party_ID = ?";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("dd", $userID, $partyID);
				$stmt->execute();
			}
		}
	}
	
	function retrievePartyAttendanceCount($partyID)
	{
		global $con;
		$attendanceCount = 0;
		if($con->connect_error)
		{
			echo "Failed to connect to MySQL: " . $con->connect_error . "<br />";
		}
		else
		{
			$query = "SELECT * FROM reservation WHERE Reservation_Party_ID = ?";
			$stmt = $con->stmt_init();
			
			if(!$stmt->prepare($query))
			{
				echo "Error: " . $stmt->error . "<br />";
			}
			else
			{
				$stmt->bind_param("d", $partyID);
				$stmt->execute();
				$parties = $stmt->get_result();
				$attendanceCount = $parties->num_rows;
			}
		}
		return $attendanceCount;
	}
?>
