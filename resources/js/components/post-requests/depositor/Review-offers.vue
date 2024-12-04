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
                filterType="all-products" :products="products" @searching="search" from="reviewoffers">
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
import Table from "../../shared/PostOffersTable";
import Pagination from "../../shared/Table/Pagination";
import FilterBox from "../../shared/Table/ReviewOffersFilterBox";
import CustomInput from "../../shared/CustomInput";

import { userCan } from "../../../utils/GlobalUtils";
// import { formatTimestamp } from "../../../utils/commonUtils";
import ViewOffer from "../actions/ViewOffer"
import WithdrawRequest from "../actions/WithdrawRequest"
import EditRequest from "../actions/EditRequest"
import RequestSummary from "../actions/ViewRequestSummary"
import { addCommasToANumber, formatTimestamp,formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../store/modules/postreq/mutation-types.js';
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
        FilterBox
    },
    created() {
    },
    props: ['offers', 'products', 'titlespan'],
    data() {
        let columnss = ['Approximate Deposit Date', 'Request ID', 'Product', 'Request Amount', 'Duration', 'Offers', 'Highest', 'Lowest', 'Action'];
        let act = [
            {
                name: "View Offers",
                component: ViewOffer
            },
            {
                name: "Withdraw Request",
                component: WithdrawRequest
            },
            {
                name: "Edit Request",
                component: EditRequest
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
                url = `/get-review-offers-data-new?${value}&search=${value}`;
            } else {
                url = `/get-review-offers-data-new?search=${value}`
            }
            this.getData(url);
        },
        submitFilters(value) {
            this.filterString = value;
            console.log(value, "all filters data");
            let url = `/get-review-offers-data-new?${value}`;
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
                url = `${url}&${this.filterString}`;
            }
            this.getData(url);

        },
        getData(url) {
            this.is_loading = true;
            let this_ = this;
            url = url ? url : "/get-review-offers-data-new";
            axios.get(url)
                .then(response => {
                    let table_data = [];
                    this_.data = response?.data.data;
                    Object.values(response?.data.data?.data).forEach((item) => {
                        table_data.push([
                            item?.id,
                            formatTimestamp(item.date_of_deposit,false),
                            item?.reference_no,
                            item?.product_name,
                            item?.currency + ' ' + addCommasToANumber(item?.amount),
                            (this.capitalize(item?.term_length_type) === 'Hisa') ? '-' : item?.term_length + " " + this.capitalize(item?.term_length_type),
                            item?.total_offers,
                            (item?.max_interest_rate_offer === null) ? '-' : item?.max_interest_rate_offer.toFixed(2) + " %",
                            (item?.min_interest_rate_offer === null) ? '-' : item?.min_interest_rate_offer.toFixed(2) + " %"]
                        );

                    });
                    this.is_loading = false;
                    this_.table_data = table_data;
                }).catch(error => {
                    this.is_loading = false;
                    console.log("error > " + error);
                });

        },
        userCan(user, permission) {
            return userCan(user, permission);
        },
    }
}
</script>