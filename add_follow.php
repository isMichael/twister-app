<?php
	
	session_start();

	require('database.php');

	if (isset($_SESSION['login'])) {
		$follow = $_POST['follow'];

		$sql = "INSERT INTO Followers (username, follows) VALUES ('$_SESSION[username]', '$follow')";
			
		if ($mysqli->query($sql)) {
			header("Location: index.php");
		}
		else {
			ShowError("Unable to follow user. Please try again.");
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