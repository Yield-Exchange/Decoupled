@extends('dashboard.master')
@section('page_title')
    Profile Setting
@stop
@section('styles')
   
@endsection
@section('page_content')
    @php
        $user_data = auth()->user();
    @endphp
    <!-- Main charts -->
    <div class="row" id="VueApp">
        <div class="col-xl-12">
            <!-- Traffic sources -->
            
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <profile-setting
                            provinces="{{ $provinces }}"
                            timezones="{{ $timezones }}"
                            user="{{ json_encode($user_data) }}"
                            logged-in-as="{{ !is_admin_route(request()) ? ucwords(strtolower($user_data->organization->type)) : 'Admin' }}"
                            update-profile-route="{{ !is_admin_route(request()) ? route('user.update-profile-setting') : route('admin.update-profile-setting') }}"
                            reset-password-route="{{ !is_admin_route(request()) ? url('reset-password') : url('yie-admin/reset-password') }}"
                        >
                        </profile-setting>
                    </div>

                    <div class="col-md-2"></div>
                </div>
                   
            <!-- /support tickets -->
        </div>
    </div>

    <!-- /main charts -->
@endsection
@section('scripts')
    <script>
        // $(document).on('click','a',function (e) {
        //         if ( !$(this).hasClass("paginate_button") && !$(this).hasClass("no-page-exit-alert")) {
        //             e.preventDefault();
        //             let $this = $(this);
        //             let $loader = $("#cover-spin");
        //             swal({
        //                 title: "Do you want to leave this page?",
        //                 text: "Changes you made will not be saved.",
        //                 // icon: "warning",
        //                 buttons: ["No", "Yes"],
        //             }).then((response) => {
        //                 if (response) {
        //                     $loader.show();
        //                     window.onbeforeunload = null;
        //                     window.location.href=$this.attr('href');

        //                 }
        //             });
        //         }
        //     });
    </script>
@endsection
