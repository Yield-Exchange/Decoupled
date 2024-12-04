<template>
    <div style="height: 100%">
        <div
            style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
            <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                <div style="display: flex; flex-direction: column;">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58801 14.9165C6.67383 10.2875 10.2882 6.6731 14.9173 5.58727C18.2603 4.8031 21.7395 4.8031 25.0825 5.58727C29.7115 6.6731 33.3259 10.2875 34.4118 14.9165C35.1959 18.2596 35.1959 21.7387 34.4118 25.0818C33.3259 29.7108 29.7115 33.3252 25.0825 34.411C21.7395 35.1952 18.2603 35.1952 14.9173 34.411C10.2882 33.3252 6.67383 29.7108 5.58801 25.0818C4.80383 21.7387 4.80383 18.2596 5.58801 14.9165Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M25.3462 18.2912H21.8187V14.7637C21.8187 14.296 21.6329 13.8474 21.3022 13.5166C20.9714 13.1858 20.5228 13 20.055 13C19.5872 13 19.1386 13.1858 18.8078 13.5166C18.4771 13.8474 18.2912 14.296 18.2912 14.7637L18.3539 18.2912H14.7637C14.296 18.2912 13.8474 18.4771 13.5166 18.8078C13.1858 19.1386 13 19.5872 13 20.055C13 20.5228 13.1858 20.9714 13.5166 21.3022C13.8474 21.6329 14.296 21.8187 14.7637 21.8187L18.3539 21.7561L18.2912 25.3462C18.2912 25.814 18.4771 26.2626 18.8078 26.5934C19.1386 26.9242 19.5872 27.11 20.055 27.11C20.5228 27.11 20.9714 26.9242 21.3022 26.5934C21.6329 26.2626 21.8187 25.814 21.8187 25.3462V21.7561L25.3462 21.8187C25.814 21.8187 26.2626 21.6329 26.5934 21.3022C26.9242 20.9714 27.11 20.5228 27.11 20.055C27.11 19.5872 26.9242 19.1386 26.5934 18.8078C26.2626 18.4771 25.814 18.2912 25.3462 18.2912Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            New Requests
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
            <FilterBox :dontshow="['rate', 'tradedate']" :filtered="filtered" @apply_filters="submitFilters"
                @clear_filters="clearFilters" filterType="bank" :products="products" @searching="search"
                from="reviewoffers">
            </FilterBox>
        </div>
        <div class="mt-3">
            <Table :columns="columns" no-data-title="No Active Request"
                no-data-message="Table will be populated once the colateral takers post them" :data="table_data"
                :has_action='false' :actions='actions' :selectable="false" :is_loading="is_loading" />
        </div>
        <div class="mt-3">
            <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
        </div>

    </div>
</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import FilterBox from "../../shared/filters/FilterBox";
import CustomInput from "../../../shared/CustomInput";

import { userCan } from "../../../../utils/GlobalUtils";
// import { formatTimestamp } from "../../../../utils/dateUtils";
import ViewOffer from "./actions/ViewOffer"
import WithdrawRequest from "./actions/WithdrawRequest"
import EditRequest from "./actions/EditRequest"
import RequestSummary from "./actions/ViewRequestSummary"
import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, calculateSettlementLabel } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import ShowCG from "../../shared/ShowCG.vue";
import * as types from '../../../../store/modules/postreq/mutation-types.js';
export default {
    mounted() {
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
        FilterBox, ShowCG
    },
    created() {
    },
    props: ['offers', 'products', 'titlespan'],
    data() {
        let columnss = ['Request ID', 'Investor', 'Investment ', 'Term Length', 'Day Count', 'Settlement Date', 'Action'];
        let act = [
            {
                name: "View Offers",
                component: ViewOffer
            },
            // {
            //     name: "Withdraw Request",
            //     component: WithdrawRequest
            // },
            // {
            //     name: "Edit Request",
            //     component: EditRequest
            // }
        ];
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
        renderOrgData(org) {
            return ({ 'component': ShowCG, 'attrs': { orgname: org.name, organization: org } });
        },
        viewOffer(deposit_id) {
            return ({ 'component': ViewOffer, 'attrs': { deposit_id: deposit_id } });
        },
        clearFilters() {
            this.getData()
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/CG/get-trade-requests?type=new&${value}&search=${value}`;
            } else {
                url = `/trade/CG/get-trade-requests?type=new&search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/CG/get-trade-requests?type=new&${value}`;
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
                url = `${url}&${this.filterString}&type=new`
            } else {
                url = `${url}&type=new`
            }
            this.getData(url);
        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/CG/get-trade-requests?type=new";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    Object.values(response?.data.data).forEach((item) => {
                        table_data.push([
                            item?.encoded_id,
                            item?.reference_no,
                            () => this.renderOrgData(item?.inviter),
                            item?.currency + ' ' + addCommasToANumber(item?.investment_amount)+` (${formatNumberAbbreviated(item.investment_amount)})`,
                            item?.term_length + " " + this.capitalize(item?.term_length_type),
                            item?.interest_calculation_option ? item.interest_calculation_option.label : '-',
                            // formatTimestamp(item.trade_time, false),
                            item?.settlement_date ? formatTimestamp(item?.settlement_date, false) : '-',
                            // item?.trade_allowed_settlement_period ? calculateSettlementLabel(item?.trade_allowed_settlement_period) : '-',
                            () => this.viewOffer(item?.encoded_id),
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
        userCan(user, permission) {
            return userCan(user, permission);
        },
    }
}
</script>