<template>
    <Modal :show="show" @isVisible="$emit('closeModal', $event)" modalsize="xl" v-if="offer">
        <div class="w-100 p-4">
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
                    </div>
                </div>
            </div>
            <!-- end header -->
            <div class="row my-2" v-if="offer">
                <div class="col-md-4 col-lg-3">
                    <div
                        style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
                        <div
                            style="height: 42px; text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                            {{ offer?.offer_interest_rate.toFixed(2) }}%</div>
                        <div style="width: 128px; height: 0px; border: 0.51px #9CA1AA solid ;margin-top: 20px;"></div>
                        <div
                            style="flex-direction: column; justify-content: center; align-items: center; display: flex">
                            <div
                                style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Term Length</div>
                            <div
                                style="color: #5063F4; font-size: 20px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                {{ offer?.offer_term_length + " " + capitalize(offer.offer_term_length_type) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-lg-9">
                    <div class="d-flex flex-column">

                        <div class="pr-deposit-summary-investment">
                            <h4 style="font-size: 16px;font-style: normal;font-weight: 700;">{{
        offer?.product?.product_name }}</h4>
                            <p class="p-0 m-0 mt-2">
                                A fixed-term investment with a locked interest rate, providing steady and predictable
                                earningsover the investment period.</p>
                        </div>
                        <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                            <ViewCard title="Purchase Value " desc="Short Desc" />
                            <ViewCard title="Re-purchase Value" desc="Short Desc" />
                            <ViewCard title="Interest Earned" desc="Short Desc" />
                            <ViewCard title="Settlement Date" :desc="addDaysOrMonths(request?.trade_time, Number.parseInt(offer.trade_settlement_period_id
    ), 'days')" />
                        </div>
                        <hr>
                        <div class="d-flex gap-4 flex-wrap mt-2 p-3">
                            <ViewCard title="Basket Id " :desc="offer?.basket?.basket_id" />
                            <ViewCard title="Type" desc="Short Desc" />
                            <ViewCard title="Value" desc="Short Desc" />
                            <ViewCard title="Rating" desc="Short Desc" />
                            <ViewCard title="Expiry" desc="Short Desc" />
                        </div>

                    </div>

                </div>
            </div>

            <div class="w-100">
                <div style="width: 100%; margin-top: 10px;" class="row mt-3">

                    <b-tabs content-class="mt-3"
                        nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                        <b-tab title="Collateral Basket Details">
                            <!-- <AboutBank :organization_data="organization_data"></AboutBank> -->
                        </b-tab>
                        <b-tab :title="'About ' + organization_data?.name">
                            <AboutBank :organization_data="organization_data"></AboutBank>
                        </b-tab>
                        <b-tab title="Collateral Basket Details">
                            <!-- <AboutBank :organization_data="organization_data"></AboutBank> -->
                        </b-tab>
                        <b-tab title="Counter Offer Change Log" active>
                            <Table :columns="logcolumns" no-data-title="No Offers"
                                no-data-message=" No offers available for review" :data="log_table_data"
                                :has_action='false' :selectable="false" :is_loading="false" />
                        </b-tab>
                        <b-tab title="Purchase History">
                            <!-- <AboutBank :organization_data="organization_data"></AboutBank> -->
                        </b-tab>
                    </b-tabs>
                </div>
            </div>
        </div>
    </Modal>

</template>

<script>
import ViewCard from '../../../../shared/ViewCard.vue';
import InviteCard from '../../../../shared/CustomInvitedStatusBadge.vue';
import AboutBank from '../../../../postrequest/depositor/pendingdeposits/AboutBank.vue'
import Modal from '../../../../shared/Modal.vue';
import { addDaysOrMonths, formatTimestamp } from '../../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Table from '../../../../shared/Table.vue';


export default {
    components: { InviteCard, ViewCard, AboutBank, Modal, Table },
    props: ['offerIndex', 'show'],
    mounted() {
        if (this.offerIndex != null && this.getAllOffersInReview != null) {
            let offerobj = this.getAllOffersInReview[this.offerIndex]
            this.offer = offerobj.offer
            this.request = offerobj.offer.c_t_trade_request
            // console.log(this.request, "Request")
            this.organization_data = this.offer.invitee.organization
            const counter_offers = this.offer.counter_offers
            let counter_offer_pld = []
            if (counter_offers.length > 0) {
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
                this.log_table_data = counter_offer_pld;
            }

        }
    },

    data() {
        return {
            viewMore1: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            request: null,
            success: false,
            fail: false,
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null,
            offer: null,
            organization_data: null,
            log_table_data: null,
            logcolumns: ['#', 'Counter Offer Date', 'Investment Amount', 'Counter Rate', 'Rate Change', 'Counter']
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview']),

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        // addDaysOrMonths(date, daystoadd = 1) {
        //     return addDaysOrMonths(date, daystoadd)
        // },
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
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

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>