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
                window.location.href = '/request-offer-summary?offer_id=' + this.actionId;
            }
        },
        props: ['actionId']
    }
</script>