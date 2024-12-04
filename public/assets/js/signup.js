let password_score=0;
$(document).ready(function (){
    let password=$('#lgd_out_pg_pass').val();
    password_score = zxcvbn(password).score;
    if(password=="") password_score = 0;
    $("#lgd_out_pg_pass").keyup(function (){
        password = $(this).val();
        if(password=="") password_score = 0;
        password_score = zxcvbn(password).score;

        let value = $(".pass_confirm").val();
        if(value !== ""){

            if(value !== $(this).val()){
                $("#pass_error_confirm").show();
                $("#pass_error_confirm").html("Password do not match");
                $(".signup_btn").attr('disabled',true);
            }else{
                $("#pass_error_confirm").hide();
                $("#pass_error_confirm").html("");
                // if (password_score >= 3){
                    $(".signup_btn").removeAttr('disabled');
                // }
            }
        }

    });
});

function checkRecaptcha() {

    if (jQuery('input[data-recaptcha]').val() === "") {
        jQuery("#recpatchareq").show();
    } else {
        jQuery("#recpatchareq").hide();
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

    $('#signup-form').on('submit', function (e) {
        e.preventDefault();

        if (jQuery(".recaptchar").val() === "") {
            jQuery("#recpatchareq").show();
            swal("Please verify that you are not a robot");
            return false;
        } else {
            jQuery("#recpatchareq").hide();
        }

        $(".signup_btn").attr('disabled', true).html('Registering...');
        $this = $('#signup-form');
        $loader = $(".proloader");
        let userType = $('input[name="userType"]:checked').val();
        let formData = {
            'userType': userType,
            'timezone': $('select[name="time_zone"]').val(),
            'name': userType==="Depositor" ? $('input[name="institution"]').val() : $('select[name="institution_s"]').val(),
            'address': $('input[name="address"]').val(),
            'admin_name': $('input[name="admin_name"]').val(),
            'address2': $('input[name="address2"]').val(),
            'city': $('input[name="city"]').val(),
            'province': $('select[name="province"]').val(),
            'postal': $('input[name="postal"]').val(),
            'email': $('input[name="email"]').val(),
            'telephone': $('input[name="telephone"]').val(),
            'pass': $('input[name="pass"]').val(),
            'cpass':$('input[name="cpass"]').val(),
            'recaptcha':$('input[name="recaptcha"]').val(),
            '_token':$('input[name="_token"]').val()
        };
        makeApiCall($this.attr('action'), formData, function(response) {
            swal("",response.message,"success").then(function () {
                $loader.show();
                window.location.href="/login";
            });
            $(".signup_btn").attr('disabled', false).html('Register');
            $this.trigger("reset");
        }, $loader,"POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)){
                swal("An error occurred, the page will refresh.").then(()=>{
                    window.onbeforeunload = null;
                    window.location.reload();
                });
            }
            swal(apiCallServerErrorMessage(xhr,"Unable to sign up, try again later"));
            $(".signup_btn").attr('disabled', false).html('Register');
        });

    });

});

$(document).ready(function(){

    $(".pass_confirm").keyup(function(){
        let value = $(this).val();

        if(value !== ""){

            if(value !== $(".password").val()){
                $("#pass_error_confirm").show();
                $("#pass_error_confirm").html("Password do not match");
                $(".signup_btn").attr('disabled',true);
            }else{
                $("#pass_error_confirm").hide();
                $("#pass_error_confirm").html("");
                // if (password_score >= 3){
                    $(".signup_btn").removeAttr('disabled');
                // }
            }

        }
    });

    $(".select_institution").hide();
    $(".register_as").change(function () {

        if($(this).val() === "Bank") {
            $(".select_institution").show();
            $(".input_institution").hide();
            $("#short_term_credit").show();
            $("#deposit_insurance").show();
            $(".institution_s").attr("required",true);
            $(".deposit_insurance").attr("required",true);
        }else{
            $(".select_institution").hide();
            $(".input_institution").show();
            $("#short_term_credit").hide();
            $("#deposit_insurance").hide();
            $(".institution_s").removeAttr("required");
            $(".deposit_insurance").removeAttr("required");
        }

    });

});

function myFunction(thi) {
    let x = document.getElementById("lgd_out_pg_pass");
    let x_ = document.getElementById("myInput1");
    if (thi.checked) {
        x.type = "text";
        x_.type = "text";
    } else {
        x.type = "password";
        x_.type = "password";
    }
}