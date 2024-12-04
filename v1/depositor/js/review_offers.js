$(window).on('load', function() {
    let table = $('.custom-data-tables').DataTable({
        // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [[ 0, "desc" ]],
        "ordering": false,
        // Load data from an Ajax source
        "ajax": {
            "url": "ajax/review_offers_api.php",
            "type": "POST",
            "data": {
                'ACTION':'FETCH_REVIEW_OFFERS',
                'req_id': req_id,
                '_token': token
            },
            dataFilter:function(inData){
                // what is being sent back from the server (if no error)
                // console.log(inData);
                return inData;
            },
            error:function(err, status){
                // what error is seen(it could be either server side or client side.
                // console.log(err);
            },
        },
        //Set column definition initialisation properties
        "columnDefs": [{
            "targets": [0],
            "orderable": false
        }],
        "scrollX": true,
        "pageLength": 50,
    });
    let labels = ['days', 'hours', 'minutes', 'seconds'],
        template = _.template($('#countdown-timer-template').html()),
        currDate = '00:00:00:00',
        nextDate = '00:00:00:00',
        currDate1 = '00:00:00:00',
        nextDate1 = '00:00:00:00',
        parser = /([0-9]{2})/gi,
        $selectOffersIn = $('#select-offers-in'),
        $requestExpiresIn = $('#request-expires-in');

    // Parse countdown string to an object
    function strfobj(str) {
        let parsed = str.match(parser),
            obj = {};
        labels.forEach(function(label, i) {
            obj[label] = parsed[i]
        });
        return obj;
    }
    // Return the time components that diffs
    function diff(obj1, obj2) {
        let diff = [];
        labels.forEach(function(key) {
            if (obj1[key] !== obj2[key]) {
                diff.push(key);
            }
        });
        return diff;
    }
    // Build the layout
    let initData = strfobj(currDate);
    labels.forEach(function(label, i) {
        $selectOffersIn.append(template({
            curr: initData[label],
            next: initData[label],
            label: label
        }));
    });

    // Build the layout
    let initData1 = strfobj(currDate1);
    labels.forEach(function(label, i) {
        $requestExpiresIn.append(template({
            curr: initData1[label],
            next: initData1[label],
            label: label
        }));
    });

    // Starts the countdown for request expiry
    $requestExpiresIn.countdown(requestExpiry, {defer: false})
        .on('finish.countdown', function (event){
            if (!is_already_expired){
                table.ajax.reload();
            }
            showConfirmButton();
            $(".request-expiry-container").find(".main-timer-container").find(".count").css('background','#ccc');
        })
        .on('update.countdown', function(event) {
            if ( parseInt(event.strftime("%D")) < 1 ){
                $(".request-expiry-container").find(".main-timer-container").find(".count").css('background','#EA3C53');
            }
            let newDate1 = event.strftime('%D:%H:%M:%S'),
                data;
            if (newDate1 !== nextDate1) {
                currDate1 = nextDate1;
                nextDate1 = newDate1;
                // Setup the data
                data = {
                    'curr': strfobj(currDate1),
                    'next': strfobj(nextDate1)
                };
                // Apply the new values to each node that changed
                diff(data.curr, data.next).forEach(function(label) {
                    let selector = '.%s'.replace(/%s/, label),
                        $node = $requestExpiresIn.find(selector);
                    // Update the node
                    $node.removeClass('flip');
                    $node.find('.curr').text(data.curr[label]);
                    $node.find('.next').text(data.next[label]);
                    // Wait for a repaint to then flip
                    _.delay(function($node) {
                        $node.addClass('flip');
                    }, 50, $node);
                });
            }
        }).countdown('start');

    // Starts the countdown for request offer closure date
    $selectOffersIn.countdown(closingDate, {defer: false})
        .on('finish.countdown', function (event) {
            if (!is_already_open){
                table.ajax.reload();
            }
            showConfirmButton();
            setTimeout(function(){
                if (!is_already_open){
                    table.ajax.reload();
                }
            }, 5000); // reload the table again after 5 seconds in case the cronjob was working
            $(".select-offer-container").find(".main-timer-container").find(".count").css('background','#ccc');
        }).on('update.countdown', function(event) {

        if ( parseInt(event.strftime("%D")) < 1 && parseInt(event.strftime("%H")) < 1 ){
            $(".select-offer-container").find(".main-timer-container").find(".count").css('background','#29AB87');
        }

        let newDate = event.strftime('%D:%H:%M:%S'),
            data;
        if (newDate !== nextDate) {
            currDate = nextDate;
            nextDate = newDate;
            // Setup the data
            data = {
                'curr': strfobj(currDate),
                'next': strfobj(nextDate)
            };
            // Apply the new values to each node that changed
            diff(data.curr, data.next).forEach(function(label) {
                let selector = '.%s'.replace(/%s/, label),
                    $node = $selectOffersIn.find(selector);
                // Update the node
                $node.removeClass('flip');
                $node.find('.curr').text(data.curr[label]);
                $node.find('.next').text(data.next[label]);
                // Wait for a repaint to then flip
                _.delay(function($node) {
                    $node.addClass('flip');
                }, 50, $node);
            });
        }
    });

});

$(document).ready(function(){

    let timer = null;
    $(document).on('keydown','.offered_amount',function(){
        clearTimeout(timer);
        timer = setTimeout(function () {
            updateOffered();
        }, 1100);
    });

    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('click','a',function (e) {
        if ( !$("#confirmButton").attr("disabled") ) {

            if ( !$(this).hasClass("paginate_button") ) {
                e.preventDefault();
                if (confirm("Are you sure you want to leave this page?")) {
                    window.location.href = $(this).attr("href");
                }
            }
        }
    });
});

function addThousands(e){
    let off = $(e).val().replace(/,/g, "");
    off = off > 0 ? off : "";
    $(e).val(thousandSeparator(off, ','));
}

function updateOffered() {

    let total_off=0, off=0, real_off=0, rate=0, sm_rate=0, max_amount=0, min_amount=0;
    var needError=false;

    $('#offer-table > tbody  > tr').each(function(index, tr) {

        off = $(tr).find("[name='offered_amount']").val().replace(/,/g, "");
        real_off = $(tr).find("[name='offered_amount']").val().replace(/,/g, "");

        var $_error = $(tr).find("td:last-child > ._error");
        $(tr).find("td:last").css('color','grey');
        $(tr).find("[name='offered_amount']").css('color','grey');
        $_error.html("");
        if (real_off) {

            max_amount = $(tr).find("[name='max_amount']").val().replace(/,/g, "");
            min_amount = $(tr).find("[name='min_amount']").val().replace(/,/g, "");
            if (parseFloat(real_off) > parseFloat(max_amount)) {
                off = 0;
                $_error.html("Should not be more than maximum amount");
                // swal("Awarded Amount should not be more than maximum amount");
                // $(tr).find("[name='offered_amount']").val("").focus();
                needError=true;
            }

            if (parseFloat(real_off) < parseFloat(min_amount)) {
                off = 0;
                $_error.html("Should not be less than minimum amount");
                // swal("Awarded Amount should not be less than minimum amount");
                // $(tr).find("[name='offered_amount']").val("").focus();
                needError=true;
            }

            if (needError){
                $(tr).find("td:last").css('color','red');
                $(tr).find("[name='offered_amount']").css('color','red');
            }else{
                $(tr).find("td:last").css('color','grey');
                $(tr).find("[name='offered_amount']").css('color','grey');
                $_error.html("");
            }

            rate = $(tr).find("[name='rate']").val().replace(/,/g, "");
            total_off = total_off + parseFloat(off);
            sm_rate += (parseFloat(off) * parseFloat(rate) / 100);
        }

    });
    let av_rate = (sm_rate/total_off) * 100;

    $("#total_offered").html( currency +" "+ thousandSeparator( (isNaN(total_off) ? 0 : total_off) ,","));
    $("#interest_rate").html( (isNaN(av_rate) ? 0 : av_rate.toFixed(3) )+"%");

    if ( total_off > 0 && !needError ){
        $("#confirmButton").removeAttr("disabled");
    }else{
        $("#confirmButton").attr("disabled","disabled");
    }
}

function selectOffer(_this){
    let last_td = $(_this).closest('tr').find('td:last-child');
    if ( $(_this).attr("data-selected") === "0" ) {
        let rm=false;
        $('#offer-table > tbody  > tr').each(function(index, tr) {
            if ( $(tr).find("[name='offered_amount']").val() === "" && ( !$(tr).find("[name='offered_amount']").attr('disabled')) && (last_td.find("input").data('rec-id') !== $(tr).find('td:last-child').find("input").data('rec-id')) ){
                swal({
                    title: "Offered amount",
                    text: "There is no offered amount entered. Do you want to remove the selection?",
                    icon: "warning",
                    buttons: ["No", "Yes"],
                    dangerMode: true
                }).then((yes) => {
                    if (yes) {
                        $(tr).find('.btn-danger').attr("data-selected", "0").text("Select").removeClass("btn-danger").addClass("btn-outline-secondary-custom");
                        $(tr).find("input[name='offered_amount']").attr("disabled","disabled").val("");

                        $(_this).attr("data-selected", "1").text("Remove").removeClass("btn-outline-secondary-custom").addClass("btn-danger");
                        $(last_td).find("input[name='offered_amount']").removeAttr("disabled").focus();
                    }
                });
                rm=true;
                return false;
            }
        });

        if (!rm) {
            $(_this).attr("data-selected", "1").text("Remove").removeClass("btn-outline-secondary-custom").addClass("btn-danger");
            $(last_td).find("input[name='offered_amount']").removeAttr("disabled").focus();
            updateOffered();
        }

    }else{
        swal({
            title: "Remove selected?",
            text: "Do you want to remove the selected amount?",
            icon: "warning",
            buttons: ["No", "Yes"],
            dangerMode: true
        }).then((yes) => {
            if (yes) {
                $(_this).attr("data-selected", "0").text("Select").removeClass("btn-danger").addClass("btn-outline-secondary-custom");
                $(last_td).find("input[name='offered_amount']").attr("disabled","disabled").val("");
                updateOffered();
                $(last_td).css('color','grey');
                $(last_td).find("[name='offered_amount']").css('color','grey');
                $(_this).closest('tr').find("td:last-child > ._error").html("");
            }
        });
    }
}

function selectButton () {
    let total_off=0;
    let off=0;

    let offers = [];
    $('#offer-table > tbody  > tr').each(function(index, tr) {

        let offered_amount_input = $(tr).find("[name='offered_amount']");
        if ( !$(offered_amount_input).attr("disabled") && $(offered_amount_input).val() === "" ){
            $(offered_amount_input).focus();
            return;
        }

        off = $(tr).find("[name='offered_amount']").val().replace(/,/g, "");
        if (off) {
            total_off = total_off + parseFloat(off);
            offers.push({
                'id': $(tr).find("[name='id']").val(),
                'offered_amount': $(tr).find("[name='offered_amount']").val()
            });
        }

    });

    $("[name='submitted_offers_data']").val(JSON.stringify(offers));
    // if (parseFloat(total_off) === deposit_amount){
        if (parseFloat(total_off) <= deposit_amount){
            $("#confirmButton").attr('disabled',true);
            $("#form_offers").submit();
        }else{
            swal("Total requested amount must not exceed original requested amount.");
        }
    // }else{
    //     swal("Total requested amount must equal original requested amount.");
    // }
}

function showConfirmButton(){
    $.post("ajax/review_offers_api.php",{
        ACTION: "SHOW_CONFIRM_BUTTON",
        req_id: req_id
    },
    function(data, status){
        if (data === "0"){
            $("#confirmButton").hide();
        }else{
            $("#confirmButton").show();
        }
    });
}