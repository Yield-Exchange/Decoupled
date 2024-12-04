<style>
    .swal-modal .swal-text {
        text-align: center;
    }

    .swal-footer {
        text-align: center;
    }

    .confirm-container {
        display: none;
    }

    .swal-modal .swal-text {
        text-align: center;
    }
</style>
@php
    $user = auth()->user();
@endphp
<div class="card confirm-container" @if(request()->filled('demo_setup')) style="background: pink;padding: 10px 20px 20px;" @else style="padding: 10px 20px 20px;" @endif >
    <div class="table-responsive" style="padding-left:0px">
        <table class="table text-nowrap">
            <tbody>
                <tr class="table-active table-border-double">
                    <td style="padding-left:10px" colspan="3" class="my_h"><span class="b_b">CON</span>FIRM DATA
                    </td>
                    <td class="text-right"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br />
    <div class="confirm_post_request_container"></div>
    <div class="row" >
        <div class="col-md-12" style="margin-top: -10px;">
            <div class="table-responsive" style="padding-left:0px;padding-bottom:0!important;">
                <table class="table text-nowrap">
                    <tbody>
                        <tr class="table-active table-border-double">
                            <td style="padding-left:0px;cursor:pointer;" class="my_h" onclick="show_more();">
                                <span style="color:#2CADF5;font-weight:bold;">INVITED FINANCIAL INSTITUTIONS</span>
                                &nbsp;&nbsp;
                                <img src="{{ asset('image/navigate-arrows-pointing-to-down.png') }}"
                                    class="img-advance-options" height="15px" />
                            </td>
                            <td class="text-left"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row confirm-container-list-fis" id="div11"></div>
    <br /><br />
    <div style="padding-bottom:30px;padding-left:10px;" class="row">
        <div class="col-lg-2 col-md-2 col-sm-3">
            <button style="border:1.5px solid gainsboro"
                class="btn btn-block cancel-request  custom-secondary round px-4 font-weight-bold">Cancel</button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3">
            <button style="border:1.5px solid gainsboro"
                class="btn btn-block  custom-secondary round px-4 font-weight-bold"
                id="confirm_edit_request">Edit</button>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-3">
            @if($user->is_super_admin || request()->filled('demo_setup'))
                <button type="button" class="btn mmy_btn btn-primary btn-block custom-primary round px-4 font-weight-bold" data-toggle="modal" data-target="#exampleModal">
                    Next
                </button>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Rate & Deposit Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">FI</th>
                                            <th scope="col">Rate %</th>
                                            @if(!request()->filled('demo_setup'))
                                                <th scope="col">Amount</th>
                                                <th scope="col">GIC Number</th>
                                                <th scope="col">Start Date</th>
                                                <th scope="col">Maturity Date</th>
                                            @else
                                                <th scope="col">Minimum Amount</th>
                                                <th scope="col">Maximum Amount</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody class="fi-rates-list-table">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary save_the_request">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <button
                        class="btn mmy_btn btn-primary btn-block save_the_request  custom-primary round px-4 font-weight-bold">Submit</button>
            @endif
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3"></div>
        <div class="col-lg-3 col-md-3 col-sm-3"></div>
    </div>
</div>
