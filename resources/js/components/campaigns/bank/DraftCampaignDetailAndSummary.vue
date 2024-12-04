<template>
    <div>
        <DetailSummary :campaign="campaign" />
        <div style="width: 100%;height: 30px;"></div>
        <!--        <DetailInsights />-->
    </div>
</template>
<script>
    import DetailSummary from "./DraftDetailSummary"
    export default {
        components: {
            DetailSummary
        },
        props: ['campaign']
    }
</script>