<!DOCTYPE html>
<html>
<head>
	<title>Twister - Login</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="styles.css">

<?php
session_start();
		// Change the following
		define('TAZ_USERNAME', 'username');

		require('heading.php');
?>

</head>
<body>
	<div class="container">

		<?php
		if ($_SERVER['REQUEST_METHOD'] != 'POST')
		{
		?>

		    <h2>Login to Twister</h2>

		    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
		    <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

			<table>
				<div class="form-group">
					<label for="username" class="col-md-2 control-label">Username</label>
					<div class="col-md-4">
						<input type="text" class="form-control" id="username" name="username" 
							required pattern="[A-Za-z0-9]{3,}" autofocus>
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-md-2 control-label">Password</label>
					<div class="col-md-4">
						<input type="password" class="form-control" id="password" name="password">
					</div>
				</div>
		    	<br>
		    	<input type="submit" class="btn btn-primary" value="Login">
		    	<div>
		    		<br>
		    		<p>Don't have an account? Create one for <a href="create_account.php">free</a>!</p>
		    	</div>
		    </table>
		    </form>
		<?php

		}
		else {  // POST
			require('database.php');
			
			// Get submitted data and verify it wasn't left blank
			$username = htmlspecialchars(trim($mysqli->real_escape_string($_POST['username'])));
			$password = htmlspecialchars(trim($mysqli->real_escape_string($_POST['password'])));
				
			if ($password == '')
				ShowError("Please enter username and password.");
			
			$sql = "SELECT * FROM TwisterUsers WHERE username='$username'";

			$result = $mysqli->query($sql) or
			Error("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");

			$row = $result->fetch_assoc();

			if (!$row) {
				ShowError("Sorry, the username $username does not exist.");
			}
			else {
				$hashed_password = $row['password'];

				if (password_verify($password, $hashed_password)) {
					$_SESSION["login"] = true;
					
					$_SESSION["username"] = $username;

					$_SESSION["about"] = $row['about'];
					
					header("Location: index.php");
				}
				else {
					ShowError("Password is incorrect.");
				}
			}
		}
		?>
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


<!--<form>
	<div>
	Username <input type="text" name="username" autofocus>
	</div>
	<div>
	Password <input type="password" name="password">
	</div>
	<input type="submit" value="Login">
</form>

<?php
/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Verify correct username and password
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if ($username == 'bsmith' && $password === "opensesame") {
		echo "<h1>Welcome!</h1>";
	}
	else {
		echo "<p>Username or password is incorrect.</p>";
	}
}*/
?>-->