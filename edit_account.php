<?php 
session_start(); 
?>

<html>
<head><title>Twister: Edit Account</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="styles.css">
</head>
<body>

<?php

// Change the following
define('TAZ_USERNAME', 'username');

require('heading.php');

// Profile image locations
$image_dir = "images";
$upload_dir = "twister/$image_dir";

// Username is set in create_account.php
if (isset($_SESSION['username']))
	$username = $_SESSION['username'];
else
	Error("The username is not known.");
	
// Connect and select database 
require('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{	
	// Get user data from database	
	$sql = "SELECT * FROM TwisterUsers WHERE username='$username'";
	$result = $mysqli->query($sql) or
		Error("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");

	$row = $result->fetch_assoc();
	if (!$row)
		Error("Unable to find the record for user $username.");
	
	$about = $row['about'];
	$profile_img = "$image_dir/$username.jpg";
	
?>
	<div class="container">
    <h2>Edit Account</h2>

    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

	<table>
		<div class="form-group">
			<label for="username" class="col-md-2 control-label">Username</label>
			<div class="col-md-4">
				<?= $username ?>
			</div>
		</div>
		<div class="form-group">
			<label for="about" class="col-md-2 control-label">About</label>
			<div class="col-md-4">
				<textarea rows="3" cols="40" class="form-control" id="about" name="about" required autofocus><?= $about ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-md-2 control-label">Password</label>
			<div class="col-md-4">
				<input type="password" class="form-control" id="password" name="password">
			</div>
		</div>
		<div class="form-group">
			<label for="profileImage" class="col-md-2 control-label">Profile Image
				<br><span class="extraInfo">(JPEG only)</span>
			</label>
			
			<div class="col-md-4">
				<img src="<?= $profile_img ?>">
				<br>
				<input type="file" class="form-control" id="profileImage" name="imgfile" 
					accept="image/jpeg" class="btn btn-default">
			</div>
		</div>
		
		
    <br>
    <input type="submit" class="btn btn-primary" value="Save">
    </form>
	</div>
</body>
</html>
<?php

}
else   // POST
{	
	require('image_util.php');   // To use functions that upload and resize images
	
	// Get submitted data and verify it wasn't left blank
	$about = trim($mysqli->real_escape_string($_POST['about']));
	$about = htmlspecialchars($about);
	$password = trim($_POST['password']);
	
	if (ImageUploaded())
	{
		// This is the directory the uploaded images will be placed in.
		// It must have priviledges sufficient for the web server to write to it
		$upload_directory_full = "/home/" . TAZ_USERNAME . "/public_html/$upload_dir";
		if (!is_writeable($upload_directory_full)) 
			Error("The directory $upload_directory_full is not writeable.\n");


		$image_filename = "$upload_directory_full/$username.jpg";
		
		// Save the uploaded image to the given filename
		$error_msg = UploadSingleImage($image_filename);
		if ($error_msg != "")
			Error($error_msg);
		
		// Save uploaded image with a maximum width or height of 300 pixels
		CreateThumbnailImage($image_filename, $image_filename, 300);
		
		// Create a very small thumbnail of the image to be used later
		$image_thumbnail = $username . "_thumb.jpg";
		CreateThumbnailImage($image_filename, "$upload_directory_full/$image_thumbnail", 60);
	}
		
	// Insert record into the database
	$sql = "UPDATE TwisterUsers SET about='$about' ";
	if ($password != "")
	{
		// Get the Bcrypt hash of the password for inserting into the database
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		$sql .= ", password='$password_hash'";
	}
		
	$sql .= " WHERE username='$username'";
	
	$mysqli->query($sql) or
		Error("Error executing query: ($mysqli->errno) $mysqli->error<br>SQL = $sql");
	
	print "<div class='container'>\n<h2>Account Successfully Modified</h2>\n" .
		"<p><img src='$image_dir/$username.jpg' style='float:left; margin: 0pt 10pt 10px 10px;'>" .
		"<h3>$username</h3></p>\n".
		"</div>\n";

}  // end POST


function Error($error)
{
?>
    <h1>Unable to edit the account</h1>
    <p><?= $error ?></p>
	<p><a href="javascript:history.back()">Go back</a></p>
</body>
</html>
<?php
    exit;
} 
?> 

