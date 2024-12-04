<div id="assignIndustryModal{{ $organization_id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('organization.update-industry.update',$organization_id) }}" class="admin-update-industry-form" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">Update Industry for <strong style="font-weight: bolder">{{ $organization_name }}</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Select Industry</label>
                            <select name="industry_id" class="form-control">
                                <option value="">--Select---</option>
                                @foreach(\App\Models\Industry::all() as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                @endforeach
                            </select>
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