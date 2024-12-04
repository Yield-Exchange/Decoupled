@section('scripts')
    <script>
        $the_form = $(".post_request_form_container");
        $request_submit_btn_container = $(".request_submit_btn_container");
        $add_additional_request_container = $(".add-additional-request-container");
        $post_request_container_default = $(".post_request_container_default");
        $the_invitation_page = $(".make-invitation-container");
        $the_confirm_container= $(".confirm-container");
    </script>
    <script src="{{ asset('assets/dashboard/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/moment-timezone.js')  }}"></script>
    @php
        $user = auth()->user();
    @endphp
    <script>
        window.onbeforeunload = () => {
            return windowUnloadCallback();
        };
        let close_btn_clicked_yes=false;
        let depositor_post_request_invites_url = "{{route($user->is_super_admin ? 'admin.post-request-invites' : 'depositor.post-request-invites')}}";
        let depositor_post_request_non_partnered_invite = "{{route($user->is_super_admin ? 'admin.post-request-non-partnered-invite' : 'depositor.post-request-non-partnered-invite')}}";

        let _token="{{ csrf_token() }}";
        let table = $('.custom-data-tables').DataTable({
            lengthMenu: [["All"],["All"]],
            order: [[ 0, "asc" ]],
            scrollX: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: {
                url:depositor_post_request_invites_url,
                data: function (d) {
                    d.credit = $(':input[name="credit_rating[]"]').map(function () {
                        return this.value; // $(this).val()
                    }).get();

                    d.debit = $(':input[name="deposit_insurance[]"]').map(function () {
                        return this.value; // $(this).val()
                    }).get();

                    d.req_id = $("input[name=deposit_request_id]").val();
                }
            },
            columns: [
                { data: 'name' },
                { data: 'province' },
                { data: 'credit_rating' },
                { data: 'deposit_insurance' },
                { data: 'checkbox' }
            ]
        });
    </script>
    <script type="text/javascript">
        let format = 'YYYY/MM/DD HH:mm:ss ZZ';
        let timeZone = "{{ formattedTimezone(auth()->user()->timezone) }}";
        let is_product_high_interest="{{ ($deposit_request && $deposit_request->product && strpos($deposit_request->product->description, 'High Interest Savings') !== false ) ? 1 : 0 }}";
        is_product_high_interest = is_product_high_interest === "1";
        $(document).ready(function () {
            $lockout_period_container = $(".lockout-period-container");
            $term_length_container=$(".term-length-container");
            $lockout_period=$(".lockout_period");
            $term_type=$(".term_type");
            $term_length=$(".term_length");
            if ( is_product_high_interest ){
                $term_length_container.hide();
                $lockout_period_container.hide();
                $lockout_period.removeAttr("required");
                $term_type.removeAttr("required");
                $term_length.removeAttr("required");
                $term_length.removeAttr("min");
                $term_length.removeAttr("max");
            }
            
        });

        function compareTermLength(){
            var term_type= document.getElementsByName('term_type[]');
            var term_length = document.getElementsByName('term_length[]');
            //var lockout_period = document.getElementsByName('lockout_period[]');
            var lockout_period = document.getElementsByClassName('lockout_period');


            for (var i = 0; i < term_length.length; i++) {
                
                if(term_type[i].value != "" && term_length[i].value != "" && lockout_period[i].value !=""){
                   
                    var term_total = term_length[i].value;
                    if(term_type[i].value == 'months'){
                        term_total = term_total * 30;
                    }
                  
                    if(parseInt(lockout_period[i].value) > parseInt(term_total) ){
                        lockout_period[i].value="";
                        swal("","Lockout period can not be greater than the term length").then((response)=>{
                            if(response){
                                
                            }
                        });
                        
                    }

                }
               
            }
            
        }

        function windowUnloadCallback(){
            // makeApiCall(depositor_post_request_non_partnered_invite, {
            //     'action':'CANCEL_NON_PARTNERED_INVITES',
            //     '_token':_token
            // }, function(response) {
            //     makeApiCall(depositor_post_request_non_partnered_invite, {
            //         'action':'CACHE_INVITES',
            //         'CACHE_INVITES':'all',
            //         '_token':_token
            //     }, function(response) {
            //     }, null,"POST", function (xhr, textStatus, errorThrown) {});
            // }, null,"POST", function (xhr, textStatus, errorThrown) {});

            return '';
        }

        let is_demo_setup = false;
        let depositor_demo_setup=false;
        @if(request()->has('demo_setup'))
            depositor_demo_setup = is_demo_setup=true;
        @endif
    </script>
    <script src="{{ asset('assets/dashboard/js/post_request.js?v=latest')  }}"></script>
    <style>
        .closedate_alert .swal-text {
            text-align: left !important;
        }

    </style>
    <script type="text/javascript">

        $(document).ready(function(){

            $(document).on("click",".toggle_advance_button",function () {
                let $container = $(this).closest(".card-body").find(".toggle_advance_button_container");
                if ( $container.is(':hidden') ) {
                    $container.show();
                }else{
                    $container.hide();
                }
            });

            $(document).on('click','a',function (e) {
                if ( !$(this).hasClass("paginate_button") && !$(this).hasClass("no-page-exit-alert")) {
                    e.preventDefault();
                    let $this = $(this);
                    let $loader = $("#cover-spin");
                    swal({
                        title: "Do you want to leave this page?",
                        text: "Changes you made will not be saved.",
                        // icon: "warning",
                        buttons: ["No", "Yes"],
                    }).then((response) => {
                        if (response) {
                            makeApiCall(depositor_post_request_non_partnered_invite, {
                                'action':'CANCEL_NON_PARTNERED_INVITES',
                                '_token':_token
                            }, function(response) {
                                makeApiCall(depositor_post_request_non_partnered_invite, {
                                    'action':'CACHE_INVITES',
                                    'CACHE_INVITES':'all',
                                    '_token':_token
                                }, function(response) {
                                    $loader.show();
                                    window.onbeforeunload = null;
                                    window.location.href=$this.attr('href');
                                }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                                    if ([419].includes(xhr.status)){
                                        swal("An error occurred, the page will refresh.").then(()=>{
                                            window.onbeforeunload = null;
                                            window.location.reload();
                                        });
                                    }
                                });
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
                }
            });
        });
    </script>
    <script src="{{ asset('assets/dashboard/js/invites.js?v=1.0')  }}"></script>
    <script>
        $(document).on("click",".save_the_request",function () {
            let $this_=$(this);
            let $loader = $("#cover-spin");

            let rates_and_deposits = [];
            let arr_ = [];
            if($(".fi-rates-list-table").length > 0) {
                let field_missing = false;
                let rate_errors = false;
                let min_amount_errors = false;
                $(".fi-rates-list-table tr").each(function () {
                    let data = {};
                    data.organization_id = $(this).find(".organization_id").val();

                    data.rate = $(this).find(".rate").val();
                    if(!data.rate){
                        field_missing=true;
                    }

                    if(parseInt(data.rate) > 100){
                        rate_errors = true;
                    }

                    if(parseInt(data.rate) < 0){
                        rate_errors = true;
                    }

                    if(!is_demo_setup) {
                        data.gic_number = $(this).find(".gic_number").val();
                        if (!data.gic_number) {
                            field_missing = true;
                        }

                        data.start_date = $(this).find(".start_date").val();
                        if (!data.start_date) {
                            field_missing = true;
                        }

                        data.maturity_date = $(this).find(".maturity_date").val();
                        if (!data.maturity_date) {
                            field_missing = true;
                        }

                        data.amount = $(this).find(".amount").val();
                        if(!data.amount){
                            field_missing=true;
                        }

                        data.min_amount = data.amount;
                        data.max_amount = data.amount;

                    }else{
                        data.min_amount = $(this).find(".min_amount").val();
                        if(!data.min_amount){
                            field_missing=true;
                        }

                        data.max_amount = $(this).find(".max_amount").val();
                        if(!data.max_amount){
                            field_missing=true;
                        }

                        if(!field_missing) {
                            data.min_amount=data.min_amount.replace(/,/g,"");
                            data.max_amount=data.max_amount.replace(/,/g,"");
                            if (parseFloat(data.min_amount) > parseFloat(data.max_amount)) {
                                min_amount_errors = true;
                            }
                        }
                    }
                    rates_and_deposits.push(data);
                });

                if(field_missing){
                    swal("Please fill in all rates & deposits details");
                    return;
                }

                if(rate_errors){
                    swal("Invalid Rate");
                    return;
                }

                if(min_amount_errors){
                    swal("Min Amount should not be greater than Max Amount");
                    return;
                }
            }

            $(".select_row").each(function(){
                if($(this).is(":checked")){
                    let id = $(this).attr("id");
                    arr_.push(id);
                }
            });

            $(".select_row_non_fi").each(function () {
                if($(this).is(":checked")){
                    let id = $(this).attr("id");
                    arr_.push(id);
                }
            });

            if(arr_.length === 0){
                swal("Please select Institution to send invites");
                return;
            }

            let $this_form = $(".post_request_form");
            let formData = $this_form.serializeArray();
            formData.push({ name: "invited", value: arr_ });
            formData.push({ name: "organization_id", value: "@php echo $organization->id @endphp" });
            formData.push({ name: "rates_and_deposits", value: JSON.stringify(rates_and_deposits) });
            if(depositor_demo_setup) {
                formData.push({name: "depositor_demo_setup", value: depositor_demo_setup});
            }

            // let deposit_amount = $("input[name=deposit_amount]").val();
            // formData.push({ name: "deposit_amount", value: parseFloat(deposit_amount.replace(/,/g, '')) });

            $this_.attr('disabled', true).html('Please wait');
            makeApiCall("{{ !$user->is_super_admin ? route('depositor.post-request-submit') : route('admin.post-request-submit') }}", formData, function(response) {
                if(response.success){
                    swal(response.message_title,response.message).then(function () {
                        $loader.show();
                        window.onbeforeunload = null;
                        @if($user->is_super_admin)
                            window.location.reload();
                        @elseif(request()->has('demo_setup'))
                            window.location.href = "{{url('/review-offers')}}";
                        @else
                             window.location.href="/review-offers";
                        @endif
                    });
                }else{
                    swal("",response.message,"info");
                }
                $this_.attr('disabled', false).html('Submit');
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                swal("",apiCallServerErrorMessage(xhr,"Unable to submit the request, try again later","error"));
                $this_.attr('disabled', false).html('Submit');
            });
        });

    </script>
@endsection