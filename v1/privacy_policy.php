<?php
include "config/db.php";
$page_title="Privacy Policy";
require("includes/header.php");
?>
<style>
    #banner{
        padding-top: 120px!important;
    }
</style>
    <!-- ====== Banner Part HTML Start ====== -->

     <div id="banner" style="background:white!important;">
        <div class="container">
            <div class="theme-title-four text-center theme-title">
                <h2>Privacy Policy</h2>
                <p></p>
            </div> <!-- /.theme-title-four -->

            <div class="row">
                <div class="col-md-12">
                    <embed src="<?php echo BASE_URL.'/assets/Privacy_Policy.pdf';?>" width="100%" height="900px" />
                </div>
            </div>
        </div>
    </div>

<?php
require "includes/footer.php";
?>