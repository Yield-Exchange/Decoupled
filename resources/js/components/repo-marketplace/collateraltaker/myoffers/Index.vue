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
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.3261 10.2884 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.3261 29.7117 29.7117 33.3261 25.0826 34.4119C21.7396 35.1961 18.2604 35.1961 14.9174 34.4119C10.2884 33.3261 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M29.4206 11.0648C29.1818 10.9104 28.9087 10.8169 28.6254 10.7927C28.342 10.7685 28.0571 10.8142 27.7956 10.9259L19.2261 14.3009C19.0232 14.3835 18.8063 14.426 18.5872 14.4259H13.1011C12.6407 14.4259 12.1991 14.6088 11.8735 14.9344C11.5479 15.26 11.365 15.7016 11.365 16.162V16.3009H9.62891V20.4676H11.365V20.6482C11.3759 21.1013 11.5636 21.5323 11.888 21.8489C12.2124 22.1656 12.6478 22.3427 13.1011 22.3426L15.1845 26.7593C15.3255 27.0567 15.5476 27.3083 15.8252 27.4852C16.1029 27.6621 16.4247 27.7571 16.7539 27.7593H17.6289C18.0869 27.7556 18.525 27.5711 18.8476 27.2459C19.1701 26.9207 19.3511 26.4812 19.3511 26.0232V22.5093L27.7956 25.8843C28.0033 25.9669 28.2248 26.0093 28.4483 26.0093C28.7952 26.0037 29.1329 25.8975 29.4206 25.7037C29.649 25.5495 29.8374 25.3431 29.9703 25.1015C30.1031 24.86 30.1765 24.5903 30.1845 24.3148V12.4954C30.1832 12.2128 30.1129 11.9347 29.9798 11.6854C29.8467 11.4361 29.6547 11.2231 29.4206 11.0648ZM17.615 16.162V20.6482H13.1011V16.162H17.615ZM17.615 26.0232H16.74L15.0317 22.3426H17.615V26.0232ZM19.865 20.8565C19.7001 20.7722 19.5282 20.7025 19.3511 20.6482V16.0648C19.5265 16.0286 19.6984 15.9775 19.865 15.912L28.4483 12.4954V24.2732L19.865 20.8565ZM30.2261 16.6482V20.1204C30.6866 20.1204 31.1282 19.9375 31.4537 19.6119C31.7793 19.2863 31.9622 18.8447 31.9622 18.3843C31.9622 17.9238 31.7793 17.4822 31.4537 17.1567C31.1282 16.8311 30.6866 16.6482 30.2261 16.6482Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            Repos
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="depositor" :dontshow="['settlement']" :products="products" @searching="search"
                from="deposit">
            </FilterBox>
            <div style="display: flex;gap: 10px;padding-left: 10px;padding-top: 5px">
                <div v-bind:class="offers_view_type === 'list' ? 'activeView' : 'inActiveView'"
                    :style="'height: 40px;padding: 10px; border-radius: 8px; justify-content: flex-start; align-items: center; gap: 9px; display: flex; cursor: pointer'"
                    @click="updateOfferView('list')">
                    <img v-if="(offers_view_type === 'list')" src="/assets/dashboard/icons/Vector (3).svg" />
                    <img v-if="(offers_view_type === 'tile')" src="/assets/dashboard/icons/Vector (3)-grey.svg" />
                    <div style="font-size: 16px; font-weight: 700; text-transform: capitalize; white-space:nowrap">
                        List View</div>
                </div>
                <div v-bind:class="offers_view_type === 'tile' ? 'activeView' : 'inActiveView'"
                    style="height: 40px;padding: 10px; border-radius: 8px; justify-content: flex-start; align-items: center; gap: 9px; display: flex; cursor: pointer"
                    @click="updateOfferView('tile')">
                    <img v-if="(offers_view_type === 'tile')" src="/assets/dashboard/icons/Vector (4).svg" />
                    <img v-if="(offers_view_type === 'list')" src="/assets/dashboard/icons/Vector (4)-grey.svg" />
                    <div style="font-size: 16px; font-weight: 700; text-transform: capitalize; white-space: nowrap">
                        Tile View</div>
                </div>
            </div>
        </div>
        <template v-if="offers_view_type == 'list'">

            <div class="mt-3">
                <Table :columns="columns" no-data-title="No Offers" no-data-message=" No offers available for review"
                    :data="table_data" :has_action='false' :actions='actions' :selectable="false"
                    :is_loading="is_loading" />
            </div>

        </template>
        <template v-else>
            <div class="mt-3" v-if="offers" ref="tileContainer">
                <TileView v-if="offers.length > 0" :data="offers"></TileView>
                <NoData v-else title="No Offers" message=" No offers available for review"></NoData>
            </div>
        </template>
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

import TileView from './TileView.vue'

import { userCan } from "../../../../utils/GlobalUtils";
// import { formatTimestamp } from "../../../../utils/dateUtils";
import ViewOffer from "./actions/ViewOffer"
import WithdrawRequest from "./actions/WithdrawRequest"
import EditRequest from "./actions/EditRequest"
import RequestSummary from "./actions/ViewRequestSummary"
import StartChat from './actions/StartChat.vue'
import { repoProductName, addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, getBasketDetails } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/postreq/mutation-types.js';
import ShowCG from "../../shared/ShowCG.vue";
import NoData from "../../../shared/Table/NoData.vue";
import TimerClock from "../../../campaigns/TimerClock.vue";
export default {
    beforeMount() {
        this.getData();
    },
    mounted() {
        // this.setContainerWidth();

        // Add a resize event listener to update the width on window resize
        // window.addEventListener('resize', this.setContainerWidth);
    },
    beforeDestroy() {
        // Clean up the event listener when the component is destroyed
        window.removeEventListener('resize', this.setContainerWidth);
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
        TileView,
        FilterBox,
        StartChat,
        ShowCG,
        NoData,
        TimerClock
    },
    created() {
    },
    props: ['products', 'formattedtimezone'],
    data() {
        let columnss = ['Repo ID', 'Seller', 'Basket', 'Term Length', 'Rate', 'Currency', 'Minimum', 'Maximum', 'Days To Expiry', 'Action'];
        let act = [
            {
                name: "View Offers",
                component: ViewOffer
            },
        ];
        return {
            details: null,
            existing: null,
            actions: act,
            columns: columnss,
            is_modal: false,
            offers_view_type: 'tile',
            table_data: [],
            links: [],
            data: [],
            term_length_filter: null,
            product_type_filter: null,
            offers: null,
            filtered: [],
            is_loading: false,
            productFilter: '',
            filterString: '',
            containerWidth: 0
        }
    },
    watch: {
        // offers() {
        //     this.setContainerWidth()
        // }
    },
    // my offers

    methods: {
        updateOfferView(value) {
            this.offers_view_type = value
        },
        setContainerWidth() {
            if (this.offers) {
                // Get the container's width using ref and update the data property
                const container = this.$refs.tileContainer;
                if (this.$refs.tileContainer) {
                    let padding = 48
                    let gap = 17.500
                    let item_width = padding + gap

                    this.containerWidth = container.clientWidth;

                    console.log(this.containerWidth / (239.500 + item_width), "Conatiner")
                }
            }
        },
        clearFilters() {
            this.getData()
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
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/market-place/CT/get-my-requests-offers?${value}`;
            this.getData(url);
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/market-place/CT/get-my-requests-offers&search=${value}`;
            } else {
                url = `/trade/market-place/CT/get-my-requests-offers&search=${value}`
            }
            this.getData(url);
        },
        renderView(value) {
            return ({ 'component': ViewOffer, 'attrs': { actionId: value } });
        },
        getPageData(url) {
            if (this.filterString != '') {
                this.formatLabel(this.filterString);
                url = `${url}&${this.filterString}`
            } else {
                url = `${url}`
            }
            this.getData(url);
        },
        renderTimerClock(time) {
            return ({ 'component': TimerClock, 'attrs': { timezone: JSON.parse(this.formattedtimezone), targetTime: time } });
        },
        getData(url) {
            this.offers = null
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/market-place/CT/get-my-requests-offers";
            // url = url ? url : "https://api.npoint.io/844419591d4dedef666d";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    this.offers = response.data.data
                    Object.values(response?.data.data).forEach((item) => {
                        // let basketType = item?.c_g_offer.basket != null ? getBasketDetails(item?.c_g_offer?.basket) : getBasketDetails(item?.c_g_offer?.bi_colleteral, false)
                        table_data.push([
                            item?.encoded_id,
                            item?.offer_reference_no,
                            () => {
                                return ({ 'component': ShowCG, 'attrs': { orgname: item?.c_g?.name, organization: item?.c_g } });
                            },
                            item?.basket?.basket_details?.trade_basket_type?.basket_name,
                            item?.offer_term_length + " " + this.capitalize(item?.offer_term_length_type),
                            item?.offer_interest_rate ? item.offer_interest_rate.toFixed(2) + " %" : '-',
                            item?.currency ? item.currency : 'CAD',
                            addCommasToANumber(item?.offer_minimum_amount) + ` (${formatNumberAbbreviated(item?.offer_minimum_amount)})`,
                            addCommasToANumber(item?.offer_maximum_amount) + ` (${formatNumberAbbreviated(item?.offer_maximum_amount)})`,
                            item?.rate_valid_until ? () => this.renderTimerClock(item?.rate_valid_until) : '-',
                            () => this.renderView(item?.encoded_id)
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


<style scoped>
.activeView {
    border: 0.50px #5063F4 solid;
    color: #050505;
    color: #5063F4;
}

.inActiveView {

    /* background: white; */
    border: 0.50px #9CA1AA solid;
    /* color: #5063F4; */
    color: #9CA1AA;
}

.inActiveView:hover {
    border: 0.50px #5063F4 solid;
    color: #050505;
    color: #5063F4;
}
</style>