<?php
if (isset($_SESSION['login'])) {
	echo '<header class="container"><nav class="navbar-header navbar-default" style="margin-bottom:15px">';
	echo '<div class="container"><a href="./">Twister</a>';
	echo '<form method="get" action="search.php" class="form-inline" style="display:inline; padding-left:130px">';
	echo '<input type="search" name="search" placeholder="Search" class="form-control">';
	echo '</form><a style="float:right; margin-top:5px" href="logout.php">Logout</a></div></nav></header>';
}
else {
	echo '<header class="container"><nav class="navbar-header navbar-default" style="margin-bottom:15px">';
	echo '<div class="container"><a href="./">Twister</a></div></nav></header>';
}
?>