<template>
    <div>
        <b-tooltip :placement="placement ? placement : 'top'" ref="tooltip" :target="target"
            custom-class="tooltip-wrapper">
            <img :src="message" style="width:800px;height:400px;" />
        </b-tooltip>
    </div>
</template>
<style>
    .tooltip-wrapper .tooltip-inner {
        background-color: #EFF2FE;
        /* Your desired background color */
        /* Other styles */
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .tooltip-wrapper .arrow::before {
        border-top-color: #EFF2FE;
        /* Match the background color */
    }
</style>
<script>
    export default {
        props: ['message', 'target', 'placement'],
        data() {
            return {

            }
        },
        computed: {

        },
        methods: {

        }
    }
</script>