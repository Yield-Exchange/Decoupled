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

function myFunction(thi) {
    let x = document.getElementById("lgd_out_pg_pass");
    let x_ = document.getElementById("conf_pass");
    if (thi.checked) {
        x.type = "text";
        x_.type = "text";
    } else {
        x.type = "password";
        x_.type = "password";
    }
}

$(document).ready(function() {
    // $("#form-submit").submit(function () {
    //     return password_score >= 3;
    // });

    $(".pass_confirm").on('keyup change',function () {
        let value = $(this).val();

        if (value !== "") {

            if (value !== $(".password").val()) {
                $("#pass_error_confirm").show();
                $("#pass_error_confirm").html("Password do not match");
                $(".change_pwd").attr('disabled', true);
            } else {
                $("#pass_error_confirm").hide();
                $("#pass_error_confirm").html("");
                // if (password_score >= 3) {
                    $(".change_pwd").removeAttr('disabled');
                // }
            }

        }else{
            $("#pass_error_confirm").hide();
            $("#pass_error_confirm").html("");
        }
    });
});