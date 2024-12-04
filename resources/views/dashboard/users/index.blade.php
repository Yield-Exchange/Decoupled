@extends('dashboard.master')
@section('page_title')
    Users
@stop
@section('page_content')

<style>
    .withdraw-modl{ width:600px; }

    .swal-modal .swal-text {
        text-align: center;
    }
    .swal-footer { text-align: center; }
    .verror {
        border: 1px solid red;
        border-radius: 5px;
    }
</style>
@php
    $auth_user =auth()->user();
@endphp
    <div id="VueApp">
       
                        @php
                            $createRoute = json_encode(route('users.create'));

                            if($auth_user->is_super_admin){
                                $listUserRoute = json_encode(route('users.data.new',$org_id));
                            } else{
                                $listUserRoute = json_encode(route('users.data.new'));
                            }

                            $authuser = json_encode(auth()->user());
                            $organization = json_encode($organization);
                            $currentRoute = json_encode(route('users.index'));

                            $provinces = json_encode(provinces());
                            $timezones = json_encode(timezonesList());
                            $roles = json_encode(App\Role::whereNotIn('roles.name',unfetchableRoles())->where('for_system_admin',false)->get());
                            $suspendStatus = json_encode(route('org.update-users-status', 'suspend'));
                            $activateStatus = json_encode(route('org.update-users-status', 'activate'));
                            $deleteRoute = json_encode(route('users.delete'));
                            $canCreate = $auth_user->userCan('universal/users/create-users');
                            $limitExceeded = $users_limit_exceeded;
                        @endphp

                        <user-list  columns="name,email,role,account_opening_date,account_status,last_login,creator"
                                columns_table_head="Name,Email,Role,Account Opening Date,Status,Last Login,Created By"
                                fetchusersroute="{{ $listUserRoute }}" authuser="{{ $authuser }}"
                                listroute="{{ $currentRoute }}" suspendroute="{{ $suspendStatus }}"
                                activiateroute="{{ $activateStatus }}" deleteroute="{{$deleteRoute}}"
                                provinces="{{$provinces}}"  timezones="{{$timezones}}"
                                roles="{{$roles}}"  createroute="{{$createRoute}}"
                                organisation="{{$organization}}" 
                                cancreate="{{$canCreate}}" limit="{{$limitExceeded}}">
                        </user-list>
                    
    </div>
@endsection
@section('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

        $(document).on('click','.users-limit-exceeded',function () {
            swal("You have reached the maximum","All available user seats have been assigned. \nTo add more users please contact us.","");
        });
    </script>
@endsection