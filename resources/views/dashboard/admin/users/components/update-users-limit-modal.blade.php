<div id="usersLimitModal{{ $organization_id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('organization.user-limit.update',$organization_id) }}" class="admin-user-limit-form" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">Update users limit for <strong style="font-weight: bolder">{{ $organization_name }}</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Users Limit</label>
                           <input class="form-control" type="number" min="1" name="users_limit" value="{{ $current_limit }}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn custom-primary round">Update</button>
                    <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>