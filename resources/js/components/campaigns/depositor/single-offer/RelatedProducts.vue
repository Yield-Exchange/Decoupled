<template>
    <div v-if="data && data.length > 0">
        <div style="width: 100%;justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex; flex-wrap: wrap;">
            <RelatedProductCard :key="datum_?.id" v-for="(datum_) in data?.data" :datum="datum_" />
        </div>
        <div style="width: 90%">
            <Pagination @click-next-page="getData" v-if="data && data?.links" :data="data" />
        </div>
    </div>
    <div v-else>
        <NoProduct />
    </div>
</template>
<script>
    import RelatedProductCard from "./RelatedProductCard";
    import Pagination from "../../../shared/Table/Pagination";
    import NoProduct from "./NoProduct";
    export default{
        mounted() {
            this.getData();
        },
        components: {
            RelatedProductCard,
            Pagination,
            NoProduct
        },
        props: ['datum'],
        data(){
            return {
                data: null
            }
        },
        methods:{
            getData(url) {
                url = url ? url : "/inv-camp-offers-fetch-data?fi_id="+this.datum?.campaign?.fi_id+'&related_products='+this.datum?.id;
                let this_ = this;
                axios.get(url)
                .then(response => {
                    this_.data = response?.data?.data;
                }).catch(error => {
                    this_.data = null;
                });
            },
        }
    }
</script>