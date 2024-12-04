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
                <tr v-for="(value, index) in getAllOffersInReview" :key="index">
                    <td> {{ formatTimestamp(value.settledata, false) }}</td>
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
                        {{ value.rate_type }}

                    </td>
                    <td>
                        {{ value.rate.toFixed(2) }}
                    </td>
                    <td>
                        {{ value.counterrate }}
                    </td>
                    <td>
                        <CustomInput inputType="number"
                            c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                            id="rate" name="Enter Amount" :has-validation="true"
                            @inputChanged="awardAmount($event, value.offer_id)" input-type="number" :defaultValue="null"
                            :hasSpecificError="false" />
                    </td>
                    <td v-if="value.counter_status">
                        <InviteCard :text="value.counter_status" />
                    </td>
                    <td v-else>
                        -
                    </td>
                    <td>
                        <TableActions @showCounterOffer="showCounterOffer" @showOffer="showOffer" :id="index">
                        </TableActions>
                    </td>

                </tr>
            </tbody>

        </table>
        <div class="mt-3" v-if="data">
            <Pagination @click-next-page="getPageData" v-if="data?.links" :data="data" />
        </div>

        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-end gap-2">
                <CustomSubmit @action="goBack" :previous="true" title="Previous" />
                <CustomSubmit :isLoading="submitting" @action="submitOffers" title="Submit" />
            </div>
        </div>
        <OfferSummary v-if="view_offer" :offerIndex="offer_index" :show="view_offer" @closeModal="closeShowOffer">
        </OfferSummary>
        <Counter v-if="view_counter_offer" :offerIndex="offer_index" :show="view_counter_offer"
            @closeModal="closeShowOffer" />
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" title="Offer has  been submitted!"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..Prepare your funds for
                transfer</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            :title="fail_title" :showm="fail">
            <div class="ml-5 description-text-withdraw " v-if="fail_message">{{ fail_message }}</div>
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
import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, calculateIterestOnProduct } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import TableActions from './actions/TableActions.vue'
import ShowCG from '../../shared/ShowCG.vue'
import * as types from '../../../../store/modules/repos/mutation-types';
import FilterBox from "../../../shared/Table/ReviewOffersFilterBox";
import OfferSummary from './osummary/OfferSummary.vue'
import Counter from './osummary/Counter.vue'
export default {
    data() {
        let columnss = ['Settlement Date ', 'Collateral Giver', 'Product', 'Basket Type', 'Term', 'Rate Type', 'Rate', 'Counter', 'Awarded Amount', 'Counter', 'Action'];

        return {
            success: false,
            fail: false,
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
            offers_to_submit: []
        }
    },
    mounted() {
        this.getUrlPArams()
        if (this.requestId)
            this.getOffers("/trade/CT/get-trade-request-offers?requestId=" + this.requestId);

    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview']),
    },

    components: {
        CustomSubmit,
        ActionMessage,
        Counter,
        FilterBox,
        institutionProfile,
        ShowCG,
        TableActions,
        CustomInput,
        Table,
        OfferSummary,
        InviteCard,
    },
    methods: {

        goBack() {
            window.location.href = '/repos-reviews'
        },
        awardAmount(newvalue, valueid) {
            let offer_objects = this.offers_object
            let acc = {}
            newvalue = newvalue.replace(/,/g, '')
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
            Object.values(offer_objects).forEach(element => {
                if (element.awarded_amount != null) {
                    this.offers_to_submit.push({
                        'offerId': element.offerId,
                        'awarded_amount': element.awarded_amount
                    })
                    count += 1
                    sum += Number.parseFloat(element.awarded_amount)
                    weightedavg += Number.parseFloat(element.rate)
                    // interest amount
                    let rowinterest = calculateIterestOnProduct(
                        element.awarded_amount,
                        element.term,
                        element.term_type,
                        null,
                        element.rate
                    );
                    interest_amout += rowinterest;
                }
            })

            let res = {
                'awarded_amount': `CAD ${addCommasToANumber(sum)}`,
                'interest_amout': `CAD ${addCommasToANumber(interest_amout)}`,
                'weightedavg': `${(weightedavg / count).toFixed(2)}`,

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
        submitOffers() {
            if (this.offers_to_submit.length > 0) {
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
                        setTimeout(() => {
                            // window.location.href = "/view-all-new-requests"
                            this.success = false
                            this.goBack()
                        }, 3000)
                    } else {
                        this.fail = true

                    }
                }).catch(err => {
                    this.submitting = false
                    this.fail = true
                })
            } else {
                this.fail_title = "No Offer selected"
                this.fail_message = "Please select at least one offer by entering amount to award field"
                this.submitting = false
                this.fail = true
            }
        },
        getOffers(url) {
            // this.table_data.push([1, 1, 1, 1, 1, 1, 1, 1, 1, 1])
            let this_ = this;
            // url = url ? url : "/trade/CT/get-trade-request-offers?requestId=";
            axios.get(url)
                .then(response => {

                    let table_data = [];
                    let earnedInterests = [];
                    this_.data = response?.data;
                    // console.log(response)
                    Object.values(response?.data).forEach(item => {
                        let lates_counter = null;
                        table_data.push({

                            offer_id: item.encoded_id,
                            settledata: item?.c_t_trade_request.trade_time,
                            collateralgiver: item.invitee.organization,
                            product: item?.product?.product_name,
                            basket_type: item?.basket?.basket_id,
                            term: item?.offer_term_length + " " + item.offer_term_length_type,
                            rate_type: item?.rate_type == 'fixed' ? 'Fixed' : 'Variable',
                            rate: item?.offer_interest_rate,
                            awarded_amount: "Input Field",
                            counter_status: item.counter_offers.length > 0 ? item.counter_offers[0].status : false,
                            counterrate: item.counter_offers.length > 0 ? item.counter_offers[0].offer_interest_rate.toFixed(2) + '%' : '-',
                            offer: item
                            // counter_status: item?.depositor_request_id,
                        });
                        this.offers_object[item.encoded_id] = {
                            'rate': item.offer_interest_rate,
                            'offerId': item.encoded_id,
                            'awarded_amount': null,
                            'term': item?.offer_term_length,
                            "term_type": item.offer_term_length_type,

                        }
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
    props: ['actionId', "deposit_request", "encoded_deposit_request_id", "shouldNotPerformNoAction", "offersselectsubmitting", "fiorganizations"]

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
</style>