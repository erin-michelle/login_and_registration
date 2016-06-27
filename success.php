<?php 

	session_start();
	require('connection.php');

	echo "Well hey, {$_SESSION['first_name']}.";
	echo "<a href='process.php'> Log Off </a>";

?>