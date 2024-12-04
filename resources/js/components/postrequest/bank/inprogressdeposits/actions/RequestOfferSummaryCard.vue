<template>
    <div>
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 20px; display: inline-flex">
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
                                    d="M23.6 29C22.355 29 21.2936 28.5588 20.4158 27.6763C19.538 26.7939 19.0994 25.7272 19.1 24.4762C19.1 23.2246 19.5389 22.1576 20.4167 21.2751C21.2945 20.3927 22.3556 19.9518 23.6 19.9524C24.845 19.9524 25.9064 20.3936 26.7842 21.276C27.662 22.1585 28.1006 23.2252 28.1 24.4762C28.1 25.7278 27.6611 26.7948 26.7833 27.6772C25.9055 28.5597 24.8444 29.0006 23.6 29ZM25.1075 26.625L25.7375 25.9917L24.05 24.2952V21.7619H23.15V24.6571L25.1075 26.625ZM12.8 28.0952C12.305 28.0952 11.8811 27.9179 11.5283 27.5632C11.1755 27.2086 10.9994 26.7827 11 26.2857V13.619C11 13.1214 11.1764 12.6953 11.5292 12.3406C11.882 11.986 12.3056 11.8089 12.8 11.8095H16.5575C16.7225 11.2817 17.045 10.8481 17.525 10.5085C18.005 10.1689 18.53 9.9994 19.1 10C19.7 10 20.2364 10.1698 20.7092 10.5094C21.182 10.849 21.5006 11.2824 21.665 11.8095H25.4C25.895 11.8095 26.3189 11.9869 26.6717 12.3415C27.0245 12.6962 27.2006 13.122 27.2 13.619V19.2738C26.93 19.0778 26.645 18.9119 26.345 18.7762C26.045 18.6405 25.73 18.5198 25.4 18.4143V13.619H23.6V16.3333H14.6V13.619H12.8V26.2857H17.57C17.675 26.6175 17.795 26.9341 17.93 27.2357C18.065 27.5373 18.23 27.8238 18.425 28.0952H12.8ZM19.1 13.619C19.355 13.619 19.5689 13.5322 19.7417 13.3585C19.9145 13.1848 20.0006 12.97 20 12.7143C20 12.4579 19.9136 12.2429 19.7408 12.0692C19.568 11.8955 19.3544 11.8089 19.1 11.8095C18.845 11.8095 18.6311 11.8964 18.4583 12.0701C18.2855 12.2438 18.1994 12.4585 18.2 12.7143C18.2 12.9706 18.2864 13.1857 18.4592 13.3594C18.632 13.5331 18.8456 13.6197 19.1 13.619Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div class="text-div" style="font-size: 28px; line-height: 32px">Request Summary</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="offer_data" style="display: flex;padding: 10px;gap: 30px;">
            <div
                style="background: #FFF;box-shadow: 0px 4px 4px 0px #D9D9D9; display: flex; flex-direction: column; justify-content: center; align-content: center; padding: 20px 40px; width: 300px;">
                <h2 class="request_head" style="text-align: center;">{{
            offer_data?.invited?.deposit_request?.product_name
        }}</h2>
                <div v-if="hasCounter">
                    <!-- {{ offer_data?.counter_offers[0] }} -->
                    <div v-if="offer_data?.counter_offers[0]?.status == 'DECLINED'">
                        <h5 class="request_rate" style="text-align: center;">{{
            parseFloat(offer_data.interest_rate_offer).toFixed(2) }}%</h5>
                        <h2 class="request_counter_offer" style="text-align: center;">
                            {{ parseFloat(offer_data.counter_offers[0].offered_interest_rate).toFixed(2) }}%</h2>
                    </div>
                    <div v-else>
                        <h2 class="request_rate" style="text-align: center;">
                            {{ parseFloat(offer_data.counter_offers[0].offered_interest_rate).toFixed(2) }}%</h2>
                        <h5 class="request_counter_offer" style="text-align: center;">{{
            parseFloat(offer_data.interest_rate_offer).toFixed(2) }}%</h5>
                    </div>
                </div>
                <div v-else>
                    <h5 class="request_rate" style="text-align: center;">{{
            parseFloat(offer_data.interest_rate_offer).toFixed(2) }}%</h5>
                </div>
                <hr style="background-color: #9CA1AA;" />
                <h6 class="request_amount" style="text-align: center;">Requested Amount</h6>
                <h6 class="request_amount_cash" style="text-align: center;">{{
            offer_data.invited.deposit_request.currency }} {{
            addCommas(offer_data.invited.deposit_request.amount) }}</h6>
            </div>
            <div style="width: 90%;" class="d-flex flex-column justify-content-start">
                <p class="fixed_term">{{ offer_data?.invited?.deposit_request?.product_definition
                    }} </p>
                <div class="d-flex gap-5 flex-wrap mt-3 p-3">
                    <ViewCard title="Product" :desc="offer_data?.invited?.deposit_request?.product_name" />
                    <ViewCard title="Term Length" :desc="term_length" />
                    <ViewCard title="Lockout Period" :desc="hasNoticePeriod ? offer_data?.invited?.deposit_request?.lockout_period + ' Day ' +
            offer_data?.description : offer_data?.description" />
                    <ViewCard title="Compounding freq"
                        :desc="ucwords(offer_data?.invited?.deposit_request?.compound_frequency)" />
                    <ViewCard title="Date of deposit"
                        :desc="formatDateToCustomFormat(offer_data?.invited?.deposit_request?.date_of_deposit)" />





                    <!-- <div style="display: flex; flex-direction: column;">

                        <div style="display: flex;">
                            <p class="product-info">Product</p>
                            <p class="product-info">Term Length</p>
                            <p class="product-info">Lockout Period</p>
                            <p class="product-info">Compounding freq</p>
                            <p class="product-info">Date of deposit</p>
                        </div>
                        <div style="display: flex;">
                            <p class="product-info" style="color:  #252525;">
                                {{ offer_data?.invited?.deposit_request?.product_name }}</p>
                            <p class="product-info" style="color:  #252525;">
                                {{ offer_data?.invited?.deposit_request?.term_length_type == 'HISA' ? " - " :
            offer_data?.invited?.deposit_request?.term_length + " " +
            ucwords(offer_data?.invited?.deposit_request?.term_length_type.toLowerCase()) }}</p>
                            <p class="product-info" style="color:  #252525;">
                                {{ hasNoticePeriod ? offer_data?.invited?.deposit_request?.lockout_period + ' Day ' +
            offer_data?.description : offer_data?.description }}</p>
                            <p class="product-info" style="color:  #252525;">
                                {{ ucwords(offer_data?.invited?.deposit_request?.compound_frequency) }}</p>
                            <p class="product-info" style="color:  #252525;">
                                {{ formatDateToCustomFormat(offer_data?.invited?.deposit_request?.date_of_deposit) }}
                            </p>
                        </div>
                    </div> -->
                </div>
                <div v-if="hasCounter" style="margin-top: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="202" height="21" viewBox="0 0 202 21" fill="none">
                        <path d="M0 0H202L189.758 10.5L202 21H0V0Z" fill="#44E0AA" />
                        <text x="15" y="15" font-family="Arial" font-size="14" fill="white"
                            style="color: #FFF;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize;">Counter
                            offer received</text>
                    </svg>

                </div>
                <b-row style="margin-top: 10px;" v-if="hasCounter">
                    <div style="width: 100%;padding:25px;">
                        <table class="table" style="width: 100%;">
                            <thead class="customHeader" style="background-color: #EFF2FE;">
                                <tr>
                                    <th>#</th>
                                    <th>Counter Offer Date</th>
                                    <th>Deposit Amount</th>
                                    <th>Counter Rate</th>
                                    <th>Interest Rate Change</th>
                                    <th>Counter</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #F4F5F6;">
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
                                        <div class="textContainer"> {{ value.offered_interest_rate.toFixed(2) }} %
                                        </div>
                                    </td>
                                    <td>
                                        <div class="textContainer"> {{ (value.offered_interest_rate -
            offer_data.interest_rate_offer).toFixed(2) }}
                                            %</div>
                                    </td>
                                    <td>

                                        <CustomInvitedStatusBadge :text="value.status" />
                                    </td>
                                </tr>
                            </tbody>


                        </table>

                    </div>

                </b-row>
            </div>
        </div>
        <div class="button_section">
            <div v-if="!hasCounter">
                <button @click="clickPrevious()" class="accept_button">Previous</button>
            </div>
            <div v-else>
                <button @click="clickPrevious()" class="previous_button">Previous</button>
            </div>
            <div style="gap: 10px;" v-if="from_page != 'bank-history' && hasCounter">
                <button class="decline_button" @click="counterOfferActions('decline')">Decline Offer</button>
                <button class="counter_button" @click="counterOfferSubmit">Counter Offer</button>
                <button class="accept_button" @click="counterOfferActions('accept')">Accept Offer</button>
            </div>
        </div>

        <OKButtonActionMessageBoxVue :size="successModalSize" @closedSuccessModal="showResponseModal = false"
            :title="successModalTitle" :showm="showResponseModal" @okClicked="showResponseModal = false" fontsize="28px"
            :message="transError" />

        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
            :title="successModalTitle" :showm="showSuccessModal" @okClicked="showSuccessModal = false" fontsize="28px"
            :message="transError" btn-one-text="Cancel" btn-two-text="Submit" @btnOneClicked="cancleClicked"
            @btnTwoClicked="submitClicked" />

        <CounterOfferB :submitted="submitted" :deposit_request="deposit_request_data" :datum="offer"
            :hasCounterOffer="hasCounterOffer" :investment_amount="investment_amount" :show="showCounterOfferModal"
            @visible="closeCounterOfferModal" />
        <!-- <CounterOfferBT :offer_data="offer" :deposit_request="deposit_request" :encoded_deposit_request_id="deposit_request?.request_encypted_id" /> -->

        <ActionMessageBox style="width: 600px;" @closedSuccessModal="showSuccessResponseModal = false" @btnTwoClicked=""
            @btnOneClicked="showSuccessResponseModal = false" icon="/assets/signup/success_promo.svg"
            :title="successModalTitle" btnOneText="" btnTwoText="" :showm="showSuccessResponseModal">
            <div class="ml-5 description-text-withdraw ">{{ successModalTitle }}</div>
        </ActionMessageBox>

    </div>

</template>

<script>
import CounterOffer from '../../../../campaigns/depositor/single-offer/CounterOffer.vue';
import TwoButtonActionMessageBox from '../../../../shared/messageboxes/TwoButtonActionMessageBox.vue';
import OKButtonActionMessageBoxVue from "../../../../shared/messageboxes/NoButtonActionErrorMessageBox.vue";
import CounterOfferB from "./CounterOfferB.vue";
import CounterOfferLogs from '../../../../campaigns/depositor/single-offer/CounterOfferLogs.vue';
import CustomInvitedStatusBadge from "../../../../../components/campaigns/depositor/single-offer/CustomInvitedStatusBadge.vue";
import CounterOfferBT from "../../../../post-requests/sharedComponents/CounterOfferR.vue";

import ActionMessageBox from "../../../../shared/messageboxes/ActionMessageBox.vue";
import ViewCard from '../../../../shared/ViewCard.vue';

export default {
    components: {
        OKButtonActionMessageBoxVue,
        CounterOffer,
        TwoButtonActionMessageBox,
        CounterOfferB,
        CounterOfferLogs,
        CustomInvitedStatusBadge,
        CounterOfferBT,
        ActionMessageBox,
        ViewCard
    },
    mounted() {
        if (this.offer) {
            this.offer_data = JSON.parse(this.offer);
            let offer_data = this.offer_data
            this.term_length = offer_data?.invited?.deposit_request?.term_length_type == 'HISA' ? " - " :
                offer_data?.invited?.deposit_request?.term_length + " " +
                this.ucwords(offer_data?.invited?.deposit_request?.term_length_type.toLowerCase())

        }
        if (this.deposit_request) {
            this.deposit_request_data = JSON.parse(this.deposit_request)
        }
    },
    props: ['offer', 'from_page', 'deposit_request'],
    data() {
        return {
            queryParams: {},
            offer_data: null,
            successModalSize: "md",
            showSuccessModal: false,
            successModalTitle: "",
            showCounterOfferModal: false,
            investment_amount: '',
            submitted: false,
            datum: this.offer_data,
            transError: '',
            hasCounterOffer: false,
            successModalAction: '',
            showResponseModal: false,
            showSuccessResponseModal: false,
            deposit_request_data: null,
            term_length: null,
            lockout_period: null,

        }
    },
    methods: {
        submitClicked() {
            let action = this.successModalAction;
            let counter_offer = this.offer_data?.counter_offers[0];
            let counter_offer_id = counter_offer?.counter_offer_id_encoded;

            axios.get(`/counter-offer/${counter_offer_id}/${action}`).then(response => {
                this.successModalTitle = response?.data.message;
                //this.showResponseModal = true;
                this.showSuccessModal = false;
                this.showSuccessResponseModal = true
                setTimeout(() => {
                    window.location.reload()
                }, 2000)
            }).catch(error => {
                console.log(error);
            })
        },
        cancleClicked() {
            this.showSuccessModal = false
        },
        closeCounterOfferModal(event) {
            this.showCounterOfferModal = event;
        },
        counterOfferSubmit() {
            //console.log('clciked')
            this.showCounterOfferModal = true;
            //console.log(this.showCounterOfferModal)
        },
        counterOfferActions(action) {
            this.successModalAction = action;
            this.successModalTitle = `Are you sure you want to ${action} this counter offer?`
            this.showSuccessModal = true
        },
        formatDateToCustomFormat(inputDate) {
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        addCommas(newvalue) {
            return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        capitalizeText(str) {
            return str[0].toUpperCase() + str.substring(1)
        },
        clickPrevious() {
            if (this.from_page == 'bank-history') {
                window.location.href = "/bank-history";
                return;
            }
            window.location.href = "/in-progress";

        },
        ucwords(str) {
            return str.toLowerCase().replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            })
        }
    },
    computed: {
        hasNoticePeriod() {
            // Using the includes method
            return (this.offer_data?.description?.includes("Notice deposit") || this.offer_data?.description?.includes("Cashable"));
        },
        hasCounter() {
            if (this.offer_data?.counter_offers.length > 0) {
                return true
            }
            return false
        },
        counterOffers() {
            if (this.offer_data?.counter_offers?.length > 0) {
                return this.offer_data?.counter_offers.slice(0, 2);
            }
            return [];
        },
        hasPendingCounterOffer() {
            return this.offer_data?.counter_offers.some(counterOffer => counterOffer.status === 'PENDING');
        }
    }
}
</script>

<style>
.product-info {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    text-transform: capitalize;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0;
    flex: 1;
}

.accept_button {
    border-radius: 32px;
    border: 2px solid #5063F4;
    background: #5063F4;
    color: #FFF;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
    text-transform: capitalize;
    padding: 10px 30px;
}

.counter_button {
    border-radius: 32px;
    background-color: unset;
    border: 2px solid #5063F4;
    color: #5063F4;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
    text-transform: capitalize;
    padding: 10px 30px;
}

.decline_button {
    border-radius: 32px;
    background: #D9D9D9;
    border: 2px solid #D9D9D9;
    color: #FFF;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
    text-transform: capitalize;
    padding: 10px 30px;
}

.previous_button {
    border-radius: 32px;
    background-color: unset;
    border: 2px solid #9CA1AA;
    color: #9CA1AA;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
    text-transform: capitalize;
    padding: 10px 30px;
}

.button_section {
    display: flex;
    margin: 20px;
    justify-content: space-between;
    align-items: center;
    gap: 396px;
    align-self: stretch;
}

.log-container {
    display: flex;
    padding: 10px;
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

.product-data {
    display: flex;
    padding: 0px 10px;
    align-items: flex-start;
    gap: 20px;
}

.fixed_term {
    color: #252525;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.request_amount_cash {
    color: #252525;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.request_amount {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.request_counter_offer {
    color: #252525;
    text-align: center;
    font-family: Montserrat;
    font-size: 30px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    text-decoration: line-through;
}

.request_head {
    color: #252525;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.request_rate {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat;
    font-size: 55px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}
</style>