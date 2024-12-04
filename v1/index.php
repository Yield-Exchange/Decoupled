<?php
session_start();
require_once "config/db.php";

require_once "config/Core.php";
require_once "config/AuthModel.php";

if( isset($_POST['send']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST['recaptcha']) ){
   AuthModel::authCsrfToken();

   if ( Core::verifyCaptcha($_POST['recaptcha'],SECRET_KEY) ) {
       $name = trim($_POST['name']);
       $email = trim($_POST['email']);
       $messages = trim($_POST['message']);
       $phone = trim($_POST['phone']);

       global $contact_us_notification_email;
       $toEmail = $contact_us_notification_email;
       $subject = "Contact Us Form";

       $message = '<p>';
       $message .= "<strong>Name:</strong> " . $name;
       $message .= "<br/><strong>Email:</strong> " . $email;
       $message .= "<br/><strong>Phone:</strong> " . $phone;
       $message .= "<br/><strong>Message:</strong> " . $messages;
       $message .= "</p>";

       Core::sendMail($subject,$toEmail, $message,'contact-us-form');
       $info = "Request sent successfully.";
       echo "<script>alert('" . $info . "');location='index'</script>";
   }else{
       $info = "Oops! Could not validate the recaptcha.";
       echo "<script>alert('" . $info . "');location='index'</script>";
   }
}

$token = AuthModel::generateToken();
require("includes/header.php");
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- ====== Banner Part HTML Start ====== -->

    <div id="banner">
        <span class="object object-1"></span>
        <span class="object object-2"></span>
        <span class="object object-3"></span>
        <span class="object object-4"></span>
        <span class="object object-5"></span>
        <img src="assets/img/banner-img.svg" alt="Image" class="banner-img">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="banner-text">
                        <h1>Change the way<br>
                            you negotiate<br>
                            GIC rates</h1>
                        <p>Yield Exchange is a platform for Canadian organizations to get the best rates for their GICs from
                            Canadian financial institutions.</p>
                        <a href="signup" class="btn-space custom-btn2">Request an Account</a>
                        <a href="#contact" class="btn-space custom-btn2" style="margin-left: 10px;">Request a Demo</a>
                    </div>
                </div>
                <div class="offset-lg-6"></div>
            </div>
        </div>
    </div>

    <!-- ====== Banner Part HTML End ====== -->

    <!-- ====== Eowb Part HTML End ====== -->

    <section id="eowb">
        <span class="object object-6"></span>
        <span class="object object-7"></span>
        <span class="object object-8"></span>
        <span class="object object-9"></span>
        <span class="object object-10"></span>
        <span class="object object-11"></span>
        <span class="object object-7 object-12"></span>
        <span class="object object-8 object-13"></span>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 pr-lg-4">
                    <div class="eowb-left">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="eowb-item-left">
                                    <img src="assets/img/dots.png" alt="Dots" class="dots">
                                    <div class="eowb-item eowb-one">
                                        <img src="assets/img/eowb-one.png" alt="Image" class="eowb-one-bg">
                                        <div class="eowb-item-content">
                                            <div class="eowb-icon">
                                                <img src="assets/img/icon-1.png" alt="Icon" class="img-fluid w-100">
                                            </div>
                                            <h3>Efficient</h3>
                                            <p>No more wasting time on phone calls and never-ending negotiations. </p>
                                        </div>
                                    </div>
                                    <div class="eowb-item eowb-three" style="display:none">
                                        <div class="eowb-item-content">
                                            <div class="eowb-icon">
                                                <img src="assets/img/icon-3.png" alt="Icon" class="img-fluid w-100">
                                            </div>
                                            <h3>Confidential</h3>
                                            <p>You are anonymous until you choose an institution to do your business
                                                with.
                                            </p>
                                            info
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="eowb-item eowb-two">
                                    <div class="eowb-item-content">
                                        <div class="eowb-icon">
                                            <img src="assets/img/icon-2.png" alt="Icon" class="img-fluid w-100">
                                        </div>
                                        <h3>Transparent</h3>
                                        <p>All associated financial
                                            institutions will have an equal
                                            opportunity to offer you the
                                            best GIC interest rate.</p>
                                    </div>
                                </div>
                                <div class="eowb-item eowb-four">
                                    <div class="eowb-item-content">
                                        <div class="eowb-icon">
                                            <img src="assets/img/icon-4.png" alt="Icon" class="img-fluid w-100">
                                        </div>
                                        <h3>Cost effective</h3>
                                        <p>Immediate savings from traditional wholesale investor channels and reduction in operational costs.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="eowb-right">
                        <h2 class="sec-header">Yield Exchange is a great tool to get
                            the best GIC rates for:</h2>
                        <div class="eowb-list">
                            <ul>
                                <li>Federal organizations</li>
                                <li>Provincial organizations</li>
                                <li>Municipalities</li>
                                <li>Universities</li>
                                <li>Colleges</li>
                                <li>Trusts</li>
                            </ul>
                            <ul class="ml-sm-4 ml-lg-4">
                                <li>School boards</li>
                                <li>Hospitals</li>
                                <li>Community foundations</li>
                                <li>Strata councils and property managers</li>
                                <li>Developers</li>
                            </ul>
                        </div>
                        <div class="go">
                           <a href="#video-area" class="btn-space custom-btn2" >Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== Eowb Part HTML End ====== -->

    <!-- ====== How Part HTML Start ====== -->

    <section id="how">
        <span class="object object-10 object-14"></span>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="sec-header">How it works</h2>
                </div>
                <div class="col-lg-11 m-auto">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 px-lg-4">
                            <div class="how-item how-item-one">
                                <img src="assets/img/how-one-bg.png" alt="Background" class="how-one-bg">
                                <div class="how-item-content">
                                    <div class="how-icon">
                                        <img src="assets/img/how-icon-1.png" alt="How Icon">
                                    </div>
                                    <h3>Request</h3>
                                    <p> Post your request in less than <br>
                                        a few minutes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 px-lg-4">
                            <div class="how-item how-item-two">
                                <div class="how-item-content">
                                    <div class="how-icon">
                                        <img src="assets/img/how-icon-2.png" alt="How Icon">
                                    </div>
                                    <h3>Receive</h3>
                                    <p> You will get offers from our <br>
                                        associated financial institutions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 px-lg-4">
                            <div class="how-item how-item-three">
                                <div class="how-item-content">
                                    <div class="how-icon">
                                        <img src="assets/img/how-icon-3.png" alt="How Icon">
                                    </div>
                                    <h3>Award</h3>
                                    <p> Choose the offer that suits you <br>
                                        best</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== How Part HTML End ====== -->

    <!-- Video area Start  -->

<!--    <div id="video-area">-->
<!--        <div class="container">-->
<!--            <div class="row justify-content-center">-->
<!--                    <div class="col-lg-6">-->
<!--                            <div class="video-part">-->
<!--                                <span class="object object-11 object-15"></span>-->
<!--                                <div class="about-video">-->
<!--                                    <img src="assets/img/about-video.jpg" alt="Image" class="img-fluid w-100">-->
<!--                                    <a class="venobox vbox-item" data-autoplay="true" data-vbtype="video" href="https://youtu.be/sgJEHOWLGTw">-->
<!--                                        <div class="video-overlay">-->
<!--                                            <span><i class="fas fa-play-circle"></i></span>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <!-- Video area Start  -->


    <!-- ====== About Part HTML Start ====== -->

    <section id="about" style="margin-top: 50px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="h2 sec-header">About us</div>
                    <div class="about-text">
                      <div class="about-single-text">
                        <span>We are a Canadian online GIC marketplace.</span>
                        <p>Our Yield Exchange platform is a secure, transparent, time and cost-effective way to get the best GIC rates </p>
                        <span>Do you manage funds on behalf of your employer?</span>
                        <p>If so, you know that GIC rates vary from financial institution to financial institution based on their cash flow requirements.</p>
                        <p>To quickly find the best rate, Canadian municipalities, public and private universities and colleges, school boards, hospitals, health authorities, companies and charities (wholesale investors) use our platform to post investment requirements. Trusted and familiar financial Institutions respond to those requirements with their best available rates.</p>
                        <p>Yield Exchange is a way to feel confident that your deposits are valued by Canadian financial institutions, and that you've done your best for your employer.</p>
                        </p>
                        <span>Do you represent a financial institution?</span>
                        <p>Yield Exchange is one stop GIC marketplace for wholesale deposits.  Our platform is equitable, allowing all associated financial institutions an equal opportunity attract wholesale depositors.  Best of all, you are not paying for the inefficient manual. traditional deposit brokerage processes.   Yield Exchange is faster, simpler and smarter.  </p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== About Part HTML End ====== -->

    <!-- ====== Brand Part HTML Start ====== -->

    <section id="brand">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="sec-header">Partners Spotlight</h2>
                    <div class="brand-slider slider">
                        <div class="slide"><img src="images/testimonials/WOBC Logo.png" style="width: 100%" alt="WOBC Logo" /></div>
                        <div class="slide"><img src="images/testimonials/summerland_fc.png" style="width: 100%" alt="Summerland Logo" /></div>
                        <div class="slide"><img src="images/testimonials/concentra.png" style="width: 100%" alt="CONCE-LOGO" /></div>
                        <div class="slide"><img src="images/testimonials/FW%20with%20regional%20logo.png" style="width: 100%" alt="FW With Regional Logo" /></div>
                        <div class="slide"><img src="images/testimonials/RCU_Sig2_CMYK transparent.png" style="width: 100%" alt="RCU_Sig2_CMYK Logo" /></div>
                        <div class="slide"><img src="images/testimonials/BVCU.png" style="width: 100%" alt="BVCU Logo" /></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== Brand Part HTML End ====== -->

    <!-- ====== Contact Part HTML Start ====== -->

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <div class="contact-form">
                        <img src="assets/img/dots.png" alt="Dots" class="dots">
                        <div class="row">
                            <div class="col-lg-6 m-auto">
                                <form action="index.php" method="post" enctype="multipart/form-data" id="contact-us" autocomplete="off">
                                    <h3>Contact Us</h3>
                                    <?php
                                    if(!empty($info)){
                                    ?>
                                    <div class="alert alert-success" id="contact-info">
                                        <i class="fa fa-check"></i> <?php echo $info;?>
                                    </div><br/><br/>
                                    <?php
                                    }
                                    ?>
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Name" class="form-control" required>
                                        <input type="hidden" name="_token" value="<?php echo $token;?>"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" id="phone_number" name="phone" placeholder="Phone" class="form-control" required>
                                     </div>
                                    <div class="form-group">
                                        <input type="email" name="email"  placeholder="Email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea placeholder="Message" maxlength="500" name="message" class="form-control textareaWithTextLimit" required></textarea>
                                        <span style="color: white;"><span id="rchars">500</span> Character(s) Remaining</span>
                                    </div>
<!--                                    <div class="row align-items-center pt-3">-->
<!--                                        <div class="col-12 file-container">-->
<!--                                            <div class="row">-->
<!--                                                <div class="col-md-10 emptyFile">-->
<!--                                                    <input type="file" name="file[]" class="form-control"/>-->
<!--                                                </div>-->
<!--                                                <div class="col-md-2">-->
<!--                                                    <button class="btn btn-danger" type="button" onclick="emptyFile()"> - </button>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="col-12 createNewFile">-->
<!--                                            <br/>-->
<!--                                            <button class="btn btn-secondary" type="button" onclick="addAnotherFile();"> Add Another File </button>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="row align-items-center pt-3">
                                        <div class="col-12">
                                            <label id="recpatchareq" style="color:white;display:none">Please verify that you are not a robot</label>
                                            <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_KEY;?>" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div>
                                            <input class="form-control d-none" name="recaptcha" data-recaptcha="true" required data-error="Please complete the Captcha"/>
                                        </div>
<!--                                        <div class="col-12">-->
<!--                                            <br/>-->
<!--                                            <span class="form-text text-center text-white">By continuing, you're confirming that you've read our <a href="/terms_condition">Terms &amp; Conditions</a> and <a href="/privacy_policy">Privacy and cookie policy</a></span>-->
<!--                                            <br/>-->
<!--                                        </div>-->
                                        <div class="col-12 text-right">
                                            <button type="submit" name="send" class="btn-space custom-btn btn-send">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== Contact Part HTML End ====== -->
    <script>

        function emptyFile(){
            $(".emptyFile").html("<input type='file' name='file[]' class='form-control'/>");
        }

        $('.textareaWithTextLimit').keyup(function() {
            let maxLength = parseInt($(this).attr("maxlength"));
            let text_length = maxLength - $(this).val().length;
            $(this).parent().find('#rchars').text(text_length);
        });

        function checkRecaptcha() {

            if (jQuery('input[data-recaptcha]').val() === "") {
                jQuery("#recpatchareq").show();
            } else {
                jQuery("#recpatchareq").hide();
            }

        }

        function isNumberKey(evt){
            if ((evt.which != 46 || evt.value.indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
            }
            let input = evt.value;
            if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
                return false;
            }
        }

        $(function () {

            checkRecaptcha();
            window.verifyRecaptchaCallback = function (response) {
                $('input[data-recaptcha]').val(response).trigger('change');
            };

            window.expiredRecaptchaCallback = function () {
                $('input[data-recaptcha]').val("").trigger('change');
            };

            $('#contact-us').on('submit', function (e) {

                if ( !isMobileValid() ) {
                    alert("Enter a valid phone number");
                    return false;
                }

                if (jQuery(".recaptchar").val() === "") {
                    jQuery("#recpatchareq").show();
                    alert("Please verify that you are not a robot");
                    return false;
                } else {
                    jQuery("#recpatchareq").hide();
                }

                if (!e.isDefaultPrevented()) {
                    $(".btn-send").attr('disabled', true);
                    $('#contact-us').append("<input type='hidden' name='send' value=''/>");
                    $('#contact-us').submit();
                    return false;
                }
            });

        });

        function isMobileValid(){
            let mobile = $("#phone_number").val();
            let phone_no = /^\d{10}$/;
            return !!(mobile.match(phone_no));
        }

        $(".createNewFile").hide();
        $(document).on("change","#contact-us input[type=file]",function () {
            let files = $(this)[0].files;

            if ( files.length > 0 && files.length <= 5 ){
                $(".createNewFile").show();
            }else{
                $(".createNewFile").hide();
            }
        });

        function addAnotherFile() {
            if ( $("#contact-us input[type=file]").length < 5) {
                $(".createNewFile").show();
                $(".file-container").append('<div class="row" style="margin-top: 10px;"> <div class="col-md-10"><input type="file" name="file[]" class="form-control"/></div><div class="col-md-2"><button class="btn btn-danger" type="button" onclick="removeFile(this)"> - </button></div></div>');
            }else{
                $(".createNewFile").hide();
            }
        }

        function removeFile(e) {
            $(e).parent().parent().remove();
        }

        $('.brand-slider').not('.slick-initialized').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 1800,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 3
                }
            }]
        });
    </script>

<?php
    require "includes/footer.php";
?>
