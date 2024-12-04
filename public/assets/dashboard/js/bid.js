$(document).ready(function () {
    $(".select2").select2({
        width: "100%",
    });

    // let minutesDiff = 60 - parseInt(dateOfDepositWithUserTimezone.minutes());
    // let nextHours = 1;
    // if (minutesDiff > 0){
    //     nextHours+=1;
    // }

    let date = new Date();
    let timeFromDate = `${date.getHours()}:${date.getMinutes()}`;

    let dateOfDepositWithUserTimezone_copy = dateOfDepositWithUserTimezone.add(
        1,
        "days"
    );
    let timeNow = timeFromDate;

    // if (nextHours+1+dateOfDepositWithUserTimezone.hours() >= 24){
    //     timeNow = ((nextHours+1+dateOfDepositWithUserTimezone.hours()) - 24)+":00";
    //     dateOfDepositWithUserTimezone_copy.add(24,"hours");
    // }

    $(".datetimepicker").datetimepicker({
        minDate: dateOfDepositWithUserTimezone_copy.format("YYYY/MM/DD"),
        minTime: timeNow,
        // maxDate: dateOfDepositWithUserTimezone_copy.format("YYYY/MM/DD"),
        lang: "en",
        format: "Y-m-d H:i A",
        onChangeDateTime: function (dp, $input) {
            if (
                moment($input.val(), "YYYY/MM/DD HH:mm A").format(
                    "YYYY-MM-DD"
                ) === dateOfDepositWithUserTimezone.format("YYYY-MM-DD")
            ) {
                this.setOptions({
                    minTime: timeNow,
                });
            } else if ($input.val() !== "") {
                this.setOptions({
                    minTime: "00:00",
                });
            }

            let dateOfDepositPlusNextHours = moment(
                dateOfDepositWithUserTimezone_copy.format("YYYY/MM/DD") +
                    " " +
                    timeNow,
                "YYYY/MM/DD HH:mm"
            ); // this is logically added here DO NOT REMOVE!
            if (
                dateOfDepositPlusNextHours <=
                moment($input.val(), "YYYY/MM/DD HH:mm A")
            ) {
                // TODO
            } else if ($input.val() !== "") {
                $input.val("");
            }
        },
    });

    if (typeof minimum_counter_offer_expiry !== "undefined") {
        $(".counter-offer-datetimepicker").datetimepicker({
            minDate: minimum_counter_offer_expiry.format("YYYY/MM/DD"),
            minTime: minimum_counter_offer_expiry.format("H:i"),
            maxDate: dateOfDepositWithUserTimezone_copy.format("YYYY/MM/DD"),
            lang: "en",
            format: "Y-m-d H:i A",
            onChangeDateTime: function (dp, $input) {
                if (
                    moment($input.val(), "YYYY/MM/DD HH:mm A").format(
                        "YYYY-MM-DD"
                    ) === minimum_counter_offer_expiry.format("YYYY/MM/DD")
                ) {
                    this.setOptions({
                        minTime: minimum_counter_offer_expiry.format("H:i"),
                    });
                } else if ($input.val() !== "") {
                    this.setOptions({
                        minTime: "00:00",
                    });
                }
            },
        });
    }
});

function checkURLHttps(abc) {
    let string = abc.value;
    if (!string || string === "") {
        abc.setCustomValidity("");
        return true;
    }
    if (string.indexOf("http") === 0) {
        string = string.replace("http://", "");
        string = string.replace("https://", "");
    }
    abc.value = string;

    if (!isValidURL($(".pre_url").val() + string)) {
        abc.setCustomValidity("Please enter valid url");
        // console.log("not a valid url");
    } else {
        abc.setCustomValidity("");
        // console.log("a valid url");
    }

    return abc;
}

// let elm;
function isValidURL(u) {
    if (!u || u === "") {
        return true;
    }

    let res = u.match(
        /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
    );
    return res !== null;
    // if(!elm){
    //     elm = document.createElement('input');
    //     elm.setAttribute('type', 'url');
    // }
    // elm.value = u;
    // return elm.validity.valid;
}

$(function () {
    $(".file").change(function () {
        let f = this.files[0];
        let size = parseFloat(f.size / 1000 / 1000).toFixed(2);
        if (size > 25) {
            swal("The file size is greater than 25mb which is not allowed");
            removeFile($(this));
        } else {
            if (!validateFileType(f)) {
                swal(
                    "Please upload a disclosure statement in a valid format: jpeg, gif, png, pdf and word documents"
                );
                removeFile($(this));
            }
        }
    });
});

function validateFileType(file) {
    let fileType = file.type;
    switch (true) {
        case fileType.startsWith("image/"):
        case fileType.startsWith(
            "application/vnd.openxmlformats-officedocument"
        ):
        case fileType.startsWith("application/pdf"):
        case fileType.startsWith("application/msword"):
        case fileType.startsWith("application/vnd.oasis.opendocument"):
            // case fileType.startsWith('video/'):
            return true;
        default:
            return false;
    }
}

function removeFile(e) {
    $(e).parent().wrap("<form>").closest("form").get(0).reset();
    $(e).parent().unwrap();
    $(".disclosure_attachmentF").remove();
    $("#attached_file").val("");
}

function addThousands(e) {
    let off = $(e).val().replace(/,/g, "");
    off = off >= 0 ? off : "";
    $(e).val(thousandSeparator(off, ","));
}

$(function () {
    $("form#bid_offer_form").dirty({
        preventLeaving: true,
        leavingMessage:
            "Are you sure you want to leave this page? Your request changes won't be saved!",
        // onDirty: function(){
        //     $(".message").html("The Form Is Dirty!")
        // }
    });

    // $('form#bid_offer_form').areYouSure( {message:'Are you sure you want to leave this page? Your request changes won\'t be saved!'} );
});
