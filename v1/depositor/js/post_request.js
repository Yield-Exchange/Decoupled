$(document).ready(function() {
    let format = 'YYYY/MM/DD HH:mm:ss ZZ';

    let currentDateTimeWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);
    let minutesDiff = 60 - parseInt(currentDateTimeWithUserTimezone.minutes());
    let nextHours = 1;
    if (minutesDiff > 0){
        nextHours+=1;
    }

    let currentDateTimeWithUserTimezone_copy = currentDateTimeWithUserTimezone;
    let timeNow = (nextHours+1+currentDateTimeWithUserTimezone.hours())+":00";
    if (nextHours+1+currentDateTimeWithUserTimezone.hours() >= 24){
        timeNow = ((nextHours+1+currentDateTimeWithUserTimezone.hours()) - 24)+":00";
        currentDateTimeWithUserTimezone_copy.add(24,"hours");
    }

    let currentDate = currentDateTimeWithUserTimezone_copy.format("YYYY/MM/DD");
    let currentDateUserTimezone = currentDateTimeWithUserTimezone.date();
    $('.datetimepicker').datetimepicker({
        minDate: currentDate,
        minTime:timeNow,
        lang: 'en',
        formatTime:'h:i a',
        validateOnBlur : true,
        onChangeDateTime:function(dp,$input){

            if(new Date($input.val()).getDate() === currentDateUserTimezone) {
                this.setOptions({
                    minTime: timeNow
                });
            }else if ($input.val() !== ""){
                this.setOptions({
                    minTime: "00:00"
                });
            }

            let currentDateTimeWithUserTimezonePlusNextHours = moment(currentDate+" "+timeNow,'YYYY/MM/DD HH:mm',timeZone); // this is logically added here DO NOT REMOVE!
            if( currentDateTimeWithUserTimezonePlusNextHours <= moment($input.val(),'YYYY/MM/DD HH:mm',timeZone) ) {

                let newDate_24_hours = moment($input.val(),'YYYY/MM/DD HH:mm',timeZone);
                newDate_24_hours = newDate_24_hours.add(24,"hours");

                let date_picker = $('.date_picker');
                date_picker.datetimepicker('destroy');
                date_picker.datetimepicker({
                    timepicker: false,
                    format: 'Y-m-d',
                    minDate: newDate_24_hours.format("YYYY/MM/DD"),
                    validateOnBlur: true,
                    onChangeDateTime:function(dp,$input) {
                        if ( moment($input.val()+" 23:59",'YYYY/MM/DD HH:mm',timeZone) >= moment($('.datetimepicker').val(),'YYYY/MM/DD HH:mm',timeZone).add(24,"hours") ) {
                            // TODO
                        }else{
                            $input.val("");
                        }
                    }
                });

            }else if ($input.val() !== ""){
                $input.val("");
            }
        }
    });

    let currentDateTimeWithUserTimezone_24_hours = currentDateTimeWithUserTimezone.clone();
    currentDateTimeWithUserTimezone_24_hours = currentDateTimeWithUserTimezone_24_hours.add(24,"hours");

    $('.date_picker').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        minDate: currentDateTimeWithUserTimezone_24_hours.format("YYYY/MM/DD"),
        validateOnBlur : true,
        onChangeDateTime:function(dp,$input) {
            if ( moment($input.val()+" 23:59",'YYYY/MM/DD HH:mm',timeZone) >= moment($('.datetimepicker').val(),'YYYY/MM/DD HH:mm',timeZone).add(24,"hours") ){
                // TODO
            }else{
                $input.val("");
            }
        }
    });

    $(document).on("submit",".post_request_form",function(){
        if(parseInt($(".term_length").val().replace(/,/g, "")) > 8000) {
            swal("Term length can't be more than 8,000");
            return false;
        }

        if(parseInt($(".dep_amm").val().replace(/,/g, "")) > 99999999999){
            swal("Deposit amount can't be more than 99,999,999,999");
            return false;
        }

        if(!$(".lockout_period").attr("disabled") && parseInt($(".lockout_period").val()) > 3660) {
            swal("Lockout period can't be more than 3660");
            return false;
        }

        if ( moment($("#offer_closing_date").val()) > moment($("#offer_opening_date").val()) ){
            swal("Closing date & time can't be greater than date of deposit");
            return false;
        }

        return true;
    });

    $('#div1').hide();
    $('#div2').hide();
    $('#div3').hide();
});

function show(){
    if(document.getElementById("div1").style.display=="none"){
        $('#div1').show();
        $('#div2').show();
        $('#div3').show();
    }else{
        $('#div1').hide();
        $('#div2').hide();
        $('#div3').hide();
    }
}

$(document).on("change",".productos",function(){
    $lockout_period_container = $(".lockout-period-container");
    $term_length_container=$(".term-length-container");
    $lockout_period=$(".lockout_period");
    $period_label=$(".period-label");
    $term_type=$(".term_type");
    $term_length=$(".term_length");

    $term_length_container.show();
    $lockout_period_container.show();
    $term_type.attr("required", "required");
    $term_length.attr("required", "required");
    if($(this).find(":selected").text().includes("Cashable")) {
        $lockout_period.removeAttr("disabled");
        $lockout_period.attr("placeholder", "Enter Lockout Period");
        $lockout_period.attr("required", "required");
        $period_label.html("Lockout period (days)");
    }else if($(this).find(":selected").text().includes("Notice deposit")){
        $lockout_period.removeAttr("disabled");
        $lockout_period.attr("placeholder", "Enter Notice Period");
        $lockout_period.attr("required", "required");
        $period_label.html("Notice period (days)");
    }else if($(this).find(":selected").text().includes("High Interest Savings")){
        $term_length_container.hide();
        $lockout_period_container.hide();
        $lockout_period.removeAttr("required");
        $term_type.removeAttr("required");
        $term_length.removeAttr("required");
    }else{
        $lockout_period.attr("disabled","disabled");
        $lockout_period.attr("placeholder","-");
        $lockout_period.val("");
        $lockout_period.removeAttr("required");
        $period_label.html("Lockout period (days)");
    }
});

$(document).on("keyup",".term_length",function () {
    let value = $(this).val();
    if ( parseInt(value) < 1 ){
        $(this).val("1");
    }
});

function addThousands(e){
    let off = $(e).val().replace(/,/g, "");
    off = off > 0 ? off : "";
    $(e).val(thousandSeparator(off, ','));
}

$(function() {
    $("form.post_request_form").dirty({
        preventLeaving: true,
        leavingMessage: 'Are you sure you want to leave this page? Your request changes won\'t be saved!',
        // onDirty: function(){
        //     $(".message").html("The Form Is Dirty!")
        // }
    });
    // $('form.post_request_form').areYouSure( {message:'Are you sure you want to leave this page? Your request changes won\'t be saved!'} );
});


$('a[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    html: true,
    viewport: '#div1'
});