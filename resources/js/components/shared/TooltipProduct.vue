<template>
    <div>
        <b-tooltip :placement="placement ? placement : 'top'" ref="tooltip" :target="target"
            custom-class="tooltip-wrapper">
            <div v-for="productType in productTypes" :key="productType.id">
                <h5>{{ productType.description }} </h5><br>
                <p>{{ productType.definition }}</p>
            </div>

        </b-tooltip>
    </div>
</template>
<style>
    .tooltip-wrapper {}

    .tooltip-wrapper .tooltip-inner {
        background-color: white;
        color: #6F6C90;
        font-size: 11px;
        font-family: Montserrat;
        font-weight: 700;
        line-height: 14px;
        max-height: 300px !important;
        overflow-y: scroll !important;
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
                productTypes: null,
            }
        },
        mounted() {
            this.getProducts();
        },
        computed: {

        },
        methods: {
            getProducts() {
                this.loading = true;
                let this_ = this;
                axios.get(`/campaigns/fi/get-all-products-types`)
                    .then(response => {
                        this.productTypes = response?.data;

                    }).catch(error => {
                        console.log("error > " + error);
                        this_.groups_lists = null;
                        this.loading = false;
                    });
            }
        }
    }
</script>