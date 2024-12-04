@extends('dashboard.master')
@section('page_title')
    Organization Setting
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
    </style>
@endsection
@section('page_content')
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
                                    <legend class="font-weight-semibold"><i class="icon-reading mr-2"></i> Organization Settings</legend>
                                    <form action="#" class="form" method="post" id="account__update_setting_form" enctype="multipart/form-data">
                                        <input type="hidden" name="new_profile_image" class="new_profile_image" value="" />
                                        <input type="hidden" name="profile_url" class="profile_url" value="{{ $organization->logo }}" />
                                        @csrf
                                        <div class="form-group row profile-image">
                                            @if (!empty($organization->logo))
                                                <img style="height:100px;" src="{{ url('image/'.$organization->logo) }}" />
                                            @else
                                                <span class="i-initial-inverse-big"><span>{{ !empty($organization->name[0]) ? $organization->name[0] : 'Y' }}</span></span>
                                            @endif
                                        </div>
                                        @if (!empty($organization->logo))
                                            <div class="form-group row">
                                                <div class="col-lg-5">
                                                    <a href="javascript:void()" class="btn btn-warning btn-sm btn-remove-profile-image">Remove Profile Image</a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label style="font-weight:bold"><div id="parent"> Upload Image:
                                                        <div style="height: 20px;
                                                          width: 20px;
                                                          color:#1DA1F2;
                                                          margin-left:8px;
                                                          display: inline-block;">
                                                            <span style="margin-left:6.5px;"> <i class="fa fa-info-circle" style="font-size: 20px"></i></span></div>
                                                        <div id="hover-content" style="min-height:30px;">
                                                            Max. Image size: 500 x 500 ; Allowable image types: png, jpg
                                                        </div>
                                                    </div></label>
                                                <input type="file" name="file" class="form-control attach_image" />
                                            </div><span style="color:red">*</span>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Name</label>
                                                <input disabled type="text" class="form-control" placeholder="Institution" value="{{ $organization->name }}" required />
                                                <!-- </div> -->
                                            </div><span style="color:red"></span>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Telephone No:</label>
                                                <input type="text" name="telephone" class="form-control" value="{{ $organization->demographicData->telephone }}" placeholder="Telephone" >
                                                <!-- </div> -->
                                            </div>{{--<span style="color:red">*</span>--}}
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
        let _inverse_big ='<span class="i-initial-inverse-big"><span>{{  !empty($organization->name[0]) ? $organization->name[0] : 'Y' }}</span></span>';
        $(function() {
            $(".form").dirty({
                preventLeaving: true,
                leavingMessage: 'Are you sure you want to leave this page? Your request changes won\'t be saved!'
            });
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
                makeApiCall("{{ route('user.update-account-setting') }}", $this_form.serialize(), function(response) {
                    if(response.success){
                        swal("",response.message,"success").then(function () {
                            window.onbeforeunload = null;
                            window.location.href="{{ request()->has('fromPage') ? request()->fromPage : '/account-setting' }}";
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

                    swal("",apiCallServerErrorMessage(xhr,"Unable to update account settings, try again later","error"));
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
@endsection