<?php  
	require "header.php";
?>

    <main>
		<div class="wrapper-main">
			<section class="section-default contact">
                <h2>Submit Your Inquiry</h2>
                <?php 
                    if(isset($_GET)) {
                        if(isset($_GET['err'])) {
                            ?> <p style="text-align:center; color:red;">Database error!</p>
                            <?php
                        }
                        else if(isset($_GET['success'])) {
                            ?> <p style="text-align:center; color:green;">Submitted!</p>
                            <?php
                        }
                    }
                ?>
                <form action="includes/submit_inquery.inc.php" class="form contact_form" method="POST">
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="msg" placeholder="Write your message...." required></textarea>
                    <button type="submit">Submit</button>
                </form>

            </section>
        </div>
    </main>

<?php 
    // require "footer.php";
?>