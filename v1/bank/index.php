<?php
    session_start();
    require_once "header.php";
    require_once "sidebar.php";
    Core::activityLog("Bank Dashboard");
?>
		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content">
                <?php 
                    require_once "index_realtime.php";
                ?>
			</div>

        <!-- Modal -->
            <div style="top:40%" id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                            <p>Please fill your information from Account Setting.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
