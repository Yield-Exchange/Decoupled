<template>
    <div>
        <div class="ml-10 mt-3" style="width: 100%;">
            <FilterBox class="d-none" :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="all-products" :products="products" @searching="search" :fiorganizations="fiorganizations"
                from="offerssum">
            </FilterBox>
        </div>
        <table class="table" style="width: 100%; ">
            <thead class="customHeader">
                <tr>
                    <th v-for="(column, index) in columns" :key="index">{{ column }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, index) in getAllOffersInReview" :key="index">
                    <td>
                        {{ value.reference }}
                    </td>
                    <!-- <td> {{ formatTimestamp(value.settledata, false) }}</td> -->
                    <td>
                        {{ value.settlement_date }}
                    </td>
                    <td>
                        {{ value.product }}
                    </td>
                    <td>
                        {{ value.basket_type.name }}
                    </td>
                    <td>
                        {{ value.term }}
                    </td>
                    <td>
                        {{ value?.interest_calculation_option }}
                    </td>
                    <td>
                        {{ currency + " " + abreviateNumber(value.min_amount) + '-' + abreviateNumber(value.max_amount)
                        }}
                    </td>
                    <td>
                        {{ value.rate_type }}

                    </td>
                    <td>
                        <CounterStatus :status="value.counter_status" checkFor="rate"
                            :rateValue="`${value.rate.toFixed(2)}%`" />
                    </td>
                    <!-- <td :class="{ 'strike-through': strikethrough(value.counter_status) }">
                        {{ value.rate.toFixed(2) }}%
                    </td> -->
                    <td>
                        <CounterStatus :status="value.counter_status" checkFor="counterrate"
                            :rateValue="value.counterrate" />
                    </td>
                    <!-- <td
                        :class="{ 'newvaluecolor': strikethrough(value.counter_status), 'strike-through': value.counter_status && value.counter_status == 'DECLINED' }">
                        {{ value.counterrate }}
                    </td> -->
                    <td v-if="value.counter_status">
                        <CounterStatus :status="value.counter_status" checkFor="counterstatus"
                            :rateValue="value.counterrate" />
                        <!-- <InviteCard :text="value.counter_status" /> -->
                    </td>
                    <td v-else>
                        -
                    </td>
                    <td>
                        <TableActions :counter_status="value.counter_status" v-if="userLoggedIn" :reference="value.reference" :userLoggedIn="userLoggedIn"
                            :offer_id="value.offer_id" :organization_data="value?.collateraltaker"
                            @showCounterOffer="showCounterOffer" @showOffer="showOffer" :id="index">
                        </TableActions>
                    </td>

                </tr>
            </tbody>

        </table>
        <div class="mt-3" v-if="data">
            <Pagination @click-next-page="getPageData" v-if="data?.links" :data="data" />
        </div>
        <OfferSummary :userLoggedIn="userLoggedIn" @showEditOffer="showEditOffer" @showCounterOffer="showCounterOffer"
            v-if="view_offer" :offerIndex="offer_index" :show="view_offer" @closeModal="closeShowOffer">
        </OfferSummary>
        <Counter :daycount="daycount" :holidays="holidays" :userLoggedIn="userLoggedIn" v-if="view_counter_offer"
            :offerIndex="offer_index" :show="view_counter_offer" @closeModal="closeShowOffer" />
        <EditOffer v-if="edit_offer" :formattedtimezone="formattedtimezone" :daycount="daycount" :holidays="holidays"
            :bilateral_primary_baskets="bilateral_primary_baskets" :triparties="triparties"
            :triparties_primary_baskets="triparties_primary_baskets" :bilaterals="collaterals" :offerIndex="offer_index"
            :show="edit_offer" @closeModal="closeShowOffer" />

    </div>

</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import CustomInput from "../../../shared/CustomInput";
import InviteCard from '../../../shared/CustomInvitedStatusBadge.vue';


import Button from "../../../shared/Buttons/Button";
import TableActionButton from "../../../shared/Buttons/TableActionButton";
import institutionProfile from "../../shared/AboutCG.vue"
import { addCommasToANumber, calculateSettlementLabel, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, capitalize, repoProductName, getBasketDetails } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import TableActions from './actions/TableActions.vue'
import ShowCG from '../../shared/ShowCG.vue'
import * as types from '../../../../store/modules/repos/mutation-types';
import FilterBox from "../../../shared/Table/ReviewOffersFilterBox";
import OfferSummary from './osummary/OfferSummary.vue'
import Counter from './osummary/Counter.vue'
import EditOffer from "./osummary/editOffer.vue";
import CounterStatus from "../../shared/CounterStatus.vue";
export default {
    data() {
        let columnss = ['Offer ID ', 'Settlement Date', 'Product', 'Collateral', 'Term Length', 'Day Count', 'Min-Max', 'Rate Type', 'Rate', 'Counter', 'Counter', 'Action'];

        return {
            table_data: [],
            actions: [],
            columns: columnss,
            details: null,
            existing: null,
            action: 'view',
            is_modal: false,
            filtered: [],
            filterString: '',
            products: [],
            data: {},
            requestId: null,
            // offer model
            offer_index: null,
            view_offer: false,
            view_counter_offer: false,
            edit_offer: false,
            currency: 'CAD',
            collaterals: null,
            triparties: null,
            unecd_id: null,
            triparties_primary_baskets: null,
            bilateral_primary_baskets: null,
            daycount: null,
            holidays: null,
            formattedtimezone: null,

        }
    },
    beforeMount() {
        this.getUrlPArams()
        this.getAllHolidays()
        this.getTimezone()
        this.getAllDayCounts()
        this.getCollaterals()
        this.getBilateralBasketTypes()
        this.getTriBasketTypes()
        if (this.requestId)
            this.getOffers("/trade/CG/get-trade-request-offers?requestId=" + this.requestId);
        if (this.organization_id)
            this.getTriParties()

    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate']),
    },

    components: {
        EditOffer,
        Counter,
        CounterStatus,
        institutionProfile,
        ShowCG,
        TableActions,
        CustomInput,
        FilterBox,
        Table,
        OfferSummary,
        InviteCard
    },
    methods: {
        getTimezone() {
            axios.get('/get-formated-timezone').then(res => {
                this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        getAllHolidays() {
            axios.get('https://canada-holidays.ca/api/v1/holidays').then(res => {
                this.holidays = res?.data?.holidays
                // console.log(res.data.holidays, "Holdays")
                // this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        getAllDayCounts() {
            axios.get('/common/trade/get-all-interest-calculation-options?status=ACTIVE').then(res => {
                //    this.holidays=res?.data?.holidays
                if (res.data.length > 0)
                    this.daycount = res.data
                // console.log(res.data, "Holdays")
                // this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        getTriBasketTypes() {
            axios.get('/common/trade/get-basket-types').then(response => {
                this.triparties_primary_baskets = response.data
            })
        },
        getBilateralBasketTypes() {
            axios.get('/common/trade/get-colletarals-list').then(response => {
                this.bilateral_primary_baskets = response.data
            })
        },
        getCollaterals() {
            axios.get('/trade/CG/get-colleterals?is_dummy=0').then(response => {
                let collateral = []
                if (response.data.length > 0) {
                    Object.values(response?.data).forEach((item, count) => {
                        Object.values(item.trade_organization_c_u_s_s_i_p).forEach((cusip) => {
                            // console.log(cusip, 'cusip item')
                            if (cusip?.cusips_status == 'ACTIVE') {
                                collateral.push(
                                    {
                                        'id': cusip?.id,
                                        'primary_id': cusip?.collateral_details?.id,
                                        'cucip': cusip?.CUSIP_code,
                                        'collateral_name': cusip?.collateral_details?.collateral_name,
                                        'rating': item?.rating,
                                        'currency': item?.currency,
                                        'name': `${item?.currency}-${item?.rating}-${cusip?.CUSIP_code}`,
                                    }
                                )
                            }
                        });
                    });
                    // console.log(collateral, 'Data 2')
                    this.collaterals = collateral
                }

            })
        },
        async getTriParties() {
            await axios.get('/trade/CG/get-baskets?is_dummy=0').then(response => {
                let triparties = []
                if (response.data.length > 0) {
                    Object.values(response?.data).forEach((item, count) => {
                        if (item?.is_disabled == 0) {
                            item?.trade_tri_basket_third_party.forEach(basket => {
                                if (basket?.basket_status == 'ACTIVE' && basket?.organization_id == this.organization_id)
                                    triparties.push(
                                        {
                                            'id': basket?.id,
                                            'primary_id': item?.trade_basket_type?.id,
                                            'basket_id': basket.basket_id,
                                            'basket_name': item?.trade_basket_type?.basket_name,
                                            'rating': item?.rating,
                                            'currency': item?.currency,
                                            'name': `${item?.currency}-${item?.rating}-${basket.basket_id}`,
                                        }
                                    )
                            })

                        }
                    });
                    this.triparties = triparties
                    // console.log(triparties, "Tri parties")
                }

            })
        },
        strikethrough(value) {
            if (value && (value.toLowerCase() == 'pending')) {
                return true
            } else {
                return false
            }
        },
        showOffer(id) {
            this.offer_index = id
            this.view_offer = true
        },
        showCounterOffer(id) {
            this.offer_index = id
            this.view_counter_offer = true
            this.view_offer = false

        },
        showEditOffer(id) {
            this.offer_index = id
            this.edit_offer = true
            this.view_offer = false
        },
        closeShowOffer() {
            this.view_offer = false
            this.view_counter_offer = false
            this.edit_offer = false
            this.offer_index = null

        },
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];
            this.requestId = numberPart
            return numberPart
        },
        formatTimestamp(value, hastime) {
            return formatTimestamp(value, hastime)

        },
        clearFilters() {
            this.getOffers(`/get-prequest-offers?request_id=${this.requestId}`);
        },
        search(value) {
            let url = '';
            if (this.filterString !== '') {
                url = `/get-prequest-offers?request_id=${this.requestId}&${value}&search=${value}`;
            } else {
                url = `/get-prequest-offers?request_id=${this.requestId}&search=${value}`
            }
            this.getOffers(url);
        },
        submitFilters(value) {

            this.filterString = value;
            let url = `/get-prequest-offers?request_id=${this.requestId}&${value}`;
            this.getOffers(url);
        },
        renderExpiryDate(ex_date, useertimezone) {

            return ({ 'component': TimerClock, 'attrs ': { targetTime: ex_date, timezone: useertimezone } });

        },
        abreviateNumber(number) {
            return formatNumberAbbreviated(number);
        },
        formatToNumberAndAddDecimals(number) {
            return addCommasAndDecToANumber(number);
        },
        renderListComponents(componentType, compnentData) {
            switch (componentType) {
                case "TimeClock":
                    return ({ 'component': TimerClock, 'attrs': { targetTime: compnentData['ex_date'], timezone: compnentData['useertimezone'] } });
                    break;
                case "award":
                    return ({ 'component': EnterOfferInput, 'attrs': {} });
                    break;
                case "lineinterestearned":
                    return ({ 'component': CustomSpan, 'attrs': { type: compnentData['participated'] } });
                    break;
                case "Counter":
                    return ({ 'component': CounterOfferBT, 'attrs': {} });
                    break;
                default:
                    return "-";
            }

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
            this.getOffers(url);
        },
        abreviateNumber(number) {
            return formatNumberAbbreviated(number);
        },
        getOffers(url) {
            // this.table_data.push([1, 1, 1, 1, 1, 1, 1, 1, 1, 1])
            let this_ = this;
            // url = url ? url : "/trade/CT/get-trade-request-offers?requestId=";
            axios.get(url + "&from=inprogress")
                .then(response => {

                    let table_data = [];
                    let earnedInterests = [];
                    this_.data = response?.data;
                    // console.log(response)
                    Object.values(response?.data).forEach(item => {
                        let lates_counter = null;
                        let basketType = item?.basket != null
                            ? getBasketDetails(item?.basket)
                            : getBasketDetails(item?.bi_colleteral, false);
                        table_data.push({

                            offer_id: item.encoded_id,
                            min_amount: item.offer_minimum_amount,
                            max_amount: item.offer_maximum_amount,
                            reference: item.offer_reference_no,
                            settlement_date: item?.settlement_date ? formatTimestamp(item?.settlement_date, false) : '-',
                            // settlement_date: (item?.trade_settlement_period_id && this.getsettlemntdate) ? calculateSettlementLabel(this.getsettlemntdate.find(element => element.id == item?.trade_settlement_period_id)) : '-',
                            settledata: item?.c_t_trade_request.trade_time,
                            collateralgiver: item?.invitee?.organization,
                            interest_calculation_option: item?.interest_calculation_option ? item?.interest_calculation_option?.label : '-',
                            collateraltaker: item?.c_t_trade_request?.inviter,
                            product: item.product?.product_name,
                            // product: repoProductName(item?.offer_term_length, item.offer_term_length_type, item?.product?.product_name),
                            basket_type: basketType,
                            term: item?.offer_term_length + " " + capitalize(item.offer_term_length_type),
                            rate_type: item?.rate_type == 'fixed' ? 'Fixed' : 'Variable',
                            rate: item?.offer_interest_rate,
                            awarded_amount: "Input Field",
                            currency: item?.c_t_trade_request?.currency,
                            counter_status: item.counter_offers?.length > 0 ? item.counter_offers[0].status : false,
                            counterrate: item.counter_offers?.length > 0 ? item.counter_offers[0].offer_interest_rate.toFixed(2) + '%' : '-',
                            offer: item
                            // counter_status: item?.depositor_request_id,
                        });

                        // earnedInterests.push({
                        //     offer_id: item?.offer_id,
                        //     row_rate: 0
                        // });
                    });
                    this.table_data = table_data

                    // Committing mutation to the store
                    this.$store.commit('repopostrequest/' + types.SET_REVIEW_REQUEST_OFFERS, table_data);
                    //  console.log(this.table_data, "table_data");
                }).catch(error => {
                    console.log("error > " + error);
                });
        }
    },
    props: ['actionId', 'userLoggedIn', 'organization_id', "deposit_request", "encoded_deposit_request_id", "shouldNotPerformNoAction", "offersselectsubmitting", "fiorganizations"]

}
</script>
<style scoped>
.textContainer {
    width: 100%;
    height: 100%;
    color: black;
    font-size: 15px;
    font-family: Montserrat;
    font-weight: 400;
    word-wrap: break-word
}

thead.customHeader {
    background: #eff2fe !important;
    height: 51px;
}

thead.customHeader tr th span .custom-checkbox ::before {
    border-radius: 4px !important;
    border: 0.50px #5063F4 solid !important;
    padding-left: 2px;
}

thead.customHeader tr th span .custom-checkbox .custom-control-label {
    border: 0.50px #5063F4 solid !important;
    margin-top: 0 !important;


}

thead .custom-control-label {
    margin-top: 0 !important;
}

thead.customHeader tr {
    border-bottom: 0 solid #b3b2b2 !important;
}

thead.customHeader tr th {
    color: black;
    font-size: 16px !important;
    font-weight: 700;
    background: inherit !important;
    max-width: 300px;
    /* min-width: 150px; */
    padding-right: 0.75rem;
    padding-left: 0.55rem;
}

@media screen and (max-width:1200px) {
    thead.customHeader tr th {
        font-size: .75em;
    }
}

.table tbody tr td {
    padding: none !important;
}

.error-message {
    color: red;
}

.strike-through {
    text-decoration: line-through !important;
}

.newvaluecolor {
    color: #44E0AA !important;
    font-family: Montserrat;
    font-size: 12px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
    /* text-decoration-line: strikethrough; */
    text-transform: capitalize;
}
</style>