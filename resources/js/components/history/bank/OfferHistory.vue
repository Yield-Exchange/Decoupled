<template>
    <div style="margin-top: 30px;">
        <FilterBox @clear_filters="getData" @apply_filters="applyFilters" filterType="bank"></FilterBox>
        
        <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No offer history"
            :data="table_data" :has_action='false' :actions='actions' :is_loading="is_fetching_data" />
        <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
    </div>
</template>

<script>
import Table from '../../shared/Table.vue';
import Pagination from '../../shared/Table/Pagination.vue';
import ViewOffer from "./ViewOffer.vue";
import FilterBox from "../../postrequest/shared/FilterBox.vue"
export default {
    components: {
        Table,
        Pagination,
        ViewOffer,
        FilterBox
    },
    methods: {
        getData(url) {
            this.is_fetching_data = true;
            let getpath = url ? url : "/get-bank-history-offer";
            let offerHistories = []
            axios.get(getpath).then(response => {
                let data = response.data.data;
                this.data = response.data
                this.is_fetching_data = false
                if (data.length > 0) {
                    data.forEach(offer => {
                        let offerHistory = [
                            offer?.offer_id_encoded,
                            offer?.invited?.deposit_request?.reference_no,
                            offer?.modified_date ? this.formatDateToCustomFormat(offer?.modified_date) : this.formatDateToCustomFormat(offer?.created_date),
                            offer?.depositor,
                            offer?.province,
                            offer?.invited?.deposit_request?.currency+" "+ this.addCommas(offer?.invited?.deposit_request?.amount),
                            offer?.invited?.deposit_request?.product_name,
                            offer?.invited?.deposit_request?.term_length_type == "HISA" ? "-" : offer?.invited?.deposit_request?.term_length + " " + this.ucwords(offer?.invited?.deposit_request?.term_length_type.toLowerCase()),
                            parseFloat(offer?.interest_rate_offer).toFixed(2)+" %",
                            this.ucwords(offer?.offer_status.toLowerCase().replace(/_/g, ' ')),
                            () => {
                                return ({ 'component': ViewOffer, 'attrs': { offer_id: offer.offer_id_encoded, } });
                            },
                        ]
                        offerHistories.push(offerHistory)

                    });
                    
                    this.table_data = offerHistories
                }else{
                    this.table_data = []
                }
            }).catch(error => {
                console.log(error)
            })
        },
        formatDateToCustomFormat(inputDate) {
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        addCommas(newvalue) {
            return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        ucwords(str) {
            return str.toLowerCase().replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            })
        },
        applyFilters(value) {
            let url = '/get-bank-history-offer?'
            console.log(url + "" + value)
            this.getData(url + value)
        }
    },
    mounted() {
        this.getData();
    },
    data() {
        let columns = ['Request ID', 'Date', 'Depositor Name', 'Province', 'Deposit Amount', 'Product', 'Duration', 'Interest', 'Status', 'Action']

        return {
            columns: columns,
            is_loading: true,
            is_fetching_data: false,
            actions: [],
            table_data: [],
            data: null
        }
    },
}
</script>

<style></style>