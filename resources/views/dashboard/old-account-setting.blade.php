@extends('dashboard.master')
@section('page_title')
    Account Setting
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
                                    @if($organization->is_non_partnered_fi ==1 && $organization->account_status=='ACTIVE' && $organization->password_changed == 0)
                                        <p style="color: red">Please complete account settings in order to use the Yield Exchange Limited Version</p>
                                    @endif
                                    <form action="#" class="form" method="post" id="account__update_setting_form" enctype="multipart/form-data">
                                        @if( !empty($data['update_for']) )
                                            <input type="hidden" name="update_for" value="{{ $data['update_for'] }}" />
                                            <input type="hidden" name="organization_id" value="{{ $organization->id }}" />
                                        @endif
                                        <input type="hidden" name="userType" value="{{ ucwords(strtolower($organization->type)) }}" />
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

                                        <?php $user = auth()->user(); ?>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Institution </label>
                                                <input <?php echo ($user->is_super_admin && $organization->type=="DEPOSITOR")? ' name="organization_name" ': ' disabled '; ?> type="text" class="form-control" placeholder="Institution" maxlength="50" value="{{ $organization->name }}" required />
                                                <!-- </div> -->
                                            </div><span style="color:red"></span>
                                        </div>

                                    @if($organization->type == "BANK")
                                    <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Institution Type</label>
                                                <select  name="fi_type" class="form-control"   required>
                                                    <option value="">Select Institution Type</option>
                                                    @foreach($fi_types as $row)
                                                        <option {{ $organization->fi_type_id == $row->id ? "selected" : "" }} value="{{ $row->id}}">{{ $row->description }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                    @endif

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Address Line 1:</label>
                                                <input type="text" name="address" class="form-control" maxlength="150" placeholder="Address Line 1" value="{{ $organization->demographicData->address1 }}" required>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Address Line 2:</label>
                                                <input type="text" name="address2" class="form-control" maxlength="150"  placeholder="Address Line 2" value="{{ $organization->demographicData->address2 }}" />
                                                <!-- </div> -->
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <label style="font-weight:bold">City:</label>
                                                <input type="text" name="city" class="form-control" placeholder="City" maxlength="50" value="{{ $organization->demographicData->city }}" required />
                                            </div><span style="color:red">*</span>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Province/Territory:</label>
                                                <select  name="province" class="form-control" required>
                                                    @php
                                                        $provinces = provinces();
                                                    @endphp
                                                    @foreach($provinces as $province)
                                                        <option {{ $organization->demographicData->province == $province ? "selected" : "" }} value="{{ $province }}">{{ $province }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Postal Code:</label>
                                                <input type="text" name="postal" class="form-control myinput" maxlength="10" placeholder="Postal Code" value="{{ $organization->demographicData->postal_code }}" />
                                                <!-- </div> -->
                                            </div>
                                            <span style="color:red">*</span>
                                        </div>
                                     
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Telephone No:</label>
                                                <input type="text" name="telephone" class="form-control" maxlength="10" value="{{ $organization->demographicData->telephone }}" placeholder="Telephone" required>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Website :</label>
                                                <input type="text" name="website" class="form-control" maxlength="50" value="{{ $organization->demographicData->website }}" placeholder="Website" >
                                                <!-- </div> -->
                                            </div><span style="color:red"></span>
                                        </div>
                                        @if($organization->type == "BANK")
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Wholesale Deposit Portfolio*:</label>
                                                <select  name="wholesale_deposit_portfolio" class="form-control" value="" required>
                                                <option value="">Select Wholesale Deposit Portfolio</option>
                                                    @foreach($wholesale_deposits_portfolio as $wdp)
                                                        <option {{ $organization->wholesale_deposit_portfolio_id == $wdp->id ? "selected" : "" }} value="{{ $wdp->id }}">{{ $wdp->band }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                    @endif

                                        @if($organization->type == "DEPOSITOR")
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">NAICS Code 4 digits *:</label>
                                                <select  name="naics_code" class="form-control" required>
                                                        <option value="">Select NAICS Code</option>
                                                    @foreach($naics as $naic)
                                                        <option {{ $organization->naics_code_id == $naic->id ? "selected" : "" }} value="{{ $naic->id}}">{{ $naic->code_description }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                    

                                    <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold">Potential Yearly Deposits:</label>
                                                <select  name="potential_yearly_deposit" class="form-control" required>
                                                <option value="">Select Potential Yearly Deposits</option>
                                                    @foreach($potential_yearly_deposits as $pyd)
                                                        <option {{ $organization->potential_yearly_deposit_id == $pyd->id ? "selected" : "" }} value="{{ $pyd->id }}">{{ $pyd->band }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                    @endif


                                    @if($organization->type == "BANK")
                                    <label style="color:skyblue;font-weight:bold;display: none;">Credit rating and deposit insurance </label>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <div style="display: inline-block;">
                                                    <label style="font-weight:bold">Short term DBRS rating </label>
                                                    <div class="form-group">
                                                        <select class="form-control" name="credit_rating">
                                                            <option value="">--Select--</option>
                                                            @php
                                                                $credit_rating_types=\App\Models\CreditRatingType::where("status","ACTIVE")->orderBy('id','ASC')->get();
                                                            @endphp
                                                            @foreach ( $credit_rating_types as $item)
                                                                <option {{ !empty($organization->depositCreditRating->creditRating) && $organization->depositCreditRating->creditRating->id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->description }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-1" style="display: inline-block;">
                                                    <a data-toggle="tooltip" title="<img src='{{ asset('assets/img/credit_rating.png') }}' style='width:100%' />">
                                                        <i class="fa fa-info-circle" style="font-size: 20px"></i>
                                                    </a>
                                                </div>
                                            </div><span style="color:red">*</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-8">
                                                <!-- <div class="col-lg-12"> -->
                                                <label style="font-weight:bold"> Deposit insurance</label>
                                                <select class="form-control" name="deposit_insurance">
                                                    <option value="">--Select--</option>
                                                    @php
                                                        $deposit_insurances=\App\Models\DepositInsuranceType::where('description','!=','Any Provincial Insurance')->get()->partition(function ($item) {
                                                            return strpos($item->description, 'Any') !== false; // This is move Any deposit insurance at the top
                                                        })->flatten();
                                                    @endphp
                                                    @foreach ( $deposit_insurances as $item)
                                                        <option {{ !empty($organization->depositCreditRating->insuranceRating) && $organization->depositCreditRating->insuranceRating->id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->description }}</option>
                                                    @endforeach
                                                </select>
                                                <!-- </div> -->
                                            </div><span style="color:red">*</span>
                                        </div>
                                        @endif
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
                makeApiCall("{{ route('user.update-account-setting') }}", $this_form.serialize(), function(response) {
                    if(response.success){
                        swal("Account settings update.",response.message).then(function () {
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