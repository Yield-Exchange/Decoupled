$(document).ready(function () {
    table = $('.custom-data-tables').DataTable({
        "order": [[ 0, "asc" ]],
        "scrollX": true,
        "pageLength": 100
    });
    $('.dataTables_length').addClass('bs-select');
});

$(document).ready(function(){

    $(document).on('click','a',function (e) {
        if ( !$(this).hasClass("paginate_button") && !$(this).hasClass("no-page-exit-alert")) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "Are you sure?",
                text: "Are you sure leave this page?, your request changes won't be saved!",
                icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    $.post("../includes/ajax/general_requests.php",{
                            'action':'CANCEL_NON_PARTNERED_INVITES'
                        },
                        function(data, status){
                            data=JSON.parse(data);
                            if (data.success){
                                window.location.href = "logic?cancel_request_values=" + $this.attr("href");
                                return;
                            }

                            if(data?.reload){
                                swal({title:'', text:data.message, type:data.success? 'success': 'error',timer: 5000}).then((value) => { window.location.reload() });
                                return;
                            }
                            swal(data.message);
                        });
                }
            });
        }
    });

    $(".clear_invites").click(function(){
        $(".select_row").prop("checked", false);
        $.post("../includes/ajax/general_requests.php",{'CACHE_INVITES':'all','action':'CLEAR_CACHED_INVITES'},
            function(data, status){
            });
    });

    $(".select_all").click(function(){
        $(".select_row").prop("checked", true);
        $.post("../includes/ajax/general_requests.php",{'CACHE_INVITES':'all','action':'CACHE_INVITES'},
            function(data, status){
            });
    });

    $(".select_row").change(function() {
        console.log("called");
        $this= $(this);
        if(this.checked) {
            $.post("../includes/ajax/general_requests.php",{'CACHE_INVITES':$this.attr('id'),'action':'CACHE_INVITES'},
                function(data, status){
                });
        }else{
            $.post("../includes/ajax/general_requests.php",{'CACHE_INVITES':$this.attr('id'),'action':'CLEAR_CACHED_INVITES'},
                function(data, status){
                });
        }
    });

    $(document).on('submit','#InviteNewFi',function (e) {
        e.preventDefault();
        let $this = $(this);
        $this.find( "input[name='create_non_partnered_fi']" ).attr('disabled','disabled').val("Submitting..");
        $.post("../includes/ajax/general_requests.php",$this.serialize(),
            function(data, status){
                data = JSON.parse(data);
                $this.find( "input[name='create_non_partnered_fi']" ).removeAttr('disabled').val("NEXT");
                if (data.success || data?.reload){
                    swal({title:'', text:data.message, type:data.success? 'success': 'error',timer: 5000}).then((value) => { window.location.reload() });
                    return;
                }
                swal(data.message);
            });
    });

    // $(document).on("keyup","input[name='account_manager_name']",function (){
    //     $("#account_manager_name_footer").html($(this).val());
    // });

    $(document).on("change", ".institution_select",function (){
        $("#institution_name").html($(this).val());
    });

    $('.next').click(function(e){
        e.preventDefault();

        let $this = $("#InviteNewFi");
        let formData = $this.serializeArray();
        formData.push({ name: "stage", value: "1" });

        $this.find( "input[name='next_step_non_partnered_fi']" ).attr('disabled','disabled').val("Sending..");
        $.post("../includes/ajax/general_requests.php",formData,
            function(data, status){
                data=JSON.parse(data);
                $this.find( "input[name='next_step_non_partnered_fi']" ).removeAttr('disabled').val("Send");
                if (data.success){
                    $('#myWizard a:last').tab('show');
                    return;
                }

                if(data?.reload){
                    swal({title:'', text:data.message, type:data.success? 'success': 'error',timer: 5000}).then((value) => { window.location.reload() });
                    return;
                }
                swal(data.message);
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

});