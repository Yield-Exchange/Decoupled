<?php
    session_start();
    require_once "header.php";
    require_once "sidebar.php";
    Core::activityLog("Depositor Dashboard");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content">
                <?php
                    require_once "index_realtime.php";
                ?>
			</div>
			<!-- /content area -->

 <script>
    let timer;
    let seconds = 3; // how often should we refresh the DIV?
    function startActivityRefresh() {
        timer = setInterval(function() {
            $('.content').load('index_realtime.php');
        }, seconds*1000)
    }

    function cancelActivityRefresh() {
        clearInterval(timer);
    }

    $(function() {
        startActivityRefresh();
    });
 </script>
<?php
require_once "footer.php";
?>