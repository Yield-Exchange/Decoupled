<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="xl">
        <div class="w-100 p-4">
            <!-- header -->

            <!-- end header -->
            <div class="row my-2">
                <div class="col-md-5 col-lg-5">
                    <p class="sect-header-counter ">Current</p>
                    <div
                        style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
                        <div
                            style="padding: 5px; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            {{ offer?.product?.product_name }}
                        </div>
                        <div
                            style="height: 42px; text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                            {{ offer?.offer_interest_rate.toFixed(2) }}%</div>
                        <div style="width: 128px; height: 0px; border: 0.51px #9CA1AA solid ;margin-top: 20px;"></div>
                        <div
                            style="flex-direction: column; justify-content: center; align-items: center; display: flex">
                            <div
                                style="padding: 5px; color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                {{ offer?.invitee?.organization?.name }}
                            </div>
                            <div class="my-2"
                                style="background-color: #EFF2FE;padding: 5px; color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Interest Earned: {{ selectedCurrency + " " + addCommas(new_offer_interest_rate) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 col-lg-7">
                    <div class="row w-100 mx-auto px-4 bg-white">
                        <div class=" col-12 col-md-12">
                            <p class="sect-header-counter ">Counter</p>
                        </div>
                        <div class=" col-12 col-md-12 mb-20">
                            <FormLabelRequired labelText="Investment Amount" required="true" :showHelperText="false"
                                helperText="Investment Amount" helperId="investementAmount" />
                            <CustomCurrencyValueInput placeholder="Enter investment amount"
                                @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                                :currencyOptions="currencyOptions" inputType="number" :defaultValue="investementAmount"
                                @inputChanged="validateInvestmentAmount" :hasError="investmentAmountError" />
                            <div v-if="investmentAmountError" class="error-message">
                                {{ investmentAmountError }}
                            </div>
                        </div>



                        <!-- arte type  -->
                        <div class="col-md-6 mb-20 ">

                            <FormLabelRequired labelText="Rate type" :required="true" :showHelperText="true"
                                helperText="Rate type" helperId="ratetype" />

                            <b-select v-if="rate_types" v-model="selected_rate_type" @change="changeRateType"
                                style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                                <option v-for="item in rate_types" :key="item.id" :value="item.id">{{ item.name
                                    }}
                                </option>
                            </b-select>
                        </div>

                        <div class="col-md-6 mb-20 ">
                            <FormLabelRequired labelText="Spread (%) " :required="true" :showHelperText="false"
                                helperText="Spread rate" helperId="spread" />
                            <div class="combined-input"
                                :class="{ 'disabled-div': rate_type == 'fixed', 'error-repo-inputs': spreadRateError != null }"
                                style="margin-top: 4px;">
                                <b-form-select :disabled="rate_type == 'fixed'"
                                    :class="{ 'disabled-input': rate_type == 'fixed' }" id="termlengthid"
                                    v-model="rate_operator" ref="termLengthSelect" @change="validateSpreadChange"
                                    :options="['+', '-']"
                                    style="border: none;width:25%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                </b-form-select>
                                <b-form-input :disabled="rate_type == 'fixed'"
                                    :class="{ 'disabled-input': rate_type == 'fixed', 'validation-error': false }"
                                    style="border: none; width:75%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                    type="number" step='0.01' min="0" v-model="spread_rate" @blur="validateSpreadChange"
                                    placeholder="eg. 3" />
                            </div>
                            <div v-if="spreadRateError" class="error-message">
                                {{ spreadRateError }}
                            </div>
                        </div>
                        <!-- deposit amount -->

                        <div class="col-md-6 mb-20 ">
                            <FormLabelRequired labelText="Interest Rate (%)" :required="true" :showHelperText="false"
                                helperText="Interest Rate Offer" helperId="PDSHId" />
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="rate" name="Rate*" :has-validation="true" :disabled="rate_type != 'fixed'"
                                @inputChanged="InterestRateChange($event)" input-type="number"
                                :defaultValue="interest_rate" :hasSpecificError="interestRateError" />
                            <div v-if="interestRateError" class="error-message">
                                {{ interestRateError }}
                            </div>
                        </div>

                        <div class="mb-20 col-6 col-md-6">
                            <FormLabelRequired labelText="Trade Date" :required="true" :showHelperText="false"
                                helperText="Trade Date" helperId="PDSHId" />
                            <JQueryCustomDatePicker v-if="formattedtimezone" :cannotpicktime="true" style=""
                                :hasError="tradeDateError" id="unique" :start_date="addWeekdays(0)"
                                :end_date="addMonths(null, 1)" :formattedtimezone="formattedtimezone"
                                placeholder="Select Trade Date" :selected_date="trade_date" v-model="trade_date" />
                            <div v-if="tradeDateError" class="error-message">
                                {{ tradeDateError }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-20 ">
                            <FormLabelRequired labelText="Interest Rate Change" :required="false"
                                :showHelperText="false" helperText="Interest Rate Offer" helperId="ratechange" />
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="rate" name="0" :has-validation="false" :disabled="true" input-type="number"
                                :defaultValue="ratechange" />
                            <!-- <div v-if="interestRateError" class="error-message">
                                {{ interestRateError }}
                            </div> -->
                        </div>
                        <div class="col-md-6 mb-20 ">
                            <FormLabelRequired labelText="Additional Interest earned" :required="false"
                                :showHelperText="false" helperText="Interest Rate Offer" helperId="PDSHId" />
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="rate" name="0" :has-validation="false" :disabled="true" input-type="number"
                                :defaultValue="interestearned" />

                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <b-row>
                        <FormLabelRequired style="padding: 4px;" labelText="Special Instructions" :required="false"
                            :showHelperText="false" helperText="Special Instructions"
                            :helperId="'specialInstructions'" />
                        <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                            p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                            id="specialInstructions" name="Special Instructions" :has-validation="true" :maxlength="100"
                            :default-value="specialInstructions" input-type="textareanew"
                            :hasSpecificError="specialInstructionsError" @inputChanged="validateInstructions($event)" />
                        <p class="character-count" v-if="!specialInstructionsError">{{ characterCount }}
                            Character(s) Remaining </p>
                        <div v-else class="error-message">
                            {{ specialInstructionsError }}
                        </div>
                    </b-row>
                </div>
            </div>
            <div class="col-md-12" v-if="log_table_data != null">
                <p
                    style="color:  #5063F4;font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 700;line-height: 26px; text-transform: capitalize;">
                    Counter offer change Log</p>
                <Table :columns="logcolumns" no-data-title="No Offers" no-data-message=" No offers available for review"
                    :data="log_table_data" :has_action='false' :selectable="false" :is_loading="false" />
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-end gap-2">
                    <!-- <CustomSubmit v-if="!isedit" @action="clear = true" :outline="true" title="Clear" /> -->
                    <CustomSubmit :isLoading="submitting" @action="ableToSubmit" title="Submit" />
                </div>
            </div>

        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Counter has  been submitted!" btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Counter has not been submitted!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </Modal>

</template>

<script>
import ViewCard from '../../../../shared/ViewCard.vue';
import CustomInput from '../../../../shared/CustomInput.vue';
import FormLabelRequired from "../../../../shared/formLabels/FormLabelRequired.vue";
import JQueryCustomDatePicker from '../../../../shared/JQueryCustomDatePicker.vue';
import CustomCurrencyValueInput from "../../../../shared/CustomCurencyAmount.vue";
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'
import InviteCard from '../../../../shared/CustomInvitedStatusBadge.vue';



import AboutBank from '../../../../postrequest/depositor/pendingdeposits/AboutBank.vue'
import Modal from '../../../../shared/Modal.vue';
import { addDaysOrMonths, formatTimestamp, calculateIterestOnProduct } from '../../../../../utils/commonUtils'
import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue';
import { mapGetters } from 'vuex';
import Table from '../../../../shared/Table.vue';


export default {
    props: ['offerIndex', 'show'],
    beforeMount() {
        this.getInterestRatesTypes()
        this.getTimezone()
    },
    mounted() {
        if (this.offerIndex != null && this.getAllOffersInReview != null) {
            let offerobj = this.getAllOffersInReview[this.offerIndex]
            this.offer = offerobj.offer
            this.request = offerobj.offer.c_t_trade_request
            this.organization_data = this.offer.invitee.organization
            const counter_offers = this.offer.counter_offers
            let counter_offer_pld = []
            if (counter_offers.length > 0) {
                let counter_offer_pld = [];
                for (let index = 0; index < counter_offers.length && index < 2; index++) {
                    const coffer = counter_offers[index];
                    counter_offer_pld.push([
                        coffer.encoded_id,
                        index + 1,
                        formatTimestamp(coffer.created_at, false),
                        this.addCommas(coffer.offer_minimum_amount),
                        coffer.offer_interest_rate.toFixed(2) + "%",
                        (Number.parseFloat(coffer.offer_interest_rate) - Number.parseFloat(this.offer.offer_interest_rate)).toFixed(2) + "%",
                        () => this.renderStatus(coffer.status)
                    ]);
                }
                this.log_table_data = counter_offer_pld;
            }
            this.setDefaults()
        }
    },
    components: { Table, InviteCard, ActionMessage, CustomSubmit, ViewCard, AboutBank, Modal, CustomCurrencyValueInput, FormLabelRequired, CustomInput, JQueryCustomDatePicker },

    data() {
        return {
            fail: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            request: null,
            success: false,
            fail: false,
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null,
            offer: null,
            organization_data: null,
            // 
            selectedCurrency: 'CAD',
            investementAmount: null,
            termLengtherror: null,
            investmentAmountError: null,
            currencyOptions: ['CAD', 'USD'],
            trade_date: null,
            tradeDateError: null,
            // rates
            interestRateError: null,
            spreadRateError: null,
            rate_types: null,
            rate_type: 'fixed',
            spread_rate: null,
            interest_rate: null,
            prime_rate_formated: null,
            final_rate_type: ['fixed', 0],
            rate_operator: '+',
            selected_rate_type: 'fixed',
            formattedtimezone: null,

            ratechange: 0,
            interestearned: 0,
            specialInstructionsError: null,
            characterCount: 100,
            specialInstructions: null,

            successtitle: "Counter Offer has been send",
            success: false,
            original_offer_amount: 0,
            new_offer_interest_rate: 0,
            original_rate: 0,
            submitting: false,
            log_table_data: null,
            logcolumns: ['#', 'Counter Offer Date', 'Investment Amount', 'Counter Rate', 'Rate Change', 'Counter']


        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview']),

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
        },
        closeModal() {
            this.$emit('closeModal', false);
        },
        setDefaults() {
            if (this.offer) {
                let offer = this.offer
                this.rate_operator = offer.rate_operator
                this.investementAmount = this.request.investment_amount
                this.selectedCurrency = this.request.currency
                if (this.offer.rate_type != 'fixed') {
                    this.spread_rate = offer.fixed_rate
                }
                this.rate_type = offer.rate_type
                this.selected_rate_type = offer.rate_type
                this.interest_rate = offer.rate_type == 'fixed' ? offer.fixed_rate : null
                this.original_offer = this.offer_interest_rate
                this.original_offer_amount = calculateIterestOnProduct(this.request.investment_amount, offer.offer_term_length, offer.offer_term_length_type, null, offer.offer_interest_rate)
                this.new_offer_interest_rate = this.original_offer_amount
                this.final_rate_type = [this.rate_type, offer.variable_rate_value]


            }

        },

        newInterestCalculations() {
            let rate = 1
            if (this.rate_type == 'fixed')
                rate = this.interest_rate
            else {
                let spread_rate = this.spread_rate != null ? Number.parseFloat(this.spread_rate).toFixed(2) : 0;
                var varyrate = Number.parseFloat(this.final_rate_type[1]);
                if (this.rate_operator === "+") {
                    var sum = varyrate + Number.parseFloat(spread_rate);
                    console.log(sum, "Sum", varyrate, " vary rate", spread_rate)
                    rate = sum
                } else if (this.rate_operator === "-") {
                    var difference = varyrate - Number.parseFloat(spread_rate);
                    rate = difference
                }
            }

            let new_offer_interest_rate = calculateIterestOnProduct(this.investementAmount, this.offer.offer_term_length, this.offer.offer_term_length_type, null, rate)
            this.new_offer_interest_rate = new_offer_interest_rate
            this.interestearned = Number.parseFloat(new_offer_interest_rate) - Number.parseFloat(this.original_offer_amount)
            this.ratechange = rate - this.offer.offer_interest_rate

        },

        calclulate_intersts() {
            const interest = calculateIterestOnProduct(amt, term, term_type, null, rate)
        },
        validateInstructions(value) {
            // console.log(value)
            // let clength = this.characterCount
            // console.log(value.length)
            this.characterCount = 100 - value.length
            if (value.length <= 100) {
                this.specialInstructionsError = null
            } else {
                this.specialInstructionsError = "Instructions should have a maximum of 100 characters."
            }
            this.specialInstructions = value
        },
        // second 3
        changeRateType(value) {
            if (value != 'fixed') {
                this.interestRateError = false

                this.rate_type = value.trim()
                let foundElement = this.rate_types.find(element => element.id === this.rate_type);
                if (foundElement) {
                    // console.log(foundElement.value, "Rate value");
                    this.final_rate_type = [foundElement.id, foundElement.rate];
                }

            } else {
                this.spreadRateError = null
                this.rate_type = value.toLowerCase()
                this.final_rate_type = ['fixed', 0]
            }
            this.newInterestCalculations()
        },

        validateSpreadChange() {
            this.spread_rate = Number.parseFloat(this.spread_rate).toFixed(2);
            var varyrate = Number.parseFloat(this.final_rate_type[1]);

            if (this.spread_rate > 100) {
                this.spreadRateError = "Rate is greater than 100%";
            }
            else if (this.rate_operator === "+") {
                var sum = varyrate + Number.parseFloat(this.spread_rate);
                this.interest_rate = sum
                if (sum > 100) {
                    this.spreadRateError = "Variable rate + prime rate is greater than 100%";
                    // console.log(sum);
                } else {
                    this.spreadRateError = null;
                }
            } else if (this.rate_operator === "-") {
                var difference = varyrate - Number.parseFloat(this.spread_rate);
                this.interest_rate = difference
                if (difference < 0.01) {
                    this.spreadRateError = "Variable rate - prime rate is less than 0.01%";
                    // console.log(difference);
                } else {

                    this.spreadRateError = null;
                }
            } else {
                this.spreadRateError = null;
            }
            this.newInterestCalculations()

        },
        InterestRateChange(value) {
            this.interest_rate = value
            if (this.interest_rate > 100)
                this.interestRateError = "Rate is greater than 100%"
            else
                this.interestRateError = null
            this.newInterestCalculations()
        },
        validateInvestmentAmount(value) {

            this.investementAmount = value.replace(/,/g, '')
            if (this.investementAmount == null || this.investementAmount == '' || this.investementAmount < 0 || this.investementAmount > 9999999999999) {
                this.investmentAmountError = "Please enter a valid amount"
            } else {
                this.investmentAmountError = null
            }
            this.newInterestCalculations()

        },
        getTimezone() {
            axios.get('/get-formated-timezone').then(res => {
                // console.log(res.data, "Timexone");
                this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        getInterestRatesTypes(url = "") {
            url = url ? url : `/get-rate_types`;
            axios.get(url)
                .then(response => {
                    let ratess = [];
                    let loop = 0;
                    this.prime_rate_formated = response.data
                    response.data.map((val, key) => {
                        if (val.rate_label === 'Fixed') {
                            let fixed = {
                                id: val.key,
                                rate: val.rate_value,
                                label: val.rate_label,
                                name: `${val.rate_label}`
                            };
                            ratess.push(fixed);
                            this.selectedRateType = fixed;
                        } else {
                            ratess.push({
                                id: val.key,
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
        ableToSubmit() {
            this.haserror = false
            if (this.investementAmount > 0 && this.investmentAmountError == null) {
            } else {
                this.investmentAmountError = this.investmentAmountError == null ? "This field is required" : this.investmentAmountError
                this.haserror = true
            }
            if (this.rate_type == 'fixed') {
                this.InterestRateChange(this.interest_rate)
                if (Number.parseFloat(this.interest_rate) > 0 && this.interestRateError == null) {
                } else {
                    this.haserror = true
                    this.interestRateError = this.interestRateError == null ? "This field is required" : this.interestRateError
                }
            } else {
                this.validateSpreadChange()
                if (Number.parseFloat(this.spread_rate) > 0 && this.spreadRateError == null) {
                } else {
                    this.haserror = true
                    this.spreadRateError = this.spreadRateError == null ? "This field is required" : this.spreadRateError
                }
            }
            if (this.trade_date != null && this.tradeDateError == null) {
            } else {
                this.haserror = true
                this.tradeDateError = this.tradeDateError == null ? "This field is required" : this.tradeDateError
            }
            if (!this.haserror) {
                this.doSubmit()
            }


        },
        doSubmit() {
            this.submitting = true
            const data = {
                'offerId': this.offer.encoded_id,
                'currency': this.selectedCurrency,
                'investment_amount': this.investementAmount.toString(),
                "trade_date": this.trade_date,
                "rate_type": this.rate_type,
                "special_instructions": this.specialInstructions,
                "entered_rate": this.rate_type == 'fixed' ? this.interest_rate.toString() : this.spread_rate.toString(),
                "operator": this.rate_operator,
            }

            axios.post('/trade/CT/give-counter-offer', data).then(response => {
                this.submitting = false
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        // window.location.href = "/view-all-new-requests"
                        this.success = false
                        this.closeModal()
                    }, 3000)
                } else {
                    this.fail = true
                    this.confirmsubmit = false
                }
            }).catch(err => {
                this.submitting = false
                this.confirmsubmit = false
                this.fail = true
            })
        },
        isWeekend(date) {
            var newdate = new Date(date)
            var day = newdate.getDay();
            return (day === 0 || day === 6);
        },
        addMonths(date = null, months) {
            if (date)
                var newDate = new Date(date);
            else
                var newDate = new Date();
            newDate.setMonth(newDate.getMonth() + months);
            return newDate;
        },
        addWeekdays(daysToAdd = 0) {

            var currentDate = new Date();
            var addedDays = 0;

            while (addedDays < daysToAdd) {
                currentDate.setDate(currentDate.getDate() + 1);

                if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
                    addedDays++;
                }
            }

            return currentDate;
        },
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];
            return numberPart
        },
        setPageDefaults() {
            // console.log(this.getAllOffersInReview)
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
        formatDateToCustomFormat(inputDate) {
            // Create a Date object from the inputDate parameter
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        withDrawDeposit() {
            this.withdrawpromt = false
            axios.post('/withdraw-deposit', { 'deposit_id': this.offer_id }).then(response => {
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.href = "/pending-deposits"
                    }, 3000)
                }
            }).catch(err => {
                this.fail = true
            })
        },
        goBack() {
            if (this.fromPage == "pending-deposits")
                window.location.href = "/pending-deposits"
            else if (this.fromPage == "active-deposits")
                window.location.href = "/active-deposits"
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }

        },
        addDaysOrMonths(dateString, count, identifier) {
            // Parse the input date string to get the date object
            const date = new Date(dateString);

            // Check if the identifier is 'days' or 'months' and add the corresponding value
            if (identifier === 'days') {
                date.setDate(date.getDate() + count);
            } else if (identifier === 'months') {
                date.setMonth(date.getMonth() + count);
            } else {
                // If the identifier is neither 'days' nor 'months', return an error message
                return "Invalid identifier. Please use 'days' or 'months'.";
            }

            // Format the updated date object to a string in the format 'YYYY-MM-DD'
            const updatedDateString = date.toISOString()

            return updatedDateString;
        }


    },
    watch: {

        trade_date() {
            if (this.isWeekend(this.trade_date)) {
                this.tradeDateError = "Counter party does not accept rates on weekends"
            } else {
                this.tradeDateError = null
            }

        }
    }
}

</script>
<style>
.t-clock p {
    font-size: 16px !important;
    font-family: Montserrat;
    font-weight: 500;
    word-wrap: break-word
}
</style>
<style scoped>
.sect-header-counter {
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

.pr-deposit-summary-investment p {
    width: 100%;
    color: #252525;
    font-size: 16px;
    font-family: Montserrat;
    font-weight: 500;
    word-wrap: break-word
}

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>