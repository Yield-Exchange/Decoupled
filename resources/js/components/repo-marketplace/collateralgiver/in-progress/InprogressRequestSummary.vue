<template>
    <div class=" w-100">
        <accordion :is_open="true" title="Request Summary" width="100"
            title_image="/assets/dashboard/icons/Setting__3.svg" />
        <div style="width: 100%;" v-if="getRequestSummary">

            <div class="d-flex flex-column">
                <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                    <ViewCard title="Request ID" :desc="getRequestSummary?.reference_no" />
                    <ViewCard title="Investor" :desc="getRequestSummary?.inviter?.name" />

                    <ViewCard title="Settlement Date" :desc="getRequestSummary?.settlement_date ?
            formatTimestamp(getRequestSummary?.settlement_date, false) : '-'" />
                    <!-- <ViewCard title="Settlement Date" :desc="getRequestSummary?.trade_allowed_settlement_period ?
            calculateSettlementLabel(getRequestSummary?.trade_allowed_settlement_period) : '-'" /> -->


                    <ViewCard title="Term Length" :desc="getRequestSummary?.term_length + ' ' +
            capitalize(getRequestSummary?.term_length_type)" />
                    <ViewCard title="Day Count" :hastooltip="true" id="dcc"
                        tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                        :desc="getRequestSummary?.interest_calculation_option ? getRequestSummary?.interest_calculation_option.label : '-'" />

                    <ViewCard title="Requested Amount"
                        :desc="getRequestSummary?.currency + ' ' +
            addCommasToANumber(getRequestSummary?.investment_amount) + ' (' + abreviateNumber(getRequestSummary?.investment_amount) + ')'" />

                    <template v-if="getRequestSummary?.preferred_collaterals.length > 0">
                        <ViewCard :title="'Preferred Coll ' + (index + 1)"
                            v-for="(   value, index   ) in    getRequestSummary?.preferred_collaterals   "
                            :desc="value.collateral_name" :key="index" />
                    </template>
                    <template v-else>
                        <ViewCard title='Preferred Coll' desc="No Preference" />
                    </template>
                </div>
            </div>
        </div>
        <div class="mt-3" tyle="justify-content: flex-start;align-items: flex-start;display: inline-flex;width: 100;">
            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom"
                style="width:100%;">
                <b-tab title="All Offers" active>
                    <RequestOffers :userLoggedIn="userLoggedIn" v-if="organization_data && getRequestSummary"
                        :organization_id="organization_data?.id" :deposit_request="deposit_request"
                        :encoded_deposit_request_id="encoded_deposit_request_id"
                        :offersselectsubmitting="offersselectsubmitting"
                        :shouldNotPerformNoAction="shouldNotPerformNoAction" :fiorganizations="fiorganizations" />
                    <div style="justify-content: flex-end;align-items: flex-end;display: inline-flex;width: 100%; ">
                        <div style="justify-content: flex-end;align-items: flex-end;display:inline-flex; gap:20px; ">
                            <b-button @click="redirectToDetailsPage()"
                                style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                                Previous
                            </b-button>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="About Investor">
                    <AboutDepositor v-if="organization_data" :organization_data="organization_data" />
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
import AboutDepositor from '../../../postrequest/bank/pendingdeposits/AboutDepositor.vue'

// import { formatTimestamp } from "../../../../utils/dateUtils";
import { addCommasToANumber, formatTimestamp, calculateSettlementLabel, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, calculateIterestOnProduct, sanitizeAmount } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/repos/mutation-types';

import GeneralErrorNoInteraction from "../../../shared/messageboxes/GeneralNoInteractionError";
import SucessMessageBox from "../../../shared/messageboxes/OneButtonActionMessageBox";
import ViewCard from "../../../shared/ViewCard.vue";

export default {
    props: ['offers', 'deposit_request', 'encoded_deposit_request_id', 'fiorganizations', 'userLoggedIn'],
    beforeMount() {
        this.getUrlPArams()
        this.getSettlementDates()
        if (this.request_id) {
            this.getTradeRequest()
        }
        this.getProducts()
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
        AboutDepositor,
        ViewCard

    },
    created() {
    },
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
            ftotalAwardedAmount: "CAD 0.00",
            fweightedAvgRate: "0.00 %",
            finterestEarned: "CAD 0.00",
            totalAwardedAmount: 0,
            weightedAvgRate: 0,
            interestEarned: 0,
            ratetoUse: 0,
            request_id: null,
            organization_data: null
        }
    },

    methods: {
        calculateSettlementLabel(value) {
            return calculateSettlementLabel(value)
        },
        abreviateNumber(number) {
            return formatNumberAbbreviated(number);
        },
        addCommasToANumber(number) {
            return addCommasToANumber(number);
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
            await axios.get('/trade/CG/get-trade-request?requestId=' + this.request_id).then(response => {
                // console.log(response.data)
                this.$store.commit('repopostrequest/' + types.SET_REQUEST_SUMMARY, response.data);
                this.organization_data = response.data.inviter
            })

        },
        back() {
            window.location.href = '/repos/review-offers';
        },

        redirectToDetailsPage() {
            window.location.href = '/repos/view-all-in-progress';

        },
        formatTimestamp(newvalue, hastime) {
            return formatTimestamp(newvalue, hastime)
        },
        addCommans(newvalue) {
            return addCommasAndDecToANumber(newvalue);
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
        async getProducts() {
            await axios.get('/common/trade/get-products-list?disabled=0').then(response => {
                // console.log('set data', response.data)
                this.$store.commit('repopostrequest/' + types.SET_GLOBAL_PRODUCTS, response.data);

                // console.log(sdates,'sdates')

            }).catch(err => {
                console.log(err, 'Error')
            })
        },
    },

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
</style>