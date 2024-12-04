<template>
    <div>
        <div class="ml-10 mt-3" style="width: 100%;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
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
                <tr v-for="(value, index) in getAllSelectedRequestOffers" :key="index">
                    <td>
                        <TimerClock :targetTime="value.rate_held" :timezone="value.timezone" />
                    </td>
                    <td>
                        <div class="textContainer">
                            <institutionProfile :organization="value.org" />
                        </div>
                    </td>
                    <td>
                        <div class="textContainer">{{value.currency}}</div>
                    </td>
                    <td>
                        <div class="textContainer">
                            {{abreviateNumber(value.min)}} -
                            {{abreviateNumber(value.max)}}
                        </div>
                    </td>

                    <td>
                        <div class="textContainer">

                            <div v-if="value.latest_counter!==null && (value.latest_counter?.offered_interest_rate!=value.rate) && value.latest_counter?.label!=='Declined' "
                                style="width: 100%; height: 100%; color: #9CA1AA; font-size: 15px; font-family: Montserrat; font-weight: 400; text-decoration: line-through; text-transform: capitalize; word-wrap: break-word">
                                {{(value.rate).toFixed(2)}}%</div>
                            <div v-else>
                                {{(value.rate).toFixed(2)}}%
                            </div>
                        </div>
                    </td>
                    <td>
                        <div v-if="value.latest_counter!==null  && (value.latest_counter?.offered_interest_rate!=value.rate) "
                            class="textContainer"
                            style="display:flex; flex-direction:column; gap:3px; justify-content:flex-start; align-items:start;">

                            <div v-if="value.latest_counter!==null && (value.latest_counter?.offered_interest_rate!=value.rate) && value.latest_counter?.label==='Declined' "
                                style="width: 100%; height: 100%; color: #9CA1AA; font-size: 15px; font-family: Montserrat; font-weight: 400; text-decoration: line-through; text-transform: capitalize; word-wrap: break-word">
                                {{(value.latest_counter?.offered_interest_rate.toFixed(2))}}%
                            </div>
                            <div v-else>
                                {{(value.latest_counter?.offered_interest_rate.toFixed(2))}}%
                            </div>

                        </div>
                        <div v-else class="textContainer">
                            -
                        </div>


                    </td>

                    <td>
                        <EnterOfferInput :offer_id="value.offer_id" :offer="value"
                            :shouldNotPerformNoAction="shouldNotPerformNoAction" /><br />
                        <span v-if="value.awarded_error.length > 0"
                            class="error-message ">{{value.awarded_error}}</span>
                    </td>
                    <td>
                        <div class="textContainer">CAD
                            {{getSelectedRequestOfferRate(value.offer_id)}}</div>
                    </td>
                    <td>

                        <div v-if="value.latest_counter!==null" class="textContainer"
                            style="color: #5063F4 !important; display:flex; flex-direction:column; gap:3px; justify-content:flex-start; align-items:start;">
                            <div v-if="value.latest_counter.label==='Counter Sent'"
                                style="width: 100%; height: 100%; justify-content: flex-start; align-items: center; display: inline-flex">
                                <div
                                    style="min-height: 21px; justify-content: flex-start; align-items: center; display: flex">
                                    <div
                                        style="min-height: 21px; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #5063F4; justify-content: center; align-items: center; gap: 10px; display: flex">
                                        <div
                                            style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                            {{value.latest_counter?.label}}</div>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="value.latest_counter.label==='Counter Received'"
                                style="width: 100%; height: 100%; justify-content: flex-start; align-items: center; display: inline-flex">
                                <div
                                    style="min-height: 21px; justify-content: flex-start; align-items: center; display: flex">
                                    <div
                                        style="min-height: 21px; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #9CA1AA; justify-content: center; align-items: center; gap: 10px; display: flex">
                                        <div
                                            style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                            {{value.latest_counter?.label}}</div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="value.latest_counter.label==='Accepted'"
                                style="width: 100%; height: 100%; justify-content: flex-start; align-items: center; display: inline-flex">
                                <div
                                    style="min-height: 21px; justify-content: flex-start; align-items: center; display: flex">
                                    <div
                                        style="min-height: 21px; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #44E0AA; justify-content: center; align-items: center; gap: 10px; display: flex">
                                        <div
                                            style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                            {{value.latest_counter?.label}}</div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="value.latest_counter.label==='Declined'"
                                style="width: 100%; height: 100%; justify-content: flex-start; align-items: center; display: inline-flex">
                                <div
                                    style="min-height: 21px; justify-content: flex-start; align-items: center; display: flex">
                                    <div
                                        style="min-height: 21px; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #FF2E2E; justify-content: center; align-items: center; gap: 10px; display: flex">
                                        <div
                                            style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                            {{value.latest_counter?.label}}</div>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="value.latest_counter.label==='Expired'"
                                style="width: 100%; height: 100%; justify-content: flex-start; align-items: center; display: inline-flex">
                                <div
                                    style="min-height: 21px; justify-content: flex-start; align-items: center; display: flex">
                                    <div
                                        style="min-height: 21px; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #FF2E2E; justify-content: center; align-items: center; gap: 10px; display: flex">
                                        <div
                                            style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                            {{value.latest_counter?.label}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="textContainer">

                        </div>
                    </td>
                    <td>
                        <CounterOfferBT :offer="value" :deposit_request="deposit_request"
                            :encoded_deposit_request_id="encoded_deposit_request_id"
                            :offersselectsubmitting="offersselectsubmitting"
                            :shouldNotPerformNoAction="shouldNotPerformNoAction" :timezone="value.timezone" />
                    </td>

                </tr>
            </tbody>

        </table>
        <div class="mt-3" v-if="data">
            <Pagination @click-next-page="getPageData" v-if="data?.links" :data="data" />
        </div>
    </div>

</template>
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
</style>
<script>
    import Table from "../../shared/PostOffersTable";
    import Pagination from "../../shared/Table/Pagination";
    import CustomInput from "../../shared/CustomInput";

    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    import { formatTimestamp } from "../../../utils/dateUtils";
    import ViewOffer from "../actions/ViewOfferSummary"
    import WithdrawRequest from "../actions/WithdrawRequest"
    import EditRequest from "../actions/EditRequest"
    import EnterOfferInput from "../sharedComponents/CustomInPutB"
    import CounterOfferBT from "../sharedComponents/CounterOfferR"
    import institutionProfile from "../sharedComponents/institutionProfile"

    import CustomSpan from "../sharedComponents/CustomSpan"

    import Actions from "../../shared/Table/ActionsReviewOffers";

    import TimerClock from "../sharedComponents/TimerClock";
    import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../utils/commonUtils";
    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/postreq/mutation-types.js';
    import FilterBox from "../../shared/Table/ReviewOffersFilterBox";
    export default {
        data() {
            let columnss = ['Rate Held Until', 'Institution', 'Currency', 'Offer Range', 'Rate', 'Counter Rate', 'Awarded Amount', 'Interest Paid', 'Counter', 'Action'];
            let act = [
                {
                    name: "View",
                    component: ViewOffer
                }
            ];
            return {
                table_data: [],
                actions: act,
                columns: columnss,
                details: null,
                existing: null,
                action: 'view',
                is_modal: false,
                filtered: [],
                filterString: '',
                products: [],
                data: {}
            }
        },
        mounted() {
            const params = new URLSearchParams(window.location.search);
            this.requestId = params.get('request_id');
            this.getOffers("/get-prequest-offers?request_id=" + this.requestId + "");

        },
        computed: {
            ...mapGetters('postreq', ['getAllSelectedRequestOffers', 'getPickedSelectedRequestOffers', 'getAllSelectedRequestOffersRates', 'getSelectedRequestOfferRate']),
        },
        components: {
            FilterBox,
            institutionProfile,
            TimerClock,
            Actions,
            CustomSpan,
            CounterOfferBT,
            EnterOfferInput,
            Table,
            EditRequest,
            WithdrawRequest,
            ViewOffer,
        },
        methods: {
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
            getOffers(url) {

                let this_ = this;
                url = url ? url : "/get-prequest-offers";
                axios.get(url)
                    .then(response => {

                        let table_data = [];
                        let earnedInterests = [];
                        this_.data = response?.data?.data;

                        Object.values(response?.data?.data).forEach(item => {
                            let lates_counter = null;

                            if (item?.counter_offers.length > 0) {
                                // <!-- alert(item?.counter_offers[0].offe); -->
                                lates_counter = item?.counter_offers[0];
                            }

                            console.log("lates_counter", lates_counter);
                            table_data.push({

                                offer_id: item?.offer_id,
                                depositor_request_id: item?.depositor_request_id,
                                amount: item?.amount,
                                rate_held: item?.rate_held_until,
                                org_name: item?.organization_name,
                                currency: item?.currency,
                                min: item?.minimum_amount,
                                max: item?.maximum_amount,
                                rate: item?.interest_rate_offer,
                                awarded: 0,
                                row_rate: 0,
                                awarded_error: '',
                                counter_offers: item?.counter_offers,
                                latest_counter: (lates_counter != null) ? lates_counter : null,
                                timezone: item?.timezone,
                                org: item?.invited?.bank
                            });

                            earnedInterests.push({
                                offer_id: item?.offer_id,
                                row_rate: 0
                            });
                        });

                        // Committing mutation to the store
                        this.$store.commit('postreq/' + types.SET_ALL_SELECTED_REQUEST_OFFERS, table_data);
                        this.$store.commit('postreq/' + types.SET_ALL_SELECTED_REQUEST_OFFERS_RATES, earnedInterests);

                        //  console.log(this.table_data, "table_data");
                    }).catch(error => {
                        console.log("error > " + error);
                    });
            }
        },
        props: ['actionId', "deposit_request", "encoded_deposit_request_id", "shouldNotPerformNoAction", "offersselectsubmitting", "fiorganizations"]

    }
</script>