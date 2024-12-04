<template>
    <div>
        <div
            style="width: 100%;justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex; flex-wrap: wrap;">
            <BankOtherProductCard :key="datum_?.id" v-for="(datum_) in data?.data" :datum="datum_" />
        </div>
        <div style="width: 90%">
            <Pagination @click-next-page="getData" v-if="data && data?.links" :data="data" />
        </div>
    </div>
</template>
<script>
    import BankOtherProductCard from "./BankOtherProductCard";
    import Pagination from "../../../shared/Table/Pagination";
    export default {
        mounted() {
            this.getData();
        },
        props: ['datum'],
        components: {
            BankOtherProductCard,
            Pagination
        },
        data() {
            return {
                data: null
            }
        },
        methods: {
            getData(url) {
                url = url ? `${url}&other_bank_products=${this.datum?.id}&fi_id=${this.datum?.campaign?.fi_id}` : "/inv-camp-offers-fetch-data?other_bank_products=" + this.datum?.id + "&fi_id=" + this.datum?.campaign?.fi_id;
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