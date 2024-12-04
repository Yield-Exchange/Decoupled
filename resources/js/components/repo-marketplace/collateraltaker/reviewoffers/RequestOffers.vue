<template>
    <div>
        <div class="ml-10 mt-3 d-none" style="width: 100%;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="all-products" :products="products" @searching="search" :fiorganizations="fiorganizations"
                from="offerssum">
            </FilterBox>
        </div>
        <table class="table" style="width: 100%; " v-if="getAllOffersInReview && getAllOffersInReview?.length != 0">
            <thead class="customHeader">
                <tr>
                    <th v-for="(column, index) in columns" :key="index">{{ column }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, index) in getAllOffersInReview" :key="index">
                    <td> {{ value.settlement_date }}</td>
                    <!-- <td> {{ formatTimestamp(value.settledata, false) }}</td> -->
                    <td>
                        <ShowCG :organization="value.collateralgiver" :orgname="value.collateralgiver.name"></ShowCG>
                    </td>
                    <td>
                        {{ value.product }}
                    </td>
                    <td>
                        {{ value.basket_type }}
                    </td>
                    <td>
                        {{ value.term }}
                    </td>
                    <td>
                        {{ value?.interest_calculation_option }}
                    </td>
                    <td>
                        {{ value.currency + " " + abreviateNumber(value.min_amount) + '-' +
                abreviateNumber(value.max_amount)
                        }}
                    </td>
                    <td>
                        {{ value.rate_type }}

                    </td>
                    <td>
                        <CounterStatus :status="value.counter_status" checkFor="rate" :rateValue=" `${value.rate.toFixed(2)}%`" />
                        <!-- <span :class="{ 'strike-through': strikethrough(value.counter_status) }">
                            {{ value.rate.toFixed(2) }}%
                        </span> -->
                        <span></span>
                    </td>
                    <td>
                        <CounterStatus :status="value.counter_status" checkFor="counterrate"
                            :rateValue="value.counterrate" />

                    </td>
                    <!-- <td
                        :class="{ 'newvaluecolor': strikethrough(value.counter_status), 'strike-through': value.counter_status && value.counter_status == 'DECLINED' }">
                        {{ value.counterrate }}
                    </td> -->
                    <td>
                        <CurrencyInput inputType="number" :allownull="true" :nocurrency="true"
                            inputStyle="font-weight: 400;width:100%;font-size:13px !important;padding:5px 10px !important"
                            p-style="width:100%" id="rate" placeholder="Enter Amount" :has-validation="true"
                            @currencyError="currencyError($event, value.offer_id)"
                            @inputChanged="awardAmount($event, value.offer_id)" input-type="number" :defaultValue="null"
                            :hasSpecificError="inputErrors[value.offer_id]" />
                        <!-- {{ value.validation_error }} -->
                        <div v-if="value?.amount_validation_error != ''" class="error-message">
                            {{ value?.amount_validation_error }} </div>
                        <!-- <CustomInput inputType="number"
                            inputStyle="font-weight: 400;width:100%;font-size:13px !important;padding:5px 10px !important"
                            p-style="width:100%" id="rate" name="Enter Amount" :has-validation="true"
                            @inputChanged="awardAmount($event, value.offer_id)" input-type="number" :defaultValue="null"
                            :hasSpecificError="false" /> -->
                    </td>
                    <td v-if="value.counter_status">
                        <CounterStatus :status="value.counter_status" checkFor="counterstatus"
                            :rateValue="value.counterrate" />
                        <!-- <InviteCard :text="value.counter_status" /> -->
                    </td>
                    <td v-else>
                        -
                    </td>
                    <td>
                        <TableActions :organization_data="value?.collateralgiver" :userLoggedIn="userLoggedIn"
                            @showCounterOffer="showCounterOffer" @showOffer="showOffer" :reference="value.reference"
                            :id="index" :offer_id="value.offer_id">
                        </TableActions>
                    </td>

                </tr>
            </tbody>
        </table>

        <LoadingData v-else-if="isLoading_data" />
        <NoData v-else title="No Offers" message="You will see offers when Recieved from Collateral Givers" />

        <div class="mt-3" v-if="data">
            <Pagination @click-next-page="getPageData" v-if="data?.links" :data="data" />
        </div>

        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-end gap-2">
                <CustomSubmit @action="goBackConditionally" :previous="true" title="Previous" />
                <CustomSubmit :isLoading="submitting"
                    v-if="userCan('depositor/repos/give-offers') && getAllOffersInReview && getAllOffersInReview?.length > 0"
                    @action="confirmSubmit" title="Submit" />
            </div>
        </div>
        <OfferSummary v-if="view_offer" :offerIndex="offer_index" :show="view_offer" @closeModal="closeShowOffer">
        </OfferSummary>
        <Counter v-if="view_counter_offer" :daycount="daycount" :holidays="holidays" :offerIndex="offer_index"
            :show="view_counter_offer" @closeModal="closeShowOffer" />
        <ActionMessage style="width: 600px;" @closedSuccessModal="closeSuccess" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" title="Offer has  been submitted!"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="px-3  description-text-withdraw "> The collateral giver has been notified. Prepare your funds
                for
                transfer.Where should we take you next?</div>
            <div class="d-flex w-100 justify-content-end mt-2 gap-3">
                <CustomSubmit @action="viewPending" :outline="true" title="Pending Deposits" />
                <CustomSubmit @action="goBack" title="Review Offers" />
            </div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="confimsubmit = false" @btnTwoClicked="submitOffers"
            @btnOneClicked="confimsubmit = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Ready to submit the selected offers?"
            :showm="confimsubmit">
            <div class="px-3  description-text-withdraw "> Are you sure you want to submit the offers as they are?</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            :title="fail_title" :showm="fail">
            <div class="ml-5 description-text-withdraw " v-if="fail_message">{{ fail_message }}</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="requestgoback" @btnTwoClicked="goBack"
            @btnOneClicked="requestgoback = false" icon="/assets/dashboard/icons/question-new.svg"
            title="Do you want to leave this page?" btnOneText="No" btnTwoText="Yes" :showm="requestgoback">
            <div class="ml-5 description-text-withdraw "> Changes you made will not be saved.</div>
        </ActionMessage>


    </div>

</template>

<script>
import Table from "../../../shared/PostOffersTable";
import Pagination from "../../../shared/Table/Pagination";
import CustomInput from "../../../shared/CustomInput";
import InviteCard from '../../../shared/CustomInvitedStatusBadge.vue';

import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSubmit from "../../../auth/signup/shared/CustomSubmit.vue";
import Button from "../../../shared/Buttons/Button";
import TableActionButton from "../../../shared/Buttons/TableActionButton";
import institutionProfile from "../../shared/AboutCG.vue"
import { addCommasToANumber, calculateSettlementLabel, getBasketDetails, repoProductName, formatTimestamp, formatNumberAbbreviated, sentenceCase, calculateIterestOnDateCountConnvention, addCommasAndDecToANumber, calculateIterestOnProduct, capitalize } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import TableActions from './actions/TableActions.vue'
import ShowCG from '../../shared/ShowCG.vue'
import * as types from '../../../../store/modules/repos/mutation-types';

import FilterBox from "../../shared/filters/FilterBox.vue";
import OfferSummary from './osummary/OfferSummary.vue'
import Counter from './osummary/Counter.vue'
import NoData from '../../../shared/Table/NoData.vue'
import LoadingData from '../../../shared/Table/LoadingData.vue'
import { userCan } from "../../../../utils/GlobalUtils";
import CurrencyInput from '../../../shared/CurrencyInput.vue'
import CounterStatus from "../../shared/CounterStatus.vue";

export default {
    data() {
        let columnss = ['Settlement Date ', 'Collateral Giver', 'Product', 'Collateral', 'Term Length', 'Day Count', 'Min - Max', 'Rate Type', 'Rate', 'Counter', 'Awarded Amount', 'Counter', 'Action'];

        return {
            success: false,
            fail: false,
            requestgoback: false,
            confimsubmit: false,
            submitting: false,
            fail_title: 'Offer has  not been editied!',
            fail_message: null,
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
            offers_object: {},
            // inputErrors: {},
            offers_to_submit: [],
            isLoading_data: false,
            // currency: 'CAD'
            daycount: null,
            holidays: null,
        }
    },
    beforeMount() {
        this.getUrlPArams()
        this.getAllHolidays()
        this.getAllDayCounts()
        // if (this.isloading) {
        //     this.isLoading_data = true
        // }
        if (this.requestId) {
            this.getOffers("/trade/CT/get-trade-request-offers?requestId=" + this.requestId);
            // console.log("This data")
        }

    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate', 'getRequestSummary', 'getOfferErrors']),
        inputErrors() {
            return this.getOfferErrors
        }
    },

    components: {
        CounterStatus,
        CustomSubmit,
        ActionMessage,
        Counter,
        FilterBox,
        institutionProfile,
        ShowCG,
        TableActions,
        CustomInput,
        CurrencyInput,
        Table,
        OfferSummary,
        InviteCard,
        NoData,
        LoadingData
    },
    methods: {
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
        strikethrough(value) {
            if (value && value.toLowerCase() == 'pending') {
                return true
            } else {
                return false
            }
        },
        viewPending() {
            window.location.href = '/repos/ct-repos-pending-trades'
        },
        goBack() {
            window.location.href = '/repos/repos-reviews'
        },
        currencyError(error_message, element_id) {
            console.log(error_message, " element_id")
            // this.$set(this.inputErrors, element_id, error_message);
            // console.log(this.inputErrors, "Input Erros")
            this.$store.commit('repopostrequest/' + types.SET_OFFER_ERRORS, [element_id, error_message]);
            // console.log(this.getOfferErrors[element_id] != null)
            // this.getOfferErrors
        },
        closeSuccess() {
            this.success = false
            this.goBack()
        },
        awardAmount(newvalue, valueid) {
            let offer_objects = this.offers_object
            let acc = {}
            // newvalue = newvalue.replace(/,/g, '')
            if (offer_objects != null && offer_objects.hasOwnProperty(valueid)) {
                if (newvalue != null && newvalue != '') {
                    offer_objects[valueid].awarded_amount = newvalue
                } else {
                    offer_objects[valueid].awarded_amount = null
                }
            }
            this.offers_object = offer_objects
            let sum = 0
            let weightedavg = 0
            let interest_amout = 0
            let count = 0
            this.offers_to_submit = []
            Object.values(offer_objects).forEach(element => {
                if (element.awarded_amount != null) {

                    if (element.awarded_amount > element.max_amount) {

                        this.$store.commit('repopostrequest/' + types.UPDATE_SELECTED_OFFER_ENTRY, {
                            offer_id: element.offerId,
                            field: 'amount_validation_error',
                            value: `Cannot be more than ${element?.currency}  ${addCommasToANumber(element.max_amount)}`
                        });
                        this.$store.commit('repopostrequest/' + types.SET_OFFER_ERRORS, [element.offerId, `Cannot be more than ${element?.currency}  ${addCommasToANumber(element.max_amount)}`]);

                    } else if (element.awarded_amount < element.min_amount) {
                        this.$store.commit('repopostrequest/' + types.UPDATE_SELECTED_OFFER_ENTRY, {
                            offer_id: element.offerId,
                            field: 'amount_validation_error',
                            value: `Cannot be less than ${element?.currency}  ${addCommasToANumber(element.min_amount)}`
                        });
                        this.$store.commit('repopostrequest/' + types.SET_OFFER_ERRORS, [element.offerId, `Cannot be less than ${element?.currency}  ${addCommasToANumber(element.min_amount)}`]);

                    } else {
                        this.$store.commit('repopostrequest/' + types.UPDATE_SELECTED_OFFER_ENTRY, {
                            offer_id: element.offerId,
                            field: 'amount_validation_error',
                            value: ``
                        });
                        this.$store.commit('repopostrequest/' + types.SET_OFFER_ERRORS, [element.offerId, null]);

                    }

                    this.offers_to_submit.push({
                        'offerId': element.offerId,
                        'awarded_amount': element.awarded_amount
                    })
                    count += 1
                    sum += Number.parseFloat(element.awarded_amount)
                    weightedavg += Number.parseFloat(element.rate)
                    // interest amount
                    // let rowinterest = calculateIterestOnProduct(
                    //     element.awarded_amount,
                    //     element.term,
                    //     element.term_type,
                    //     null,
                    //     element.rate
                    // );
                    // console.log(element.daycount, 'element daycount')
                    let rowinterest = calculateIterestOnDateCountConnvention(
                        element.awarded_amount,
                        element.rate,
                        element.daycount,
                        element.start_date,
                        element.term,
                        element.term_type,
                    );




                    interest_amout += Number.parseFloat(rowinterest);
                } else {
                    this.$store.commit('repopostrequest/' + types.UPDATE_SELECTED_OFFER_ENTRY, {
                        offer_id: element.offerId,
                        field: 'amount_validation_error',
                        value: ``
                    });
                }
            })
            let wa = 0
            if (count > 0 && weightedavg > 0)
                wa = (weightedavg / count)
            let res = {
                'awarded_amount': `${this.getRequestSummary?.currency} ${addCommasToANumber(sum)}`,
                'interest_amout': `${this.getRequestSummary?.currency} ${addCommasToANumber(interest_amout)}`,
                'weightedavg': `${wa.toFixed(2)}`,

            }
            this.$emit('awardedamt', res)
        },
        showOffer(id) {
            this.offer_index = id
            this.view_offer = true
        },
        showCounterOffer(id) {
            // console.log("Clicked")
            this.offer_index = id
            this.view_counter_offer = true

        },
        closeShowOffer() {
            this.view_offer = false
            this.view_counter_offer = false
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
            return capitalize(thestring)
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
        goBackConditionally() {
            if (this.offers_to_submit.length > 0) {
                this.requestgoback = true
            } else {
                this.goBack()
            }

        },
        confirmSubmit() {
            if (this.offers_to_submit.length > 0) {
                // console.log
                let haserror = false
                Object.keys(this.getOfferErrors).forEach(key => {
                    if (this.getOfferErrors[key] != null) {
                        haserror = true
                    }

                });
                if (!haserror)
                    this.confimsubmit = true
            } else {
                this.fail_title = "No Offer selected"
                this.fail_message = "Please select at least one offer by entering amount to award field"
                this.fail = true
            }
        },
        submitOffers() {
            this.submitting = true
            this.fail_title = "Offer has  not been submitted!"
            this.fail_message = "Something's not right,please try again or contact info@yieldechange.ca!"
            const data = {
                'offers': JSON.stringify(this.offers_to_submit),
                'requestId': this.requestId
            }
            axios.post('/trade/CT/select-offers', data).then(response => {
                this.submitting = false
                if (response.data.success) {
                    this.success = true
                    // setTimeout(() => {
                    //     // window.location.href = "/view-all-new-requests"
                    //     this.success = false
                    //     this.goBack()
                    // }, 3000)
                } else {
                    this.fail = true

                }
            }).catch(err => {
                this.submitting = false
                this.fail = true
            })
        },
        abreviateNumber(number) {
            return formatNumberAbbreviated(number);
        },
        userCan(b) {
            return userCan(this.userLoggedIn, b)
        },

        async getOffers(url) {
            let this_ = this;
            this.isLoading_data = true
            await axios.get(url + "&from=review")
                .then(response => {

                    let table_data = [];
                    let earnedInterests = [];
                    this_.data = response?.data;
                    // console.log(response)
                    Object.values(response?.data).forEach(item => {
                        let lates_counter = null;
                        let basketType = null
                        if (item?.basket != null || item?.bi_colleteral != null) {
                            basketType = item?.basket != null
                                ? getBasketDetails(item?.basket)
                                : getBasketDetails(item?.bi_colleteral, false);
                        } else {
                            basketType = null
                        }
                        // console.log(item?.basket == null, "Check null", this_.getBasketDetails(item?.basket));

                        table_data.push({
                            min_amount: item?.offer_minimum_amount,
                            max_amount: item?.offer_maximum_amount,
                            reference: item?.offer_reference_no,
                            offer_id: item?.encoded_id,
                            settlement_date: item?.settlement_date ? formatTimestamp(item?.settlement_date, false) : '-',
                            // settlement_date: item?.trade_settlement_period_id && this.getsettlemntdate ? calculateSettlementLabel(this.getsettlemntdate.find(element => element.id == item?.trade_settlement_period_id)) : '-',
                            // settledata: item?.c_t_trade_request.trade_time,
                            settledata: item?.c_t_trade_request.trade_time,
                            // organization_data: item?.c_t_trade_request.trade_time,
                            collateralgiver: item?.invitee?.organization,
                            interest_calculation_option: item?.interest_calculation_option ? item?.interest_calculation_option?.label : '-',
                            product: item.product?.product_name,
                            // product: repoProductName(item?.offer_term_length, item?.offer_term_length_type, item?.product?.product_name),
                            basket_type: basketType != null ? basketType.name : '-',
                            // basket_type: basketType,
                            term: item?.offer_term_length + " " + capitalize(item?.offer_term_length_type),
                            rate_type: item?.rate_type == 'fixed' ? 'Fixed' : 'Variable',
                            rate: item?.offer_interest_rate,
                            awarded_amount: "Input Field",
                            currency: item?.c_t_trade_request?.currency,
                            counter_status: item?.counter_offers ? item?.counter_offers?.length > 0 ? item?.counter_offers[0].status : false : false,
                            counterrate: item?.counter_offers ? item?.counter_offers?.length > 0 ? item?.counter_offers[0].offer_interest_rate.toFixed(2) + '%' : '-' : '-',
                            offer: item,
                            amount_validation_error: ""
                            // counter_status: item?.depositor_request_id,
                        });

                        this.$store.commit('repopostrequest/' + types.SET_OFFER_ERRORS, [item?.encoded_id, null]);

                        // this.inputErrors[item?.encoded_id] = null
                        this.offers_object[item?.encoded_id] = {
                            'start_date': item?.settlement_date,
                            'daycount': item?.interest_calculation_option.slug,
                            'rate': item?.offer_interest_rate,
                            'offerId': item?.encoded_id,
                            'max_amount': item?.offer_maximum_amount,
                            'min_amount': item?.offer_minimum_amount,
                            'currency': item?.c_t_trade_request?.currency,
                            'awarded_amount': null,
                            'term': item?.offer_term_length,
                            "term_type": item?.offer_term_length_type,

                        }
                    });
                    this.isLoading_data = false
                    this.table_data = table_data

                    // Committing mutation to the store
                    this.$store.commit('repopostrequest/' + types.SET_REVIEW_REQUEST_OFFERS, table_data);
                    //  console.log(this.table_data, "table_data");
                }).catch(error => {
                    this.isLoading_data = false

                    // console.log("error > " + error);
                });
        }
    },
    watch: {
        inputErrors: {
            handler(newValue, oldValue) {
                console.log(newValue, "Offer errors");
            },
            deep: true // Enables deep watching
        }
        // getsettlemntdate() {
        //     this.getOffers("/trade/CT/get-trade-request-offers?requestId=" + this.requestId);

        // }
    },
    props: ['actionId', 'isloading', "deposit_request", "encoded_deposit_request_id", "shouldNotPerformNoAction", "offersselectsubmitting", "fiorganizations", 'userLoggedIn']

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
