@php
    $allowedOrganizations = auth()
        ->user()
        ->allowedOrganizationsForSwitch();
    $types_to_switch_to = [];
    if ($allowedOrganizations) {
        $types_to_switch_to = array_unique($allowedOrganizations->pluck('type')->toArray());
    }
@endphp
<div id="switch-organization-modal" class="modal fade" role="dialog">
    <div class="modal-dialog  modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Switch Organization</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.switch-organization') }}" method="post" autocomplete="off"
                    id="switch-organization-form">
                    @csrf
                    <div class="row">

                        <div class="col-md-12 well">
                            @if (count($types_to_switch_to) > 1)
                                <h5>Type</h5>
                                <div class="form-group">
                                    <select name="organization_type" id="switch_to_organization_type"
                                        class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach ($types_to_switch_to as $to)
                                            <option value="{{ $to }}">{{ ucfirst(strtolower($to)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <h5>Organization</h5>
                            <div class="form-group">
                                <select id="switch_to_organization_id" name="organization_id" class="form-control"
                                    required>
                                    <option data-type="select" value="">Select</option>
                                    @if ($allowedOrganizations)
                                        @foreach ($allowedOrganizations as $organization)
                                            <option style="display: none" data-type="{{ $organization->type }}"
                                                value="{{ $organization->id }}">
                                                {{ ucfirst(strtolower($organization->name)) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row" align="center">
                        <div class="col-md-12 well">
                            <div class="form-group">
                                <br>
                                <input type="button" class="btn custom-secondary round" data-dismiss="modal"
                                    value="Cancel">
                                <input type="submit" class="btn custom-primary round mmy_btn text-white"
                                    value="Switch" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        @if (count($types_to_switch_to) > 1)
            $("#switch_to_organization_id option[data-type!=select]").hide();
        @endif

        @if (count($types_to_switch_to) == 1)
            $("#switch_to_organization_id option").show();
        @endif

        $(document).on('change', '#switch_to_organization_type', function() {
            let type = $(this).val();
            if (type) {
                $("#switch_to_organization_id option[data-type=" + type + "]").show();
                $("#switch_to_organization_id option[data-type!=" + type + "]").hide();
            } else {
                $("#switch_to_organization_id option[data-type=select]").show();
                $("#switch_to_organization_id option[data-type!=select]").hide();
            }
        });

        // $(document).on("submit","#switch-organization-form",function (){
        //
        //     swal({
        //         title: "",
        //         text: "Are you sure to switch to this organization?",
        //         // icon: "warning",
        //         buttons: ["No", "Yes"],
        //     }).then((response) => {
        //         if(response) {
        //             let $loader1 = $("#cover-spin");
        //             makeApiCall($(this).attr('action'), $(this).serializeArray(), function (response) {
        //                 swal("", response.message, "success").then(() => {
        //                     window.location.href='/dashboard';
        //                 });
        //             }, $loader1, "POST", function (xhr, textStatus, errorThrown) {
        //                 if ([419].includes(xhr.status)) {
        //                     swal("An error occurred, the page will refresh.").then(() => {
        //                         window.onbeforeunload = null;
        //                         window.location.href='/dashboard';
        //                     });
        //                     return;
        //                 }
        //
        //                 swal("", apiCallServerErrorMessage(xhr, "Unable to perform the action to the user, try again later"), "error");
        //             });
        //         }
        //     });
        //
        //     return false;
        // });
    });
</script>
