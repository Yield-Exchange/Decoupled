<template>
    <div style="height: 100%">
        <div class="ml-10 mt-3"
            style="width:100%;display: flex; flex-direction:row; justify-content:flex-end; gap: 10px; align-items: center">
            <FilterBox :filtered="filtered" :dontshow="['tradedate', 'investor']" @apply_filters="submitFilters"
                @clear_filters="clearFilters" filterType="bank" @searching="search" from="reviewoffers">
            </FilterBox>
            <div style="white-space: nowrap;display: flex;padding: 5px 30px;justify-content: center;height: 40px; color: white;font-weight: 800; align-items: center;border-radius: 32px;background:  #5063F4;"
                @click="$emit('nextStep')">Publish New Offer</div>
        </div>
        <div class="mt-3">
            <Table :columns="columns" no-data-title="No Offers"
                no-data-message="List will be pupulated once you create offers" :data="table_data"
                :has_action='has_action' :actions='actions' :selectable="false" :is_loading="is_loading" />
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

import ViewOffer from "./actions/ViewOffer"
import CancelOffer from "./actions/CancelOffer"
import EditOffer from "./actions/EditOffer"
import TimerClock from "../../../campaigns/TimerClock.vue";

import { addDaysToDate, addCommasToANumber, formatTimestamp, repoProductName, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, getBasketDetails } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/postreq/mutation-types.js';
import * as authtypes from '../../../../store/modules/auth/mutation-types.js';
import ShowCG from "../../shared/ShowCG.vue";
import CustomSubmit from "../../../auth/signup/shared/CustomSubmit.vue";
import CounterStatus from "../../shared/CounterStatus.vue";
export default {
    mounted() {
        this.getData();
    },
    computed: {

    },
    components: {
        CustomSubmit,
        ViewOffer,
        Table,
        Pagination,
        FilterBox,
        ShowCG,
        TimerClock,
        CounterStatus
    },
    created() {
    },
    props: ['offers', 'timezone', 'titlespan', 'userLoggedIn'],
    data() {
        this.$store.commit('auth/' + authtypes.ADD_LOGGEDINUSER, this.userLoggedIn);
        let columnss = ['Offer Id', 'Buyer', 'Basket', 'Term Length', 'Min-Max', 'Rate', 'Counter', 'Counter', 'Days To Expiry', 'Action'];
        let act = [
            {
                name: "View Offers",
                component: ViewOffer
            },
            {
                name: "Cancel Offer",
                component: CancelOffer
            },
            {
                name: "Edit Offer",
                component: EditOffer
            }

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
            filterString: '',
            has_action: true
        }
    },
    watch: {

    },
    computed: {
        ...mapGetters('publishrateoffer', ['getSelectedFis', 'getFIS'])

    },
    methods: {
        clearFilters() {
            this.getData()
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/market-place/CG/get-my-requests-offers?search=${value}`;
            } else {
                url = `/trade/market-place/CG/get-my-requests-offers?search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/market-place/CG/get-my-requests-offers?${value}`;
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
                url = `${url}&${this.filterString}&type=pending&`
            } else {
                url = `${url}&type=pending&`
            }
            this.getData(url);
        },
        renderCounterStatus(status, checkFor, rateValue) {
            return ({ 'component': CounterStatus, 'attrs': { status: status, checkFor: checkFor, rateValue: rateValue } });
        },
        renderView(value) {
            return ({ 'component': ViewOffer, 'attrs': { offer: value, userLoggedIn: this.userLoggedIn } });
        },
        renderTimerClock(time) {
            return ({ 'component': TimerClock, 'attrs': { timezone: JSON.parse(this.timezone), targetTime: time } });
        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/market-place/CG/get-my-requests-offers";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    // console.log(response.data.data)
                    Object.values(response?.data.data).forEach((item) => {
                        //     let basketType = item?.c_g_offer.basket != null ? getBasketDetails(item?.c_g_offer?.basket) : getBasketDetails(item?.c_g_offer?.bi_colleteral, false)
                        let orgnization = item.c_g_trade_request_invited_c_t.ct
                        table_data.push([
                            item?.encoded_id,
                            item?.offer_reference_no,
                            () => {
                                return ({ 'component': ShowCG, 'attrs': { orgname: orgnization?.name, organization: orgnization } });
                            },
                            item?.basket?.basket_details?.trade_basket_type?.basket_name,
                            // repoProductName(item?.c_g_offer?.offer_term_length, item?.c_g_offer?.offer_term_length_type, item?.c_g_offer?.product?.product_name),
                            item?.offer_term_length + " " + this.capitalize(item?.offer_term_length_type),
                            // item?.offer_interest_rate ? item.offer_interest_rate.toFixed(2) + " %" : '-',
                            (item?.currency ? item.currency : 'CAD') + ` ${formatNumberAbbreviated(item?.offer_minimum_amount)} - ${formatNumberAbbreviated(item?.offer_maximum_amount)}`,
                            // addCommasToANumber(item?.offer_minimum_amount) + ` (${formatNumberAbbreviated(item?.offer_minimum_amount)})`,
                            // addCommasToANumber(item?.offer_maximum_amount) + ` (${formatNumberAbbreviated(item?.offer_maximum_amount)})`,
                            (item?.offer_interest_rate && item?.counters.length > 0) ? () => this.renderCounterStatus(item.counters[0].status, 'rate', item.offer_interest_rate.toFixed(2) + '%') : () => this.renderCounterStatus('accepted', 'rate', item.offer_interest_rate.toFixed(2) + '%'),
                            item?.counters.length > 0 ? () => this.renderCounterStatus(item.counters[0].status, 'counterrate', item.counters[0].offer_interest_rate.toFixed(2) + '%') : '-',
                            item?.counters.length > 0 ? () => this.renderCounterStatus(item.counters[0].status, 'counterstatus', item.offer_interest_rate.toFixed(2) + '%') : '-',
                            item?.rate_valid_until ? () => this.renderTimerClock(item?.rate_valid_until) : '-'
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