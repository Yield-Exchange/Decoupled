<template>
    <div style="height: 100%">
        <div class="ml-10 mt-3"
            style="width:100%;display: flex; flex-direction:row; justify-content:flex-end; gap: 10px; align-items: center">
            <FilterBox :filtered="filtered" :dontshow="['tradedate', 'investor']" @apply_filters="submitFilters"
                @clear_filters="clearFilters" filterType="bank" :products="products" @searching="search"
                from="reviewoffers">
            </FilterBox>
        </div>
        <div class="mt-3">
            <Table :columns="columns" no-data-title="No Offers" @selectedItems="selectedItems($event)"
                no-data-message="List will be pupulated once you create offers" :data="table_data" :has_action='false'
                :allselectable="false" :selectable="selectable" :selected_items="selected_offers" :actions='actions'
                :is_loading="is_loading" />
        </div>
        <div class="mt-3">
            <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
        </div>
    </div>
</template>

<script>
import Table from "../../../shared/Table.vue";
import Pagination from "../../../shared/Table/Pagination";
import FilterBox from "../../shared/filters/FilterBox";
import CustomInput from "../../../shared/CustomInput";
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'


import { userCan } from "../../../../utils/GlobalUtils";

import ViewOffer from "./actions/ViewOfferSelectable.vue"

import { addDaysToDate, addCommasToANumber, formatTimestamp, repoProductName, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, getBasketDetails, generateRandomValue } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/publishratesoffer/mutation-types';
import ShowCG from "../../shared/ShowCG.vue";
import CustomSubmit from "../../../auth/signup/shared/CustomSubmit.vue";
import TimerClock from "../../../campaigns/TimerClock.vue";

export default {
    mounted() {
        this.getData();
        if (this.selected_offers) {
            this.selected_items = this.selected_items
        }
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
        ActionMessage,
        TimerClock
    },
    created() {
    },
    props: ['products', 'titlespan', 'timezone'],
    data() {
        let columnss = ['Offer Id', 'Institution', 'Primary Basket', 'Basket ID', 'Currency', 'Minimum ', 'Maximum', 'Rate', 'Trade Date', 'Action'];
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
            is_loading: true,
            productFilter: '',
            filterString: '',
            selectable: true,
            selected_items: [],
            previous_selected_items: [],
            slectearequest: false,
            offers: null,
            single_offer: {
                'offer_uniqueid': null,
                'ct': null,
                'organization': null,
                'primary_basket_id': null,
                'primary_basket': null,
                'basket_id_no': null,
                'haircut': null,
                'rating': null,
                // new structure
                "collateralType": 'tri',
                "currency": "CAD",
                "min": null,
                "max": null,
                "product": 1,
                "term_length_type": 'Days',
                "term_length": null,
                "basket": null,
                "rate_valid_until": null,
                "convention_id": 1,
                "rate_type": "fixed",
                "rate_type_value": 0,
                "entered_rate": null,
                "spreadvalue": null,
                "interest_rate": null,
                "operator": "+",
                "collateral_id": null,
                "primaryBasket": null,
            }

        }
    },

    computed: {
        ...mapGetters('publishrateoffer', ['getSelectedFis', 'getFIS', 'selected_offers'])

    },
    methods: {
        selectedItems(value) {
            if (value) {
                if (Object.values(value).length > 0) {
                    // console.log("Has Values", value)
                    // console.log("selected Items", this.selected_items)
                    this.selected_items = value
                } else {
                    // console.log("Has no Values")
                    this.selected_items = []
                }
            }
            this.$store.commit('publishrateoffer/' + types.SET_SELECTED_OFFERS, this.selected_items);

        },
        async nextStep() {
            if (this.selected_items.length > 0) {
                this.$emit('nextStep')
            } else
                this.slectearequest = true
        },

        checkFormat(text) {
            const regex = /^[A-Za-z]{3}_[A-Za-z]_(.*)$/;
            return regex.test(text);
        },
        setOffers() {
            let offers = []
            let selected_cgs = []
            this.selected_items.forEach(item => {
                let found_item = this.offers.find(offer => offer.offer_reference_no == item)
                if (found_item) {
                    // const foundOrg = item?.c_g_trade_request_invited_c_t?.ct;
                    const randValue = generateRandomValue();
                    const singleOffer = {
                        ...this.single_offer, offer_uniqueid: randValue, ct: found_item?.c_g_trade_request_invited_c_t?.ct.id, organization: found_item.c_g_trade_request_invited_c_t?.ct, primaryBasket: found_item.basket.basket_details.trade_basket_type_id, rate_type: found_item?.rate_type,
                        term_length_type: this.capitalize(found_item?.offer_term_length_type), min: found_item?.offer_minimum_amount, max: found_item?.offer_maximum_amount,
                        term_length: found_item?.offer_term_length, interest_rate: found_item.offer_interest_rate.toFixed(2), convention_id: found_item?.interest_calculation_options_id,
                        basket: found_item?.basket ? !this.checkFormat(found_item.basket.basket_id) ? found_item.basket.id : '0' : null, spreadvalue: found_item?.rate_type != 'fixed' ? found_item.fixed_rate.toFixed(2) : null, entered_rate: found_item?.fixed_rate.toFixed(2), primary_basket: found_item?.basket?.basket_details?.trade_basket_type, rating: found_item?.basket?.basket_details?.rating, basket_id_no: item?.basket?.basket_id,
                        offer_id: item, rate_valid_until: found_item?.rate_valid_until.split(' ')[0]
                    };
                    offers.push(singleOffer)
                    if (found_item?.c_g_trade_request_invited_c_t?.ct?.id && !selected_cgs.includes(found_item.c_g_trade_request_invited_c_t?.ct.id)) {
                        selected_cgs.push(found_item.c_g_trade_request_invited_c_t?.ct.id);
                    }

                    // selected_cgs.push(found_item?.c_g?.id)
                }
                // offers.push(existingOffersMap.get(element));
            })
            // this.$store.commit('publishrateoffer/' + types.SET_SELECTED_OFFERS, this.selected_items);

            this.$store.commit('publishrateoffer/' + types.SET_SELECTED_FIS, selected_cgs);
            this.$store.commit('publishrateoffer/' + types.SET_OFFERS, offers);

        },
        prevStep() {
            this.$store.commit('publishrateoffer/' + types.SET_SELECTED_FIS, null);
            this.$store.commit('publishrateoffer/' + types.SET_OFFERS, null);
            this.$store.commit('publishrateoffer/' + types.SET_SELECTED_OFFERS, null);
            this.$emit('prevStep')

        },
        clearFilters() {
            this.getData()
        },
        renderTimerClock(time) {
            return ({ 'component': TimerClock, 'attrs': { timezone: JSON.parse(this.timezone), targetTime: time } });
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/market-place/CG/get-my-requests-matured-offers?search=${value}`;
            } else {
                url = `/trade/market-place/CG/get-my-requests-matured-offers?search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            // console.log(value, "all filters data");
            let url = `/trade/market-place/CG/get-my-requests-matured-offers?${value}`;
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
        renderView(value) {
            return ({ 'component': ViewOffer, 'attrs': { offer: value } });
        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/market-place/CG/get-my-requests-matured-offers";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    this.offers = response.data.data
                    // console.log(response.data.data)
                    Object.values(response?.data.data).forEach((item) => {
                        //     let basketType = item?.c_g_offer.basket != null ? getBasketDetails(item?.c_g_offer?.basket) : getBasketDetails(item?.c_g_offer?.bi_colleteral, false)
                        let orgnization = item.c_g_trade_request_invited_c_t.ct

                        table_data.push([
                            item?.offer_reference_no,
                            item?.offer_reference_no,
                            () => {
                                return ({ 'component': ShowCG, 'attrs': { orgname: orgnization?.name, organization: orgnization } });
                            },
                            item?.basket?.basket_details?.trade_basket_type?.basket_name,
                            item?.basket?.basket_id,
                            item?.currency ? item.currency : 'CAD',
                            addCommasToANumber(item?.offer_minimum_amount) + ` (${formatNumberAbbreviated(item?.offer_minimum_amount)})`,
                            addCommasToANumber(item?.offer_maximum_amount) + ` (${formatNumberAbbreviated(item?.offer_maximum_amount)})`,
                            item?.offer_interest_rate ? item.offer_interest_rate.toFixed(2) + " %" : '-',
                            item?.rate_valid_until ? () => this.formatTimestamp(item?.rate_valid_until,false) : '-',
                            () => this.renderView(item)

                        ]);
                    });
                    this_.table_data = table_data;
                    this.is_loading = false;
                    // console.log(this.table_data, 'Table Data')
                }).catch(error => {
                    this.is_loading = false;
                    // console.log("error > ", error);
                });

        },

        userCan(user, permission) {
            return userCan(user, permission);
        },
    },
    watch: {
        selected_items(newval, oldVal) {
            this.setOffers()
            // console.log(newval,"old val", oldVal)
        }

    },
}
</script>