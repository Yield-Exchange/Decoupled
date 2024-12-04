<?php
//require_once "../config/db.php";
//require_once "../config/Model.php";
//require_once "../config/AuthModel.php";
//require_once "timezone.php";

$user_data = AuthModel::getUserdata();
//require_once BASE_DIR."/includes/head.php";
$user_preferences = Core::getUserPreferences();
$demographic_user_data = AuthModel::getUserDemographicData($user_data['id']);
?>
    <style type="text/css">
        .content-wrapper{
        /* Add the blur effect */
            filter: blur(5px);
            -webkit-filter: blur(5px);
        }
        .dropdown-menu{
            z-index: 2147483647;
        }
        #overlay {
            position: fixed; /* Sit on top of the page content */
            display: block; /* Hidden by default */
            /*width: 100%; !* Full width (cover the whole page) *!*/
            height: 100%; /* Full height (cover the whole page) */
            top:8%;
            left: 16.875rem;
            right: 0;
            /*bottom: 0; */
            /*background-color:rgba(255,255,255,0.9); /*!*background-color: rgba(0,0,0,0.5); !* Black background with opacity *!*/
            z-index: 2147483646; /* Specify a stack order in case you're using a different order for other elements */
            cursor: pointer; /* Add a pointer on hover */
        }
        #text{
            border: 1px solid #ccc;
            width: 400px;
            height: 100px;
            position: fixed;
            top: 45%;
            left: 50%;
            padding-top: 20px;
            padding-right:20px;
            padding-left:20px; 
            margin-top: -100px;
            margin-left: -150px;
            background-color: rgba(255,255,255,1);
            border-radius: 10px;
            text-align: center;
            z-index: 100;
            color: black;
            font-size: 16px;

        }
        @media (max-width: 800px) {
            #overlay {
                width: 100%; /* Full width (cover the whole page) */
                height: 100%; /* Full height (cover the whole page) */
                top:0;
                left: 0;
                right: 0;
                bottom: 0;
            }

            #text{
                width: 400px;
                height: 100px;
                left: 40%;

            }
        }
    </style>

<script type="text/javascript">
    $(document).ready(function() {
        $("#open_not_message").click(function(){
            $("#gen_message").modal('show');
        });
    });
    // document.addEventListener('contextmenu', event => event.preventDefault());
</script>
 <!-- Modal -->
    <div id="gen_message" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">&nbsp;</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="myWizard">
                   <center>
                    <p></p>
                   To have complete access to Yield Exchange please contact <b style="color:blue;"><u>info@yieldexchange.ca</u></b> to finish onboarding.
                   </center>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

<?php
if ( AuthModel::isLoggedIn() ) {
    global $user_data;
    ?>
        <div id="overlay"><div id="text">To have complete access to yield exchange please contact <b style="color:blue;"><u>info@yieldexchange.ca</u></b> to finish on boarding.</div></div>
    <?php
}
?>