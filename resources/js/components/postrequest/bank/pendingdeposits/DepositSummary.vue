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
                            <img src="/assets/dashboard/icons/PromoOffer.svg" />
                        </div>
                        <div class="text-div">Deposit Summary</div>
                    </div>
                    <div @click="toggleView(1)"
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
        <!-- end header -->
        <div class="row">
            <div class="col-md-3 col-lg-2">

                <div
                    style="width:85%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
                    <div
                        style="height: 42px; text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                        {{ offer_data.interest_rate_offer.toFixed(2) }}%</div>
                    <div style="width: 128px; height: 0px; border: 0.51px #9CA1AA solid ;margin-top: 20px;"></div>
                    <div style="flex-direction: column; justify-content: center; align-items: center; display: flex">
                        <div
                            style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            Interest earned</div>
                        <div
                            style="color: #5063F4; font-size: 20px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            CAD 2,000</div>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-lg-10">
                <div class="d-flex flex-column">
                    <div class="pr-deposit-summary-investment">
                        <p class="p-0 m-0">
                            A fixed-term investment with a locked interest rate, providing steady and predictable
                            earningsover the investment period.</p>
                    </div>
                    <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                        <ViewCard title="Amount Awarded"
                            :desc="deposit_request?.currency + ' ' + addCommas(deposit_request.amount)" />
                        <ViewCard title="Product" :desc="deposit_request?.product_name" />
                        <ViewCard title="Term Length"
                            :desc="deposit_request?.term_length + ' ' + capitalize(deposit_request.term_length_type)" />
                        <ViewCard title="Lockout Period"
                            :desc="deposit_request.lockout_period_days ? deposit_request.lockout_period_days + ' Days' : '-'" />
                        <ViewCard title="Maturity Date"
                            :desc="formatDateToCustomFormat(offer_data.deposit.maturity_date)" />
                        <!-- :desc="addDaysOrMonths(deposit_request.date_of_deposit, deposit_request.term_length, deposit_request.term_length_type.toLowerCase())" /> -->
                        <!-- <ViewCard title="interest frequency" :desc="offer_data.compound_frequency" /> -->
                        <ViewCard title="Rate Held Until">
                            <TimerClock class="t-clock" :targetTime="offer_data.rate_held_until"> </TimerClock>
                        </ViewCard>
                        <ViewCard title="Compounding Frequency"
                            :desc="capitalize(deposit_request.compound_frequency)" />
                    </div>

                </div>

            </div>
        </div>
        <div class=" w-100 d-flex justify-content-between my-4 gap-2">
            <div>
                <CustomSubmit @action="goBack()" :outline="true" title="Previous" />
            </div>
            <div class="d-flex justify-content-end mt-3 gap-2" v-if="fromPage == 'pending-deposits'">
                <CustomSubmit @action="" :outline="true" title="chat" />
                <CustomSubmit @action="withdrawpromt = true" title="Withdraw Award" />
            </div>
        </div>

        <!-- header -->
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/PromoOffer.svg" />
                        </div>
                        <div class="text-div">Financial Institution</div>
                    </div>
                    <div @click="toggleView(1)"
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
        <!-- end header -->
        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">
                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <b-tab :title="'About ' + organization_data.name" active>
                        <AboutBank :organization_data="organization_data"></AboutBank>
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
                will no
                longer be available to all Financial Institutions.</div>
        </ActionMessage>
    </div>

</template>

<script>
import TimerClock from '../../../campaigns/TimerClock.vue';
import ViewCard from './actions/ViewCard.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import AboutBank from './AboutBank.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'



export default {
    components: { ViewCard, TimerClock, CustomSubmit, AboutBank, ActionMessage },
    props: ['organization', 'offer', 'depositRequest'],
    mounted() {
        if (this.organization) {
            this.organization_data = JSON.parse(this.organization)
            this.offer_data = JSON.parse(this.offer)
            this.deposit_request = JSON.parse(this.depositRequest)
        }
        this.offer_id = this.getUrlPArams()

        const urlParams = new URLSearchParams(window.location.search);
        let fromPage = urlParams.get('fromPage');
        this.fromPage = fromPage
    },

    data() {
        return {
            viewMore1: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            success: false,
            fail: false,
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null
        }
    },
    computed: {

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];
            return numberPart
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
            if (this.fromPage == "pending-deposits")
                window.location.href = "/pending-deposits"
            else if (this.fromPage == "active-deposits")
                window.location.href = "/active-deposits"
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