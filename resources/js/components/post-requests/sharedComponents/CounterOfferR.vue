<template>
    <div>

        <b-button v-if="from !== 'page'" @click="openCounterModal" :disabled="shouldNotPerformNoAction"
            style=" width:120px;  height: 30px !important;padding:3px !important;border-radius: 20px !important; font-size:15px !important; border: 2px !important;background-color: #9CA1AA !important;color: white !important;">
            Counter Offer
        </b-button>
        <b-button v-else @click="openCounterModal" :disabled="shouldNotPerformNoAction"
            style=" width:290px;  height: 40px !important;padding:3px !important;border-radius: 20px !important; font-size:15px !important; border: 2px !important;background-color: #D9D9D9 !important;color: white !important;">
            Counter Offer
        </b-button>
        <Modal :show="show" @isVisible="$emit('show', $event)" modalsize="xl" @closeModal="show = false">
            <div>
                <div class="counter-offer-container">
                    <div class="original_offer">
                        <div class="header_section">Current</div>
                        <div class="original_info" style="margin-top: 25px !important;">
                            <div class="sub_header">
                                {{ hasNoticePeriod ? deposit_request?.lockout_period_days + ' Day ' +
            deposit_request.product_name
            :
            deposit_request.product_name }}
                            </div>
                            <div class="original_rate">{{ addCommas(offer?.rate) }}%</div>
                            <div class="original_bank">{{ deposit_request?.user.organization.name }}</div>
                            <div class="original_deposit">Interest Earned: CAD
                                {{ addCommas(initialIntereofstRateEarned) }} </div>

                            <div class="original_dates">
                                <div class="sub_dates"><span style="color: #5063F4;">Date of Deposit</span>{{
            formatTimestamp(deposit_request?.date_of_deposit, false)
        }} </div>
                                <div class="sub_dates"><span style="color: #5063F4;">Rate Held Until </span>

                                    <TimerClock variant="success" :timezone="offer?.timezone"
                                        :target-time="offer?.rate_held" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="counter_offer">
                        <div class="header_section">Counter</div>
                        <div style="margin-top: 20px; min-width:200px !important;">
                            <b-row>
                                <FormLabelRequired style="padding: 4px;" labelText="Deposit Amount" required="true"
                                    :showHelperText="false" helperText="Deposit Amount" helperId="depositAmount" />
                                <CustomCurrencyValueInput :selectedCurrency="selectedCurrency"
                                    :currencyOptions="currencyOptions" inputType="number" :defaultValue="depositAmount"
                                    @inputChanged="setValueFromField($event, 'depositAmount')" />
                                <span class="error-message" style="margin-left: 20%;"
                                    v-if="deposit_amount_error.length > 0">{{ deposit_amount_error }}</span>

                            </b-row>

                            <b-row v-if="deposit_request?.term_length_type != 'HISA'">
                                <div style="display: flex; flex-direction:row;">
                                    <div
                                        style="display: flex; width:50%; padding-top:13px;  flex-direction:column;justify-content:flex-start;">
                                        <FormLabelRequired labelText="Interest Rate" required="true"
                                            :showHelperText="false" helperText="Interest Rate"
                                            helperId="interestRate" />

                                        <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                            p-style="width: 100%;  height:50px; mergin-right:20px;"
                                            c-style="font-weight: 400;width:100%;background:white" id="interestRate"
                                            name="Rate*" :has-validation="true" :defaultValue="intialRate"
                                            inputType='text' v-model="interestRate"
                                            @inputChanged="setValueFromField($event, 'counteredRateWith')" />
                                        <span class="error-message" v-if="rate_error.length > 0">{{ rate_error }}</span>
                                    </div>
                                    <div
                                        style="display: flex; width:50%; padding-left:5px; padding-top:13px;  flex-direction:column; justify-content:flex-start;">
                                        <!-- <CustomDate label="Rate Held Until " :required="false" placeholder="2012/2/23"
                                            input_type="text" @input="setValueFromField($event,'rateHeldUntil')" /> -->
                                        <FormLabelRequired labelText="Rate Held Until" required="true"
                                            :showHelperText="false" helperText="Interest Rate"
                                            helperId="interestRate" />
                                        <CustomSystemDate :timezone="timezone" @input="setRateHeld($event)" />
                                        <!-- <flat-pickr :config="config" class="form-control" placeholder="Select date"
                                            name="date" /> -->
                                        <span class="error-message" v-if="rateHeldUntilError.length > 0">{{
            rateHeldUntilError }}</span>

                                    </div>
                                </div>

                            </b-row>
                            <b-row v-else>
                                <div style="display: flex; flex-direction:row; gap:10px;">
                                    <div
                                        style="display: flex; width:50%; padding-top:13px; flex-direction:column;justify-content:flex-start;">
                                        <FormLabelRequired labelText="Rate type" :required="true" :showHelperText="true"
                                            helperText="Rate type" helperId="ratetype" />
                                        <b-select v-model="selectedRateType" @change="setRateType"
                                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                                            <option v-for="item in rate_types" :key="item.id" :value="item">{{ item.name
                                                }}
                                            </option>
                                        </b-select>

                                        <!-- <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                            p-style="width: 100%;"
                                            c-style="font-weight: 400;width:100%;background:white"
                                            :data="['Fixed', 'Variable']" id="product_type_id" name="Product Type*"
                                            :has-validation="false" default-value="Fixed" v-model="rate_type"
                                            @selectChanged="changeRateType($event)" /> -->
                                    </div>
                                    <div
                                        style="display: flex; padding-top:13px; width:50%;flex-direction:column;justify-content:flex-start; gap:3px;">

                                        <FormLabelRequired
                                            :labelText="(selectedRateType.name !== 'Fixed') ? 'Spread(%)' : 'Interest Rate'"
                                            required="true" :showHelperText="false" helperText="Spread"
                                            helperId="Spread" />
                                        <CustomRateValueInput v-if="selectedRateType.name !== 'Fixed'"
                                            :selectedCurrency="selectedCounter" :currencyOptions="operatorsList"
                                            inputType="number" :defaultValue="plus_minus_rate"
                                            @selectedValue="setValueFromField($event, 'selectedCounter')"
                                            @inputChanged="setValueFromField($event, 'plus_minus_rate')" />
                                        <CustomInput v-else
                                            :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                            p-style="width: 100%;  height:50px; mergin-right:20px;"
                                            c-style="font-weight: 400;width:100%;background:white" id="interestRate"
                                            name="Rate*" :has-validation="true" :defaultValue="intialRate"
                                            inputType='text' v-model="interestRate"
                                            @inputChanged="setValueFromField($event, 'counteredRateWith')" />
                                        <span class="error-message" v-if="rate_error.length > 0">{{ rate_error }}</span>
                                    </div>
                                </div>
                            </b-row>
                            <b-row
                                v-if="deposit_request?.term_length_type === 'HISA' && (selectedRateType.name !== 'Fixed')">
                                <div style="display: flex; flex-direction:row; gap:5px;">
                                    <div
                                        style="display: flex; padding-top:13px; width:50%; padding-left:5px;  flex-direction:column; justify-content:flex-start;">
                                        <FormLabelRequired labelText="Interest Rate" required="true"
                                            :showHelperText="false" helperText="Interest Rate"
                                            helperId="Interest Rate" />
                                        <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                            p-style="width: 100%;"
                                            c-style="font-weight: 400;width:100%;background:white" id="prime_rate"
                                            name="Prime Rate" :defaultValue="counteredRateWith" :has-validation="false"
                                            :disabled="true" />


                                    </div>
                                    <div
                                        style="display: flex; width:50%; padding-top:13px; padding-left:5px; flex-direction:column; justify-content:flex-start;">

                                        <FormLabelRequired labelText="Rate Held Until" required="true"
                                            :showHelperText="false" helperText="Interest Rate"
                                            helperId="interestRate" />
                                        <CustomSystemDate :timezone="timezone" @input="setRateHeld($event)" />
                                        <!-- <flat-pickr :config="config" class="form-control" placeholder="Select date"
                                    name="date" /> -->
                                        <span class="error-message" v-if="rateHeldUntilError.length > 0">{{
            rateHeldUntilError }}</span>

                                    </div>
                                </div>
                            </b-row>
                            <b-row
                                v-if="deposit_request?.term_length_type === 'HISA' && (selectedRateType.name === 'Fixed')">
                                <div style="display: flex; flex-direction:row; gap:5px;">
                                    <div
                                        style="display: flex; width:100%; padding-left:5px;  flex-direction:column; justify-content:flex-start;">

                                        <FormLabelRequired labelText="Rate Held Until" required="true"
                                            :showHelperText="false" helperText="Interest Rate"
                                            helperId="interestRate" />
                                        <CustomSystemDate :timezone="timezone" @input="setRateHeld($event)" />
                                        <!-- <flat-pickr :config="config" class="form-control" placeholder="Select date"
                                name="date" /> -->
                                        <span class="error-message" v-if="rateHeldUntilError.length > 0">{{
            rateHeldUntilError }}</span>

                                    </div>
                                </div>
                            </b-row>

                            <b-row>
                                <div style="display: flex; flex-direction:row; gap:5px;">
                                    <div
                                        style="display: flex; width:100%; padding-left:5px;   flex-direction:column; justify-content:flex-start;">
                                        <FormLabelRequired style="padding: 4px;" labelText="Interest Rate Changed by"
                                            required="true" :showHelperText="false"
                                            helperText="Interest Rate Changed by" helperId="interestRateChangedby" />
                                        <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                            p-style="width: 100%;"
                                            c-style="font-weight: 400;width:100%;background:white"
                                            id="interestRateChangedby" name="Change in Interest rate"
                                            :defaultValue="rateChange" :has-validation="false" :disabled="true" />
                                    </div>
                                    <div
                                        style="display: flex; width:100%; padding-left:5px;  flex-direction:column; justify-content:flex-start;">
                                        <FormLabelRequired style="padding: 4px;" labelText="Additional interest earned"
                                            required="true" :showHelperText="false"
                                            helperText="Additional interest earned"
                                            helperId="additionalInterestEarned" />

                                        <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                            p-style="width: 100%;"
                                            c-style="font-weight: 400;width:100%;background:white"
                                            id="additionalInterestEarned" name="Additional Interest Earned"
                                            :defaultValue="addtionalInterestAdded" :has-validation="false"
                                            :disabled="true" />
                                    </div>
                                </div>

                            </b-row>
                            <b-row>

                            </b-row>
                            <b-row>

                            </b-row>
                        </div>
                    </div>

                </div>

                <div class="header_section" style="margin-left: 25px;">Counter offer change log</div>
                <div style="width: 100%;padding:25px;">
                    <table class="table" style="width: 100%;">
                        <thead class="customHeader">
                            <tr>
                                <th>#</th>
                                <th>Counter Offer Date</th>
                                <th>Deposit Amount</th>
                                <th>Interest Rate</th>
                                <th>Interest Rate Change</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, index) in getCurrentOfferCounters" :key="index" v-if="index < 2">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="textContainer">
                                        {{ formatTimestamp(value.created_at) }}</div>
                                </td>
                                <td>
                                    <div class="textContainer">CAD {{ addCommas(value.maximum_amount) }}</div>
                                </td>
                                <td>
                                    <div class="textContainer"> {{ value.offered_interest_rate.toFixed(2) }} %</div>
                                </td>
                                <td>
                                    <div class="textContainer"> {{ (value.offered_interest_rate -
            intialRate).toFixed(2) }}
                                        %</div>

                                </td>
                                <td>

                                    <CustomInvitedStatusBadge :text="value.status" />
                                </td>
                            </tr>
                        </tbody>


                    </table>

                </div>


                <b-row>
                    <div class="button-layout">
                        <div class="button-action" @click="submit">
                            <div class="text-button">
                                <b-spinner label="Loading" v-if="counteroffersub"
                                    style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                </b-spinner>
                                Submit
                            </div>
                        </div>
                    </div>
                </b-row>

            </div>
        </Modal>
        <GeneralErrorNoInteraction :title="errorTitle" :message="errorMsg" :show="showErrorMsg" size="md"
            @closedModal="showErrorMsg = false" />
        <SucessMessageBox :title="successTitle" :message="successMsg" :show="showSuccessMsg" size="md"
            :showokbtn="false" @closedSuccessModal="redirectToDetailsPage('reloadpage')" btnOneText="Review Offers"
            btnTwoText="Pending Deposits" @btnOneClicked="redirectToDetailsPage('review')"
            @btnTwoClicked="redirectToDetailsPage('pending')" />
    </div>

</template>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css";

.date-picker {
    padding: 0 20px;
    letter-spacing: 1px;
    font-weight: normal;
}

.has-error {
    border: 0.5px solid red;
}

.input-height {
    height: 40px;
}

.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #ddd;
    background-image: none
}

.b-calendar-grid-body>div>div>.text-muted {
    color: #111 !important;
    font-weight: bold !important;
}

.counter_offer {
    flex-grow: 1;
}

.original_offer {

    flex-grow: 1;
}

.counter-values {
    color: #252525;
    font-family: Montserrat;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.counter-labels {
    color: #000;
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.log-container {
    display: flex;
    padding: 10px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    background: #EFF2FE;
    margin-bottom: 6px;
}

.counter_header {
    color: #5063F4;
    margin-bottom: -3px;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.text-button {
    color: #FFF;
    /* Yield Exchange Text Styles/Buttons Bold */
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    /* 125% */
    text-transform: capitalize;
}

.button-action {
    cursor: pointer;
    border-radius: 20px;
    background: #5063F4;
    padding: 10px 30px;
}

.button-layout {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 30px;
    align-self: stretch;
}

.vertical_line {
    border-left: 0.5px solid #9CA1AA;
    height: 292px;
}

.sub_dates {
    display: flex;
    flex-direction: column;
    color: #252525;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.original_deposit {
    background-color: #EFF2FE;
    color: #252525;
    text-align: center;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    padding-left: 10px;
    padding-right: 10px;
}

.original_bank {
    color: #252525;
    text-align: center;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.original_rate {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat;
    font-size: 55px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.sub_header {
    color: #252525;
    text-align: center;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Promotion chart body */
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.original_dates {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}


.original_info {
    background: #FFF;
    box-shadow: 0px 4px 6px 0px rgba(80, 99, 244, 0.20);
    display: flex;
    padding: 20px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex: 1 0 0;
    align-self: stretch;
    min-height: 289px !important;
}

.review-transaction-details-modal .modal-content {
    max-width: 750px !important;
    align-items: center;
    justify-content: center;
}

.counter-offer-container {
    display: flex;
    gap: 30px;
    flex-direction: row;
    align-items: flex-start;
    padding: 20px;
    font-family: sans-serif;

}

.header_section {
    color: #5063F4;

    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}

.title {
    font-weight: 800;
    font-size: 22px;
}

.info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    max-width: 700px;
    /* Limit the width of the info section */
}

.info-section {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    flex-basis: calc(33.33% - 20px);
    /* Accommodate only 3 elements per row with spacing */
}

.info-label {
    font-size: 16px;
    font-weight: 550;
    line-height: 1.5;
    color: black;
}

.info-value {
    font-size: 16px;
    font-weight: 550;
    line-height: 1.5;
    color: #5063F4;

}

.text-capitalize {
    text-transform: capitalize !important;
}

.submit-button {
    margin-top: 20px;

    align-self: flex-end;
}

.action-button {
    padding: 10px 30px;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    background: #5063F4;
    font-size: 19px;
    font-weight: 700;
    margin-left: 200px;
    text-transform: capitalize;
    color: white;
}

.button-text {
    font-size: 19px;
    font-weight: 700;
    text-transform: capitalize;
    line-height: 1.2;
}

.interest_earned {
    width: 100%;
    height: 100%;
    padding: 5px;
    background: #EFF2FE;
    justify-content: center;
    align-items: center;
    gap: 10px;
    display: inline-flex
}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* You can adjust this value to your desired minimum width */
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
</style>

<script>
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import GeneralErrorNoInteraction from "../../shared/messageboxes/GeneralNoInteractionError.vue";
import Modal from "../../shared/Modal";
import CustomInput from "../../shared/CustomInput.vue";
import CustomDate from "./CustomDate.vue";
import CustomDateInput from './CustomDate.vue';
import FormLabelRequired from "../../shared/formLabels/FormLabelRequired.vue";
import CustomCurrencyValueInput from "./CustomCurrencyValue.vue";

import CustomRateValueInput from "./CustomCurrencyValueRate.vue";

import CustomInvitedStatusBadge from "../sharedComponents/CustomInvitedStatusBadge";
import CustomSystemDate from "../sharedComponents/CustomSystemDate";
import TimerClock from "../sharedComponents/TimerClock";
import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, formatCreatedAtToRequiredTimestamp, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import * as types from '../../../store/modules/postreq/mutation-types.js';
import SucessMessageBox from "../../shared/messageboxes/OneButtonActionMessageBox";

import $ from "jquery";
import datetimepicker from "jquery-datetimepicker";
import CustomSelect from '../../shared/CustomSelect.vue';

export default {
    props: ["offer", "deposit_request", "from", "encoded_deposit_request_id", "shouldNotPerformNoAction", "offersselectsubmitting", "timezone"],
    created() { },
    components: {
        TimerClock,
        CustomRateValueInput,
        CustomSelect,
        CustomSystemDate,
        SucessMessageBox,
        CustomInvitedStatusBadge,
        GeneralErrorNoInteraction,
        Modal,
        FormLabelRequired,
        CustomInput,
        selectedCurrency: 'CAD', // Default currency
        amount: null,
        CustomCurrencyValueInput,
        CustomDate
    },
    computed: {
        ...mapGetters('postreq', ['getCurrentOfferCounters']),
        currencyOptions() {
            return ['CAD']; // Sample currency options
        },
        counterOptions() {
            return ['+', '-']; // Sample currency options
        },
        hasNoticePeriod() {
            // Using the includes method
            //  return (this.deposit_request.product_name.includes("Notice deposit") || this.deposit_request.product_name.includes("Cashable"));
        }
    },
    data() {
        console.log(this.offer, "offer details on modal counter");
        let reviseddetailsbe = this.offer;
        let typeofcurrent = 'offer';
        let topTwoCounters = [];
        if (this.offer.counter_offers.length > 0) {
            // reviseddetailsbe = this.offer.counter_offers[0];
            //  typeofcurrent = 'counter';
            topTwoCounters = this.offer.counter_offers.slice(0, 2);

        }


        return {
            prime_rate: '',
            selectedCounter: {
                id: 1,
                label: 'plus',
                oparatorSymbol: '+',
            },
            selectedRateType: '',
            rate_type: 'Fixed',
            plus_minus_rate: 0,
            counteroffersub: false,
            showSuccessMsg: false,
            successMsg: '',
            successTitle: '',
            typeOfCurrent: typeofcurrent,
            showErrorMsg: false,
            errorMsg: '',
            errorTitle: '',
            rate_error: '',
            deposit_amount_error: '',
            show: false,
            depositAmount: 0,
            interestRate: "0.00",
            selectedCurrency: "CAD",
            depositAmount: this.deposit_request?.amount,
            intialRate: (typeofcurrent == 'offer') ? (reviseddetailsbe?.rate).toFixed(2) : (reviseddetailsbe?.offered_interest_rate).toFixed(2),
            counteredRateWith: 0,
            rateChange: 0,
            rateHeldUntil: '',
            rateHeldUntilError: '',
            addtionalInterestAdded: 0,
            newCalculatedRate: 0,
            initialIntereofstRateEarned: 0,
            operatorsList: [{
                id: 1,
                label: 'plus',
                oparatorSymbol: '+',
            },
            {
                id: 2,
                label: 'minus',
                oparatorSymbol: '-',
            }],
            rate_types: [

            ],
            config: {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            }
        }
    },
    mounted: function () {
        if ($) {
            let defaultstr = new Date().toLocaleString("en-US");
            let defaultstartdate = new Date(defaultstr);
            $("#rate_held_picker").datetimepicker({
                defaultDate: defaultstartdate,
                minDate: defaultstartdate,
                format: "Y-m-d H:i",
            });
        } else {

        }

        this.initialIntereofstRateEarned = calculateIterestOnProduct(
            this.depositAmount,
            this.deposit_request.term_length,
            this.deposit_request.term_length_type,
            this.deposit_request.product_name,
            this.intialRate
        );

    },
    methods: {
        setRateType(value) {
            console.log(this.selectedRateType);
            this.prime_rate = this.selectedRateType.rate
            this.rate_type = this.selectedRateType.label.toLowerCase()
        },
        setRateHeld(date) {

            this.rateHeldUntil = date;
        },
        redirectToDetailsPage(to) {
            this.showSuccessMsg = false;
            window.location.reload()
        },
        async openCounterModal() {
            this.getTheOfferCounters();
            this.getInterestRatesTypes();
            this.show = true;

        },
        getInterestRatesTypes(url = "") {
            url = url ? url : `/get-rate_types`;
            axios.get(url)
                .then(response => {
                    let ratess = [];
                    let loop = 0;
                    response.data.map((val, key) => {
                        if (val.rate_label === 'Fixed') {
                            let fixed = {
                                id: val.rate_label,
                                rate: val.rate_value,
                                label: val.rate_label,
                                name: `${val.rate_label}`
                            };
                            ratess.push(fixed);
                            this.selectedRateType = fixed;
                        } else {
                            ratess.push({
                                id: val.rate_label,
                                rate: val.rate_value,
                                label: val.rate_label,
                                name: `${val.rate_label} (${val.rate_value}%)`
                            });
                        }
                        loop++;

                    });
                    this.rate_types = ratess;
                }).catch(error => {
                    this.is_loading = false;
                    console.log("error > " + error);
                });
        },
        getTheOfferCounters(url = "") {
            url = url ? url : `/get-offer-counters?offer_id=${this.offer.offer_id}`;
            axios.get(url)
                .then(response => {
                    this.prime_rate = response?.data?.prime;
                    this.$store.commit('postreq/' + types.SET_CURRENT_OFFER_COUNTERS, response?.data?.offers);
                }).catch(error => {
                    this.is_loading = false;
                    console.log("error > " + error);
                });
        },
        addCommas(number) {
            return addCommasAndDecToANumber(number);
        },
        formatTimestamp(timeStamp, includetime) {
            let correctlyformattedtimestamp = formatCreatedAtToRequiredTimestamp(timeStamp);
            return formatTimestamp(correctlyformattedtimestamp, includetime);
        },
        submit() {
            this.counteroffersub = true;
            console.log(this.depositAmount + ":this.depositAmount" + this.deposit_request.amount + ":this.deposit_request.amount " + this.counteredRateWith + "this.counteredRateWith")
            if ((sanitizeAmount(this.depositAmount) === sanitizeAmount(this.deposit_request.amount)) && (this.counteredRateWith === 0 || this.counteredRateWith === this.intialRate)) {
                this.errorTitle = "Counter Offer Error";
                this.errorMsg = "No changes .";
                this.showErrorMsg = true;
                this.rateHeldUntilError = '';
                this.counteroffersub = false;
            } else {
                if (this.rateHeldUntil === '') {
                    this.rateHeldUntilError = 'Required';
                    this.counteroffersub = false;

                } else {

                    if (this.rate_error.length > 0.01) {
                        this.errorTitle = "Rate Error";
                        this.errorMsg = this.rate_error;
                        this.showErrorMsg = true;
                        this.counteroffersub = false;
                        return;
                    }
                    if (this.deposit_amount_error.length > 0) {
                        return;
                    }
                    this.rateHeldUntilError = '';
                    let formData = new FormData();
                    console.log(this.offer, "offeroffer");
                    formData.append("request_id", this.offer.depositor_request_id);
                    formData.append("offer_id", this.offer.offer_id);
                    formData.append("min_amount", this.depositAmount);
                    formData.append("max_amount", this.depositAmount);
                    if (this.deposit_request?.term_length_type === 'HISA') {
                        if (this.rate_type === 'fixed' || this.rate_type === 'Fixed') {
                            formData.append("nir", this.counteredRateWith);
                        } else {
                            formData.append("nir", this.interestRate);
                        }

                    } else {
                        formData.append("nir", this.counteredRateWith);
                    }
                    formData.append("counter_offer_expiry", `${this.rateHeldUntil}`);
                    formData.append("rate_held_until", `${this.rateHeldUntil}`);
                    formData.append("expdate", `${this.rateHeldUntil}`);
                    formData.append("expdate", `${this.rateHeldUntil}`);
                    if (this.deposit_request?.term_length_type === 'HISA') {
                        formData.append("rate_type", `${((this.rate_type).toLowerCase()).replace(/\s+/g, "_")}`);
                        formData.append("rate_operator", `${this.selectedCounter}`);
                        formData.append("fixed_rate", `${this.plus_minus_rate}`);
                    } else {
                        formData.append("rate_type", `fixed`);
                    }

                    let url = `counter-offer`;
                    axios.post(url, formData, {
                    }).then(response => {
                        console.log(response, "responseresponse");
                        if (response?.data?.success) {
                            console.log(response, "success");
                            this.getTheOfferCounters();
                            this.successTitle = response?.data.message_title;
                            this.successMsg = response?.data?.message;
                            this.showSuccessMsg = true;
                            this.counteroffersub = false;
                        } else {
                            console.log(response, "failure 2");
                            this.errorTitle = response?.data?.message_title;
                            this.errorMsg = response?.data?.message;
                            this.showErrorMsg = true;
                            this.counteroffersub = false;
                        }

                    }).catch(error => {
                        this.counteroffersub = false;
                        if (error.response) {
                            console.log(error?.response?.data, "response response 4");
                            this.errorTitle = "Error posting your counter offer";
                            this.errorMsg = error?.response?.data.message;
                            this.showErrorMsg = true;
                        } else if (error.request) {
                            console.log(response, "failure 4");
                            console.error("Request made but no response received:", error.request);
                        } else {
                            console.log(response, "failure 5");
                            console.error("Error during request setup:", error.message);
                        }
                    });
                }


            }

        },
        setValueFromField(value, field) {
            console.log(field, value);
            if (field != 'rateHeldUntil') {
                value = sanitizeAmount(value);
            }
            switch (field) {
                case "counteredRateWith":
                    this.counteredRateWith = value;
                    if (value > 100 || value < 1) {
                        this.rate_error = 'Invali Rate';
                    } else {
                        this.rate_error = '';
                    }

                    break;
                case "rateHeldUntil":
                    this.rateHeldUntilError = '';
                    this.rateHeldUntil = value;
                    break;
                case "selectedCounter":
                    this.selectedCounter = value;

                    break;
                case "plus_minus_rate":
                    this.plus_minus_rate = value;
                    break;
                case "depositAmount":
                    if (value > this.offer.max) {
                        this.deposit_amount_error = `Cannot be greater than CAD. ${this.addCommas(this.offer.max)}`;
                        return;
                    } else if (value < this.offer.min) {
                        this.deposit_amount_error = `Cannot be less than CAD. ${this.addCommas(this.offer.min)}`;
                        return;
                    } else {

                        if (value < this.deposit_request?.amount) {
                            this.deposit_amount_error = `Cannot be less than CAD. ${this.addCommas(this.deposit_request?.amount)}`;
                            return;
                        } else if (value > this.deposit_request?.amount) {
                            this.deposit_amount_error = `Cannot be more than CAD. ${this.addCommas(this.deposit_request?.amount)}`;
                            return;
                        }
                        this.deposit_amount_error = '';
                    }
                    this.depositAmount = value;
                    break;
            }
        }

    },
    watch: {
        selectedCounter(newv) {

            console.log("new v rate", newv);
            console.log("new v operator", this.selectedCounter);
            console.log("new v prime_rate", this.prime_rate);
            if (this.plus_minus_rate != 0 || this.plus_minus_rate != '') {
                if (newv.label === "plus") {
                    this.counteredRateWith = parseFloat(this.plus_minus_rate) + parseFloat(this.prime_rate);
                } else if (newv.label === "minus") {
                    this.counteredRateWith = parseFloat(this.prime_rate) - parseFloat(this.plus_minus_rate);
                }
            }
            if (this.counteredRateWith < 0.01 || this.counteredRateWith > 100) {
                this.rate_error = 'Cannot be less than 0.01 or greater than 100.';
            } else {
                this.rate_error = '';
            }

        },
        plus_minus_rate(newv) {
            console.log("new v rate", newv);
            console.log("new v operator", this.selectedCounter);
            console.log("new v prime_rate", this.prime_rate);
            if (this.selectedCounter.label === "plus") {
                this.counteredRateWith = parseFloat(newv) + parseFloat(this.prime_rate);
            } else if (this.selectedCounter.label === "minus") {
                this.counteredRateWith = parseFloat(this.prime_rate) - parseFloat(newv);
            }
            if (this.counteredRateWith < 0.01 || this.counteredRateWith > 100) {
                this.rate_error = 'Cannot be less than 0.01 or greater than 100.';
            } else {
                this.rate_error = '';
            }

        },
        counteredRateWith(newv, old) {

            if (newv !== '' && newv !== null && newv !== undefined) {
                if (newv != this.offer.rate) {
                    this.newCalculatedRate = calculateIterestOnProduct(
                        sanitizeAmount(this.depositAmount),
                        this.deposit_request.term_length,
                        this.deposit_request.term_length_type,
                        this.deposit_request.product_name,
                        newv
                    );
                    this.addtionalInterestAdded = this.newCalculatedRate - this.initialIntereofstRateEarned;
                    this.addtionalInterestAdded = this.addCommas(this.addtionalInterestAdded);
                    this.rateChange = newv - this.offer.rate;
                    this.rateChange = `${this.rateChange.toFixed(2)}%`;
                    console.log(this.newCalculatedRate, "this.newCalculatedRate");
                } else {
                    if (this.depositAmount != this.deposit_request.amount) {
                        this.newCalculatedRate = calculateIterestOnProduct(
                            sanitizeAmount(this.depositAmount),
                            this.deposit_request.term_length,
                            this.deposit_request.term_length_type,
                            this.deposit_request.product_name,
                            newv
                        );
                        this.addtionalInterestAdded = this.newCalculatedRate - this.initialIntereofstRateEarned;
                        this.addtionalInterestAdded = this.addCommas(this.addtionalInterestAdded);
                        this.rateChange = newv - this.offer.rate;
                        this.rateChange = `${this.rateChange.toFixed(2)}%`;
                        console.log(this.newCalculatedRate, "this.newCalculatedRate");
                    } else {
                        this.addtionalInterestAdded = this.addCommas(0);
                        this.rateChange = 0;
                        this.rateChange = this.rateChange + "%";
                    }


                }
            }

        }, depositAmount(newv, old) {

            if (newv !== '' && newv !== null && newv !== undefined) {
                if (newv !== this.deposit_request.amount) {
                    this.initialIntereofstRateEarned = calculateIterestOnProduct(
                        this.deposit_request.amount,
                        this.deposit_request.term_length,
                        this.deposit_request.term_length_type,
                        this.deposit_request.product_name,
                        this.offer.rate
                    );
                    console.log(this.initialIntereofstRateEarned, "this.initialIntereofstRateEarned");
                    this.newCalculatedRate = calculateIterestOnProduct(
                        sanitizeAmount(newv),
                        this.deposit_request.term_length,
                        this.deposit_request.term_length_type,
                        this.deposit_request.product_name,
                        (this.counteredRateWith == 0) ? this.offer?.rate : this.counteredRateWith
                    );
                    this.addtionalInterestAdded = this.newCalculatedRate - this.initialIntereofstRateEarned;
                    this.addtionalInterestAdded = this.addCommas(this.addtionalInterestAdded);
                    this.rateChange = (this.counteredRateWith == 0) ? 0 : this.counteredRateWith - this.offer.rate;
                    this.rateChange = `${this.rateChange.toFixed(2)}%`;
                    console.log(this.newCalculatedRate, "this.newCalculatedRate");
                } else {
                    this.addtionalInterestAdded = this.addCommas(0);
                    this.rateChange = 0;
                    this.rateChange = this.rateChange + "%";
                }
            }
        }
    }
}

</script>