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
                                <img src="/assets/dashboard/icons/offer-summay-modal-icon.svg" alt="" srcset="">
                            </div>
                            <div class="text-div">Offer Summary</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end header -->
            <div class="row my-2" v-if="offer">
                <div class="col-md-4 col-lg-3">
                    <ShowRateCard :rate_type="offer?.rate_type" :rate_operator="offer?.rate_operator"
                        :interest_rate="offer?.offer_interest_rate" :counter_rate="counter_rate"
                        :variable_rate_value="offer.variable_rate_value" :requireAction="false"
                        :counters="offer.counter_offers" :hasCounters="false">
                    </ShowRateCard>
                </div>

                <div class="col-md-8 col-lg-9">
                    <div class="d-flex flex-column">

                        <div class="pr-deposit-summary-investment">
                            <h4>{{
        repoProductName(offer?.offer_term_length, offer?.offer_term_length_type,
            offer?.product?.product_name),
                                }}</h4>
                            <p class="p-0 m-0 mt-2">
                                {{ offer?.product?.description != null && offer?.product?.description != 'null' ?
        offer?.product?.description : '' }}</p>
                        </div>
                        <div class="d-flex gap-4 flex-wrap mt-2">
                            <ViewCard :inverted="true" title="Min - Max"
                                :desc="request.currency + ' ' + formatNumberAbbreviated(offer?.offer_minimum_amount) + ' - ' + formatNumberAbbreviated(offer?.offer_maximum_amount)" />
                            <ViewCard :inverted="true" title="Term Length"
                                :desc="offer?.offer_term_length + ' ' + capitalize(offer?.offer_term_length_type)" />
                            <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="irctdcc"
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

                </div>
            </div>
            <hr style="height: 0.5px;background: #9CA1AA;margin: 30px 0px;">

            <div class="w-100">
                <div style="width: 100%; margin-top: 10px;" class="row mt-3">

                    <b-tabs content-class="mt-3"
                        nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">

                        <b-tab active :title="'About ' + organization_data?.name">
                            <AboutCGNoModal :organization="organization_data"></AboutCGNoModal>
                        </b-tab>

                        <b-tab title="Counter Offer Change Log">
                            <Table :columns="logcolumns" no-data-title="No Counter Offers"
                                no-data-message=" No counter offers available" :data="log_table_data"
                                :has_action='false' :selectable="false" :is_loading="false" />
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
import Modal from '../../../../shared/Modal.vue';
import { addDaysOrMonths, calculateSettlementLabel, calculateIterestOnDateCountConnvention, formatTimestamp, formatNumberAbbreviated, repoProductName, calculateIterestOnProduct, addCommasAndDecToANumber, getBasketDetails } from '../../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Table from '../../../../shared/Table.vue';
import ShowRateCard from '../../../shared/ShowRateCard.vue';
import AboutCGNoModal from '../../../shared/AboutCGNoModal.vue';


export default {
    components: { InviteCard, ViewCard, Modal, Table, ShowRateCard, AboutCGNoModal },
    props: ['offerIndex', 'show'],
    mounted() {
        if (this.offerIndex != null && this.getAllOffersInReview != null) {
            let offerobj = this.getAllOffersInReview[this.offerIndex]
            this.offer = offerobj.offer
            this.request = offerobj.offer.c_t_trade_request
            // console.log(this.request, "Request")
            this.organization_data = this.offer.invitee.organization
            const counter_offers = this.offer.counter_offers

            // check if is triparty or not

            this.istriparty = this.offer.basket != null
            this.collateral = this.offer?.basket != null
                ? getBasketDetails(this.offer?.basket)
                : getBasketDetails(this.offer?.bi_colleteral, false);

            // check if is triparty or not

            let counter_offer_pld = []
            if (counter_offers?.length > 0) {
                this.hasCounters = true

                let counter_offer_pld = [];
                for (let index = 0; index < counter_offers?.length && index < 5; index++) {
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
            viewMore1: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            collateral: null,
            request: null,
            success: false,
            istriparty: true,
            counter_rate: 0,
            fail: false,
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null,
            offer: null,
            organization_data: null,
            log_table_data: [],
            hasCounters: false,
            requireAction: false,
            amt_awarded: 0,
            total_interest: 0,
            settle_date: null,
            daycount: null,
            repurchase_value: 0,
            logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter']
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate']),

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        calculateSettlementLabel(value) {
            return calculateSettlementLabel(value)
        },
        formatNumberAbbreviated(value) {
            return formatNumberAbbreviated(value)
        },
        repoProductName(x, y, z) {
            return repoProductName(x, y, z)
        },
        formatTimestamp(date, hasdate) {
            return formatTimestamp(date, hasdate)
        },
        addCommasAndDecToANumber(value) {
            return addCommasAndDecToANumber(value)
        },
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
        },

        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts?.length - 1];
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