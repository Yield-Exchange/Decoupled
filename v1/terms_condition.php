<?php
include "config/db.php";
$page_title="Terms &nsp; Conditions";
require("includes/header.php");
?>
<style>
    #banner{
        padding-top: 120px!important;
    }
</style>
     <div id="banner" style="background:white;">
        <div class="container">
            <div class="theme-title-four text-center theme-title">
                <h2>Yield Exchange | Terms and Conditions</h2>
                <p></p>
            </div> <!-- /.theme-title-four -->

            <div class="row">
                <div class="col-md-12">
                    <embed src="<?php echo BASE_URL.'/assets/Terms_And_Conditions.pdf';?>" width="100%" height="900px" />
                </div>
            </div>
        </div>
    </div>


<?php
require "includes/footer.php";
?>