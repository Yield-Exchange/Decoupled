<template>
    <div>
        <RequestOfferSummaryCard :offer="offer" :hasCounter="counterOffers" :from_page="from_page" :deposit_request="deposit_request" />
        <DepositorSummaryCard :organization_data="organization_data" :deposit_request="deposit_request" />
    </div>
</template>

<script>
import DepositorSummaryCard from './DepositorSummaryCard.vue';
import RequestOfferSummaryCard from './RequestOfferSummaryCard.vue';

export default {
    props: ['organization_data', "offer", "counterOffers","from_page","deposit_request"],
    components: {
        RequestOfferSummaryCard,
        DepositorSummaryCard
    },
    data() {
        return {
        }
    },
    mounted() {
    },
}
</script>

<style>

</style>
