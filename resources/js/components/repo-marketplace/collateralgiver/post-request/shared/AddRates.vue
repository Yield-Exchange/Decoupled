<template>
    <div class="w-100 my-4">
        <!-- header -->
        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 100%">
                    <div class="page-title">
                        <!-- <div class="title-icon">
                            <img src="/assets/dashboard/icons/rate_offer.svg" alt="" srcset="">
                        </div> -->
                        <div class="text-div">{{ basket_name }}</div>
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
        <div class="row bg-white my-3 gx-0" style="padding: 20px;" v-show="viewMore1" v-for="(offer, index) in request"
            :key="offer.offer_uniqueid">
            <div class="" style="width: 270px;">
                <RateCard :haircut="offer.haircut" :interest_rate="offer.interest_rate" :rating="offer.rating"
                    :organization="offer.organization" :basket_id_no="offer.basket_id_no"></RateCard>
            </div>

            <div style="width: calc(100% - 270px);"
                class="d-flex flex-column justify-content-between align-items-between gap-2">
                <div class="d-flex justify-content-end align-items-center  gap-2 pointer">
                    <div v-if="index == 0 && request.length > 1" @click="attemptCopy(offer.offer_uniqueid)"
                        class="d-flex justify-content-center align-items-center gap-2" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
                            <path
                                d="M0 2.79167C0 2.18388 0.241443 1.60098 0.671214 1.17121C1.10098 0.741443 1.68388 0.5 2.29167 0.5H12.7083C13.3161 0.5 13.899 0.741443 14.3288 1.17121C14.7586 1.60098 15 2.18388 15 2.79167V8.01833C14.488 7.69095 13.9246 7.45206 13.3333 7.31167V2.79167C13.3333 2.62591 13.2675 2.46694 13.1503 2.34972C13.0331 2.23251 12.8741 2.16667 12.7083 2.16667H2.29167C2.12591 2.16667 1.96694 2.23251 1.84972 2.34972C1.73251 2.46694 1.66667 2.62591 1.66667 2.79167V13.2083C1.66667 13.5533 1.94667 13.8333 2.29167 13.8333H6.81167C6.95333 14.4333 7.195 14.9958 7.51833 15.5H2.29167C1.68388 15.5 1.10098 15.2586 0.671214 14.8288C0.241443 14.399 0 13.8161 0 13.2083V2.79167ZM16.6667 12.5833C16.6667 11.3678 16.1838 10.202 15.3242 9.34243C14.4647 8.48288 13.2989 8 12.0833 8C10.8678 8 9.70197 8.48288 8.84243 9.34243C7.98288 10.202 7.5 11.3678 7.5 12.5833C7.5 13.7989 7.98288 14.9647 8.84243 15.8242C9.70197 16.6838 10.8678 17.1667 12.0833 17.1667C13.2989 17.1667 14.4647 16.6838 15.3242 15.8242C16.1838 14.9647 16.6667 13.7989 16.6667 12.5833ZM12.5 13L12.5008 15.0858C12.5008 15.1963 12.4569 15.3023 12.3788 15.3805C12.3007 15.4586 12.1947 15.5025 12.0842 15.5025C11.9737 15.5025 11.8677 15.4586 11.7895 15.3805C11.7114 15.3023 11.6675 15.1963 11.6675 15.0858V13H9.58C9.46949 13 9.36351 12.9561 9.28537 12.878C9.20723 12.7998 9.16333 12.6938 9.16333 12.5833C9.16333 12.4728 9.20723 12.3668 9.28537 12.2887C9.36351 12.2106 9.46949 12.1667 9.58 12.1667H11.6667V10.0833C11.6667 9.97283 11.7106 9.86685 11.7887 9.7887C11.8668 9.71056 11.9728 9.66667 12.0833 9.66667C12.1938 9.66667 12.2998 9.71056 12.378 9.7887C12.4561 9.86685 12.5 9.97283 12.5 10.0833V12.1667H14.5808C14.6913 12.1667 14.7973 12.2106 14.8755 12.2887C14.9536 12.3668 14.9975 12.4728 14.9975 12.5833C14.9975 12.6938 14.9536 12.7998 14.8755 12.878C14.7973 12.9561 14.6913 13 14.5808 13H12.5Z"
                                fill="#5063F4" />
                        </svg>
                        <p class="p-0 m-0 copy-button-text">Copy for all {{ basket_name }}s</p>
                        <template>
                            <img src="/assets/dashboard/icons/helpicon.svg" :id="'summary' + offer.offer_uniqueid"
                                alt="" srcset="">
                            <Tooltip v-if="true"
                                :message="`Click this button to copy the offer data to all other ${basket_name}s in the list. Make sure all fields are filled in correctly to avoid any errors.`"
                                :target="'summary' + offer.offer_uniqueid" />
                        </template>
                    </div>
                    <p class="p-0 m-0 copy-button-text"
                        v-if="offerWhere(offer.offer_uniqueid).duplicated && index != 0"> Copied</p>

                    <div @click="deleteOffer(offer.offer_uniqueid)"
                        class="d-flex justify-content-center align-items-center gap-2 pointer" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                            <path
                                d="M6.33398 17.5C5.87565 17.5 5.48343 17.3369 5.15732 17.0108C4.83121 16.6847 4.66787 16.2922 4.66732 15.8333V5H3.83398V3.33333H8.00065V2.5H13.0007V3.33333H17.1673V5H16.334V15.8333C16.334 16.2917 16.1709 16.6842 15.8448 17.0108C15.5187 17.3375 15.1262 17.5006 14.6673 17.5H6.33398ZM14.6673 5H6.33398V15.8333H14.6673V5ZM8.00065 14.1667H9.66732V6.66667H8.00065V14.1667ZM11.334 14.1667H13.0007V6.66667H11.334V14.1667Z"
                                fill="#FF2E2E" />
                        </svg>
                        <!-- <p class="p-0 m-0">Copy</p> -->
                    </div>
                </div>
                <div class="row w-100 mx-auto bg-white align-self-baseline">
                    <!-- arte type  -->
                    <div class="col-md-4 mb-20 ">

                        <FormLabelRequired labelText="Rate type" :required="true" :showHelperText="true"
                            helperText="Rate type" helperId="ratetype" />
                        <NewCustomSelect style="margin-top: 4px;" :haserror="false" :options="rate_types" idkey="id"
                            valuekey="name" placeholder="Select rate"
                            :defaultValue="offerWhere(offer.offer_uniqueid).rate_type"
                            @change="changeRateType($event, offer.offer_uniqueid)" />
                    </div>

                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Spread (%) " :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="rate-type" />
                        <div class="combined-input"
                            :class="{ 'disabled-div': offerWhere(offer.offer_uniqueid).rate_type == 'fixed', 'error-repo-inputs': spreadRateError[offer.offer_uniqueid] }"
                            style="margin-top: 4px;">
                            <b-form-select :disabled="offerWhere(offer.offer_uniqueid).rate_type == 'fixed'"
                                :class="{ 'disabled-input': offerWhere(offer.offer_uniqueid).rate_type == 'fixed' }"
                                id="termlengthid" ref="termLengthSelect"
                                v-model="offerWhere(offer.offer_uniqueid).operator"
                                @change="changeOpertor($event, offer.offer_uniqueid)" :options="['+', '-']"
                                style="border: none;width:25%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                            </b-form-select>
                            <b-form-input :disabled="offerWhere(offer.offer_uniqueid).rate_type == 'fixed'"
                                :class="{ 'disabled-input': offerWhere(offer.offer_uniqueid).rate_type == 'fixed', 'validation-error': false }"
                                style="border: none;min-width:100px !important;font-size:14px !important;width:79%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                                type="number" step='0.01' min="-100"
                                v-model="offerWhere(offer.offer_uniqueid).spreadvalue"
                                @blur="changeSpreadValue($event.target.value, offer.offer_uniqueid)"
                                placeholder="eg. 3" />
                        </div>
                        <div v-if="spreadRateError[offer.offer_uniqueid]" class="error-message">
                            {{ spreadRateError[offer.offer_uniqueid] }}
                        </div>
                    </div>
                    <!-- deposit amount -->

                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Interest Rate (%)" :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput inputType="number" input-style="font-size:14px !important" p-style="width:100%"
                            id="rate" name="Rate*" :has-validation="true"
                            :disabled="offerWhere(offer.offer_uniqueid).rate_type != 'fixed'"
                            @inputChanged="InterestRateChange($event, offer.offer_uniqueid)" input-type="number"
                            :defaultValue="offerWhere(offer.offer_uniqueid).interest_rate"
                            :hasSpecificError="interestRateError[offer.offer_uniqueid]" />
                        <div v-if="interestRateError[offer.offer_uniqueid]" class="error-message">
                            {{ interestRateError[offer.offer_uniqueid] }}
                        </div>
                    </div>

                    <!-- Amount sections -->

                    <template>

                        <div class=" col-12 col-md-4 mb-20">
                            <FormLabelRequired labelText="Minimum Amount" required="true" :showHelperText="false"
                                helperText="Investment Amount" helperId="investementAmount" />
                            <CurrencyInput :disabledcurr="false" placeholder="Enter minimum amount"
                                style="margin-top: 5px;"
                                @selectedCurrency="selectedCurrencyChange($event, offer.offer_uniqueid)"
                                :selectedCurrency="offerWhere(offer.offer_uniqueid).currency"
                                :currencyOptions="currencyOptions" inputType="number"
                                :defaultValue="offerWhere(offer.offer_uniqueid).min"
                                @inputChanged="validateMinAmount($event, offer.offer_uniqueid)"
                                @currencyError="minAmountInvalidError($event, offer.offer_uniqueid)"
                                :hasError="minAmountError[offer.offer_uniqueid]" />
                            <div v-if="minAmountError[offer.offer_uniqueid]" class="error-message">
                                {{ minAmountError[offer.offer_uniqueid] }}
                            </div>
                        </div>

                        <div class=" col-12 col-md-4 mb-20">
                            <FormLabelRequired labelText="Maximum Amount" required="true" :showHelperText="false"
                                helperText="Investment Amount" helperId="investementAmount" />
                            <CurrencyInput :disabledcurr="false" placeholder="Enter maximum amount"
                                style="margin-top: 5px;"
                                @selectedCurrency="selectedCurrencyChange($event, offer.offer_uniqueid)"
                                :selectedCurrency="offerWhere(offer.offer_uniqueid).currency"
                                :currencyOptions="currencyOptions" inputType="number"
                                :defaultValue="offerWhere(offer.offer_uniqueid).max"
                                @inputChanged="validateMaxAmount($event, offer.offer_uniqueid)"
                                @currencyError="maxAmountInvalidError($event, offer.offer_uniqueid)"
                                :hasError="maxAmountError[offer.offer_uniqueid]" />
                            <div v-if="maxAmountError[offer.offer_uniqueid]" class="error-message">
                                {{ maxAmountError[offer.offer_uniqueid] }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-20 ">
                            <FormLabelRequired labelText="Day Count" :required="true" :showHelperText="true"
                                helperText="Calculate interest based on the number of days in a period."
                                helperId="daycount" />
                            <NewCustomSelect :disabled="false" style="margin-top: 4px;" :haserror="dayCountError"
                                :options="day__counts" idkey="id" valuekey="label" placeholder="Select a day count"
                                :defaultValue="offerWhere(offer.offer_uniqueid).convention_id"
                                @change="dayCountChange($event, offer.offer_uniqueid)" />
                            <div v-if="dayCountError" class="error-message">
                                {{ dayCountError }}
                            </div>
                        </div>
                    </template>

                    <template>
                        <!-- term length -->
                        <div class="col-md-4  ">

                            <FormLabelRequired labelText="Offer Valid Till" :required="true" :showHelperText="true"
                                helperText="Choose the date when the transaction settles. " helperId="settledate" />

                            <JQueryCustomDatePicker v-if="formattedtimezone" :cannotpicktime="true"
                                style="margin-top: 5px;" :hasError="dateOfDepositError[offer.offer_uniqueid]"
                                :id="offer.offer_uniqueid" :start_date="addWeekdays(0)"
                                :formattedtimezone="formattedtimezone" placeholder="Select date"
                                @inputChange="datePicker($event, offer.offer_uniqueid)"
                                :selected_date="offerWhere(offer.offer_uniqueid).rate_valid_until"
                                v-model="offerWhere(offer.offer_uniqueid).rate_valid_until" />


                            <div v-if="dateOfDepositError[offer.offer_uniqueid]" class="error-message">
                                {{ dateOfDepositError[offer.offer_uniqueid] }}
                            </div>
                        </div>

                        <div class="col-12 col-md-4  ">
                            <FormLabelRequired labelText="Term Length" :required="true" :showHelperText="true"
                                helperText="Specify the duration of the investment." helperId="termlength" />
                            <div class="combined-input" :class="{ 'has-error': termLengtherror[offer.offer_uniqueid] }"
                                style="margin-top: 4px;background-color: white !important;">
                                <b-form-select class="" id="termlengthid"
                                    v-model="offerWhere(offer.offer_uniqueid).term_length_type" ref="termLengthSelect"
                                    @change="termLengthChangeType($event, offer.offer_uniqueid)"
                                    :options="['Days', 'Months']" default-value="Days"
                                    style="border: none;min-width:100px !important;width:21%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                </b-form-select>
                                <b-form-input
                                    style="border: none;min-width:100px !important;font-size:14px !important;width:79%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                                    type="number" v-model="offerWhere(offer.offer_uniqueid).term_length" step='1'
                                    min="0" @blur="termLengthChange($event.target.value, offer.offer_uniqueid)"
                                    :class="{ 'validation-error': termLengtherror[offer.offer_uniqueid] }"
                                    placeholder="Enter length " />
                            </div>
                            <div v-if="termLengtherror[offer.offer_uniqueid]" class="error-message">
                                {{ termLengtherror[offer.offer_uniqueid] }}
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="deleterequest = false" @btnTwoClicked="doDeleteOffer"
            @btnOneClicked="deleterequest = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Delete Request" :showm="deleterequest">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to delete this request</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="attempt_copy = false" @btnTwoClicked="copyForAll"
            @btnOneClicked="attempt_copy = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Copy this offer?" :showm="attempt_copy">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to copy this request for all the {{
                basket_name }}'s</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fill_all_values = false"
            @btnTwoClicked="fill_all_values = false" @btnOneClicked="fill_all_values = false" btnOneText=""
            btnTwoText="close" icon="/assets/signup/danger.svg" title="Missing Values" :showm="fill_all_values">
            <div class="ml-5 description-text-withdraw ">Please ensure you have all value for this offer filled in
                correctly.
            </div>
        </ActionMessage>
    </div>
</template>

<script>
    import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue'
    import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'
    import CustomSelectInput from '../../../../auth/signup/shared/CustomSelectInput.vue'
    import CustomTextInput from '../../../../auth/signup/shared/CustomTextInput.vue'
    import CustomDateInput from '../../../../auth/signup/shared/CustomDateInput.vue'

    import FormLabelRequired from '../../../../shared/formLabels/FormLabelRequired.vue'


    import PlaceOferModal from '../../../../auth/signup/shared/PopUpModal.vue'
    import CustomInput from '../../../../shared/CustomInput.vue';
    import CustomSelect from '../../../../shared/CustomSelect.vue';
    // import CustomMultiSelect from '../../../../shared/CustomMultiSelect.vue';
    // import CustomDateInput
    import JQueryCustomDatePicker from '../../../../shared/JQueryCustomDatePicker.vue';
    import CustomCurrencyValueInput from "../../../../shared/CustomCurencyAmount.vue";
    import CurrencyInput from '../../../../shared/CurrencyInput.vue'
    import CustomMultiSelect from '../../../../shared/CustomMultiSelect.vue'
    import RateCard from './RateCard.vue'
    import { mapGetters } from 'vuex'

    import NewCustomSelect from '../../../../shared/NewCustomSelect.vue'
    import * as types from '../../../../../store/modules/publishratesoffer/mutation-types'

    import { addDaysOrMonthsToDate, generateRandomValue, calculateIterestOnDateCountConnvention, formatTimestamp, DayCountConvention, dayDiffference } from '../../../../../utils/commonUtils'
    import Tooltip from '../../../../shared/Tooltip.vue'

    export default {
        components: { Tooltip, RateCard, ActionMessage, CurrencyInput, NewCustomSelect, CustomMultiSelect, CustomSelect, CustomCurrencyValueInput, JQueryCustomDatePicker, FormLabelRequired, PlaceOferModal, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
        props: ['prime_rate', 'candelete', 'holidays', 'defdaycount', 'created_from', 'day__counts', 'formattedtimezone', 'count', 'request', 'defcurrency'],
        beforeMount() {
            if (this.request) {
                this.basket_name = this.request[0].primary_basket.basket_name ? this.request[0].primary_basket.basket_name : this.request[0].primary_basket.collateral_name
                // this.setDefaults(this.request)
            }
            this.setRateTypes()
        },
        mounted() {
            // console.log('Mounted')
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
                fill_all_values: false,
                reqsummary: null,
                // expected variable
                selectedCurrency: 'CAD',
                termLengtherror: {},
                basket_name: null,

                haserror: false,
                reqid: null,
                collateralValue: null,
                collateralError: {},
                productError: null,
                settlementError: null,
                istriparty: null,
                // date of deposit
                date_of_deposit: null,
                dateOfDepositError: {},

                // Add rate offer new request
                minAmountError: {},
                maxAmountError: {},

                min_amount: null,
                max_amount: null,
                interestRateError: {},
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
                spreadRateError: {},
                settlement_date: null,
                daycount_convection: null,
                dayCountError: null,
                settle_period: null,
                loading: false,
                attempt_copy: false,
                item_to_copy: null,

                deleterequest: false,
                request_to_delete: null,
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
            ...mapGetters('publishrateoffer', ['offer_rates', 'getOffers', 'getSelectedFis', 'selected_offers'])
        },
        methods: {
            dayCountChange(value, index) {
                this.daycount_convection = value
                this.dayCountError = null
                this.changeOfferValue(value, 'convention', index)


            },
            filteredBasket(baskets, filter_id) {
                if (baskets)
                    baskets = baskets.filter(item => item.primary_id == filter_id && item.currency == this.selectedCurrency)
                // console.log(baskets, "baskets")
                return baskets
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
            termLengthChangeType(eventval, index) {
                this.changeOfferValue(eventval, 'tltype', index)
                this.termLengthChange(null, index, true)

            },
            doDeleteOffer() {
                let offers = this.getOffers; // Assuming getOffers is a method and should be called
                let offertodelete = { ...this.offerWhere(this.request_to_delete) };
                let filteredOffers = offers.filter(offer => offer.offer_uniqueid !== this.request_to_delete); // Use '===' for comparison
                // console.log(filteredOffers)
                this.$store.commit('publishrateoffer/' + types.SET_OFFERS, filteredOffers);

                // remove slected FI
                let ct = offertodelete.ct

                let selectd_fis = [...this.getSelectedFis]

                if (!filteredOffers.some(item => item.ct == ct)) {
                    selectd_fis = selectd_fis.filter(item => item != ct);
                    this.$store.commit('publishrateoffer/' + types.SET_SELECTED_FIS, selectd_fis);
                }
                console.log(this.created_from, "Created from")
                if (this.created_from == 'Copied') {
                    let ref_id = offertodelete.offer_id
                    if (!filteredOffers.some(item => item.offer_id == ref_id)) {
                        let selectedoffers = [...this.selected_offers]
                        selectedoffers = selectedoffers.filter(item => item != ref_id);
                        this.$store.commit('publishrateoffer/' + types.SET_SELECTED_OFFERS, selectedoffers);
                    }
                }

                if (filteredOffers.length > 0)
                    this.$emit('regroupData')
                else
                    this.$emit('goBack')
                this.deleterequest = false
            },
            deleteOffer(offerid) {
                this.request_to_delete = offerid
                this.deleterequest = true
            },
            generateRandomValue() {
                return generateRandomValue()
            },
            attemptCopy(id) {
                this.item_to_copy = id
                if (this.checkIfHasNullValuesAndErrors())
                    this.fill_all_values = true
                else
                    this.attempt_copy = true
            },
            checkIfHasNullValuesAndErrors() {
                const offer = this.request[0]
                // console.log("Able to submit")
                const isValidRate = (rate) => rate > 0 && rate <= 100;
                const isValidMinMax = (min, max) => min > 0 && min <= 9999999999999 && min <= max && max > 0 && max <= 9999999999999;

                let hasError = false;


                let index = offer.offer_uniqueid
                console.log(index, ' index')
                if (offer.rate_type === 'fixed') {
                    if (!(offer.entered_rate && isValidRate(offer.entered_rate))) {
                        this.InterestRateChange(offer.interest_rate, index)
                        hasError = true
                    }
                } else {
                    if (!(offer.spreadvalue && isValidRate(offer.interest_rate))) {
                        this.changeSpreadValue(offer.spreadvalue, index)
                        hasError = true
                    }
                }

                // Check min and max values
                if (offer.min && offer.max) {
                    if (!isValidMinMax(offer.min, offer.max)) {
                        this.validateMinAmount(offer.min, index)
                        this.validateMaxAmount(offer.max, index)
                        hasError = true
                    }
                } else {
                    this.validateMinAmount(offer.min, index)
                    this.validateMaxAmount(offer.max, index)
                }

                // Check rate validity and term length
                if (!(offer.rate_valid_until && offer.term_length)) {
                    if (offer.rate_valid_until == null || offer.rate_valid_until == '') {
                        hasError = true
                        this.$set(this.dateOfDepositError, index, "This field is required")
                    }
                    if (offer.term_length == null || offer.termLength == '') {
                        hasError = true
                        this.$set(this.termLengtherror, index, "This field is required")

                    }

                }
                if (hasError) {
                    hasError = true
                } else {
                    // has no errror
                    if (
                        this.spreadRateError[index] == null &&
                        this.interestRateError[index] == null &&
                        this.minAmountError[index] == null &&
                        this.maxAmountError[index] == null &&
                        this.dateOfDepositError[index] == null &&
                        this.termLengtherror[index] == null
                    ) {
                        hasError = false;
                    } else {
                        hasError = true;
                    }

                }

                return hasError

            },
            copyForAll() {
                this.attempt_copy = false
                let offer_id = this.item_to_copy
                // Get the selected offer (this is the offer whose data will be used for updating others)
                const currentSelectedOffer = this.offerWhere(offer_id);
                this.spreadRateError = {}
                this.interestRateError = {}
                this.minAmountError = {}
                this.maxAmountError = {}
                this.dateOfDepositError = {}
                this.termLengtherror = {}
                // Shallow copy of the offers array
                const offers = [...this.getOffers];
     
                // Iterate through the requests and update the offers array
                this.request.forEach(request => {
                    // Check if the current request's offer_uniqueid matches the offer_id
                    if (request.offer_uniqueid != offer_id) {
                        // Update the specific offer in the offers array (matching the offer_uniqueid)
                        const updatedOffer = offers.find(offer => offer.offer_uniqueid === request.offer_uniqueid);
     
                        if (updatedOffer) {
                            // Update the found offer with the new values from the selected offer
                            Object.assign(updatedOffer, {
                                currency: currentSelectedOffer.currency,
                                min: currentSelectedOffer.min,
                                max: currentSelectedOffer.max,
                                duplicated: true,
                                term_length_type: currentSelectedOffer.term_length_type,
                                term_length: currentSelectedOffer.term_length,
                                rate_valid_until: currentSelectedOffer.rate_valid_until,
                                convention_id: currentSelectedOffer.convention_id,
                                rate_type: currentSelectedOffer.rate_type,
                                rate_type_value: currentSelectedOffer.rate_type_value,
                                entered_rate: currentSelectedOffer.entered_rate,
                                spreadvalue: currentSelectedOffer.spreadvalue,
                                interest_rate: currentSelectedOffer.interest_rate,
                                operator: currentSelectedOffer.operator
                            });
                        }
                    }
                });
     
                // Commit the updated offers array to Vuex store
                this.$store.commit('publishrateoffer/' + types.SET_OFFERS, offers);
     
                // Emit the regroupData event
                this.$emit('regroupData');
            },
     
            oldCopyForAll() {
                // Create a shallow copy of the `this.getOffers` array to avoid modifying the original
                let offers = [...this.getOffers];  // This creates a shallow copy of the array
                let new_offers = [];

                // Iterate through `this.request` to generate new offers with unique IDs
                this.request.forEach(request => {
                    let randValue = this.generateRandomValue();  // Assuming generateRandomValue() is defined elsewhere
                    let current_offer = { ...request };  // Create a shallow copy of the request to avoid mutating the original object
                    current_offer.offer_uniqueid = randValue;

                    // Add the modified offer to the `new_offers` array
                    new_offers.push(current_offer);
                });

                // Merge the new offers with the original offers array (which is independent of `this.getOffers`)
                offers.push(...new_offers);

                console.log(offers, "Offers");

                // Commit the updated offers array to Vuex store
                this.$store.commit('publishrateoffer/' + types.SET_OFFERS, offers);

                // Emit the regroupData event
                this.$emit('regroupData');
            },
            datePicker(eventval, index) {
                this.changeOfferValue(eventval, 'dpicker', index)
                console.log(eventval)
                if (this.isWeekend(eventval)) {
                    this.$set(this.dateOfDepositError, index, "Counter party does not accept rates on weekends")
                } else {
                    let found_item = null
                    let year_to_check = new Date(eventval).getFullYear()
                    let thisyear = new Date().getFullYear()
                    this.$set(this.termLengtherror, index, null)
                    if (year_to_check != thisyear) {
                        this.getAllHolidaysInAnYear(eventval, eventval, 'date', index)
                    } else {
                        found_item = this.holidays.find(item => item.date == eventval)
                        if (found_item)                // if (this.holidays.find(item => item.date == eventval))
                            this.$set(this.dateOfDepositError, index, "Sorry this day will be on a holiday")
                        else {
                            // this.getMaturityDate()
                            // this.settle_period = dayDiffference(new Date(), eventval)
                            this.$set(this.dateOfDepositError, index, null)
                            // this.isValidDate()
                        }
                    }
                }

            },

            termLengthChange(eventval, index, test = false) {
                if (!test)
                    this.changeOfferValue(eventval, 'termlength', index)
                let offer = this.offerWhere(index)
                let termLength = offer.term_length_type
                let value = Number.parseFloat(offer.term_length)
                if (value < 1 || value == null || value == '') {
                    this.$set(this.termLengtherror, index, "Term length cannot be less than 1")
                } else {
                    if (termLength == "Days") {
                        if (value > 3650) {
                            this.$set(this.termLengtherror, index, "Term length cannot be greater than 3650 Days")
                        } else {
                            // this.getMaturityDate()
                            this.$set(this.termLengtherror, index, null)
                            this.isValidDate(index)
                        }
                    } else {
                        if (value > 120) {
                            this.$set(this.termLengtherror, index, "Term length cannot be greater than 120 Months")
                        } else {
                            // this.getMaturityDate()
                            this.$set(this.termLengtherror, index, null)
                            this.isValidDate(index)
                        }

                    }
                }
            },


            changeSettlementDate(event) {
                this.settlement_date = event
                this.settlementError = null
            },
            offerWhere(value) {
                let found_item = this.getOffers.find(item => item.offer_uniqueid == value)
                return found_item

            },
            changeOfferValue(offervalue, section, index) {
                let offers = this.getOffers

                offers.forEach(element => {
                    if (element.offer_uniqueid == index) {
                        if (section == 'rate_type') {
                            element.rate_type = offervalue
                        }
                        if (section == 'enteredrate') {
                            element.entered_rate = offervalue
                        }
                        if (section == 'interestrate') {
                            element.interest_rate = offervalue
                        }
                        if (section == 'minamount') {
                            element.min = offervalue
                        }
                        if (section == 'maxamount') {
                            element.max = offervalue
                        }
                        if (section == 'selectedcurrency') {
                            element.currency = offervalue
                        }
                        if (section == 'convention') {
                            element.convention_id = offervalue
                        }
                        if (section == 'tltype') {
                            element.term_length_type = offervalue
                        }
                        if (section == 'termlength') {
                            element.term_length = offervalue
                        }
                        if (section == 'dpicker') {
                            element.rate_valid_until = offervalue
                        }
                        if (section == 'rate_type_value') {
                            element.rate_type_value = offervalue
                        }
                        if (section == 'operator') {
                            element.operator = offervalue
                        }
                        if (section == 'spreadvalue') {
                            element.spreadvalue = offervalue
                        }
                        if (section == 'termLengthError') {
                            element.termLengthHasAnError = offervalue
                        }
                    }
                });

                this.$store.commit('publishrateoffer/' + types.SET_OFFERS, offers);


            },
            // second 3
            changeRateType(value, index) {
                this.changeOfferValue(0, 'spreadvalue', index)
                this.changeOfferValue(null, 'enteredrate', index)
                this.changeOfferValue(null, 'interestrate', index)
                this.$set(this.spreadRateError, index, null)
                this.$set(this.interestRateError, index, null)

                let foundElement = this.prime_rate_formated.find(element => element.key === value);

                if (value != 'fixed') {
                    if (foundElement) {

                        this.changeOfferValue(foundElement.value, 'rate_type_value', index)
                        this.changeOfferValue(0, 'enteredrate', index)
                        this.changeOfferValue(parseFloat(foundElement.value), 'interestrate', index)
                        if (parseFloat(foundElement.value) <= 0) {
                            let minamount = 0.01 + Math.abs(parseFloat(foundElement.value));
                            this.$set(this.spreadRateError, index, `Enter a value greater than ${minamount}`)
                        }

                        // console.log(foundElement, "Spread Value", this.prime_rate_formated)
                    }

                } else {
                    this.changeOfferValue(0, 'rate_type_value', index)
                }
                this.changeOfferValue(value.trim(), 'rate_type', index)
            },
            changeOpertor(value, elementid) {
                this.changeOfferValue(value, 'operator', elementid)
                this.validateSpreadChange(elementid)

            },
            changeSpreadValue(value, elementid) {

                // console.log(value,'value')
                this.changeOfferValue(value, 'spreadvalue', elementid)
                this.changeOfferValue(value, 'enteredrate', elementid)

                this.validateSpreadChange(elementid)
            },

            validateSpreadChange(index) {
                let offer = this.offerWhere(index)
                console.log("Validation launched " + offer.spreadvalue);
                var spread_rate = null
                var interest_rate = null
                var varyrate = Number.parseFloat(offer.rate_type_value)
                var operator = offer.operator
                if (offer.spreadvalue) {
                    spread_rate = Number.parseFloat(offer.spreadvalue)
                }

                // console.log(varyrate, "Varry Rate", this.final_rate_type[1], "final Rate", this.final_rate_type)
                if (spread_rate) {
                    if (spread_rate > 100) {
                        // this.spreadRateError = "Rate is greater than 100%";
                        this.$set(this.spreadRateError, index, "Rate is greater than 100%")

                    } else {
                        console.log("Executed A " + offer.spreadvalue);
                        if (operator === "+") {

                            var sum = varyrate + Number.parseFloat(spread_rate);
                            interest_rate = sum
                            if (sum > 100) {

                                this.$set(this.spreadRateError, index, `Cannot be more than 100%`);
                                // console.log(sum);
                            } else if (sum <= 0) {
                                let minmum = (Number.parseFloat(varyrate) >= 0) ? `- ${(Math.abs(varyrate) - 0.01)}` : 0.01 + Math.abs(varyrate);
                                this.$set(this.spreadRateError, index, `Cannot be less than (${minmum})`);
                            } else {
                                console.log("Three and testing");
                                this.$set(this.spreadRateError, index, null)

                            }

                        } else if (operator === "-") {
                            var difference = varyrate - Number.parseFloat(spread_rate);
                            interest_rate = difference
                            if (difference <= 0) {
                                let minmum = (varyrate > 0) ? `- ${(Math.abs(varyrate) - 0.01)}` : 0.01 + Math.abs(varyrate);
                                this.$set(this.spreadRateError, index, `Cannot be less than ${minmum}`)
                                // console.log(difference);
                            } else {
                                this.$set(this.spreadRateError, index, null)
                            }
                        } else {
                            this.$set(this.spreadRateError, index, null)
                        }
                    }

                    // this.interest_rate = interest_rate.toFixed(2)
                    if (interest_rate)
                        this.changeOfferValue(interest_rate.toFixed(2), 'interestrate', index)
                } else {
                    console.log("Executed  " + offer.spreadvalue);
                    var sum = varyrate + Number.parseFloat(spread_rate);
                    if (Number.parseFloat(sum) > 0.01) {
                        this.changeOfferValue(sum.toFixed(2), 'interestrate', index)
                        this.$set(this.spreadRateError, index, null)
                    } else {
                        let minmum = (varyrate > 0) ? `- ${(Math.abs(varyrate) + 0.01)}` : 0.01 + Math.abs(varyrate);
                        this.$set(this.spreadRateError, index, `Cannot be less than t ${minmum}`);
                    }
                }
            },
            InterestRateChange(value, index) {
                // console.log(value)
                if (value != null && value != '') {
                    var interest_rate = Number.parseFloat(value)
                    if (interest_rate > 100) {
                        this.$set(this.interestRateError, index, "Rate is greater than 100%")
                    } else if (interest_rate <= 0) {
                        this.$set(this.interestRateError, index, "Rate should be greater than 100%")

                    } else {
                        this.changeOfferValue(interest_rate.toFixed(2), 'interestrate', index)
                        this.changeOfferValue(interest_rate.toFixed(2), 'enteredrate', index)
                        this.$set(this.interestRateError, index, null)
                    }
                } else {

                    this.$set(this.interestRateError, index, "Provide a valid rate")

                }

            },
            selectedCurrencyChange(value, index) {
                // console.log(value)
                this.changeOfferValue(value.trim(), 'selectedcurrency', index)
            },
            // third 3
            maxAmountInvalidError(value, index) {
                // console.log(newval, ',', oldval)
                if (value != null) {
                    this.$set(this.maxAmountError, index, value)
                } else {
                    this.$set(this.maxAmountError, index, null)

                }
            },
            minAmountInvalidError(value, index) {
                if (value != null) {
                    this.$set(this.minAmountError, index, value)
                } else {
                    this.$set(this.minAmountError, index, null)

                }
            },
            validateMinAmount(value, index) {

                // this.min_amount = Number.parseFloat(value.replace(/,/g, ''))
                let min_amount = null
                let max_amount = null
                if (value != null && value != '') {
                    min_amount = Number.parseFloat(value)
                    this.changeOfferValue(Number.parseFloat(value), 'minamount', index)
                }
                let offer = this.offerWhere(index)
                if (offer.max) {
                    max_amount = Number.parseFloat(offer.max)
                }

                if (min_amount == null || min_amount == '' || min_amount < 0 || min_amount > 9999999999999) {

                    this.$set(this.minAmountError, index, "Please enter a valid amount")

                } else if (max_amount && min_amount > max_amount) {
                    this.$set(this.minAmountError, index, "Minimum amount should be less than maximum amount")
                } else {
                    if (this.minAmountError[index]) {
                        this.$set(this.minAmountError, index, this.minAmountError[index])
                    } else {
                        this.$set(this.minAmountError, index, null)
                        this.$set(this.maxAmountError, index, null)
                    }
                }
            },
            validateMaxAmount(value, index) {
                let min_amount = null
                let max_amount = null
                if (value != null && value != '') {
                    max_amount = Number.parseFloat(value)
                    this.changeOfferValue(Number.parseFloat(value), 'maxamount', index)
                }
                let offer = this.offerWhere(index)
                if (offer.min) {
                    min_amount = Number.parseFloat(offer.min)
                }
                if (max_amount == null || max_amount == '' || max_amount < 0 || max_amount > 9999999999999) {
                    this.$set(this.maxAmountError, index, "Please enter a valid amount")
                } else if (min_amount && max_amount < min_amount) {
                    this.$set(this.maxAmountError, index, "Maximum amount should be greater than minimum amount")
                } else {
                    if (this.maxAmountError[index]) {
                        this.$set(this.maxAmountError, index, this.maxAmountError[index])
                    } else {
                        this.$set(this.maxAmountError, index, null)
                        this.$set(this.minAmountError, index, null)
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
            setRateTypes() {
                if (this.prime_rate) {
                    let primerate = this.prime_rate
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
            },
            ableToSubmit() {

                console.log("Able to submit")
                const isValidRate = (rate) => rate > 0 && rate <= 100;
                const isValidMinMax = (min, max) => min > 0 && min <= 9999999999999 && min <= max && max > 0 && max <= 9999999999999;

                let hasError = false;

                this.request.forEach(offer => {
                    let index = offer.offer_uniqueid
                    console.log(index, ' index')
                    if (offer.rate_type === 'fixed') {
                        if (!(offer.entered_rate && isValidRate(offer.entered_rate))) {
                            this.InterestRateChange(offer.interest_rate, index)
                        }
                    } else {
                        if (!(offer.spreadvalue && isValidRate(offer.interest_rate))) {
                            this.changeSpreadValue(offer.spreadvalue, index)

                        }
                    }

                    // Check min and max values
                    if (offer.min && offer.max) {
                        if (!isValidMinMax(offer.min, offer.max)) {
                            this.validateMinAmount(offer.min, index)
                            this.validateMaxAmount(offer.max, index)
                        }
                    } else {
                        this.validateMinAmount(offer.min, index)
                        this.validateMaxAmount(offer.max, index)
                    }

                    // Check rate validity and term length
                    if (!(offer.rate_valid_until && offer.term_length)) {
                        if (offer.rate_valid_until == null || offer.rate_valid_until == '')
                            this.$set(this.dateOfDepositError, index, "This field is required")
                        if (offer.term_length == null || offer.termLength == '')
                            this.$set(this.termLengtherror, index, "This field is required")
                        // this.datePicker(offer.rate_valid_until, index)
                        // this.termLengthChange(offer.termLength, index, true)
                    }
                    if (offer.termLengthHasAnError) {
                        this.$set(this.termLengtherror, index, "Sorry this day will be on a holiday or a weekend.");
                    }
                });
            },
            deleteRequest() {
                this.$emit('deleteRequest', this.count)
            },
            isWeekend(date) {
                var newdate = new Date(date)
                var day = newdate.getDay();
                return (day === 0 || day === 6);
            },
            getAllHolidaysInAnYear(date, fdate, type = "term", index) {

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
                            this.$set(this.termLengtherror, index, "Sorry this day will be on a holiday.")
                        else {
                            this.$set(this.termLengtherror, index, null)
                        }
                    } else {
                        let found_item = holidaysinayear.find(item => item.date == fdate.toString())
                        if (found_item)                // if (this.holidays.find(item => item.date == this.date_of_deposit))
                            this.$set(this.dateOfDepositError, index, "Sorry this day will be on a holiday.")
                        else {
                            // this.getMaturityDate()
                            this.$set(this.dateOfDepositError, index, null)
                            this.isValidDate(index)
                        }
                    }

                }).catch(err => {
                    console.log("Not a valide date")
                    this.loading = false
                })
            },
            isValidDate(index) {

                let offer = this.offerWhere(index)
                console.log("Testing error up", this.offerWhere(index));
                let date_of_deposit = offer.rate_valid_until
                let termLength = offer.term_length_type
                let termLengthValue = offer.term_length
                if ((date_of_deposit != null && this.dateOfDepositError[index] == null) &&
                    (this.termLengtherror[index] == null && termLength != null && termLengthValue != null)) {
                    // console.log(date_of_deposit, this.termLengthValue, this.termLength, false)
                    let date = addDaysOrMonthsToDate(date_of_deposit, termLengthValue, termLength, false)
                    let year_to_check = new Date(date).getFullYear()
                    let thisyear = new Date().getFullYear()
                    // console.log(thisyear, "This Year", year_to_check, "Year To check")
                    if (date) {
                        let newdate = date.split('/')
                        // const fdate = `${}-${String(newdate[1]).padStart(2, '0')}-${newdate[2]}`;
                        let fdate = `${newdate[2]}-${String(newdate[0]).padStart(2, '0')}-${String(newdate[1]).padStart(2, '0')}`
                        if (this.isWeekend(fdate)) {
                            this.changeOfferValue(true, 'termLengthError', index);
                            this.$set(this.termLengtherror, index, "The selected term will be on a weekend.")
                        } else {
                            let found_item = null
                            if (thisyear != year_to_check) {
                                this.getAllHolidaysInAnYear(date, fdate, 'term', index)
                            } else {
                                found_item = this.holidays.find(item => item.date == fdate.toString())
                            }
                            if (found_item) {
                                this.changeOfferValue(true, 'termLengthError', index);
                                this.$set(this.termLengtherror, index, "Sorry this day will be on a holiday.")
                            }
                            else {
                                this.changeOfferValue(false, 'termLengthError', index);
                                this.$set(this.termLengtherror, index, null)
                                // this.isValidDate(index)
                            }
                        }
                    }
                    // console.log(date, 'return date')
                }
                console.log("Testing error up after", this.offerWhere(index));
            },
        },
        watch: {
            getRequestSummary() {
                this.selectedCurrency = this.getRequestSummary?.currency
                // console.log(this.getRequestSummary?.currency, "Def curr")
            },
            date_of_deposit() {


            },

        }

    }


</script>
<style scoped>
    .copy-button-text {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, #5063F4);
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        /* 125% */
    }
</style>

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
