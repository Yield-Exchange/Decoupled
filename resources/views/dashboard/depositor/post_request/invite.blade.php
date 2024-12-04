<style>
    input[type=checkbox] {
        /* Double-sized Checkboxes */
        padding: 10px;
    }

    .fade:not(.show) {
        opacity: unset;
    }

    #InviteNewFi {
        margin-top: 20px;
    }

    #InviteNewFi input,
    #InviteNewFi p,
    #InviteNewFi span {
        font-weight: 500;
        font-size: 15px;
    }

    .progress {
        display: -ms-flexbox;
        display: flex;
        height: 2.125rem;
        font-size: 1rem;
    }

    .make-invitation-container {
        display: none
    }

    .swal-modal .swal-text {
        text-align: center;
    }

    .swal-footer {
        text-align: center;
    }

    .dataTables_scrollHeadInner {
        /*display: none;*/
    }

    #DataTables_Table_0>thead {
        display: none;
    }
</style>
<div class="card make-invitation-container" @if(request()->filled('demo_setup')) style="background: pink" @endif>
    <div class="table-responsive">
        <table class="table text-nowrap">
            <tbody>
                <tr class="table-active table-border-double">
                    <td colspan="4" class="my_h">Select institutions</td>
                    <td>
{{--                        <button type="button" class="btn custom-primary round mmy_btn pull-right" data-toggle="modal" data-target="#update">Add a New FI</button>--}}
                    </td>
                    <td class="text-left"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-sm-12">
        <div class="table-responsive">

            <table class="table custom-data-tables no-footer" style="table-layout:fixed">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th>Province</th>
                        <th>Short term DBRS rating</th>
                        <th>Deposit Insurance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-2">
            <button style="border:1.5px solid gainsboro"
                class="btn btn-block custom-secondary round px-4 font-weight-bold "
                id="confirm_edit_request">Back</button>
        </div>
        <div class="col-sm-10 col-10 col-md-10" style="text-align: right;">
            <button type="button"
                class="btn btn-lg select_all  custom-secondary round px-4 font-weight-bold"> Invite
                All</button>
            &nbsp;&nbsp;&nbsp;
            <button type="button" class="btn btn-warning btn-lg clear_invites px-4 round  font-weight-bold">Clear
                All</button>
            &nbsp;&nbsp;&nbsp;
            <button type="button"
                class="btn custom-primary round btn-lg send_invites custom-primary round px-4 font-weight-bold">Next</button>
        </div>
    </div>
    <br /><br />

    @php
        $user = auth()->user();
        //$organization = $user->organization;
    @endphp
    <div id="update" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md" style="width: 50%!important;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add New Financial Institution</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="myWizard">
{{--                    <div class="progress">--}}
{{--                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1"--}}
{{--                            aria-valuemin="1" aria-valuemax="2" style="width: 100%;">--}}
{{--                            Step 1--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="navbar" style="display: none">
                        <div class="navbar-inner">
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#step1" data-toggle="tab" data-step="1">Step 1</a></li>
{{--                                <li><a href="#step2" data-toggle="tab" data-step="2">Step 2</a></li>--}}
                            </ul>
                        </div>
                    </div>

                    <form action="" method="post" autocomplete="off" id="InviteNewFi">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="step1">
                                <div class="row">
                                    <div class="col-md-12 well">
                                        <h5>Institution Name</h5>
                                        <div class="form-group">
                                            @csrf
                                            <input type="hidden" name="action" value="INVITE_NEW_FI" />

                                            <select name="name" class="form-control select2 institution_select"
                                                required>
                                                <option value="">Select Institution</option>
                                                @php
                                                    $fis = getFIs();
                                                @endphp
                                                @foreach ($fis as $fi)
                                                    <option value="{{ $fi->name }}">{{ $fi->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <h5>Email</h5>
                                        <div class="form-group">
                                            <input type="email" id="email" name="email"
                                                placeholder="Email Address" class="form-control col-md-12" />
                                        </div>

                                        <h5>Re-Email</h5>
                                        <div class="form-group">
                                            <input type="email" id="re-email" name="re_enter_email"
                                                placeholder="Re-email Address" class="form-control col-md-12" />
                                        </div>

                                        <div class="form-group">
                                            <input type="button" class="btn btn-secondary" data-dismiss="modal"
                                                value="Close">
                                            <input type="reset" class="btn custom-primary round" value="Clear" />
                                            <input type="submit" name="create_non_partnered_fi"
                                                class="btn custom-primary round mmy_btn pull-right" value="Submit" />
                                        </div>
                                    </div>
                                </div>
                            </div>
{{--                            <div class="tab-pane fade" id="step2">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <h5 style="color: red">--}}
{{--                                            I acknowledge that Yield Exchange will send my request details as well as--}}
{{--                                            this email to <span id="institution_name"></span> on my behalf.--}}
{{--                                        </h5>--}}
{{--                                        <div class="card card-primary">--}}
{{--                                            <div class="card-header">--}}

{{--                                            </div>--}}
{{--                                            <div class="card-body">--}}
{{--                                                <p>Hi <input name="account_manager_name" type="text"--}}
{{--                                                        maxlength="30" placeholder="Account Manager Name"--}}
{{--                                                        class="form-control col-md-5" style="display: inline" />,</p>--}}
{{--                                                <br />--}}
{{--                                                <p>--}}
{{--                                                    I'm inviting you to participate in my deposit request through Yield--}}
{{--                                                    Exchange; a secure, digital platform that allows me to negotiate--}}
{{--                                                    with Canadian Financial Institutions like you, easily and--}}
{{--                                                    efficiently.--}}
{{--                                                </p>--}}
{{--                                                <br />--}}
{{--                                                <p>--}}
{{--                                                    Please contact me if you have any questions regarding this--}}
{{--                                                    invitation, otherwise I look forward to your response on my Deposit.--}}
{{--                                                </p>--}}
{{--                                                <br />--}}
{{--                                                <p>Thanks,</p>--}}
{{--                                                <input name="your_name" type="text"--}}
{{--                                                    placeholder="Input your name here" class="form-control col-md-5"--}}
{{--                                                    maxlength="30" style="display: inline" />--}}
{{--                                                <!--                                                <p><span id="account_manager_name_footer"></span></p>-->--}}
{{--                                                <br />--}}
{{--                                                <br />--}}
{{--                                                <p>{{ $user->email }}</p>--}}
{{--                                                <p>{{ $organization->demographicData ? $organization->demographicData->telephone : '-' }}--}}
{{--                                                </p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group">--}}
{{--                                            <input type="button" class="btn btn-secondary back " value="Back">--}}
{{--                                            <input type="button" class="btn custom-primary round" data-dismiss="modal"--}}
{{--                                                value="Cancel Invite" />--}}
{{--                                            <input type="submit" name="create_non_partnered_fi"--}}
{{--                                                class="btn custom-primary round mmy_btn pull-right" value="Send" />--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</div>
