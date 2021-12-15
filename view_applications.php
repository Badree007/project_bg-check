<?php
    include "header.php";
    require "includes/dbh.inc.php";

?>

    <main>
	    <div class="wrapper-main">
			<section class="section-default admin_page">
                <h2 style="text-align: center;">Applications</h2>
                <div class="applications">
                    <?php 
                        include "includes/view_app.inc.php";
                    ?>
                    <!-- <div class='action_btns'>
                        <button class='action_btn btn-aprv'>Approve</button>
                    </div> -->
                </div>
                

            </section>
        </div>
    </main>

<?php
    // include "footer.php";
?>