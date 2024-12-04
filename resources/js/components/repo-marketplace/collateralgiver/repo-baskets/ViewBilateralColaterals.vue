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
                            {{ basket_name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center w-100 flex-column mt-2">
            <div class="shadow-sm w-75 p-4 bg-white">
                <div class="d-flex justify-content-center">
                    <p class="basket-head">
                        Manage Your bilateral Collateral Baskets
                    </p>
                </div>
                <div class="d-flex justify-content-start px-3">
                    <p class="basket-head-description p-0 m-0">
                        From this screen, you can edit, archive or activate your collateral baskets.
                    </p>
                </div>
                <div>
                    <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                        <ViewCard title="Issuer" :desc="data?.collateral_issuer?.name" />
                        <ViewCard title="Currency" :desc="data?.currency" />
                        <ViewCard title="Rating" :desc="data?.rating" />
                    </div>
                </div>
                <div class="ml-10 mt-3" style="width:100%;display: flex; flex-direction:row; justify-content:flex-end;">
                    <FilterBox :attentioncount="attentioncount" @filterPending="filterPending"
                        :pendingcount="pendingcount" :dontshow="['btype', 'issuer', 'rating']" :filtered="filtered"
                        @apply_filters="submitFilters" @clear_filters="clearFilters" filterType="colaterals"
                        @searching="search" from="colaterals">
                    </FilterBox>
                </div>
                <div class="mt-3">
                    <Table :columns="columns" no-data-title="No Active Baskets"
                        no-data-message="Start by adding a new basket to populate data" :data="table_data"
                        :has_action='has_action' :actions='actions' :selectable="false" :is_loading="is_loading" />
                </div>
            </div>
            <div class="mt-3 w-75">
                <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
            </div>
            <div class="d-flex justify-content-start w-75 mt-3">
                <CustomSubmit :previous="true" @action="goBack" title="Previous" />
                <!-- <CustomSubmit @action="morecps = true" title="Add more counterparties" /> -->
            </div>
        </div>
    </div>
</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import FilterBox from "./filters/FilterBox";
import ArchiveCollateral from "./actions/bilaterals/ArchiveCollateral.vue"
import ActivateCollateral from "./actions/bilaterals/ActivateCollateral.vue"
import EditCollateral from "./actions/bilaterals/EditCollateral.vue";

import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, formatTimestamp } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/baskets/mutation-types';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import BasketStatus from "../../shared/BasketStatus.vue";
import ViewCard from '../../../shared/ViewCard.vue'
import { userCan } from "../../../../utils/GlobalUtils";
export default {
    props: ['userLoggedIn'],
    beforeMount() {
        this.getCollateteralIssure()
        this.getCollaterals()
        this.has_action = userCan(this.userLoggedIn, 'bank/repos/edit-basket')
        if (this.has_action)
            this.columns = ['#', 'CUSIP Number', 'Collateral Type', ' Maturity Date', 'Status', 'Action'];
        else
            this.columns = ['#', 'CUSIP Number', 'Collateral Type', ' Maturity Date', 'Status'];
    },
    mounted() {
        this.getUrlPArams()

    },
    computed: {

    },
    components: {
        CustomSubmit,
        ArchiveCollateral,
        ActivateCollateral,
        EditCollateral,
        Table,
        Pagination,
        FilterBox,
        BasketStatus,
        ViewCard
    },
    created() {
    },
    data() {
        // let columnss = ['#', 'CUSIP Number', 'Collateral Type', ' Maturity Date', 'Status', 'Action'];
        let act = [
            {
                name: "Edit",
                component: EditCollateral
            },
            {
                name: "Archive",
                component: ArchiveCollateral
            },
        ];
        return {
            details: null,
            pendingcount: null,
            attentioncount: null,
            existing: null,
            collateral_issuers: null,
            basket_name: 'Manage Collateral',
            actions: act,
            columns: null,
            is_modal: false,
            has_action: false,
            table_data: [],
            links: [],
            data: [],
            term_length_filter: null,
            product_type_filter: null,
            filtered: [],
            is_loading: false,
            productFilter: '',
            filterString: '',
            basketId: null
        }
    },
    watch: {

    },
    methods: {
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/

            const numberPart = parts[parts.length - 1];
            this.basketId = numberPart
            this.getData()
        },
        goBack() {
            window.location.href = "/repos/view-collaterals"
        },
        getCollaterals() {
            axios.get('/common/trade/get-colletarals-list').then(response => {
                let collateral = response.data
                if (collateral.length > 0) {
                    // this.collaterals = collateral
                    this.$store.commit('basket/' + types.SET_BI_COLLATERAL, collateral);
                }
            })
        },
        getCollateteralIssure() {
            axios.get('/trade/CG/get-colleterals-issuers').then(response => {
                let collateral_issuers = response.data
                let collateral_issuer = []
                if (collateral_issuers.length > 0) {
                    collateral_issuers.forEach(element => {
                        collateral_issuer.push(
                            { 'id': element.encoded_id, 'name': element.name, 'item_id': element.id }
                        )
                    });
                }
                this.collateral_issuers = collateral_issuer
                this.$store.commit('basket/' + types.SET_COLLATERAL_ISSUERS, collateral_issuer);
            })
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
            url = `/trade/CG/get-colleteral?status=${value}&collateral=${this.basketId}&is_dummy=0`;

            this.getData(url);
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/trade/CG/get-colleteral?${this.filterString}&search=${value}&collateral=${this.basketId}&is_dummy=0`;
            } else {
                url = `/trade/CG/get-colleteral&search=${value}&collateral=${this.basketId}&is_dummy=0`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            let url = `/trade/CG/get-colleteral?${value}&collateral=${this.basketId}&is_dummy=0`;
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
        formatTimestamp(value, bootest) {
            return formatTimestamp(value, bootest);

        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : `/trade/CG/get-colleteral?collateral=${this.basketId}&is_dummy=0`;
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data;
                    this.$store.commit('basket/' + types.ADD_BI_COLLATERALS, response.data);

                    let pc = 0
                    let ac = 0
                    response?.data.trade_organization_c_u_s_s_i_p.forEach((item, count) => {
                        if (item?.cusips_status == 'PENDING') {
                            pc += 1
                        }
                        if (item?.cusips_status == 'ATTENTION') {
                            ac += 1
                        }
                        this.pendingcount = pc
                        this.attentioncount = ac
                        table_data.push([
                            item?.encoded_id,
                            count + 1,
                            item?.CUSIP_code,
                            item?.collateral_details?.collateral_name,
                            // new Date(item?.maturity_date),
                            formatTimestamp(item?.maturity_date + " 00:00", false),
                            () => this.renderStatus(item?.cusips_status),
                        ]);
                    });
                    this.is_loading = false;
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
</style>