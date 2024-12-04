<template>
    <div class="w-100 my-4">
        <!-- header -->
        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/rate_offer.svg" alt="" srcset="">
                        </div>
                        <div class="text-div">Rate Offer {{ count + 1 }}</div>
                    </div>
                    <div @click="viewMore1 = !viewMore1"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div>
                </div>
            </div>
        </div>

        <!-- header 2 -->

        <div class="row shadow-sm my-3 w-100 mx-auto p-4 bg-white" v-show="viewMore1">
            <div class="col-md-4 mb-20 ">

                <FormLabelRequired labelText="Product" :required="true" :showHelperText="true" helperText="Product"
                    helperId="product" />

                <NewCustomSelect style="margin-top: 4px;" :haserror="productError" :options="getgloabalproducts"
                    idkey="id" valuekey="product_name" placeholder="Select Product" :defaultValue="product"
                    @change="changeProductValue" />


                <div v-if="productError" class="error-message">
                    {{ productError }}
                </div>
            </div>

            <div class="col-md-4 mb-20 ">

                <FormLabelRequired labelText="Primary Baskets" :required="true" :showHelperText="false"
                    helperText="Primary Baskets" helperId="primarybasket" />

                <template v-if="istriparty == 'tri'">
                    <NewCustomSelect style="margin-top: 4px;" :haserror="primaryBasketError"
                        :options="triparties_primary_baskets" idkey="id" valuekey="basket_name"
                        placeholder="Select primary basket type" :defaultValue="primary_basket"
                        @change="changePrimaryBasket" />

                </template>
                <template v-else-if="istriparty == 'bi'">
                    <NewCustomSelect style="margin-top: 4px;" :haserror="primaryBasketError"
                        :options="bilateral_primary_baskets" idkey="id" valuekey="collateral_name"
                        placeholder="Select primary basket type" :defaultValue="primary_basket"
                        @change="changePrimaryBasket" />
                </template>
                <template v-else>
                    <NewCustomSelect style="margin-top: 4px;" :haserror="primaryBasketError" options="" idkey=""
                        valuekey="" placeholder="Select primary basket type" :defaultValue="null" @change="" />
                </template>

                <div v-if="primaryBasketError" class="error-message">
                    {{ primaryBasketError }}
                </div>
            </div>

            <!-- collateral basket -->
            <div class="col-md-4 mb-20 " v-if="istriparty == 'tri' && primary_basket != null">
                <FormLabelRequired labelText="Triparty Baskets" summary="(Currency- Rating - ID)" required="true"
                    :showHelperText="true" helperText="Choose a triparty collateral basket from here"
                    helperId="collateralbasket34" />

                <NewCustomSelect style="margin-top: 4px;" :haserror="collateralError"
                    :options="filteredBasket(triparties, primary_basket)" idkey="id" valuekey="name"
                    placeholder="Select collateral basket" :defaultValue="selected_tri_party"
                    @change="changeCollateralBasket($event, 'tri')" :add_later="true" />


                <div v-if="collateralError" class="error-message">
                    {{ collateralError }}
                </div>
            </div>
            <div class="col-md-4 mb-20 " v-if="istriparty == 'bi' && primary_basket != null">

                <FormLabelRequired labelText="Bilateral Baskets" summary="(Currency- Rating - ID)" :required="true"
                    :showHelperText="true" helperText="Choose a bilateral collateral basket from here"
                    helperId="collateralbasket2" />

                <NewCustomSelect style="margin-top: 4px;" :haserror="collateralError"
                    :options="filteredBasket(bilaterals, primary_basket)" idkey="id" valuekey="name"
                    placeholder="Select collateral basket" :defaultValue="selected_collateral_basket"
                    @change="changeCollateralBasket($event, 'bi')" :add_later="true" />

                <div v-if="collateralError" class="error-message">
                    {{ collateralError }}
                </div>
            </div>
            <div class="col-md-4 mb-20 " v-if="istriparty == null || primary_basket == null">
                <FormLabelRequired labelText=" Select Baskets" :required="false" :showHelperText="false"
                    helperText="Choose a bilateral collateral basket from here" helperId="collateralbasket" />
                <NewCustomSelect :disabled="true" style="margin-top: 4px;" :haserror="collateralError" options=""
                    idkey="" valuekey="" placeholder="Select a product to view products" :defaultValue="ptype"
                    @change="changeCollateralBasket" />
                <div v-if="collateralError" class="error-message">
                    {{ collateralError }}
                </div>
            </div>
            <template>
                <!-- <div class="col-md-4 mb-20 ">

                    <FormLabelRequired labelText="Settlement Date" :required="true" :showHelperText="true"
                        helperText="Rate type" helperId="ratetype" />

                    <NewCustomSelect style="margin-top: 4px;" :haserror="settlementError" :options="getsettlemntdate"
                        idkey="id" valuekey="formated_date" placeholder="Select Settlement Date"
                        :defaultValue="settlement_date" @change="changeSettlementDate" />


                    <div v-if="settlementError" class="error-message">
                        {{ settlementError }}
                    </div>
                </div> -->
                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Day Count Convention" :required="true" :showHelperText="true"
                        helperText="Calculate interest based on the number of days in a period." helperId="daycount" />
                    <NewCustomSelect :disabled="false" style="margin-top: 4px;" :haserror="dayCountError"
                        :options="day__counts" idkey="id" valuekey="label" placeholder="Select a day count"
                        :defaultValue="daycount_convection" @change="daycountChnage" />
                    <div v-if="dayCountError" class="error-message">
                        {{ dayCountError }}
                    </div>
                </div>
                <div class=" col-12 col-md-4 mb-20">
                    <FormLabelRequired labelText="Minimum Amount" required="true" :showHelperText="false"
                        helperText="Investment Amount" helperId="investementAmount"
                        summary='(Enter M for Million, B for Billion)' />
                    <CurrencyInput :disabledcurr="true" placeholder="Enter minimum amount" style="margin-top: 5px;"
                        @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                        :currencyOptions="currencyOptions" inputType="number" :defaultValue="min_amount"
                        @currencyError="minAmountInvalidError = $event" @inputChanged="validateMinAmount"
                        :hasError="minAmountError" />
                    <div v-if="minAmountError" class="error-message">
                        {{ minAmountError }}
                    </div>
                </div>

                <div class=" col-12 col-md-4 mb-20">
                    <FormLabelRequired labelText="Maximum Amount" required="true" :showHelperText="false"
                        helperText="Investment Amount" helperId="investementAmount"
                        summary='(Enter M for Million, B for Billion)' />
                    <CurrencyInput :disabledcurr="true" placeholder="Enter maximum amount" style="margin-top: 5px;"
                        @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                        :currencyOptions="currencyOptions" inputType="number" :defaultValue="max_amount"
                        @currencyError="maxAmountInvalidError = $event" @inputChanged="validateMaxAmount"
                        :hasError="maxAmountError" />
                    <div v-if="maxAmountError" class="error-message">
                        {{ maxAmountError }}
                    </div>
                </div>
            </template>

            <template>
                <!-- arte type  -->
                <div class="col-md-4 mb-20 ">

                    <FormLabelRequired labelText="Rate type" :required="true" :showHelperText="true"
                        helperText="Rate type" helperId="ratetype" />
                    <NewCustomSelect style="margin-top: 4px;" :haserror="false" :options="rate_types" idkey="id"
                        valuekey="name" placeholder="Select rate" :defaultValue="selected_rate_type"
                        @change="changeRateType" />

                    <!-- <b-select v-model="selected_rate_type" @change="changeRateType"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option v-for="item in rate_types" :key="item.id" :value="item.id">{{ item.name
                            }}
                        </option>
                    </b-select> -->
                </div>

                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Spread (%) " :required="true" :showHelperText="false"
                        helperText="Interest Rate Offer" helperId="rate-type" />
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
                            style="border: none;min-width:100px !important;font-size:14px !important;width:79%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                            type="number" step='0.01' min="-100" v-model="spread_rate" @blur="validateSpreadChange"
                            placeholder="eg. 3" />
                    </div>
                    <div v-if="spreadRateError" class="error-message">
                        {{ spreadRateError }}
                    </div>
                </div>
                <!-- deposit amount -->

                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Interest Rate (%)" :required="true" :showHelperText="false"
                        helperText="Interest Rate Offer" helperId="PDSHId" />
                    <CustomInput inputType="number" input-style="font-size:14px !important" p-style="width:100%"
                        id="rate" name="Rate*" :has-validation="true" :disabled="rate_type != 'fixed'"
                        @inputChanged="InterestRateChange($event)" input-type="number" v-model="interest_rate"
                        :defaultValue="interest_rate" :hasSpecificError="interestRateError" />
                    <div v-if="interestRateError" class="error-message">
                        {{ interestRateError }}
                    </div>
                </div>

                <!-- setrtlement date -->
                <div class="col-md-4 mb-20 ">

                    <FormLabelRequired labelText="Settlement Date" :required="true" :showHelperText="true"
                        helperText="Choose the date when the transaction settles. " helperId="settledate" />

                    <JQueryCustomDatePicker v-if="formattedtimezone" :cannotpicktime="true" style="margin-top: 5px;"
                        :hasError="dateOfDepositError" :id="count" :start_date="addWeekdays(0)"
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
                <!-- term length -->
                <div class="col-12 col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Term Length" :required="true" :showHelperText="true"
                        helperText="Specify the duration of the investment." helperId="termlength" />
                    <div class="combined-input" :class="{ 'has-error': termLengtherror }"
                        style="margin-top: 4px;background-color: white !important;">
                        <b-form-select class="" id="termlengthid" v-model="termLength" ref="termLengthSelect"
                            @change="termLengthChange" :options="['Days', 'Months']" default-value="Days"
                            style="border: none;min-width:100px !important;width:21%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                        </b-form-select>
                        <b-form-input
                            style="border: none;min-width:100px !important;font-size:14px !important;width:79%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
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
                    <CustomInput inputType="text" input-style="font-size:14px !important" p-style="width:100%" id="rate"
                        name="Maturity Date" :has-validation="false" :disabled="true" @inputChanged="" input-type="text"
                        :defaultValue="settle_period" :hasSpecificError="false" />
                    <!-- <div v-if="interestRateError" class="error-message">
                {{ interestRateError }}
            </div> -->
                </div>



            </template>

            <div class="d-flex justify-content-between gap-2">
                <div class="d-flex justify-content-start gap-2" v-if="candelete" @click="deleteRequest">
                    <p class="advanc-option-click">Remove Offer </p>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.2502 2.5H8.75022C8.58445 2.5 8.42548 2.56585 8.30827 2.68306C8.19106 2.80027 8.12522 2.95924 8.12522 3.125V3.75H11.8752V3.125C11.8752 2.95924 11.8094 2.80027 11.6922 2.68306C11.5749 2.56585 11.416 2.5 11.2502 2.5ZM13.7502 3.75V3.125C13.7502 2.46196 13.4868 1.82607 13.018 1.35723C12.5491 0.888392 11.9133 0.625 11.2502 0.625H8.75022C8.08717 0.625 7.45129 0.888392 6.98245 1.35723C6.51361 1.82607 6.25022 2.46196 6.25022 3.125V3.75H2.81396C2.56532 3.75 2.32687 3.84877 2.15105 4.02459C1.97524 4.2004 1.87646 4.43886 1.87646 4.6875C1.87646 4.93614 1.97524 5.1746 2.15105 5.35041C2.32687 5.52623 2.56532 5.625 2.81396 5.625H3.20396L3.60021 15.1562C3.64053 16.1231 4.05301 17.0368 4.75141 17.7066C5.44981 18.3763 6.38007 18.7502 7.34771 18.75H12.654C13.6214 18.7499 14.5513 18.3759 15.2495 17.7062C15.9476 17.0364 16.3599 16.1228 16.4002 15.1562L16.7977 5.625H17.1877C17.4364 5.625 17.6748 5.52623 17.8506 5.35041C18.0264 5.1746 18.1252 4.93614 18.1252 4.6875C18.1252 4.43886 18.0264 4.2004 17.8506 4.02459C17.6748 3.84877 17.4364 3.75 17.1877 3.75H13.7502ZM14.9202 5.625H5.08021L5.47397 15.0775C5.49397 15.561 5.70014 16.0181 6.04935 16.3531C6.39857 16.6881 6.86379 16.8751 7.34771 16.875H12.654C13.1377 16.8748 13.6026 16.6876 13.9515 16.3526C14.3005 16.0177 14.5065 15.5608 14.5265 15.0775L14.9202 5.625ZM7.18772 8.125V14.375C7.18772 14.6236 7.28649 14.8621 7.4623 15.0379C7.63812 15.2137 7.87657 15.3125 8.12522 15.3125C8.37386 15.3125 8.61231 15.2137 8.78813 15.0379C8.96394 14.8621 9.06272 14.6236 9.06272 14.375V8.125C9.06272 7.87636 8.96394 7.6379 8.78813 7.46209C8.61231 7.28627 8.37386 7.1875 8.12522 7.1875C7.87657 7.1875 7.63812 7.28627 7.4623 7.46209C7.28649 7.6379 7.18772 7.87636 7.18772 8.125ZM11.8752 7.1875C12.1239 7.1875 12.3623 7.28627 12.5381 7.46209C12.7139 7.6379 12.8127 7.87636 12.8127 8.125V14.375C12.8127 14.6236 12.7139 14.8621 12.5381 15.0379C12.3623 15.2137 12.1239 15.3125 11.8752 15.3125C11.6266 15.3125 11.3881 15.2137 11.2123 15.0379C11.0365 14.8621 10.9377 14.6236 10.9377 14.375V8.125C10.9377 7.87636 11.0365 7.6379 11.2123 7.46209C11.3881 7.28627 11.6266 7.1875 11.8752 7.1875Z"
                                fill="#5063F4" />
                        </svg>
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

    // import ViewCard from '../pendingdeposits/actions/ViewCard.vue'
    import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";


    import PlaceOferModal from '../../../auth/signup/shared/PopUpModal.vue'
    import CustomInput from '../../../shared/CustomInput.vue';
    import CustomSelect from '../../../shared/CustomSelect.vue';
    // import CustomMultiSelect from '../../../shared/CustomMultiSelect.vue';
    // import CustomDateInput
    import FileUpload from "../../../shared/FileUpload";
    import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
    import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";
    import CurrencyInput from '../../../shared/CurrencyInput.vue'
    import CustomMultiSelect from '../../../shared/CustomMultiSelect.vue'
    import { mapGetters } from 'vuex'

    import NewCustomSelect from '../../../shared/NewCustomSelect.vue'
    import { addDaysOrMonthsToDate, calculateIterestOnDateCountConnvention, formatTimestamp, DayCountConvention, dayDiffference } from '../../../../utils/commonUtils'

    export default {
        components: { CurrencyInput, NewCustomSelect, CustomMultiSelect, CustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, PlaceOferModal, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
        props: ['prime_rate', 'candelete', 'holidays', 'editData', 'defdaycount', 'day__counts', 'triparties_primary_baskets', 'formattedtimezone', 'bilateral_primary_baskets', 'depositor_demo_setup', 'bilaterals', 'triparties', 'isedit', 'count', 'request', 'defcurrency'],
        beforeMount() {
            if (this.request && !this.isedit) {
                this.setDefaults(this.request)
            }
            if (this.isedit && this.editData != null) {
                this.setDefaults(this.editData)
            }
        },
        mounted() {
            console.log('Mounted')
        },

        data() {
            return {

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

                haserror: false,
                reqid: null,
                collateralValue: null,
                collateralError: null,
                productError: null,
                collateralError: null,
                settlementError: null,
                istriparty: null,
                // date of deposit
                date_of_deposit: null,
                dateOfDepositError: null,

                // Add rate offer new request
                minAmountError: null,
                maxAmountError: null,
                minAmountInvalidError: null,
                maxAmountInvalidError: null,
                min_amount: null,
                max_amount: null,
                interestRateError: null,
                spreadRateError: null,
                rate_types: null,
                rate_type: 'fixed',
                spread_rate: null,
                interest_rate: null,
                prime_rate_formated: null,
                final_rate_type: ['fixed', 0],
                rate_operator: '+',
                selected_collateral_basket: null,
                selected_tri_party: null,
                selected_rate_type: 'fixed',
                product: null,
                varyrate: null,
                ptype: null,
                basket_types: [],
                primary_basket: null,
                primaryBasketError: null,
                settlement_date: null,
                daycount_convection: null,
                dayCountError: null,
                settle_period: null,
                loading: false,
                collateral_basket:
                    [
                        { id: 1, value: 'Basket 1' },
                        { id: 2, value: 'Basket 2' },
                        { id: 3, value: 'Basket 3' },
                        { id: 4, value: 'Basket 4' },
                    ],
            }
        },
        computed: {
            currencyOptions() {
                return this.$store.getters.systemCurrencies
            },
            ...mapGetters('repopostrequest', ['getPrimeRates', 'getsettlemntdate', 'getRequestSummary', 'getgloabalproducts']),

        },
        methods: {
            daycountChnage(value) {
                this.daycount_convection = value
                this.dayCountError = null

            },
            filteredBasket(baskets, filter_id) {
                if (baskets)
                    baskets = baskets.filter(item => item.primary_id == filter_id && item.currency == this.selectedCurrency)
                // console.log(baskets, "baskets")
                return baskets
            },
            changeProductValue(event) {
                this.primary_basket = null
                this.product = event
                this.selected_tri_party = null
                this.selected_collateral_basket = null
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
            changePrimaryBasket(event) {
                // console.log('primary_basket', event)
                this.primary_basket = event
                this.primaryBasketError = null
                this.selected_tri_party = null
                this.selected_collateral_basket = null
            },
            changeCollateralBasket(event, type) {
                // console.log(event)
                if (type == 'tri') {
                    this.selected_tri_party = event
                    this.selected_collateral_basket = null
                } else if (type == 'bi') {
                    this.selected_tri_party = null
                    this.selected_collateral_basket = event

                }
                this.collateralError = null

            },
            changeSettlementDate(event) {
                this.settlement_date = event
                this.settlementError = null
            },
            // second 3
            changeRateType(value) {
                this.spread_rate = null
                this.interest_rate = null
                if (value != 'fixed') {
                    this.interestRateError = false

                    this.rate_type = value.trim()
                    let foundElement = this.rate_types.find(element => element.id === this.rate_type);
                    console.log(foundElement, "foundElement");
                    if (foundElement) {
                        console.log(foundElement, "Rate value");
                        this.final_rate_type = [foundElement.id, foundElement.rate];
                        this.spread_rate = 0;
                        this.interest_rate = parseFloat(foundElement.rate);
                        this.entered_rate = parseFloat(foundElement.rate);
                        if (this.entered_rate >= 0.01) {
                            this.spreadRateError = null;
                        } else {
                            this.spreadRateError = "Enter a vallid spread rate";
                        }

                    }

                } else {
                    this.spreadRateError = null
                    this.rate_type = value.toLowerCase()
                    this.final_rate_type = ['fixed', 0]
                }
            },

            validateSpreadChange() {

                this.spread_rate = Number.parseFloat(this.spread_rate).toFixed(2);
                console.log(this.spread_rate, "this.final_rate_type[1]");
                this.varyrate = Number.parseFloat(this.final_rate_type[1]);

                // console.log(varyrate, "Varry Rate", this.final_rate_type[1], "final Rate", this.final_rate_type)


                if (this.spread_rate > 100) {
                    this.spreadRateError = "Rate is greater than 100%";
                } else {
                    if (this.rate_operator === "+") {
                        var sum = this.varyrate + Number.parseFloat(this.spread_rate);
                        this.interest_rate = sum.toFixed(2)
                        console.log(this.interest_rate, "new revised rate when adding");

                        if (sum > 100) {
                            this.spreadRateError = "Variable rate + prime rate is greater than 100%";
                            // console.log(sum);
                        } else {
                            if (Number.parseFloat(sum.toFixed(2)) < 0.01) {
                                let minmum = (Number.parseFloat(this.varyrate) >= 0) ? `- ${(Math.abs(this.varyrate) - 0.01)}` : 0.01 + Math.abs(this.varyrate);
                                this.spreadRateError = `Cannot be less than (${minmum}) `;
                            } else {

                                this.spreadRateError = null;
                            }

                        }
                    } else if (this.rate_operator === "-") {

                        var difference = this.varyrate - Number.parseFloat(this.spread_rate);
                        this.interest_rate = difference.toFixed(2)
                        console.log(this.interest_rate, "new revised rate when deducting");
                        if (difference < 0.01) {
                            let minmum = (Number.parseFloat(this.varyrate) >= 0) ? `- ${(Math.abs(this.varyrate) - 0.01)}` : 0.01 + Math.abs(this.varyrate);

                            this.spreadRateError = `Cannot be less than (${minimum})`;
                            // console.log(difference);
                        } else {
                            this.spreadRateError = null;
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
            validateMinAmount(value) {

                // this.min_amount = Number.parseFloat(value.replace(/,/g, ''))
                this.min_amount = Number.parseFloat(value, "Min Amount")
                if (this.min_amount == null || this.min_amount == '' || this.min_amount < 0 || this.min_amount > 9999999999999) {
                    this.minAmountError = "Please enter a valid amount"
                } else if (this.max_amount && this.min_amount > this.max_amount) {
                    this.minAmountError = "Minimum amount should be less than maximum amount"
                } else {
                    if (this.minAmountInvalidError) {
                        this.minAmountError = this.minAmountInvalidError
                    } else {
                        this.maxAmountError = null
                        this.minAmountError = null
                    }
                }
            },
            validateMaxAmount(value) {
                // console.log(value, "Max Value")
                this.max_amount = Number.parseFloat(value)
                // this.max_amount = Number.parseFloat(value.replace(/,/g, ''))
                if (this.max_amount == null || this.max_amount == '' || this.max_amount < 0 || this.max_amount > 9999999999999) {
                    this.maxAmountError = "Please enter a valid amount"
                } else if (this.min_amount && this.max_amount < this.min_amount) {
                    this.maxAmountError = "Maximum amount should be greater than minimum amount"
                } else {
                    if (this.maxAmountInvalidError) {
                        this.maxAmountError = this.maxAmountInvalidError
                    } else {
                        this.maxAmountError = null
                        this.minAmountError = null
                    }
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
            setDefaults(defvalue) {
                if (defvalue != undefined) {
                    this.selectedCurrency = this.defcurrency ? this.defcurrency : 'CAD'
                    this.min_amount = defvalue.min
                    this.max_amount = defvalue.max
                    this.termLengthValue = defvalue.term_length
                    this.termLength = defvalue.term_length_type
                    this.product = defvalue.product
                    this.selected_collateral_basket = defvalue.basket
                    this.date_of_deposit = defvalue.settlement_date
                    this.daycount_convection = this.defdaycount ? this.defdaycount : defvalue.convention_id
                    this.rate_type = defvalue.rate_type
                    this.selected_rate_type = defvalue.rate_type
                    if (this.rate_type === 'fixed') {
                        this.interest_rate = defvalue.entered_rate;
                    } else {
                        this.interest_rate = Number.parseFloat(defvalue.sum_rate).toFixed(2);
                        this.spread_rate = defvalue.entered_rate;
                    }

                    // this.rate_type == 'fixed' ? this.interest_rate = defvalue.entered_rate : this.spread_rate = defvalue.entered_rate
                    this.rate_operator = defvalue.operator
                    this.istriparty = defvalue.collateralType
                    this.selected_collateral_basket = defvalue.collateral_id
                    this.selected_tri_party = defvalue.basket
                    this.reqid = defvalue.reqid
                    this.primary_basket = defvalue.collateralType == 'tri' ? defvalue.primaryBasket : defvalue.collateralType == 'bi' ? defvalue.primaryCollateral : null
                }
                if (this.prime_rate) {
                    let primerate = this.getPrimeRates
                    this.prime_rate_formated = primerate
                    let ratess = [];
                    primerate.forEach(rate => {
                        if (rate.rate_label === 'Fixed') {
                            let fixed = {
                                id: rate.key,
                                name: `${rate.rate_label}`
                            };
                            ratess.push(fixed);
                            this.selectedRateType = fixed;
                        } else {
                            ratess.push({
                                id: rate.key,
                                rate: rate.value,
                                label: rate.rate_label,
                                name: `${rate.rate_label} (${rate.value}%)`
                            });
                        }

                    })
                    this.rate_types = ratess;
                }
                // this.changeRateType(this.rate_type)
                // this.final_rate_type = [, defvalue.entered_rate]

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

                // product data
                if (this.product == null) {
                    this.productError = "This field is required"
                    this.haserror = true
                }

                // collateral data
                if (this.selected_collateral_basket == null && this.selected_tri_party == null) {
                    this.collateralError = "This field is required"
                    this.haserror = true
                }

                // collateral data
                // if (this.settlement_date == null) {
                //     this.settlementError = "This field is required"
                //     this.haserror = true
                // }
                if (this.primary_basket == null) {
                    this.primaryBasketError = "This field is required"
                    this.haserror = true
                }
                if (this.date_of_deposit == null) {
                    this.dateOfDepositError = "This field is required"
                    this.haserror = true
                }
                if (this.daycount_convection == null) {
                    this.dayCountError = "This field is required"
                    this.haserror = true
                }


                // max Amount
                if (this.max_amount > 0 && this.maxAmountError == null) {
                    this.validateMaxAmount(this.max_amount.toString())
                } else {
                    this.maxAmountError = this.maxAmountError == null ? "This field is required" : this.maxAmountError
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
                        if (this.entered_rate <= 0.01) {
                            this.haserror = true
                            this.spreadRateError = this.spreadRateError == null ? "This field is required" : this.spreadRateError
                        } else {
                            this.haserror = false
                            this.spreadRateError = null;
                        }

                    }

                }
                // validate trade date
                // validate term length
                if (Number.parseFloat(this.termLengthValue) > 0 && this.termLengtherror == null) {
                } else {
                    this.haserror = true
                    this.termLengtherror = this.termLengtherror == null ? "This field is required" : this.termLengtherror
                }



                this.$emit('hasError', [this.count, this.haserror])
                const data = {
                    'reqid': this.reqid,
                    'currency': this.selectedCurrency,
                    'min': this.min_amount,
                    'collateralType': this.istriparty,
                    'collateral_id': this.istriparty == 'bi' ? this.selected_collateral_basket : '',
                    'max': this.max_amount,
                    'term_length': this.termLengthValue,
                    'term_length_type': this.termLength,
                    "product": this.product,
                    "basket": this.istriparty == 'tri' ? this.selected_tri_party : '',
                    "settlement_date": this.date_of_deposit,
                    "rate_type": this.rate_type,
                    "entered_rate": this.rate_type == 'fixed' ? this.interest_rate : this.spread_rate,
                    "sum_rate": Number.parseFloat(this.interest_rate),
                    "operator": this.rate_operator,
                    "convention_id": this.daycount_convection,
                }
                if (this.istriparty == 'tri') {
                    data['primaryBasket'] = this.primary_basket
                } else {
                    data['primaryCollateral'] = this.primary_basket
                }
                if (!this.haserror)
                    this.$emit('newRequest', [this.count, data])
            },
            deleteRequest() {
                this.$emit('deleteRequest', this.count)
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
            maxAmountInvalidError(newval, oldval) {
                // console.log(newval, ',', oldval)
                if (newval != null) {
                    this.maxAmountError = newval
                } else {
                    this.maxAmountInvalidError = null
                }
            },
            minAmountInvalidError(newval, oldval) {
                if (newval != null) {
                    this.minAmountError = newval
                } else {
                    this.minAmountInvalidError = null
                }
            },
            getRequestSummary() {
                this.selectedCurrency = this.getRequestSummary?.currency
                // console.log(this.getRequestSummary?.currency, "Def curr")
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