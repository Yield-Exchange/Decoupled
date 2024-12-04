<template>
    <div>
        <b-tooltip :placement="placement ? placement : 'top'" ref="tooltip" :target="target"
            custom-class="tooltip-wrapper">
            <template v-if="ishtml">

                <div v-html="message"> </div>

            </template>
            <template v-else>{{ message }}</template>
        </b-tooltip>
    </div>
</template>
<style>
.tooltip-wrapper .tooltip-inner {
    background-color: white;
    /* Your desired background color */
    /* Other styles */
    color: #6F6C90;
    font-size: 11px;
    font-family: Montserrat;
    font-weight: 700;
    line-height: 14px;
}

.tooltip-wrapper .arrow::before {
    border-top-color: #EFF2FE;
    /* Match the background color */
}
</style>
<script>
export default {
    props: ['message', 'target', 'placement', "ishtml"],
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