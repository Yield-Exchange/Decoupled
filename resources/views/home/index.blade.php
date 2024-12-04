@extends('home.master')
@section('page_title')
    Home
@stop
@section('page_content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<!-- ====== Banner Part HTML Start ====== -->

<style>
/* Slider arrows */
.slide .card{
    border:0px;
}
.prev {
  display: block;
  position: absolute;
  z-index: 1000;
  top:40%;
  transform: translateY(-50%);
}

.next {
  display: block;
  position: absolute;
  right: 0px;
  top:40%;
  transform: translateY(-50%);
  z-index: 1000;
}
/* Dots */

.slick-dots {
	display: flex;
	justify-content: center;
	margin: 0;
	padding: 1rem 0;

	list-style-type: none;

}
.slick-dots li {
	margin: 0 0.25rem;
}

.slick-dots	button {
	display: block;
	width: 1rem;
	height: 1rem;
	padding: 0;
	border: none;
	border-radius: 100%;
	background-color: grey;
	text-indent: -9999px;
}

.slick-dots	li.slick-active button {
	background-color:#43E0AA /*#00acee*/;
}

</style>

<div id="banner">
    <span class="object object-1"></span>
    <span class="object object-2"></span>
    <span class="object object-3"></span>
    <span class="object object-4"></span>
    <span class="object object-5"></span>
    <img src="{{ asset('/assets/img/banner-img.svg') }}" alt="Image" class="banner-img">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="banner-text">
                    <h1>Change the way<br>
                        you negotiate<br>
                        GIC rates</h1>
                    <p>Yield Exchange is a platform for Canadian organizations to get the best rates for their GICs from
                        Canadian financial institutions.</p>
                    <a href="{{ url('/sign-up') }}" class="btn-space custom-btn2">Request an Account</a>
                    <a href="{{ url('/') }}#contact" class="btn-space custom-btn2" style="margin-left: 10px;">Request a Demo</a>
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
                                <img src="{{ asset('/assets/img/dots.png') }}" alt="Dots" class="dots">
                                <div class="eowb-item eowb-one">
                                    <img src="{{ asset('/assets/img/eowb-one.png') }}" alt="Image" class="eowb-one-bg">
                                    <div class="eowb-item-content">
                                        <div class="eowb-icon">
                                            <img src="{{ asset('/assets/img/icon-1.png') }}" alt="Icon" class="img-fluid w-100">
                                        </div>
                                        <h3>Efficient</h3>
                                        <p>No more wasting time on phone calls and never-ending negotiations. </p>
                                    </div>
                                </div>
                                <div class="eowb-item eowb-three" style="display:none">
                                    <div class="eowb-item-content">
                                        <div class="eowb-icon">
                                            <img src="{{ asset('/assets/img/icon-3.png') }}" alt="Icon" class="img-fluid w-100">
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
                                        <img src="{{ asset('/assets/img/icon-2.png') }}" alt="Icon" class="img-fluid w-100">
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
                                        <img src="{{ asset('/assets/img/icon-4.png') }}" alt="Icon" class="img-fluid w-100">
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
                        <a href="{{ url('/') }}#video-area" class="btn-space custom-btn2" >Learn More</a>
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
                            <img src="{{ asset('/assets/img/how-one-bg.png') }}" alt="Background" class="how-one-bg">
                            <div class="how-item-content">
                                <div class="how-icon">
                                    <img src="{{ asset('/assets/img/how-icon-1.png') }}" alt="How Icon">
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
                                    <img src="{{ asset('/assets/img/how-icon-2.png') }}" alt="How Icon">
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
                                    <img src="{{ asset('/assets/img/how-icon-3.png') }}" alt="How Icon">
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

<!-- ====== Brand Part HTML Start ====== -->

<section id="brand">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="sec-header">Partners Spotlight</h2>
                <div class="brand-slider slider card-deck">
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/Vancity_Logo_ReadyRead_RGB.png') }}" style="padding:10px;"  alt="Vancity_Logo_ReadyRead_RGB" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/WOBC Logo.png') }}" style="padding:10px;" alt="Summerland Logo" />
                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/summerland_fc.png') }}" style="padding:10px;" alt="Summerland Logo" />
                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/FW%20with%20regional%20logo.png') }}" style="padding:10px;" alt="FW With Regional Logo" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/RCU_Sig2_CMYK transparent.png') }}" style="padding:10px;" alt="RCU_Sig2_CMYK Logo" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/BVCU.png') }}" style="padding:10px;" alt="BVCU Logo" />

                        </div>
                    </div>

                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/Peoples%20Bank%20-%20500w.png') }}" style="padding:10px;" alt="BVCU Logo" />

                        </div>
                    </div>

                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/vcib_logo.png') }}" style="padding:10px;"  alt="Vancity_Logo_ReadyRead_RGB" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/ocu-horizontal-lockup-full-color-rgb-900px@144ppi.png') }}" style="padding:10px;" alt="OCU Logo" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/SCCU.png') }}" style="padding:10px;" alt="SCCU Logo" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/lsm_vert_logo_col.png') }}" style="padding:10px;" alt="lsm vart  Logo" />

                        </div>
                    </div>

                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/BlueShore_Financial.png') }}" style="padding:10px;" alt="lsm vart  Logo" />

                        </div>
                    </div>

                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/GulfandFraser.png') }}" style="padding:10px;" alt="lsm vart  Logo" />

                        </div>
                    </div>

                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/Laurentian_Bank.png') }}" style="padding:10px;" alt="lsm vart  Logo" />

                        </div>
                    </div>
                    <div class="slide" style="margin-bottom:20px;">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('/images/testimonials/NewNonFederal.png') }}" style="padding:10px;" alt="lsm vart  Logo" />

                        </div>
                    </div>

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
                    <img src="{{ asset('assets/img/dots.png') }}" alt="Dots" class="dots">
                    <div class="row">
                        <div class="col-lg-6 m-auto">
                            <h3>Contact Us</h3>
                        <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
                        <script>
                            hbspt.forms.create({
                                region: "na1",
                                portalId: "7987108",
                                formId: "0391ea68-4609-49fb-a5a2-9d6ed06150ef",
                            });
                        </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<!-- ====== Contact Part HTML End ====== -->
<script>

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
            e.preventDefault();

            if ( !isMobileValid() ) {
                swal("","Enter a valid phone number","info");
                return false;
            }

            if (jQuery(".recaptchar").val() === "") {
                jQuery("#recpatchareq").show();
                swal("","Please verify that you are not a robot","info");
                return false;
            } else {
                jQuery("#recpatchareq").hide();
            }

            $(".btn-send").attr('disabled', true).html('Sending...');
            $this = $('#contact-us');
            $loader = $(".proloader");
            makeApiCall($this.attr('action'), $this.serialize(), function(response) {
                swal("",response.message,"success");
                $(".btn-send").attr('disabled', false).html('Send');
                $this.trigger("reset");
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                swal("",apiCallServerErrorMessage(xhr,"Unable to send this message, try again later"),"error");
                $(".btn-send").attr('disabled', false).html('Send');
            });

            return false;
        });

    });

    function isMobileValid(){
        let mobile = $("#phone_number").val();
        let phone_no = /^\d{10}$/;
        return !!(mobile.match(phone_no));
    }

    /*$('.brand-slider').not('.slick-initialized').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1800,
        arrows: true,
        dots: true,
        prevArrow: "<i class='slick-prev pull-left fas fa-arrow-left' aria-hidden='true'></i>",
        nextArrow: "<i class=' slick-next pull-right fas fa-arrow-right' aria-hidden='true'></i>",
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
    });*/
</script>
@endsection