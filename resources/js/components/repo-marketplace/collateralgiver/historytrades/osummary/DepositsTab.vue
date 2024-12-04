<template>
    <div style="height: 100%">
        <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="bank" :dontshow="['tradedate', 'settlement', 'investor']" products="products"
                @searching="search" from="reviewoffers">
            </FilterBox>
        </div>
        <div class="mt-3">
            <Table :columns="columns" no-data-title="No Trades In History" no-data-message="No Trades available"
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
import CustomInput from "../../../../shared/CustomInput";

import { userCan } from "../../../../../utils/GlobalUtils";
// import { formatTimestamp } from "../../../../../utils/dateUtils";
import ViewOffer from "../actions/ViewOffer"
import WithdrawRequest from "../actions/WithdrawRequest"
import EditRequest from "../actions/EditRequest"
import RequestSummary from "../actions/ViewRequestSummary"
import StartChat from '../actions/StartChat.vue'
import { addDaysToDate, repoProductName, addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, getBasketDetails } from "../../../../../utils/commonUtils";
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
        InviteCard,
        FilterBox,
        StartChat,
        ShowCG
    },
    created() {
    },
    props: ['offers', 'products', 'titlespan'],
    data() {
        let columnss = ['Trade ID', 'Collateral Taker', 'Repo Name', 'Collateral', 'Term Length', 'Investment', 'Rate', 'Status', 'Action'];
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
        clearFilters() {
            this.getData()
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/CG/get-deposits?type=history&${value}&search=${value}`;
            } else {
                url = `/trade/CG/get-deposits?type=history&search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/CG/get-deposits?type=history&${value}`;
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
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/CG/get-deposits?type=history";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    // console.log(response.data.data)
                    Object.values(response?.data.data).forEach((item) => {
                        let basketType = item?.c_g_offer.basket != null ? getBasketDetails(item?.c_g_offer?.basket) : getBasketDetails(item?.c_g_offer?.bi_colleteral, false)

                        table_data.push([
                            item?.encoded_id,
                            item?.deposit_reference_no,
                            () => {
                                return ({ 'component': ShowCG, 'attrs': { orgname: item?.c_t_organization?.name, organization: item?.c_t_organization } });
                            },
                            repoProductName(item?.c_g_offer?.offer_term_length, item?.c_g_offer?.offer_term_length_type, item?.c_g_offer?.product?.product_name),
                            basketType.name,
                            item?.c_g_offer?.offer_term_length + " " + this.capitalize(item?.c_g_offer?.offer_term_length_type),
                            item?.c_g_offer?.c_t_trade_request?.currency + ' ' + addCommasToANumber(item?.offered_amount) + ' (' + formatNumberAbbreviated(item?.offered_amount) + ')',
                            item.c_g_offer?.offer_interest_rate ? item.c_g_offer.offer_interest_rate.toFixed(2) + " %" : '-',
                            () => this.renderStatus(item?.deposit_status),
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