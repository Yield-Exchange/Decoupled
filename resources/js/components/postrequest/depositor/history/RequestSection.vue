<template>
    <div>

        <div class="w-100 my-3">
            <FilterBox @clear_filters="clearFilters" @apply_filters="applyFilters" :isfromrequest="true"
                filterType="depositor"></FilterBox>
        </div>
        <!-- table -->
        <div>
            <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No History"
                :data="table_data" :has_action='false' :actions='actions' :is_loading="is_fetching_data" />
            <Pagination @click-next-page="paginateFunction" v-if="data && data.links" :data="data" />
        </div>

        <!-- {{ data.data }} -->

        <!-- <div class=" w-100 d-flex justify-content-between my-4 gap-2">
            <div class="d-flex justify-content-end mt-3 gap-2">
                <CustomSubmit @action="goBack = true" title="Previous" />
            </div>
        </div> -->
    </div>
</template>
<script>

import Pagination from '../../../shared/Table/Pagination.vue'
import Table from '../../../shared/Table'
import ReviewOffer from './actions/ReviewOffer.vue';
import FilterBox from '../../shared/FilterBox.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'

export default {
    components: { Pagination, Table, ReviewOffer, FilterBox, CustomSubmit },
    mounted() {
        this.getData()
    },

    data() {
        let columns = ['Date', 'Request ID', 'Request Amount', 'Product', 'Duration', 'Number of Offers', 'Status', "Action"]
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
        goBack() {
            window.location.href = "/depositor-history"
        },
        getData(url) {
            if (this.table_data.length == 0)
                this.is_fetching_data = true
            let pendingdeposits = []
            let getpath = url != null ? url : "/request-history-data2"
            axios.get(getpath).then(response => {
                let responsdata = response.data.data
                this.data = response.data
                // console.log(responsdata)
                if (responsdata.length > 0) {
                    responsdata.forEach(deposit => {
                        let pendingdeposit = [
                            deposit.encoded_deposit_id,
                            this.formatDateToCustomFormat(deposit.closing_date_time),
                            deposit.reference_no,
                            deposit.currency + " " + this.addCommas(deposit.amount),
                            this.capitalize(deposit.product_name),
                            deposit.term_length_type == "HISA" ? '-' : deposit.term_length + " " + this.capitalize(deposit.term_length_type),
                            deposit.total_offers,
                            this.capitalize(deposit.request_status.replace(/_/g, ' ')),
                            () => {
                                return ({ 'component': ReviewOffer, 'attrs': { deposit_id: deposit.encoded_deposit_id, } });
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
            let url = '/request-history-data2?'
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