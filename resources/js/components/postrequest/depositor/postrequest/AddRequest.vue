<template>
    <div class="w-100">

        <!-- header -->
        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58807 14.9174C6.6739 10.2883 10.2883 6.67395 14.9173 5.58813C18.2604 4.80396 21.7395 4.80396 25.0826 5.58813C29.7116 6.67395 33.326 10.2883 34.4118 14.9174C35.196 18.2604 35.196 21.7396 34.4118 25.0826C33.326 29.7117 29.7116 33.326 25.0825 34.4119C21.7395 35.196 18.2604 35.196 14.9173 34.4119C10.2883 33.326 6.6739 29.7117 5.58807 25.0826C4.8039 21.7396 4.8039 18.2604 5.58807 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M25.3462 18.2912H21.8187V14.7637C21.8187 14.296 21.6329 13.8474 21.3022 13.5166C20.9714 13.1858 20.5228 13 20.055 13C19.5872 13 19.1386 13.1858 18.8078 13.5166C18.4771 13.8474 18.2912 14.296 18.2912 14.7637L18.3539 18.2912H14.7637C14.296 18.2912 13.8474 18.4771 13.5166 18.8078C13.1858 19.1386 13 19.5872 13 20.055C13 20.5228 13.1858 20.9714 13.5166 21.3022C13.8474 21.6329 14.296 21.8187 14.7637 21.8187L18.3539 21.7561L18.2912 25.3462C18.2912 25.814 18.4771 26.2626 18.8078 26.5934C19.1386 26.9242 19.5872 27.11 20.055 27.11C20.5228 27.11 20.9714 26.9242 21.3022 26.5934C21.6329 26.2626 21.8187 25.814 21.8187 25.3462V21.7561L25.3462 21.8187C25.814 21.8187 26.2626 21.6329 26.5934 21.3022C26.9242 20.9714 27.11 20.5228 27.11 20.055C27.11 19.5872 26.9242 19.1386 26.5934 18.8078C26.2626 18.4771 25.814 18.2912 25.3462 18.2912Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div class="text-div">Post Request {{ count + 1 }}</div>
                    </div>
                    <!-- <div @click="toggleView(1)"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div> -->
                </div>
            </div>
        </div>

        <!-- header 2 -->

        <div class="row shadow-sm my-3 w-100 mx-auto p-4"
            :class="{ 'bg-white': !depositor_demo_setup, 'demo-bg': depositor_demo_setup }">
            <div class="col-md-6 mb-20">

                <FormLabelRequired labelText="Product" :required="true" :showHelperText="true" :ishtml="true"
                    :helperText="setTooltips().product" :helperId="'producttype' + count" />

                <CustomSelect v-if="productType.length > 0"
                    :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
                    inputStyle="font-size:14px !important" c-style="font-weight: 400;width:100%;background:white"
                    :data="productType" id="product_type_id" name="Product Type*" :has-validation="false"
                    :default-value="productname" @selectChanged="productTypeChange($event)" v-model="productname" />
            </div>
            <div class="col-md-3 mb-20" v-if="!ishisa">
                <FormLabelRequired labelText=" Term Length" :required="true" :showHelperText="true" :ishtml="true"
                    :helperText="setTooltips().termLength" :helperId="'termLength' + count" />
                <div class="combined-input" :class="{ 'has-error': termLengtherror }"
                    style="margin-top: 4px;background-color: white !important;">
                    <b-form-select class="" id="termlengthid" v-model="termLength" ref="termLengthSelect"
                        @change="termLengthChange" :options="['Days', 'Months']" default-value="Days"
                        style="border: none;min-width:100px !important;width:30%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                    </b-form-select>
                    <b-form-input
                        style="border: none;min-width:100px !important;width:70%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                        type="number" v-model="termLengthValue" step='1' min="0" @blur="termLengthChange"
                        :class="{ 'validation-error': termLengtherror }" placeholder="Enter length " />
                </div>
                <div v-if="termLengtherror" class="error-message">
                    {{ termLengtherror }}
                </div>
            </div>

            <!-- lockout period -->
            <div class="mb-20 col-md-3" v-if="!ishisa">

                <FormLabelRequired labelText="Lockout Period(Days)" :required="true" :showHelperText="true"
                    :helperId="'lockout' + count" :ishtml="true" :helperText="setTooltips().lockout" />

                <CustomInput :disabled="!haslockout" inputType="number" inputStyle="font-size:14px !important"
                    c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%" id="maximum"
                    name="Enter lockout period " :has-validation="true" @inputChanged="validateLockoutPeriod($event)"
                    input-type="number" :defaultValue="lockout_period" :hasSpecificError="lockoutPeriodError" />
                <div v-if="lockoutPeriodError" class="error-message">
                    {{ lockoutPeriodError }}
                </div>
            </div>

            <!-- deposit amount -->

            <div class="mb-20 " :class="{ 'col-md-4': ishisa, 'col-md-6': !ishisa }">
                <FormLabelRequired labelText="Deposit Amount" required="true" :showHelperText="true" :ishtml="true"
                    :helperText="setTooltips().depamount" :helperId="'depositAmount' + count" />
                <CustomCurrencyValueInput placeholder="Enter deposit amount"
                    @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                    :currencyOptions="currencyOptions" inputType="number" :defaultValue="depositAmount"
                    @inputChanged="validateDepositAmount" :hasError="depositAmountError" />
                <div v-if="depositAmountError" class="error-message">
                    {{ depositAmountError }}
                </div>
            </div>
            <div class="mb-20 " :class="{ 'col-md-4': ishisa, 'col-md-6': !ishisa }">
                <FormLabelRequired labelText="Approximate Date of Deposit" :required="true" :showHelperText="true"
                    :helperId="'dod' + count" :ishtml="true" :helperText="setTooltips().dodeposit" />
                <JQueryCustomDatePicker style="" :cannotpicktime="true" :hasError="dateOfDepositError" :id="count"
                    :start_date="addWeekdays(0)" :formattedtimezone="formattedtimezone" placeholder="Select date"
                    :selected_date="date_of_deposit" v-model="date_of_deposit" />
                <div v-if="dateOfDepositError" class="error-message">
                    {{ dateOfDepositError }}
                </div>
            </div>
            <div class="d-flex justify-content-between gap-2">


                <div class="d-flex justify-content-start gap-2" @click="toggleadvanceOptions">
                    <p class="advanc-option-click">Advanced Options</p>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#5063F4" stroke-width="1.66667"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div class="d-flex justify-content-start gap-2" v-if="candelete" @click="deleteRequest">
                    <p class="advanc-option-click">Remove Product </p>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.2502 2.5H8.75022C8.58445 2.5 8.42548 2.56585 8.30827 2.68306C8.19106 2.80027 8.12522 2.95924 8.12522 3.125V3.75H11.8752V3.125C11.8752 2.95924 11.8094 2.80027 11.6922 2.68306C11.5749 2.56585 11.416 2.5 11.2502 2.5ZM13.7502 3.75V3.125C13.7502 2.46196 13.4868 1.82607 13.018 1.35723C12.5491 0.888392 11.9133 0.625 11.2502 0.625H8.75022C8.08717 0.625 7.45129 0.888392 6.98245 1.35723C6.51361 1.82607 6.25022 2.46196 6.25022 3.125V3.75H2.81396C2.56532 3.75 2.32687 3.84877 2.15105 4.02459C1.97524 4.2004 1.87646 4.43886 1.87646 4.6875C1.87646 4.93614 1.97524 5.1746 2.15105 5.35041C2.32687 5.52623 2.56532 5.625 2.81396 5.625H3.20396L3.60021 15.1562C3.64053 16.1231 4.05301 17.0368 4.75141 17.7066C5.44981 18.3763 6.38007 18.7502 7.34771 18.75H12.654C13.6214 18.7499 14.5513 18.3759 15.2495 17.7062C15.9476 17.0364 16.3599 16.1228 16.4002 15.1562L16.7977 5.625H17.1877C17.4364 5.625 17.6748 5.52623 17.8506 5.35041C18.0264 5.1746 18.1252 4.93614 18.1252 4.6875C18.1252 4.43886 18.0264 4.2004 17.8506 4.02459C17.6748 3.84877 17.4364 3.75 17.1877 3.75H13.7502ZM14.9202 5.625H5.08021L5.47397 15.0775C5.49397 15.561 5.70014 16.0181 6.04935 16.3531C6.39857 16.6881 6.86379 16.8751 7.34771 16.875H12.654C13.1377 16.8748 13.6026 16.6876 13.9515 16.3526C14.3005 16.0177 14.5065 15.5608 14.5265 15.0775L14.9202 5.625ZM7.18772 8.125V14.375C7.18772 14.6236 7.28649 14.8621 7.4623 15.0379C7.63812 15.2137 7.87657 15.3125 8.12522 15.3125C8.37386 15.3125 8.61231 15.2137 8.78813 15.0379C8.96394 14.8621 9.06272 14.6236 9.06272 14.375V8.125C9.06272 7.87636 8.96394 7.6379 8.78813 7.46209C8.61231 7.28627 8.37386 7.1875 8.12522 7.1875C7.87657 7.1875 7.63812 7.28627 7.4623 7.46209C7.28649 7.6379 7.18772 7.87636 7.18772 8.125ZM11.8752 7.1875C12.1239 7.1875 12.3623 7.28627 12.5381 7.46209C12.7139 7.6379 12.8127 7.87636 12.8127 8.125V14.375C12.8127 14.6236 12.7139 14.8621 12.5381 15.0379C12.3623 15.2137 12.1239 15.3125 11.8752 15.3125C11.6266 15.3125 11.3881 15.2137 11.2123 15.0379C11.0365 14.8621 10.9377 14.6236 10.9377 14.375V8.125C10.9377 7.87636 11.0365 7.6379 11.2123 7.46209C11.3881 7.28627 11.6266 7.1875 11.8752 7.1875Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div v-if="showAdvancedOptions">
                <div class="row">
                    <div class="col-md-4 mb-20">

                        <FormLabelRequired labelText="Compounding Frequency" :required="true" :showHelperText="true"
                            :ishtml="true" :helperText="setTooltips().cfreq" :helperId="'cfreq' + count" />

                        <CustomSelect v-if="compoundingfrequencies"
                            :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
                            c-style="font-weight: 400;width:100%;background:white" :data="compoundingfrequencies"
                            id="cfreq" name="Product Type*" :has-validation="false" :default-value="compoundingfreq"
                            @selectChanged="cFreqChange($event)" />
                    </div>
                    <div class="col-md-4 mb-20">

                        <FormLabelRequired labelText="Short Term Credit Rating" :required="true" :showHelperText="true"
                            :ishtml="true" :helperText="setTooltips().crating" :helperId="'crating' + count" />

                        <CustomSelect v-if="ratingtypes"
                            :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
                            c-style="font-weight: 400;width:100%;background:white" :data="ratingtypes" id="crating"
                            name="Product Type*" :has-validation="false" :default-value="creditrating"
                            @selectChanged="CRatingChange($event)" />
                    </div>
                    <div class="col-md-4 mb-20">

                        <FormLabelRequired labelText="Deposit insurance" :required="true" :showHelperText="true"
                            :ishtml="true" :helperText="setTooltips().depinsurance"
                            :helperId="'deposit_insurance' + count" />

                        <CustomSelect v-if="deposit_insurance"
                            :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
                            c-style="font-weight: 400;width:100%;background:white" :data="deposit_insurance"
                            id="deposit_insurance" name="Product Type*" :has-validation="false"
                            :default-value="depositInsurance" @selectChanged="DInsuranceChange($event)" />
                    </div>

                    <div class="col-md-12">
                        <b-row>
                            <FormLabelRequired style="padding: 4px;" labelText="Special Instructions" :required="false"
                                :showHelperText="false" helperText="Special Instructions"
                                :helperId="'specialInstructions' + count" />
                            <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                id="specialInstructions" name="Special Instructions" :has-validation="true"
                                :maxlength="100" :default-value="specialInstructions" input-type="textareanew"
                                :hasSpecificError="specialInstructionsError"
                                @inputChanged="validateInstructions($event)" />
                            <p class="character-count" v-if="!specialInstructionsError">{{ characterCount }}
                                Character(s) Remaining </p>
                            <div v-else class="error-message">
                                {{ specialInstructionsError }}
                            </div>
                        </b-row>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSelectInput from '../../../auth/signup/shared/CustomSelectInput.vue'
import CustomTextInput from '../../../auth/signup/shared/CustomTextInput.vue'
import CustomDateInput from '../../../auth/signup/shared/CustomDateInput.vue'

import ViewCard from '../pendingdeposits/actions/ViewCard.vue'
import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";


import PlaceOferModal from '../../../auth/signup/shared/PopUpModal.vue'
import CustomInput from '../../../shared/CustomInput.vue';
import CustomSelect from '../../../shared/CustomSelect.vue';
// import CustomDateInput
import FileUpload from "../../../shared/FileUpload";
import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";


export default {
    components: { CustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, PlaceOferModal, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
    props: ['candelete', 'editData', 'depositor_demo_setup', 'isedit', 'deposit_insurances', 'credit_rating_types', 'products', 'count', 'formattedtimezone', 'request'],
    beforeMount() {
        if (this.request) {
            this.setDefaults(this.request)
        }
        if (this.isedit && this.request == undefined && this.editData != null) {
            // console.log(this.editData[0])
            this.setDefaults(this.editData[0])
        }
    },
    mounted() {
        if (this.deposit_insurances)
            this.deposit_insurance = JSON.parse(this.deposit_insurances)
        if (this.credit_rating_types)
            this.ratingtypes = JSON.parse(this.credit_rating_types)
        if (this.products) {
            this.availableproducts = JSON.parse(this.products)
            this.productType = this.availableproducts.map(element => element.description);
            // console.log(this.productType)

        }
    },

    data() {
        return {
            productType: [],
            currencyOptions: ['CAD', 'USD'],
            date_of_deposit: null,
            termLength: 'Months',
            termLengthValue: null,
            ratingtypes: null,
            deposit_insurance: null,
            availableproducts: null,
            showAdvancedOptions: false,
            productname: 'Short Term',
            haslockout: false,
            product_id: 1,
            reqsummary: null,
            ishisa: false,
            compoundingfrequencies: ['At maturity', 'Monthly', 'Quarterly', 'Semi annually', 'Annually'],
            // expected variable
            selectedCurrency: 'CAD',
            depositAmount: null,
            specialInstructions: null,
            dateOfDepositError: null,
            compoundingfreq: 'At maturity',
            shorttermratting: null,
            depositInsurance: 'Any',
            lockout_period: null,
            lockoutPeriodError: null,
            termLengtherror: null,
            depositAmountError: null,
            creditrating: "Any/Not Rated",
            characterCount: 100,
            specialInstructionsError: null,
            haserror: false,
            reqid: null,
        }
    },
    methods: {
        productTypeChange(value) {
            this.productname = value
            let product = this.availableproducts.find(product => product.description === this.productname);
            this.product_id = product.id

            if (value.toLowerCase() == "cashable" || value.toLowerCase() == "notice deposit")
                this.haslockout = true
            else {
                this.haslockout = false
                this.lockout_period = null
            }
            if (value.toLowerCase() == "high interest savings") {
                this.ishisa = true
                this.lockout_period = null
                this.termLengthValue = null
            }
            else
                this.ishisa = false


        },
        cFreqChange(value) {
            this.compoundingfreq = value

        },
        setDefaults(defvalue) {
            if (defvalue != undefined) {
                this.date_of_deposit = defvalue.date_of_deposit
                this.termLengthValue = defvalue.term_length
                this.productname = defvalue.product
                if (this.productname.toLowerCase() == "cashable" || this.productname.toLowerCase() == "notice deposit")
                    this.haslockout = true
                else
                    this.haslockout = false
                if (this.productname.toLowerCase() == "high interest savings")
                    this.ishisa = true
                else
                    this.ishisa = false
                this.selectedCurrency = defvalue.deposit_currency
                this.product_id = defvalue.product_id
                this.termLength = this.capitalize(defvalue.term_type)
                this.lockout_period = defvalue.lockout_period
                this.depositAmount = defvalue.deposit_amount
                this.compoundingfreq = defvalue.compound_frequency
                this.creditrating = defvalue.credit_rating
                this.depositInsurance = defvalue.deposit_insurance
                this.specialInstructions = defvalue.specinstructions
                this.reqid = defvalue.reqid
                // console.log(Object.values(defvalue))
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
        CRatingChange(value) {
            this.creditrating = value
        },
        DInsuranceChange(value) {
            this.depositInsurance = value
        },
        generateRandomValue() {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var length = 12;
            var randomValue = '';
            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * characters.length);
                randomValue += characters.charAt(randomIndex);
            }
            return randomValue;
        },
        termLengthChange() {
            // console.log('TL', this.termLength, this.termLengthValue)
            let value = Number.parseFloat(this.termLengthValue)
            if (this.haslockout && this.lockout_period != null)
                this.validateLockoutPeriod(this.lockout_period)
            if (value < 1 || value == null || value == '') {
                this.termLengtherror = "Term length cannot be less than 1"
            } else {
                if (this.termLength == "Days") {
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
            }

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
        validateLockoutPeriod(value) {
            this.lockout_period = value.replace(/,/g, '')
            // console.log(this.termLength, this.haslockout)
            if (this.haslockout) {
                if (this.lockout_period != '') {
                    if (this.termLength.toLowerCase() == "days") {
                        if (Number.parseFloat(this.lockout_period) > Number.parseFloat(this.termLengthValue)) {
                            this.lockoutPeriodError = "Lockout period cannot be greater than the term length"
                        } else {
                            this.lockoutPeriodError = null
                        }
                    } else {
                        if (this.termLengtherror == null) {
                            if (Number.parseFloat(this.lockout_period) > (Number.parseFloat(this.termLengthValue) * 30)) {
                                this.lockoutPeriodError = "Lockout period cannot be greater than the term length"

                            } else {
                                this.lockoutPeriodError = null
                            }
                        }
                    }
                } else {
                    this.lockoutPeriodError = "Please enter a valid amount"
                }
            } else {
                this.lockoutPeriodError = null
                this.lockout_period = null
            }
        },
        validateDepositAmount(value) {

            this.depositAmount = value.replace(/,/g, '')
            if (this.haslockout && this.lockout_period != null)
                this.validateLockoutPeriod(this.lockout_period.toString())
            if (this.depositAmount == null || this.depositAmount == '' || this.depositAmount < 0 || this.depositAmount > 9999999999999) {
                this.depositAmountError = "Please enter a valid amount"
            } else {
                this.depositAmountError = null
            }

        },
        ableToSubmit() {
            this.haserror = false
            // check deposit amount
            if (this.depositAmount > 0 && this.depositAmountError == null) {
                if (this.date_of_deposit != null && this.dateOfDepositError == null) {
                    if (this.productname.toLowerCase() != 'high interest savings') {

                        if (this.termLengthValue > 0 && this.termLengtherror == null) {
                            // this.$emit('hasError', [this.count, false])
                        } else {
                            this.haserror = true
                            // this.$emit('hasError', [this.count, true])
                            this.termLengtherror = this.termLengtherror == null ? "This field is required" : this.termLengtherror
                        }
                        if (this.haslockout && (this.lockoutPeriodError || !this.lockout_period)) {
                            // this.$emit('hasError', [this.count, true])
                            this.haserror = true

                            this.lockoutPeriodError = this.lockoutPeriodError == null ? "This field is required" : this.lockoutPeriodError
                        } else {
                            // this.$emit('hasError', [this.count, false])
                        }
                        if (this.specialInstructionsError) {                            // this.$emit('hasError', [this.count, true])
                            this.haserror = true

                        }
                    } else {
                        // this.$emit('hasError', [this.count, false])
                    }

                } else {
                    this.haserror = true
                    // this.$emit('hasError', [this.count, true])
                    this.dateOfDepositError = this.dateOfDepositError == null ? "This field is required" : this.dateOfDepositError
                }
            } else {
                this.depositAmountError = this.depositAmountError == null ? "This field is required" : this.depositAmountError
                // this.$emit('hasError', [this.count, true])
                this.haserror = true
                // this.haserror = false


            }
            this.$emit('hasError', [this.count, this.haserror])


            const data = {
                'reqid': this.reqid,
                'date_of_deposit': this.date_of_deposit,
                'term_length': this.termLengthValue,
                'product': this.productname,
                'product_id': this.product_id,
                'deposit_currency': this.selectedCurrency,
                'term_type': this.termLength,
                'lockout_period': this.lockout_period,
                'deposit_amount': this.depositAmount,
                'compound_frequency': this.compoundingfreq,
                'credit_rating': this.creditrating,
                'deposit_insurance': this.depositInsurance,
                'specinstructions': this.specialInstructions,

            }
            if (!this.haserror)
                this.$emit('newRequest', [this.count, data])
        },
        toggleadvanceOptions() {
            this.showAdvancedOptions = !this.showAdvancedOptions
        },
        deleteRequest() {
            this.$emit('deleteRequest', this.count)
        },
        isWeekend(date) {
            var newdate = new Date(date)
            var day = newdate.getDay();
            return (day === 0 || day === 6);
        },
        setTooltips() {
            return {
                product: `
<div class="bg-white">
<h5 class='font-weight-bold'>Redeemable</h5>


        <p class='' style="font-weight:500;font-size:14px">
    A redeemable deposit is a type of savings account where you deposit money with a bank or credit union for a set period of time in exchange for a fixed amount of interest. You can take out your money at any time without any penalty, and the interest rate is usually higher than a regular savings account. It's a good option for people who want to earn more interest on their savings but still want easy access to their money.

</p>



<h5 class='font-weight-bold'>Non-Redeemable</h5>

<p class='' style="font-weight:500;font-size:14px">
    A non-redeemable term is a type of investment where you deposit money with a financial institution for a fixed period of time, during which you cannot withdraw your funds without incurring a penalty. The interest rate for the duration of the term is fixed and guaranteed. Non-redeemable terms are similar to a savings account, but with a set time frame and a higher interest rate. They are ideal for people who want to earn a higher interest rate on their savings and don't need to access their money in the short term. However, it's important to consider the length of the term and the penalty for early withdrawal before investing your money.
</p>


<h5 class='font-weight-bold'>Cashable:</h5>

<p class='' style="font-weight:500;font-size:14px">
    A cashable deposit is a type of savings account that allows you to withdraw your money at any time without penalties. However, some cashable deposits may have a lock-out period (30 days, 60 days etc.), during which you cannot withdraw your funds or may be charged a penalty if you do. Despite this, cashable deposits are still a good option for people who want to earn interest on their savings while having easy access to their money, especially in case of emergencies. It's important to read the terms and conditions of the cashable deposit to understand any lock-out periods or penalties.
</p>


<h5 class='font-weight-bold'>High Interest Savings Account:</h5>

<p class='' style="font-weight:500;font-size:14px">
    A high interest savings account is a type of savings account that pays a higher interest rate than a regular savings account. They are a good option for people who want to earn more interest on their savings while still having easy access to their money. However, high interest savings accounts may have limitations, such as transaction fees or restrictions on the number of withdrawals you can make per month. It's important to compare different high interest savings accounts and read the terms and conditions before opening an account.
</p>


<h5 class='font-weight-bold'>Notice Deposit:</h5>

<p class='' style="font-weight:500;font-size:14px">
    A notice deposit is a type of savings account that requires you to give notice before you withdraw your money. This means that you cannot access your funds immediately, but rather have to give a notice period, usually ranging from 30 to 90 days, before withdrawing. Notice deposits typically offer higher interest rates than regular savings accounts but lower than term deposits. They are a good option for people who want to earn interest on their savings but do not need immediate access to their funds. It's important to read the terms and conditions of the notice deposit to understand the notice period required and any penalties for early withdrawals.
</p>
</div>

                
                `,
                termLength: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Term Length</h5>


        <p class='' style="font-weight:500;font-size:14px">
            The term length of a deposit refers to the length of time that an investor agrees to deposit their money with a financial institution. This period can vary from a short time, such as a day, to several years. At the end of the term, the investor can choose to withdraw their initial deposit plus any earned interest, or renew the deposit for another term.
</p>
</div>
                `,
                lockout: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Lock out </h5>


        <p class='' style="font-weight:500;font-size:14px">
            A lock-out period is a specific period of time during which you cannot access your money or withdraw funds from an account. It is used to discourage early withdrawals, and if you try to withdraw funds before the end of the lock-out period, you may face penalties or fees. The length of the lock-out period can vary depending on the type of account or investment
</p>
</div>
                `,
                depamount: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Deposit Amount </h5>


        <p class='' style="font-weight:500;font-size:14px">
            Deposit amount refers to the total amount of money an investor intends to invest in a particular account or investment. It is the initial investment amount that an investor puts into an account or investment to start earning interest or other returns.
</p>
</div>
                `,
                dodeposit: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Approximate Date of Deposit  </h5>


        <p class='' style="font-weight:500;font-size:14px">
            The date of deposit refers to the approximate date when an investor plans to deposit funds into the chosen financial institution. This is crucial because the rate offers from financial institutions are time-sensitive. It is advisable to keep a gap of no more than 5 business days between the offer closure date and the date of deposit.

</p>
</div>
                `,
                crating: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Short Term Rating  </h5>


        <p class='' style="font-weight:500;font-size:14px">
            The short-term credit rating of a financial institution is an assessment of its ability to meet its financial obligations in a timely manner. A very strong rating indicates that the institution has a very strong ability to pay back its debts, while an adequate rating means that it has a reasonable ability to do so
</p>
    <img src='/assets/img/credit_rating.png' width="100%">
</div>
                `,
                cfreq: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Compounding Frequency </h5>


        <p class='' style="font-weight:500;font-size:14px">
            The compounding frequency for an investment refers to how often the interest earned on the investment is added back into the principal amount, increasing the amount of interest earned in the future. Most investments are compounded either annually or at maturity, meaning that the interest is added back to the principal either once per year or at the end of the investment term.
</p>
</div>
                `,
                depinsurance: `
                <div class="bg-white">
<h5 class='font-weight-bold'>Deposit Insurance </h5>


        <p class='' style="font-weight:500;font-size:14px">
            In Canada, deposit insurance is a government-backed program that protects eligible deposits held at financial institutions in the event of insolvency. The Canada Deposit Insurance Corporation (CDIC) is a federal agency that provides deposit insurance for eligible deposits up to $100,000 per insured category, per member institution. In addition to CDIC, some provinces in Canada have their own deposit insurance programs. These programs provide additional deposit protection and most of them offer unlimited deposit insurance. For more information, please read our knowledge base.</p>
</div>
                `,
            }
        }
    },
    watch: {
        date_of_deposit() {
            // console.log(this.date_of_deposit, "Date of deposits")
            if (this.isWeekend(this.date_of_deposit)) {
                this.dateOfDepositError = "Financial institutions do not accept money on weekends"
            } else {
                this.dateOfDepositError = null
            }

        }
    }

}


</script>


<style>
.demo-bg {
    background: var(--Yield-Exchange-Pallette-Yield-Exchange-Off-White, #F4F5F6) !important;
    box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.15);
}

.character-count {
    color: var(--Yield-Exchange-Colors-Darks, #252525);

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.advanc-option-click {

    color: var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4);
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    cursor: pointer;
}
</style>