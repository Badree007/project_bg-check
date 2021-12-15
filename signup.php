 
<?php  
	require "header.php";
?> 

	<main>
		<div class="wrapper-main">
		  <section class="section-default">
			<div class="login page current">
				<h1>Login</h1>
				<?php
					if (isset($_GET["lgerror"])) {
						if ($_GET["lgerror"] == "emptyfields") {
							echo '<p class="signuperror">Fill in all fields!</p>';
						}
						else if ($_GET["lgerror"] == "wrongpwd" || $_GET["lgerror"] == "wronguidpwd") {
							echo '<p class="signuperror">Invalid username or password!</p>';
						}
					}
				?>
				<form class="form form-login" action="includes/login.inc.php" method="post">
					<input type="text" name="mailuid" placeholder="E-mail/Username" required>
					<input type="password" name="pwd" placeholder="Password" required>
					<button type="submit" name="login-submit">Login</button>
				</form>
				<div class="switch">
					<p>Don't have an account? <a href="#" class="switch-page switch-page-signup">Signup</a></p>
				</div>
			</div>
 			<div class="signup page">
				<h1>Signup</h1>
				<p class="signuperror"></p>
				
				<form class="form form-signup" action="includes/signup.inc.php" method="POST">
					<input type="text" name="uid" placeholder="Username">
					<input type="text" name="mail" placeholder="E-mail">
					<input type="password" name="pwd" placeholder="Password">
					<input type="password" name="pwd_repeat" placeholder="Repeat Password">
					<button type="submit" id="signup-submit" name="signup-submit">Signup</button>
				</form>
				<div class="switch">
					<p>Already registered? <a href="#" class="switch-page switch-page-login">Login</a></p>
				</div>
			 </div>
		  </section>
		</div>
	</main>

<?php
	require "footer.php";
?>
	
