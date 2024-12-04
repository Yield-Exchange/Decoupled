$(document).ready(function () {
    let format = "YYYY/MM/DD HH:mm:ss ZZ";

    let currentDateTimeWithUserTimezone = moment(
        moment().toISOString(),
        format
    ).tz(timeZone);

    // let minutesDiff = 30 - parseInt(currentDateTimeWithUserTimezone.minutes()); // 30-55=-25
    //
    // let nextHours = 0.5;
    // let timeNow = (currentDateTimeWithUserTimezone.hours())+":30";
    // if (minutesDiff != 0){
    //     nextHours = 1;
    //     timeNow = (nextHours+currentDateTimeWithUserTimezone.hours())+":00";//7:00
    // }
    //
    let currentDateTimeWithUserTimezone_copy = currentDateTimeWithUserTimezone;
    // currentDateTimeWithUserTimezone_copy.add(30,"minutes");
    let minutes_ = currentDateTimeWithUserTimezone_copy.minutes(); //(nextHours+currentDateTimeWithUserTimezone.hours())+":00";
    let timeNow;
    let hours_to_set = currentDateTimeWithUserTimezone_copy.hours() + 1;

    // console.log("hours_to_set");
    // console.log(hours_to_set);
    if (hours_to_set >= 24) {
        // if it's the next day, then day will change
        currentDateTimeWithUserTimezone_copy.add(24, "hours");
        hours_to_set = 0;
        // console.log("------###-----");
        // console.log(currentDateTimeWithUserTimezone_copy);
        timeNow = currentDateTimeWithUserTimezone_copy
            .set({ hour: hours_to_set, minute: 30 })
            .format("HH:mm");
    } else {
        if (minutes_ >= 30) {
            // add an hour eg 7:55 should be 8:30
            timeNow = currentDateTimeWithUserTimezone_copy
                .set({ hour: hours_to_set, minute: 30 })
                .format("HH:mm");
            // console.log("------&&&-----");
            // console.log(timeNow);
        } else {
            // add 30 minutes eg 7:15 should be 8:00
            timeNow = currentDateTimeWithUserTimezone_copy
                .set({ hour: hours_to_set, minute: 0 })
                .format("HH:mm");
            // console.log("------***-----");
            // console.log(timeNow);
        }
    }
    // if (nextHours+currentDateTimeWithUserTimezone.hours() >= 24){
    //     let minutes = "00";
    //     if(nextHours === 0.5){
    //         nextHours = nextHours-0.5;
    //         minutes = "30";
    //     }
    //     timeNow = ((nextHours+currentDateTimeWithUserTimezone.hours()) - 24)+":"+minutes;
    //     currentDateTimeWithUserTimezone_copy.add(24,"hours");
    // }

    let currentDate = currentDateTimeWithUserTimezone_copy.format("YYYY/MM/DD");
    // let currentDateUserTimezone = currentDateTimeWithUserTimezone.date();

    let $default_post_request_form_container = $(
        ".post_request_form_container"
    );

    // console.log("------|||||-----");
    // console.log(timeNow);

    $default_post_request_form_container
        .find(".datetimepicker")
        .datetimepicker({
            minDate: currentDate,
            minTime: timeNow,
            lang: "en",
            step: 30,
            format: "Y-m-d H:i A",
            // formatTime:'h:i a',
            validateOnBlur: true,
            onChangeDateTime: function (dp, $input) {
                // if(new Date($input.val()).getDate() === currentDateUserTimezone) {
                if (
                    moment($input.val(), "YYYY/MM/DD HH:mm A").format(
                        "YYYY-MM-DD"
                    ) === currentDateTimeWithUserTimezone.format("YYYY-MM-DD")
                ) {
                    this.setOptions({
                        minTime: timeNow,
                        timeHeightInTimePicker: "10px", //DO NOT REMOVE => fixes the issue with blank times on switching today
                    });
                } else if ($input.val() !== "") {
                    this.setOptions({
                        minTime: "00:00",
                    });
                }

                let currentDateTimeWithUserTimezonePlusNextHours = moment(
                    currentDate + " " + timeNow,
                    "YYYY/MM/DD HH:mm",
                    timeZone
                ); // this is logically added here DO NOT REMOVE!
                if (
                    currentDateTimeWithUserTimezonePlusNextHours <=
                    moment($input.val(), "YYYY/MM/DD HH:mm A", timeZone)
                ) {
                    let newDate_24_hours = moment(
                        $input.val(),
                        "YYYY/MM/DD HH:mm A",
                        timeZone
                    );
                    let maxDateOfDeposit = moment(
                        addWeekdays(newDate_24_hours.toDate(), 6),
                        "YYYY/MM/DD HH:mm",
                        timeZone
                    );
                    newDate_24_hours = newDate_24_hours.add(24, "hours");

                    let date_picker =
                        $default_post_request_form_container.find(
                            ".date_picker"
                        );
                    if (
                        date_picker.val() !== "" &&
                        moment(
                            date_picker.val() + " 23:59",
                            "YYYY/MM/DD HH:mm",
                            timeZone
                        ) <=
                            moment(
                                $default_post_request_form_container
                                    .find(".datetimepicker")
                                    .val(),
                                "YYYY/MM/DD HH:mm A",
                                timeZone
                            ).add(24, "hours")
                    ) {
                        date_picker.val("");
                        return;
                    }

                    date_picker.datetimepicker("destroy");
                    date_picker.datetimepicker({
                        timepicker: false,
                        format: "Y-m-d",
                        minDate: newDate_24_hours.format("YYYY/MM/DD"),
                        validateOnBlur: true,
                        onChangeDateTime: function (dp, $input1) {
                            if (
                                !$default_post_request_form_container
                                    .find(".datetimepicker")
                                    .val()
                            ) {
                                $default_post_request_form_container
                                    .find(".datetimepicker")
                                    .focus();
                            }
                            let closedate = moment(
                                $input1.val() + " 23:59",
                                "YYYY/MM/DD HH:mm",
                                timeZone
                            );
                            let DateOfDepositFromClosingDate = moment(
                                subDays(closedate.toDate(), 5),
                                "YYYY/MM/DD HH:mm",
                                timeZone
                            );

                            if (closedate > maxDateOfDeposit) {
                                swal(
                                    "Financial Institutions do not hold rates more than 5 business days.  You have two options \n\n (1) Change the date of deposit to " +
                                        DateOfDepositFromClosingDate.format(
                                            "YYYY-MM-DD"
                                        ) +
                                        "\n\n (2) Change the offer closure date to " +
                                        maxDateOfDeposit.format("YYYY-MM-DD")
                                );
                                $input1.val("");
                            } else if (
                                moment(
                                    $input1.val() + " 23:59",
                                    "YYYY/MM/DD HH:mm",
                                    timeZone
                                ) >=
                                moment(
                                    $default_post_request_form_container
                                        .find(".datetimepicker")
                                        .val(),
                                    "YYYY/MM/DD HH:mm A",
                                    timeZone
                                ).add(24, "hours")
                            ) {
                                // TODO
                            } else {
                                $input1.val("");
                            }

                            if (
                                closedate.isoWeekday() === 6 ||
                                closedate.isoWeekday() === 7
                            ) {
                                swal(
                                    "Financial Institutions do not accept rates on weekends"
                                );
                                $input.val("");
                            }
                        },
                    });
                } else if ($input.val() !== "") {
                    $input.val("");
                }
            },
        });

    let currentDateTimeWithUserTimezone_24_hours =
        currentDateTimeWithUserTimezone.clone();
    currentDateTimeWithUserTimezone_24_hours =
        currentDateTimeWithUserTimezone_24_hours.add(24, "hours");

    $default_post_request_form_container.find(".date_picker").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
        minDate: currentDateTimeWithUserTimezone_24_hours.format("YYYY/MM/DD"),
        validateOnBlur: true,
        onChangeDateTime: function (dp, $input) {
            if (
                !$default_post_request_form_container
                    .find(".datetimepicker")
                    .val()
            ) {
                $default_post_request_form_container
                    .find(".datetimepicker")
                    .focus();
            }
            if (
                moment($input.val() + " 23:59", "YYYY/MM/DD HH:mm", timeZone) >=
                moment(
                    $default_post_request_form_container
                        .find(".datetimepicker")
                        .val(),
                    "YYYY/MM/DD HH:mm A",
                    timeZone
                ).add(24, "hours")
            ) {
                // TODO
            } else {
                $input.val("");
            }
        },
    });

    $("#div1").hide();
    $("#div2").hide();
    $("#div3").hide();

    map_correct_request_container_index = function () {
        $(".post_request_container_default").each(function (index, element) {
            $(this)
                .find(".request_count")
                .html(index + 1);
        });
    };

    $(document).on("click", ".remove-product-btn", function () {
        let post_request_container_default = $(
            ".post_request_container_default"
        ).length;
        if (post_request_container_default <= 1) {
            $(".remove-product-btn").hide();
            $(this).find(".request_count").html("");
            return;
        }

        swal({
            title: "Are you sure to remove this product?",
            text: "Changes will be lost!",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                $(this).closest(".post_request_container_default").remove();
                map_correct_request_container_index();
                if (post_request_container_default === 2) {
                    $(".remove-product-btn").hide();
                }
            }
        });
    });

    $(document).on("click", ".request-summary-collapse", function () {
        let $request_summary_container = $(this)
            .parent()
            .parent()
            .parent()
            .find(".request-summary-container");
        if ($request_summary_container.is(":visible")) {
            $request_summary_container.hide();
        } else {
            $request_summary_container.show();
        }
    });

    $(document).on("click", ".button_clear", function () {
        swal({
            title: " Clear request?",
            text: "Your changes will be cleared from the request.",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                let $container = $(this).closest(
                    ".post_request_container_default"
                );
                clear_form_elements_values($container, [
                    "productos",
                    "deposit_currency",
                    "term_type",
                    "compound_frequency",
                    "credit_rating",
                    "deposit_insurance",
                ]);
            }
        });
    });

    $(document).on("click", ".add-additional-request-container", function () {
        let unique_key = Date.now();
        if (!$(".post_request_form")[0].checkValidity()) {
            // If the form is invalid, submit it. The form won't actually submit;
            // this will just cause the browser to display the native HTML5 error messages.
            $(".submit_post_request").click();
            return;
        }

        var other_post_request_form_container = $(
            ".post_request_container_default"
        ).length;
        if (other_post_request_form_container >= 9) {
            swal(
                "You have reached the maximum number of request you can post at a go"
            );
            return;
        }

        if (other_post_request_form_container === 1) {
            other_post_request_form_container++;
        }

        function reinitializeTooltips() {
            $('[data-toggle="tooltip"]').tooltip({
                html: true, /// important for tooltip on the next post requests
            });
        }

        var $container_clone =
            $(".post_request_form_container").length > 0
                ? $(".post_request_form_container").html()
                : $(".other_post_request_form_container").html();
        // var post_request_count = (other_post_request_form_container+1);
        $(
            '<div class="card post_request_container_default other_post_request_form_container post_request_count' +
                unique_key +
                '">' +
                $container_clone +
                "</div>"
        ).insertBefore(".add-additional-request-container");

        $('[data-toggle="tooltip"]').tooltip("dispose"); // Dispose existing tooltips to prevent duplicates
        reinitializeTooltips(); ///// important for tooltip on the next post requests

        let $post_request_count_container_ = $(
            ".post_request_count" + unique_key
        );
        var $lockout_period_ =
            $post_request_count_container_.find(".lockout_period");
        var $period_label_ =
            $post_request_count_container_.find(".period-label");

        var lockout_period_container = $post_request_count_container_.find(
            ".lockout-period-container"
        );
        var term_length_container = $post_request_count_container_.find(
            ".term-length-container"
        );
        var term_type = $post_request_count_container_.find(".term_type");
        var term_length = $post_request_count_container_.find(".term_length");
        $post_request_count_container_.find(".rchars").html("100"); // reset instructions limit

        term_length_container.show();
        lockout_period_container.show();
        term_type.attr("required", "required");
        term_length.attr("required", "required");

        term_type.attr("onchange", "compareTermLength()");
        term_length.attr("onchange", "compareTermLength()");

        $lockout_period_.attr("readonly", true);
        $lockout_period_.attr("placeholder", "-");
        $lockout_period_.attr("onchange", "compareTermLength()");

        $lockout_period_.val("");
        $lockout_period_.removeAttr("required");
        $period_label_.html("Lockout period (days)");

        var $appended_date_picker = $(".post_request_count" + unique_key).find(
            ".date_picker"
        );
        var $appended_date_time_picker = $(
            ".post_request_count" + unique_key
        ).find(".datetimepicker");

        $appended_date_time_picker.datetimepicker({
            minDate: currentDate,
            minTime: timeNow,
            step: 30,
            lang: "en",
            format: "Y-m-d H:i A",
            // formatTime:'h:i a',
            validateOnBlur: true,
            onChangeDateTime: function (dp, $input) {
                // if(new Date($input.val()).getDate() === currentDateUserTimezone) {
                if (
                    moment($input.val(), "YYYY/MM/DD HH:mm A").format(
                        "YYYY-MM-DD"
                    ) === currentDateTimeWithUserTimezone.format("YYYY-MM-DD")
                ) {
                    this.setOptions({
                        minTime: timeNow,
                        timeHeightInTimePicker: "10px", //DO NOT REMOVE => fixes the issue with blank times on switching today
                    });
                } else if ($input.val() !== "") {
                    this.setOptions({
                        minTime: "00:00",
                    });
                }

                var currentDateTimeWithUserTimezonePlusNextHours_ = moment(
                    currentDate + " " + timeNow,
                    "YYYY/MM/DD HH:mm",
                    timeZone
                ); // this is logically added here DO NOT REMOVE!
                if (
                    currentDateTimeWithUserTimezonePlusNextHours_ <=
                    moment($input.val(), "YYYY/MM/DD HH:mm", timeZone)
                ) {
                    if (
                        $appended_date_picker.val() !== "" &&
                        moment(
                            $appended_date_picker.val() + " 23:59",
                            "YYYY/MM/DD HH:mm",
                            timeZone
                        ) <=
                            moment(
                                $appended_date_time_picker.val(),
                                "YYYY/MM/DD HH:mm A",
                                timeZone
                            ).add(24, "hours")
                    ) {
                        $appended_date_picker.val("");
                        return;
                    }

                    var newDate_24_hours_ = moment(
                        $input.val(),
                        "YYYY/MM/DD HH:mm",
                        timeZone
                    );

                    let maxDateOfDeposit_ = moment(
                        addWeekdays(newDate_24_hours_.toDate(), 6),
                        "YYYY/MM/DD HH:mm",
                        timeZone
                    );
                    var newDate_24_hour_s = newDate_24_hours_.add(24, "hours");

                    $appended_date_picker.datetimepicker("destroy");
                    $appended_date_picker.datetimepicker({
                        timepicker: false,
                        format: "Y-m-d",
                        minDate: newDate_24_hour_s.format("YYYY/MM/DD"),
                        validateOnBlur: true,
                        onChangeDateTime: function (dp, $input) {
                            if (!$appended_date_time_picker.val()) {
                                $appended_date_time_picker.focus();
                            }
                            let closedate_ = moment(
                                $input.val() + " 23:59",
                                "YYYY/MM/DD HH:mm",
                                timeZone
                            );
                            let DateOfDepositFromClosingDate_ = moment(
                                subDays(closedate_.toDate(), 5),
                                "YYYY/MM/DD HH:mm",
                                timeZone
                            );
                            if (closedate_ > maxDateOfDeposit_) {
                                swal(
                                    "Financial Institutions do not hold rates more than 5 business days.  You have two options \n\n (1) Change the date of deposit to " +
                                        DateOfDepositFromClosingDate_.format(
                                            "YYYY-MM-DD"
                                        ) +
                                        "\n\n (2) Change the offer closure date to " +
                                        maxDateOfDeposit_.format("YYYY-MM-DD")
                                );
                                $input.val("");
                            }
                            if (
                                closedate_.isoWeekday() === 6 ||
                                closedate_.isoWeekday() === 7
                            ) {
                                swal(
                                    "Financial Institutions do not accept rates on weekends"
                                );
                                $input.val("");
                            }
                            if (
                                moment(
                                    $input.val() + " 23:59",
                                    "YYYY/MM/DD HH:mm",
                                    timeZone
                                ) >=
                                moment(
                                    $appended_date_time_picker.val(),
                                    "YYYY/MM/DD HH:mm",
                                    timeZone
                                ).add(24, "hours")
                            ) {
                                // TODO
                            } else {
                                $input.val("");
                            }
                        },
                    });
                } else if ($input.val() !== "") {
                    $input.val("");
                }
            },
        });

        $appended_date_picker.datetimepicker({
            timepicker: false,
            format: "Y-m-d",
            minDate:
                currentDateTimeWithUserTimezone_24_hours.format("YYYY/MM/DD"),
            validateOnBlur: true,
            onChangeDateTime: function (dp, $input) {
                if (!$appended_date_time_picker.val()) {
                    $appended_date_time_picker.focus();
                }
                if (
                    moment(
                        $input.val() + " 23:59",
                        "YYYY/MM/DD HH:mm",
                        timeZone
                    ) >=
                    moment(
                        $appended_date_time_picker.val(),
                        "YYYY/MM/DD HH:mm A",
                        timeZone
                    ).add(24, "hours")
                ) {
                    // TODO
                } else {
                    $input.val("");
                }
            },
        });

        map_correct_request_container_index();
        $(".remove-product-btn").show();
    });
});

function show() {
    if (document.getElementById("div1").style.display == "none") {
        $("#div1").show();
        $("#div2").show();
        $("#div3").show();
    } else {
        $("#div1").hide();
        $("#div2").hide();
        $("#div3").hide();
    }
}

$(document).on("change", ".productos", function () {
    let $post_request_container_default_ = $(this).closest(
        ".post_request_container_default"
    );

    $lockout_period_container = $post_request_container_default_.find(
        ".lockout-period-container"
    );
    $term_length_container = $post_request_container_default_.find(
        ".term-length-container"
    );
    $lockout_period = $post_request_container_default_.find(".lockout_period");
    $period_label = $post_request_container_default_.find(".period-label");
    $term_type = $post_request_container_default_.find(".term_type");
    $term_length = $post_request_container_default_.find(".term_length");

    $term_length_container.show();
    $lockout_period_container.show();
    $term_type.attr("required", "required");
    $term_length.attr("required", "required");
    if ($(this).find(":selected").text().includes("Cashable")) {
        $lockout_period.removeAttr("readonly");
        $lockout_period.attr("placeholder", "Enter Lockout Period");
        $lockout_period.attr("required", "required");
        $period_label.html("Lockout period (days)");
    } else if ($(this).find(":selected").text().includes("Notice deposit")) {
        $lockout_period.removeAttr("readonly");
        $lockout_period.attr("placeholder", "Enter Notice Period");
        $lockout_period.attr("required", "required");
        $period_label.html("Notice period (days)");
    } else if ($(this).find(":selected").text().includes("notice deposits")) {
        $lockout_period.removeAttr("readonly");
        $lockout_period.attr("placeholder", "Enter Notice Period");
        $lockout_period.attr("required", "required");
        $period_label.html("Notice period (days)");
    } else if (
        $(this).find(":selected").text().includes("High Interest Savings")
    ) {
        $term_length_container.hide();
        $lockout_period_container.hide();
        $lockout_period.removeAttr("required");
        $term_type.removeAttr("required");
        $term_length.removeAttr("required");
    } else {
        $lockout_period.attr("readonly", true);
        $lockout_period.attr("placeholder", "-");
        $lockout_period.val("");
        $lockout_period.removeAttr("required");
        $period_label.html("Lockout period (days)");
    }
});

$(document).on("keyup", ".term_length", function () {
    let value = $(this).val();
    if (parseInt(value) < 1) {
        $(this).val("1");
    }
});

function addWeekdays(startDate, numWeekdays) {
    let currentDate = moment(startDate);
    let weekdaysAdded = 0;

    while (weekdaysAdded < numWeekdays) {
        currentDate.add(1, "days");
        if (currentDate.isoWeekday() <= 5) {
            weekdaysAdded++;
        }
    }

    return currentDate;
}

function addDays(date, days) {
    const copy = new Date(Number(date));
    copy.setDate(date.getDate() + days);
    return copy;
}

function subDays(date, days) {
    const copy = new Date(Number(date));
    copy.setDate(date.getDate() - days);
    return copy;
}

function addThousands(e) {
    let off = $(e).val().replace(/,/g, "");
    off = off > 0 ? off : "";
    $(e).val(thousandSeparator(off, ","));
}

/*$(function() {
    $("form.post_request_form").dirty({
        preventLeaving: true,
        leavingMessage: 'Are you sure you want to leave this page? Your request changes won\'t be saved!',
        // onDirty: function(){
        //     $(".message").html("The Form Is Dirty!")
        // }
    });
    // $('form.post_request_form').areYouSure( {message:'Are you sure you want to leave this page? Your request changes won\'t be saved!'} );
});*/

$('a[data-toggle="tooltip"]').tooltip({
    animated: "fade",
    placement: "bottom",
    html: true,
    viewport: "#div1",
});

$(function () {
    $(document).on("submit", ".post_request_form", function (e) {
        let has_error = false;
        let term_types = $(':input[name="term_type[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let term_lengths = $(':input[name="term_length[]"]')
            .map(function (currElement, index) {
                if (term_types[currElement] == "months") {
                    if (parseInt(this.value.replace(/,/g, "")) > 120) {
                        swal("Term length can't be more than 120 months");
                        has_error = true;
                    }
                } else if (term_types[currElement] == "days") {
                    if (parseInt(this.value.replace(/,/g, "")) > 3650) {
                        swal("Term length can't be more than 3,650 days");
                        has_error = true;
                    }
                }

                return this.value; // $(this).val()
            })
            .get();

        let deposit_amounts = $(':input[name="deposit_amount[]"]')
            .map(function () {
                if (parseInt(this.value.replace(/,/g, "")) > 99999999999) {
                    swal("Deposit amount can't be more than 99,999,999,999");
                    has_error = true;
                }
                return this.value; // $(this).val()
            })
            .get();

        let lockout_periods = $(':input[name="lockout_period[]"]')
            .map(function () {
                if (
                    !$(this).attr("readonly") &&
                    parseInt(this.value.replace(/,/g, "")) > 3660
                ) {
                    swal("Lockout period can't be more than 3660");
                    has_error = true;
                }
                return this.value; // $(this).val()
            })
            .get();

        let closing_date_times = $(':input[name="closing_date_time[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let date_of_deposits = $(':input[name="date_of_deposit[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        closing_date_times.forEach(function (index, item) {
            if (moment(item) > moment(date_of_deposits[index])) {
                swal(
                    "Closing date & time can't be greater than date of deposit"
                );
                has_error = true;
            }
        });

        let compound_frequency_s = $(':input[name="compound_frequency[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let requested_rates = $(':input[name="requested_rate[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let special_instructions = $(':input[name="special_instructions[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let product_ids = $(':input[name="product_id[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let deposit_currency_s = $(':input[name="deposit_currency[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let credit_ratings = $(':input[name="credit_rating[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        let deposit_insurances = $(':input[name="deposit_insurance[]"]')
            .map(function () {
                return this.value; // $(this).val()
            })
            .get();

        if (has_error) {
            return false;
        }

        $post_request_container_default = $(".post_request_container_default");
        $the_form.hide();
        $the_invitation_page.show();
        $the_confirm_container.hide();
        $request_submit_btn_container.hide();
        $post_request_container_default.hide();
        $add_additional_request_container.hide();
        table.draw();

        window.onbeforeunload = () => {
            return windowUnloadCallback();
        };

        let confirm_post_request_container = "";
        let count_confirm = 0;

        deposit_amounts.forEach(function (item, index) {
            count_confirm++;

            let product_name = product_ids[index]
                ? product_ids[index].trim()
                : product_ids[index];
            let confirm_lockout_period = "";

            if (
                product_name == "Cashable" ||
                product_name == "Notice deposit"
            ) {
                confirm_lockout_period = lockout_periods[index] + " days";
            } else {
                confirm_lockout_period = "-";
            }

            confirm_post_request_container +=
                '<div class="row row-number-' +
                index +
                '">\n' +
                '        <div class="col-md-12" style="margin-bottom: 10px">\n' +
                '            <span style="color:#2CADF5;font-weight:bold;">REQUEST SUMMARY <span>' +
                count_confirm +
                '</span>  <img src="/image/navigate-arrows-pointing-to-down.png" class="request-summary-collapse" style="margin-left: 0.4%" height="15px"/> </span>\n' +
                "        </div>\n" +
                '        <div class="request-summary-container row col-md-12"><div class="col-md-6">\n' +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Product</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                product_name +
                "</span>\n" +
                "                </div>\n" +
                "            </div>";

            if (
                product_name == "Cashable" ||
                product_name == "Notice deposit"
            ) {
                var confirm_lockout_period_title = "Notice period";
                if (product_name == "Cashable") {
                    confirm_lockout_period_title = "Lockout period";
                }

                confirm_post_request_container +=
                    '<div class="row confirm_lockout_period_container">\n' +
                    '                <div class="row col-md-12"><div class="col-md-5"><p style="font-weight:normal;" class="confirm_lockout_period_title">' +
                    confirm_lockout_period_title +
                    "</p></div>\n" +
                    '                <div class="col-md-7" style="padding-left: 20px">\n' +
                    '                    <span style="font-weight:bold">' +
                    confirm_lockout_period +
                    "</span>\n" +
                    "                </div></div>\n" +
                    "            </div>";
            }

            confirm_post_request_container +=
                '<div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Requested Amount</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                (deposit_currency_s[index] + " " + item) +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n";

            if (product_name != "High Interest Savings") {
                confirm_post_request_container +=
                    '<div class="row confirm_term_length_container">\n' +
                    '                <div class="col-md-5"><p style="font-weight:normal;">Term Length</p></div>\n' +
                    '                <div class="col-md-7">\n' +
                    '                    <span style="font-weight:bold">' +
                    term_lengths[index] +
                    ' <span style="text-transform: capitalize">' +
                    term_types[index] +
                    "</span></span>\n" +
                    "                </div>\n" +
                    "            </div>\n";
            }

            var dt = closing_date_times[index];
            var hrs = dt.substr(11, 2);
            hrs_int = parseInt(hrs);
            if (hrs_int > 12 && dt.length > 16) {
                dt = dt.slice(0, -2);
            }
            confirm_post_request_container +=
                '<div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Closing Date & Time</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                dt +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Ask Rate</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                (requested_rates[index] > 0
                    ? requested_rates[index] + "%"
                    : "") +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                "        </div>\n" +
                '        <div class="col-6">\n' +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Date of deposit (approx)</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                date_of_deposits[index] +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Compounding frequency</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                compound_frequency_s[index] +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Short Term DBRS Rating</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                credit_ratings[index] +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Deposit Insurance</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                deposit_insurances[index] +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                '            <div class="row">\n' +
                '                <div class="col-md-5"><p style="font-weight:normal;">Special Instructions</p></div>\n' +
                '                <div class="col-md-7">\n' +
                '                    <span style="font-weight:bold">' +
                special_instructions[index] +
                "</span>\n" +
                "                </div>\n" +
                "            </div>\n" +
                "        </div>" +
                "</div>\n" +
                "    </div>";

            $(".confirm_post_request_container").html(
                confirm_post_request_container
            );
        });

        return false;
    });
});

$("#update").on("hide.bs.modal", function () {
    if (close_btn_clicked_yes) {
        close_btn_clicked_yes = false;
        return true;
    }
    swal({
        title: "You are about to abandon this invitation.",
        text: "Would you wish to proceed?",
        // icon: "warning",
        buttons: ["No", "Yes"],
    }).then((response) => {
        if (response) {
            $("#myWizard a:first").tab("show");
            $("#InviteNewFi").trigger("reset");
            close_btn_clicked_yes = true;
            $("#update").modal("hide");
        } else {
            close_btn_clicked_yes = false;
        }
    });
    return false;
});

$(".send_invites").click(function () {
    //Show all the rows
    // table.page.len( -1 ).draw();

    let arr = [];

    $(".select_row").each(function () {
        if ($(this).is(":checked")) {
            let id = $(this).attr("id");
            arr.push(id);
        }
    });

    $(".select_row_non_fi").each(function () {
        if ($(this).is(":checked")) {
            let id = $(this).attr("id");
            arr.push(id);
        }
    });

    if (arr.length > 0) {
        $the_form.hide();
        $add_additional_request_container.hide();
        $post_request_container_default.hide();
        $the_invitation_page.hide();
        $the_confirm_container.show();

        let $loader = $("#cover-spin");
        $(".send_invites").attr("disabled", false).html("Please wait..");
        makeApiCall(
            depositor_post_request_invites_url,
            {
                bank_ids: arr,
                action: "confirm-list-banks",
            },
            function (response) {
                if (response.success) {
                    let list_fi = "";
                    let table = "";
                    let fi_rates_list_table = $(".fi-rates-list-table");
                    response.data.forEach(function (item, index) {
                        list_fi +=
                            '<div class="col-md-3">' +
                            (index + 1) +
                            "." +
                            '<span style="font-weight:bold"> ' +
                            item.name +
                            "</span></div>";
                        if (fi_rates_list_table.length > 0) {
                            table +=
                                "<tr>\n" +
                                '                                            <th scope="row">' +
                                (index + 1) +
                                "</th>\n" +
                                "                                            <td>" +
                                item.name +
                                "</td>\n" +
                                '                                            <td><input type="hidden" class="organization_id form-control" name="organization_id" value="' +
                                item.id +
                                '" /> <input type="number" class="form-control rate" name="rate" max="100" </td>\n';
                            if (!is_demo_setup) {
                                table +=
                                    '                                     <td><input type="text" class="amount form-control" onchange="addThousands(this)" name="amount" </td>\n' +
                                    '                                            <td><input type="text" class="gic_number form-control" name="gic_number" </td>\n' +
                                    '                                         <td><input type="date" class="start_date form-control" name="start_date" </td>\n' +
                                    '                                         <td><input type="date" class="maturity_date form-control" name="maturity_date" </td>\n' +
                                    "                                         <td></td>\n";
                            } else {
                                table +=
                                    '                                     <td><input type="text" class="min_amount form-control" onchange="addThousands(this)" name="min_amount" </td>\n' +
                                    '                                         <td><input type="text" class="max_amount form-control" onchange="addThousands(this)" name="max_amount" </td>\n';
                            }
                            table +=
                                "                                         </tr>";
                        }
                    });

                    if (fi_rates_list_table.length > 0) {
                        fi_rates_list_table.html(table);
                    }
                    $(".confirm-container-list-fis").html(list_fi);
                } else {
                    swal("", response.message, "info");
                }
                $(".send_invites").attr("disabled", false).html("Next");
            },
            $loader,
            "GET",
            function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)) {
                    swal("An error occurred, the page will refresh.").then(
                        () => {
                            window.onbeforeunload = null;
                            window.location.reload();
                        }
                    );
                    return;
                }

                swal(
                    "",
                    apiCallServerErrorMessage(
                        xhr,
                        "Unable to submit the selected institutions, try again later"
                    ),
                    "error"
                );
                $(".send_invites").attr("disabled", false).html("Confirm");
            }
        );
    } else {
        swal("", "Please select Institution to send invites");
    }

    window.onbeforeunload = () => windowUnloadCallback();
});

$(document).ready(function () {
    $("#div11").hide();
    $("#div22").hide();

    $(document).on("click", ".cancel-request", function (e) {
        e.preventDefault();
        let $this = $(this);

        swal({
            title: "You are about to cancel this request",
            text: "Would you wish to proceed?",
            // icon: "warning",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                makeApiCall(
                    depositor_post_request_non_partnered_invite,
                    {
                        action: "CANCEL_NON_PARTNERED_INVITES",
                        _token: _token,
                    },
                    function (response) {
                        makeApiCall(
                            depositor_post_request_non_partnered_invite,
                            {
                                action: "CACHE_INVITES",
                                CACHE_INVITES: "all",
                                _token: _token,
                            },
                            function (response) {
                                window.location = "/post-request";
                            },
                            $loader,
                            "POST",
                            function (xhr, textStatus, errorThrown) {
                                if ([419].includes(xhr.status)) {
                                    swal(
                                        "An error occurred, the page will refresh."
                                    ).then(() => {
                                        window.onbeforeunload = null;
                                        window.location.reload();
                                    });
                                }
                            }
                        );
                    },
                    $loader,
                    "POST",
                    function (xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal(
                                "An error occurred, the page will refresh."
                            ).then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                        }
                    }
                );
            }
        });
    });

    $(document).on("click", "#confirm_edit_request", function () {
        $post_request_container_default = $(".post_request_container_default");
        $the_form.show();
        $the_invitation_page.hide();
        $the_confirm_container.hide();
        $request_submit_btn_container.show();
        $post_request_container_default.show();
        $add_additional_request_container.show();
    });
});

function show_more() {
    if (document.getElementById("div11").style.display == "none") {
        $("#div11").show();
        $("#div22").show();
    } else {
        $("#div11").hide();
        $("#div22").hide();
    }
}
