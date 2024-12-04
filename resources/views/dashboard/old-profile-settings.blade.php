@extends('dashboard.master')
@section('page_title')
    Profile Setting
@stop
@section('styles')
    <link rel="stylesheet" href="{{ asset('/assets/css/croppie.css') }}" />
    <style>
        .tooltip-inner{
            background: transparent!important;
        }
        .tooltip-inner > img{
            width: 400px!important;
        }
        #hover-content {
            display:none;
        }
        #parent:hover #hover-content {
            display:block;
        }
        #old_password_error{
            display: none;
        }
        #new_password_error{
            display: none;
        }
        .croppie-container{
            height: auto!important;
        }

        .swal-modal .swal-text {
            text-align: center;
        }
        .swal-footer { text-align: center; }

    </style>
@endsection
@section('page_content')

 @php
    $user_data = auth()->user();
@endphp
    <!-- Main charts -->
    <div class="row">
        <div class="col-xl-12">
            <!-- Traffic sources -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">

                                <fieldset class="col-md-10">
                                    <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Profile Settings</legend>

                                    <form action="#" class="form" method="post" id="account__update_setting_form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label style="font-weight:bold">Password Settings</label>
                                                <br/>
                                                <button class="btn btn-primary mmy_btn reset_password_request_btn" style="margin-bottom: 5px">
                                                Reset Password
                                                </button>
                                        </div>
                                    </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Assigned Role</label>
                                                <input disabled type="text" class="form-control"  value="{{  ucwords(strtolower($user_data->role_name))  }}" required />
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">First Name</label>
                                                <input name="firstname" type="text" class="form-control" placeholder="First name" maxlength="25" value="{{ $user->firstname }}" required />
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Last Name</label>
                                                <input name="lastname" type="text" class="form-control" placeholder="Last name" maxlength="25" value="{{ $user->lastname }}" required />
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label style="font-weight:bold">Email:</label>
                                                <input type="text" name="email" class="form-control myinput" maxlength="50" value="{{ $user->email }}" placeholder="Email" readonly>
                                            </div>
                                            <span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label style="font-weight:bold">Phone:</label>
                                                <input type="text" name="phone" class="form-control myinput" maxlength="10" value="{{ $user->demographicData->phone }}" placeholder="Telephone" >
                                            </div>
                                            <span style="color:red"></span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label style="font-weight:bold">Job Title:</label>
                                                <input type="text" name="job_title" class="form-control myinput" maxlength="50" value="{{ $user->demographicData->job_title }}" placeholder="Job Title" >
                                            </div>
                                            <span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Department:</label>
                                                <input type="text" name="department" class="form-control myinput" maxlength="50" value="{{ $user->demographicData->department }}" placeholder="Department" >
                                                <!-- </div> -->
                                            </div>
                                            <span style="color:red"></span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">City:</label>
                                                <input type="text" name="city" class="form-control myinput" maxlength="50" value="{{ $user->demographicData->city }}" placeholder="City" >
                                                <!-- </div> -->
                                            </div>
                                            <span style="color:red"></span>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Province:</label>
                                                <select  name="location" class="form-control" required>
                                                    @php
                                                        $provinces = provinces();
                                                    @endphp
                                                    @foreach($provinces as $province)
                                                        <option {{ $user->demographicData->province == $province ? "selected" : "" }} value="{{ $province }}">{{ $province }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Timezone:<span style="color:red">*</span></label>
                                                <select  name="timezone" class="form-control" required>
                                                    <option value="">Select</option>
                                                    @php
                                                        $timezones = timezonesList();
                                                    @endphp
                                                    @foreach($timezones as $key => $timezone)
                                                        <option {{ $user->demographicData->timezone == $key ? "selected" : "" }} value="{{ $key }}">{{ $timezone }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div>
                                            <div class="col-lg-4">
                                                <br/>
                                                <br/>
                                                <span style="font-weight:bold;font-size:18px;border:1px solid #333; padding:6px;">
                                                   {{ \Carbon\Carbon::now()->tz(formattedTimezone($user->demographicData->timezone))->format(\App\Constants::DATE_TIME_FORMAT) }}
                                                </span>
                                            </div>

                                        </div>

                                        <div class="col-lg-8">
                                            <button type="submit" class="btn btn-lg btn-primary account__update_setting_btn">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </fieldset>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /support tickets -->
        </div>
    </div>

    <div id="uploadimageModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload & Crop Image</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="display: block;">
                        <div class="col-md-12">
                            <div class="col-md-8 text-center">
                                <div id="image_demo" class="col-md-12"></div>
                            </div>
                            <div class="col-md-6">
                                <br/>
                                <br/>
                                <br/>
                                <button class="btn btn-success crop_image">Crop & Upload Image</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /main charts -->
@endsection
@section('scripts')
    <script src="{{ asset('/assets/js/croppie.js') }}"></script>
    <script>
        let _inverse_big ='<span class="i-initial-inverse-big"><span>{{  !empty($user->name[0]) ? $user->name[0] : 'Y' }}</span></span>';
        /*$(function() {
            $(".form").dirty({
                preventLeaving: true,
                leavingMessage: 'Are you sure you want to leave this page? Your request changes won\'t be saved!'
            });
        });*/
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
                            $loader.show();
                            window.onbeforeunload = null;
                            window.location.href=$this.attr('href');
                        
                        }
                    });
                }
            });
        
        $('a[data-toggle="tooltip"]').tooltip({
            animated: 'fade',
            placement: 'bottom',
            html: true,
            viewport: '#myform3'
        });
        $(document).on("click",".btn-remove-profile-image",function (e) {
            e.preventDefault();

            swal({
                title: "Are you sure?",
                text: "Are you sure to proceed and remove your profile picture?",
                icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    $(".profile_url").val("");
                    $("div.profile-image").html(_inverse_big);
                    $(this).hide();
                    window.onbeforeunload = () => '';
                }
            });

        });
    </script>
    <script>
        $(document).ready(function(){
            $(document).on("submit","#account__update_setting_form",function () {
                //account__update_setting_btn
                let $this_form = $("#account__update_setting_form");
                let $this_ = $(".account__update_setting_btn");
                let $loader = $("#cover-spin");

                $this_.attr('disabled', true).html('Please wait');
                makeApiCall("{{ !is_admin_route(request()) ? route('user.update-profile-setting') : route('admin.update-profile-setting') }}", $this_form.serialize(), function(response) {
                    if(response.success){
                        swal("Profile settings update.",response.message).then(function () {
                            window.onbeforeunload = null;
                            window.location.href="{{ request()->has('fromPage') ? request()->fromPage : (!is_admin_route(request()) ? '/profile-setting' : '/yie-admin/profile-setting') }}";
                        });
                    }else{
                        swal("",response.message,"info");
                    }
                    $this_.attr('disabled', false).html('Save');
                }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                    if ([419].includes(xhr.status)){
                        swal("An error occurred, the page will refresh.").then(()=>{
                            window.onbeforeunload = null;
                            window.location.reload();
                        });
                        return;
                    }

                    swal("",apiCallServerErrorMessage(xhr,"Unable to update profile settings, try again later","error"));
                    $this_.attr('disabled', false).html('Save');
                });

                return false;
            });

            let $image_crop = $('#image_demo').croppie({
                enableExif: true,
                viewport: {
                    width:400,
                    height:400,
                    type:'square' //circle
                },
                boundary:{
                    width:500,
                    height:500
                }
            });

            $('.attach_image').on('change', function(){
                let reader = new FileReader();
                var file = this.files[0]; // Get your file here
                var fileTypes = ['jpg', 'jpeg', 'png'];
                var fileExt = file.type.split('/')[1]; // Get the file extension
                if (fileTypes.indexOf(fileExt) === -1) {
                    swal("","Only png,jpg file formats are allowed","info");
                    $('.attach_image').val('');
                    return;
                }
                reader.onload = function (event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadimageModal').modal('show');
            });

            $('.crop_image').click(function(event){
                $image_crop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(response){
                    if (!response){
                        return;
                    }

                    window.onbeforeunload = () => '';
                    $('#uploadimageModal').modal('hide');
                    $(".new_profile_image").val(response);

                })
            });

        });
    </script>
    <script>
        $('.reset_password_request_btn').on('click', function (e) {
            e.preventDefault();

            $(".reset_password_request_btn").attr('disabled', true).html('Please wait...');
            let $loader = $("#cover-spin");

            makeApiCall("{{ !is_admin_route(request()) ? url('reset-password') : url('yie-admin/reset-password') }}", {email:"{{ $user->email }}",_token:"{{ csrf_token() }}",fromLoggedInUser:1,loginAs:"{{ !is_admin_route(request()) ? ucwords(strtolower($user->organization->type)) : 'Admin' }}" }, function(response) {
                if(response.success) {
                    swal("",response.message,"success");
                }else{
                    swal("",response.message,"info")
                }
                $(".reset_password_request_btn").attr('disabled', false).html('Reset password');
            }, $loader,"POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                swal("",apiCallServerErrorMessage(xhr,"Unable to reset password, try again later"),"info");
                $(".reset_password_request_btn").attr('disabled', false).html('Reset password');
            });

        });
    </script>
@endsection