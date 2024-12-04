<template>
    <div class="w-100 p-4">
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/trade-summary.svg" alt="" srcset="">                           
                        </div>
                        <div class="text-div">Trade Summary</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-2" v-if="offer">
            <div class="col-md-4 col-lg-3">
                <ShowRateCard :rate_type="offer?.rate_type" :rate_operator="offer?.rate_operator"
                    :interest_rate="offer?.offer_interest_rate" :counter_rate="0"
                    :variable_rate_value="offer.variable_rate_value" :requireAction="requireAction"
                    :hasCounters="hasCounters">
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
                        <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="cgaddcc"
                            tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                            :desc="daycount ? daycount.label : '-'" />
                        <ViewCard :inverted="true" title="Purchase Value "
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(amt_awarded)" />
                        <ViewCard :inverted="true" title="Re-purchase Value"
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(repurchase_value)" />
                        <ViewCard :inverted="true" title="Interest Earned"
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(total_interest)" />
                        <ViewCard :inverted="true" title="Maturity Date" :desc="maturity_date" />
                    </div>
                    <hr class="w-100">
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
                    <div class=" d-flex justify-content-end gap-2">
                        <div v-if="accept_cancel && !i_initated">
                            <CustomSubmit v-if="accept_cancel" title="Respond" :outline="true"
                                @action="start_cancel = true" />
                            <!-- <CustomSubmit v-else title="Cancel Trade" :outline="true" @action="cancelTrade" /> -->
                        </div>
                        <!-- <CustomSubmit v-if="!accept_cancel && !i_initated" title="Cancel Trade" :outline="true"
                            @action="cancelTrade" /> -->
                        <CustomSubmit title="Chat" @action="start_conv = true" />
                        <!-- <CustomSubmit title="Adjustment" @action="adjustDeposit" /> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3">

                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <!-- <b-tab class="d-none" title="Collateral Basket Details">
                    </b-tab> -->
                    <b-tab active :title="'About ' + organization_data?.name">
                        <AboutBank :organization_data="organization_data" />
                    </b-tab>

                    <b-tab title="Counter Offer Change Log">
                        <Table :columns="logcolumns" no-data-title="No Counter Offers"
                            no-data-message=" No offers available for review" :data="log_table_data" :has_action='false'
                            :selectable="false" :is_loading="false" />
                    </b-tab>
                    <!-- <b-tab class="d-none" title="Purchase History">
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
        <Adjustment @closeModal="adjust_deposit = false" v-if="adjust_deposit" from="active" :deposit="deposit"
            :show="adjust_deposit" />
        <ActionMessage size="md" style="width: 600px;" @closedSuccessModal="withdraw_request = false" @btnTwoClicked=""
            @btnOneClicked="withdraw_request = false" btnOneText="" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="Are you sure you want to cancel the trade?"
            :showm="withdraw_request">
            <div class="ml-5 description-text-withdraw ">
                <b-row style="margin-top: 30px;width:100%;padding: 0px !important; margin-top: 15px;">
                    <b-col md="12" class="align-items-left "
                        style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                        <FormLabelRequired labelText="Reason for cancelling the trade?" :required="false"
                            showHelperText="true" helperText="Reason For Withdrawing the request"
                            helperId="reasonforwith" />
                        <CustomSelect :attributes="{ 'value_field': 'reason', 'text_field': 'reason' }"
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
        <ActionMessage size="md" style="width: 600px;" @closedSuccessModal="start_cancel = false" @btnTwoClicked=""
            @btnOneClicked="start_cancel = false" btnOneText="" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="Trade Event" :showm="start_cancel">
            <div class="ml-5 description-text-withdraw ">
                <div>The Collateral Giver wishes to cancel this trade. Do you accept their cancellation request? </div>
                <div class="w-100 mt-3">
                    <div class=" d-flex justify-content-end gap-2">
                        <CustomSubmit title="No" :outline="true" @action="repsondToTrade('decline')" />
                        <CustomSubmit title="Yes" @action="repsondToTrade('accept')" />
                    </div>
                </div>

            </div>
        </ActionMessage>
        <ChatComponent :reference="reference" from="CT" :recipient="organization" :deposit_id="deposit_id"
            @closeModal="start_conv = false" :show="start_conv" v-if="start_conv" />
    </div>
</template>

<script>
import ViewCard from '../../../shared/ViewCard.vue';
import InviteCard from '../../../shared/CustomInvitedStatusBadge.vue';
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import ChatComponent from '../../shared/ChatComponent.vue';
// CustomInput
import AboutBank from '../../../postrequest/depositor/pendingdeposits/AboutBank.vue'
import Modal from '../../../shared/Modal.vue';
import { addDaysOrMonthsToDate, calculateIterestOnDateCountConnvention, formatTimestamp, formatNumberAbbreviated, repoProductName, calculateIterestOnProduct, addCommasAndDecToANumber, getBasketDetails } from '../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Adjustment from './Adjustment.vue';
import Table from '../../../shared/Table.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
import CustomSelect from '../../../shared/CustomSelect.vue';
import FormLabelRequired from '../../../shared/formLabels/FormLabelRequired.vue';
import ShowRateCard from '../../shared/ShowRateCard.vue';


export default {
    components: { ChatComponent, ShowRateCard, Adjustment, CustomSelect, FormLabelRequired, ActionMessage, InviteCard, ViewCard, AboutBank, Modal, Table, CustomSubmit },
    props: ['offerIndex', 'show'],
    beforeMount() {
        this.getUrlPArams()
    },
    mounted() {
        this.setPageDefaults()
    },

    data() {
        return {
            latest_counter_offer_id: null,
            hasCounters: false,
            start_conv: false,
            viewMore1: false,
            adjust_deposit: false,
            organization_data: null,
            organization: null,
            offer_data: null,
            offer_id: null,
            deposit_id: null,
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
            requireAction: false,
            fromPage: null,
            offer: null,
            organization_data: null,
            log_table_data: [],
            reference: null,
            currency_to_use: 'CAD',
            amt_awarded: 0,
            total_interest: 0,
            reason_for_termination: null,
            maturity_date: 0,
            settle_date: null,
            daycount: null,
            repurchase_value: 0,
            logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter'],
            deposit: null,
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
            accept_cancel: false,
            batch_no: false,
            i_initated: false,
            current_organization: null,
            start_cancel: false,
            collateral: null,
            istriparty: false
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
        cancelTrade() {
            // console.log("Offer terminated",)
            this.withdraw_request = true
        },

        addCommasAndDecToANumber(value) {
            return addCommasAndDecToANumber(value)
        },
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
        },
        async doTerminate() {

            let formdata = new FormData()
            formdata.append('depositID', this.deposit_id)
            formdata.append('event_type', 'early_termination')
            formdata.append('early_terminate_reason', this.selected_reason)
            axios.post('/trade/CT/post-trade-events', formdata).then(response => {
                if (response.data.success) {
                    this.success_title = "Trade Cancellation Request Submitted "
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        this.goBack()
                    }, 3000)

                } else {
                    this.fail_title = "Ooops! Cancel Request not submitted"
                    this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                    this.fail = true

                }
            }).catch(err => {
                this.fail_title = "Ooops! Cancel Request not submitted"
                this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                this.fail = true
            })
        },

        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];

            axios.get('/trade/CT/get-pending-deposit?depositId=' + numberPart).then(res => {
                // console.log(res.data)
                this.deposit = res.data
                this.deposit_id = numberPart
            }).catch(err => {
                console.log(err, "Trade")
            })
        },
        setPageDefaults() {
            if (this.deposit != null) {
                let offerobj = this.deposit.c_g_offer
                this.reference = this.deposit.deposit_reference_no

                this.organization = this.deposit?.c_g_organization
                this.current_organization = this.deposit.c_t_organization.id
                this.deposit_status = this.deposit?.deposit_status
                if (this.deposit_status == 'INITIATED') {
                    let latest_trade_event = this.deposit?.latest_trade_event[0]
                    if (latest_trade_event.event_type == 'cancelletion') {
                        this.accept_cancel = true
                        this.batch_no = latest_trade_event.batch_no
                        this.i_initated = this.current_organization == latest_trade_event.initiating_organization_id
                        // console.log(this.i_initated, "User Initiated")
                    }
                }

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
                this.organization_data = this.offer?.invitee.organization
                const counter_offers = this.offer?.counter_offers
                this.counteroffers = counter_offers
                this.settle_date = this.offer?.settlement_date ? formatTimestamp(this.offer?.settlement_date, false) : '-'
                this.daycount = this.offer?.interest_calculation_option ? this.offer?.interest_calculation_option : null
                this.amt_awarded = Number.parseFloat(this.deposit?.offered_amount)
                if (this.offer.settlement_date && this.offer?.interest_calculation_option) {
                    this.total_interest = calculateIterestOnDateCountConnvention(
                        this.deposit?.offered_amount,
                        this.offer?.offer_interest_rate,
                        this.daycount?.slug,
                        this.offer?.settlement_date,
                        this.offer?.offer_term_length,
                        this.offer?.offer_term_length_type,
                    );
                    this.repurchase_value = this.amt_awarded + Number.parseFloat(this.total_interest)
                }
                // let settdate = addDaysOrMonthsToDate(this.request.trade_time, this.offer.trade_settlement_period_id, 'days', false)
                // this.maturity_date = this.deposit?.trade_date ? addDaysOrMonthsToDate(this.deposit?.trade_date, this.offer?.offer_term_length, this.offer?.offer_term_length_type, true) : '-'
                this.maturity_date = this.deposit?.maturity_date ? formatTimestamp(this.deposit?.maturity_date, false) : '-'

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
                    this.log_table_data = null;
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
            window.location.href = "/repos/ct-repos-active-trades"
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
        adjustDeposit() {
            // console.log("Offer terminated",)
            this.adjust_deposit = true
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
            await axios.post('/trade/CT/early-terminate', data).then(response => {
                console.log(response.data)
            }).catch(err => {
                console.log(err)
            })
        },
        async repsondToTrade(value) {

            let formdata = new FormData()
            // formdata.append('depositID', this.deposit_id)
            formdata.append('action', value)
            formdata.append('batchNo', this.batch_no)
            axios.post('/trade/CT/respond-on-trade-event', formdata).then(response => {
                if (response.data.success) {
                    this.success_title = "Request has been submitted"
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        if (value == 'accept')
                            this.goBack()
                        else
                            window.location.reload()
                    }, 3000)

                } else {
                    this.fail_title = "Ooops!  Request not submitted"
                    this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                    this.fail = true

                }
            }).catch(err => {
                this.fail_title = "Ooops!  Request not submitted"
                this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                this.fail = true
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