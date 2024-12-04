$(document).ready(function(){

    $(".clear_invites").click(function(){
        $(".select_row").prop("checked", false);
        let $loader = $("#cover-spin");
        makeApiCall(depositor_post_request_non_partnered_invite, {
            'action':'CLEAR_CACHED_INVITES',
            'CACHE_INVITES':'all',
            '_token':_token,
            'credit': $("input[name=credit_rating]").val(),
            'debit': $("input[name=deposit_insurance]").val()
        }, function(response) {
           // remain silent
        }, $loader,"POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)){
                swal("An error occurred, the page will refresh.").then(()=>{
                    window.onbeforeunload = null;
                    window.location.reload();
                });
            }
        });
    });

    $(".select_all").click(function(){
        $(".select_row").prop("checked", true);
        let $loader = $("#cover-spin");
        makeApiCall(depositor_post_request_non_partnered_invite, {
            'action':'CACHE_INVITES',
            'CACHE_INVITES':'all',
            '_token':_token
        }, function(response) {
            // remain silent
        }, $loader,"POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)){
                swal("An error occurred, the page will refresh.").then(()=>{
                    window.onbeforeunload = null;
                    window.location.reload();
                });
            }
        });
    });

    $(document).on("change",".select_row",function() {
        let $this= $(this);
        let $loader = $("#cover-spin");
        if(this.checked) {
            makeApiCall(depositor_post_request_non_partnered_invite, {
                'action':'CACHE_INVITES',
                'CACHE_INVITES':$this.attr('id'),
                '_token':_token
            }, function(response) {
                // remain silent
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                }
            });
        }else{
            makeApiCall(depositor_post_request_non_partnered_invite, {
                'action':'CLEAR_CACHED_INVITES',
                'CACHE_INVITES': $this.attr('id'),
                '_token':_token
            }, function(response) {
                // remain silent
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                }
            });
        }
    });

    $(document).on('submit','#InviteNewFi',function (e) {
        e.preventDefault();
        let $this = $(this);
        let $create_non_partnered_fi = $this.find( "input[name='create_non_partnered_fi']" );
        $create_non_partnered_fi.attr('disabled','disabled').val("Submitting..");
        let $loader = $("#cover-spin");
        makeApiCall(depositor_post_request_non_partnered_invite, $this.serialize(), function(response) {
            if( response.success ){
                close_btn_clicked_yes=true;
                $('#update').modal('hide');
                $this.trigger("reset");
                swal("",response.message,"success").then(function () {
                    table.draw();
                });
            }else{
                swal("",response.message,"info");
            }
            $create_non_partnered_fi.removeAttr('disabled').val("Send");
        }, $loader,"POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)){
                swal("An error occurred, the page will refresh.").then(()=>{
                    window.onbeforeunload = null;
                    window.location.reload();
                });
                return;
            }
            swal("",apiCallServerErrorMessage(xhr,"Unable to invited the institutions, try again later"),"error");
            $create_non_partnered_fi.removeAttr('disabled').val("Send");
        });
    });

    $(document).on("change", ".institution_select",function (){
        $("#institution_name").html($(this).val());
    });

    $('.next').click(function(e){
        e.preventDefault();

        let $this = $("#InviteNewFi");
        let formData = $this.serializeArray();
        formData.push({ name: "stage", value: "1" });

        let $create_non_partnered_fi = $this.find("input[name='create_non_partnered_fi']");
        $create_non_partnered_fi.attr('disabled','disabled').val("Sending..");
        let $loader = $("#cover-spin");
        makeApiCall(depositor_post_request_non_partnered_invite, formData, function(response) {
            if(response.success){
                $('#myWizard a:last').tab('show');
            }else{
                swal("",response.message,"info");
            }
            $create_non_partnered_fi.removeAttr('disabled').val("Next");
        }, $loader,"POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)){
                swal("An error occurred, the page will refresh.").then(()=>{
                    window.onbeforeunload = null;
                    window.location.reload();
                });
                return;
            }

            swal("",apiCallServerErrorMessage(xhr,"Unable to invited the institutions, try again later"),"error");
            $create_non_partnered_fi.removeAttr('disabled').val("Next");
        });

        return false;
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        //update progresss
        let step = $(e.target).data('step');
        let percent = (parseInt(step) / 2) * 100;

        $('.progress-bar').css({width: percent + '%'});
        $('.progress-bar').text("Step " + step + " of 2");
    });

    $('.back').click(function(){
        $('#myWizard a:first').tab('show');
    });

    $("#reset").click(function(){
        $("input").each(function(){
            if( $(this).is('[disabled]')== false)
            {
                $(this).removeAttr("checked");
            }
        });
    });

});