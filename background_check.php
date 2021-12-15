<?php
    include 'header.php';
	require 'includes/conn-logincheck.inc.php'; 
?>
    <main>
		<div class="wrapper-main">
			<section class="section-default user_page">
				<div class="page_links">
					<ul>
						<li><a class="page_show app_view-btn" href="#" data-num="1">View Application</a></li>						
						<li><a class="page_show apply-btn" href="#" data-num="2">Background Check</a></li>
					</ul>
				</div>
				<div class="content">
					<div class="rel_page pg_view active" data-num="1">
						<p style="font-size: 17px; color:peru">* To download report, click View Report and print (Ctrl + p)the page as 'Save as PDF'.</p> <br>
						<div class="applications">
							<?php 
								include "includes/view_app.inc.php";
							?>
						</div>
						<div class="modal feedback_modal">
							<div class='fb_modal'>
								<span class='close fb_close'>&times;</span>
								<form class="form feedback_form" action="">
									<textarea type="text" name="feedback_text" placeholder="Write your feedback....." required></textarea>
									<button class="btn feed_btn" type="submit">Submit</button>
								</form>
							</div>
						</div>
					</div>

					<div class="rel_page pg_apply" data-num="2">
						<p id="bg-check">Apply for Police Check</p>
						<p style="text-align: center;">!!! Please fill in the details as it appears on your ID Card.</p>
						<p class="error" style="text-align: center;"></p>
						<?php 
							if (isset($_GET["error"])) {
								if ($_GET["error"] == "emptyfields") {
								echo '<p class="signuperror">Fill in all fields!</p>';
								} else if ($_GET["error"] == "sqlerror") {
									echo '<p class="signuperror">Cannot save data to database!</p>';
								}
							}

						?>

						<form class="form form-signup form-checkAdd" action="includes/save-details.inc.php" method="POST" enctype="multipart/form-data">
							<div id="personal_details">
								<h2>Personal Details</h2>
								<input type="text" id="fname" name="fname"  placeholder="First Name" required>
								<input type="text" id="lname" name="lname"  placeholder="Last Name" required>
								<label for="birthday">DOB</label>
								<input type="date" id="birthday" name="birthday" required>
								<label for="gender">Gender:</label>
								<select id="gender" name="gender">
								<option></option>
								<option value="male">Male</option>
								<option value="female">Female</option>
								<option value="other">Other</option>
								</select><br>
								<label for="photoid">Photo ID / Driving License:</label>
								<label for="photoid">   (.PDF, .PNG, .JPEG, .JPG):</label>
                    			<input type="file" name="photoid" accept="image/*,.pdf" required>                    
							</div>
							<div id="address">
								<h2>Address</h2>
								<input type="number" id="stno" name="stno"  placeholder="Street No" required>
								<input type="text" id="stname"  name="stname" placeholder="Street Name" required>
								<input type="text" id="city" name="city"  placeholder="City/Suburb" required>
								<input type="text" id="postal" name="postal" placeholder="Postcode" required>
								<input type="text" id="state" name="state"   placeholder="State">
							</div>
							<button class="btn btn-apply" id="bg-apply" type="submit" name="check-submit">Save and Continue</button>
						</form>
					</div>
				</div>
                           
			</section>
		</div>
    </main>
<?php 
	//include 'footer.php';
?>