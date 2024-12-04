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
                        <div class="text-div">Deposit Summary</div>
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
            <div class="" style="width: 250px !important;">

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
                    <div class="my-2" style="width: 100%; height: 100%; border: 0.51px #9CA1AA solid"></div>
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
            <!-- {{ offer_data }} -->
            <div class="">
                <div class="d-flex flex-column">
                    <div class="pr-deposit-summary-investment">
                        <p class="p-0 m-0" v-if="reasons">
                            {{ productFromProductName(deposit_request?.product_name) }}

                        </p>
                    </div>
                    <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                        <ViewCard title="Term Length"
                            :desc="deposit_request.term_length_type == 'HISA' ? '-' : deposit_request?.term_length + ' ' + capitalize(deposit_request?.term_length_type)" />
                        <ViewCard title="Lockout Period"
                            :desc="deposit_request?.lockout_period_days ? deposit_request?.lockout_period_days + ' Days' : '-'" />
                        <ViewCard title="Maturity Date" :desc="formatDateToCustomFormat(offer_data?.rate_held_until)" />
                        <ViewCard title="Amount Awarded"
                            :desc="deposit_request?.currency + ' ' + addCommas(deposit_request?.amount)" />
                        <!-- <ViewCard title="Product" :desc="deposit_request?.product_name" /> -->
                        <ViewCard title="Compounding Frequency"
                            :desc="capitalize(deposit_request?.compound_frequency)" />
                    </div>

                </div>

            </div>
        </div>
        <div class=" w-100 d-flex justify-content-between my-4 gap-2">
            <div>
                <CustomSubmit @action="goBack()" :outline="true" v-if="fromPage == 'bank-pending-deposits'"
                    title="Previous" />
            </div>
            <div class="d-flex justify-content-end mt-3 gap-2" v-if="fromPage == 'bank-pending-deposits'">
                <CustomSubmit @action="" :outline="true" title="chat" />
                <CustomSubmit @action="withdrawpromt = true" title="Withdraw Award" />
            </div>
            <div class="d-flex justify-content-end mt-3 gap-2" v-if="fromPage == 'bank-active-deposits'">
                <CustomSubmit @action="goBack()" :outline="true" title="Previous" />
                <CustomSubmit @action="markinactive = true" title="Mark Inactive" />
            </div>
        </div>

        <!-- header -->

        <!-- end header -->
        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">
                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <b-tab :title="'About ' + organization_data.name" active>
                        <AboutDepositor :organization_data="organization_data"></AboutDepositor>
                    </b-tab>
                    <b-tab title="Contact Person Summary" >
                        <AboutOrgAdmin :depositor_data="depositor"></AboutOrgAdmin>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Offer Withdrawn Successfully" btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw ">Your Deposit will no longer be
                available to all Financial Institutions.</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Offer not withdrawn!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="withdrawpromt = false"
            @btnTwoClicked="withDrawDeposit" @btnOneClicked="withdrawpromt = false" icon="/assets/signup/danger.svg"
            title="Withdraw Request" :showm="withdrawpromt" btnOneText="Cancel" btnTwoText="Submit">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to withdraw your Deposit? It
                will no longer be available to investors.</div>
        </ActionMessage>

        <!-- mark inactive -->

        <ActionMessage style="width: 600px;" @closedSuccessModal="markinactive = false" @btnTwoClicked="markInactive"
            @btnOneClicked="markinactive = false" icon="/assets/dashboard/icons/question-new.svg"
            title="mark this deposit as inactive?" :showm="markinactive" btnOneText="Cancel" btnTwoText="Mark Inactive">
            <div class="ml-5 description-text-withdraw ">
                <CustomSelectInput label="Reason for marking the deposit as inactive" :options="options"
                    :required="true" placeholder="Select your reason" :currentValue="reason" v-model="reason" />
            </div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="inactivesuccess = false" @btnTwoClicked=""
            @btnOneClicked="inactivesuccess = false" icon="/assets/signup/success_promo.svg"
            title="Deposit Marked as inactive" btnOneText="" btnTwoText="" :showm="inactivesuccess">
            <div class="ml-5 description-text-withdraw ">Your Deposit will no longer be
                available to all Investors.</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="inactivefail = false" @btnTwoClicked=""
            @btnOneClicked="inactivefail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Deposit not inactivated!" :showm="inactivefail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </div>

</template>

<script>
import TimerClock from '../../../campaigns/TimerClock.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSelectInput from '../../../auth/signup/shared/CustomSelectInput.vue'

import ViewCard from '../pendingdeposits/actions/ViewCard.vue'

import AboutDepositor from '../pendingdeposits/AboutDepositor.vue'
import AboutOrgAdmin from '../../depositor/pendingdeposits/AboutOrgAdmin.vue';





export default {
    components: { TimerClock, CustomSubmit, ActionMessage, CustomSelectInput, ViewCard, AboutDepositor, AboutOrgAdmin },
    props: ['organization', 'offer', 'depositRequest', 'contract', 'depositor'],
    mounted() {
        if (this.organization)
            this.organization_data = JSON.parse(this.organization)
        if (this.depositRequest)
            this.deposit_request = JSON.parse(this.depositRequest)
        if (this.offer)
            this.offer_data = JSON.parse(this.offer)
        // console.log(this.organization)

        this.offer_id = this.getUrlPArams()

        const urlParams = new URLSearchParams(window.location.search);
        let fromPage = urlParams.get('fromPage');
        this.fromPage = fromPage
        this.getReasons()
        this.getProductTypes()

    },

    data() {
        return {
            viewMore1: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            success: false,
            fail: false,
            inactivesuccess: false,
            inactivefail: false,
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null,
            markinactive: false,
            options: [],
            reason: null,
            products: null,
            reasons: null

        }
    },
    computed: {

        awarded_amount() {
            return this.addCommas(this.offer_data?.amount)
        },
    },
    methods: {
        markInactive() {
            this.markinactive = false
            axios.post('/mark-deposits-inactive/' + this.offer_id, { 'dep_id': this.offer_id, 'reason': this.reason }).then(response => {
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.href = "/bank-active-deposits"
                    }, 3000)
                } else {
                    this.fail = true

                }
            }).catch(err => {
                this.fail = true
            })
        },
        async getReasons() {
            await axios.get('/bank-inactivation-reasons').then(response => {
                response.data.forEach(element => {
                    let reason = {}
                    reason = {
                        'id': element.reason,
                        'name': element.reason,
                    }
                    this.options.push(reason)
                });
            }).catch(err => {
                console.log(err)
            })
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
            if (this.fromPage == "bank-pending-deposits")
                window.location.href = "/pending-deposits"
            else if (this.fromPage == "bank-active-deposits")
                window.location.href = "/bank-active-deposits"
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