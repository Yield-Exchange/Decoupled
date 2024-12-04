<template>
    <div>
        <Modal :show="true" @isVisible="$emit('closeModal', $event)" modalsize="xl">

            <div>
                <MessageHeaderIconized title="What adjustment would you like to make?" width="100"
                    title_image="/assets/dashboard/icons/question-new.svg" />
            </div>
            <div class="w-100 p-4">
                <div class=" d-flex flex-column gap-3">
                    <div v-if="from == 'pending'" :class="{ 'overall-bg': stepval.includes('term_length') }">
                        <div class="title-message">
                            <input type="checkbox" v-model="stepval" value="term_length" name="process" id=""> Term
                            Length
                        </div>
                        <div class="row mt-3" v-if="stepval.includes('term_length')">
                            <div class="col-4 col-md-4">
                                <FormLabelRequired labelText="Previous Term Length" :required="true"
                                    :showHelperText="false" helperText="Term Length" helperId="PDSHId" />
                                <div class="combined-input disabled-bg"
                                    style="margin-top: 4px;background-color: white;">
                                    <b-form-select disabled class="disabled-bg" id="termlengthid" v-model="prev_term"
                                        ref="termLengthSelect" :options="['Days', 'Months']" default-value="Days"
                                        style="border: none;min-width:40px !important;width:30%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                    </b-form-select>
                                    <b-form-input class="disabled-bg" disabled
                                        style="border: none;min-width:100px !important;width:70%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                                        type="number" v-model="prev_term_length" step='1' min="0"
                                        placeholder="Enter length " />
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <FormLabelRequired labelText="New Term Length" :required="true" :showHelperText="false"
                                    helperText="Term Length" helperId="PDSHId" />
                                <div class="combined-input" :class="{ 'has-error': termLengtherror }"
                                    style="margin-top: 4px;background-color: white !important;">
                                    <b-form-select class="" id="termlengthid" v-model="new_term" ref="termLengthSelect"
                                        @change="termLengthChange" :options="['Days', 'Months']" default-value="Days"
                                        style="border: none;min-width:40px !important;width:30%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                    </b-form-select>
                                    <b-form-input
                                        style="border: none;min-width:100px !important;width:70%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                                        type="number" v-model="new_term_length" step='1' min="0"
                                        @blur="termLengthChange" :class="{ 'validation-error': termLengtherror }"
                                        placeholder="Enter length " />
                                </div>
                                <div v-if="termLengtherror" class="error-message">
                                    {{ termLengtherror }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <b-col md="12" class="align-items-left "
                                    style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                                    <FormLabelRequired labelText="Reason" required="true" showHelperText="true"
                                        helperText="Reason For Withdrawing the request" helperId="reasonforwith" />
                                    <CustomSelect :attributes="{ 'value_field': 'reason', 'text_field': 'reason' }"
                                        p-style="width: 100%;"
                                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                                        :data="terminationReasons" id="reason_for_withdraw" name="Reason for Withdraw*"
                                        :has-validation="false" :default-value="new_term_length_reason"
                                        v-model="new_term_length_reason" />
                                </b-col>
                            </div>

                        </div>
                    </div>
                    <div v-if="from == 'active'" :class="{ 'overall-bg': stepval.includes('term_length') }">
                        <div class="title-message">
                            <input type="checkbox" v-model="stepval" value="maturity_date" name="process" id="">
                            Maturity
                            Date
                        </div>
                        <div class="row mt-3" v-if="stepval.includes('maturity_date')">
                            <div class="col-4 col-md-4">
                                <FormLabelRequired labelText="Previous Maturity Date" :showHelperText="false"
                                    helperText="Previous Maturity Date" helperId="PDSHId" />
                                <JQueryCustomDatePicker :disabled="true" :cannotpicktime="true" style="" id="rftg"
                                    :formattedtimezone="formattedtimezone" placeholder="Select Trade Date"
                                    :selected_date="old_maturity_date" v-model="old_maturity_date" />

                            </div>
                            <div class="col-4 col-md-4">
                                <FormLabelRequired labelText="New Maturity Date" :required="true"
                                    :showHelperText="false" helperText="New Maturity Date" helperId="PDSHId" />
                                <JQueryCustomDatePicker :cannotpicktime="true" style="" :hasError="newMaturityError"
                                    id="rftgsdc" :formattedtimezone="formattedtimezone" placeholder="Select Trade Date"
                                    :selected_date="new_maturity_date" v-model="new_maturity_date" />
                                <div v-if="newMaturityError" class="error-message">
                                    {{ newMaturityError }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <b-col md="12" class="align-items-left "
                                    style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                                    <FormLabelRequired labelText="Reason" required="true" showHelperText="true"
                                        helperText="Reason For Withdrawing the request" helperId="reasonforwith" />
                                    <CustomSelect :attributes="{ 'value_field': 'reason', 'text_field': 'reason' }"
                                        p-style="width: 100%;"
                                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                                        :data="terminationReasons" id="reason_for_withdraw" name="Reason for Withdraw*"
                                        :has-validation="false" :default-value="extension_reason"
                                        v-model="extension_reason" />
                                </b-col>
                            </div>

                        </div>
                    </div>

                    <div>
                        <div class="title-message">
                            <input value="rate_change" v-model="stepval" type="checkbox" name="process" id=""> Rate
                            Change
                        </div>
                        <div class="row mt-3 " v-if="stepval.includes('rate_change')">
                            <div class="col-md-4">
                                <FormLabelRequired labelText="Previous Rate (%)" :required="true"
                                    :showHelperText="false" helperText="Interest Rate Offer" helperId="rate" />
                                <CustomInput inputType="number"
                                    c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                                    p-style="width:100%" id="rate" name="Rate*" :has-validation="false" :disabled="true"
                                    input-type="number" :defaultValue="old_rate" />

                            </div>
                            <div class="col-md-4">
                                <FormLabelRequired labelText="New Rate (%)" :required="true" :showHelperText="false"
                                    helperText="Interest Rate Offer" helperId="rate" />
                                <CustomInput inputType="number"
                                    c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                                    p-style="width:100%" id="rate" name="Rate*" :has-validation="true"
                                    @inputChanged="InterestRateChange($event)" input-type="number"
                                    :defaultValue="interest_rate" :hasSpecificError="interestRateError" />
                                <div v-if="interestRateError" class="error-message">
                                    {{ interestRateError }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b-col md="12" class="align-items-left "
                                    style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                                    <FormLabelRequired labelText="Reason" required="true" showHelperText="true"
                                        helperText="Reason For Withdrawing the request" helperId="reasonforwith" />
                                    <CustomSelect :attributes="{ 'value_field': 'reason', 'text_field': 'reason' }"
                                        p-style="width: 100%;"
                                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                                        :data="terminationReasons" id="reason_for_withdraw" name="Reason for Withdraw*"
                                        :has-validation="false" :default-value="rate_change_reason"
                                        v-model="rate_change_reason" />
                                </b-col>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="title-message">
                            <input value="increase_exposure" v-model="stepval" type="checkbox" name="process" id="">
                            Increase Exposure
                        </div>
                        <div class="row mt-3" v-if="stepval.includes('increase_exposure')">
                            <div class=" col-4 col-md-4">
                                <FormLabelRequired labelText="Previous Purchase Value" :showHelperText="false"
                                    helperText="Investment Amount" helperId="investementAmount" />
                                <CustomCurrencyValueInput placeholder="Enter Investment amount"
                                    @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                                    :currencyOptions="currencyOptions" inputType="number" :disabled="true"
                                    :defaultValue="old_purchase_value" />
                            </div>
                            <div class=" col-4 col-md-4">
                                <FormLabelRequired labelText="New Purchase Value" :required="true"
                                    :showHelperText="false" helperText="Investment Amount"
                                    helperId="investementAmount" />
                                <CustomCurrencyValueInput placeholder="Enter Investment amount"
                                    @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                                    :currencyOptions="currencyOptions" inputType="number"
                                    :defaultValue="investementAmount" @inputChanged="validateInvestmentAmount"
                                    :hasError="investmentAmountError" />
                                <div v-if="investmentAmountError" class="error-message">
                                    {{ investmentAmountError }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <b-col md="12" class="align-items-left "
                                    style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                                    <FormLabelRequired labelText="Reason" required="true" showHelperText="true"
                                        helperText="Reason For Withdrawing the request" helperId="reasonforwith" />
                                    <CustomSelect :attributes="{ 'value_field': 'reason', 'text_field': 'reason' }"
                                        p-style="width: 100%;"
                                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                                        :data="terminationReasons" id="reason_for_withdraw" name="Reason for Withdraw*"
                                        :has-validation="false" :default-value="exposure_reason"
                                        v-model="exposure_reason" />
                                </b-col>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100 mt-4">
                    <div class="d-flex justify-content-end w-100">
                        <CustomSubmit title="Submit" @action="adjustAction" />
                    </div>
                </div>
            </div>
        </Modal>

        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" :title="success_title"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="warning = false" @btnTwoClicked=""
            @btnOneClicked="warning = false" icon="/assets/signup/danger.svg" :title="warning_title" btnOneText=""
            btnTwoText="" :showm="warning">
            <div class="ml-5 description-text-withdraw ">{{ warning_desc }}</div>
        </ActionMessage>
    </div>
</template>

<script>
import Modal from '../../../shared/Modal.vue';
// import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
import MessageHeaderIconized from '../../../shared/messageboxes/MessageHeaderIconized.vue';
import CustomSelect from '../../../shared/CustomSelect.vue';
import CustomInput from '../../../shared/CustomInput.vue';
import FormLabelRequired from '../../../shared/formLabels/FormLabelRequired.vue';
import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
import Tooltip from '../../../shared/Tooltip.vue';
import { addDaysOrMonthsToDate, capitalize } from '../../../../utils/commonUtils';
import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';


export default {
    props: ['show', 'deposit', 'from'],
    beforeMount() {
        this.getFormatedTimezone()
        this.setDefaults()
    },
    components: { MessageHeaderIconized, ActionMessage, CustomCurrencyValueInput, Tooltip, CustomInput, Modal, CustomSelect, CustomSubmit, FormLabelRequired, JQueryCustomDatePicker },
    data() {
        return {
            // reasons
            rate_change_reason: 'Mutual Agreement',
            extension_reason: 'Mutual Agreement',
            exposure_reason: 'Mutual Agreement',
            new_term_length_reason: 'Mutual Agreement',

            deposit_id: null,
            warning: false,
            success: false,
            success_title: '',
            warning_title: '',
            warning_desc: '',
            trade_date: null,
            newMaturityError: null,
            terminationReasoonError: null,
            interest_rate: null,
            interestRateError: null,
            formattedtimezone: null,
            reason_for_termination: 'Mutual Agreement',
            currencyOptions: ['CAD', 'USD'],
            selectedCurrency: 'CAD',
            investementAmount: null,
            investmentAmountError: null,
            stepval: [],
            old_maturity_date: null,
            new_maturity_date: null,
            old_purchase_value: null,
            new_purchase_value: null,
            old_rate: null,
            new_rate: null,
            prev_term: 'Days',
            new_term: 'Days',
            prev_term_length: null,
            new_term_length: null,
            termLengtherror: null,
            terminationReasons: [
                {
                    reason: "Mutual Agreement",
                    description: "Both parties agree to end the Repo early.",
                    combined: "Mutual Agreement: Both parties agree to end the Repo early."
                },
                {
                    reason: "Default on Repurchase",
                    description: "Failure to repurchase the securities on the agreed terms.",
                    combined: "Default on Repurchase: Failure to repurchase the securities on the agreed terms."
                },
                {
                    reason: "Breach of Contract",
                    description: "Violation of any contractual terms (e.g., regarding collateral quality).",
                    combined: "Breach of Contract: Violation of any contractual terms (e.g., regarding collateral quality)."
                },
                {
                    reason: "Market Volatility",
                    description: "Significant changes in market conditions affecting security values.",
                    combined: "Market Volatility: Significant changes in market conditions affecting security values."
                },
                {
                    reason: "Regulatory Changes",
                    description: "New laws or regulations make the Repo untenable or less attractive.",
                    combined: "Regulatory Changes: New laws or regulations make the Repo untenable or less attractive."
                },
                {
                    reason: "Liquidity Needs",
                    description: "Termination due to urgent liquidity requirements.",
                    combined: "Liquidity Needs: Termination due to urgent liquidity requirements."
                },
                {
                    reason: "Counterparty Risk",
                    description: "Concerns about the financial stability or reliability of the other party.",
                    combined: "Counterparty Risk: Concerns about the financial stability or reliability of the other party."
                },
                {
                    reason: "Other",
                    description: "Any other unforeseen reasons not listed above.",
                    combined: "Other: Any other unforeseen reasons not listed above."
                }
            ],
            selected_reason: null

        }
    },
    methods: {
        InterestRateChange(value) {
            this.interest_rate = value
            if (this.interest_rate > 100)
                this.interestRateError = "Rate is greater than 100%"
            else
                this.interestRateError = null
        },
        EarlyTermination() {
            this.withdraw_request = true
        },
        withdrawReasonChange(value) {
            const reason = this.terminationReasons.find(item => item.reason == value)
            this.selected_reason = reason.combined
        },
        getFormatedTimezone() {
            axios.get('/get-formated-timezone').then(respons => {
                this.formattedtimezone = JSON.stringify(respons.data)
            })
        },
        termLengthChange() {
            let value = Number.parseFloat(this.new_term_length)
            if (this.new_term == "Days") {
                if (value > 3650) {
                    this.termLengtherror = "Term length cannot be greater than 3650 Days"
                } else {
                    this.termLengtherror = null
                }
            } else {
                if (value > 120) {
                    this.termLengtherror = "Term length cannot be greater than 120 Months"
                } else {
                    this.termLengtherror = null
                }
            }
        },
        validateInvestmentAmount(value) {

            this.investementAmount = value.replace(/,/g, '')
            if (this.investementAmount == null || this.investementAmount == '' || this.investementAmount < 0 || this.investementAmount > 9999999999999) {
                this.investmentAmountError = "Please enter a valid amount"
            } else {
                this.investmentAmountError = null
            }

        },

        adjustActionBCP() {
            let haserror = false
            let action_count = 0
            if (this.stepval) {
                let formdata = new FormData()
                formdata.append('depositID', this.deposit_id)
                if (this.stepval == 'extension') {
                    if (!this.new_maturity_date) {
                        haserror = true
                        this.newMaturityError = "Plase select a date"
                    } else {
                        this.newMaturityError = null
                        this.haserror = false
                        formdata.append('event_type', 'extension')
                        formdata.append('old_maturity_date', this.old_maturity_date)
                        formdata.append('new_maturity_date', this.new_maturity_date)
                    }

                } else if (this.stepval == 'early_termination') {
                    if (!this.selected_reason) {
                        haserror = true
                        this.terminationReasoonError = "Plase select a reason"
                    } else {
                        this.terminationReasoonError = null
                        this.haserror = false
                        formdata.append('event_type', 'early_termination')
                        formdata.append('early_terminate_reason', this.selected_reason)
                    }

                } else if (this.stepval == 'rate_change') {
                    if (!this.interest_rate) {
                        haserror = true
                        this.interestRateError = "Plase enter a valid rate"
                    } else {
                        formdata.append('event_type', 'rate_change')
                        formdata.append('old_rate', this.old_rate)
                        formdata.append('new_rate', this.interest_rate)
                    }

                } else if (this.stepval == 'increase_exposure') {

                    if (this.investementAmount) {
                        if (Number.parseFloat(this.old_purchase_value) > Number.parseFloat(this.investementAmount)) {
                            this.investmentAmountError = "New purchase value should be greater than previous purchase value"
                            haserror = true
                        } else {
                            this.investmentAmountError = null
                            haserror = false
                            formdata.append('event_type', 'increase_exposure')
                            formdata.append('old_purchase_value', this.old_purchase_value)
                            formdata.append('new_purchase_value', this.investementAmount)
                        }
                    } else {
                        haserror = true
                        this.investmentAmountError = "Enter a valid amount"
                    }

                } else if (this.stepval == 'exposuredecrease') {
                    if (this.investementAmount) {
                        if (Number.parseFloat(this.investementAmount) > Number.parseFloat(this.old_purchase_value)) {
                            this.investmentAmountError = "New purchase value should be less than previous purchase value"
                            haserror = true
                        } else {
                            this.investmentAmountError = null
                            haserror = false
                            formdata.append('event_type', 'decrease_exposure')
                            formdata.append('old_purchase_value', this.old_purchase_value)
                            formdata.append('new_purchase_value', this.investementAmount)
                        }
                    } else {
                        haserror = true
                        this.investmentAmountError = "Enter a valid amount"
                    }
                }

                if (!haserror) {
                    axios.post('/trade/CT/post-trade-events', formdata).then(response => {
                        if (response.data.success) {
                            this.success_title = "Adjustment request has been submitted"
                            this.success = true
                            setTimeout(() => {
                                this.success = false
                                window.location.reload()
                            }, 3000)

                        } else {
                            this.warning_title = "Ooops! Adjustment not submitted"
                            this.warning_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                            this.warning = true

                        }
                    }).catch(err => {

                        this.warning_title = "Ooops! Adjustment not submitted"
                        this.warning_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                        this.warning = true
                    })
                }

            } else {
                this.warning_title = "Invalid Selection!"
                this.warning_desc = "Please choose a parameter to change"
                this.warning = true
            }

        },
        adjustAction() {
            let haserror = false
            if (this.new_term_length || this.interest_rate || this.investementAmount) {
                let formdata = new FormData()
                formdata.append('depositID', this.deposit_id)

                if (this.new_maturity_date) {
                    this.newMaturityError = null
                    formdata.append('old_maturity_date', this.old_maturity_date)
                    formdata.append('new_maturity_date', this.new_maturity_date)
                }
                if (this.interest_rate) {
                    adjust_reason = 'rate_change'
                    formdata.append('old_rate', this.old_rate)
                    formdata.append('new_rate', this.interest_rate)
                }
                if (this.investementAmount) {
                    // formdata.append('event_type', 'increase_exposure')
                    formdata.append('old_purchase_value', this.old_purchase_value)
                    formdata.append('new_purchase_value', this.investementAmount)
                }

                if (this.stepval.length > 1) {
                    formdata.append('event_type', 'all')
                } else {
                    formdata.append('event_type', this.stepval[0])
                }

                if (this.stepval.length > 0) {
                    axios.post('/trade/CT/post-trade-events', formdata).then(response => {
                        if (response.data.success) {
                            this.success_title = "Adjustment request has been submitted"
                            this.success = true
                            setTimeout(() => {
                                this.success = false
                                // window.location.reload()
                            }, 3000)

                        } else {
                            this.warning_title = "Ooops! Adjustment not submitted"
                            this.warning_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                            this.warning = true

                        }
                    }).catch(err => {

                        this.warning_title = "Ooops! Adjustment not submitted"
                        this.warning_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                        this.warning = true
                    })
                }

            } else {
                this.warning_title = "Invalid Selection!"
                this.warning_desc = "Please add atleast one parameter to change"
                this.warning = true
            }

        },
        setDefaults() {
            if (this.deposit) {
                const deposit = this.deposit
                this.deposit_id = deposit.encoded_id
                this.old_purchase_value = deposit.offered_amount
                this.old_rate = deposit.c_g_offer.offer_interest_rate
                this.prev_term = capitalize(deposit.c_g_offer.offer_term_length_type)
                this.prev_term_length = deposit.c_g_offer.offer_term_length
                let settdate = addDaysOrMonthsToDate(deposit.c_g_offer.c_t_trade_request.trade_time, deposit.c_g_offer.trade_settlement_period_id, 'days', false)
                this.old_maturity_date = addDaysOrMonthsToDate(settdate, deposit.c_g_offer.offer_term_length, deposit.c_g_offer.offer_term_length_type, false)
            }
        }
    }

}
</script>


<style scoped>
.title-message {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

input[type=radio] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=radio]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=radio]:checked::before {
    transform: scale(1);
}

input[type=radio]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}

.overall-bg {
    /* background: #D9D9D9; */
    /* padding: 10px;
    border-radius: 5px; */
}

.disabled-bg {
    background-color: #e9ecef !important;
}
</style>