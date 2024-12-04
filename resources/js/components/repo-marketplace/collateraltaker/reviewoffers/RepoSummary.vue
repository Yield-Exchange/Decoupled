<template>
    <div>
        <accordion :is_open="true" title="Request Summary" width="100"
            title_image="/assets/dashboard/icons/reposummary.svg" />
        <div class="mt-2">

            <table class="table borderless" style="background-color: transparent !important;" v-if="getRequestSummary">
                <tbody>
                    <tr>
                        <td class="label-table">Request ID</td>
                        <td class="label-table">Settlement Date</td>
                        <td class="label-table">Term Length</td>
                        <td class="label-table">
                            <div class="d-flex gap-2 ">
                                Day Count
                                <img src="/assets/dashboard/icons/helpicon.svg" id="summaryre" alt="" srcset="">
                                <Tooltip v-if="true"
                                    message="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                                    target="summaryre" />
                            </div>
                        </td>
                        <td class="label-table">
                            <div style="min-width: 170px!important;">Requested Amount</div>
                        </td>
                        <td class="label-table">Awarded Amount</td>
                        <td class="label-table">Weighted Average(%)</td>
                        <td class="label-table">Interest Amount</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="textContainer">{{ getRequestSummary?.reference_no }}
                            </div>
                        </td>
                        <td>
                            <div class="textContainer">{{ getRequestSummary?.settlement_date ?
            formatTimestamp(getRequestSummary?.settlement_date, false) : '-' }}
                            </div>
                        </td>
                        <td>
                            <div class="textContainer">{{ getRequestSummary?.term_length }} {{
            capitalize(getRequestSummary?.term_length_type) }}</div>
                        </td>
                        <td>
                            <div class="textContainer">{{
            getRequestSummary?.interest_calculation_option ?
                getRequestSummary?.interest_calculation_option.label : '-'
        }}</div>
                        </td>
                        <td>
                            <div class="textContainer">{{ getRequestSummary?.currency }} {{
            addCommans(getRequestSummary?.investment_amount) + ' (' +
            abreviateNumber(getRequestSummary?.investment_amount) + ')' }}</div>
                        </td>
                        <td>
                            <div class="textContainer">{{ calcvalst?.awarded_amount }}</div>
                        </td>
                        <td style="min-width: 100px!important;">
                            <div class="textContainer">{{ calcvalst?.weightedavg ? calcvalst?.weightedavg : 0 }}</div>
                        </td>
                        <td style="min-width: 100px!important;">
                            <div class="textContainer">{{ calcvalst?.interest_amout }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="getRequestSummary"
            style="justify-content: flex-start;align-items: flex-start;display: inline-flex;width: 99%;">
            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom"
                style="width:100%;">
                <b-tab title="All Offers" active>
                    <RequestOffers :isloading="has_offer >= 1" :userLoggedIn="userLoggedIn"
                        @awardedamt="calcvalst = $event" :deposit_request="deposit_request"
                        :encoded_deposit_request_id="encoded_deposit_request_id"
                        :offersselectsubmitting="offersselectsubmitting"
                        :shouldNotPerformNoAction="shouldNotPerformNoAction" :fiorganizations="fiorganizations" />
                </b-tab>
                <b-tab title="Invited Institutions">
                    <RequestedCGs :deposit_request="deposit_request"
                        :encoded_deposit_request_id="encoded_deposit_request_id" :fiorganizations="fiorganizations" />

                </b-tab>
            </b-tabs>
        </div>
        <GeneralErrorNoInteraction :title="errorTitle" :message="errorMsg" :show="showErrorMsg" size="md"
            @closedModal="showErrorMsg = false" />
        <SucessMessageBox :title="successTitle" :message="successMsg" :show="showSuccessMsg" size="md" :showokbtn="true"
            @closedSuccessModal="redirectToDetailsPage('reloadpage')" btnOneText="Review Offers"
            btnTwoText="Pending Deposits" @btnOneClicked="redirectToDetailsPage('review')"
            @btnTwoClicked="redirectToDetailsPage('pending')" />

    </div>
</template>



<script>
import { userCan } from "../../../../utils/GlobalUtils";
import Accordion from "../../../shared/Accordion";
import RequestedCGs from "./RequestedCGs.vue";
import RequestOffers from "./RequestOffers.vue";
import CustomInput from "../../../shared/CustomInput";

// import { formatTimestamp } from "../../../../utils/dateUtils";
import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, calculateIterestOnProduct, sanitizeAmount, calculateSettlementLabel } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/repos/mutation-types';

import GeneralErrorNoInteraction from "../../../shared/messageboxes/GeneralNoInteractionError";
import SucessMessageBox from "../../../shared/messageboxes/OneButtonActionMessageBox";
import Tooltip from "../../../shared/Tooltip.vue";

export default {
    beforeMount() {
        this.getUrlPArams()
        if (this.request_id) {
            this.getTradeRequest()
        }
        this.getSettlementDates()

    },
    mounted() {

    },
    computed: {
        ...mapGetters('repopostrequest', ['getRequestSummary']),
    },
    components: {
        GeneralErrorNoInteraction,
        CustomInput,
        Accordion,
        RequestedCGs,
        RequestOffers,
        SucessMessageBox,
        Tooltip

    },
    created() {
    },
    props: ['offers', 'deposit_request', 'encoded_deposit_request_id', 'fiorganizations', 'userLoggedIn'],
    data() {
        // console.log(this.deposit_request, "deposit_requestdeposit_request");
        return {
            shouldNotPerformNoAction: (this.deposit_request?.request_status === 'ACTIVE') ? false : true,
            offersselectsubmitting: false,
            showSuccessMsg: false,
            successMsg: 'The institution will be notified',
            successTitle: 'Offers Selected',
            showErrorMsg: false,
            errorMsg: '',
            errorTitle: '',
            details: null,
            existing: null,
            action: 'view',
            is_modal: false,
            calcvalst: null,
            ftotalAwardedAmount: "CAD 0",
            fweightedAvgRate: "0.00 %",
            finterestEarned: "CAD 0.00",
            totalAwardedAmount: 0,
            weightedAvgRate: 0,
            interestEarned: 0,
            ratetoUse: 0,
            request_id: null,
            has_offer: 0
        }
    },

    methods: {
        abreviateNumber(number) {
            return formatNumberAbbreviated(number);
        },
        calculateSettlementLabel(value) {
            return calculateSettlementLabel(value)
        },
        async getSettlementDates() {
            await axios.get('/common/trade/get-settlement-dates').then(response => {
                // console.log('set data', response.data)
                let sdates = response.data
                if (sdates.length > 0) {
                    sdates.forEach((date, index) => {
                        date['formated_date'] = `${date.trade_date_label} + ${date.duration} ${date.period_label}`
                    })
                }
                // console.log(sdates,'sdates')
                this.$store.commit('repopostrequest/' + types.SET_SETTLEMENT_DATE, sdates);

            }).catch(err => {
                console.log(err, 'Error')
            })
        },
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];
            this.request_id = numberPart
            return numberPart
        },

        async getTradeRequest() {
            await axios.get('/trade/CT/get-trade-request?requestId=' + this.request_id).then(response => {
                // console.log(response.data)
                this.has_offer = response.data?.total_offers
                this.$store.commit('repopostrequest/' + types.SET_REQUEST_SUMMARY, response.data);

            })

        },
        back() {
            window.location.href = '/review-offers';
        },

        redirectToDetailsPage(to) {

            this.showSuccessMsg = false;

            if (to === 'pending') {
                window.location.href = '/pending-deposits';
            } else if (to === 'review') {
                window.location.href = '/review-offers';
            } else {
                window.location.href = '/pending-deposits';
            }


        },
        formatTimestamp(newvalue, hastime) {
            return formatTimestamp(newvalue, hastime)
        },
        addCommans(newvalue) {
            return addCommasToANumber(newvalue);
        },
        capitalize(thestring) {
            if (thestring != null || thestring != null) {
                return thestring.charAt(0).toUpperCase() + thestring.slice(1).toLowerCase();
            }
        },
        submitSelected() {
            this.offersselectsubmitting = true;
            let deposit_idd = '';
            let selected = [];
            let awardedTotal = 0;
            // this.getAllSelectedRequestOffers.map((val, key) => {
            //     awardedTotal += (val.awarded != '') ? parseFloat(val.awarded) : 0;
            //     if (val.awarded != '' && val.awarded_error.length === 0) {
            //         deposit_idd = val.depositor_request_id;
            //         selected.push({
            //             id: val.offer_id,
            //             offered_amount: sanitizeAmount(val.awarded)
            //         });
            //     }

            // })
            if (parseInt(awardedTotal) > parseInt(this.deposit_request?.amount)) {
                this.errorTitle = "Selecting Offers Error";
                this.errorMsg = "The awarded amount is more than the rquested amount.";
                this.showErrorMsg = true;
                this.rateHeldUntilError = '';
                this.offersselectsubmitting = false;
                return;
            }

            if (selected.length === 0) {
                this.errorTitle = "Selecting Offers Error";
                this.errorMsg = "Please select offers by awarding valid amounts.";
                this.showErrorMsg = true;
                this.rateHeldUntilError = '';
                this.offersselectsubmitting = false;
                return;
            }
            //submit
            let url = '/submit-selected-offers';
            let formData = new FormData();
            formData.append("req_id", this.encoded_deposit_request_id);
            formData.append("offers", JSON.stringify(selected));
            axios.post(url, formData, {
            }).then(response => {

                if (response?.data?.success) {
                    console.log(response, "success");
                    this.successTitle = response?.data.message_title;
                    this.successMsg = response?.data?.message;
                    this.showSuccessMsg = true;
                } else {
                    console.log(response, "failure 2");
                    this.errorTitle = response?.data?.message_title;
                    this.errorMsg = response?.data?.message;
                    this.showErrorMsg = true;
                }


            }).catch(error => {
                this.offersselectsubmitting = false;
                if (error.response) {

                    this.errorTitle = "Error Submitting Selected Offers";
                    this.errorMsg = "Please contact admin.";
                    this.showErrorMsg = true;
                } else if (error.request) {
                    console.log(response, "failure 4");
                    console.error("Request made but no response received:", error.request);
                } else {
                    console.log(response, "failure 5");
                    console.error("Error during request setup:", error.message);
                }

            });
            //submit





        },
        back() {

        },
        userCan(user, permission) {
            return userCan(user, permission);
        },
    },
    watch: {
        getRequestSummary() {
            this.calcvalst = {
                'awarded_amount': `${this.getRequestSummary?.currency} ${addCommasToANumber(0)}`,
                'interest_amout': `${this.getRequestSummary?.currency} ${addCommasToANumber(0)}`,
                'weightedavg': `${(0).toFixed(2)}`,
            }
        }
    }
}
</script>

<style scoped>
.textContainer {
    width: 100%;
    height: 100%;
    color: #5063F4;
    font-size: 16px;
    font-family: Montserrat !important;
    font-weight: 700;
    word-wrap: break-word
}

.borderless,
.borderless th,
.borderless th,
.borderless td {
    border: none !important;
}

.borderless thead {
    border-bottom: none !important;
}

.no-border {
    border-bottom: none;
}

.label {
    color: #5063F4;
    font-size: 16px !important;
    font-family: Montserrat;
    font-weight: 700 !important;
    text-transform: capitalize;
    line-height: 26px;
    word-wrap: break-word;
}

.label-table {
    color: #5063F4;
    font-size: 16px !important;
    font-family: Montserrat;
    font-weight: 700 !important;
    text-transform: capitalize;
    line-height: 26px;
    word-wrap: break-word;
}

.label-table-blue {
    color: #5063F4 !important;
    font-size: 16px !important;
    font-family: Montserrat;
    font-weight: 700 !important;
    text-transform: capitalize;
    line-height: 26px;
    word-wrap: break-word;
}
</style>