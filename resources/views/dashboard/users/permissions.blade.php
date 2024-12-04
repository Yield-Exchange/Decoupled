@extends('dashboard.master')
@section('page_title')
    Set Roles Permissions
@stop
@section("styles")
    <style>
        .card{
            border-radius: 5px;
        }
        .card-title>a.collapsed:before {
            content: "";
        }
        .card-title>a:before {
            right: 0;
        }
        .card-title>a:before {
            content: "";
            /* font-family: icomoon; */
            position: absolute;
            top: 50%;
            margin-top: -0.5rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        input[type=checkbox] {
            -ms-transform: scale(1.5);
            -moz-transform: scale(1.5);
            -webkit-transform: scale(1.5);
            -o-transform: scale(1.5);
            transform: scale(1.5);
            padding: 10px;
        }
        .card-header {
            color: #333;
            background-color: #f5f5f5;
            border-color: red;
        }
        .select-all-permission{
            margin-left: 10px;
            margin-top: -10px;
        }
    </style>
@stop
@section('page_content')
    <div class="row">
        <div class="col-xl-12">
            <form action={{ route('admin.roles.assign.permission') }} method="post">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <tbody>
                    <tr class="table-active table-border-double">
                        <td colspan="3" class="my_h"><span class="b_b">Set</span> Permissions to Role: <strong style="font-weight: bold;text-decoration: underline;">{{ $role->display_name }}</strong> <span class="badge bg-blue badge-pill total_records_pill"></span></td>
                        <td class="text-right">
                            <a href="{{ url('/yie-admin/roles') }}" class="btn btn-lg btn-outline-secondary btn-sm" style="margin-right: 10px">Back</a>
                            @if($user->userCan('admin/roles/assign-permissions') )
                            <button type="submit" class="btn custom-primary round">Save</button>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
                @foreach($permission_user_groups as $permission_user_group)
                    @php
                        if($role->for_system_admin == 1 && in_array(strtoupper($permission_user_group), ['DEPOSITOR','BANK'])){
                            continue;
                        }else if($role->for_system_admin == 0 && in_array(strtoupper($permission_user_group), ['ADMIN'])){
                            continue;
                        }
                    @endphp
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">
                            <a class="text-body" data-toggle="collapse" href="#collapsible-permissions-group{{$permission_user_group}}" aria-expanded="true">{{ ucfirst(strtolower($permission_user_group)) }} Permissions</a>
                        </h6>
                    </div>

                    {{csrf_field()}}

                    <input type="hidden" class="form-control" name="role_id" id="user_id" value="{{ $role->id }}"/>

                    <div id="collapsible-permissions-group{{$permission_user_group}}" class="collapse collapsed">
                        <div class="card-body">
                            @php
                                $permissions_ = collect($permissions)->where('user_group',$permission_user_group);
                            @endphp
                            @foreach($permissions_ as $permission)
                            <div class="row col-md-12">
                                @php $perm_string=''; $all_permissions_enabled=true;
                                    foreach($permission->permissions as $item){
                                        $can_do = $role->isAbleTo($item->name,true) || $role->name == "organization-administrator";
                                        if(!$can_do){
                                            $all_permissions_enabled=false;
                                        }
                                        $perm_string .= '<div class="col-sm-4">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input name="permissions[]" '.($can_do ? "checked" : "").' class="form-check-input"  value="'.$item->id.'" type="checkbox" />
                                                    <label class="form-check-label" style="margin-top: 0;font-weight: normal">'.$item->display_name.'</label>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                @endphp
                                <div class="row col-md-12" style="margin-bottom: 10px;border-top: 1px dotted #ddd;padding-top: 5px">
                                    <h5 style="font-size: 14px;font-weight: bold">{{ $permission->name }}</h5> <div style="margin-top: 2px"><input {{ $all_permissions_enabled ? 'checked' : '' }} class="select-all-permission" style="margin-top: 0"  value="" type="checkbox" /> <small style="margin-left: 8px;margin-top: 0">Select All</small></div>
                                </div>
                                {!! $perm_string !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="row col-md-12">
                    <a href="{{ url('/yie-admin/roles') }}" class="btn btn-lg btn-outline-secondary" style="margin-right: 10px">Back</a>
                    @if($user->userCan('admin/roles/assign-permissions') )
                        <button type="submit" class="btn custom-primary round">Save</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on('change','.select-all-permission',function () {
            if(!$(this).is(':checked')){
                $(this).parent().parent().parent().find('.form-check-input').removeAttr('checked');
            }else{
                $(this).parent().parent().parent().find('.form-check-input').attr('checked','checked');
            }
        });
    </script>
@endsection
