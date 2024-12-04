@extends('dashboard.master')
@section('page_title')
    System Admins
@stop
@section('page_content')
@php
    $user = auth()->user();
@endphp
<style>
    .withdraw-modl{ width:600px; }

    .swal-modal .swal-text {
        text-align: center;
    }
    .swal-footer { text-align: center; }
</style>
<div id="VueApp">

    @php
        $listusers = json_encode(route('admin.users.data','admins'));
        $authuser  = json_encode(auth()->user());
        $provinces = json_encode(provinces());
        $timezones = json_encode(timezonesList());
        $roles = json_encode(App\Role::where(function ($query) {
                $query->where('for_system_admin', true)
                    ->orWhere('name', 'system-administrator');
           })->get());
        $route = json_encode(route('admins.create'));
        $currentRoute = json_encode(route('admin.users', 'admins'));
        $suspendStatus = json_encode(route('org.update-users-status', 'suspend'));
        $activateStatus = json_encode(route('org.update-users-status', 'activate'));
        $deleteRoute = json_encode(route('users.delete'));
        $canCreate = $user->userCan('admin/manage-admins/create');
    @endphp
    {{--  <admin-list provinces="{{ $provinces }}" timezones="{{ $timezones }}" roles="{{ $roles }}" createroute="{{ $route }}" listroute="{{ $currentRoute }}" authuser="{{ $authuser }}" listusersroute="{{ $listusers }}" suspendroute="{{ $suspendStatus }}" activiateroute="{{ $activateStatus }}" deleteroute="{{$deleteRoute}}"></admin-list>  --}}
    <ye-table columns="name,email,role_name,account_status" columns_table_head="Name,Email,Role,Status" provinces="{{ $provinces }}"
            timezones="{{ $timezones }}" roles="{{ $roles }}" createroute="{{ $route }}"
            listroute="{{ $currentRoute }}" authuser="{{ $authuser }}"
            listusersroute="{{ $listusers }}" suspendroute="{{ $suspendStatus }}"
            activiateroute="{{ $activateStatus }}" deleteroute="{{$deleteRoute}}"
            cancreate = "{{$canCreate}}">
    </ye-table>

</div>


@endsection

@section('scripts')



@endsection

