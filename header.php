<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<title>Project Work</title>
	<link rel="stylesheet" href="css/decorate.css">
	<script src="script.js" defer></script>
</head>
<body>
	<header>
		<nav class="nav-header-main">
			<a class="header-logo" href = "index.php">
			<img class="header-logo" src="images/logo.png" alt="logo"></a>
			<ul>
				 <?php
					if (isset($_SESSION["id"])) {
						echo "<li><a href='index.php'>Home</a></li>";
						if ($_SESSION["uid"] == "admin") {
							echo "<li><a href='view_applications.php'>View Application</a></li>
								  <li><a href='view_message.php?getMsg'>Message</a></li>";
						}  else {
							echo "<li><a href='contact.php'>Contact</a></li>
							<li><a href='background_check.php'>Application</a></li>";
						}
					}
				?> 				
			</ul>
		</nav>
			<div class="header-login">
			    <?php
					if (!isset($_SESSION['id'])) {
					//   echo ' 
					//   <a href="signup.php" class="header-signup">Login</a>';
					}
					else if (isset($_SESSION['id'])) {
					  echo '<form class="logout" action="includes/logout.inc.php" method="post">
						<button class="logout-btn" type="submit" name="logout-submit">Logout</button>
					  </form>';
					}
				?>
			</div>
	</header>



