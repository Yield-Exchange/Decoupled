<template>
    <div style="height: 100%">

        <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="depositor" :dontshow="['tradedate', 'settlement']"  :products="products" @searching="search" from="reviewoffers">
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
import Table from "../../../../shared/PostOffersTable";
import Pagination from "../../../../shared/Table/Pagination";
import FilterBox from "../../../shared/filters/FilterBox.vue";


import { userCan } from "../../../../../utils/GlobalUtils";
// import { formatTimestamp } from "../../../../../utils/dateUtils";
import ViewOffer from "../actions/ViewRequest.vue"
import WithdrawRequest from "../actions/WithdrawRequest"
import EditRequest from "../actions/EditRequest"
import RequestSummary from "../actions/ViewRequestSummary"
import StartChat from '../actions/StartChat.vue'
import { addDaysToDate, addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../../store/modules/postreq/mutation-types.js';
import ShowCG from "../../../shared/ShowCG.vue";
import InviteCard from '../../../../shared/CustomInvitedStatusBadge.vue';

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
        FilterBox,
        StartChat,
        InviteCard,
        ShowCG
    },
    created() {
    },
    props: ['offers', 'products', 'titlespan'],
    data() {
        let columnss = ['Request ID', 'Trade Date', 'Investment Amount', 'Term Length', 'Offers', 'Status', 'Action'];
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

    methods: {
        clearFilters() {
            this.getData()
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/CT/get-trade-requests?type=history&${value}&search=${value}`;
            } else {
                url = `/trade/CT/get-trade-requests?type=history&search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/CT/get-trade-requests?type=history&${value}`;
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
                url = `${url}&${this.filterString}&type=history&`
            } else {
                url = `${url}&type=history&`
            }
            this.getData(url);
        },
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
        },
        getData(url) {
            console.log("Get data")
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/CT/get-trade-requests?type=history";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    Object.values(response?.data.data).forEach((item) => {
                        table_data.push([
                            item?.encoded_id,
                            item?.reference_no,
                            formatTimestamp(item.trade_time, false),
                            item?.currency + ' ' + addCommasToANumber(item?.investment_amount),
                            item?.term_length + " " + this.capitalize(item?.term_length_type),
                            item?.total_offers ? item?.total_offers : '0',
                            () => this.renderStatus(item?.request_status),

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
        userCan(user, permission) {
            return userCan(user, permission);
        },
    }
}
</script>