<div id="addAwsIntegrationModal{{ $organization_id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('organization.aws-integrate',$organization_id) }}" class="admin-update-industry-form" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">AWS Integration Settings For <strong style="font-weight: bolder">{{ $organization_name }}</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Select FileType</label>
                            <select name="file_type" class="form-control">
                                <option value="">--Select---</option>
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Select Routing Agent</label>
                            <select name="routing_agent" class="form-control">
                                <option value="">--Select---</option>
                                <option value="cds">CDS</option>
                                <option value="self">SELF</option>
                                <option value="yield">YIELD</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Select Delivery Method</label>
                            <select name="delivery_method" class="form-control">
                                <option value="">--Select---</option>
                                <option value="email">Email</option>
                                <option value="file_transfer">Fire Transfer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn custom-primary round">Save</button>
                    <button type="button" class="btn custom-secondary round" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>