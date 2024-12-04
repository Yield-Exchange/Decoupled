<template>
    <TableActionButton type="table-action-btn" variantColor="#5063F4" borderColor="#5063F4" fontColor="#FFFFFF"
        @click="viewOffersSummary()">
        <img src="/assets/dashboard/icons/view_offer.svg">
        View Offers
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
            window.location.href = '/repos/repos-reviews/summary/' + this.actionId + '?fromPage=repos/repos-reviews';
        }
    },
    props: ['actionId']
}
</script>