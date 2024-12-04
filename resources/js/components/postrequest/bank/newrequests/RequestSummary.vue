<template>
    <div class="w-100">
        <!-- header -->
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/clipboard.svg" />
                        </div>
                        <div class="text-div">Request Summary</div>
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
        <!-- end header -->
        <div class="d-flex justify-content-start gap-3">
            <div class="d-none" style="width: 230px !important;">
                <div
                    style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 5px; display: inline-flex">
                    <div
                        style="font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 400;line-height: 26px; /* 162.5% */text-transform: capitalize;">
                        {{ deposit_request?.product_name }}
                    </div>
                    <div
                        style="text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                        {{ offer_data?.interest_rate_offer != null ? offer_data?.interest_rate_offer.toFixed(2) : 0.00
                        }}%
                    </div>
                    <div style="flex-direction: column; justify-content: center; align-items: center; display: flex">
                        <div
                            style="color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            Deposit ID</div>
                        <div
                            style="color: #252525; font-size: 20px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            {{ offer_data?.reference_no }}</div>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="d-flex flex-column">
                    <div class="pr-deposit-summary-investment">
                        <p class="p-0 m-0"
                            style="font-family: Montserrat !important;font-size: 16px;font-style: normal;font-weight: 500;">
                            {{ deposit_request?.product_definition }}
                        </p>
                    </div>
                    <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                        <ViewCard title="Product" :desc="deposit_request?.product_name" />
                        <ViewCard title="Deposit ID" :desc="deposit_request?.reference_no" />
                        <ViewCard title="Term Length"
                            :desc="deposit_request?.term_length_type == 'HISA' ? '-' : deposit_request?.term_length + ' ' + capitalize(deposit_request?.term_length_type)" />
                        <ViewCard title="Lockout Period"
                            :desc="deposit_request?.lockout_period_days ? deposit_request?.lockout_period_days + ' Days' : '-'" />
                        <ViewCard title="Approximate Deposit Date"
                            :desc="formatDateToCustomFormat(deposit_request?.closing_date_time, false)" />
                        <ViewCard title="Compounding Frequency"
                            :desc="capitalize(deposit_request?.compound_frequency)" />
                        <ViewCard title="Amount Awarded"
                            :desc="deposit_request?.currency + ' ' + addCommas(deposit_request?.amount)" />
                        <!-- <ViewCard title="Closing Date & Time"
                            :desc="deposit_request?.currency + ' ' + addCommas(deposit_request?.amount)" /> -->
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-100 d-flex justify-content-end my-4 gap-2">
            <div class="d-flex justify-content-end mt-3 gap-2">
                <CustomSubmit @action="goBack" :outline="true" title="Back" />
                <CustomSubmit @action="placeoffermodal = true" title="Place Offer" />
            </div>

        </div>

        <!-- header -->

        <!-- end header -->
        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">
                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <b-tab v-if="organization_data" :title="'About ' + organization_data.name" active>
                        <AboutDepositor :organization_data="organization_data"></AboutDepositor>
                    </b-tab>
                    <b-tab v-if="depositor" title="Contact Person Summary">
                        <AboutOrgAdmin :depositor_data="depositor"></AboutOrgAdmin>
                    </b-tab>
                </b-tabs>
            </div>
        </div>


        <PlaceOferModal v-if="deposit_request" :show="placeoffermodal" @close="placeoffermodal = false"
            classess="d-flex justify-content-center modal-cont" @primaryClicked="withDrawDeposit"
            @outlinedClicked="placeoffermodal = false" icon="signup/danger.svg" outlinedbuttontext="Cancel"
            primarybuttontext="Submit">
            <div class="" style="width: 850px;">
                <div class="place-offer-header mb-20">Place Your offer</div>
                <div>
                    <div class="row ">
                        <div class="mb-20" :class="{ 'col-md-4': isHisa, 'col-md-6': !isHisa }">

                            <FormLabelRequired labelText="Minimum Amount" :required="true" :showHelperText="false"
                                helperText="Enter minimum amount" helperId="PDSHId" />

                            <CustomInput inputType="number" :dontShowErrorModal="true"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="minimum" name="Enter minimum amount" :has-validation="true"
                                @inputChanged="minAmountChange($event)" input-type="number"
                                :defaultValue="addCommas(min_amount)" :hasSpecificError="minAmountError" />
                            <div v-if="minAmountError" class="error-message">
                                {{ minAmountError }}
                            </div>
                        </div>
                        <div class="mb-20" :class="{ 'col-md-4': isHisa, 'col-md-6': !isHisa }">

                            <FormLabelRequired labelText="Maximum Amount" :required="true" :showHelperText="false"
                                helperText="Enter maximum amount" helperId="PDSHId" />

                            <CustomInput inputType="number" :dontShowErrorModal="true"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="maximum" name="Enter maximum amount" :has-validation="true"
                                @inputChanged="maxAmountChange($event)" input-type="number"
                                :defaultValue="addCommas(max_amount)" :hasSpecificError="maxAmountError" />
                            <div v-if="maxAmountError" class="error-message">
                                {{ maxAmountError }}
                            </div>
                        </div>
                        <div class="mb-20" :class="{ 'col-md-4': isHisa, 'col-md-6': !isHisa }">

                            <FormLabelRequired labelText="Rate Held Until" style="margin-bottom: 4px !important;"
                                :required="true" :showHelperText="false" helperText="Rate Held Until"
                                helperId="PDSHId" />

                            <JQueryCustomDatePicker :hasError="rateHeldError" placeholder="Select Date "
                                :start_date="addWeekdays(today, 3)" v-model="rate_held_until"
                                :formattedtimezone="formattedtimezone" />
                            <div v-if="rateHeldError" class="error-message">
                                {{ rateHeldError }}
                            </div>

                        </div>
                        <!-- fixed rate or variable -->
                        <div class="col-md-6 mb-20" v-if="deposit_request?.term_length_type == 'HISA'">

                            <FormLabelRequired labelText="Rate type" :required="true" :showHelperText="true"
                                helperText="Rate type" helperId="ratetype" />

                            <b-select v-model="selectedRateType" @change="setRateType"
                                style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font: size 16px !important;">
                                <option v-for="item in rate_types" :key="item.id" :value="item">{{ item.name
                                    }}
                                </option>
                            </b-select>
                            <!-- <CustomSelect v-if="ratetypeOptions"
                                :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                :data="ratetypeOptions" id="product_type_id" name="Product Type*"
                                :has-validation="false" default-value="Fixed" @selectChanged="changeRateType($event)" /> -->
                        </div>

                        <!-- <div class="col-md-6 mb-20 radio-label d-flex justify-content-center align-items-center gap-3"
                            v-if="deposit_request?.term_length_type == 'HISA'">
                            <div>

                                Fixed &nbsp <input v-model="rate_type" value="fixed" class="my-radio" type="radio"
                                    name="organizationinformation">

                            </div>
                            <div>
                                Variable &nbsp <input v-model="rate_type" value="variable" class="my-radio" type="radio"
                                    name="organizationinformation">
                            </div>

                        </div> -->
                        <div class="col-md-6 mb-20"
                            v-if="deposit_request?.term_length_type == 'HISA' && rate_type != 'fixed'">
                            <FormLabelRequired labelText="Prime Rate (%) " :required="true" :showHelperText="false"
                                helperText="Interest Rate Offer" helperId="PDSHId" />
                            <div class="combined-input" style="margin-top: 4px;">
                                <b-form-select class="" id="termlengthid" v-model="rate_operator" ref="termLengthSelect"
                                    @change="" :options="['+', '-']"
                                    style="border: none;width:25%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                </b-form-select>
                                <b-form-input
                                    style="border: none; ;width:75%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                    type="number" step='0.01' min="0" v-model.lazy="fixed_rate" @blur="valueToFixed2"
                                    :class="{ 'validation-error': false }" placeholder="eg. 3" />
                            </div>
                            <div v-if="interestRateError" class="error-message">
                                {{ interestRateError }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-20" v-else>
                            <FormLabelRequired labelText="Interest Rate (%)" :required="true" :showHelperText="false"
                                helperText="Interest Rate Offer" helperId="PDSHId" />
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="rate" name="Rate*" :has-validation="true" @inputChanged="InterestRateChange($event)"
                                input-type="number" :defaultValue="interest_rate"
                                :hasSpecificError="interestRateError" />
                            <div v-if="interestRateError" class="error-message">
                                {{ interestRateError }}
                            </div>
                        </div>
                        <div class="col-md-12 mb-20">
                            <FormLabelRequired labelText="Special Instructions " :required="false"
                                :showHelperText="false" helperText="Special Instructions" helperId="PDSHId" />
                            <CustomInput inputType="text"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="instructions" name="Enter Special Instructions" :has-validation="true"
                                :maxlength="100" @inputChanged="specialInstructions" input-type="text"
                                :defaultValue="special_instructions" hasSpecificError="" />
                            <div v-if="specialInstructionsError" class="error-message">

                            </div>
                        </div>

                        <!-- <div class="col-md-12 mb-20"
                            :class="{ 'col-md-12': deposit_request?.term_length_type == 'HISA' }">
                            <FormLabelRequired labelText="PDS" :required="false" showHelperText="true"
                                helperText="Product Disclosure Statement" helperId="PDSHId" />
                            <FileUpload :file_selected.sync="pds_file" />

                            <span v-if="false" style="color:red;">Invalid.</span>
                        </div> -->

                        <div class="col-md-12 mb-20">
                            <FormLabelRequired labelText="PDS" :required="false" showHelperText="true"
                                helperText="Product Disclosure Statement" helperId="PDSHId" />
                            <CustomPDsUpload @url="urlValue" :default="httpvalue" :file_selected.sync="pds_file" />

                            <!-- <div v-if="interestRateError" class="error-message">
                                {{ interestRateError }}
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class=" w-100 d-flex justify-content-end my-4 gap-2">
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <CustomSubmit @action="placeoffermodal = false" :outline="true" title="Cancel" />
                        <CustomSubmit @action="doPlaceOffer" title="Place Offer" />
                    </div>

                </div>
            </div>
        </PlaceOferModal>

        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Your offer has been submitted." btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw ">The depositor has been notified.</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Offer not submitted!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="confirmsubmit = false" @btnTwoClicked="placeOffer"
            @btnOneClicked="closeIfNoSelected" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Sure to submit this offer?" :showm="confirmsubmit">
            <div class="ml-5 description-text-withdraw ">Your offer will be sent to the depositor</div>
        </ActionMessage>

    </div>

</template>

<script>
    import TimerClock from '../../../campaigns/TimerClock.vue';
    import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
    import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
    import CustomSelectInput from '../../../auth/signup/shared/CustomSelectInput.vue'
    import CustomTextInput from '../../../auth/signup/shared/CustomTextInput.vue'
    import CustomDateInput from '../../../auth/signup/shared/CustomDateInput.vue'

    import ViewCard from '../pendingdeposits/actions/ViewCard.vue'
    import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";

    import AboutDepositor from '../pendingdeposits/AboutDepositor.vue'
    import AboutOrgAdmin from '../../depositor/pendingdeposits/AboutOrgAdmin.vue';

    import PlaceOferModal from '../../../auth/signup/shared/PopUpModal.vue'
    import CustomInput from '../../../shared/CustomInput.vue';
    import CustomSelect from '../../../shared/CustomSelect.vue';
    // import CustomDateInput
    import FileUpload from "../../../shared/FileUpload";
    import CustomPDsUpload from "../../../shared/CustomPDsUpload";
    import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';









    export default {
        components: { CustomSelect, FileUpload, CustomPDsUpload, JQueryCustomDatePicker, FormLabelRequired, PlaceOferModal, CustomDateInput, TimerClock, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput, ViewCard, AboutDepositor, AboutOrgAdmin },
        props: ['offer', 'depositRequest', 'formattedtimezone', 'prime_rate'],
        mounted() {
            if (this.depositRequest) {
                this.deposit_request = JSON.parse(this.depositRequest)
                this.organization_data = this.deposit_request?.inviter
                this.depositor = JSON.stringify(this.deposit_request?.invitinguser)
                if (this.deposit_request?.term_length_type)
                    this.isHisa = this.deposit_request?.term_length_type == "HISA" ? true : false
                this.requestedAmount = this.deposit_request?.amount
            }
            // console.log(this.depositRequest, 'deposit request')
            if (this.prime_rate) {
                let primerate = JSON.parse(this.prime_rate)
                // console.log(primerate, "primerateprimerateprimerate");
                this.prime_rate_formated = primerate
                let ratess = [];
                primerate.forEach(rate => {
                    if (rate.rate_label === 'Fixed') {
                        let fixed = {
                            id: rate.rate_label,
                            rate: rate.value,
                            label: rate.rate_label,
                            name: `${rate.rate_label}`
                        };
                        ratess.push(fixed);
                        this.selectedRateType = fixed;
                    } else {
                        ratess.push({
                            id: rate.rate_label,
                            rate: rate.value,
                            label: rate.rate_label,
                            name: `${rate.rate_label} (${rate.value}%)`
                        });
                    }

                })
                this.rate_types = ratess;
            }
            if (this.offer)
                this.offer_data = JSON.parse(this.offer)

            this.request_id = this.getUrlPArams()

            const urlParams = new URLSearchParams(window.location.search);
            let fromPage = urlParams.get('fromPage');
            this.fromPage = fromPage
            this.getProductTypes()

        },

        data() {
            return {
                viewMore1: false,
                organization_data: null,
                offer_data: null,
                request_id: null,
                success: false,
                fail: false,
                requestedAmount: null,
                depositor: null,
                inactivesuccess: false,
                inactivefail: false,
                deposit_request: null,
                fromPage: null,
                markinactive: false,
                options: [],
                reason: null,
                products: null,
                reasons: null,
                isHisa: false,
                ratetypeOptions: [],
                httpvalue: 'https://',
                url: null,

                confirmsubmit: false,

                placeoffermodal: false,
                // form inputs
                special_instructions: null,
                pds_file: null,
                min_amount: null,
                max_amount: null,
                max_amount: null,
                rate_held_until: null,
                interest_rate: null,
                instructions: null,
                requiredChecker: false,
                today: new Date(),
                prime_rate_formated: null,
                // errors
                interestRateError: null,
                minAmountError: null,
                maxAmountError: null,
                rateHeldError: null,
                rate_operator: "+",
                specialInstructionsError: null,
                fixed_rate: null,
                rate_type: "fixed",
                final_rate_type: ['fixed', 0],
                rate_types: [],
                selectedRateType: '',

            }
        },
        computed: {

            awarded_amount() {
                return this.addCommas(this.offer_data?.amount)
            },
        },
        methods: {
            specialInstructions(value) {
                if (value == '') {
                    this.special_instructions = null
                    this.specialInstructionsError = null
                } else {
                    if (value.length > 100) {
                        this.special_instructions = value
                        this.specialInstructionsError = `The field character should be between 1-100 (${value.length}) given.`
                    }
                }
            },
            setRateType(value) {
                // console.log(this.selectedRateType);
                this.prime_rate = this.selectedRateType.rate
                this.rate_type = this.selectedRateType.label.toLowerCase()
            },
            urlValue(value) {
                // console.log(value, "Value ")
                this.url = value
            },
            closeIfNoSelected() {
                this.confirmsubmit = false
                window.location.href = "/new-requests"

            },
            changeRateType(value) {

                if (value != 'Fixed') {
                    // this.rate_type = 'variable'
                    this.rate_type = value.replace(/\(\d+\)%/, "").trim()
                    // console.log(this.rate_type)
                    let foundElement = this.prime_rate_formated.find(element => element.rate_label === this.rate_type);
                    if (foundElement) {
                        this.final_rate_type = [foundElement.key, foundElement.value];
                    }

                } else {
                    this.rate_type = value.toLowerCase()
                    this.final_rate_type = ['fixed', 0]
                }
                // console.log("Rate type", value, this.final_rate_type)
            },
            doPlaceOffer() {
                let has_error = false
                if (this.min_amount == null || this.minAmountError != null) {
                    this.minAmountError = this.minAmountError ? this.minAmountError : "This field is required";
                    has_error = true
                }
                if (this.max_amount === null || this.maxAmountError != null) {
                    this.maxAmountError = this.maxAmountError ? this.maxAmountError : "This field is required";
                    has_error = true
                }
                if (this.rate_held_until === null) {
                    this.rateHeldError = "This field is required";
                    has_error = true
                }
                if (this.deposit_request?.term_length_type === 'HISA') {
                    if (this.fixed_rate === null && this.rate_type !== 'fixed') {
                        this.interestRateError = "This field is required";
                        has_error = true
                    } else if (this.interest_rate === null && this.rate_type === 'fixed') {
                        this.interestRateError = "This field is required";
                        has_error = true
                    } else if (this.interestRateError) {
                        this.interestRateError = this.interestRateError;
                        has_error = true
                    }
                } else if (this.deposit_request?.term_length_type !== 'HISA' && this.interest_rate === null) {
                    this.interestRateError = this.interestRateError ? this.interestRateError : "This field is required";
                    has_error = true
                }
                if (this.special_instructions != null) {
                    if (this.special_instructions != '' && this.special_instructions.length > 100) {
                        has_error = true
                        this.specialInstructionsError = `The field character should be between 1-100 (${this.special_instructions.length}) given.`
                    }
                }
                if (!has_error) {
                    this.placeoffermodal = false;
                    this.confirmsubmit = true;
                }
            },

            valueToFixed2() {
                this.fixed_rate = Number.parseFloat(this.fixed_rate).toFixed(2);
                var varyrate = Number.parseFloat(this.final_rate_type[1]);

                if (this.fixed_rate > 100) {
                    this.interestRateError = "Rate is greater than 100%";
                } else if (this.rate_operator === "+") {
                    var sum = varyrate + Number.parseFloat(this.fixed_rate);
                    if (sum > 100) {
                        this.interestRateError = "Variable rate + prime rate is greater than 100%";
                        // console.log(sum);
                    } else {
                        this.interestRateError = null;
                    }
                } else if (this.rate_operator === "-") {
                    var difference = varyrate - Number.parseFloat(this.fixed_rate);
                    if (difference < 0.01) {
                        this.interestRateError = "Variable rate - prime rate is less than 0.01%";
                        // console.log(difference);
                    } else {

                        this.interestRateError = null;
                    }
                } else {
                    this.interestRateError = null;
                }

            },
            addWeekdays(startDate, daysToAdd) {
                var currentDate = new Date(startDate);
                var addedDays = 0;

                while (addedDays < daysToAdd) {
                    currentDate.setDate(currentDate.getDate() + 1);

                    if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
                        addedDays++;
                    }
                }

                return currentDate;
            },
            placeOffer() {

                let data = new FormData()
                data.append('request_id', this.request_id)
                data.append('min_amount', this.min_amount)
                data.append('max_amount', this.max_amount)
                data.append('expdate', this.rate_held_until)
                if (this.pds_file) {
                    data.append('file', this.pds_file)
                }
                if (this.special_instructions) {
                    data.append('special_ins', this.special_instructions)
                }
                data.append('nir', this.interest_rate)
                // to validate later
                // data.append('rate_type', this.final_rate_type[0])
                //  data.append('rate_operator', this.rate_operator)
                if (this.deposit_request?.term_length_type === 'HISA') {
                    data.append("rate_type", `${((this.rate_type).toLowerCase()).replace(/\s+/g, "_")}`);
                    data.append('rate_operator', this.rate_operator)
                } else {
                    data.append("rate_type", `fixed`);
                }
                data.append('term_length_type', this.deposit_request.term_length_type)
                data.append('fixed_rate', this.fixed_rate)
                //  data.append('prime_rate', this.final_rate_type[1])
                data.append('url', this.url)

                // if (this.deposit_request.term_length_type != 'HISA') {

                // }
                axios.post('/submit/place-offer/', data).then(response => {
                    this.confirmsubmit = false
                    if (response.data.success) {
                        this.success = true
                        setTimeout(() => {
                            this.success = false
                            window.location.href = "/in-progress"
                        }, 3000)
                    } else {
                        this.fail = true
                        this.confirmsubmit = false
                    }
                }).catch(err => {
                    this.confirmsubmit = false
                    this.fail = true
                })
            },
            minAmountChange(value) {
                this.min_amount = this.removeCommas(value)
                if (this.min_amount > this.requestedAmount)
                    this.minAmountError = "Amount cannot be greater than requested amount"
                else if (this.max_amount != null && this.min_amount > this.max_amount)
                    this.minAmountError = "Amount cannot be greater than Maximum amount"
                else
                    this.minAmountError = null
            },
            maxAmountChange(value) {
                this.max_amount = this.removeCommas(value)
                if (this.max_amount > this.requestedAmount)
                    this.maxAmountError = "Amount cannot be greater than requested amount"
                else if (this.min_amount != null && this.max_amount < this.min_amount)
                    this.maxAmountError = "Amount Cannot Be less than minimum amount"
                else
                    this.maxAmountError = null
            },
            InterestRateChange(value) {
                this.interest_rate = value
                if (this.interest_rate > 100)
                    this.interestRateError = "Rate is greater than 100%"
                else
                    this.interestRateError = null
            },
            fixedRateChange(value) {
                this.fixed_rate = value
                if (this.fixed_rate > 100)
                    this.interestRateError = "Rate is greater than 100%"
                else
                    this.interestRateError = null
            },
            removeCommas(newValue) {
                return newValue ? parseFloat(newValue.toString().replace(/,/g, '')) : 0;
            },
            getUrlPArams() {
                const url = window.location.pathname; // Get the current URL path
                const parts = url.split('/'); // Split the URL by '/'

                // The last part of the URL should be the number part
                const numberPart = parts[parts.length - 1];
                return numberPart
            },
            productFromProductName(value) {
                let description;
                this.products.forEach(element => {
                    if (element.description === value) {
                        description = element.definition
                    }
                })
                this.reasons = description
                return description
            },
            async getProductTypes() {
                await axios.get('/get-all-products').then(response => {
                    this.products = response.data
                }).catch(err => {
                    // console.log(err)
                })
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
            formatDateToCustomFormat(inputDate, hastime = false) {
                const date = new Date(inputDate);

                const dateOptions = { month: 'short', day: '2-digit', year: 'numeric' };
                let formattedDate = date.toLocaleDateString('en-US', dateOptions);

                if (hastime) {
                    const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
                    const formattedTime = date.toLocaleTimeString('en-US', timeOptions);

                    formattedDate += ': ' + formattedTime;
                }

                return formattedDate;
            },
            withDrawDeposit() {
                this.withdrawpromt = false
                axios.post('/withdraw-deposit', { 'deposit_id': this.request_id }).then(response => {
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
                window.location.href = "/new-requests"
                // if (this.fromPage == "bank-pending-deposits")
                // else if (this.fromPage == "bank-active-deposits")
                //     window.location.href = "/bank-active-deposits"
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
            rate_held_until() {
                this.rateHeldError = null
            }
        }
    }

</script>
<style>
    .t-clock p,
    .radio-label {
        font-size: 16px !important;
        font-family: Montserrat;
        font-weight: 500;
        word-wrap: break-word
    }

    .modal-cont .modal-content {
        width: 900px !important;
    }
</style>
<style scoped>
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

    .place-offer-header {
        color: var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4);

        /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
        font-family: Montserrat !important;
        font-size: 28px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;
        /* 114.286% */
        text-transform: capitalize;
    }

    .mb-20 {
        margin-bottom: 20px !important;
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