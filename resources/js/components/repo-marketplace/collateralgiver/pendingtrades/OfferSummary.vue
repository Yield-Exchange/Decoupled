<template>
    <div class="w-100 p-4" v-if="offer">
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
                    :interest_rate="offer?.offer_interest_rate" :counter_rate="counter_data"
                    :variable_rate_value="offer.variable_rate_value" :requireAction="requireAction"
                    :hasCounters="hasCounters" :needsRespond="accept_cancel">
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
                        <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="cgpddcc"
                            tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                            :desc="daycount ? daycount.label : '-'" />
                        <ViewCard :inverted="true" title="Purchase Value "
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(amt_awarded)" />
                        <ViewCard :inverted="true" title="Re-purchase Value"
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(repurchase_value)" />
                        <ViewCard :inverted="true" title="Interest Earned"
                            :desc="request?.currency + ' ' + formatNumberAbbreviated(total_interest)" />
                        <ViewCard :inverted="true" title="Settlement Date" :desc="maturity_date" />
                    </div>
                    <hr>
                    <div class="d-flex gap-4 flex-wrap" v-if="istriparty">
                        <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                        <ViewCard :inverted="true" title="Basket Type " :desc="collateral?.name" />
                        <ViewCard :inverted="true" title="Rating"
                            :desc="collateral?.rating ? collateral?.rating : '-'" />
                        <ViewCard :inverted="true" title="Basket ID"
                            :desc="is_dummy ? collateral?.basket_id : collateral?.basket_id" />
                    </div>
                    <div class="d-flex gap-4 flex-wrap" v-else>
                        <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                        <ViewCard :inverted="true" title="Collateral Type " :desc="collateral?.name" />
                        <ViewCard :inverted="true" title="Rating"
                            :desc="collateral?.rating ? collateral?.rating : '-'" />
                        <ViewCard :inverted="true" title="CUSIP NO"
                            :desc="is_dummy ? collateral?.cucip_code : collateral?.cucip_code" />
                    </div>
                </div>

            </div>
            <div class="col-md-12 mt-3">
                <div class="d-flex justify-content-between gap-2">
                    <CustomSubmit title="Previous" :previous="true" @action="goBack" />
                    <div class=" d-flex justify-content-end gap-2">
                        <div v-if="accept_cancel && !i_initated">
                            <CustomSubmit v-if="accept_cancel && userCan(userLoggedIn, 'bank/repos/adjust-repo')"
                                title="Respond Cancellation" :previous="true" @action="start_cancel = true" />
                            <!-- <CustomSubmit v-else title="Cancel Trade" :previous="true" @action="cancelTrade" /> -->
                        </div>
                        <CustomSubmit
                            v-if="!accept_cancel && !i_initated && userCan(userLoggedIn, 'bank/repos/adjust-repo')"
                            title="Cancel Trade" :previous="true" @action="cancelTrade" />
                        <CustomSubmit title="Chat" :outline="true" @action="showchat = true" />
                        <CustomSubmit title="Process"
                            v-if="userCan(userLoggedIn, 'bank/repos/adjust-repo') && userCan(userLoggedIn, 'bank/repos/create-basket')"
                            @action="processOffer" />
                        <!-- <CustomSubmit title="Early Termination" @action="EarlyTermination" /> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3" v-if="organization_data">

                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <!-- <b-tab title="Collateral Basket Details">
                    </b-tab> -->
                    <b-tab active v-if="organization_data" :title="'About ' + organization_data?.name">
                        <AboutBank :organization_data="organization_data" />
                    </b-tab>

                    <b-tab title="Counter Offer Change Log">
                        <Table :columns="logcolumns" no-data-title="No Counter Offers"
                            no-data-message=" No counter offers available for review" :data="log_table_data"
                            :has_action='false' :selectable="false" :is_loading="false" />
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
        <AdjustBasket :defcurrency="request?.currency" :formattedtimezone="formattedtimezone" :depositID="deposit_id"
            v-if="process_offer" :basket="basket" :is_dummy="is_dummy" @closeModal="process_offer = false"
            :show="process_offer" :basket_type="istriparty">
        </AdjustBasket>
        <!-- <ActionMessage style="width: 600px;" @closedSuccessModal="process_offer = false" @btnTwoClicked=""
            @btnOneClicked="process_offer = false" btnOneText="" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="How would you like to process this contract"
            :showm="process_offer">
            <div class="ml-5 description-text-withdraw mt-3">
                <div class=" d-flex flex-column gap-3">
                    <div>
                        <input type="radio" name="process" value="Tri Party" v-model="message" id=""> Tri-Party
                        <Tooltip></Tooltip>
                    </div>
                    <div>
                        <input type="radio" name="process" id="" v-model="message" value="Own channel"> Own Channel
                    </div>

                </div>
                <div class="w-100 mt-3">
                    <div class=" d-flex justify-content-end gap-2">
                        <CustomSubmit title="No" :outline="true" @action="process_offer = false" />
                        <CustomSubmit title="submit" @action="activateDeposit" />
                    </div>
                </div>
            </div>
        </ActionMessage> -->
        <ActionMessage size="md" class="bv-example-row"tyle="width: 600px;" @closedSuccessModal="start_cancel = false" @btnTwoClicked=""
            @btnOneClicked="start_cancel = false" btnOneText="" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="Trade Cancellation Request" :showm="start_cancel">
            <div class="ml-5 description-text-withdraw ">
                <div>The Collateral Taker wishes to cancel this trade. Do you accept their cancellation request? </div>
                <div class="w-100 mt-3">
                    <div class=" d-flex justify-content-end gap-2 w-100">
                        <CustomSubmit title="Decline" :outline="true" @action="repsondToTrade('decline')" />
                        <CustomSubmit title="Approve" @action="repsondToTrade('accept')" />
                    </div>
                </div>

            </div>
        </ActionMessage>
        <ChatComponent from="CG" :reference="reference" :recipient="organization" :deposit_id="deposit_id"
            @closeModal="showchat = false" :show="showchat" v-if="showchat">
        </ChatComponent>
    </div>
</template>

<script>
import ViewCard from '../../../shared/ViewCard.vue';
import InviteCard from '../../../shared/CustomInvitedStatusBadge.vue';
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import ChatComponent from '../../shared/ChatComponent.vue';
import Modal from '../../../shared/Modal.vue';
import { addDaysOrMonthsToDate, calculateIterestOnDateCountConnvention, formatNumberAbbreviated, rateTypeCheck, repoProductName, formatTimestamp, calculateIterestOnProduct, addCommasAndDecToANumber, getBasketDetails } from '../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Table from '../../../shared/Table.vue';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
import AboutBank from '../../../postrequest/bank/pendingdeposits/AboutDepositor.vue'
import CustomSelect from '../../../shared/CustomSelect.vue';
import FormLabelRequired from '../../../shared/formLabels/FormLabelRequired.vue';
import Tooltip from '../../../shared/Tooltip.vue';
import ShowRateCard from '../../shared/ShowRateCard.vue';
import AdjustBasket from './osummary/AdjustBasket.vue';
import { userCan } from '../../../../utils/GlobalUtils';

export default {
    components: { AdjustBasket, ShowRateCard, ChatComponent, CustomSelect, FormLabelRequired, ActionMessage, InviteCard, ViewCard, AboutBank, Modal, Table, CustomSubmit, Tooltip },
    props: ['offerIndex', 'show', 'userLoggedIn'],
    beforeMount() {
        this.getUrlPArams()
        this.getTimezone()
    },
    mounted() {
        this.setPageDefaults()
    },

    data() {
        return {
            latest_counter_offer_id: null,
            hasCounters: false,
            viewMore1: false,
            organization_data: null,
            organization: null,
            offer_data: null,
            offer_id: null,
            request: null,
            counteroffers: null,
            settelemntdate: null,
            deposit_id: null,
            success: false,
            fail: false,
            // show: false,
            showchat: false,
            success_title: '',
            fail_title: '',
            success_desc: '',
            deposit_status: null,
            fail_desc: '',
            withdraw_request: false,
            deposit_request: null,
            withdrawpromt: false,
            process_offer: false,
            fromPage: null,
            message: null,
            offer: null,
            log_table_data: [],
            currency_to_use: 'CAD',
            amt_awarded: 0,
            total_interest: 0,
            counter_data: 0,
            maturity_date: 0,
            logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter'],
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
            accept_cancel: false,
            batch_no: false,
            requireAction: false,
            i_initated: false,
            current_organization: null,
            reference: null,
            start_cancel: false,
            collateral: null,
            istriparty: false,
            is_dummy: false,
            basket: null,
            formattedtimezone: null,
            settle_date: null,
            daycount: null,
            repurchase_value: 0,


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
        getTimezone() {
            axios.get('/get-formated-timezone').then(res => {
                this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        formatNumberAbbreviated(value) {
            return formatNumberAbbreviated(value)
        },
        repoProductName(x, y, z) {
            return repoProductName(x, y, z)
        },
        cancelTrade() {
            this.withdraw_request = true
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
            })
        },
        setPageDefaults() {
            if (this.deposit != null) {
                this.reference = this.deposit?.deposit_reference_no

                let offerobj = this.deposit.c_g_offer
                this.current_organization = this.deposit.c_g_organization.id
                this.organization = this.deposit?.c_t_organization
                this.deposit_id = this.deposit?.encoded_id
                this.deposit_status = this.deposit?.deposit_status
                if (this.deposit_status == 'INITIATED') {
                    let latest_trade_event = this.deposit?.latest_trade_event[0]
                    if (latest_trade_event.event_type == 'cancelletion') {
                        this.accept_cancel = true
                        this.batch_no = latest_trade_event.batch_no
                        this.i_initated = this.current_organization == latest_trade_event.initiating_organization_id
                    }
                }
                this.offer = offerobj
                this.offer_id = this.offer?.encoded_id
                this.request = offerobj?.c_t_trade_request

                // check if is triparty or not

                this.istriparty = this.offer.basket != null
                if (this.istriparty) {
                    this.basket = this.offer?.basket
                } else {
                    this.basket = this.offer?.bi_colleteral
                }

                this.is_dummy = this.basket.is_dummy
                this.collateral = this.offer?.basket != null
                    ? getBasketDetails(this.offer?.basket)
                    : getBasketDetails(this.offer?.bi_colleteral, false);

                // check if is triparty or not

                this.organization_data = this.deposit?.c_t_organization
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
                // let settdate = addDaysOrMonthsToDate(, this.offer.trade_settlement_period_id, 'days', false)
                this.maturity_date = this.offer.settlement_date ? formatTimestamp(this.offer.settlement_date, false) : '-'
                // addDaysOrMonthsToDate(settdate, this.offer?.offer_term_length, this.offer?.offer_term_length_type, true)

                // console.log(this.maturity_date)
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
                            this.formatNumberAbbreviated(coffer?.offer_minimum_amount),
                            coffer?.offer_interest_rate.toFixed(2) + "%",
                            (Number.parseFloat(coffer?.offer_interest_rate) - Number.parseFloat(this.offer?.offer_interest_rate)).toFixed(2) + "%",
                            () => this.renderStatus(coffer?.status)
                        ]);
                    }
                    this.log_table_data = null;
                    this.log_table_data = counter_offer_pld;
                    this.counter_data = this.log_table_data[0][4]
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
            window.location.href = "/repos/cg-repos-pending-trades"
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

            let formdata = new FormData()
            formdata.append('depositID', this.deposit_id)
            formdata.append('event_type', 'cancel')
            formdata.append('reason', this.selected_reason)
            axios.post('/trade/CG/post-trade-events', formdata).then(response => {
                if (response.data.success) {
                    this.success_title = "Trade Cancellation Request Submitted "
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.reload()
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
        async activateDeposit() {

            let formdata = new FormData()

            formdata.append('depositId', this.deposit_id)
            formdata.append('message', 'cancel')
            formdata.append('actual_trade_date', this.request.trade_time.split(' ')[0])
            axios.post('/trade/CG/activate-trade', formdata).then(response => {
                if (response.data.success) {
                    this.success_title = "The repo has been activated"
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        this.goBack()
                    }, 3000)

                } else {
                    this.fail_title = "Ooops! repo activation has failed"
                    this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                    this.fail = true

                }
            }).catch(err => {
                this.fail_title = "Ooops! repo activation has failed"
                this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                this.fail = true
            })
        },
        async repsondToTrade(value) {

            let formdata = new FormData()
            // formdata.append('depositID', this.deposit_id)
            formdata.append('action', value)
            formdata.append('batchNo', this.batch_no)
            axios.post('/trade/CG/respond-on-trade-event', formdata).then(response => {
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