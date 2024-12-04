<?php

class Emails{


    public static function passwordReset($toEmail,$env,$other_buttons) {
        
        $subject = "Reset your Yield Exchange Password";
        $header ="Reset Your Password";
        $message = "<br/><center>You can reset your password by clicking the link below. For increased security, this password link will expire in 5 minutes after it was sent.</center>";

        Core::sendMail($subject, $toEmail, $message, $env,false,false,$other_buttons,true,$logo_position="top",$header);
    }

    public static function passwordResetConfirmation($email,$env,$datetime_from_utc,$resetLink,$chat_link,$other_buttons) {
        
        $show_login =true;
        if($env=='admin'){
            $show_login=false;
        }
        $subject="Password Changed";
        $header ="Your Password Changed";
        $message="<center>Your password was changed on ".$datetime_from_utc.".</center> <br/><center>If this was you, then you can safely ignore this email.</center><br><center> If this wasn't you, your account has been compromised. Please:</center><br> <div style='margin-left:150px;'> 1. <a href='".$resetLink."'>Reset your password</a>.<br></div> <div style='margin-left:150px;'> 2. <a href='mailto:info@yieldexchange.ca'>Email</a> us or use our <a href='".$chat_link."'>Chat</a>.</div>";
        
        Core::sendMail($subject, $email, $message, $env,$show_login, false,$other_buttons,true,$logo_position="top",$header);
        
    }

    public static function newPin($email,$_pin,$env) {
        $header = "Verification Code";
        $toEmail = $email;
        $subject = "Your Verification Code"; 
        $message = "<br/><center>Please use this one time verification code to login to your account.</center> <br/><div style='color:blue;width: 100%;text-align: center;padding-top:0;padding-bottom: 0;margin: 0'>".$_pin."</div> <br/> <center>This code is only available for a few minutes.</center>";
                     
        Core::sendMail($subject, $toEmail, $message, $env, false,false,[],true,$logo_position="top",$header);
    }

    public static function acccount_creation($email,$env,$other_buttons){
        $toEmail = $email;
        $subject = "Welcome to Yield Exchange!";
        $header ="Your account has been created";
        $message = "<center>Welcome to Yield Exchange. We are completing your account setup. You will be notified via email once you can sign in.<center><p><center>In the mean time please review our FAQs page to learn more about our platform.<center></p>";
                     
        Core::sendMail($subject, $toEmail, $message, $env,false,false,$other_buttons,true,$logo_position="top",$header);
    }
   
}
