<template>
    <div>
        <Modal :show="show" @isVisible="$emit('closeModal', $event)" modalsize="xl" v-if="offer">
            <div class="w-100 p-4">
                <!-- header -->
                <div
                    style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                    <div
                        style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                        <div style="">
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
                        </div>
                    </div>
                </div>
                <!-- end header -->
                <div class="row my-2" v-if="offer">
                    <div class="col-md-4 col-lg-3">
                        <ShowRateCard :rate_type="offer?.rate_type" :counters="offer.counter_offers"
                            :rate_operator="offer?.rate_operator" :interest_rate="offer?.offer_interest_rate"
                            :counter_rate="counter_rate" :variable_rate_value="offer.variable_rate_value"
                            :requireAction="requireAction" :hasCounters="hasCounters">
                        </ShowRateCard>
                    </div>

                    <div class="col-md-8 col-lg-9">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div class="d-flex flex-column">

                                <div class="pr-deposit-summary-investment">
                                    <h4>{{
            repoProductName(offer?.offer_term_length, offer?.offer_term_length_type,
                offer?.product?.product_name),
                                        }}</h4>
                                    <p class="p-0 m-0 mt-2">
                                        {{ offer?.product?.description != null ? offer?.product?.description : '' }}</p>
                                </div>
                                <div class="d-flex gap-4 flex-wrap mt-2">
                                    <ViewCard :inverted="true" title="Min - Max"
                                        :desc="request.currency + ' ' + formatNumberAbbreviated(offer?.offer_minimum_amount) + ' - ' + formatNumberAbbreviated(offer?.offer_maximum_amount)" />
                                    <ViewCard :inverted="true" title="Term Length"
                                        :desc="offer?.offer_term_length + ' ' + capitalize(offer?.offer_term_length_type)" />
                                    <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="irdcc"
                                        tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                                        :desc="daycount ? daycount.label : '-'" />
                                    <ViewCard :inverted="true" title="Purchase Value " class="d-none"
                                        :desc="request.currency + ' ' + formatNumberAbbreviated(offer?.offer_minimum_amount)" />
                                    <ViewCard :inverted="true" title="Re-purchase Value" class="d-none"
                                        :desc="request?.currency + ' ' + formatNumberAbbreviated(repurchase_value)" />
                                    <ViewCard :inverted="true" title="Interest Earned" class="d-none"
                                        :desc="request?.currency + ' ' + formatNumberAbbreviated(total_interest)" />

                                    <ViewCard :inverted="true" title="Settlement Date" :desc="settle_date" />
                                </div>
                                <hr>
                                <div class="d-flex gap-4 flex-wrap" v-if="istriparty">
                                    <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                                    <ViewCard :inverted="true" title="Basket Type " :desc="collateral?.name" />
                                    <ViewCard :inverted="true" title="Rating" :desc="collateral?.rating" />
                                    <ViewCard :inverted="true" title="Basket ID" :desc="collateral?.basket_id" />
                                </div>
                                <div class="d-flex gap-4 flex-wrap" v-else>
                                    <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                                    <ViewCard :inverted="true" title="Collateral Type " :desc="collateral?.name" />
                                    <ViewCard :inverted="true" title="Rating" :desc="collateral?.rating" />
                                    <ViewCard :inverted="true" title="CUSIP NO" :desc="collateral?.cucip_code" />
                                </div>

                            </div>
                            <div class="w-100 mt-2">
                                <div class="d-flex justify-content-end gap-2" v-if="hasCounters && requireAction">
                                    <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/withdraw-offer')"
                                        title="Withdraw Offer" :previous="true" @action="withdrawOffer">
                                    </CustomSubmit>
                                    <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/accept-counter')"
                                        title=" Decline Counter" :prevoutline="true" @action="counterAction('decline')">
                                    </CustomSubmit>
                                    <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/give-counter')"
                                        title="Counter Offer" :outline="true" @action="showCounterOffer">
                                    </CustomSubmit>
                                    <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/accept-counter')"
                                        title="Accept Counter" @action="counterAction('accept')">
                                    </CustomSubmit>
                                </div>
                                <div class="d-flex justify-content-end gap-2" v-else>
                                    <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/withdraw-offer')"
                                        title="Withdraw" :outline="true" @action="withdrawOffer">
                                    </CustomSubmit>
                                    <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/edit-offer') && !hasCounters"
                                        class="" title="Edit Offer" @action="editOffer"></CustomSubmit>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr style="height: 0.5px;background: #9CA1AA;margin: 30px 0px;">

                <div class="w-100">
                    <div style="width: 100%; margin-top: 10px;" class="row mt-3">

                        <b-tabs content-class="mt-3"
                            nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                            <!-- <b-tab title="Collateral Basket Details"> -->
                            <!-- <AboutBank :organization_data="organization_data"></AboutBank> -->
                            <!-- </b-tab> -->
                            <b-tab :title="'About ' + organization_data?.name"  active>
                                <AboutCGNoModal :organization="organization_data"></AboutCGNoModal>
                            </b-tab>

                            <b-tab title="Counter Offer Change Log">
                                <Table :columns="logcolumns" no-data-title="No Counter Offers"
                                    no-data-message=" No counter offers available" :data="log_table_data"
                                    :has_action='false' :selectable="false" :is_loading="false" />
                            </b-tab>
                            <!-- <b-tab title="Purchase History">
                                <AboutBank :organization_data="organization_data"></AboutBank>
                            </b-tab> -->
                        </b-tabs>
                    </div>
                </div>
            </div>
        </Modal>
        <WithdrawRequest @closeModal="withdraw_request = false" v-if="withdraw_request" :offer_id="offer_id"
            :show="withdraw_request"></WithdrawRequest>

        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" :title="success_title"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral taker has been notified..</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            :title="fail_title" :showm="fail">
            <div class="ml-5 description-text-withdraw ">{{ fail_desc }}</div>
        </ActionMessage>
    </div>

</template>

<script>
import ViewCard from '../../../../shared/ViewCard.vue';
import InviteCard from '../../../../shared/CustomInvitedStatusBadge.vue';
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'

import AboutBank from '../../../../postrequest/depositor/pendingdeposits/AboutBank.vue'
import Modal from '../../../../shared/Modal.vue';
import { addDaysOrMonths, calculateIterestOnDateCountConnvention, formatNumberAbbreviated, rateTypeCheck, calculateIterestOnProduct, formatTimestamp, addCommasAndDecToANumber, repoProductName, getBasketDetails } from '../../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Table from '../../../../shared/Table.vue';
import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue';
import WithdrawRequest from '../actions/WithdrawRequest.vue';
import AboutCGNoModal from '../../../shared/AboutCGNoModal.vue';
import ShowRateCard from '../../../shared/ShowRateCard.vue';
import { userCan } from '../../../../../utils/GlobalUtils';

export default {
    components: { ShowRateCard, AboutCGNoModal, ActionMessage, InviteCard, ViewCard, AboutBank, Modal, Table, CustomSubmit, WithdrawRequest },
    props: ['offerIndex', 'show', 'userLoggedIn'],
    mounted() {
        if (this.offerIndex != null && this.getAllOffersInReview != null) {
            let offerobj = this.getAllOffersInReview[this.offerIndex]
            this.offer = offerobj.offer
            this.offer_id = this.offer.encoded_id
            this.request = offerobj.offer.c_t_trade_request
            this.settelemntdate = this.getsettlemntdate.find(item => item.id === this.offer.trade_settlement_period_id)
            // console.log(this.request, "Request")
            this.organization_data = this.offer?.c_t_trade_request?.inviter
            const counter_offers = this.offer.counter_offers
            this.counteroffers = counter_offers
            let counter_offer_pld = []

            // check if is triparty or not

            this.istriparty = this.offer.basket != null
            this.collateral = this.offer?.basket != null
                ? getBasketDetails(this.offer?.basket)
                : getBasketDetails(this.offer?.bi_colleteral, false);

            // check if is triparty or not
            if (counter_offers.length > 0) {
                this.latest_counter_offer_id = counter_offers[0].encoded_id
                this.hasCounters = true
                let counter_offer_pld = [];
                for (let index = 0; index < counter_offers.length && index < 5; index++) {
                    const coffer = counter_offers[index];
                    counter_offer_pld.push([
                        coffer.encoded_id,
                        index + 1,
                        formatTimestamp(coffer.created_at, false),
                        this.addCommas(coffer.offer_minimum_amount),
                        coffer.offer_interest_rate.toFixed(2) + "%",
                        (Number.parseFloat(coffer.offer_interest_rate) - Number.parseFloat(this.offer.offer_interest_rate)).toFixed(2) + "%",
                        () => this.renderStatus(coffer.status)
                    ]);
                }
                this.log_table_data = null
                this.log_table_data = counter_offer_pld;
                this.counter_rate = this.log_table_data[0][4]
                this.requireAction = counter_offers[0].status.toLowerCase() == 'pending'
            }
            this.settle_date = this.offer?.settlement_date ? formatTimestamp(this.offer?.settlement_date, false) : '-'
            this.daycount = this.offer?.interest_calculation_option ? this.offer?.interest_calculation_option : null
            this.amt_awarded = Number.parseFloat(this.offer?.offer_minimum_amount)
            if (this.offer.settlement_date && this.offer?.interest_calculation_option) {
                this.total_interest = calculateIterestOnDateCountConnvention(
                    this.offer?.offer_minimum_amount,
                    this.offer?.offer_interest_rate,
                    this.daycount?.slug,
                    this.offer?.settlement_date,
                    this.offer?.offer_term_length,
                    this.offer?.offer_term_length_type,
                );
                this.repurchase_value = this.amt_awarded + Number.parseFloat(this.total_interest)
            }

        }
    },

    data() {
        return {
            latest_counter_offer_id: null,
            hasCounters: false,
            viewMore1: false,
            requireAction: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            request: null,
            counteroffers: null,
            settelemntdate: null,
            settle_date: null,
            istriparty: true,
            collateral: null,
            daycount: null,
            success: false,
            fail: false,
            counter_rate: false,
            success_title: '',
            fail_title: '',
            success_desc: '',
            fail_desc: '',
            withdraw_request: false,
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null,
            offer: null,
            repurchase_value: 0,
            amt_awarded: 0,
            total_interest: 0,
            organization_data: null,
            log_table_data: [],
            logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter']
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate', 'getgloabalproducts']),

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        userCan(a, b) {
            return userCan(a, b)
        },
        formatNumberAbbreviated(value) {
            return formatNumberAbbreviated(value)
        },
        repoProductName(x, y, z) {
            return repoProductName(x, y, z)
        },
        addCommasAndDecToANumber(value) {
            return addCommasAndDecToANumber(value)
        },
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
        },
        rateTypeCheck(x, y, z) {
            return rateTypeCheck(x, y, z)
        },

        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];
            return numberPart
        },
        setPageDefaults() {
            // console.log(this.getAllOffersInReview)
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
                    this.success_title = "Offer withdrawn susscessfully!"
                    this.success_desc = "Your offer has been withdrawn.It will no longer appear to the collateral taker"
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        this.goBack()
                    }, 3000)
                } else {
                    this.fail_title = "Offer not withdrawn !"
                    this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                    this.fail = true
                }
            }).catch(err => {

                this.fail_title = "Offer not withdrawn !"
                this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                this.fail = true
            })
        },
        goBack() {
            window.location.href = '/view-all-in-progress'
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
        async counterAction(action) {
            const data = {
                action: action,
                counterOfferId: this.latest_counter_offer_id
            }
            await axios.post('/trade/CG/respond-counter-offer', data).then(response => {
                if (response.data.success) {
                    if (action == 'accept') {
                        this.success_title = "Counter offer accepted"
                        this.success_desc = "Your have successfully accepted a counter offer from the collateral taker will be notified"
                    } else {
                        this.success_title = "Counter offer declined!"
                        this.success_desc = "Your have successfucly declined a counter offer from the collateral taker will be notified"
                    }
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.reload()
                    }, 3000)

                } else {
                    this.fail_title = "Offer not " + ((action === 'accept') ? 'accepted' : 'declined');
                    this.fail_desc = response.data.message
                    this.fail = true
                }
            }).catch(err => {
                this.fail_title = "Offer not " + ((action === 'accept') ? 'accepted' : 'declined');
                this.fail_desc = 'The counter offer is expired , already accepted , declined or countered'
                // this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                this.fail = true
            });

        },
        showCounterOffer() {
            this.$emit('showCounterOffer', this.offerIndex)
        },
        withdrawOffer() {
            this.withdraw_request = true
        },
        editOffer() {
            this.$emit('showEditOffer', this.offerIndex)

        }



    },
    watch: {
        getAllOffersInReview() {
            console.log("changed")
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

.pr-deposit-summary-investment h4 {
    color: #252525;
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>
<style>
.pr-deposit-summary-investment h4 {
    color: #252525;
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}
</style>