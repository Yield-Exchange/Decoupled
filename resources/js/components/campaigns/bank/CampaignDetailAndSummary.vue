<template>
    <div>
        <DetailSummary :campaign="campaign" :userloggedin="userloggedin" :formattedtimezone="formattedtimezone" />
        <div style="width: 100%;height: 30px;"></div>
        <!--        <DetailInsights />-->
    </div>
</template>
<script>
    import DetailSummary from "./DetailSummary"
    import DetailInsights from "./DetailInsights"
    export default {
        components: {
            DetailSummary,
            DetailInsights
        },
        props: ['campaign', 'userloggedin', 'formattedtimezone']
    }
</script>