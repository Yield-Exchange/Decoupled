<script>
    //===== Prealoder
    // $(window).on('load', function (event) {
    //     $('#cover-spin').delay(100).fadeOut(100);
    // });

    $(document).ready(function() {
        $(document).on("click", ".menu-open .nav-link-main", function(e) {
            // e.preventDefault();
            $(this).parent().find(".nav-treeview").toggle();
        });
    });

    $(document).on("change", ".switch_timezone", function() {
        makeApiCall("{{ url('update-timezone') }}", {
            'timezone': $(this).val(),
            '_token': "{{ csrf_token() }}"
        }, function(response) {
            @if (!$blurred)
                if (response.success) {
                    try {
                        swal("Timezone update.", response.message).then(function() {
                            window.location.reload();
                        });
                    } catch (e) {
                        window.location.reload();
                    }
                } else {
                    try {
                        swal("", response.message, "info");
                    } catch (e) {}
                }
            @else
                window.location.reload();
            @endif
        }, null, "POST", function(xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)) {
                swal("An error occurred, the page will refresh.").then(() => {
                    window.onbeforeunload = null;
                    window.location.reload();
                });
                return;
            }

            @if (!$blurred)
                swal("", apiCallServerErrorMessage(xhr, "Unable to update timezone, try again later"),
                    "error");
            @endif
        });
    });
</script>
<script>
    $(document).on("change", ".update_notification_preference", function() {
        makeApiCall("{{ url('update-preference') }}", {
            'preference': 'mute_notification',
            'preference_value': $(this).prop('checked') === true ? 1 : 0,
            '_token': "{{ csrf_token() }}"
        }, function(response) {
            swal("", response.message, "success");
        }, null, "POST", function(xhr, textStatus, errorThrown) {
            if ([419].includes(xhr.status)) {
                swal("An error occurred, the page will refresh.").then(() => {
                    window.onbeforeunload = null;
                    window.location.reload();
                });
                return;
            }

            swal("", apiCallServerErrorMessage(xhr,
                "Unable to update the notifications setting, try again later"), "error");
        });
    });
</script>
<script>
    @if (is_admin_route(request()))
        $(document).on("click", ".admin-action-to-user-alert-update-credit-rating", function() {
            swal("", "Please update deposit and credit rating for the organization before approving", "");
            // swal({
            //     title: "",
            //     text: "Please update deposit and credit rating for the organization before approving",
            //     // icon: "warning",
            //     buttons: ["Ok"],
            // }).then((response) => {
            //     // DO  NOTHING
            // });
        });
        $(document).on("click", ".admin-action-to-user", function() {

            swal({
                title: "",
                text: "Are you sure to perform that action to the organization?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    let $loader1 = $("#cover-spin");
                    makeApiCall($(this).attr('href'), {
                        '_token': "{{ csrf_token() }}"
                    }, function(response) {
                        swal("", response.message, "success").then(() => {
                            window.location.reload();
                        });
                    }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                            "Unable to perform the action to the user, try again later"
                        ), "error");
                    });
                }
            });

            return false;
        });
        $(document).on("submit", ".admin-close-user-form", function() {

            swal({
                title: "",
                text: "Are you sure to close this organization?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    let $loader1 = $("#cover-spin");
                    makeApiCall($(this).attr('action'), $(this).serializeArray(), function(response) {
                        swal("", response.message, "success").then(() => {
                            window.location.reload();
                        });
                    }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                            "Unable to perform the action to the user, try again later"
                        ), "error");
                    });
                }
            });

            return false;
        });
        $(document).on("submit", ".admin-update-organization-level-form", function() {

            swal({
                title: "",
                text: "Are you sure you want to update permissions?",
                // icon: "warning",
                buttons: ["No", "Yes"],
            }).then((response) => {
                if (response) {
                    let $loader1 = $("#cover-spin");
                    makeApiCall($(this).attr('action'), $(this).serializeArray(), function(response) {
                        swal("", response.message, "success").then(() => {
                            window.location.reload();
                        });
                    }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                        if ([419].includes(xhr.status)) {
                            swal("An error occurred, the page will refresh.").then(() => {
                                window.onbeforeunload = null;
                                window.location.reload();
                            });
                            return;
                        }

                        swal("", apiCallServerErrorMessage(xhr,
                            "Unable to perform the action to the user, try again later"
                        ), "error");
                    });
                }
            });

            return false;
        });
        $(document).on("submit", ".admin-user-limit-form", function() {

            let $loader1 = $("#cover-spin");
            makeApiCall($(this).attr('action'), $(this).serializeArray(), function(response) {
                swal("", response.message, "success").then(() => {
                    window.location.reload();
                });
            }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                if ([419].includes(xhr.status)) {
                    swal("An error occurred, the page will refresh.").then(() => {
                        window.onbeforeunload = null;
                        window.location.reload();
                    });
                    return;
                }

                swal("", apiCallServerErrorMessage(xhr,
                    "Unable to perform the action to the user, try again later"), "error");
            });

            return false;
        });
    @endif
    $(document).on("click", ".org-action-to-user", function() {

        swal({
            title: "",
            text: "Are you sure to perform that action to the user?",
            // icon: "warning",
            buttons: ["No", "Yes"],
        }).then((response) => {
            if (response) {
                let $loader1 = $("#cover-spin");
                makeApiCall($(this).attr('href'), {
                    '_token': "{{ csrf_token() }}"
                }, function(response) {
                    swal("", response.message, "success").then(() => {
                        window.location.reload();
                    });
                }, $loader1, "POST", function(xhr, textStatus, errorThrown) {
                    if ([419].includes(xhr.status)) {
                        swal("An error occurred, the page will refresh.").then(() => {
                            window.onbeforeunload = null;
                            window.location.reload();
                        });
                        return;
                    }

                    swal("", apiCallServerErrorMessage(xhr,
                            "Unable to perform the action to the user, try again later"),
                        "error");
                });
            }
        });

        return false;
    });
    // 
    $(document).ready(function() {
        const $select = $('#switch-select');
        $select.append(`<option>${user_object.organization_name}</option>`);

        axios.get('/common/account-management/get-my-organizations').then(response => {
            const options = response.data;

            // Clear existing options (optional)
            
            // Check if the response contains values
            if (Array.isArray(options) && options.length > 0) {
                // Loop through the options array and add each as an option element
                $select.empty();
                options.forEach(function(item) {
                    // Create a new option element
                    const $option = $('<option></option>')
                        .attr('value', item.id) // Adjust if needed based on item structure
                        .text(item.name); // Adjust if needed based on item structure
                    if (item.id == user_object.organization_id) {
                        $option.attr('selected', 'selected');
                    }
                    // Append the option element to the select element
                    $select.append($option);
                });
            } else {
                // Optionally handle cases where there are no values
                $select.append(`<option>${user_object.organization_name}</option>`);
            }

        }).catch(err => {
            console.log(err)
        })

    });
</script>
