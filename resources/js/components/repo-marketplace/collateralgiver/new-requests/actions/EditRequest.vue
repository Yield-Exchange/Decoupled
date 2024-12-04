<template>
    <TableActionButton type="table-action-btn" variantColor="#FFFFFF" borderColor="#5063F4" fontColor="#5063F4"
        @click="viewPostInvitedInstitutions()">
        <img src="/assets/dashboard/icons/edit_offers.svg">
        Edit Request
        <GeneralErrorNoInteraction :title="errorTitle" :message="errorMsg" :show="showErrorMsg" size="md"
            @closedModal="showErrorMsg = false" />
    </TableActionButton>
</template>
<script>
import Button from "../../../../shared/Buttons/Button";
import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
import GeneralErrorNoInteraction from "../../../../shared/messageboxes/GeneralNoInteractionError.vue";
export default {
    components: {
        GeneralErrorNoInteraction,
        TableActionButton,
        Button,
    },
    data() {
        // console.log("actionObjectDetails", this.actionObjectDetails[6]);
        return {
            showErrorMsg: false,
            errorMsg: '',
            errorTitle: '',
            hasOffers: (this.actionObjectDetails[6] != undefined && this.actionObjectDetails[6] > 0) ? true : false
        }
    },
    methods: {
        viewPostInvitedInstitutions() {
            window.location.href = `/edit-trade-request?request_id=${this.actionId}`;
            // if (this.hasOffers) {
            //     this.showErrorMsg = true;
            //     this.errorTitle = 'You cannot Edit The Request';
            //     this.errorMsg = 'You have already received some offers.';

            // } else {
            // }

        }
    },
    props: ['actionId', 'actionObjectDetails']
}
</script>