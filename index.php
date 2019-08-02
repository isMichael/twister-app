<!DOCTYPE html>
<html>
<head>
	<title>Twister</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="styles.css">
	<script src="charsLeft.js"></script>

<?php 
	session_start(); 
	require('database.php');
	require('heading.php');
	require('nicetime.php');
?>

</head>
<body>
	<div class="container">

<?php
	if (isset($_SESSION['login'])) {
		$username = $_SESSION['username'];
		$about = $_SESSION['about'];

		$image_dir = "images";
		$profile_img = "$image_dir/$username.jpg";

		$sql = "SELECT COUNT(*) FROM Twists WHERE username='$username'";
		$result = $mysqli->query($sql) or
			ShowError("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");
		$twists = $result->fetch_row()[0];

		$sql = "SELECT COUNT(*) FROM Followers WHERE follows='$username'";
		$result = $mysqli->query($sql) or
			ShowError("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");
		$followers = $result->fetch_row()[0];

		$sql = "SELECT COUNT(*) FROM Followers WHERE username='$username'";
		$result = $mysqli->query($sql) or
			ShowError("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");
		$following = $result->fetch_row()[0];
	}
	else
		header("Location: login.php");	
?>
   
    <img src="<?= $profile_img ?>" alt="Profile image" class="profilePic">
	<div class="profileInfo">
		<h3><?= $username ?></h3> 
		<p class="about"><?= $about ?> &nbsp;&nbsp; <a href="edit_account.php">Edit</a></p>
		<p>
			Twists: <strong><?= $twists ?></strong> &nbsp;
			Followers: <strong><?= $followers ?></strong> &nbsp;
			Following: <strong><?= $following ?></strong>
		</p>

		<form method="POST" action="post_twist.php" class="form-inline statusForm">
			<textarea name="twist" id="twist" class="form-control" required rows="3" cols="70" maxlength="150" placeholder="What's happening?"></textarea>
			<div class="twistBlock">
				<button class="btn btn-primary" type="submit">Twist</button><br><br>
				<span id="charsLeft" class="statusGood">150</span>
			</div>
		</form>
	</div>
	</div>

	<div class="container twistList">
		<form method="post" action="remove_follow.php">

<?php
	$sql = "SELECT twistid, username, message, timestamp FROM Twists WHERE username='$username' UNION
			SELECT twistid, username, message, timestamp FROM Twists WHERE username IN (SELECT follows
			FROM Followers WHERE username='$username') ORDER BY timestamp DESC LIMIT 0, 20";

	$result = $mysqli->query($sql) or
		ShowError("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");

	if ($result->num_rows > 0) {
		echo '<table class="table table-striped"><tbody>';
		
		while ($row = $result->fetch_assoc()) {
			$time = nicetime($row['timestamp']);
	        
	        echo "<tr><td width='50'>
	        		<img src='$image_dir/$row[username]_thumb.jpg' alt='$row[username]' border='0'/>
	        	</td>
	        	<td valign='top'>
	        		<span class='username'>$row[username]</span>&nbsp;
	        		<span class='status'>$row[message]</span>
	        		<br>
	        		<span class='timestamp'>$time</span>
	        	</td>";

	        if ($row['username'] != $username) {
	        	echo "<td valign='top'>
	        		<button type='submit' class='btn btn-primary' aria-label='unfollow' name='unfollow' value='$row[username]'>Unfollow</button>
	        		</td></tr>";
	        }
	        else {
	        	echo "<td></td></tr>";
	        }
		}

		echo '</tbody></table>';
	}
	else {
		echo "<p>No twists yet.</p>";
	}
?>

		</form>
	</div>

</body>
</html>

<?php
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
