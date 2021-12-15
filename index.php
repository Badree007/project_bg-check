 
<?php  
	require "header.php";
?> 

	<main>
		<div class="wrapper-main">
		  <section class="section-default home">
			  <?php
			  if (!isset($_SESSION['id'])) {
				echo '<p class="logout-status">Logged out! Please login first.</p>';
			  }
			  else if (isset($_SESSION['id'])) {
				echo '<p class="login-status">Welcome, ' . $_SESSION['uid']. '</p>';
			  }
			  ?>
				<div class="service">
					<h2>Service</h2>
					<h4>Apply for Address Verification Report</h4>
				</div>
		  </section>		  
		</div>
	</main>

<?php
	require "footer.php";
?>
	
