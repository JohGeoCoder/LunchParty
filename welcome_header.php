<?php
	//Don't require anything here, it will redefine functions when included in other pages.
?>

<div id="header_welcome_message">
	<p class="welcome_message">Welcome, <?php echo getUserName() ?></p>
	<p class="compliment">Wow... Such a cool person</p>
</div>

<div id="header_logout_section">
	<form action="logout.php" method="post">
		<input type="submit" name="submit_button" value="Logout" />
	</form>
</div>

<div id="header_home_section">
	<a href="index.php"><input type="button" value="Home" /></a>
</div>
	