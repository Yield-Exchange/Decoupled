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
                            <img src="/assets/dashboard/icons/create-gic-icon.svg" />
                        </div>
                        <div class="text-div">Create GIC</div>
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
        <div class="w-100 d-flex flex-column justify-content-end">
            <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
                <div class="d-flex justify-ontent-center w-100">
                    <img src="/assets/dashboard/images/create-gic.svg" style="max-height: 200px; margin: 20px auto;"
                        alt="" srcset="">
                </div>
                <div class="w-100 d-flex flex-column justify-content-start">

                    <div>
                        <p class="p-0 m-0 title">GIC Details</p>
                    </div>
                    <div class="w-100 mt-2">
                        <table class="pr-gic-table w-100">
                            <thead>
                                <tr>
                                    <td>Deposit ID</td>
                                    <td>Product</td>
                                    <td>Term Length</td>
                                    <td>Lockout Period</td>
                                    <td>Awarded Amount</td>
                                    <td>Compounding Frequency</td>
                                    <td>Date of Deposit</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ deposit.reference_no }}</td>
                                    <td>{{ deposit.offer.invited.deposit_request.product_name }}</td>

                                    <td class="text-capitalize">{{
                                        deposit.offer.invited.deposit_request.term_length_type === 'HISA' ? '-' :
                                            (deposit.offer.invited.deposit_request.term_length +
                                                ' ' + deposit.offer.invited.deposit_request.term_length_type.toLowerCase()) }}
                                    </td>
                                    <!-- <td>{{ deposit.offer.invited.deposit_request.currency }}</td> -->
                                    <td>{{ deposit.offer.invited.deposit_request.lockout_period_days ?
                                        deposit.offer.invited.deposit_request.lockout_period_days : '-' }}</td>
                                    <td>{{ deposit.offer.invited.deposit_request.currency + " " +
                                        addCommas(deposit.offer.invited.deposit_request.amount) }}</td>
                                    <td class="text-capitalize">
                                        {{ deposit.offer.invited.deposit_request.compound_frequency.toLowerCase() }}
                                    </td>
                                    <td> {{
                                        formatDateToCustomFormat(deposit.offer.invited.deposit_request.date_of_deposit)
                                    }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100">
                        <div class="pr-my-20">
                            <p class="p-0 m-0 pr-buy-gic-form">
                                Please enter the following information
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <FormLabelRequired labelText="Interest Rate" style="margin-bottom: 4px !important;"
                                    :required="true" :showHelperText="false" helperText="Rate " helperId="PDSHId" />
                                <CustomInput inputType="number" input-style="height:46px !important"
                                    c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                                    p-style="width:100%" id="rate" name="Rate*" :has-validation="true"
                                    @inputChanged="InterestRateChange($event)" input-type="number"
                                    :defaultValue="interest_rate" :hasSpecificError="interestRateError" />
                                <div v-if="interestRateError" class="error-message">
                                    {{ interestRateError }}
                                </div>

                                <!-- <CustomTextInput @hasError="(checkerVariable) => interestRateError = checkerVariable"
                                    :isemptycheck="requiredChecker" :currentValue="interest_rate" minlength="1"
                                    v-model="interest_rate" :required="true" label="Interest Rate"
                                    placeholder="Enter interest rate" input_type="number" /> -->
                            </div>
                            <div class="col-md-3">
                                <CustomDatePicker @hasError="(checkerVariable) => startDateError = checkerVariable"
                                    :isemptycheck="requiredChecker" :currentValue="start_date" v-model="start_date"
                                    :min="this.startDateMinDate" :max="this.startDateMaxDate" :required="true"
                                    label="Start Date" placeholder="Select  start date" input_type="text" />
                            </div>
                            <div class="col-md-3">
                                <CustomDatePicker @hasError="(checkerVariable) => maturityDateError = checkerVariable"
                                    :isemptycheck="requiredChecker" :currentValue="maturity_date"
                                    :min="this.maturityDateMinDate" :max="this.maturityDateMaxDate"
                                    v-model="maturity_date" :required="true" label="Maturity Date"
                                    placeholder="Select maturity date" input_type="text" />
                            </div>

                            <div class="col-md-3">
                                <CustomTextInput inputStyle="font-weight:400 !important"
                                    @hasError="(checkerVariable) => gicNumberError = checkerVariable"
                                    :isemptycheck="requiredChecker" :currentValue="gic_number" v-model="gic_number"
                                    :maxlength="12" :required="true" label="GIC Number" placeholder="Enter GIC number"
                                    input_type="text" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
            <div class="w-100 d-flex justify-content-end mt-3 gap-2">
                <Button @action="goBack" :outline="true" title="previous" />
                <Button @action="checkRates" :outline="false" title="Submit" />
            </div>
            <ActionMessage style="width: 600px;" @closedSuccessModal="gic_created = false" @btnTwoClicked=""
                @btnOneClicked="gic_created = false" icon="/assets/signup/success_promo.svg"
                title="GIC has been created." btnOneText="" btnTwoText="" :showm="gic_created">
                <div class="ml-5 description-text-withdraw ">Your GIC is now showing in the Active Deposits.</div>
            </ActionMessage>

            <ActionMessage style="width: 600px;" @closedSuccessModal="acceptRateChange(false)"
                @btnTwoClicked="acceptRateChange(true)" @btnOneClicked="acceptRateChange(false)"
                icon="/assets/signup/success_promo.svg" title="The Interest rate is different." btnOneText="No"
                btnTwoText="Yes" :showm="interest_rate_checker">
                <div class="ml-5 description-text-withdraw ">
                    The Interest rate entered is different than the offer rate.Do you wish to update the interest rate?
                </div>
            </ActionMessage>

            <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
                @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
                title="GIC Not created!" :showm="fail">
                <div class="ml-5 description-text-withdraw ">Your GIC has not been created.Please try
                    again .</div>
            </ActionMessage>
        </div>
    </div>
</template>

<script>

import CustomTextInput from '../../../auth/signup/shared/CustomTextInput.vue';
import Button from '../../../auth/signup/shared/CustomSubmit.vue'
import ErrorMessage from '../../../auth/signup/shared/PopUpModal.vue'
import CustomDatePicker from '../../depositor/shared/DatePicker.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import moment from 'moment-timezone';
import CustomInput from '../../../shared/CustomInput.vue';
import FormLabelRequired from '../../../shared/formLabels/FormLabelRequired.vue';

// import CustomDa from '../../../auth/signup/shared/PopUpModal.vue'



export default {
    props: ["organization", "deposit", "submitRoute", "depositReq", "fromPage", "userObject", "permittedSubmitButton"],

    components: {
        CustomInput, FormLabelRequired,
        CustomTextInput, Button, ErrorMessage, CustomDatePicker, ActionMessage
    },
    created() {
        let format = this.format;
        let formatDate = this.formatDate;
        //  let format_ = 'YYYY-MM-DD HH:mm:ss ZZ';
        let timeZone = this?.userObject?.formatted_timezone;
        let todayDateWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);

        this.maturityDateMinDate = moment.utc(this.deposit.offer.invited.deposit_request.closing_date_time).local();
        this.startDateMinDate = moment.utc(this.deposit.offer.invited.deposit_request.closing_date_time).local();
        this.startDateMaxDate = todayDateWithUserTimezone;
        if (this.startDateMinDate > this.startDateMaxDate) {
            this.startDateMaxDate = this.startDateMinDate;
        }

        this.startDateMinDate = this.startDateMinDate.format(formatDate);
        this.startDateMaxDate = this.startDateMaxDate.format(formatDate);
        this.maturityDateMinDate = this.maturityDateMinDate.format(formatDate);
    },
    mounted() {
        // console.log(this.depositReq)
        if (this.deposit) {
            this.interest_rate = this.deposit?.offer.interest_rate_offer ? this.deposit?.offer.interest_rate_offer.toFixed(2) : null
            this.current_rate = this.deposit?.offer.interest_rate_offer ? this.deposit?.offer.interest_rate_offer.toFixed(2) : null

        }
    },
    data() {
        return {
            start_date: null,
            maturity_date: null,
            interest_rate: null,
            gic_number: null,
            requiredChecker: false,
            gic_created: false,
            acceptrate_change: false,
            interest_rate_checker: false,

            startDateError: false,
            gicNumberError: false,
            interestRateError: false,
            maturityDateError: false,
            maturityDateMinDate: null,
            maturityDateMaxDate: null,
            fail: false,
            failmessage: 'Please ensure all fields are filled in correctly and retry. If the issue persists, please contact us at info@yieldexchange.ca.',
        }
    },
    methods: {
        InterestRateChange(value) {
            if (value != '' && value > 0) {
                this.interestRateError = null
                this.interest_rate = value
            }
            if (value > 100) {
                this.interestRateError = "Rate cannot be greater than 100%"
            }
        },
        onChangeStartDate(value) {
            // let format = this.format;
            let formatDate = this.formatDate;

            // this.start_date = value;
            var date = moment(value);
            var max_date;
            var min_date;
            let new_date;

            var termlength = this.deposit.offer.invited.deposit_request.term_length;
            var termlengthtype = this.deposit.offer.invited.deposit_request.term_length_type;

            new_date = moment(value).add(parseInt(termlength), termlengthtype.toLowerCase());

            max_date = new_date.add(7, 'days').format(formatDate)
            min_date = new_date.subtract(7, 'days').format(formatDate)


            // _date = date.clone().add(7, "days");
            this.maturity_date = date
            this.maturityDateMinDate = min_date
            this.maturityDateMaxDate = max_date
        },

        goBack() {
            window.location.href = '/bank-pending-deposits';
        },
        acceptRateChange(value) {
            if (value) {
                this.interest_rate_checker = false
                this.acceptrate_change = true
            } else {
                this.interest_rate_checker = false
                this.acceptrate_change = false
                this.submitForm()
            }
        },
        checkRates() {
            if (this.current_rate != this.interest_rate) {
                this.interest_rate_checker = true
            } else {
                this.submitForm()
            }
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }
        },
        submitForm() {
            // console.log(this.submitRoute)
            this.requiredChecker = false
            this.canSubmit();
            if (this.canSubmit()) {
                this.submitAction()
            } else {
                this.requiredChecker = true
            }
        },
        formatDateToCustomFormat(inputDate) {
            // Create a Date object from the inputDate parameter
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        canSubmit() {
            if (this.start_date != null
                && this.maturity_date != null
                && this.interest_rate != null
                && this.gic_number != null
                && !this.startDateError
                && !this.maturityDateError
                && !this.gicNumberError
                && !this.interestRateError
            ) {
                return true;
            }
            else {
                return false
            }
        },
        async submitAction() {
            const updated_user = {
                deposit_id: this.deposit.id,
                gic_start_date: this.start_date,
                gic_number: this.gic_number,
                maturity_date: this.maturity_date
            }
            await axios.post(this.submitRoute, updated_user, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.data.success) {
                    // console.log("Data Updated")
                    this.gic_created = true
                    setTimeout(() => {
                        this.gic_created = false
                        window.location.href = "/bank-active-deposits"
                    }, 3000)
                }
            }).catch(err => {
                this.fail = true
                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false
                }, 3000)
            })
        },
    },
    watch: {
        start_date() {
            this.onChangeStartDate(this.start_date)
        }
    }

}
</script>

<style scoped>
.pr-my-20 {
    margin: 20px 0;
}

.description-text-withdraw {
    margin-top: -30px;
    font-size: 16px;
    font-family: Montserrat !important;
}

.pr-gic-table {
    font-family: Montserrat !important;

}

.pr-buy-gic-form {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
}

.pr-gic-table thead tr td {
    font-weight: 700;
    color: #5063F4 !important;
    margin-left: 10px;
}

.pr-gic-table tbody tr td {
    color: var(--Yield-Exchange-Colors-Darks, #252525);
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Promotion chart body */
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
    padding: 5px 0px;
}


.thirdpartyHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    padding: 0;
    margin: 0 !important;
}

.thirdpartytext {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 18px;
    padding: 0;
    /* 112.5% */
}





.title {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 108.333% */
    text-transform: capitalize;

}
</style>