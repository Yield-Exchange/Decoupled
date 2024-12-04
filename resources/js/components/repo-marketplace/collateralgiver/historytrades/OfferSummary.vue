<template>
    <div class="w-100 p-4">
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div>
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
                        <div class="text-div">Trade Summary</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-2" v-if="true">
            <div class="col-md-4 col-lg-3">
                <ShowRateCard :rate_type="offer?.rate_type" :rate_operator="offer?.rate_operator"
                    :interest_rate="offer?.offer_interest_rate" :counter_rate="0"
                    :variable_rate_value="offer.variable_rate_value" :requireAction="false" :hasCounters="false">
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
                            {{ offer?.product?.description != null ? offer?.product?.description : '' }}</p>
                    </div>
                    <div class="d-flex gap-4 flex-wrap mt-2">
                        <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="cgwaddcc"
                            tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations." :desc="daycount ? daycount.label : '-'" />
                        <ViewCard :inverted="true" title="Purchase Value "
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(amt_awarded)" />
                        <ViewCard :inverted="true" title="Re-purchase Value"
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(repurchase_value)" />
                        <ViewCard :inverted="true" title="Interest Earned"
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
            <div class="col-md-12 mt-3">
                <div class="d-flex justify-content-between gap-2">
                    <CustomSubmit title="Previous" :previous="true" @action="goBack" />
                </div>
            </div>
        </div>

        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">

                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <!-- <b-tab title="Collateral Basket Details">
                    </b-tab> -->
                    <b-tab :title="'About ' + organization_data?.name" active>
                        <AboutBank :organization_data="organization_data" />
                    </b-tab>

                    <b-tab title="Counter Offer Change Log">
                        <Table :columns="logcolumns" no-data-title="No Counter Offers"
                            no-data-message=" No counter offers available" :data="log_table_data" :has_action='false'
                            :selectable="false" :is_loading="false" />
                    </b-tab>
                    <!-- <b-tab title="Purchase History">
                    </b-tab> -->
                </b-tabs>
            </div>
        </div>



        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" :title="success_title"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            :title="fail_title" :showm="fail">
            <div class="ml-5 description-text-withdraw ">{{ fail_desc }}</div>
        </ActionMessage>
        <ActionMessage size="lg" style="width: 600px;" @closedSuccessModal="withdraw_request = false" @btnTwoClicked=""
            @btnOneClicked="withdraw_request = false" btnOneText="" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="Do you want to terminate the awarded repo?"
            :showm="withdraw_request">
            <div class="ml-5 description-text-withdraw ">
                <b-row style="margin-top: 30px;width:100%;padding: 0px !important; margin-top: 15px;">
                    <b-col md="12" class="align-items-left "
                        style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                        <FormLabelRequired labelText="Reason for terminating  the offer" required="true"
                            showHelperText="true" helperText="Reason For Withdrawing the request"
                            helperId="reasonforwith" />
                        <CustomSelect :attributes="{ 'value_field': 'reason', 'text_field': 'combined' }"
                            p-style="width: 100%;"
                            c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                            :data="terminationReasons" id="reason_for_withdraw" name="Reason for Withdraw*"
                            :has-validation="false" :default-value="reason_for_termination"
                            v-model="reason_for_termination" @selectChanged="withdrawReasonChange($event)" />
                    </b-col>
                </b-row>
                <div class="w-100 mt-3">
                    <div class=" d-flex justify-content-end gap-2">
                        <CustomSubmit title="No" :outline="true" @action="withdraw_request = false" />
                        <CustomSubmit title="Yes" @action="doTerminate" />
                    </div>
                </div>

            </div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="process_offer = false" @btnTwoClicked=""
            @btnOneClicked="process_offer = false" btnOneText="" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="How would you like to process this contract"
            :showm="process_offer">
            <div class="ml-5 description-text-withdraw mt-3">
                <div class=" d-flex flex-column gap-3">
                    <div>
                        <input type="radio" name="process" id=""> Tri-Party <Tooltip></Tooltip>
                    </div>
                    <div>
                        <input type="radio" name="process" id=""> Own Channel
                    </div>

                </div>
                <div class="w-100 mt-3">
                    <div class=" d-flex justify-content-end gap-2">
                        <CustomSubmit title="No" :outline="true" @action="process_offer = false" />
                        <CustomSubmit title="submit" @action="doTerminate" />
                    </div>
                </div>
            </div>
        </ActionMessage>
    </div>
</template>

<script>
import ViewCard from '../../../shared/ViewCard.vue';
import InviteCard from '../../../shared/CustomInvitedStatusBadge.vue';
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'

import Modal from '../../../shared/Modal.vue';
import { addDaysOrMonthsToDate, formatNumberAbbreviated, calculateIterestOnDateCountConnvention, repoProductName, formatTimestamp, calculateIterestOnProduct, addCommasAndDecToANumber, getBasketDetails } from '../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Table from '../../../shared/Table.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
import AboutBank from '../../../postrequest/bank/pendingdeposits/AboutDepositor.vue'
import CustomSelect from '../../../shared/CustomSelect.vue';
import FormLabelRequired from '../../../shared/formLabels/FormLabelRequired.vue';
import Tooltip from '../../../shared/Tooltip.vue';
import ShowRateCard from '../../shared/ShowRateCard.vue';

export default {
    components: { ShowRateCard, CustomSelect, FormLabelRequired, ActionMessage, InviteCard, ViewCard, AboutBank, Modal, Table, CustomSubmit, Tooltip },
    props: ['offerIndex', 'show'],
    beforeMount() {
        this.getUrlPArams()
    },
    mounted() {
        this.setPageDefaults()
    },

    data() {
        return {
            daycount: null,
            repurchase_value: 0,
            settle_date: null,
            latest_counter_offer_id: null,
            hasCounters: false,
            viewMore1: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            request: null,
            counteroffers: null,
            settelemntdate: null,
            success: false,
            fail: false,
            success_title: '',
            fail_title: '',
            success_desc: '',
            fail_desc: '',
            withdraw_request: false,
            deposit_request: null,
            withdrawpromt: false,
            process_offer: false,
            fromPage: null,
            offer: null,
            log_table_data: [],
            currency_to_use: 'CAD',
            amt_awarded: 0,
            total_interest: 0,
            maturity_date: 0,
            logcolumns: ['#', 'Counter Offer Date', 'Investment Amount', 'Counter Rate', 'Rate Change', 'Counter'],
            deposit: null,
            reason_for_termination: 'Mutual Agreement',
            terminationReasons: [
                {
                    reason: "Mutual Agreement",
                    description: "Both parties agree to end the Repo early.",
                    combined: "Mutual Agreement: Both parties agree to end the Repo early."
                },
                {
                    reason: "Default on Repurchase",
                    description: "Failure to repurchase the securities on the agreed terms.",
                    combined: "Default on Repurchase: Failure to repurchase the securities on the agreed terms."
                },
                {
                    reason: "Breach of Contract",
                    description: "Violation of any contractual terms (e.g., regarding collateral quality).",
                    combined: "Breach of Contract: Violation of any contractual terms (e.g., regarding collateral quality)."
                },
                {
                    reason: "Market Volatility",
                    description: "Significant changes in market conditions affecting security values.",
                    combined: "Market Volatility: Significant changes in market conditions affecting security values."
                },
                {
                    reason: "Regulatory Changes",
                    description: "New laws or regulations make the Repo untenable or less attractive.",
                    combined: "Regulatory Changes: New laws or regulations make the Repo untenable or less attractive."
                },
                {
                    reason: "Liquidity Needs",
                    description: "Termination due to urgent liquidity requirements.",
                    combined: "Liquidity Needs: Termination due to urgent liquidity requirements."
                },
                {
                    reason: "Counterparty Risk",
                    description: "Concerns about the financial stability or reliability of the other party.",
                    combined: "Counterparty Risk: Concerns about the financial stability or reliability of the other party."
                },
                {
                    reason: "Other",
                    description: "Any other unforeseen reasons not listed above.",
                    combined: "Other: Any other unforeseen reasons not listed above."
                }
            ],
            selected_reason: null,
            collateral: null,
            istriparty: false,
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate', 'getgloabalproducts']),

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
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

        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];

            axios.get('/trade/CG/get-pending-deposit?depositId=' + numberPart).then(res => {
                // console.log(res.data)
                this.deposit = res.data
            }).catch(err => {
                console.log(err, "Trade")
            })
        },
        setPageDefaults() {
            if (this.deposit != null) {
                let offerobj = this.deposit.c_g_offer

                this.offer = offerobj
                this.offer_id = this.offer?.encoded_id
                this.request = offerobj?.c_t_trade_request
                // this.settelemntdate = this.getsettlemntdate.find(item => item.id === this.offer?.trade_settlement_period_id)
                // check if is triparty or not

                this.istriparty = this.offer.basket != null
                this.collateral = this.offer?.basket != null
                    ? getBasketDetails(this.offer?.basket)
                    : getBasketDetails(this.offer?.bi_colleteral, false);

                // check if is triparty or not
                // console.log(this.request, "Request")
                this.organization_data = this.deposit?.c_t_organization
                const counter_offers = this.offer?.counter_offers
                this.counteroffers = counter_offers
                this.amt_awarded = this.deposit.offered_amount
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
                let settdate = addDaysOrMonthsToDate(this.request.trade_time, this.offer.trade_settlement_period_id, 'days', false)
                this.maturity_date = addDaysOrMonthsToDate(settdate, this.offer?.offer_term_length, this.offer?.offer_term_length_type, true)

                console.log(this.maturity_date)
                let counter_offer_pld = []
                if (counter_offers.length > 0) {
                    this.latest_counter_offer_id = counter_offers[0].encoded_id
                    this.hasCounters = true
                    let counter_offer_pld = [];
                    for (let index = 0; index < counter_offers.length && index < 5; index++) {
                        const coffer = counter_offers[index];
                        counter_offer_pld.push([
                            coffer?.encoded_id,
                            index + 1,
                            formatTimestamp(coffer?.created_at, false),
                            this.addCommas(coffer?.offer_minimum_amount),
                            coffer?.offer_interest_rate.toFixed(2) + "%",
                            (Number.parseFloat(coffer?.offer_interest_rate) - Number.parseFloat(this.offer?.offer_interest_rate)).toFixed(2) + "%",
                            () => this.renderStatus(coffer?.status)
                        ]);
                    }
                    this.log_table_data = []
                    this.log_table_data = counter_offer_pld;
                }

            }
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

        goBack() {
            window.location.href = "/repos/cg-repos-history-trades"
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }

        },

        Chat() {

        },
        processOffer() {
            this.process_offer = true
        },
        EarlyTermination() {
            this.withdraw_request = true
        },
        withdrawReasonChange(value) {
            // console.log(value)
            const reason = this.terminationReasons.find(item => item.reason == value)
            this.selected_reason = reason.combined
        },
        async doTerminate() {
            const data = {
                offerId: this.offer_id,
                reason: this.selected_reason
            }
            await axios.post('/', data).then(response => {
                console.log(response.data)
            }).catch(err => {
                console.log(err)
            })
        }





    },
    watch: {
        deposit() {
            this.setPageDefaults()
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

<style scoped>
input[type=radio] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=radio]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=radio]:checked::before {
    transform: scale(1);
}

input[type=radio]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}
</style>