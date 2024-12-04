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
                                    d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M28.3327 12.5H11.666V17.5H12.4993V26.6667C12.4993 27.1087 12.6749 27.5326 12.9875 27.8452C13.3001 28.1577 13.724 28.3333 14.166 28.3333H25.8327C26.2747 28.3333 26.6986 28.1577 27.0112 27.8452C27.3238 27.5326 27.4993 27.1087 27.4993 26.6667V17.5H28.3327V12.5ZM13.3327 14.1667H26.666V15.8333H13.3327V14.1667ZM25.8327 26.6667H14.166V17.5H25.8327V26.6667ZM17.4993 19.1667H22.4993C22.4993 19.6087 22.3238 20.0326 22.0112 20.3452C21.6986 20.6577 21.2747 20.8333 20.8327 20.8333H19.166C18.724 20.8333 18.3001 20.6577 17.9875 20.3452C17.6749 20.0326 17.4993 19.6087 17.4993 19.1667Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            My Baskets
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center w-100 flex-column mt-2">
            <div class="shadow-sm w-50 p-4 bg-white">
                <div class="d-flex justify-content-center">
                    <p class="basket-head">
                        Here is a list of all your baskets.
                        You can create or modify a basket.
                    </p>
                </div>
                <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
                    <FilterBox :dontshow="['ctype', 'issuer', 'status']" :filtered="filtered"
                        @apply_filters="submitFilters" @clear_filters="clearFilters" filterType="triparty_baskets"
                        @searching="search" from="triparty_baskets">
                    </FilterBox>
                </div>
                <div class="mt-3">
                    <Table :columns="columns" no-data-title="No Active Baskets"
                        no-data-message="Start by adding a new basket to populate data" :data="table_data"
                        :has_action='false' :actions='actions' :selectable="false" :is_loading="is_loading" />
                </div>
            </div>
            <div class="mt-3 w-50">
                <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
            </div>
            <div class="d-flex justify-content-end mt-3 w-50" v-if="userCan(userLoggedIn, 'bank/repos/create-basket')">
                <CustomSubmit @action="createBasket" title="Create New Basket" />
            </div>
        </div>
    </div>
</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import FilterBox from "./filters/FilterBox";
import ViewOffer from "./actions/ViewOffer"
import ArchiveBasket from "./actions/ArchiveBasket.vue";
import EditBasket from "./actions/EditBasket.vue";

import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/baskets/mutation-types';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import BasketStatus from "../../shared/BasketStatus.vue";
import { userCan } from "../../../../utils/GlobalUtils";
export default {

    beforeMount() {
        this.getBasketTypes()
    },
    mounted() {
        this.getData();
        },
    computed: {

    },
    components: {
        CustomSubmit,
        ArchiveBasket,
        ViewOffer,
        EditBasket,
        Table,
        Pagination,
        FilterBox,
        BasketStatus
    },
    created() {
    },
    props: ['userLoggedIn'],
    data() {
        let columnss = ['Basket Type', 'Currency', 'Rating', 'Active Tri-Parties', 'Action  '];
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
        userCan(a, b) {
            return userCan(a, b)
        },
        renderView(status) {
            return ({ 'component': ViewOffer, 'attrs': { actionId: status } });
        },
        getBasketTypes() {
            axios.get('/common/trade/get-basket-types').then(response => {
                let baskets = response.data
                // this.baskets = baskets.length > 0 ? baskets : []
                this.$store.commit('basket/' + types.SET_TRI_COLLATERAL, baskets)
            })
        },
        createBasket() {
            window.location.href = "/repos/create-basket?from=triparty"
        },
        renderStatus(status) {
            return ({ 'component': BasketStatus, 'attrs': { status: status } });
        },
        clearFilters() {
            this.getData()
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/CG/get-baskets?${this.filterString}&search=${value}&counterPartyStatus=ACTIVE&is_dummy=0`;
            } else {
                url = `/trade/CG/get-baskets?search=${value}&counterPartyStatus=ACTIVE&is_dummy=0`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            let url = `/trade/CG/get-baskets?&${value}&counterPartyStatus=ACTIVE&is_dummy=0`;
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
                url = `${url}&${this.filterString}&counterPartyStatus=ACTIVE&is_dummy=0`
            } else {
                url = `${url}&counterPartyStatus=ACTIVE&is_dummy=0`
            }
            this.getData(url);
        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/trade/CG/get-baskets?counterPartyStatus=ACTIVE&is_dummy=0";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    Object.values(response?.data).forEach((item) => {
                        table_data.push([
                            item?.encoded_id,
                            item?.trade_basket_type?.basket_name,
                            item?.currency,
                            item?.rating,
                            item?.trade_tri_basket_third_party.length,
                            // () => this.renderStatus(item?.trade_basket_type?.disabled == 0 ? 'ACTIVE' : 'pending'),
                            () => this.renderView(item?.encoded_id),
                        ]);
                    });
                    this.is_loading = false;
                    this_.table_data = table_data
                }).catch(error => {
                    this.is_loading = false;
                });

        },
        userCan(user, permission) {
            return userCan(user, permission);
        },
    }
}
</script>

<style>
.basket-head {
    color: #252525;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
}
</style>