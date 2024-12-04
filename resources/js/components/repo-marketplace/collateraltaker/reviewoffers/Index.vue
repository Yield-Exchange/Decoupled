<template>
    <div style="height: 100%">
        <div
            style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
            <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                <div style="display: flex; flex-direction: column;">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <img src="/assets/dashboard/icons/review-offers.svg" />
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            Review Offers
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="depositor" :dontshow="['rate', 'tradedate']" :products="products" @searching="search"
                from="reviewoffers">
            </FilterBox>
        </div>
        <div class="mt-3">
            <Table :columns="columns" no-data-title="No Offers" no-data-message=" No offers available for review"
                :data="table_data" :has_action='true' :actions='actions' :selectable="false" :is_loading="is_loading" />
        </div>
        <div class="mt-3">
            <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
        </div>

    </div>
</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import FilterBox from "../../shared/filters/FilterBox.vue";
import CustomInput from "../../../shared/CustomInput";

import { userCan } from "../../../../utils/GlobalUtils";
// import { formatTimestamp } from "../../../../utils/dateUtils";
import ViewOffer from "./actions/ViewOffer"
import WithdrawRequest from "./actions/WithdrawRequest"
import EditRequest from "./actions/EditRequest"
import RequestSummary from "./actions/ViewRequestSummary"
import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, calculateSettlementLabel } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/postreq/mutation-types.js';
export default {
    beforeMount() {
        this.getData();
    },
    computed: {

    },
    components: {
        EditRequest,
        RequestSummary,
        WithdrawRequest,
        ViewOffer,
        Table,
        Pagination,
        FilterBox
    },
    created() {
    },
    props: ['offers', 'products', 'titlespan', 'userLoggedIn'],
    data() {
        let columnss = ['Request ID', 'Settlement Date', 'Investment Amount', 'Term Length', 'Day Count', 'Offers', 'Highest', 'Lowest', 'Action'];
        let act = [
            {
                name: "View Offers",
                component: ViewOffer
            }
        ];
        if (userCan(this.userLoggedIn, 'depositor/repos/edit-request')) {
            act.splice(1, 0,
                {
                    name: "Edit Request",
                    component: EditRequest
                })
        }
        if (userCan(this.userLoggedIn, 'depositor/repos/withdraw-request')) {
            act.splice(1, 0,
                {
                    name: "Withdraw Request",
                    component: WithdrawRequest
                })
        }
        return {
            details: null,
            existing: null,
            actions: act,
            columns: columnss,
            is_modal: false,
            table_data: [],
            links: [],
            data: [],
            term_length_filter: null,
            product_type_filter: null,
            filtered: [],
            is_loading: false,
            productFilter: '',
            filterString: ''


        }
    },
    watch: {

    },
    methods: {
        clearFilters() {
            this.getData()
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/CT/get-trade-requests?type=new&${value}&search=${value}`;
            } else {
                url = `/trade/CT/get-trade-requests?type=new&search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/CT/get-trade-requests?type=new&${value}`;
            this.getData(url);
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
        formatLabel(str) {
            str = str.replace(/\?/g, '');
            return str;
        },
        getPageData(url) {
            if (this.filterString != '') {
                this.formatLabel(this.filterString);
                url = `${url}&${this.filterString}&type=new&`
            } else {
                url = `${url}&type=new&`
            }
            this.getData(url);

        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/CT/get-trade-requests?type=new";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    Object.values(response?.data.data).forEach((item) => {
                        table_data.push([
                            item?.encoded_id,
                            item?.reference_no,
                            item?.settlement_date ? formatTimestamp(item?.settlement_date, false) : '-',
                            // formatTimestamp(item.trade_time, false),
                            item?.currency + ' ' + addCommasToANumber(item?.investment_amount) + ` (${formatNumberAbbreviated(item?.investment_amount)})`,
                            item?.term_length + " " + this.capitalize(item?.term_length_type),
                            item?.interest_calculation_option ? item?.interest_calculation_option?.label : '-',
                            item?.total_offers ? item?.total_offers : '0',
                            item.max_offer_interest_rate ? item.max_offer_interest_rate.toFixed(2) + "%" : '-',
                            item.min_offer_interest_rate ? item.min_offer_interest_rate.toFixed(2) + "%" : '-',
                            // item.max_offer_interest_rate,
                        ]);
                    });
                    this.is_loading = false;
                    this_.table_data = table_data;
                    // console.log(this.table_data, 'Table Data')
                }).catch(error => {
                    this.is_loading = false;
                    // console.log("error > ", error);
                });

        },
    }
}
</script>