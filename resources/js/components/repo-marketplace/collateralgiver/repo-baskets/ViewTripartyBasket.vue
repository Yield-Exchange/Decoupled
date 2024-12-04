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
                                    d="M26.2949 28.3217H14.7049C14.3219 28.3241 13.9427 28.2453 13.5923 28.0906C13.2419 27.9359 12.9282 27.7088 12.6718 27.4243C12.4154 27.1397 12.2222 26.8041 12.1048 26.4395C11.9874 26.0749 11.9485 25.6896 11.9906 25.3089L12.8999 17.3153C12.9368 16.9818 13.0959 16.6738 13.3465 16.4508C13.5972 16.2277 13.9215 16.1054 14.257 16.1074H26.7427C27.0782 16.1054 27.4026 16.2277 27.6532 16.4508C27.9039 16.6738 28.063 16.9818 28.0999 17.3153L28.982 25.3089C29.0239 25.6873 28.9857 26.0703 28.8699 26.433C28.7541 26.7957 28.5633 27.13 28.3099 27.4141C28.0564 27.6983 27.7461 27.9259 27.3989 28.0823C27.0518 28.2387 26.6756 28.3202 26.2949 28.3217Z"
                                    stroke="#5063F4" stroke-width="1.35714" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M15.0713 16.1073C15.0713 14.6675 15.6432 13.2868 16.6613 12.2687C17.6793 11.2506 19.0601 10.6787 20.4999 10.6787C21.9396 10.6787 23.3204 11.2506 24.3384 12.2687C25.3565 13.2868 25.9284 14.6675 25.9284 16.1073M17.107 20.1787H23.8927"
                                    stroke="#5063F4" stroke-width="1.35714" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            {{ basket_type_data?.trade_basket_type?.basket_name }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center w-100 flex-column mt-2">
            <div class="shadow-sm w-50 p-4 bg-white">
                <div class="d-flex justify-content-center flex-column">
                    <p class="basket-head">
                        Manage Your Collateral Baskets
                    </p>
                </div>
                <div class="d-flex justify-content-start px-3">
                    <p class="basket-head-description p-0 m-0">
                        From this screen, you can edit, archive or activate your collateral baskets.
                    </p>
                </div>
                <div>
                    <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                        <ViewCard title="Basket Type" :desc="basket_type_data?.trade_basket_type?.basket_name" />
                        <ViewCard title="Currency" :desc="basket_type_data?.currency" />
                        <ViewCard title="Rating" :desc="basket_type_data?.rating" />
                    </div>
                </div>
                <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
                    <FilterBox :attentioncount="attentioncount" @filterPending="filterPending"
                        :pendingcount="pendingcount" :dontshow="['ctype', 'btype', 'rating', 'issuer']" from="tribasket"
                        :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                        filterType="triparty_baskets" @searching="search">
                    </FilterBox>
                </div>
                <div class="mt-3">
                    <Table :columns="columns" no-data-title="No Active Baskets"
                        no-data-message="Start by adding a new basket to populate data" :data="table_data"
                        :has_action='has_action' :actions='actions' :selectable="false" :is_loading="is_loading" />
                </div>
            </div>
            <div class="mt-3 w-50">
                <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
            </div>
            <div class="d-flex justify-content-between mt-3 w-50">
                <CustomSubmit :previous="true" @action="goBack" title="Previous" />
                <CustomSubmit v-if="has_action" @action="morecps = true" title="Add more counterparties" />
            </div>
        </div>
        <addCollatearalBasket v-if="morecps" :show="morecps" :basket="basket_type_data" @closeModal="morecps = false" />
    </div>
</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import FilterBox from "./filters/FilterBox.vue";
import ViewOffer from "./actions/ViewOffer"
import ArchiveBasket from "./actions/ArchiveBasket.vue";
import ActivateBasket from "./actions/ActivateBasket.vue";
import EditBasket from "./actions/EditBasket.vue";
import ViewCard from "../../../shared/ViewCard.vue";
import addCollatearalBasket from "./actions/addCollatearalBasket.vue";

import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/baskets/mutation-types';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import BasketStatus from "../../shared/BasketStatus.vue";
import { userCan } from "../../../../utils/GlobalUtils";
export default {
    props: ['userLoggedIn'],
    beforeMount() {
        this.has_action = userCan(this.userLoggedIn, 'bank/repos/edit-basket')
        if (this.has_action)
            this.columns = ['#', 'Basket ID', 'Counterparty', 'Status', 'Action'];
        else
            this.columns = ['#', 'Basket ID', 'Counterparty', 'Status'];

    },
    mounted() {
        this.getUrlPArams();
        this.url = '/trade/CG/get-basket?basket=' + this.basketId + '&is_dummy=0'
        this.getData()

    },
    computed: {

    },
    components: {
        addCollatearalBasket,
        CustomSubmit,
        ArchiveBasket,
        ActivateBasket,
        ViewOffer,
        ViewCard,
        EditBasket,
        Table,
        Pagination,
        FilterBox,
        BasketStatus
    },
    created() {
    },
    data() {
        let columnss = ['#', 'Basket ID', 'Counterparty', 'Status', 'Action'];
        let actions = [
            {
                name: "Edit",
                component: EditBasket
            },
            {
                name: "Archive",
                component: ArchiveBasket
            }
        ]
        return {
            pendingcount: null,
            attentioncount: null,
            morecps: false,
            has_action: false,
            details: null,
            existing: null,
            data: null,
            actions: actions,
            columns: null,
            is_modal: false,
            table_data: [],
            links: [],
            triparty_baskets: [],
            basket_type_data: null,
            term_length_filter: null,
            product_type_filter: null,
            filtered: [],
            is_loading: false,
            productFilter: '',
            filterString: '',
            basketId: '',
            url: null,
        }
    },
    watch: {

    },
    methods: {
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            const numberPart = parts[parts.length - 1];
            this.basketId = numberPart
        },
        goBack() {
            window.location.href = "/repos/view-baskets"
        },
        createBasket() {
            window.location.href = "/repos/create-basket"
        },
        renderStatus(status) {
            return ({ 'component': BasketStatus, 'attrs': { status: status } });
        },
        clearFilters() {
            this.getData()
        },
        filterPending(value) {
            let url = '';
            url = `${this.url}&counterPartyStatus=${value}`;
            this.getData(url);
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `${this.url}&${this.filterString}&search=${value}`;
            } else {
                url = `${this.url}&search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            let url = `${this.url}&${value}`;
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
                url = `${url}&${this.filterString}`
            } else {
                url = `${url}`
            }
            this.getData(url);
        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : this.url;
            axios.get(url).then(response => {

                this.basket_type_data = response.data
                this.triparty_baskets = response.data.trade_tri_basket_third_party
                let table_data = [];
                this_.data = response?.data;
                this.$store.commit('basket/' + types.ADD_TRI_COLLATERALS, response.data);

                let pc = 0
                let ac = 0
                let archived_data = []
                Object.values(this.triparty_baskets).forEach((item, count) => {

                    if (item?.basket_status == 'PENDING') {
                        pc += 1
                    }
                    if (item?.basket_status == 'ATTENTION') {
                        ac += 1
                    }
                    this.pendingcount = pc
                    this.attentioncount = ac
                    if (item?.basket_status == 'ARCHIVED') {
                        archived_data.push([
                            item?.encoded_id,
                            count + 1,
                            item?.basket_id,
                            item?.counter_party_details?.name,
                            // item?.basket_status,
                            () => this.renderStatus(item?.basket_status),
                        ]);
                    } else {
                        table_data.push([
                            item?.encoded_id,
                            count + 1,
                            item?.basket_id,
                            item?.counter_party_details?.name,
                            () => this.renderStatus(item?.basket_status),
                        ]);
                    }
                });
                this.is_loading = false;
                if (archived_data.length > 0) {
                    table_data.push(...archived_data)
                    table_data.forEach((item, index) => {

                        item[1] = index + 1

                    })
                }
                this_.table_data = table_data
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

.basket-head-description {
    color: #252525;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
}
</style>