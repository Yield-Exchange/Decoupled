<template>
    <TableActionButton type="table-action-btn" variantColor="#5063F4" borderColor="#5063F4" fontColor="#FFFFFF"
        @click="viewOffersSummary()">
        <img src="/assets/dashboard/icons/view_offer.svg">
        View
    </TableActionButton>
</template>
<script>
import Button from "../../../../shared/Buttons/Button";
import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
export default {
    components: {
        TableActionButton,
        Button,
    },
    methods: {
        viewOffersSummary() {
            window.location.href = '/repos/cg-view-repo-history-trade/' + this.actionId + '?fromPage=repos/cg-repos-history-trades';
        }
    },
    props: ['actionId']
}
</script>