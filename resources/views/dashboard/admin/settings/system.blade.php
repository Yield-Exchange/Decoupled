@extends('dashboard.master')
@section('page_title')
    System Setting
@stop
@section('styles')
    <style>
        .c-white{
            color:white!important;
        }
        .table td{
            border:none;
        }
    </style>
@endsection
@section('page_content')
    @php
        $setting = getSystemSettings(['prime_rate','organization_seat_rate']);
        $prime_rate = isset($setting['prime_rate']) ? $setting['prime_rate'] : null;
        $organization_seat_rate = isset($setting['organization_seat_rate']) ? $setting['organization_seat_rate'] : null;
        $user = auth()->user();
    @endphp
    <div class="card">
        <div class="table-responsive">
            <form action="{{ route('admin.system.settings.submit') }}" method="post" class="settings_form">
                @csrf
                <table id="dtHorizontalExample" class="table" cellspacing="0" width="100%">
                    <tbody>
                    <tr>
                        <td style="width: 20%"><span style="font-weight: bold">Set Prime Rate %</span></td>
                        <td>
                            <input type="number" step=".01" class="form-control col-md-12" value="{{ $prime_rate ? $prime_rate->value : '' }}" name="prime_rate" required />
                        </td>
                        <td>
                            <span style="font-weight:normal;font-size:15px;">
                                Last updated on:
                                <u>
                                    {{ $prime_rate ? changeDateFromUTCtoLocal($prime_rate->modified_date) : '-' }}
                                </u>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%"><span style="font-weight: bold">Cost Per User Seat ($)</span></td>
                        <td>
                            <input type="number" step=".01" class="form-control col-md-12" value="{{ $organization_seat_rate ? $organization_seat_rate->value : '' }}" name="organization_seat_rate" required />
                        </td>
                        <td>
                            <span style="font-weight:normal;font-size:15px;">
                                Last updated on:
                                <u>
                                    {{ $organization_seat_rate ? changeDateFromUTCtoLocal($organization_seat_rate->modified_date) : '-' }}
                                </u>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right">
                            @if( $user->userCan('admin/system-settings/save-button') )
                            <button type="submit" class="btn btn-primary mmy_btn btn-lg settings_form_btn">Save</button>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).on("submit",".settings_form",function () {
            let $loader = $("#cover-spin");
            let $this_ = $(".settings_form_btn");
            let $this_form = $(".settings_form");
            let formData = $this_form.serializeArray();

            $this_.attr('disabled', true).html('Please wait');
            makeApiCall($this_form.attr("action"), formData, function (response) {
                if (response.success) {
                    swal("", response.message, "success").then(function () {
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                } else {
                    swal("", response.message, "info");
                }
                $this_.attr('disabled', false).html('Save');
            }, $loader, "POST", function (xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)){
                    swal("An error occurred, the page will refresh.").then(()=>{
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                swal("", apiCallServerErrorMessage(xhr, "Unable to update the setting, try again later", "error"));
                $this_.attr('disabled', false).html('Save');
            });

            return false;
        });
    </script>
@endsection