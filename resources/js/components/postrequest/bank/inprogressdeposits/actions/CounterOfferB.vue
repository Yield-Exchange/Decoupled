<template>
    <div>
        <Modal :show="show" @isVisible="$emit('visible', $event)" modalsize="xl">
            <div>
                <div class="counter-offer-container">
                    <div class="original_offer">
                        <div class="header_section">Current</div>
                        <div class="original_info">
                            <div class="sub_header">
                                {{ hasNoticePeriod ?
                                offer?.invited?.deposit_request?.lockout_period_days + ' Day ' +
                                offer?.invited?.deposit_request?.product_name
                                : offer?.invited?.deposit_request?.product_name
                                }}
                            </div>
                            <div class="original_rate">{{ offer?.interest_rate_offer?.toFixed(2) }}%</div>
                            <div class="original_bank">{{ offer?.invited?.deposit_request?.organization?.name }}</div>
                            <div class="original_deposit">Interest Earned: CAD
                                {{addCommas(initialIntereofstRateEarned)}} </div>
                            <div class="original_dates">
                                <div class="sub_dates"><span style="color: #5063F4;">Date of Deposit</span> {{
                                    this.formatDateToCustomFormat(offer?.invited?.deposit_request?.date_of_deposit) }}
                                </div>
                                <div class="sub_dates"><span style="color: #5063F4;">Rate Held Until </span>
                                    <TimerClock variant="success"
                                        :timezone="offer?.invited?.deposit_request?.user?.formatted_timezone"
                                        :target-time="offer?.rate_held_until" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="counter_offer">
                        <div class="header_section">Counter</div>
                        <div style="margin-top: 20px;">
                            <b-row>
                                <FormLabelRequired style="padding: 4px;" labelText="Deposit Amount" required="true"
                                    :showHelperText="false" helperText="Deposit Amount" helperId="depositAmount" />
                                <CustomCurrencyValueInput :selectedCurrency="selectedCurrency"
                                    :currencyOptions="currencyOptions" inputType="number" :defaultValue="depositAmount"
                                    v-model="depositAmount" @inputChanged="depositAmount = $event" />
                            </b-row>

                            <b-row v-if="deposit_request?.term_length_type != 'HISA'">
                                <div style="display: flex; flex-direction:row;">
                                    <div
                                        style="display: flex; width:50%; flex-direction:column;justify-content:flex-start; margin-top: 15px;">
                                        <FormLabelRequired labelText="Interest Rate(%)" required="true"
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

                                    </div>
                                    <div
                                        style="display: flex; padding-top:13px; width:50%;flex-direction:column;justify-content:flex-start; gap:3px;">

                                        <FormLabelRequired
                                            :labelText="(selectedRateType.name !== 'Fixed') ? 'Spread(%)' : 'Interest Rate(%)'"
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
                                        <FormLabelRequired labelText="Interest Rate(%)" required="true"
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
                                        <FormLabelRequired style="padding: 4px;" labelText="Interest Rate Changed by(%)"
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
                        </div>
                    </div>

                </div>
                <b-row>
                    <FormLabelRequired style="padding: 4px;" labelText="Special Instructions" required="false"
                        :showHelperText="false" helperText="Special Instructions" helperId="specialInstructions" />
                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                        id="specialInstructions" name="Special Instructions" :has-validation="true"
                        :default-value="specialInstructions" v-model="specialInstructions" input-type="textareanew" />
                </b-row>

                <div style="width: 100%;padding:25px;">
                    <table class="table" style="width: 100%;">
                        <thead class="customHeader">
                            <tr>
                                <th>#</th>
                                <th>Counter Offer Date</th>
                                <th>Deposit Amount</th>
                                <th>Counter Rate</th>
                                <th>Interest Rate Change</th>
                                <th>Counter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, index) in counterOffers" :key="index" v-if="index < 2">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="textContainer">
                                        {{ formatDateToCustomFormat(value.created_at) }}</div>
                                </td>
                                <td>
                                    <div class="textContainer">CAD {{ addCommas(value.maximum_amount) }}</div>
                                </td>
                                <td>
                                    <div class="textContainer"> {{ value.offered_interest_rate.toFixed(2) }} %</div>
                                </td>
                                <td>
                                    <div class="textContainer"> {{ (value.offered_interest_rate -
                                        offer?.interest_rate_offer).toFixed(2) }}
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
                        <Button @click="submit" :loading="loading" type="primary" text="Submit"
                            xclass="button-action" />

                    </div>
                </b-row>

            </div>
        </Modal>
        <GeneralErrorNoInteraction :title="errorTitle" :message="errorMsg" :show="showErrorMsg" size="md"
            @closedModal="showErrorMsg=false" />
    </div>
</template>


<script>
    import GeneralErrorNoInteraction from "../../../../shared/messageboxes/GeneralNoInteractionError.vue";
    import Modal from "../../../../shared/Modal";
    import CustomInput from "../../../../shared/CustomInput.vue";
    import CustomDateInput from '../../../../../components/auth/signup/shared/CustomDateInput.vue';
    import FormLabelRequired from "../../../../shared/formLabels/FormLabelRequired.vue";
    import CustomCurrencyValueInput from "../../../../shared/CustomCurencyAmount.vue";
    import CustomDate from "../../../../shared/CustomDate.vue";
    import Button from "../../../../shared/Button.vue";
    import CustomInvitedStatusBadge from "../../../../campaigns/depositor/single-offer/CustomInvitedStatusBadge.vue";

    import CustomSystemDate from "../../../../post-requests/sharedComponents/CustomSystemDate.vue";

    import CustomRateValueInput from "../../../../post-requests/sharedComponents/CustomCurrencyValueRate"
    import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, formatCreatedAtToRequiredTimestamp, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../../../../utils/commonUtils";
    // import TimerClock from "../../../../post-requests/sharedComponents/TimerClock";
    import TimerClock from "../../../../post-requests/sharedComponents/TimerClock.vue";

    export default {
        components: {
            Modal,
            FormLabelRequired,
            CustomInput,
            CustomDateInput,
            CustomCurrencyValueInput,
            //selectedCurrency: 'CAD',
            CustomDate,
            Button,
            CustomInvitedStatusBadge,
            CustomSystemDate,
            CustomRateValueInput,
            TimerClock,
            GeneralErrorNoInteraction
        },
        props: ['show', 'datum', 'timezone'],
        created() {
            if (this.datum) {
                this.offer = JSON.parse(this.datum);
            }
        },
        data() {
            return {

                depositAmount: 0,
                interestRate: 0,
                specialInstructions: '',
                rate_held_until: '',
                selectedCurrency: "CAD",
                depositAmountError: "",
                interestRateError: "",
                offer: null,
                loading: false,
                rateHeldUntilError: "",
                counteredRateWith: 0,
                initialIntereofstRateEarned: 0,
                rate_error: "",
                prime_rate: '',
                selectedCounter: {
                    id: 1,
                    label: 'plus',
                    oparatorSymbol: '+',
                },
                selectedRateType: '',
                rate_type: 'Fixed',
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
                plus_minus_rate: 0,
                deposit_amount_error: "",
                rateChange: 0,
                addtionalInterestAdded: 0,
                errorTitle: "",
                errorMsg: "",
                showErrorMsg: false
            }
        },
        mounted() {
            if (this.datum) {
                this.offer = JSON.parse(this.datum);
            }
            this.getInterestRatesTypes();

            if (this.counterOffers.length > 0) {
                this.depositAmount = this.counterOffers[0].maximum_amount;
            } else {
                this.depositAmount = this.offer?.invited?.deposit_request?.amount;
            }
            this.interestRate = this.offer?.interest_rate_offer;

            this.initialIntereofstRateEarned = calculateIterestOnProduct(
                this.depositAmount,
                this.deposit_request.term_length,
                this.deposit_request.term_length_type,
                this.deposit_request.product_name,
                this.offer?.interest_rate_offer
            );


        },
        methods: {
            setValueFromField(value, field) {
                if (field != 'rate_held_until') {
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
                    case "rate_held_until":
                        this.rateHeldUntilError = '';
                        this.rate_held_until = value;
                        break;
                    case "selectedCounter":
                        this.selectedCounter = value;

                        break;
                    case "plus_minus_rate":
                        this.plus_minus_rate = value;
                        break;
                    case "depositAmount":
                        if (value > this.offer?.max) {
                            this.deposit_amount_error = `Cannot be greater than CAD. ${this.addCommas(this.offer.max)}`;
                            return;
                        } else if (value < this.offer.min) {
                            this.deposit_amount_error = `Cannot be less than CAD. ${this.addCommas(this.offer.min)}`;
                            return;
                        } else {
                            this.deposit_amount_error = '';
                        }
                        this.depositAmount = value;
                        break;
                }
            },

            getInterestRatesTypes(url = "") {
                url = url ? url : `/system_rates`;
                axios.get(url)
                    .then(response => {
                        let ratess = [];
                        response.data.map((val, key) => {
                            if (val.rate_label === 'Fixed') {
                                let fixed = {
                                    id: val.rate_value,
                                    rate: val.rate_value,
                                    label: val.rate_label,
                                    name: `${val.rate_label}`,
                                    key: val.key
                                };
                                ratess.push(fixed);
                                this.selectedRateType = fixed;
                            } else {
                                ratess.push({
                                    id: val.rate_value,
                                    rate: val.rate_value,
                                    label: val.rate_label,
                                    name: `${val.rate_label} (${val.rate_value}%)`,
                                    key: val.key
                                });
                            }


                        });
                        this.rate_types = ratess;
                    }).catch(error => {
                        this.is_loading = false;
                        console.log("error > " + error);
                    });
            },
            setRateType(value) {
                this.prime_rate = this.selectedRateType.rate
                this.rate_type = this.selectedRateType.key.toLowerCase()
            },
            setRateHeld(date) {
                this.rate_held_until = date;
            },
            InterestRateChange(value) {
                this.interestRate = value
                if (this.interestRate > 100)
                    this.interestRateError = "Rate is greater than 100%"
                else
                    this.interestRateError = null
            },
            selectColor(value) {
                if (value === 'ACCEPTED') {
                    return '#44E0AA';
                } else if (value === 'COUNTERED') {
                    return '#5063F4';
                }
                else {
                    return '';
                }
            },
            sanitizeAmount(val) {
                try {
                    // return parseFloat(val.replace(",", "", val).replace(" ", "", val));
                    return parseFloat(val.replace(/,/g, "").replace(/ /g, ""));
                } catch (e) {
                    return val;
                }
            },
            formatDateToCustomFormat(inputDate) {
                // Create a Date object from the inputDate parameter
                const options = { month: 'short', day: '2-digit', year: 'numeric' };
                const date = new Date(inputDate);
                const formattedDate = date.toLocaleDateString('en-US', options);

                return formattedDate;
            },
            calaculateInterestRate(term_length_type, amount_offered, term_length, rate) {
                let cal_interest = 0;
                switch (term_length_type) {
                    case "DAYS":
                        cal_interest = Math.round(
                            (((amount_offered * rate) / 100) * term_length) / 365
                        );
                        break;
                    case "MONTHS":
                        cal_interest = Math.round(
                            (((amount_offered * rate) / 100) * term_length) / 12
                        );
                    case "days":
                        cal_interest = Math.round(
                            (((amount_offered * rate) / 100) * term_length) / 365
                        );
                        break;
                    case "months":
                        cal_interest = Math.round(
                            (((amount_offered * rate) / 100) * term_length) / 12
                        );
                        break;
                }
                return cal_interest;


            },
            submit() {
                this.counteroffersub = true;
                this.loading = true;
                if ((sanitizeAmount(this.depositAmount) === sanitizeAmount(this.deposit_request.amount)) && (this.counteredRateWith === 0 || this.counteredRateWith === this.intialRate)) {
                    this.errorTitle = "Counter Offer Error";
                    this.errorMsg = "No changes .";
                    this.showErrorMsg = true;
                    this.rateHeldUntilError = '';
                    this.counteroffersub = false;
                } else {
                    if (this.rate_held_until === '') {
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

                        this.rateHeldUntilError = '';
                        let formData = new FormData();
                        formData.append("request_id", this.offer?.invited?.deposit_request?.request_encypted_id);
                        formData.append("offer_id", this.offer?.offer_encrypted_id);
                        formData.append("min_amount", this.depositAmount);
                        formData.append("max_amount", this.depositAmount);
                        formData.append("rate_type", 'prime_rate');

                        if (this.deposit_request?.term_length_type === 'HISA') {
                            if (this.rate_type === 'fixed' || this.rate_type === 'Fixed') {
                                formData.append("nir", this.counteredRateWith);
                            } else {
                                formData.append("nir", this.interestRate);
                            }
                            formData.append('fixed_rate', this.plus_minus_rate);
                            formData.append('prime_rate', this.prime_rate)
                            formData.append("rate_type", `${(this.selectedRateType.key).toLowerCase()}`);
                            formData.append("rate_operator", `${this.selectedCounter.oparatorSymbol}`);

                        } else {
                            formData.append("nir", this.counteredRateWith);
                        }

                        formData.append("counter_offer_expiry", `${this.rate_held_until}`);
                        formData.append("rate_held_until", `${this.rate_held_until}`);
                        formData.append("expdate", `${this.rate_held_until}`);

                        // for (const [key, value] of formData.entries()) {
                        //     console.log(`${key}: ${value}`);
                        // }

                        let url = `/counter-offer`;
                        axios.post(url, formData, {
                        }).then(response => {
                            this.loading = false;
                            if (response?.data?.success) {
                                this.getTheOfferCounters();
                                this.successTitle = response?.data.message_title;
                                this.successMsg = response?.data?.message;
                                this.showSuccessMsg = true;
                                this.counteroffersub = false;
                            } else {
                                this.errorTitle = response?.data?.message_title;
                                this.errorMsg = response?.data?.message;
                                this.showErrorMsg = true;
                                this.counteroffersub = false;
                            }
                            setTimeout(() => {
                                window.location.reload()
                            }, 2000)

                        }).catch(error => {
                            this.counteroffersub = false;
                            this.loading = false;
                            if (error.response) {
                                this.errorTitle = "Error posting your counter offer";
                                this.errorMsg = error?.response?.data.message;
                                this.showErrorMsg = true;
                            } else if (error.request) {
                                console.error("Request made but no response received:", error.request);
                            } else {
                                console.error("Error during request setup:", error.message);
                            }
                        });
                        setTimeout(() => {
                            window.location.reload()
                        }, 2000)
                    }


                }

            },
            addCommas(vaue) {
                return vaue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }
            },
        },
        computed: {
            hasNoticePeriod() {
                // Using the includes method
                return (this.offer?.invited?.deposit_request?.product_name?.includes("Notice deposit") || this.offer?.invited?.deposit_request?.product_name?.includes("Cashable"));
            },
            currencyOptions() {
                return ['CAD', 'USA']
            },
            counterOffers() {
                return this.offer?.counter_offers
            },
            investment_amount() {
                if (this.counterOffers.length > 0) {
                    this.depositAmount = this.counterOffers[0].maximum_amount;
                } else {
                    this.depositAmount = this.offer?.invited?.deposit_request?.amount;
                }
                this.interestRate = this.offer?.interest_rate_offer;

                return this.depositAmount;
            },
            intialRate() {
                return (this.offer?.interest_rate_offer).toFixed(2)
            },
            deposit_request() {
                return this.offer?.invited?.deposit_request
            }


        },
        watch: {
            selectedCounter(newv) {
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
                        this.rateChange = newv - this.offer.interest_rate_offer;
                        this.rateChange = `${this.rateChange.toFixed(2)}`;
                        this.interestRate = newv
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
                            this.rateChange = newv - this.offer.interest_rate_offer;
                            this.rateChange = `${this.rateChange.toFixed(2)}`;
                            this.interestRate = newv
                        } else {
                            this.addtionalInterestAdded = this.addCommas(0);
                            this.rateChange = 0;
                            this.rateChange = this.rateChange;
                        }


                    }
                }

            },
            depositAmount(newv, old) {

                if (newv !== '' && newv !== null && newv !== undefined) {
                    if (newv !== this.deposit_request.amount) {
                        this.initialIntereofstRateEarned = calculateIterestOnProduct(
                            this.deposit_request.amount,
                            this.deposit_request.term_length,
                            this.deposit_request.term_length_type,
                            this.deposit_request.product_name,
                            this.offer.interest_rate_offer
                        );
                        this.newCalculatedRate = calculateIterestOnProduct(
                            sanitizeAmount(newv),
                            this.deposit_request.term_length,
                            this.deposit_request.term_length_type,
                            this.deposit_request.product_name,
                            (this.counteredRateWith == 0) ? this.offer?.interest_rate_offer : this.counteredRateWith
                        );
                        this.addtionalInterestAdded = this.newCalculatedRate - this.initialIntereofstRateEarned;
                        this.addtionalInterestAdded = this.addCommas(this.addtionalInterestAdded);
                        this.rateChange = (this.counteredRateWith == 0) ? 0 : this.counteredRateWith - this.offer.interest_rate_offer;
                        this.rateChange = `${this.rateChange.toFixed(2)}`;
                    } else {
                        this.addtionalInterestAdded = this.addCommas(0);
                        this.rateChange = 0;
                        this.rateChange = this.rateChange;
                    }
                }
            }
        },
    }
</script>

<style>
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
        color: #252525;
        text-align: center;
        font-family: Montserrat;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
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
        padding: 30px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex: 1 0 0;
        align-self: stretch;
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
</style>