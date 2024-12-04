<template>
   <div class="w-100">

        <div class="w-100 my-3">
            <FilterBox @clear_filters="clearFilters" @apply_filters="applyFilters" filterType="depositor"></FilterBox>
        </div>
        <!-- table -->
        <div>
            <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No History"
                :data="table_data" :has_action='false' :actions='actions' :is_loading="is_fetching_data" />
            <Pagination @click-next-page="paginateFunction" v-if="data && data.links" :data="data" />
        </div>

        <!-- {{ data.data }} -->
    </div>
</template>
<script>

import Pagination from '../../../shared/Table/Pagination.vue'
import Table from '../../../shared/Table'
import ViewDepoistOffer from './actions/ViewDepoistOffer.vue';
import FilterBox from '../../shared/FilterBox.vue';
export default {
    components: { Pagination, Table, ViewDepoistOffer, FilterBox },
    mounted() {
        this.getData()
    },

    data() {
        let columns = ['Date', 'Deposit ID', 'GIC Number', 'Institution', 'Product', 'Duration', 'Interest ', "Status", "Action"]
        return {
            viewMore1: false,
            columns: columns,
            is_loading: true,
            is_fetching_data: false,
            actions: null,
            table_data: [],
            data: null,
            filters: null
            // actions: null
        }
    },
    methods: {
        paginateFunction(value) {
            if (this.filters != null) {
                this.getData(value + "&" + this.filters)
            }
            else {
                this.getData(value)
            }
        },
        clearFilters() {
            this.filters = null
            this.getData()
        },
        getData(url) {
            if (this.table_data.length == 0)
                this.is_fetching_data = true
            let pendingdeposits = []
            let getpath = url != null ? url : "/deposits-history-data-new"
            axios.get(getpath).then(response => {
                let responsdata = response.data.data
                this.data = response.data
                // console.log(responsdata)
                if (responsdata.length > 0) {
                    responsdata.forEach(deposit => {
                        let pendingdeposit = [
                            deposit.encoded_offer_id,
                            deposit.modified_at != null ? this.formatDateToCustomFormat(deposit.modified_at) : "N/A",
                            deposit.reference_no,
                            deposit.gic_number ? deposit.gic_number : '-',
                            this.capitalize(deposit.offer.bank_name),
                            deposit.offer.invited.deposit_request.product_name,
                            deposit.offer.invited.deposit_request.term_length_type == 'HISA' ? '-' : deposit.offer.invited.deposit_request.term_length + " " + this.capitalize(deposit.offer.invited.deposit_request.term_length_type),
                            deposit.offer.interest_rate_offer.toFixed(2) + " %",
                            this.capitalize(deposit.status.replace('_', ' ')),
                            () => {
                                return ({ 'component': ViewDepoistOffer, 'attrs': { deposit_id: deposit.encoded_offer_id } });
                            }

                        ]
                        pendingdeposits.push(pendingdeposit)

                    });
                    this.is_fetching_data = false
                    this.table_data = pendingdeposits

                } else {
                    this.is_fetching_data = false
                    this.table_data = []
                }

            }).catch(err => {
                this.is_fetching_data = false
                console.log(err)
            })
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }

        },
        applyFilters(value) {
            let url = '/deposits-history-data-new?'
            // console.log(url + "" + value)
            this.filters = value
            this.getData(url + value)
        },

        capitalize(thestring) {
            if (thestring != undefined) {
                return thestring
                    .toLowerCase()
                    .split(' ')
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }
        },
        formatDateToCustomFormat(inputDate) {
            // Create a Date object from the inputDate parameter
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
    }
}

</script>