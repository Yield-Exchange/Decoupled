<template>
    <div v-if="(featured===1)"
        style="width: 90px; height: 100%; padding-left: 10px; padding-right: 10px; padding-top: 5px; padding-bottom: 5px; background: #5063F4; justify-content: flex-start; align-items: center; display: inline-flex">
        <div
            style="flex: 1 1 0; text-align: center; color: white; font-size: 9px; font-family: Montserrat; font-weight: 500; line-height: 9px; word-wrap: break-word">
            Featured</div>

    </div>
    <div v-else
        style="width: 90px; height: 100%; padding-left: 10px; padding-right: 10px; padding-top: 5px; padding-bottom: 5px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
        <div
            style="flex: 1 1 0; text-align: center; color: #5063F4; font-size: 9px; font-family: Montserrat; font-weight: 500; line-height: 9px; word-wrap: break-word">
            Un-Featured</div>
    </div>
</template>
<script>

    export default {
        components: {
        },
        props: [
            'featured'
        ],

    }
</script>