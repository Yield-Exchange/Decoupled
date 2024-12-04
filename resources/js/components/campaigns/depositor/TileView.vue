<template>
    <OfferTileCard :datum="datum" :formattedtimezone="formattedtimezone" />
</template>
<script>
    import OfferTileCard from "./OfferTileCard";
    export default {
        components: {
            OfferTileCard,
        },
        props: ['datum', 'formattedtimezone']
    }
</script>