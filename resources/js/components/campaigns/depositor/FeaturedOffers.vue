<template>
    <FeaturedTileViewCard :datum="datum" :formattedtimezone="formattedtimezone" />
</template>
<script>
    import FeaturedTileViewCard from "./FeaturedTileViewCard";
    export default {
        components: {
            FeaturedTileViewCard
        },
        props: ['datum', 'formattedtimezone']
    }
</script>