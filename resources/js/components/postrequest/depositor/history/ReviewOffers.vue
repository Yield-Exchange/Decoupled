<template>
    <div class="w-100">
        <!-- header -->
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/secthistory.svg" />
                        </div>
                        <div class="text-div">Review Offers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 my-3">
            <FilterBox @clear_filters="clearFilters" @apply_filters="applyFilters" filterType="depositor"></FilterBox>
        </div>
        <!-- table -->
        <div>
            <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No History"
                :data="table_data" :has_action='false' :actions='actions' :is_loading="is_fetching_data" />
            <Pagination @click-next-page="paginateFunction" v-if="data && data.links" :data="data" />
        </div>

        <div class=" w-100 d-flex justify-content-end my-4 gap-2">
            <div class="d-flex justify-content-end mt-3 gap-2">
                <CustomSubmit @action="goBack" title="Previous" />
            </div>
        </div>


        <!-- {{ data.data }} -->
    </div>
</template>
<script>

import Pagination from '../../../shared/Table/Pagination.vue'
import Table from '../../../shared/Table'
import ViewOffer from './actions/ViewOffer.vue';
import FilterBox from '../../shared/FilterBox.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'

export default {
    components: { Pagination, Table, ViewOffer, FilterBox, CustomSubmit },
    props: ['requestid'],
    mounted() {
        this.getData()
    },

    data() {
        let columns = ['Offer ID', 'Institution', 'Interest Rate', 'Currency', 'Minimum Amount', 'Maximum Amount', 'Awarded Amount', 'Action']
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
            let getpath = url != null ? url : "/pick-offers-data-new/" + this.requestid
            axios.get(getpath).then(response => {
                let responsdata = response.data.data
                this.data = response.data
                // console.log(responsdata)
                if (responsdata.length > 0) {
                    responsdata.forEach(deposit => {
                        let pendingdeposit = [
                            deposit.encoded_offer_id,
                            deposit.reference_no,
                            this.capitalize(deposit.bank_name),
                            deposit.interest_rate_offer.toFixed(2) + "%",
                            deposit.invited.deposit_request.currency,
                            this.addCommas(deposit.minimum_amount),
                            this.addCommas(deposit.maximum_amount),
                            this.addCommas(deposit?.invited?.deposit_request?.amount),
                            () => {
                                return ({ 'component': ViewOffer, 'attrs': { deposit_id: deposit.encoded_offer_id, } });
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
            let url = "/pick-offers-data-new/" + this.requestid + "?"
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