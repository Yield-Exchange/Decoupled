<template>
    <Modal :show="show" @isVisible="$emit('closeModal', false);" modalsize="xl">
        <div class="w-100 p-4"> <!-- header -->

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
                            <div class="text-div">Edit Rate Offer</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- header 2 -->

            <div class="row my-3 w-100 mx-auto p-4 bg-white">
                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Product" :required="true" :showHelperText="true" helperText="Product"
                        helperId="product" />

                    <b-select v-model="product" @change="changeProductValue"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                        <option v-for="item in getgloabalproducts" :key="item.id" :value="item.id">{{ item.product_name
                            }}
                        </option>
                    </b-select>
                </div>


                <div class="col-md-4 mb-20 ">

                    <FormLabelRequired labelText="Primary Baskets" :required="true" :showHelperText="false"
                        helperText="Primary Baskets" helperId="ratetype" />

                    <b-select v-model="primary_basket" :class="{ 'error-repo-inputs': primaryBasketError }"
                        @change="changePrimaryBasket"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                        <option :value="null" selected disabled>Select primary basket type</option>
                        <template v-if="istriparty == 'tri'">
                            <option v-for="item in triparties_primary_baskets" :key="item.id" :value="item.id">{{
        item.basket_name
    }}
                            </option>
                        </template>
                        <template v-if="istriparty == 'bi'">
                            <option v-for="item in bilateral_primary_baskets" :key="item.id" :value="item.id">{{
        item.collateral_name }}
                            </option>
                        </template>
                    </b-select>

                    <div v-if="primaryBasketError" class="error-message">
                        {{ primaryBasketError }}
                    </div>
                </div>

                <!-- collateral basket -->
                <div class="col-md-4 mb-20 " v-if="istriparty == 'tri' && primary_basket != null">
                    <FormLabelRequired labelText="Triparty Baskets" :required="true" :showHelperText="true"
                        helperText="Choose a triparty collateral basket from here" helperId="ratetype" />

                    <b-select v-model="selected_tri_party" @change="changeCollateralBasket"
                        :class="{ 'error-repo-inputs': collateralError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select collateral basket</option>
                        <template v-if="triparties">
                            <option v-for="item in filteredBasket(triparties, primary_basket)" :key="item.id"
                                :value="item.id">
                                {{ item.name
                                }}
                            </option>
                        </template>
                        <option :value="0">Add Later</option>

                    </b-select>
                    <div v-if="collateralError" class="error-message">
                        {{ collateralError }}
                    </div>
                </div>
                <div class="col-md-4 mb-20 " v-if="istriparty == 'bi' && primary_basket != null">

                    <FormLabelRequired labelText="Bilaterals Baksets" :required="true" :showHelperText="true"
                        helperText="Choose a bilateral collateral basket from here" helperId="ratetype" />
                    <b-select v-model="selected_collateral_basket" @change="changeCollateralBasket"
                        :class="{ 'error-repo-inputs': collateralError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select collateral basket</option>
                        <option v-for="item in filteredBasket(bilaterals, primary_basket)" :key="item.id"
                            :value="item.id">
                            {{ item.name
                            }}
                        </option>
                        <option :value="0">Add Later</option>

                    </b-select>
                    <div v-if="collateralError" class="error-message">
                        {{ collateralError }}
                    </div>
                </div>
                <div class="col-md-4 mb-20 " v-if="istriparty == null || primary_basket == null">

                    <FormLabelRequired labelText="Select Baskets" :required="false" :showHelperText="false"
                        helperText="Choose a bilateral collateral basket from here" helperId="ratetype" />

                    <b-select v-model="ptype" disabled @change="changeCollateralBasket"
                        :class="{ 'error-repo-inputs': collateralError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select a product to view products</option>

                        <!-- <option v-for="item in bilaterals" :key="item.id" :value="item.id">{{ item.collateral_name }}
                    </option> -->
                    </b-select>
                    <div v-if="collateralError" class="error-message">
                        {{ collateralError }}
                    </div>
                </div>

                <template>
                    <div class="col-md-4 mb-20 ">

                        <!-- <FormLabelRequired labelText="Settlement Date" :required="true" :showHelperText="true"
                            helperText="Rate type" helperId="ratetype" />

                        <b-select v-model="settlement_date"
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                            <option v-for="item in getsettlemntdate" :key="item.id" :value="item.id">{{
        item.formated_date
    }}
                            </option>
                        </b-select> -->

                        <FormLabelRequired labelText="Day Count Convention" :required="true" :showHelperText="true"
                            helperText="Calculate interest based on the number of days in a period."
                            helperId="daycount" />
                        <NewCustomSelect :disabled="false" style="margin-top: 4px;" :haserror="dayCountError"
                            :options="daycount" idkey="id" valuekey="label" placeholder="Select a day count"
                            :defaultValue="daycount_convection" @change="daycountChnage" />
                        <div v-if="dayCountError" class="error-message">
                            {{ dayCountError }}
                        </div>


                    </div>

                    <div class=" col-12 col-md-4 mb-20">
                        <FormLabelRequired labelText="Minimum Amount" required="true" :showHelperText="false"
                            helperText="Investment Amount" helperId="investementAmount" />
                        <CustomCurrencyValueInput selector_width="30" placeholder="Enter minimum amount"
                            style="margin-top: 5px;" @selectedCurrency="selectedCurrency = $event"
                            :selectedCurrency="selectedCurrency" :currencyOptions="currencyOptions" inputType="number"
                            :defaultValue="min_amount" @inputChanged="validateMinAmount" :hasError="minAmountError" />
                        <div v-if="minAmountError" class="error-message">
                            {{ minAmountError }}
                        </div>
                    </div>
                    <div class=" col-12 col-md-4 mb-20">
                        <FormLabelRequired labelText="Maximum Amount" required="true" :showHelperText="false"
                            helperText="Investment Amount" helperId="investementAmount" />
                        <CustomCurrencyValueInput selector_width="30" placeholder="Enter maximum amount"
                            style="margin-top: 5px;" @selectedCurrency="selectedCurrency = $event"
                            :selectedCurrency="selectedCurrency" :currencyOptions="currencyOptions" inputType="number"
                            :defaultValue="max_amount" @inputChanged="validateMaxAmount" :hasError="maxAmountError" />
                        <div v-if="maxAmountError" class="error-message">
                            {{ maxAmountError }}
                        </div>
                    </div>
                </template>

                <template>
                    <!-- rate type  -->
                    <div class="col-md-4 mb-20 ">

                        <FormLabelRequired labelText="Rate type" :required="true" :showHelperText="true"
                            helperText="Rate type" helperId="ratetype" />

                        <b-select v-model="selected_rate_type" @change="changeRateType"
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                            <option v-for="item in rate_types" :key="item.id" :value="item.id">{{ item.name
                                }}
                            </option>
                        </b-select>
                    </div>

                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Spread (%)" :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
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
                                style="border: none; width:75%; margin-right:13px;outline:none; box-shadow: none; padding:0px;background-color: transparent !important"
                                type="number" step='0.01' min="-100" v-model="spread_rate" @blur="validateSpreadChange"
                                placeholder="eg. 3" />
                        </div>
                        <div v-if="spreadRateError" class="error-message">
                            {{ spreadRateError }}
                        </div>
                    </div>
                    <!-- deposit amount -->
                    <!-- term length -->

                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Interest Rate (%)" :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput inputType="number"
                            c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                            id="rate" name="Rate*" :has-validation="true" :disabled="rate_type != 'fixed'"
                            @inputChanged="InterestRateChange($event)" input-type="number" :defaultValue="interest_rate"
                            :hasSpecificError="interestRateError" />
                        <div v-if="interestRateError" class="error-message">
                            {{ interestRateError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">

                        <FormLabelRequired labelText="Settlement Date" :required="true" :showHelperText="true"
                            helperText="Choose the date when the transaction settles. " helperId="settledate" />

                        <JQueryCustomDatePicker v-if="formattedtimezone" :cannotpicktime="true" style="margin-top: 5px;"
                            :hasError="dateOfDepositError" id="counxt" :start_date="addWeekdays(0)"
                            :formattedtimezone="formattedtimezone" placeholder="Select date"
                            :selected_date="date_of_deposit" v-model="date_of_deposit" />


                        <div v-if="dateOfDepositError" class="error-message">
                            {{ dateOfDepositError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 d-none">
                        <FormLabelRequired labelText="Settlement Period" :required="false" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput inputType="number" input-style="font-size:14px !important" p-style="width:100%"
                            id="rate" name="Settlement Period" :has-validation="false" :disabled="true" @inputChanged=""
                            input-type="text" :defaultValue="settle_period" :hasSpecificError="false" />
                        <!-- <div v-if="interestRateError" class="error-message">
                        {{ interestRateError }}
                    </div> -->
                    </div>
                    <div class="col-12 col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Term Length" :required="true" :showHelperText="true"
                            helperText="Specify the duration of the investment." helperId="termlength" />
                        <div class="combined-input" :class="{ 'has-error': termLengtherror }"
                            style="margin-top: 4px;background-color: white !important;">
                            <b-form-select class="" id="termlengthid" v-model="termLength" ref="termLengthSelect"
                                @change="termLengthChange" :options="['Days', 'Months']" default-value="Days"
                                style="border: none;min-width:40px !important;width:40%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                            </b-form-select>
                            <b-form-input
                                style="border: none;min-width:100px !important;width:60%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                                type="number" v-model="termLengthValue" step='1' min="0" @blur="termLengthChange"
                                :class="{ 'validation-error': termLengtherror }" placeholder="Enter length " />
                        </div>
                        <div v-if="termLengtherror" class="error-message">
                            {{ termLengtherror }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Maturity Date" :required="false" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput inputType="text" input-style="font-size:14px !important" p-style="width:100%"
                            id="rate" name="Maturity Date" :has-validation="false" :disabled="true" @inputChanged=""
                            input-type="text" :defaultValue="settle_period" :hasSpecificError="false" />
                        <!-- <div v-if="interestRateError" class="error-message">
                {{ interestRateError }}
            </div> -->
                    </div>

                </template>



                <div class="col-md-12">
                    <div class="d-flex justify-content-end gap-2">
                        <!-- <CustomSubmit v-if="!isedit" @action="clear = true" :outline="true" title="Clear" /> -->
                        <CustomSubmit :isLoading="submitting" @action="ableToSubmit" title="Submit" />
                    </div>
                </div>
            </div>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" title="Offer has  been edited!"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Offer has  not been editied!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </Modal>
</template>

<script>
import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue'
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSelectInput from '../../../../auth/signup/shared/CustomSelectInput.vue'
import CustomTextInput from '../../../../auth/signup/shared/CustomTextInput.vue'
import CustomDateInput from '../../../../auth/signup/shared/CustomDateInput.vue'

// import ViewCard from '../pendingdeposits/actions/ViewCard.vue'
import FormLabelRequired from "../../../../shared/formLabels/FormLabelRequired.vue";
import NewCustomSelect from '../../../../shared/NewCustomSelect.vue'


import PlaceOferModal from '../../../../auth/signup/shared/PopUpModal.vue'
import CustomInput from '../../../../shared/CustomInput.vue';
import CustomSelect from '../../../../shared/CustomSelect.vue';
// import CustomMultiSelect from '../../../../shared/CustomMultiSelect.vue';
// import CustomDateInput
import FileUpload from "../../../../shared/FileUpload";
import JQueryCustomDatePicker from '../../../../shared/JQueryCustomDatePicker.vue';
import CustomCurrencyValueInput from "../../../../shared/CustomCurencyAmount.vue";
import CustomMultiSelect from '../../../../shared/CustomMultiSelect.vue'
import { mapGetters } from 'vuex'

import Modal from '../../../../shared/Modal.vue';
import { addDaysOrMonthsToDate, formatTimestamp, capitalize, dayDiffference } from '../../../../../utils/commonUtils'


export default {
    components: { CustomMultiSelect, Modal, CustomSelect, NewCustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, PlaceOferModal, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
    props: ['show', 'offerIndex', 'triparties', 'collaterals', 'daycount', 'formattedtimezone', 'holidays', 'bilateral_primary_baskets', 'triparties_primary_baskets', 'bilaterals'],
    mounted() {
        this.getInterestRatesTypes()
        if (this.offerIndex != null && this.getAllOffersInReview != null) {
            let offerobj = this.getAllOffersInReview[this.offerIndex]
            this.offer = offerobj.offer
            this.setDefaults()
        }
        // console.log(this.offerIndex, this.getAllOffersInReview, "BM")

    },

    data() {
        return {
            offer: null,
            // currencyOptions: ['CAD', 'USD'],
            trade_date: null,
            termLength: 'Days',
            termLengthValue: null,
            ratingtypes: null,
            haslockout: false,
            product_id: 1,
            viewMore1: true,
            reqsummary: null,
            // expected variable
            selectedCurrency: 'CAD',
            termLengtherror: null,
            ptype: null,

            success: false,
            fail: false,
            haserror: false,
            reqid: null,
            collateralValue: null,
            collateralError: null,
            offer_id: null,

            selected_tri_party: null,
            istriparty: null,

            // Add rate offer new request
            minAmountError: null,
            maxAmountError: null,
            min_amount: null,
            max_amount: null,
            interestRateError: null,
            spreadRateError: null,
            primaryBasketError: null,
            primary_basket: null,
            rate_types: null,
            rate_type: 'fixed',
            spread_rate: null,
            interest_rate: null,
            prime_rate_formated: null,
            final_rate_type: ['fixed', 0],
            rate_operator: '+',
            selected_collateral_basket: 1,
            selected_rate_type: 'fixed',
            product: 1,
            settlement_date: 1,
            submitting: false,
            confirmsubmit: false,
            loading: false,
            // day count

            // date of deposit
            date_of_deposit: null,
            dateOfDepositError: null,
            settlement_date: null,
            daycount_convection: null,
            dayCountError: null,
            settle_period: null,
        }
    },
    computed: {
        currencyOptions() {
            return this.$store.getters.systemCurrencies
        },
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getPrimeRates', 'getsettlemntdate', 'getgloabalproducts']),

    },
    methods: {
        daycountChnage(value) {
            this.daycount_convection = value
            this.dayCountError = null
        },
        getMaturityDate() {
            if (this.date_of_deposit && this.termLengthValue && this.termLength) {
                let date = addDaysOrMonthsToDate(this.date_of_deposit, this.termLengthValue, this.termLength, false)
                let newdate = date.split('/')
                // const fdate = `${}-${String(newdate[1]).padStart(2, '0')}-${newdate[2]}`;
                let fdate = `${newdate[2]}-${String(newdate[0]).padStart(2, '0')}-${String(newdate[1]).padStart(2, '0')} 00:00`
                // console.log(fdate)
                this.settle_period = formatTimestamp(fdate, false)
            }
        },
        filteredBasket(baskets, filter_id) {
            baskets = baskets.filter(item => item.primary_id == filter_id && item.currency == this.selectedCurrency)
            // console.log(baskets, "baskets")
            return baskets
        },
        changeProductValue(event) {
            this.primary_basket = null
            const fitem = this.getgloabalproducts.find(item => item.id == event)
            // console.log(fitem, event)
            if (fitem.filter_key == 'tri') {
                this.istriparty = 'tri'
            }
            else if (fitem.filter_key == 'bi') {
                this.istriparty = 'bi'
            }
            this.productError = null
        },
        changePrimaryBasket(event) {
            this.primaryBasketError = null
            this.selected_tri_party = null
            this.selected_collateral_basket = null
        },
        termLengthChange() {
            let value = Number.parseFloat(this.termLengthValue)
            if (value < 1 || value == null || value == '') {
                this.termLengtherror = "Term length cannot be less than 1"
            } else {
                if (this.termLength == "Days") {
                    if (value > 3650) {
                        this.termLengtherror = "Term length cannot be greater than 3650 Days"
                    } else {
                        this.getMaturityDate()
                        this.termLengtherror = null
                        this.isValidDate()
                    }
                } else {
                    if (value > 120) {
                        this.termLengtherror = "Term length cannot be greater than 120 Months"
                    } else {
                        this.getMaturityDate()
                        this.termLengtherror = null
                        this.isValidDate()
                    }

                }
            }
        },
        changeCollateralBasket(event) {
            // console.log(this.selected_collateral_basket)

        },
        closeModal() {
            this.$emit('closeModal', false)
        },
        // second 3
        changeRateType(value) {

            if (value != 'fixed') {
                this.interestRateError = false

                this.rate_type = value.trim()
                let foundElement = this.prime_rate_formated.find(element => element.key === this.rate_type);
                console.log("foundElement", foundElement);
                if (foundElement) {
                    console.log(foundElement, "Rate value");
                    this.final_rate_type = [foundElement.id, foundElement.rate_value];
                    this.spread_rate = 0;
                    this.interest_rate = parseFloat(foundElement.rate_value);
                    this.entered_rate = parseFloat(foundElement.rate_value);
                    // alert(this.entered_rate);
                    if (this.entered_rate >= 0.01) {
                        this.spreadRateError = null;
                    } else {
                        this.spreadRateError = "Enter a vallid spread rate";
                    }


                } else {

                }

            } else {
                this.spreadRateError = null
                this.rate_type = value.toLowerCase()
                this.final_rate_type = ['fixed', 0]
            }
        },

        validateSpreadChange() {
            this.spread_rate = Number.parseFloat(this.spread_rate).toFixed(2);
            var varyrate = Number.parseFloat(this.final_rate_type[1]);


            if (this.spread_rate > 100) {
                this.spreadRateError = "Net Rate is greater than 100%";
            }
            else {
                if (this.rate_operator === "+") {
                    var sum = varyrate + Number.parseFloat(this.spread_rate);
                    this.interest_rate = sum.toFixed(2)
                    if (sum > 100) {
                        this.spreadRateError = "Variable rate + prime rate is greater than 100%";
                        // console.log(sum);
                    } else {
                        if (Number.parseFloat(sum.toFixed(2)) < 0.01) {
                            let minmum = (Number.parseFloat(varyrate) >= 0) ? `- ${(Math.abs(varyrate) - 0.01)}` : 0.01 + Math.abs(varyrate);
                            this.spreadRateError = `Cannot be less than (${minmum}) `;
                        } else {

                            this.spreadRateError = null;
                        }

                    }
                } else if (this.rate_operator === "-") {
                    var difference = varyrate - Number.parseFloat(this.spread_rate);
                    this.interest_rate = difference.toFixed(2)

                    console.log(this.interest_rate, "new revised rate when deducting");
                    if (difference < 0.01) {

                        let minmum = (Number.parseFloat(this.varyrate) >= 0) ? `- ${(Math.abs(varyrate) - 0.01)}` : 0.01 + Math.abs(varyrate);

                        this.spreadRateError = `Cannot be less than (${minmum})`;
                        // console.log(difference);
                    } else {

                        this.spreadRateError = ``;
                    }
                } else {
                    this.spreadRateError = null;
                }
            }


        },
        InterestRateChange(value) {
            this.interest_rate = value
            if (this.interest_rate > 100)
                this.interestRateError = "Rate is greater than 100%"
            else
                this.interestRateError = null
        },
        // third 3
        changeSettlemntDate(event) {
            // console.log(this.settlement_date)
        },
        validateMinAmount(value) {

            this.min_amount = Number.parseFloat(value.replace(/,/g, ''))
            if (this.min_amount == null || this.min_amount == '' || this.min_amount < 0 || this.min_amount > 9999999999999) {
                this.minAmountError = "Please enter a valid amount"
            } else if (this.max_amount && this.min_amount > this.max_amount) {
                this.minAmountError = "Minimum amount should be less than maximum amount"
            } else {
                this.minAmountError = null
            }
        },
        validateMaxAmount(value) {
            this.max_amount = Number.parseFloat(value.replace(/,/g, ''))
            if (this.max_amount == null || this.max_amount == '' || this.max_amount < 0 || this.max_amount > 9999999999999) {
                this.maxAmountError = "Please enter a valid amount"
            } else if (this.min_amount && this.max_amount < this.min_amount) {
                this.maxAmountError = "Maximum amount should be greater than minimum amount"
            } else {
                this.maxAmountError = null
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
        setDefaults() {
            // console.log(this.offer, "Def value ")
            if (this.offer != undefined) {
                const defvalue = this.offer
                this.offer_id = defvalue.encoded_id
                this.selectedCurrency = defvalue.c_t_trade_request.currency
                this.min_amount = defvalue.offer_minimum_amount
                this.max_amount = defvalue.offer_maximum_amount
                this.termLengthValue = defvalue.offer_term_length
                this.date_of_deposit = defvalue.settlement_date ? defvalue.settlement_date.split(' ')[0] : null
                this.daycount_convection = defvalue.interest_calculation_options_id ? defvalue.interest_calculation_options_id : null
                this.termLength = capitalize(defvalue.offer_term_length_type)
                this.product = defvalue.offer_trade_product_id
                this.settlement_date = defvalue.trade_settlement_period_id
                this.rate_type = defvalue.rate_type
                this.selected_rate_type = defvalue.rate_type
                this.interest_rate = defvalue.offer_interest_rate;
                this.spread_rate = defvalue.fixed_rate;
                this.rate_operator = defvalue.rate_operator
                this.final_rate_type = this.rate_type == 'fixed' ? [this.rate_type, defvalue.fixed_rate] : [this.rate_type, defvalue.variable_rate_value];
                this.istriparty = this.offer.basket != null ? 'tri' : 'bi'
                // if (this.date_of_deposit)
                // this.settle_period = dayDiffference(new Date(), this.date_of_deposit)
                // this.primary_basket=
                if (this.istriparty == 'tri') {
                    this.primary_basket = this.offer?.basket != null ? this.offer?.basket?.basket_details?.trade_basket_type?.id : null
                    if (this.checkYIEPattern(this.offer?.basket?.basket_id)) {
                        this.selected_tri_party = 0
                    }
                    else {
                        this.selected_tri_party = this.offer?.basket != null ? this.offer?.basket?.id : null
                    }
                } else {
                    this.primary_basket = this.offer?.bi_colleteral != null ? this.offer?.bi_colleteral?.collateral_details.id : null
                    if (this.checkYIEPattern(this.offer?.bi_colleteral?.CUSIP_code)) {
                        this.selected_collateral_basket = 0
                    }
                    else {
                        this.selected_collateral_basket = this.offer?.bi_colleteral != null ? this.offer?.bi_colleteral?.id : null
                    }

                }
                // this.selected_collateral_basket = this.offer?.bi_colleteral != null ? this.offer?.bi_colleteral.id : null
            }
        },
        checkYIEPattern(inputString) {
            const pattern = /YIE_/;
            return pattern.test(inputString)
        },
        getInterestRatesTypes(url = "") {
            url = url ? url : `/get_all_rate_types`;
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
            // min Amount
            if (this.min_amount > 0 && this.minAmountError == null) {
                this.validateMinAmount(this.min_amount.toString())
            } else {
                this.minAmountError = this.minAmountError == null ? "This field is required" : this.minAmountError
                this.haserror = true
            }
            // max Amount
            if (this.max_amount > 0 && this.maxAmountError == null) {
                this.validateMaxAmount(this.max_amount.toString())
            } else {
                this.maxAmountError = this.maxAmountError == null ? "This field is required" : this.maxAmountError
                this.haserror = true
            }
            if (this.daycount_convection != null && this.dayCountError == null) {
                // this.validateMaxAmount(this.max_amount.toString())
            } else {
                this.dayCountError = this.dayCountError == null ? "This field is required" : this.dayCountError
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
            // validate trade date
            // validate term length
            if (Number.parseFloat(this.termLengthValue) > 0 && this.termLengtherror == null) {
            } else {
                this.haserror = true
                this.termLengtherror = this.termLengtherror == null ? "This field is required" : this.termLengtherror
            }
            if (this.selected_collateral_basket == null && this.selected_tri_party == null) {
                this.collateralError = "This field is required"
                this.haserror = true
            }


            // this.$emit('hasError', [this.offerIndex, this.haserror])
            if (!this.haserror) {
                const data = {
                    'currency': this.selectedCurrency,
                    'min': this.min_amount,
                    'max': this.max_amount,
                    'term_length': this.termLengthValue,
                    'term_length_type': this.termLength,
                    "product": this.product,
                    // "settlement_date": this.settlement_date,
                    "settlementDate": this.date_of_deposit,

                    'collateralType': this.istriparty,
                    "convention_id": this.daycount_convection,
                    "rate_type": this.rate_type,
                    "entered_rate": this.rate_type == 'fixed' ? this.interest_rate.toString() : this.spread_rate.toString(),
                    "operator": this.rate_operator,
                    "offerId": this.offer_id,
                }
                if (this.istriparty == 'bi') {
                    data['collateral_id'] = this.selected_collateral_basket
                    data['primaryCollateral'] = this.primary_basket

                } else {
                    data["basket"] = this.selected_tri_party
                    data['primaryBasket'] = this.primary_basket

                }
                axios.post('/trade/CG/edit-offer', data).then(response => {
                    this.submitting = false
                    this.confirmsubmit = false
                    if (response.data.success) {
                        this.success = true
                        window.location.reload()
                    } else {
                        this.fail = true
                        this.confirmsubmit = false
                    }
                }).catch(err => {
                    this.submitting = false
                    this.confirmsubmit = false
                    this.fail = true
                })
            }
        },

        deleteRequest() {
            this.$emit('deleteRequest', this.offerIndex)
        },
        isWeekend(date) {
            var newdate = new Date(date)
            var day = newdate.getDay();
            return (day === 0 || day === 6);
        },
        getAllHolidaysInAnYear(date, fdate, type = "term") {
            let dateticheck = new Date(date)
            let year = dateticheck.getFullYear()
            let holidaysinayear = null
            // console.log(fdate, "fdate")
            this.loading = true
            axios.get('https://canada-holidays.ca/api/v1/holidays?year=' + year).then(res => {
                holidaysinayear = res?.data?.holidays
                this.loading = false
                if (type == 'term') {
                    let found_item = holidaysinayear.find(item => item.date == fdate.toString())
                    if (found_item)
                        this.termLengtherror = "Sorry this day will be on a holiday"
                    else {
                        this.termLengtherror = null
                    }
                } else {
                    let found_item = holidaysinayear.find(item => item.date == fdate.toString())
                    if (found_item)                // if (this.holidays.find(item => item.date == this.date_of_deposit))
                        this.dateOfDepositError = "Sorry this day will be on a holiday"
                    else {
                        this.getMaturityDate()
                        this.dateOfDepositError = null
                        this.isValidDate()
                        // this.settle_period = dayDiffference(new Date(), this.date_of_deposit)
                        // this.dateOfDepositError = null
                        // this.isValidDate()
                    }
                }

            }).catch(err => {
                console.log("Not a valide date")
                this.loading = false
            })
        },
        isValidDate() {
            if ((this.date_of_deposit != null && this.dateOfDepositError == null) &&
                (this.termLengtherror == null && this.termLength != null && this.termLengthValue != null)) {
                // console.log(this.date_of_deposit, this.termLengthValue, this.termLength, false)
                let date = addDaysOrMonthsToDate(this.date_of_deposit, this.termLengthValue, this.termLength, false)
                let year_to_check = new Date(date).getFullYear()
                let thisyear = new Date().getFullYear()
                // console.log(thisyear, "This Year", year_to_check, "Year To check")
                if (date) {
                    let newdate = date.split('/')
                    // const fdate = `${}-${String(newdate[1]).padStart(2, '0')}-${newdate[2]}`;
                    let fdate = `${newdate[2]}-${String(newdate[0]).padStart(2, '0')}-${String(newdate[1]).padStart(2, '0')}`
                    if (this.isWeekend(fdate)) {
                        this.termLengtherror = "The selected term will be on a weekend"
                    } else {
                        let found_item = null
                        if (thisyear != year_to_check) {
                            this.getAllHolidaysInAnYear(date, fdate, 'term')
                        } else {
                            found_item = this.holidays.find(item => item.date == fdate.toString())
                        }
                        if (found_item)
                            this.termLengtherror = "Sorry this day will be on a holiday"
                        else {
                            this.termLengtherror = null
                            // this.isValidDate()
                        }
                    }
                }
                // console.log(date, 'return date')
            }
        },
    },
    watch: {
        trade_date() {
            if (this.isWeekend(this.trade_date)) {
                this.tradeDateError = "Counter party does not accept rates on weekends"
            } else {
                this.tradeDateError = null
            }
        },
        date_of_deposit() {
            if (this.isWeekend(this.date_of_deposit)) {
                this.dateOfDepositError = "Counter party does not accept rates on weekends"
            } else {
                let found_item = null
                let year_to_check = new Date(this.date_of_deposit).getFullYear()
                let thisyear = new Date().getFullYear()
                this.termLengtherror = null
                if (year_to_check != thisyear) {
                    this.getAllHolidaysInAnYear(this.date_of_deposit, this.date_of_deposit, 'date')
                } else {
                    found_item = this.holidays.find(item => item.date == this.date_of_deposit)
                    if (found_item)                // if (this.holidays.find(item => item.date == this.date_of_deposit))
                        this.dateOfDepositError = "Sorry this day will be on a holiday"
                    else {
                        this.getMaturityDate()
                        // this.settle_period = dayDiffference(new Date(), this.date_of_deposit)
                        this.dateOfDepositError = null
                        this.isValidDate()
                    }
                }
            }

        },
    }

}


</script>


<style>
.error-repo-inputs {
    border: 0.5px solid red !important;
}

.disabled-div {
    background-color: #F4F5F6 !important;
}

.disabled-input {
    background-color: transparent !important;
}

.demo-bg {
    background: var(--Yield-Exchange-Pallette-Yield-Exchange-Off-White, #F4F5F6) !important;
    box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.15);
}

.character-offerIndex {
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

.repo-multiselect .select2-selection__rendered {
    max-height: 45px;
}

.repo-multiselect-scroll .select2-selection__rendered {
    max-height: 45px !important;
    overflow-y: scroll !important;
}

.repo-notify {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 500;
    line-height: 26px;
    /* 130% */
}
</style>
