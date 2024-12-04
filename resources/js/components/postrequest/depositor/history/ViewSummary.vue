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
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.3261 10.2884 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.3261 29.7117 29.7117 33.3261 25.0826 34.4119C21.7396 35.1961 18.2604 35.1961 14.9174 34.4119C10.2884 33.3261 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M25.699 13H14.411C13.6349 13 13.0071 13.6349 13.0071 14.411L13 27.11L15.822 24.288H25.699C26.475 24.288 27.11 23.6531 27.11 22.877V14.411C27.11 13.6349 26.475 13 25.699 13ZM25.699 22.877H15.2364L14.8202 23.2932L14.411 23.7024V14.411H25.699V22.877ZM18.9967 21.466H24.288V20.055H20.4078L18.9967 21.466ZM21.72 17.3247C21.8611 17.1836 21.8611 16.9649 21.72 16.8238L20.4712 15.5751C20.3301 15.434 20.1114 15.434 19.9703 15.5751L15.822 19.7234V21.466H17.5646L21.72 17.3247Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div class="text-div">Offer Summary</div>
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
        <div v-if="viewMore1 && offer_data != null" class="d-flex justify-content-start gap-3">
            <div class="" style="width: 250px !important;">

                <div
                    style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 5px; display: inline-flex">
                    <div
                        style="font-family: Montserrat;font-size: 16px;font-style: normal;font-weight: 400;line-height: 26px; /* 162.5% */text-transform: capitalize;">
                        {{ deposit_request?.product_name }}
                    </div>
                    <div
                        style="text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                        {{ offer_data != null ? offer_data.interest_rate_offer.toFixed(2) : 0.00
                        }}%
                    </div>
                    <div class="my-2" style="width: 100%; height: 100%; border: 0.51px #9CA1AA solid"></div>
                    <div style="flex-direction: column; justify-content: center; align-items: center; display: flex">
                        <div
                            style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            Interest Earned</div>
                        <div
                            style="color: #5063F4;font-family: Montserrat !important;font-size: 20px;font-style: normal;font-weight: 700;line-height: 26px; text-transform: capitalize;">
                            {{
            deposit_request?.currency + " " + addCommas(
                calaculateInterestRate(deposit_request?.term_length_type, deposit_request?.amount,
                    deposit_request?.term_length, offer_data?.interest_rate_offer)) }}</div>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="d-flex flex-column">
                    <div class="pr-deposit-summary-investment">
                        <p class="p-0 m-0" v-if="this.products != null">
                            {{ productFromProductName(deposit_request?.product_name) }}</p>
                    </div>
                    <div class="d-flex gap-5 flex-wrap mt-3 p-3">
                        <ViewCard title="Minimum "
                            :desc="deposit_request?.currency + ' ' + addCommas(offer_data?.minimum_amount)" />
                        <ViewCard title="Maximum "
                            :desc="deposit_request?.currency + ' ' + addCommas(offer_data?.maximum_amount)" />
                        <!-- <ViewCard title="Offered Amount "
                            :desc="deposit_request?.currency + ' ' + addCommas(deposit_request?.amount)" /> -->
                        <ViewCard title="Rate Held Until">
                            <TimerClock class="t-clock" :targetTime="offer_data?.rate_held_until"> </TimerClock>
                        </ViewCard>
                        <!-- <ViewCard title="Compounding Frequency"
                            :desc="capitalize(deposit_request?.compound_frequency)" /> -->
                        <ViewCard title="PDS">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.33301 3.33464C3.33301 2.89261 3.5086 2.46868 3.82116 2.15612C4.13372 1.84356 4.55765 1.66797 4.99967 1.66797H14.9997C15.4417 1.66797 15.8656 1.84356 16.1782 2.15612C16.4907 2.46868 16.6663 2.89261 16.6663 3.33464V16.668C16.6663 17.11 16.4907 17.5339 16.1782 17.8465C15.8656 18.159 15.4417 18.3346 14.9997 18.3346H4.99967C4.55765 18.3346 4.13372 18.159 3.82116 17.8465C3.5086 17.5339 3.33301 17.11 3.33301 16.668V3.33464ZM14.9997 3.33464H4.99967V16.668H14.9997V3.33464ZM6.66634 7.5013C6.66634 7.28029 6.75414 7.06833 6.91042 6.91205C7.0667 6.75577 7.27866 6.66797 7.49967 6.66797H12.4997C12.7207 6.66797 12.9326 6.75577 13.0889 6.91205C13.2452 7.06833 13.333 7.28029 13.333 7.5013C13.333 7.72232 13.2452 7.93428 13.0889 8.09056C12.9326 8.24684 12.7207 8.33464 12.4997 8.33464H7.49967C7.27866 8.33464 7.0667 8.24684 6.91042 8.09056C6.75414 7.93428 6.66634 7.72232 6.66634 7.5013ZM7.49967 10.8346C7.27866 10.8346 7.0667 10.9224 6.91042 11.0787C6.75414 11.235 6.66634 11.447 6.66634 11.668C6.66634 11.889 6.75414 12.1009 6.91042 12.2572C7.0667 12.4135 7.27866 12.5013 7.49967 12.5013H9.99967C10.2207 12.5013 10.4326 12.4135 10.5889 12.2572C10.7452 12.1009 10.833 11.889 10.833 11.668C10.833 11.447 10.7452 11.235 10.5889 11.0787C10.4326 10.9224 10.2207 10.8346 9.99967 10.8346H7.49967Z"
                                    fill="#5063F4" />
                            </svg>
                        </ViewCard>
                        <ViewCard title="Special Instructions" :desc="capitalize(offer_data?.special_instructions)" />
                        <!-- <ViewCard title="Interest Earned" :desc="capitalize(deposit_request?.compound_frequency)" /> -->
                    </div>

                </div>

            </div>
        </div>
        <div v-if="viewMore1" class=" w-100 d-flex justify-content-between my-4 gap-2">
            <div>
                <!-- <CustomSubmit @action="goBack()" :outline="true" title="Previous" /> -->
            </div>
            <div class="d-flex justify-content-end mt-3 gap-2" v-if="fromPage == 'depositor-history'">
                <!-- <CustomSubmit @action="" :outline="true" title="chat" /> -->
                <CustomSubmit @action="goBack" title="Previous" />
            </div>
            <div class="d-flex justify-content-end mt-3 gap-2" v-if="fromPage == 'active-deposits'">
                <CustomSubmit @action="markinactive = true" title="Mark Inactive" />
            </div>
        </div>

        <!-- header -->

        <!-- end header -->
        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">
                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <b-tab :title="'About ' + organization_data?.name" active>
                        <AboutBank :organization_data="organization_data"></AboutBank>
                    </b-tab>
                    <b-tab title="Request Summary">
                        <div class="w-100">
                            <div class="d-flex gap-5 flex-wrap mt-3 p-3">
                                <ViewCard title="Product Type " :desc="capitalize(deposit_request?.product_name)" />


                                <ViewCard title="Rate"
                                    :desc="deposit_request?.requested_rate ? deposit_request?.requested_rate.toFixed(2) : '-'" />


                                <ViewCard title="Term Length "
                                    :desc="deposit_request.term_length_type == 'HISA' ? '-' : deposit_request?.term_length + ' ' + capitalize(deposit_request?.term_length_type)" />


                                <ViewCard title="Lockout Period "
                                    :desc="deposit_request?.lockout_period_days ? deposit_request?.lockout_period_days : '-'" />


                                <ViewCard title="Date Of Deposit "
                                    :desc="formatDateToCustomFormat(deposit_request?.date_of_deposit)" />


                                <ViewCard title="Requested Amount "
                                    :desc="deposit_request?.currency + ' ' + addCommas(deposit_request?.amount)" />


                                <ViewCard title="Compounding Freq"
                                    :desc="capitalize(deposit_request?.compound_frequency)" />

                                <ViewCard title="DBRS Rating "
                                    :desc="capitalize(deposit_request?.requested_short_term_credit_rating)" />

                                <ViewCard title="Deposit Insurance "
                                    :desc="capitalize(deposit_request?.requested_deposit_insurance)" />

                                <ViewCard title="Closing Date & Time "
                                    :desc="formatDateToCustomFormat(deposit_request?.closing_date_time, true)" />


                                <ViewCard title="Special Instructions"
                                    :desc="deposit_request?.special_instructions ? capitalize(deposit_request?.special_instructions) : '-'" />
                            </div>

                        </div>
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

        <!-- mark inactive -->

        <ActionMessage style="width: 600px;" @closedSuccessModal="markinactive = false" @btnTwoClicked="markInactive"
            @btnOneClicked="markinactive = false" icon="/assets/signup/danger.svg" title="Mark Depoit as Inactive"
            :showm="markinactive" btnOneText="Cancel" btnTwoText="Submit">
            <div class="ml-5 description-text-withdraw ">
                <CustomSelectInput :options="options" :required="true" placeholder="Select your reason"
                    :currentValue="reason" v-model="reason" />

            </div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="inactivesuccess = false" @btnTwoClicked=""
            @btnOneClicked="inactivesuccess = false" icon="/assets/signup/success_promo.svg"
            title="Deposit Marked as inactive" btnOneText="" btnTwoText="" :showm="inactivesuccess">
            <div class="ml-5 description-text-withdraw ">Your Deposit will no longer be
                available to all Financial Institutions.</div>
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
import ViewCard from '../pendingdeposits/actions/ViewCard.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import AboutBank from '../../depositor/pendingdeposits/AboutBank.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSelectInput from '../../../auth/signup/shared/CustomSelectInput.vue'




export default {
    components: { ViewCard, TimerClock, CustomSubmit, AboutBank, ActionMessage, CustomSelectInput },
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
        this.getReasons()
        this.getProductTypes()
    },

    data() {
        return {
            viewMore1: true,
            viewMore2: false,
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
            products: null
        }
    },
    computed: {

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        markInactive() {
            this.markinactive = false
            axios.post('/mark-deposit-inactive/' + this.offer_id, { 'dep_id': this.offer_id, 'reason': this.reason }).then(response => {
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.href = "/active-deposits"
                    }, 3000)
                } else {
                    this.fail = true

                }
            }).catch(err => {
                this.fail = true
            })
        },
        getReasons() {
            axios.get('/inactivation-reasons').then(response => {
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

        calaculateInterestRate(termLengthType, amountOffered, termLength, rate) {

            let cal_interest = 0;
            if (termLengthType === "HISA") {
                cal_interest = Math.round((amountOffered * rate) / 100);
                return cal_interest;
            } else {
                switch (termLengthType) {
                    case "DAYS":
                        cal_interest = Math.round(
                            (((amountOffered * rate) / 100) * termLength) / 365
                        );
                        return cal_interest;
                        break;
                    case "MONTHS":
                        cal_interest = Math.round(
                            (((amountOffered * rate) / 100) * termLength) / 12
                        );
                        return cal_interest;
                        break;
                }
            }


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
            // if (this.fromPage == "pending-deposits")
            //     window.location.href = "/pending-deposits"
            // else if (this.fromPage == "active-deposits")
            window.location.href = "/depositor-history"
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
        },
        toggleView(value) {
            if (value === 1) {
                this.viewMore1 = !this.viewMore1
            }
            if (value == 2) {
                this.viewMore2 = !this.viewMore2
            }
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
    font-family: Montserrat !important;
    font-weight: 500;
    word-wrap: break-word
}

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>