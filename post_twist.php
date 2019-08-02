<?php

session_start(); 

require('database.php');

if (isset($_SESSION['login'])) {
	if (isset($_POST['twist'])) {
		$twist = htmlspecialchars(trim($mysqli->real_escape_string($_POST['twist'])));
			
		if ($twist == '') {
			ShowError("You attempted to post an empty twist.");
		}
		else {
			$sql = "INSERT INTO Twists (username, message, timestamp) 
					VALUES ('$_SESSION[username]', '$twist', NOW())";
			
			if ($mysqli->query($sql)) {
				header("Location: index.php");
			}
			else {
				ShowError("Unable to post twist. Please try again.");
			}
		}
	}
	else {
		header("Location: index.php");
	}

}
else {
	header("Location: login.php");
}

function ShowError($error) {
	echo "<h2>There was a problem.</h2><p style='color:red'>
			$error</p><p><a href='javascript:history.back()'>
			Go back</a></p>";
	exit;
}

?>