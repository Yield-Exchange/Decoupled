<template>
    <div class="w-100 mb-4">
        <!-- header -->
        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/post-request-repo.svg" alt="" srcset="">
                        </div>
                        <div class="text-div">Post Request {{ count + 1 }}</div>
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

        <div class="row shadow-sm my-3 w-100 mx-auto p-4 bg-white" v-if="viewMore1">
            <!-- deposit amount -->

            <div class="my-3 col-12 col-md-12">
                <p class="p-0-m-0 repo-notify">
                    Complete the fields below to receive competitive offers.
                </p>
            </div>
            <div class="my-3 col-md-6 col-sm-12">
                <FormLabelRequired labelText="Investment (Enter M for Million, B for Billion)" :required="true"
                    :showHelperText="false" helperText="Investment Amount" helperId="investementAmount" />
                <CurrencyInput placeholder="Enter Investment amount" :selector_width="20" style="margin-top: 5px;"
                    @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                    :currencyOptions="currencyOptions" inputType="number" :defaultValue="investementAmount"
                    @inputChanged="validateInvestmentAmount" @currencyError="investmentAmountError = $event"
                    :hasError="investmentAmountError" />
                <div v-if="investmentAmountError" class="error-message">
                    {{ investmentAmountError }}
                </div>
            </div>
            <div class="col-md-6 col-sm-12 my-3 " v-if="day__counts">
                <FormLabelRequired labelText="Day Count Convention" :required="true" :showHelperText="true"
                    helperText="Calculate interest based on the number of days in a period." helperId="daycount" />
                <NewCustomSelect :disabled="false" style="margin-top: 4px;" :haserror="dayCountError"
                    :options="day__counts" idkey="id" valuekey="label" placeholder="Select a day count"
                    :defaultValue="daycount_convection" @change="daycountChnage" />
                <div v-if="dayCountError" class="error-message">
                    {{ dayCountError }}
                </div>
            </div>
            <div class="col-md-6 col-sm-12 my-3">

                <!-- <FormLabelRequired labelText="Settlement Date" :required="true" :showHelperText="true"
                    helperText="Rate type" helperId="ratetype" />
                <NewCustomSelect style="margin-top: 4px" :haserror="settlementError" :options="getsettlemntdate"
                    idkey="id" @change="changeSettlementDate" valuekey="formated_date"
                    placeholder="Settlement i.e T+4 Days" :defaultValue="settlement_date" />

                <div v-if="settlementError" class="error-message">
                    {{ settlementError }}
                </div> -->
                <FormLabelRequired labelText="Settlement Date" :required="true" :showHelperText="true"
                    helperText="Choose the date when the transaction settles. " helperId="settledate" />

                <JQueryCustomDatePicker v-if="formattedtimezone" :cannotpicktime="true" style="margin-top: 5px;"
                    :hasError="dateOfDepositError" :id="count" :start_date="addWeekdays(0)"
                    :formattedtimezone="formattedtimezone" placeholder="Select date" :selected_date="date_of_deposit"
                    v-model="date_of_deposit" />


                <div v-if="dateOfDepositError" class="error-message">
                    {{ dateOfDepositError }}
                </div>
            </div>
            <div class="col-md-6 col-sm-12 my-3">
                <FormLabelRequired labelText="Term Length" :required="true" :showHelperText="true"
                    helperText="Specify the duration of the investment." helperId="termlength" />
                <div class="combined-input" :class="{ 'has-error': termLengtherror }"
                    style="margin-top: 4px;background-color: white !important;">
                    <b-form-select class="" id="termlengthid" v-model="termLength" ref="termLengthSelect"
                        @change="termLengthChange" :options="['Days', 'Months']" default-value="Days"
                        style="border: none;min-width:40px !important;width:19%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                    </b-form-select>
                    <b-form-input
                        style="border: none;min-width:100px !important;width:82%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                        maxlength="5" type="number" v-model="termLengthValue" step='1' min="0" @blur="termLengthChange"
                        :class="{ 'validation-error': termLengtherror }" placeholder="Enter length " />
                </div>
                <div v-if="termLengtherror" class="error-message">
                    {{ termLengtherror }}
                </div>
            </div>
            <div class="col-md-6 col-sm-12 my-3 ">
                <FormLabelRequired labelText="Maturity Date" :required="false" :showHelperText="false"
                    helperText="Interest Rate Offer" helperId="PDSHId" />
                <CustomInput inputType="text" input-style="font-size:14px !important" p-style="width:100%" id="rate"
                    name="Maturity Date" :has-validation="false" :disabled="true" @inputChanged="" input-type="text"
                    :defaultValue="settle_period" :hasSpecificError="false" />

            </div>

            <div class="col-md-6 col-sm-12 my-3">

                <FormLabelRequired labelText="Preferred Collateral (Select up to 3)" :required="true"
                    :showHelperText="true" helperText="Choose the most desired collaterals for your investments."
                    helperId="pcollaterals" />

                <CustomMultiSelect style="margin-top: 4px" v-if="getAllPrefferedCollaterals && getAllPrefferedCollaterals.length > 0"
                    :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                    :options="getAllPrefferedCollaterals"
                    :class="(collateralValue && collateralValue.length > 0) ? 'repo-multiselect-scroll' : 'repo-multiselect'"
                    id="product_type_id" placeholder="Select preferred collateral" :haserror="collateralError != null"
                    maximumSelectionLength="3" :currentValue="collateralValue" @selectChanged="collateralChange($event)"
                    v-model="collateralValue" />
                <div v-if="collateralError" class="error-message">
                    {{ collateralError }}
                </div>
            </div>

            <div class="d-flex justify-content-between gap-2">

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
// import CustomDateInput
import FileUpload from "../../../shared/FileUpload";
import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";
import CustomMultiSelect from '../../../shared/CustomMultiSelect.vue'
import NewCustomSelect from '../../../shared/NewCustomSelect.vue'
import { mapGetters } from 'vuex'
import CurrencyInput from '../../../shared/CurrencyInput.vue'
import { addDaysOrMonthsToDate, formatTimestamp } from '../../../../utils/commonUtils'


export default {
    components: { NewCustomSelect, CurrencyInput, CustomMultiSelect, CustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, PlaceOferModal, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
    props: ['candelete', 'editData', 'day__counts', 'holidays', 'depositor_demo_setup', 'isedit', 'count', 'formattedtimezone', 'request'],
    beforeMount() {
        if (this.request && !this.isedit) {
            this.setDefaults(this.request)
        }
        if (this.isedit && this.editData != null) {
            this.setDefaults(this.editData)
        }
    },
    mounted() {
        console.log(this.day__counts, "day counts")
    },

    data() {
        return {
            productType: [],
            currencyOptions: ['CAD', 'USD'],
            trade_date: null,
            termLength: 'Days',
            termLengthValue: null,
            ratingtypes: null,
            deposit_insurance: null,
            availableproducts: null,
            showAdvancedOptions: false,
            productname: 'Short Term',
            haslockout: false,
            product_id: 1,
            viewMore1: true,
            reqsummary: null,
            ishisa: false,
            securities: [
                { "id": 1, 'text': "US Treasuries" },
            ],

            compoundingfrequencies: ['At maturity', 'Monthly', 'Quarterly', 'Semi annually', 'Annually'],
            // expected variable
            selectedCurrency: 'CAD',
            investementAmount: null,
            specialInstructions: null,
            tradeDateError: null,
            compoundingfreq: 'At maturity',
            shorttermratting: null,
            depositInsurance: 'Any',
            lockout_period: null,
            lockoutPeriodError: null,
            termLengtherror: null,
            investmentAmountError: null,
            creditrating: "Any/Not Rated",
            characterCount: 100,
            specialInstructionsError: null,
            haserror: false,
            reqid: null,
            collateralValue: null,
            collateralError: null,
            settlement_date: null,
            settlementError: null,
            daycount_convection: null,
            dayCountError: null,
            date_of_deposit: null,
            dateOfDepositError: null,
            holidaysinayear: null,
            loading: false,
            settle_period: null,

        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllPrefferedCollaterals', 'getsettlemntdate']),
    },
    methods: {
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
        daycountChnage(value) {
            this.daycount_convection = value
            this.dayCountError = null
        },
        changeSettlementDate(event) {
            this.settlement_date = event
            this.settlementError = null
        },
        collateralChange(value) {
            this.collateralError = null
            if (value.length == 0) {
                this.collateralError = "Select atleast 1 collateral"
            }

            // console.log(this.collateralValue)
            this.collateralValue = value
        },
        cFreqChange(value) {
            this.productname = value
        },
        setDefaults(defvalue) {
            if (defvalue != undefined) {
                this.trade_date = defvalue.trade_date
                this.termLengthValue = defvalue.term_length
                this.termLength = this.capitalize(defvalue.term_length_type)
                this.selectedCurrency = defvalue.currency
                this.investementAmount = defvalue.investment_amount
                this.collateralValue = defvalue.preferred_collateral
                this.date_of_deposit = defvalue.settlementDate
                this.daycount_convection = defvalue.convention_id
                this.reqid = defvalue.reqid
                // this.product_id = defvalue.product_id
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
        validateInvestmentAmount(value) {
            console.log(value)
            this.investementAmount = value
            if (this.investementAmount == null || this.investementAmount == '' || this.investementAmount < 0 || this.investementAmount > 9999999999999) {
                this.investmentAmountError = "Please enter a valid amount"
            } else {
                this.investmentAmountError = null
            }

        },
        ableToSubmit() {
            this.haserror = false
            if (this.investementAmount > 0 && this.investmentAmountError == null) {
            } else {
                this.investmentAmountError = this.investmentAmountError == null ? "This field is required" : this.investmentAmountError
                this.haserror = true
            }
            // validate trade date
            // if (this.trade_date != null && this.tradeDateError == null) {
            // } else {
            //     this.haserror = true
            //     this.tradeDateError = this.tradeDateError == null ? "This field is required" : this.tradeDateError
            // }
            // settlement
            if (this.date_of_deposit != null && this.dateOfDepositError == null) {
            } else {
                this.haserror = true
                this.dateOfDepositError = this.dateOfDepositError == null ? "This field is required" : this.dateOfDepositError
            }
            if (this.daycount_convection != null && this.dayCountError == null) {
            } else {
                this.haserror = true
                this.dayCountError = this.dayCountError == null ? "This field is required" : this.dayCountError
            }
            // validate term length
            if (Number.parseFloat(this.termLengthValue) > 0 && this.termLengtherror == null) {
            } else {
                this.haserror = true
                this.termLengtherror = this.termLengtherror == null ? "This field is required" : this.termLengtherror
            }
            // validate Collateral
            if (this.collateralValue != null && this.collateralValue.length > 0 && this.collateralError == null) {
            } else {
                this.haserror = true
                this.collateralError = this.collateralError == null ? "This field is required" : this.collateralError
            }


            this.$emit('hasError', [this.count, this.haserror])
            const data = {
                'reqid': this.reqid,
                'trade_date': '2024-12-2',
                'term_length': this.termLengthValue,
                'term_length_type': this.termLength,
                'preferred_collateral': this.collateralValue,
                'settlementDate': this.date_of_deposit,
                'currency': this.selectedCurrency,
                'investment_amount': this.investementAmount,
                'convention_id': this.daycount_convection,
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
    },
    watch: {
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
                        this.dateOfDepositError = null
                        this.isValidDate()
                    }
                }
            }

        },
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
.has-error {
    border: 0.5px solid red !important;
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
    color: #5063F4 !important;
    text-align: center;
    font-feature-settings: 'liga' off, 'clig' off;
    font-family: Montserrat !important;
    font-size: 22px !important;
    font-style: normal;
    font-weight: 700 !important;
    line-height: 26px;
    /* 118.182% */
}
</style>