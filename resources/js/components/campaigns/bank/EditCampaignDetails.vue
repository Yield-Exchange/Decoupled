<template>
    <div>
        <OKButtonActionMessageBox @okClicked="backToCampaign" :showm="showSuccessModal" :title="successModalTitle"
            :size="successModalSize" @closedSuccessModal="backToCampaign" :showokbtn="false">
        </OKButtonActionMessageBox>
        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="closeSuccessModal()"
            :btnOneText="successbtnOneText" :btnTwoText="successbtnTwoText" :title="successModalTitle"
            @btnOneClicked="backToCampaign()" @btnTwoClicked="addProductsToCampaign()" />
        <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" />
        <b-row style="width:100%;padding: 0px !important;">
            <b-col md="12" style="width:100%;padding: 0px !important;">
                <MessageHeaderIconized title=" Campaign Details" width="100"
                    title_image="/assets/dashboard/icons/PromoEditPen.svg" />
            </b-col>
        </b-row>
        <b-row
            style="width:100%;padding: 0px !important; margin-top: 10px;margin-left:0px !important; margin-right:0px !important;">
            <b-col md="12" style="width:100%;padding: 0px !important;">
                <FormLabelRequired style="padding: 4px;" labelText="Campaign Name" required="true" showHelperText="true"
                    helperText="Campaign Name" helperId="campaignNameHelperId" />
                <b-form-input type="text" placeholder="Enter Campaign Name*"
                    :class="'font-13 input-height rounded-field'" :id="'name'" :aria-describedby="'input-live-help'"
                    style="font-weight: 400;width: 100%;border-radius: 25px;height: 44px;outline:none; box-shadow: none;"
                    :maxlength="maxNameLength" @keyup="calculatetextlength" @blur="calculatetextlength" v-model="name"
                    :value="name" />
                <span v-if="nameError!=''" style="color:red;">{{nameError}}</span>
            </b-col>
        </b-row>
        <b-row
            style="width:100%;padding: 0px !important; margin-top: 10px;margin-left:0px !important; margin-right:0px !important;">
            <b-col md="6" style="padding: 0px !important; padding-right:15px;">
                <FormLabelRequired style="padding: 4px;"
                    labelText="Start
                                                                                                                                                                                                                            Date and Time"
                    required="true" showHelperText="true" helperText="Campaign Name" helperId="campaignNameHelperId" />
                <input type="text" id="datepicker" style="width:100%; height: 44px;"
                    class="input-height rounded-field date-picker" v-model="start_date"
                    :disabled="disable_edit_start_date">
                <div class="error-message" v-if="(start_date_error != '')">
                    {{start_date_error}}
                </div>
            </b-col>
            <b-col md="6" style="padding: 0px !important;padding-left:15px;">
                <FormLabelRequired style="padding: 4px;" labelText=" Expiry Date and Time " required="true"
                    showHelperText="false" helperText="Campaign Name" helperId="campaignNameHelperId" />
                <input type="text" id="expiryDatePicker" style="width:100%; height: 44px;"
                    class="input-height rounded-field date-picker" v-model="expiry_date" autocomplete="off">
                <div class="error-message" v-if="(expiry_date_error != '')">
                    {{expiry_date_error}}
                </div>

            </b-col>
        </b-row>
        <b-row
            style="width:100%;padding: 0px !important; margin-top: 10px;margin-left:0px !important; margin-right:0px !important;">
            <b-col md="4" style="padding: 0px !important; padding-right:15px;">
                <FormLabelRequired style="padding: 4px;" labelText="Currency" required="true" showHelperText="false"
                    helperText="Campaign Name" helperId="campaignNameHelperId" />
                <b-form-select :class="'mt-1 input-height rounded-field'" :options="currencies"
                    :aria-describedby="'input-live-help'" style="font-weight: 400;height: 44px;" value-field="id"
                    text-field="description" v-model="currency_id" :value="currency_id">
                </b-form-select>

            </b-col>
            <b-col md="8" style="padding: 0px !important;padding-left:15px;">
                <FormLabelRequired style="padding: 4px;" labelText="Subscription Amount" required="true"
                    showHelperText="false" helperText="Subscription Amount" helperId="" />
                <b-form-input type="text" placeholder="Subscription Amount *"
                    :class="'font-13 input-height rounded-field'" :id="'amount'" :aria-describedby="'input-live-help'"
                    style="font-weight: 400;width:100%; border-radius: 999px; height: 44px;outline:none; box-shadow: none;"
                    v-model="formattedAmount" aria-label="Input with icon" />
                <div class="error-message" v-if="subscriptionError != '' ">
                    {{subscriptionError}}
                </div>

            </b-col>
        </b-row>
        <b-row
            style="width:100%; padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
            <b-col md="5" style="padding: 4px !important;">
                &nbsp;<br>
                <span style="color:#5063F4;"> *(Mandatory Fields)
                </span>
            </b-col>
            <b-col md="7" style="padding: 10px; padding-right: 4px; !important;">
                <div style="display: flex; align-items: center; justify-content: end;">

                    <b-button @click="submit()" class="message-action-btn-solid-">
                        <b-spinner label="Loading" v-if="submitted"
                            style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                        </b-spinner>
                        Submit
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div>
</template>
<style>
    @import "https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css";

    .message-action-btn-solid- {
        width: 30%;
        height: 40px;
        padding: 10px, 30px, 10px, 30px;
        border-radius: 20px;
        border: 2px;
        background-color: #5063F4 !important;
        color: white;
    }

    .nextMonthDay {
        color: black !important;
    }

    .date-picker {
        padding: 0 20px;
        letter-spacing: 1px;
        font-weight: normal;
    }
</style>
<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import MessageHeaderIconized from "../../shared/messageboxes/MessageHeaderIconized.vue";
    import FormLabelRequired from "../../shared/formLabels/FormLabelRequired.vue";
    import Tooltip from "../../shared/Tooltip";
    import Button from "../../shared/Buttons/Button";
    import CustomInputGroup from "../../shared/CustomInputGroup";
    import CustomSelect from "../../shared/CustomSelect";
    import CustomInput from "../../shared/CustomInput";
    import CampaignDetailsAddSuccess from "./CampaignDetailsAddSuccess";
    import $ from "jquery";
    import datetimepicker from "jquery-datetimepicker";
    import OKButtonActionMessageBox from '../../shared/messageboxes/OKButtonActionMessageBox.vue';

    export default {
        components: {
            GeneralNoInteractionError,
            TwoButtonActionMessageBox,
            FormLabelRequired,
            MessageHeaderIconized,
            CampaignDetailsAddSuccess,
            Tooltip,
            CustomInputGroup,
            CustomSelect,
            CustomInput,
            Button,
            flatPickr,
            OKButtonActionMessageBox
        },
        props: ['data', 'collected_details_data', 'choice', 'timeZone', 'unformattedusertimezone', 'formattedtimezone', 'multstepEdit'],
        data() {

            let timeZone = this.formattedtimezone;
            let currentTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });
            let currentTime = new Date(currentTimestr);
            let hours = currentTime.getHours();
            let minutes = currentTime.getMinutes();
            let formattedTime = `${hours}:${minutes}`;

            let tomorrowsTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });
            let tomorrowsTime = new Date(tomorrowsTimestr);
            tomorrowsTime.setHours(tomorrowsTime.getHours() + 24);

            let minimumExpiryDate = tomorrowsTime.toISOString().split('T')[0];

            let minimumExpiryTime = tomorrowsTime.toTimeString().split(' ')[0];


            return {
                before_edit_start_date: this.data ? this.data.start_date : null,
                before_edit_edit_date: this.data ? this.data.expiry_date : null,
                disable_edit_start_date: (this.data.status === "ACTIVE") ? true : false,
                currentDateTime: currentTime,
                currentTime: formattedTime,
                minmumExpiryDate: minimumExpiryDate,
                minmumExpiryTime: minimumExpiryTime,
                tomorrowsTime: tomorrowsTime,
                errorModalTitle: "",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalMessage: "",
                successModalTitle: "",
                successbtnOneText: "",
                successbtnTwoText: "",
                showSuccessModal: false,
                successModalSize: "md",
                name: this.data ? this.data.campaign_name : '',
                amount: (this.data) ? this.data.subscription_amount : '',
                currencies: ['USD', 'CAD'],
                currency_id: this.data ? this.data.currency : 'CAD',
                start_date: this.data ? this.data.start_date : null,
                expiry_date: this.data ? this.data.expiry_date : null,
                submitted: false,
                success_campaign_details: false,
                isInvalidNumber: false,
                status: this.data ? this.data.status : null,
                maxNameLength: 100,
                showExpiryDatePicker: false,
                wrongTextLength: false,
                products: null,
                groups: null,
                campaignId: 0,
                today: new Date(Date.now()).toLocaleString('en-US', { timeZone: this.timeZone }),
                start_date_error: "",
                expiry_date_error: "",
                subscription_amount_error: "",
                nameError: false,
                subscriptionError: false,
                currencyError: false


            }
        },
        computed: {
            formattedAmount: {
                get() {

                    if (typeof this.amount === 'string') {
                        const valueWithoutCommas = this.amount.replace(/,/g, '');
                        return valueWithoutCommas.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    } else if (typeof this.amount === 'number') {
                        this.amount = String(this.amount);
                        const valueWithoutCommas = this.amount.replace(/,/g, '');
                        return valueWithoutCommas.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }

                },
                set(newValue) {
                    const valueWithoutCommas = newValue.replace(/,/g, '');
                    if (!isNaN(valueWithoutCommas)) {
                        if (valueWithoutCommas > 999999999999) {
                            this.isInvalidNumber = true;
                        } else {
                            this.subscriptionError = '';
                            this.isInvalidNumber = false;
                            this.amount = valueWithoutCommas;
                        }
                    } else {
                        this.isInvalidNumber = true;
                        this.amount = '';
                    }

                },
            }

        },
        methods: {
            backToCampaign() {
                this.showSuccessModal = false;
                const urlParams = new URLSearchParams(window.location.search);
                let draftss = urlParams.get("isDraft");

                if (draftss === "1") {

                    this.editableSteps = urlParams.get('steps').split(",");
                    let newEditableSteps = this.editableSteps.filter(item => item !== 'campaign_details');
                    var currentUrl = window.location.href;
                    var baseUrl = currentUrl.split('?')[0];
                    var newParams = `isDraft=1&steps=${newEditableSteps}&camptype=${this.data?.campaign_depositors_invite_type}`;
                    var updatedUrl = baseUrl + '?' + newParams;
                    window.location.href = updatedUrl + '&_refresh=' + Math.random();


                }
                else {
                    window.location.href = '/campaigns/summary/' + this.campaignId;
                }
            },
            addProductsToCampaign() {
                //    this.showSuccessModal = false;
                //    this.$emit("update:campaign_details_data", {
                //         "campaign_name": this.name,
                //         "subscription_amount": this.formattedAmount.replace(/,/g, ''),
                //         "currency": this.currency_id,
                //         "start_date": this.start_date,
                //         "expiry_date": this.expiry_date,
                //     });
                //     this.$emit('update:visible', false);
                //     this.$emit('update:show_modal', false);
            },
            handleStartDateChange(selectedDates) {
                this.showExpiryDatePicker = true;
                this.$nextTick(() => {
                    setTimeout(() => {
                        const expiryDatePicker = document.getElementById('expiryDatePicker');

                    }, 0);
                });
            },
            calculatetextlength() {
                console.log(this.name.length);
                if (this.name.length >= 100) {
                    this.nameError = 'Field Must be less than 101 characters';
                } else {
                    this.nameError = '';

                }

            },
            closeAddProductModal() {
                this.submitted = false;
                this.show = false;
            },
            closeSuccessModal() {
                this.submitted = false;
                this.showSuccessModal = false;
            },
            closeErrorModal() {
                this.submitted = false;
                this.showErrorModal = false;
            },
            previousSuccess() {
                this.success_campaign_details = false;
            },
            next() {
                if (!this.submit(true)) {
                    return;
                }

                this.success_campaign_details = true;
                this.$emit('update:visible', false);
                this.$emit('update:show_modal', false);
            },

            submit(validate = false) {
                let this_ = this
                this.submitted = true;
                let haserrors = false;
                if (!this.name) {
                    this.nameError = "Field is required";
                    haserrors = true;
                }
                if (!this.formattedAmount) {
                    this.subscriptionError = "Field is required";
                    haserrors = true;
                }
                if (!this.currency_id) {
                    this.currencyError = "Field is required";
                    haserrors = true;
                }
                if (!this.start_date) {
                    this.start_date_error = "Field is required";
                    haserrors = true;
                }
                if (!this.expiry_date) {
                    this.expiry_date_error = "Field is required";
                    haserrors = true;
                }
                if (haserrors) {
                    this.submitted = false;
                    return;
                }
                if (new Date(this.start_date) >= new Date(this.expiry_date)) {
                    // console.log("Date is greater than");
                    this.showErrorModal = true;
                    this.errorModalTitle = "Campaign Details";
                    this.errorModalMessage = "Start Date should be less than Expiry Date.";
                    return false;
                }
                if (new Date(this.start_date) >= new Date(this.expiry_date)) {
                    // console.log("Date is greater than");

                    this.showErrorModal = true;
                    this.errorModalTitle = "Campaign Details";
                    this.errorModalMessage = "Start Date should be less than Expiry Date.";
                    return false;
                }
                if (this.isInvalidNumber) {
                    this.amount = "";
                    this.showErrorModal = true;
                    this.errorModalTitle = "Campaign Details";
                    this.errorModalMessage = "Please enter valid subscription amount.";
                    return false;
                }
                if (this.sanitizeAmount(this.formattedAmount) < 1) {
                    this.submitted = false;
                    this.amount = "";
                    this.showErrorModal = true;
                    this.errorModalTitle = "Campaign Details";
                    this.errorModalMessage = "Please enter valid subscription amount.";
                    return false;
                }
                if (this.validateStartExpiryDatessIntervals(this.start_date, this.expiry_date)) {

                    this.submitted = false;
                    return false;
                }
                if (validate) {
                    return true;
                }

                // this.successbtnOneText = "Previous";
                // this.successbtnTwoText = "Add Products";
                // this.successModalTitle = "Campaign Details Captured";
                this.$emit("update:campaign_details_data", {
                    "campaign_name": this.name,
                    "subscription_amount": this.formattedAmount.replace(/,/g, ''),
                    "currency": this.currency_id,
                    "start_date": this.start_date,
                    "expiry_date": this.expiry_date,
                });
                // this.$emit('update:visible', false);
                // this.$emit('update:show_modal', false);

                // do a post request to dave the data
                const urlParams = new URLSearchParams(window.location.search);
                let campaign_id = urlParams.get('campaign_id');
                this.submittedSaveDraft = true;
                axios.post('/campaigns/fi/update-campaign', {
                    'campaignName': this.name,
                    'expiryDate': this.expiry_date,
                    'startDate': this.start_date,
                    'currency': this.currency_id,
                    'subscriptionAmount': this.formattedAmount.replace(/,/g, ''),
                    'status': this.status,
                    // 'products': products_,
                    'campaign': (campaign_id != null) ? campaign_id : this.data.id,
                    // 'groups': groups_
                })
                    .then(response => {
                        this.submittedSaveDraft = false;
                        if (response?.data?.success) {
                            this.submitted = false;
                            const urlParams = new URLSearchParams(window.location.search);
                            let draftss = urlParams.get("isDraft");
                            if (draftss === "1") {
                                this.backToCampaign();
                            } else {
                                if (this.multstepEdit) {
                                    this.$emit("goToEditStep", 2);
                                } else {
                                    //this.showSuccessModal = true;
                                    this.successbtnOneText = "Close";
                                    // this.successbtnTwoText = "Add Products";
                                    this.successModalTitle = "Campaign Details Captured";
                                    this.submitted = false
                                   
                                }

                            }

                        } else {
                            throw new Error(response?.data?.message);
                        }
                    }).catch(error => {
                        this.$swal({
                            title: 'Save as draft.',
                            text: error,
                            confirmButtonText: 'Close'
                        });
                        // this.submittedSaveDraft = false;
                    });
            },
            validateStartExpiryDatessIntervals(start, expiry) {

                let start_date = new Date(start);
                let expiry_date = new Date(expiry);
                let differenceInMilliseconds = Math.abs(expiry_date - start_date);
                let differenceInHours = differenceInMilliseconds / (1000 * 60 * 60);
                if (differenceInHours <= 6) {
                    return true;
                } else {
                    return false;
                }
            }, validatePastTimeOnADate(selectedDateTime, currentDateTimestr) {
                // Formatting selectedDateTime
                let selectedYear = selectedDateTime.getFullYear();
                let selectedMonth = selectedDateTime.getMonth() + 1;
                let selectedDay = selectedDateTime.getDate();
                let selectedHours = selectedDateTime.getHours();
                let selectedMinutes = selectedDateTime.getMinutes();
                let selectedSeconds = selectedDateTime.getSeconds();
                let formattedSelectedDateTime = `${selectedYear}-${selectedMonth < 10 ? '0' + selectedMonth : selectedMonth}-${selectedDay < 10 ? '0' + selectedDay : selectedDay} ${selectedHours < 10 ? '0' + selectedHours : selectedHours}:${selectedMinutes < 10 ? '0' + selectedMinutes : selectedMinutes}:${selectedSeconds < 10 ? '0' + selectedSeconds : selectedSeconds}`;

                // Formatting currentDateTime
                let currentDateTime = new Date(currentDateTimestr);
                let currentYear = currentDateTime.getFullYear();
                let currentMonth = currentDateTime.getMonth() + 1;
                let currentDay = currentDateTime.getDate();
                let currentHours = currentDateTime.getHours();
                let currentMinutes = currentDateTime.getMinutes();
                let currentSeconds = currentDateTime.getSeconds();
                let formattedCurrentDateTime = `${currentYear}-${currentMonth < 10 ? '0' + currentMonth : currentMonth}-${currentDay < 10 ? '0' + currentDay : currentDay} ${currentHours < 10 ? '0' + currentHours : currentHours}:${currentMinutes < 10 ? '0' + currentMinutes : currentMinutes}:${currentSeconds < 10 ? '0' + currentSeconds : currentSeconds}`;

                let differenceInMilliseconds = selectedDateTime - currentDateTime;
                let differenceInMinutes = differenceInMilliseconds / (1000 * 60);


                return differenceInMinutes;
            },
            sanitizeAmount(val) {
                try {
                    // return parseFloat(val.replace(",", "", val).replace(" ", "", val));
                    return parseFloat(val.replace(/,/g, "").replace(/ /g, ""));
                } catch (e) {
                    return val;
                }
            },
        },
        watch: {
            data: {
                deep: true,
                handler(newVal) {
                    this.name = newVal.campaign_name;
                    this.amount = newVal.subscription_amount;
                    this.currency_id = newVal.currency;
                    this.status = newVal.status;

                    if (this.choice === "existing") {
                        this.name = '';
                    }

                    if ((new Date(newVal.start_date) < new Date()) && (this.choice === "existing" && this.choice != undefined)) {
                        this.start_date = null;
                        this.expiry_date = null;
                    } else {
                        this.start_date = newVal.start_date;
                        this.expiry_date = newVal.expiry_date;
                    }

                    console.log('Nested prop value changed:', newVal);
                }
            },
            start_date: function (newStartDate) {
                if (newStartDate != null) {
                    newStartDate = new Date(newStartDate);
                    newStartDate.setHours(newStartDate.getHours() + 6);
                }
                if (this.expiry_date <= newStartDate.toISOString().slice(0, 16).replace('T', ' ')) {
                    this.expiry_date = newStartDate.toISOString().slice(0, 16).replace('T', ' ');
                }
                // Update minDate of expiryDatePicker when start_date changes
                $("#expiryDatePicker").datetimepicker("setOptions", {
                    minDate: newStartDate != null ? newStartDate : this.today,
                });

                $("#datepicker").datetimepicker("setOptions", {
                    //  minTime: '00:00'
                });
            },
            expiry_date: function (newExpiryDate) {
                // Update minDate of expiryDatePicker when start_date changes
                $("#datepicker").datetimepicker("setOptions", {
                    //      maxDate: newExpiryDate != null ? newExpiryDate : false,
                });
            },
        },
        mounted: function () {
            const urlParams = new URLSearchParams(window.location.search);
            let campaign_id = urlParams.get('campaign_id');
            this.campaignId = campaign_id

            let defaultstr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });
            let defaultstartdate = new Date(defaultstr);

            $("#datepicker").datetimepicker({
                defaultDate: defaultstartdate,
                minDate: defaultstartdate,
                maxDate: this.expiry_date != null ? this.expiry_date : false,
                format: "Y-m-d H:i",
                timeZone: this.formattedtimezone,
                step: 30,
                onClose: (dp, $input) => {
                    let selectedDateTime = new Date($input.val());
                    let currentDateTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });

                    let differenceInMinutes = this.validatePastTimeOnADate(selectedDateTime, currentDateTimestr);


                    if (differenceInMinutes < -5) {

                        this.submitted = false;
                        this.showErrorModal = false;
                        this.errorModalTitle = "Campaign Details";
                        this.errorModalMessage = `Start date cannot be in the past.`;
                        this.start_date_error = `Start date cannot be in the past.`;

                    } else {
                        if (this.validateStartExpiryDatessIntervals($input.val(), this.expiry_date)) {
                            this.submitted = false;
                            this.showErrorModal = false;
                            this.errorModalTitle = "Campaign Details";
                            this.errorModalMessage = `There should be a difference of atleast 6 hrs between the start and expiry date.`;
                            this.start_date_error = `Start date should atleast be 6 hrs to expiry date.`;
                        } else {
                            this.start_date = $input.val();
                            this.expiry_date_error = (this.expiry_date_error != 'Expiry date cannot be in the past.') ? 'Expiry date cannot be in the past.' : this.expiry_date_error;
                        }

                    }
                },
                onChangeDateTime: (dp, $input) => {
                    let selectedDateTime = new Date($input.val());
                    let currentDateTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });

                    let differenceInMinutes = this.validatePastTimeOnADate(selectedDateTime, currentDateTimestr);


                    if (differenceInMinutes < -5) {

                        this.submitted = false;
                        this.showErrorModal = false;
                        this.errorModalTitle = "Campaign Details";
                        this.errorModalMessage = `Start date cannot be in the past.`;
                        this.start_date_error = `Start date cannot be in the past.`;

                    } else {
                        this.start_date_error = "";
                        if (this.validateStartExpiryDatessIntervals($input.val(), this.expiry_date)) {
                            this.submitted = false;
                            this.showErrorModal = false;
                            this.errorModalTitle = "Campaign Details";
                            this.errorModalMessage = `There should be a difference of atleast 6 hrs between the start and expiry date.`;
                            this.start_date_error = `Should atleast be 6 hrs to expiry date.`;
                        } else {
                            this.start_date_error = "";
                            this.expiry_date_error = (this.expiry_date_error != 'Expiry date cannot be in the past.') ? 'cg' : this.expiry_date_error;
                        }
                    }


                },
            });
            $("#expiryDatePicker").datetimepicker({
                minDate: defaultstartdate,
                format: "Y-m-d H:i",
                timeZone: this.formattedtimezone,
                step: 30,
                defaultDate: defaultstartdate,
                onClose: (dp, $input) => {
                    let selectedDateTime = new Date($input.val());
                    let currentDateTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });

                    let differenceInMinutes = this.validatePastTimeOnADate(selectedDateTime, currentDateTimestr);


                    if (differenceInMinutes < -5) {
                        this.submitted = false;
                        this.showErrorModal = false;
                        this.errorModalTitle = "Campaign Details";
                        this.errorModalMessage = `Expiry date cannot be in the past.`;


                    } else {
                        this.expiry_date_error = "";
                        if (this.validateStartExpiryDatessIntervals(this.start_date, $input.val())) {
                            this.submitted = false;
                            this.showErrorModal = false;
                            this.errorModalTitle = "Campaign Details";
                            this.errorModalMessage = `There should be a difference of atleast 6 hrs between the start and expiry date.`;
                            this.expiry_date_error = `Should atleast be 6hrs from start date.`;
                        } else {
                            this.expiry_date = $input.val();
                            if (new Date(this.start_date) >= new Date(this.expiry_date)) {
                                console.log(new Date(this.start_date) >= new Date(this.expiry_date), "jj");
                                this.expiry_date_error = `Expiry date cannot be less than start date.`;
                            } else {
                                this.expiry_date_error = "";
                                this.start_date_error = (this.start_date_error != 'Start date cannot be in the past.') ? '' : this.start_date_error;
                            }

                        }

                    }
                },
                onChangeDateTime: (dp, $input) => {
                    this.expiry_date = $input.val();
                    let selectedDateTime = new Date($input.val());
                    let currentDateTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });

                    let differenceInMinutes = this.validatePastTimeOnADate(selectedDateTime, currentDateTimestr);

                    console.log(differenceInMinutes, `select${selectedDateTime}`, `current${currentDateTimestr}`)
                    if (differenceInMinutes < -5) {

                        this.submitted = false;
                        this.showErrorModal = false;
                        this.errorModalTitle = "Campaign Details";
                        this.errorModalMessage = `Expiry date cannot be in the past.`;
                        this.expiry_date_error = `Expiry date cannot be in the past.`;



                    } else {

                        this.expiry_date_error = "";

                        if (this.validateStartExpiryDatessIntervals(this.start_date, $input.val())) {

                            this.submitted = false;
                            this.showErrorModal = false;
                            this.errorModalTitle = "Campaign Details";
                            this.errorModalMessage = `Should atleast be 6hrs from start date.`;
                            this.expiry_date_error = `Should atleast be 6hrs from start date.`;

                        } else {
                            this.expiry_date = $input.val();

                            if (new Date(this.start_date) >= new Date(this.expiry_date)) {
                                console.log(new Date(this.start_date) >= new Date(this.expiry_date), "jj");
                                this.expiry_date_error = `Expiry date cannot be less than start date.`;
                            } else {
                                this.expiry_date_error = "";
                                this.start_date_error = (this.start_date_error != 'Start date cannot be in the past.') ? '' : this.start_date_error;
                            }

                        }
                    }
                },
            });

        },
    }
</script>