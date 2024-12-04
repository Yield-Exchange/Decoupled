<template>
    <div class="w-100 p-4">
        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/offer-summary-repos.svg" alt="Title Icon">
                        </div>
                        <div class="text-div"> Offer Summary</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-2" v-if="offer">
            <div class="col-md-4 col-lg-3">
                <ShowRateCard :rate_type="offer?.rate_type" :rate_operator="offer?.rate_operator"
                    :interest_rate="offer?.offer_interest_rate" :counter_rate="counter_rate"
                    :counter_rate_operator="counter_rate_operator" :counter_rate_type="counter_rate_type"
                    :counter_rate_spread_rate_value="counter_rate_spread_rate_value"
                    :counter_rate_applied_prime="counter_rate_applied_prime"
                    :variable_rate_value="offer?.variable_rate_value" :requireAction="false"
                    :counters="offer.counters"
                    :hasCounters="hasCounters">
                </ShowRateCard>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="d-flex flex-column">

                    <div class="pr-deposit-summary-investment">
                        <h4>{{
                            offer?.offer_term_length_type ?
                            repoProductName(offer?.offer_term_length, offer?.offer_term_length_type,
                            offer?.product?.product_name) : '-',
                            }}</h4>
                        <p class="p-0 m-0 mt-2">
                            {{ offer?.product?.description != null ? offer?.product?.description : '' }}
                        </p>

                    </div>
                    <div class="d-flex gap-4 flex-wrap mt-2">
                        <ViewCard :inverted="true" title="Minimum"
                            :desc="offer?.currency + ' ' + addCommas(offer?.offer_minimum_amount) + ' (' + formatNumberAbbreviated(offer?.offer_minimum_amount) + ')'" />
                        <ViewCard :inverted="true" title="Maximum "
                            :desc="offer?.currency + ' ' + addCommas(offer?.offer_maximum_amount) + ' (' + formatNumberAbbreviated(offer?.offer_maximum_amount) + ')'" />
                        <ViewCard :inverted="true" title="Term Length "
                            :desc="offer?.offer_term_length + ' ' + capitalize(offer?.offer_term_length_type)" />
                        <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="ctpddcc"
                            tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                            :desc="offer?.interest_calculation_option ? offer?.interest_calculation_option?.label : '-'" />
                        <ViewCard :inverted="true" title="Offer Valid Till"
                            :desc="offer?.rate_valid_until ? formatTimestamp(offer?.rate_valid_until, false) : '-'" />
                    </div>
                    <hr class="w-100">
                    <div class="d-flex gap-4 flex-wrap" v-if="istriparty">
                        <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                        <ViewCard :inverted="true" title="Basket Type "
                            :desc="offer?.basket?.basket_details?.trade_basket_type.basket_name" />
                        <ViewCard :inverted="true" title="Rating" :desc="offer?.basket?.basket_details.rating" />
                        <ViewCard :inverted="true" title="Basket ID" :desc="offer?.basket?.basket_id" />
                    </div>
                    <div class="d-flex gap-4 flex-wrap" v-else>
                        <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                        <ViewCard :inverted="true" title="Collateral Type " :desc="collateral?.name" />
                        <ViewCard :inverted="true" title="Rating" :desc="collateral?.rating" />
                        <ViewCard :inverted="true" title="CUSIP NO" :desc="collateral?.cucip_code" />
                    </div>
                </div>

            </div>
            <div class="w-100 row mt-3">

                <div class="col-md-3"
                    style="display: flex;flex-direction: column;align-items: baseline;justify-content: center">
                    <CustomSubmit title="Previous" :previous="true" @action="goBack" />

                </div>
                <div class="col-md-9">

                    <div class="d-flex justify-content-start align-items-center gap-4">
                        <div class="d-flex flex-column justify-content-end " style="width: 1000px;">
                            <CurrencyInput inputType="number" :allownull="false" :nocurrency="true"
                                inputStyle="font-weight: 400;width:100%;font-size:13px !important;padding:5px 10px !important"
                                p-style="width:100%" id="rate" placeholder="Purchase Amount" :has-validation="true"
                                @currencyError="investmentAmountError = $event" @inputChanged="investmentAmount($event)"
                                input-type="number" :defaultValue="investment_amount"
                                :hasSpecificError="investmentAmountError" />
                            <div v-if="investmentAmountError" class="error-message">
                                {{ investmentAmountError }}
                            </div>
                        </div>
                        <div
                            style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: flex-start; gap: 1px; display: inline-flex">
                            <div
                                style="color: #252525; font-size: 15px; font-family: Montserrat; font-weight: 400; white-space:nowrap">
                                Repo Income</div>
                            <div
                                style="width: 108px; color: #9CA1AA; font-size: 20px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; white-space:nowrap">
                                {{ repo_income ? `${offer?.currency}
                                ${addCommasAndDecToANumber(repo_income)}(${formatNumberAbbreviated(repo_income)})`
                                : '0.00' }}</div>
                        </div>


                        <div class=" d-flex justify-content-end gap-2">
                            <template v-if="userCan(userLoggedIn, 'depositor/repos/adjust-repo')">

                                <CustomSubmit title="Counter Offer" :outline="true" @action="show_counter = true" />

                                <CustomSubmit title="Confirm" @action="SubmitOffer" />

                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="w-100">
            <div style="width: 100%; margin-top: 10px;" class="row mt-3" v-if="offer && offer?.c_g">

                <b-tabs content-class="mt-3"
                    nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                    <!-- <b-tab class="d-none" itle="Collateral Basket Details">
                    </b-tab> -->
                    <b-tab active :title="'About ' + offer?.c_g?.name">
                        <AboutBank :organization_data="offer?.c_g" />
                    </b-tab>
                    <b-tab :title="offer?.c_g?.name + ' Products'">
                        <TileView v-if="related_products && related_products.length > 0" :data="related_products" />
                        <LoadingData v-else-if="loading_my_related" />
                        <NoData v-else title="No Related Products"
                            message="You will see products when Recieved from Collateral Givers" />
                    </b-tab>
                    <b-tab title="Related Products">
                        <TileView
                            v-if="other_related_products && other_related_products.length > 0 && !loading_other_related"
                            :data="other_related_products" />
                        <LoadingData v-else-if="loading_other_related" />
                        <NoData v-else title="No Related Products"
                            message="You will see products when Recieved from Collateral Givers" />
                    </b-tab>

                    <b-tab title="Counter Offer Change Log" v-if="log_table_data">
                        <Table :columns="logcolumns" no-data-title="No Counter Offers"
                            no-data-message="No counter offers available for review" :data="log_table_data"
                            :has_action='false' :selectable="false" :is_loading="false" />
                    </b-tab>
                    <b-tab title="Purchase History" v-if="purchase_history_data">
                        <Table :columns="purchase_history_columns" no-data-title="No Purchase History"
                            no-data-message="This table will be filled once you purchase the product"
                            :data="purchase_history_data" :has_action='false' :selectable="false" :is_loading="false" />
                    </b-tab>
                    <!-- <b-tab class="d-none" title="Purchase History">
                    </b-tab> -->
                </b-tabs>
            </div>
        </div>

        <!-- <ChatComponent :show="false"> </ChatComponent> -->

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

        <ActionMessage size="md" style="width: 600px;" @closedSuccessModal="existing_offer = false"
            @btnTwoClicked="closeExistingOfeer" @btnOneClicked="existing_offer = false" btnOneText="Ok" btnTwoText=""
            icon="/assets/dashboard/icons/question-new.svg" title="You cannot repurchase this product."
            :showm="existing_offer">
            <div class="ml-5 description-text-withdraw ">
                <div>You have already bought this product. </div>
            </div>
        </ActionMessage>


        <ConfirmRepoOffer size="xl" class="offer-summary"
            style="width: max-content !important; background-color: white !important;"
            @closedSuccessModal="review_transaction = false" @btnTwoClicked="doSubmit"
            @btnOneClicked="review_transaction = false" btnOneText="" btnTwoText="Submit"
            icon="/assets/dashboard/icons/review-transaction.svg" title="Review Transaction Details"
            :showm="review_transaction" :offer="offer">
            <div class="description-text-withdraw pl-2">
                <div class="d-flex gap-4 flex-wrap">
                    <ViewCard :inverted="true" title="Term"
                        :desc="offer?.offer_term_length + ' ' + capitalize(offer?.offer_term_length_type)" />
                    <ViewCard :inverted="true" title="Basket"
                        :desc="offer?.basket?.basket_details.trade_basket_type.basket_name" />
                    <ViewCard :inverted="true" title="Basket ID" :desc="offer?.basket?.basket_id" />
                    <ViewCard :inverted="true" title="Trade Date "
                        :desc="offer?.rate_valid_until ? formatTimestamp(offer?.rate_valid_until, false) : '-'" /> <br>
                </div>
                <div class="d-flex gap-4 flex-wrap">
                    <hr class="w-100">
                </div>
                <div class="d-flex gap-4 flex-wrap mb-20">
                    <ViewCard :inverted="true" title="Purchase price "
                        :desc="`${offer?.currency}
                                ${addCommasAndDecToANumber(investment_amount)}(${formatNumberAbbreviated(investment_amount)})`" />
                    <ViewCard :inverted="true" title="Repo income"
                        :desc="repo_income ? `${offer?.currency}
                                ${addCommasAndDecToANumber(repo_income)}(${formatNumberAbbreviated(repo_income)})` : '0.00'" />
                    <ViewCard :inverted="true" title="Repurchase price"
                        :desc="`${offer?.currency}
                                ${addCommasAndDecToANumber(repurchase_price)}(${formatNumberAbbreviated(repurchase_price)})`" />
                </div>
            </div>
        </ConfirmRepoOffer>
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
        <Adjustment @closeModal="adjust_deposit = false" v-if="adjust_deposit" from="pending" :deposit="deposit"
            :show="adjust_deposit" />
        <ChatComponent from="CT" :recipient="organization" :reference="reference" :deposit_id="deposit_id"
            @closeModal="start_conv = false" :show="start_conv" v-if="start_conv" />

        <Counter v-if="show_counter" @closeModal="show_counter=false" :offer="offer" :show="show_counter"
            :daycount="daycount" :holidays="holidays">
        </Counter>

    </div>
</template>

<script>
    import ViewCard from '../../../shared/ViewCard.vue';
    import InviteCard from '../../../shared/CustomInvitedStatusBadge.vue';
    import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
    import OKMessage from '../../../shared/messageboxes/OKButtonActionMessageBox.vue'
    // CustomInput
    import AboutBank from '../../../postrequest/depositor/pendingdeposits/AboutBank.vue'
    import Modal from '../../../shared/Modal.vue';
    import { addDaysOrMonthsToDate, calculateIterestOnDateCountConnvention, formatTimestamp, formatNumberAbbreviated, repoProductName, calculateIterestOnProduct, addCommasAndDecToANumber, getBasketDetails, addCommasToANumber } from '../../../../utils/commonUtils'
    import { mapGetters } from 'vuex';
    import { userCan } from '../../../../utils/GlobalUtils';
    import Table from '../../../shared/Table.vue';
    import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
    import CustomSelect from '../../../shared/CustomSelect.vue';
    import FormLabelRequired from '../../../shared/formLabels/FormLabelRequired.vue';
    import Adjustment from '../activetrades/Adjustment.vue';
    import ChatComponent from '../../shared/ChatComponent.vue';
    import AboutCGNoModal from '../../shared/AboutCGNoModal.vue';
    import ShowRateCard from '../../shared/ShowRateCard.vue';
    import CurrencyInput from '../../../shared/CurrencyInput.vue';
    import TileView from './TileView.vue'

    import Counter from './osummary/Counter.vue';
    import NoData from '../../../shared/Table/NoData.vue';
    import LoadingData from '../../../shared/Table/LoadingData.vue'
    import ConfirmRepoOffer from '../../../shared/messageboxes/ConfirmRepoOffer.vue';



    export default {
        components: { ConfirmRepoOffer, ShowRateCard, NoData, LoadingData, Counter, TileView, CurrencyInput, AboutCGNoModal, Adjustment, ChatComponent, CustomSelect, FormLabelRequired, ActionMessage, InviteCard, ViewCard, AboutBank, Modal, Table, CustomSubmit },
        props: ['offerIndex', 'show', 'userLoggedIn'],
        beforeMount() {
            this.getUrlPArams()
            // this.setPageDefaults()
        },
        mounted() {
            this.getAllHolidays()
            this.getAllDayCounts()
        },

        data() {
            return {
                latest_counter_offer_id: null,
                hasCounters: false,
                start_conv: false,
                viewMore1: false,
                adjust_deposit: false,
                organization_data: null,
                offer_data: null,
                offer_id: null,
                i_started_transaction: false,
                has_trade_events: false,
                loading_other_related: true,
                loading_my_related: true,
                loadingcounters: true,
                request: null,
                counteroffers: null,
                settelemntdate: null,
                success: false,
                trade_status: '',
                fail: false,
                success_title: '',
                fail_title: '',
                success_desc: '',
                fail_desc: '',
                withdraw_request: false,
                deposit_request: null,
                withdrawpromt: false,
                fromPage: null,
                deposit_id: null,
                organization: null,
                organization_data: null,
                log_table_data: [],
                purchase_history_data: [],
                reference: null,
                currency_to_use: 'CAD',
                amt_awarded: 0,
                counter_rate: 0,
                counter_rate_type: null,
                counter_rate_spread_rate_value: null,
                counter_rate_operator: null,
                counter_rate_applied_prime: null,
                total_interest: 0,
                maturity_date: 0,
                logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter'],
                purchase_history_columns: ['#', 'Settlement Date', 'Maturity Date', 'Currency', 'Purchase Price', 'Interest', 'Repurchase Price', 'Basket ID', 'Status'],
                deposit: null,
                reason_for_termination: 'Mutual Agreement',
                selected_reason: null,
                accept_cancel: false,
                batch_no: false,
                i_initated: false,
                current_organization: null,
                start_cancel: false,
                collateral: null,
                istriparty: false,
                settle_date: null,
                daycount: null,
                repurchase_value: 0,
                terminationReasons: [],

                // as my offers
                investment_amount: null,
                investmentAmountError: null,
                start_counter: false,
                offer: null,
                review_transaction: false,
                repo_income: null,
                repurchase_price: 0,

                related_products: null,
                other_related_products: null,
                show_counter: null,

                daycount: null,
                holidays: null,
                existing_offer: false

            }
        },
        computed: {
            ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate', 'getgloabalproducts']),

            awarded_amount() {
                return this.addCommas(this.offer_data.amount)
            },
        },
        methods: {
            getAllHolidays() {
                axios.get('https://canada-holidays.ca/api/v1/holidays').then(res => {
                    this.holidays = res?.data?.holidays
                    // console.log(res.data.holidays, "Holdays")
                    // this.formattedtimezone = JSON.stringify(res.data)
                })
            },
            getAllDayCounts() {
                axios.get('/common/trade/get-all-interest-calculation-options?status=ACTIVE').then(res => {
                    //    this.holidays=res?.data?.holidays
                    if (res.data.length > 0)
                        this.daycount = res.data
                    // console.log(res.data, "Holdays")
                    // this.formattedtimezone = JSON.stringify(res.data)
                })
            },
            formatNumberAbbreviated(value) {
                return formatNumberAbbreviated(value)
            },
            userCan(a, b) {
                // console.log(userCan(this.userLoggedIn, value), "User Can")
                return userCan(a, b)
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
            calulateInterest(value, date) {
                return calculateIterestOnDateCountConnvention(
                    value,
                    this.offer?.offer_interest_rate,
                    // this.daycount?.slug,
                    this.offer?.interest_calculation_option?.slug,
                    date,                // this.offer?.settlement_date,
                    this.offer?.offer_term_length,
                    this.offer?.offer_term_length_type,

                );
            },
            investmentAmount(value) {
                console.log(this.investment_amount = value)

                if (this.offer?.interest_calculation_option) {
                    this.repo_income = calculateIterestOnDateCountConnvention(
                        this.investment_amount,
                        this.offer?.offer_interest_rate,
                        // this.daycount?.slug,
                        this.offer?.interest_calculation_option?.slug,
                        this.offer?.rate_valid_until,
                        // this.offer?.settlement_date,
                        this.offer?.offer_term_length,
                        this.offer?.offer_term_length_type,

                    );
                }
                this.repurchase_price = Number.parseFloat(this.investment_amount) + Number.parseFloat(this.repo_income)
            },

            getUrlPArams() {
                const url = window.location.pathname; // Get the current URL path
                const parts = url.split('/'); // Split the URL by '/'

                // The last part of the URL should be the number part
                const numberPart = parts[parts.length - 1];

                axios.get('/trade/market-place/CT/get-offer-details?offerId=' + numberPart).then(res => {
                    // console.log(res.data)
                    this.offer = res.data
                    this.istriparty = this.offer?.product?.filter_key?.toLowerCase() == 'tri'
                    this.replatedProducts()
                    this.otherReplatedProducts()
                    this.setCounters()
                    this.setPurchaseHistory()
                }).catch(err => {
                    console.log(err, "Trade")
                })
            },
            setCounters() {
                if (this.offer != null) {


                    const counter_offers = this.offer.counters
                    let counter_offer_pld = []
                    if (counter_offers && counter_offers.length > 0) {
                        this.hasCounters = true
                        this.has_counter = counter_offers[0].status == 'PENDING'

                        let counter_offer_pld = [];
                        let filteredcounterdata = [];
                        for (let index = 0; index < counter_offers.length && index < 5; index++) {
                            const coffer = counter_offers[index];
                            filteredcounterdata.push([coffer.rate_type,
                            coffer.rate_operator,
                            coffer.fixed_rate,
                            coffer.variable_rate_value
                            ]);
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

                        this.counter_rate = this.log_table_data[0][4]
                        this.counter_rate_type = filteredcounterdata[0][0]
                        this.counter_rate_operator = filteredcounterdata[0][1]
                        this.counter_rate_spread_rate_value = filteredcounterdata[0][2]
                        this.counter_rate_applied_prime = filteredcounterdata[0][3]
                    }
                }
            },
            setPurchaseHistory() {
                if (this.offer != null) {

                    const purchase_history = this.offer.purchase_history
                    let counter_offer_pld = []
                    if (purchase_history && purchase_history.length > 0) {

                        let p_history_data = [];
                        for (let index = 0; index < purchase_history.length && index < 5; index++) {
                            const p_history = purchase_history[index];
                            let interest = this.calulateInterest(p_history.offered_amount, p_history?.c_g_offer?.settlement_date)
                            let repurhase = Number.parseFloat(p_history.offered_amount) + Number.parseFloat(interest)
                            p_history_data.push([
                                p_history.encoded_id,
                                index + 1,
                                p_history?.c_g_offer?.settlement_date ? formatTimestamp(p_history?.c_g_offer?.settlement_date, false) : '-',
                                p_history.maturity_date ? formatTimestamp(p_history?.maturity_date, false) : '-',
                                this.offer.currency,
                                this.addCommas(p_history.offered_amount) + ' (' + this.formatNumberAbbreviated(p_history.offered_amount) + ')',
                                this.addCommas(interest) + ' (' + this.formatNumberAbbreviated(interest) + ')',
                                this.addCommas(repurhase) + ' (' + this.formatNumberAbbreviated(repurhase) + ')',
                                this.offer?.basket?.basket_id,
                                () => this.renderStatus(p_history.deposit_status)
                            ]);
                        }
                        this.purchase_history_data = p_history_data;
                    }
                }
            },
            replatedProducts() {
                let product_id = this.offer?.basket?.basket_details?.trade_basket_type?.id
                let organization_id = this.offer?.c_g?.id
                let product_type = 'tri'
                if (this.istriparty)
                    product_type = 'tri'
                else
                    product_type = 'bi'
                axios.get(`/trade/market-place/CT/get-my-related-products?productId=${product_id}&productType=${product_type}&cg=${organization_id}&currentOfferId=${this.offer.id}`).then(res => {
                    this.related_products = res.data.data
                    this.loading_my_related = false
                }).catch(err => {
                    this.loading_my_related = false
                    console.log(err, "Trade")
                })
            },
            otherReplatedProducts() {
                let product_id = this.offer?.basket?.basket_details?.trade_basket_type?.id
                let organization_id = this.offer?.c_g?.id
                let product_type = 'tri'
                if (this.istriparty)
                    product_type = 'tri'
                else
                    product_type = 'bi'
                axios.get(`/trade/market-place/CT/get-other-related-products?productId=${product_id}&productType=${product_type}&cg=${organization_id}&currentOfferId=${this.offer.id}`).then(res => {
                    console.log(res.data, "Related products")
                    this.other_related_products = res.data.data
                    this.loading_other_related = false
                }).catch(err => {
                    this.loading_other_related = false
                    console.log(err, "Trade")
                })
            },
            setPageDefaults() {

            },
            formatTimestamp(val1, val2) {
                return formatTimestamp(val1, val2)

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
                window.location.href = "/repos/ct-repos-my-offers"
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
            cancelTrade() {
                this.withdraw_request = true
            },
            withdrawReasonChange(value) {
                const reason = this.terminationReasons.find(item => item.reason == value)
                this.selected_reason = reason.combined
            },
            async doTerminate() {

                let formdata = new FormData()
                formdata.append('depositID', this.deposit_id)
                formdata.append('event_type', 'cancel')
                formdata.append('reason', this.selected_reason)
                axios.post('/trade/CT/post-trade-events', formdata).then(response => {
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
            async doSubmit() {

                if (this.investment_amount > this.offer.offer_maximum_amount) {
                    this.fail_title = "Ooops!  Request not submitted"
                    this.fail_desc = `The investment amount cannot be more than ${this.offer.currency} ${this.addCommas(this.offer.offer_maximum_amount)}`
                    this.fail = true
                    return false;
                } else if (this.investment_amount < this.offer.offer_minimum_amount) {
                    this.fail_title = "Ooops!  Request not submitted"
                    this.fail_desc = `The investment amount cannot be less than ${this.offer.currency} ${this.addCommas(this.offer.offer_minimum_amount)}`
                    this.fail = true
                    return false;
                }

                let formdata = new FormData()
                formdata.append('investment_amount', this.investment_amount)
                formdata.append('offerId', this.offer?.encoded_id)
                axios.post('/trade/market-place/CT/confirm-market-offer', formdata).then(response => {
                    if (response.data.status) {
                        this.success_title = "offer has been submitted"
                        this.review_transaction = false
                        this.success = true
                        setTimeout(() => {
                            this.success = false
                            window.location.reload()
                        }, 3000)

                    } else {
                        this.fail_title = "Ooops!  Request not submitted"
                        this.fail_desc = response.data.message
                        this.fail = true

                    }
                }).catch(err => {

                    this.fail_title = "Ooops!  Request not submitted"
                    this.fail_desc = "Something's not right,please try again or contact info@yieldechange.ca"
                    this.fail = true
                })
            },
            closeExistingOfeer() {
                this.existing_offer = false
                this.review_transaction = true
            },

            // submit Offer
            SubmitOffer() {
                if (this.investment_amount != null && this.investmentAmountError == null) {
                    if (this.offer.purchase_history && this.offer.purchase_history.length > 0) {
                        this.existing_offer = true
                    } else {
                        this.review_transaction = true
                    }

                } else {
                    this.investmentAmountError = this.investmentAmountError ? this.investmentAmountError : "This field is required"
                }
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

    .modal-content {
        width: 650px !important;
    }
</style>
<!-- <style scoped>
</style> -->