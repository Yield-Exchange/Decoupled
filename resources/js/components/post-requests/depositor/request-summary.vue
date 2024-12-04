<template>
    <div
        style=" width: 99%;min-height: 736px;flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 0px; display: inline-flex;margin-left: 2%; overflow: auto;">
        <accordion :is_open="true" title="Request Summary" width="100"
            title_image="/assets/dashboard/icons/Setting__3.svg" />
        <div style="width: 99%;">

            <table class="table borderless">
                <tbody>
                    <tr>
                        <td class="label-table">Product</td>
                        <td class="label-table">Term</td>
                        <td class="label-table">Lockout</td>
                        <td class="label-table">
                            <div style="min-width: 170px!important;">Request Amount</div>
                        </td>
                        <td class="label-table">Awarded Amount</td>
                        <td class="label-table">Weighted Average</td>
                        <td class="label-table">Interest Amount</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="textContainer">{{ deposit_request?.product_name }}</div>
                        </td>
                        <td>
                            <div v-if="capitalize(deposit_request?.term_length_type) == 'Hisa'">
                                {{ capitalize(deposit_request?.term_length_type) }}</div>
                            <div v-else class="textContainer">{{ deposit_request?.term_length }} {{
            capitalize(deposit_request?.term_length_type) }}</div>
                        </td>
                        <td>
                            <div v-if="deposit_request?.lockout_period_days > 0" class="textContainer">{{
            deposit_request?.lockout_period_days }} Days</div>
                            <div v-else class="textContainer">-</div>
                        </td>
                        <td>
                            <div class="textContainer">{{ deposit_request?.currency }}. {{
            addCommans(deposit_request?.amount) }}</div>
                        </td>
                        <td>
                            <div style="min-width: 180px!important;">
                                <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                    p-style="width: 100%;" name="" :disabled="true"
                                    c-style="font-weight: 400;width:100%;background:white" id="awarded_amount"
                                    :defaultValue="ftotalAwardedAmount" :has-validation="true" />
                            </div>

                        </td>
                        <td style="min-width: 100px!important;">
                            <div style="min-width: 180px!important;">
                                <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                    p-style="width: 100%;" name="" :disabled="true"
                                    c-style="font-weight: 400;width:100%;background:white" id="awarded_amount"
                                    :defaultValue="fweightedAvgRate" :has-validation="true" />
                            </div>

                        </td>
                        <td style="min-width: 100px!important;">
                            <div style="min-width: 180px!important;">
                                <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                    p-style="width: 100%;" name="" :disabled="true"
                                    c-style="font-weight: 400;width:100%;background:white" id="awarded_amount"
                                    :defaultValue="finterestEarned" :has-validation="true" />
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>





        </div>
        <div style="justify-content: flex-start;align-items: flex-start;display: inline-flex;width: 99%;">
            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom"
                style="width:100%;">
                <b-tab title="All Offers" active>
                    <RequestOffers :deposit_request="deposit_request"
                        :encoded_deposit_request_id="encoded_deposit_request_id"
                        :offersselectsubmitting="offersselectsubmitting"
                        :shouldNotPerformNoAction="shouldNotPerformNoAction" :fiorganizations="fiorganizations" />
                    <div style="justify-content: flex-end;align-items: flex-end;display: inline-flex;width: 100%; ">
                        <div style="justify-content: flex-end;align-items: flex-end;display:inline-flex; gap:20px; ">
                            <b-button @click="redirectToDetailsPage('review')"
                                style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                                Previous
                            </b-button>
                            <b-button @click="submitSelected"
                                :disabled="offersselectsubmitting || shouldNotPerformNoAction || getAllSelectedRequestOffers.length === 0"
                                style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">

                                <b-spinner label="Loading" v-if="offersselectsubmitting"
                                    style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                </b-spinner>
                                Submit
                            </b-button>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Invited Institutions">
                    <RequestInstitution :deposit_request="deposit_request"
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


<script>
import { userCan } from "../../../utils/GlobalUtils";
import Accordion from "../../shared/Accordion";
import RequestInstitution from "./RequestInstitution.vue";
import RequestOffers from "./RequestOffers.vue";
import CustomInput from "../../shared/CustomInput";

import { formatTimestamp } from "../../../utils/dateUtils";
import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, calculateIterestOnProduct, sanitizeAmount } from "../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../store/modules/postreq/mutation-types.js';

import GeneralErrorNoInteraction from "../../shared/messageboxes/GeneralNoInteractionError";
import SucessMessageBox from "../../shared/messageboxes/OneButtonActionMessageBox";

export default {
    mounted() {
    },
    computed: {
        ...mapGetters('postreq', ['getAllSelectedRequestOffers', 'getPickedSelectedRequestOffers']),
    },
    components: {
        GeneralErrorNoInteraction,
        CustomInput,
        Accordion,
        RequestInstitution,
        RequestOffers,
        SucessMessageBox
    },
    created() {
    },
    props: ['offers', 'deposit_request', 'encoded_deposit_request_id', 'fiorganizations'],
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
            ratetoUse: 0
        }
    },
    watch: {
        getAllSelectedRequestOffers: {
            handler(newVal, oldVal) {
                this.fweightedAvgRate = 0
                let totalofferedamount = 0;
                let rowinterest = 0;
                let totalearnedInterest = 0;
                let total_off = 0,
                    off = 0,
                    real_off = 0,
                    rate = 0,
                    sm_rate = 0;
                newVal.map((row, key) => {
                    console.log(row, 'Row data')
                    const oldItem = oldVal[key];
                    let ratetoUse = row.rate;
                    if (parseFloat(row.awarded) > 0) {
                        totalofferedamount += parseFloat(row.awarded);
                        total_off += parseFloat(row.awarded);
                        off = parseFloat(row.awarded);

                        if (row.latest_counte) {
                            if (row.latest_counter.status === 'ACCEPTED') {
                                ratetoUse = row.latest_counter.offered_interest_rate;
                            } else {
                                if (row.latest_counter.label === 'Counter Received') {
                                    ratetoUse = row.latest_counter.offered_interest_rate;
                                } else if (row.latest_counter.label === 'Counter Sent') {
                                    ratetoUse = row.rate;
                                } else {
                                    ratetoUse = row.rate;
                                }
                            }
                        } else {
                            ratetoUse = row.rate;
                        }
                        rowinterest = calculateIterestOnProduct(
                            row.awarded,
                            this.deposit_request.term_length,
                            this.deposit_request.term_length_type,
                            this.deposit_request.product_name,
                            ratetoUse
                        );
                        console.log(row.awarded, "Row awarded", rowinterest)
                        totalearnedInterest += rowinterest;
                        this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_EARNED_RATES, {
                            offer_id: row.offer_id,
                            field: 'row_rate',
                            value: rowinterest
                        });
                        rate = ratetoUse;
                        console.log((parseFloat(off), ' * ', parseFloat(rate)) / 100, 'no data')
                        sm_rate += (parseFloat(off) * parseFloat(rate)) / 100;

                    } else {
                        this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_EARNED_RATES, {
                            offer_id: row.offer_id,
                            field: 'row_rate',
                            value: 0
                        });
                    }

                });
                let weigtedAvg = ((sm_rate / total_off) * 100);
                //  let weigtedAvg = addCommasAndDecToANumber((totalearnedInterest / totalofferedamount) * 100);
                if (!isNaN(weigtedAvg)) {
                    this.fweightedAvgRate = weigtedAvg.toFixed(2) + " %";
                }

                this.ftotalAwardedAmount = "CAD " + addCommasAndDecToANumber(totalofferedamount);
                this.finterestEarned = "CAD " + addCommasAndDecToANumber(totalearnedInterest);
            },
            deep: true
        }
    },
    methods: {
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
            this.getAllSelectedRequestOffers.map((val, key) => {
                awardedTotal += (val.awarded != '') ? parseFloat(val.awarded) : 0;
                if (val.awarded != '' && val.awarded_error.length === 0) {
                    deposit_idd = val.depositor_request_id;
                    selected.push({
                        id: val.offer_id,
                        offered_amount: sanitizeAmount(val.awarded)
                    });
                }

            })
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
    }
}
</script>