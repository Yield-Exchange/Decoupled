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

        if (jQuery(".recaptchar").val() === "") {
            jQuery("#recpatchareq").show();
            swal("Please verify that you are not a robot");
            return false;
        } else {
            jQuery("#recpatchareq").hide();
        }

        if ( !e.isDefaultPrevented()  ) { //password_score >= 3
            $('#signup-form').append("<input type='hidden' name='signup' value=''/>");
            $('#signup-form').submit();
            return false;
        }else{
            return false;
        }

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

        if($(this).val() === "1") {
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