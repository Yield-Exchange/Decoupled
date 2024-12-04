<?php
require_once __DIR__."/../../config/db.php";
require_once __DIR__."/../../config/Model.php";
require_once __DIR__."/../../config/AuthModel.php";
?>
<!doctype html>
<html>
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo BASE_URL.'/assets/images/favicon.png'?>" type="image/x-icon" />
    <link href="<?php echo BASE_URL?>/assets/css/login_theme.min.css" rel="stylesheet">
    <title>Yield Exchange Authorization</title>
</head>
<main class="center-segment" style="padding-top: 150px">
    <div class="ui container medium center aligned middle aligned" style="width: 30%">

        <div class="product-title">
            <div class="theme-icon inline auto transparent product-logo portal-logo">
                <img style="height:35px" src="<?php echo BASE_URL.'/assets/images/logo_light.png'?>" alt="product-logo" />
            </div>
        </div>
        <div class="ui segment">
            <div class="segment-form">
                <div class="ui visible <?php echo !$success?'negative':'positive';?> message" id="error-msg" data-testid="login-page-error-message"><?php echo $message;?></div>
                <div class="ui large form" id="loginForm">
                    <div class="ui divider hidden"></div>
                    <div class="ui two column stackable grid">
                        <div class="column mobile center aligned tablet left aligned computer left aligned buttons tablet no-padding-left-first-child computer no-padding-left-first-child">
                            <a type="button" class="ui large button link-button" href="<?php echo BASE_URL;?>" tabindex="8" role="button" data-testid="login-page-create-account-button" style="color: #2CADF5">
                                Go back home
                            </a>
                        </div>
                        <?php if (!$hide_login){
                            ?>
                            <div class="column mobile center aligned tablet right aligned computer right aligned buttons tablet no-margin-right-last-child computer no-margin-right-last-child">
                                <a href="/login" class="ui primary large button" tabindex="4" role="button" data-testid="login-page-continue-login-button" style="background-color: #2CADF5">
                                    Login
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<footer class="footer" style="text-align: center; margin-top: 20px">
    <div class="container-fluid">
        <p>Yield Exchange &copy;
            <script>document.write(new Date().getFullYear());</script>
        </p>
    </div>
</footer>
</body>
</html>