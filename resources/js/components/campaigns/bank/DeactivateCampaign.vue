<template>
    <div>
        <TableActionButton type="table-action-btn" @click="deleteStep = 1" variantColor="#FFEBEB" text-color="#FF2E2E;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path
                    d="M6.74845 6L8.84339 3.90505C8.94282 3.8058 8.99875 3.67112 8.99887 3.53064C8.99899 3.39015 8.94331 3.25537 8.84406 3.15594C8.74481 3.05652 8.61012 3.00059 8.46964 3.00047C8.32915 3.00034 8.19437 3.05603 8.09495 3.15528L6 5.25023L3.90505 3.15528C3.80563 3.05586 3.67078 3 3.53017 3C3.38956 3 3.25471 3.05586 3.15528 3.15528C3.05586 3.25471 3 3.38956 3 3.53017C3 3.67078 3.05586 3.80563 3.15528 3.90505L5.25023 6L3.15528 8.09495C3.05586 8.19437 3 8.32922 3 8.46983C3 8.61044 3.05586 8.74529 3.15528 8.84472C3.25471 8.94414 3.38956 9 3.53017 9C3.67078 9 3.80563 8.94414 3.90505 8.84472L6 6.74977L8.09495 8.84472C8.19437 8.94414 8.32922 9 8.46983 9C8.61044 9 8.74529 8.94414 8.84472 8.84472C8.94414 8.74529 9 8.61044 9 8.46983C9 8.32922 8.94414 8.19437 8.84472 8.09495L6.74845 6Z"
                    fill="#FF2E2E" />
            </svg>
            Deactivate
        </TableActionButton>
        <ConfirmDeletionPrompt :size="successModalSize" @closedSuccessModal="cancelIt" btnOneText="Cancel"
            btnTwoText="Deactivate" :title="`Warning.`" :showm="deleteStep === 1" @btnOneClicked="cancelIt"
            @btnTwoClicked="deactivateIt()"
            message="This operation cannot Be Undone. Please Note That Any Featured Product Will Also Disappear From Depositors Side." />
        <DeleteCampaignSuccess :size="successModalSize" @closedSuccessModal="dismissIt"
            title="Campaign successfully Deactivated" :showm="showSuccessModal" />
    </div>
</template>
<script>
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    import DeleteCampaignSuccess from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import ConfirmDeletionPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";

    import ApiError from "./ApiError";

    export default {
        components: {
            ConfirmDeletionPrompt,
            DeleteCampaignSuccess,
            TableActionButton,
            ApiError
        },
        props: ['actionId', 'actionObjectDetails'],
        data() {
            // console.log(this.actionObjectDetails);
            return {
                showSuccessModal: false,
                successModalSize: "md",
                response_error: null,
                deleteStep: 0,
                deleting_submitted: false,
            }
        },
        methods: {
            dismissIt() {
                this.deleteStep = 0;
                // this.$emit('reloadData', true);
                var currentUrl = window.location.href;
                window.location.href = currentUrl;
            },
            cancelIt() {
                this.deleteStep = 0;
            }, capitalize(thestring) {
                if (thestring != undefined) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            deactivateIt() {
                this.deleting_submitted = true;
                axios.post("/campaigns/fi/my-campaigns/deactivate-campaign", {
                    campaign: this.actionId,
                    action: 'INACTIVATE'
                }).then(response => {

                    if (response?.data?.success) {
                        this.response_error = null;
                        this.deleteStep = 2;
                        this.showSuccessModal = true;
                    } else {
                        new Error(response?.data?.message);
                    }

                    this.deleting_submitted = false;
                }).catch(error => {
                    this.deleting_submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });
            }
        }
    }
</script>