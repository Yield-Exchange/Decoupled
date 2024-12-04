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
                    <ShowRateCard v-if="offer" offer="offer" :rate_type="offer?.rate_type"
                        :rate_operator="offer?.rate_operator" :interest_rate="offer?.offer_interest_rate"
                        :counter_rate="counter_rate" :counter_rate_operator="counter_rate_operator"
                        :counter_rate_type="counter_rate_type"
                        :counter_rate_spread_rate_value="counter_rate_spread_rate_value"
                        :counter_rate_applied_prime="counter_rate_applied_prime"
                        :variable_rate_value="offer?.variable_rate_value" :requireAction="has_counter"
                        :from_counter="false" :selectedCurrency="selectedCurrency"
                        :new_offer_interest_rate="new_offer_interest_rate" :hasCounters="hasCounters"
                        :showInterestEarned="true"
                        :counters="offer.counters"
                        >
                    </ShowRateCard>
                    <!-- <div
                        style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
                        <div
                            style="height: 42px; text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                            {{ offer?.offer_interest_rate.toFixed(2) }}%</div>
                        <div style="width: 128px; height: 0px; border: 0.51px #9CA1AA solid ;margin-top: 20px;"></div>
                        <div
                            style="flex-direction: column; justify-content: center; align-items: center; display: flex">
                            <div
                                style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Term Length </div>
                            <div
                                style="color: #5063F4; font-size: 20px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                {{ offer?.offer_term_length + " " + capitalize(offer.offer_term_length_type) }}
                            </div>
                        </div>
                    </div> -->
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
                            <ViewCard :inverted="true" title="Minimum"
                                :desc="offer?.currency + ' ' + formatNumberAbbreviated(offer?.offer_minimum_amount)" />
                            <ViewCard :inverted="true" title="Maximum "
                                :desc="offer?.currency + ' ' + formatNumberAbbreviated(offer?.offer_maximum_amount)" />
                            <ViewCard :inverted="true" title="Term Length "
                                :desc="offer?.offer_term_length + ' ' + capitalize(offer?.offer_term_length_type)" />
                            <ViewCard :inverted="true" title="Day Count" :hastooltip="true" id="ctpddcc"
                                tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                                :desc="offer?.interest_calculation_option ? offer.interest_calculation_option?.label : '-'" />
                            <ViewCard :inverted="true" title="Offer Valid Till"
                                :desc="offer?.rate_valid_until ? formatTimestamp(offer.rate_valid_until, false) : '-'" />
                        </div>
                        <hr class="w-100">
                        <div class="d-flex gap-4 flex-wrap" v-if="istriparty">
                            <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                            <ViewCard :inverted="true" title="Basket Type "
                                :desc="offer.basket.basket_details.trade_basket_type.basket_name" />
                            <ViewCard :inverted="true" title="Rating" :desc="offer.basket.basket_details.rating" />
                            <ViewCard :inverted="true" title="Basket ID" :desc="offer.basket.basket_id" />
                        </div>
                        <div class="d-flex gap-4 flex-wrap" v-else>
                            <ViewCard :inverted="true" title="Product" :desc="offer?.product?.product_name" />
                            <ViewCard :inverted="true" title="Collateral Type " :desc="collateral?.name" />
                            <ViewCard :inverted="true" title="Rating" :desc="collateral?.rating" />
                            <ViewCard :inverted="true" title="CUSIP NO" :desc="collateral?.cucip_code" />
                        </div>
                        <div class="w-100 mt-2">
                            <div class="d-flex justify-content-end gap-2" v-if="hasCounters && requireAction">

                                <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/accept-counter')"
                                    title=" Decline Counter" :prevoutline="true" @action="counterAction('decline')">
                                </CustomSubmit>
                                <CustomSubmit v-if="userCan(userLoggedIn, 'bank/repos/accept-counter')"
                                    title="Accept Counter" @action="counterAction('accept')">
                                </CustomSubmit>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-100">
                <div style="width: 100%; margin-top: 10px;" class="row mt-3">

                    <b-tabs content-class="mt-3"
                        nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">

                        <b-tab :title="'About ' + organization_data?.name" active>
                            <AboutBank :organization_data="organization_data"></AboutBank>
                        </b-tab>

                        <b-tab title="Counter Offer Change Log" v-if="log_table_data">
                            <Table :columns="logcolumns" no-data-title="No Counter Offers"
                                no-data-message="No counter offers available for review" :data="log_table_data"
                                :has_action='false' :selectable="false" :is_loading="false" />
                        </b-tab>
                        <b-tab title="Purchase History" v-if="purchase_history_data">
                            <Table :columns="purchase_history_columns" no-data-title="No Purchase History"
                                no-data-message="This table will be filled once you purchase the product"
                                :data="purchase_history_data" :has_action='false' :selectable="false"
                                :is_loading="false" />
                        </b-tab>
                    </b-tabs>
                </div>
            </div>
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
    </Modal>

</template>

<script>
    import ViewCard from '../../../../shared/ViewCard.vue';
    import InviteCard from '../../../../shared/CustomInvitedStatusBadge.vue';
    import AboutBank from '../../../../postrequest/depositor/pendingdeposits/AboutBank.vue'
    import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'

    import Modal from '../../../../shared/Modal.vue';
    import { addCommasAndDecToANumber, addDaysOrMonths, calculateIterestOnDateCountConnvention, formatNumberAbbreviated, formatTimestamp, repoProductName } from '../../../../../utils/commonUtils'
    import { mapGetters } from 'vuex';
    import Table from '../../../../shared/Table.vue';
    import { userCan } from '../../../../../utils/GlobalUtils';
    import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue';
    import ShowRateCard from '../../../shared/ShowRateCard.vue';

    export default {
        components: { InviteCard, ViewCard, AboutBank, Modal, Table, CustomSubmit, ActionMessage, ShowRateCard },
        props: ['offer', 'show', 'userLoggedIn'],
        beforeMount() {
            this.setCounters()
            this.setPurchaseHistory()

        },
        mounted() {
            if (this.offer) {
                console.log(this.offer, "offer")
                // console.log(this.request, "Request")
                this.organization_data = this.offer?.c_g_trade_request_invited_c_t?.ct
                this.istriparty = this.offer?.product?.filter_key == 'tri'
            }
        },

        data() {
            return {
                viewMore1: false,
                hasCounters: false,
                requireAction: false,
                organization_data: null,
                offer_data: null,
                offer_id: null,
                request: null,
                collateral: null,
                success: false,
                fail: false,
                success: false,
                fail: false,
                success_title: '',
                fail_title: '',
                success_desc: '',
                fail_desc: '',
                deposit_request: null,
                withdrawpromt: false,
                fromPage: null,
                istriparty: false,
                latest_counter_offer_id: null,
                // offer: null,
                organization_data: null,
                log_table_data: [],
                purchase_history_data: [],
                logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter'],
                purchase_history_columns: ['#', 'Settlement Date', 'Maturity Date', 'Currency', 'Purchase Price', 'Interest', 'Repurchase Price', 'Basket ID', 'Status'],
                existingcounter: false,
                has_counter: false,
                hasCounters: false,
                selectedCurrency: 'CAD',
                counter_rate: null,
                counter_rate_type: null,
                counter_rate_spread_rate_value: null,
                counter_rate_operator: null,
                counter_rate_applied_prime: null,
                new_offer_interest_rate: 0

            }
        },
        computed: {
            ...mapGetters('repopostrequest', ['getAllOffersInReview']),

            awarded_amount() {
                return this.addCommas(this.offer_data.amount)
            },
        },
        methods: {
            userCan(a, b) {
                return userCan(a, b)
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
            repoProductName(x, y, z) {
                return repoProductName(x, y, z)
            },
            addCommasAndDecToANumber(value) {
                return addCommasAndDecToANumber(value)
            },
            formatNumberAbbreviated(value) {
                return formatNumberAbbreviated(value)
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
            setCounters() {
                if (this.offer != null) {


                    const counter_offers = this.offer.counters
                    let counter_offer_pld = []
                    if (counter_offers && counter_offers.length > 0) {
                        this.requireAction = counter_offers[0].status == 'PENDING'
                        this.has_counter = counter_offers[0].status == 'PENDING'
                        this.hasCounters = true
                        this.latest_counter_offer_id = counter_offers[0].encoded_id
                        let filteredcounterdata = [];
                        let counter_offer_pld = [];
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
                                coffer.created_at ? formatTimestamp(coffer.created_at, false) : '-',
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
                                p_history.maturity_date ? formatTimestamp(p_history.maturity_date, false) : '-',
                                this.offer.currency,
                                this.formatNumberAbbreviated(p_history.offered_amount),
                                this.formatNumberAbbreviated(interest),
                                this.formatNumberAbbreviated(repurhase),
                                this.offer?.basket?.basket_id,
                                () => this.renderStatus(p_history.deposit_status)
                            ]);
                        }
                        this.purchase_history_data = p_history_data;
                    }
                }
            },
            formatDateToCustomFormat(inputDate) {
                // Create a Date object from the inputDate parameter
                const options = { month: 'short', day: '2-digit', year: 'numeric' };
                const date = new Date(inputDate);
                const formattedDate = date.toLocaleDateString('en-US', options);

                return formattedDate;
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