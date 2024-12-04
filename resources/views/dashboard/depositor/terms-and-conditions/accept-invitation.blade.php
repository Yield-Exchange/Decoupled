@extends('dashboard.master')
@section('page_title')
    Accept Terms and Conditions
@stop
@section('styles')

@endsection
@section('page_content')
<style>
    .row-custom{
        margin: 30px 0 30px 0;
    }
    .card-buttons-container{
        justify-content: center; align-items: center;
    }
    .card-buttons:first-child{
        margin-right: 2%;
    }
    .card-buttons{
        cursor:pointer;
        min-height: 200px;
        font-size: 1rem;
        font-weight: normal;
        border-radius: 5px;
        width: 15rem;
    }
    .card-buttons:hover{
        -webkit-transform: scale(1.1);
        -moz-transform: scale(1.1);
        -ms-transform: scale(1.1);
        -o-transform: scale(1.1);
        transform: scale(1.1);
        box-shadow: 2px 2px #ccc;;
    }
    .choose-action-title{
        font-weight: 600;
        font-size: 1.3rem;
    }
    .card-text{
        font-weight: 300;
        font-size: 15px;
    }
    .card-img-top{
        height: 100px;
        width: 100px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
    }
</style>
@php

  $link=url('assets/AgreementYieldExchangeDepositorAcctV0823.pdf');
@endphp
<div class="row ">
    <div class="col-xl-12">
        <!-- Support tickets -->
        <div class="card" style="margin: 0;">
            <form action="" method="post" autocomplete="off" id="terms_and_conditions">
                <div class="row">
                    <div class="col-md-12">

{{--                        <h5>Read carefully and click on accept at the end.</h5>--}}
                        <div class="form-group">
                            <div class="termsx">
                                <div class="col-md-12">
                                    <iframe src="{{ $link }}" width="100%" height="1000px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="col-md-12" style=" display: flex;justify-content: center;padding-bottom: 10px;">
                <a class="no-page-exit-alert" href="{{ url('assets/Schedule-A-YEI_Privacy-Policy.pdf') }}" target="_blank">Privacy policy</a> &nbsp;&nbsp;&nbsp;
                <a class="no-page-exit-alert" href="{{ url('assets/Schedule-B-Yield-Exchange-website-terms.pdf') }}" target="_blank">Website terms</a>
            </div>
            <small style="display: inline-block;text-align:center;padding-bottom:10px;">By clicking on the "I accept T&C" button, you represent that you are authorized to enter into the Depositor User Account Terms & Conditions <br>on behalf of the business applying for the Depositor User Account</small>
            <div class="col-md-12" style=" display: flex;justify-content: center;padding-bottom: 20px;">
                <button type="button" class="btn btn-secondary" onclick="return terms(false,this);">I do not accept T&C</button> &nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-success" onclick="return terms(true,this);">I accept T&C</button>
            </div>

{{--            <div id="terms" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">--}}
{{--                <div class="modal-dialog modal-xl">--}}
{{--                    <!-- Modal content-->--}}
{{--                    <div class="modal-content">--}}
{{--                        <div class="modal-header">--}}
{{--                            <h5 class="modal-title"><b>Terms and Conditions</b></h5>--}}
{{--                            <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                        </div>--}}
{{--                        <div class="modal-body">--}}
{{--                            --}}
{{--                        </div>--}}
{{--                        <div class="modal-footer" style="justify-content:center">--}}
{{--                            <button type="button" class="btn btn-secondary btn-lg" onclick="return terms(false,this);">I do not accept T&C</button>--}}
{{--                            <button type="button" class="btn btn-success btn-lg" onclick="return terms(true,this);">I accept T&C</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).on('click','.btn-decline-terms',function () {
        $this = $(this);
        swal({
            title: "",
            text: "You are about to decline the terms and conditions, do you wish to proceed?",
            icon: "warning",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {

                let $loader = $("#cover-spin");
                makeApiCall("{{ route('user.depositor-terms-review') }}", {
                    'action': 'DECLINE_TERMS_AND_CONDITIONS',
                    '_token':"{{ csrf_token() }}"
                }, function (response) {
                    $('#exampleModalCenter').modal('hide');
                    if (response.success) {
                        swal("", response.message, "success").then(function () {
                            window.location.href = "/dashboard";
                        });
                    } else {
                        swal("", response.message, "info");
                    }
                }, $loader, "POST", function (xhr, textStatus, errorThrown) {
                    if ([419].includes(xhr.status)){
                        swal("An error occurred, the page will refresh.").then(()=>{
                            window.onbeforeunload = null;
                            window.location.reload();
                        });
                        return;
                    }

                    $('#exampleModalCenter').modal('hide');
                    swal("", apiCallServerErrorMessage(xhr, "Unable to decline the terms and conditions, try again later", "error"));
                });

            }
        });
    });

    $(document).on('click','a',function (e) {
        if ( !$(this).hasClass("no-page-exit-alert") ) {
            $this = $(this);
            swal({
                title: "Are you sure?",
                text: "You are about to to leave this page.",
                icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    window.location.href=$this.attr('href');
                }
            });

            return false;
        }
    });

    $(document).on('click','.btn-show-terms',function () {
        $('#terms').modal('show'); //{backdrop: 'static', keyboard: false}
    });

    function terms(accept=true,_this) {
        if(accept){
            ajaxTerms(accept,_this);
        }else {
            swal({
                title: "",
                text: "You no longer want to hear from Yield Exchange about deposit opportunities?",
                icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    ajaxTerms(accept,_this);
                } else {
                    $('#terms').modal('hide');
                }
            });
        }
    }

    function ajaxTerms(accept=true,_this) {
        let text = $(_this).html();
        $(_this).html('Please wait');
        let $loader = $("#cover-spin");
        makeApiCall("{{ route('user.depositor-terms-review') }}", {
            'action': accept ? 'ACCEPT_TERMS_AND_CONDITIONS' : 'DECLINE_TERMS_AND_CONDITIONS',
            '_token':"{{ csrf_token() }}"
        }, function (response) {
            $('#exampleModalCenter').modal('hide');
            if (response.success) {
                swal("", response.message, "success").then(function () {
                    $('#step3').show();
                    $('#step1').hide();
                    $('#terms').modal('hide');
                    window.location.href = "/login";
                });
            } else {
                swal("", response.message, "info");
            }
            $(_this).html(text);
        }, $loader, "POST", function (xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)){
                swal("An error occurred, the page will refresh.").then(()=>{
                    window.onbeforeunload = null;
                    window.location.reload();
                });
                return;
            }

            $('#exampleModalCenter').modal('hide');
            swal("", apiCallServerErrorMessage(xhr, "Unable to perform the action, try again later", "error"));
            $(_this).html(text);
        });
    }
</script>
@endsection