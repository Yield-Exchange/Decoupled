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
                            <img src="/assets/dashboard/icons/clipboard.svg" />
                        </div>
                        <div class="text-div">Pending Deposits</div>
                    </div>
                    <!-- <div @click="toggleView(1)"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div> -->
                </div>
            </div>
        </div>
        <!-- end header -->


        <!-- filter box -->


        <div class="w-100 my-3">

            <FilterBox @clear_filters="clearFilters" @apply_filters="applyFilters" filterType="bank"></FilterBox>

        </div>
        <!-- end filter box -->

        <!-- table -->
        <div>
            <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No Pending Deposits"
                :data="table_data" :has_action='false' :actions='actions' :is_loading="is_fetching_data" />
            <Pagination @click-next-page="paginateFunction" v-if="data && data.links" :data="data" />
        </div>

        <!-- {{ data.data }} -->
    </div>
</template>
<script>

import Pagination from '../../../shared/Table/Pagination.vue'
import Table from '../../../shared/Table'
import Activate from './actions/Activate.vue';
import StartChat from './actions/StartChat.vue';
import ViewOffers from './actions/ViewOffers.vue';
import WithDraw from './actions/WithDraw.vue';
import View from './actions/View.vue';
import FilterBox from '../../shared/FilterBox.vue';
export default {
    components: { Pagination, Table, Activate, ViewOffers, WithDraw, View, FilterBox },
    mounted() {
        this.getData()
    },

    data() {
    let columns = ['Deposit ID', 'Depositor', 'Deposit Amount', 'Product', 'Term length', 'Rate', 'Rate Held Until', "Chat", "Action"]
        let action = [
            {
                name: "Activate",
                component: Activate,
            },
            {
                name: "View",
                component: View,
            },
            // {
            //     name: "Review Offers",
            //     component: ViewOffers,
            // },
            {
                name: "Withdraw",
                component: WithDraw,
            },

        ]
        return {
            viewMore1: false,
            columns: columns,
            is_loading: true,
            is_fetching_data: false,
            actions: action,
            table_data: [],
            data: null,
            filters: null,
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
            this.is_fetching_data = true
            let pendingdeposits = []
            let getpath = url ? url : "/bank-pending-deposits-data"
            axios.get(getpath).then(response => {
                let responsdata = response.data.data
                this.data = response.data
                if (responsdata.length > 0) {
                    responsdata.forEach(deposit => {
                        let pendingdeposit = [
                            deposit.encoded_offer_id,
                            deposit.reference_no,
                            deposit.offer.invited.deposit_request.user.organization.name,
                            deposit.offer.invited.deposit_request.currency + " " + this.addCommas(deposit.offered_amount),
                            deposit.offer.invited.deposit_request.product_name,
                            deposit.offer.invited.deposit_request.term_length_type == 'HISA' ? '-' : deposit.offer.invited.deposit_request.term_length + " " + this.capitalize(deposit.offer.invited.deposit_request.term_length_type),
                            deposit.offer.interest_rate_offer.toFixed(2) + "%",
                            this.formatDateToCustomFormat(deposit.offer.rate_held_until),
                            () => {
                                return ({ 'component': StartChat, 'attrs': { deposit_id: deposit.encoded_deposit_id, } });
                            },
                            () => {
                                return ({ 'component': Activate, 'attrs': { deposit_id: deposit.encoded_deposit_id, } });
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
        applyFilters(value) {
            let url = '/bank-pending-deposits-data?'
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
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }

        },
    }
}

</script>