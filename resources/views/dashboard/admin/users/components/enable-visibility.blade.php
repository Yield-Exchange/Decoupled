<div id="enableVisibilityModal{{ $organization->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('organization.enable.visibility',\App\CustomEncoder::urlValueEncrypt($organization->id)) }}" class="admin-enable-visibility-form" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">Visibility Setting</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input name="visibility[]" class="form-check-input" {{ $organization->show_province_visibility ? 'checked' : '' }}  value="show_province_visibility" type="checkbox" />
                                    <label class="form-check-label" style="margin-top: 0;font-weight: normal">Province</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input name="visibility[]" class="form-check-input" {{ $organization->show_naics_codes_visibility ? 'checked' : '' }} value="show_naics_codes_visibility" type="checkbox" />
                                    <label class="form-check-label" style="margin-top: 0;font-weight: normal">NAICS codes</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input name="visibility[]" class="form-check-input" {{ $organization->show_customers_visibility ? 'checked' : '' }}  value="show_customers_visibility" type="checkbox" />
                                    <label class="form-check-label" style="margin-top: 0;font-weight: normal">Customers</label>
                                </div>
                            </div>
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