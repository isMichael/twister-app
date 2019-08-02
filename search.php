<!DOCTYPE html>
<html>
<head>
	<title>Twister - Search</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="styles.css">

<?php 
	session_start(); 
	
	require('database.php');
	require('heading.php');
	require('nicetime.php');
?>

</head>
<body>
	<div class="container twistList">
		<form method="post" action="add_follow.php">

<?php

	$image_dir = "images";

	if (isset($_SESSION['login'])) {
		$search = htmlspecialchars($mysqli->real_escape_string($_GET['search']));

		$sql = "SELECT twistid, username, message, timestamp FROM Twists WHERE message LIKE '%$search%' ORDER BY timestamp DESC";

		$result = $mysqli->query($sql) or
			ShowError("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");

		if ($result->num_rows > 0) {
			$result_s = "results";
			
			if ($result->num_rows == 1)
				$result_s = "result";

			echo "<p>Found $result->num_rows $result_s for <strong>$search</strong>:</p>";
			
			echo '<table class="table table-striped"><tbody>';

			while ($row = $result->fetch_assoc()) {
				$time = nicetime($row['timestamp']);

				$message = str_ireplace($search, "<strong style='color:green'>$search</strong>", $row['message']);
		        
		        echo "<tr><td width='50'>
		        		<img src='$image_dir/$row[username]_thumb.jpg' alt='$row[username]' border='0'/>
		        	</td>
		        	<td valign='top'>
		        		<span class='username'>$row[username]</span>&nbsp;
		        		<span class='status'>$message</span>
		        		<br>
		        		<span class='timestamp'>$time</span>
		        	</td>";

		        if (isFollowing($row['username'])) {
		        	echo "<td></td></tr>";
		        }
		        else {
		        	echo "<td valign='top'>
		        		<button type='submit' class='btn btn-primary' aria-label='follow' name='follow' value='$row[username]'>Follow</button>
		        		</td></tr>";
		        }
			}

			echo '</tbody></table>';
		}
		else {
			echo "<p>No results found for <strong style='color:green'>$search</strong>.</p>";
		}
	}
	else {
		header("Location: login.php");
	}

?>

		</form>
	</div>
</body>
</html>

<?php

	function isFollowing($user) {
		$isFollowing = false;

		$sql = "SELECT follows FROM Followers WHERE username='$_SESSION[username]'";

		global $mysqli;

		$following = $mysqli->query($sql) or
			ShowError("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");

		if ($user == $_SESSION['username']){
			$isFollowing = true;
		}
		elseif ($following->num_rows > 0) {
			while ($row = $following->fetch_assoc()) {
				if ($user == $row['follows'])
					$isFollowing = true;
			}
		}
		
		return $isFollowing;
	}

	function ShowError($error)
	{

?>

    <h2>There was a problem.</h2>
    <p style='color:red'><?= $error ?></p>
	<p><a href="javascript:history.back()">Go back</a></p>
</body>
</html>

<?php
	exit;
} 
?> 