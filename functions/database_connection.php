<?php
	function getDatabaseConnection()
	{
		return new mysqli("localhost", "root", "", "lunchparty");
	}
	
	function closeDatabaseConnection($connection)
	{
		$connection->close();
	}
	
	$con = getDatabaseConnection();
?>