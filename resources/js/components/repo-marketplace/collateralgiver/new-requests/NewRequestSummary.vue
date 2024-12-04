<template>
    <div class="w-100">
        <!-- header -->
        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
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
                        <div class="text-div">Request</div>
                    </div>
                </div>
            </div>
        </div>



        <div v-if="getRequestSummary" class="">
            <div class="d-flex flex-column">
                <div class="d-flex gap-4 flex-wrap mt-3 p-3">
                    <ViewCard title="Request ID" :desc="getRequestSummary?.reference_no" />

                    <ViewCard title="Investor" :desc="getRequestSummary?.inviter?.name" />
                    <ViewCard title="Investment"
                        :desc="getRequestSummary?.currency + ' ' + addCommasToANumber(getRequestSummary?.investment_amount) + ' (' + abreviateNumber(getRequestSummary?.investment_amount) + ')'" />
                    <ViewCard title="Settlement Date" :desc="getRequestSummary?.settlement_date ?
                formatTimestamp(getRequestSummary?.settlement_date, false) : '-'
            " />
                    <ViewCard title="Term Length"
                        :desc="getRequestSummary?.term_length + ' ' + capitalize(getRequestSummary?.term_length_type)" />
                    <ViewCard title="Day Count" :hastooltip="true" id="dcc-nr"
                        tooltipmessage="A day count convention is a financial method for calculating the number of days between two dates for interest calculations."
                        :desc="getRequestSummary?.interest_calculation_option ? getRequestSummary?.interest_calculation_option.label : '-'" />

                    <template v-if="getRequestSummary?.preferred_collaterals.length > 0">
                        <ViewCard :title="'Preferred Coll ' + (index + 1)"
                            v-for="(    value, index    ) in     getRequestSummary?.preferred_collaterals    "
                            :desc="value.collateral_name" :key="index" />
                    </template>
                    <template v-else>
                        <ViewCard title='Preferred Coll' desc="No Preference" />
                    </template>
                </div>
            </div>
        </div>
        <!-- header 2 -->
        <div v-if="(step == 'addrequest' || step == 'editrequest') && getRequestSummary">
            <!-- requests sections -->
            <div>
                <!-- <div>-->
                <AddRateOffer :day__counts="daycount" :holidays="holidays" :formattedtimezone="formattedtimezone"
                    :bilateral_primary_baskets="bilateral_primary_baskets" :triparties="triparties"
                    :triparties_primary_baskets="triparties_primary_baskets" :bilaterals="collaterals"
                    :defcurrency="getRequestSummary?.currency" @newRequest="newRequest"
                    :defdaycount="getRequestSummary?.interest_calculation_option?.id"
                    v-for="(    value, i    ) in     getAllRequestsOffers    " :key="value.reqid" ref="requestitems"
                    @hasError="hasError" :prime_rate="prime_rate" :editData="value" :request="value"
                    @deleteRequest="deleteRequest" :isedit="isedit" :count="i"
                    :candelete="postrequestsCount > 1">
                </AddRateOffer>

                <!-- </div> -->
            </div>


            <div class="d-flex justify-content-start gap-2 mt-3"
                v-if="userCan(userLoggedIn, 'bank/repos/give-offers')">
                <div @click="addPostRequestCount">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="29" viewBox="0 0 30 29" fill="none">
                        <path d="M25 16.9154H17.5V24.1654H12.5V16.9154H5V12.082H12.5V4.83203H17.5V12.082H25V16.9154Z"
                            fill="#5063F4" />
                    </svg>
                </div>
                <p @click="addPostRequestCount" class="aditional-option-click">Add rate offers</p>
            </div>
            <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                <div>
                    <CustomSubmit @action="requestgoback = true" :previous="true" title="Previous" />
                </div>
                <div class="d-flex justify-content-end mt-3 gap-2"
                    v-if="userCan(userLoggedIn, 'bank/repos/give-offers')">
                    <CustomSubmit @action="clear = true" :outline="true" title="Clear" />
                    <CustomSubmit @action="viewSummary" title="Next" />
                </div>
            </div>
        </div>
        <div v-if="step == 'summary'">
            <!-- requests sections -->
            <template v-for="(    request, i    ) in     getAllRequestsOffers    ">
                <PostRequestSummary :daycount="daycount" :triparties="triparties" :bilaterals="collaterals"
                    :request="request" :count="i">
                </PostRequestSummary>
            </template>


            <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                <div>
                    <CustomSubmit @action="editRequest()" :previous="true" title="Previous" />
                </div>
                <div class="d-flex justify-content-end mt-3 gap-2">
                    <CustomSubmit @action="editRequest()" :outline="true" title="Edit" />
                    <CustomSubmit :isLoading="submitting" @action="doSubmit" title="Submit" />
                    <!-- <CustomSubmit v-else :isLoading="submitting" @action="step = 'setrates'" title="Submit" /> -->
                </div>
            </div>
        </div>


        <ActionMessage style="width: 600px;" @closedSuccessModal="closeModalSuccess" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" :title="successtitle"
            btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral taker has been notified..</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="requestgoback" @btnTwoClicked="goToHome"
            @btnOneClicked="requestgoback = false" icon="/assets/dashboard/icons/question-new.svg"
            title="Do you want to leave this page?" btnOneText="No" btnTwoText="Yes" :showm="requestgoback">
            <div class="ml-5 description-text-withdraw "> Changes you made will not be saved.</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Request has not been submitted!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="clear = false" @btnTwoClicked="clearReq"
            @btnOneClicked="clear = false" btnOneText="No" btnTwoText="yes" icon="/assets/signup/danger.svg"
            title="Clear request" :showm="clear">
            <div class="ml-5 description-text-withdraw ">Your changes will be cleared from the request</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="deleteoffer = false" @btnTwoClicked="doDelete"
            @btnOneClicked="deleteoffer = false" btnOneText="No" btnTwoText="yes" icon="/assets/signup/danger.svg"
            title="Delete this rate offer?" :showm="deleteoffer">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to delete this rate offer</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="confirmsubmit = false" @btnTwoClicked=""
            @btnOneClicked="confirmsubmit = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Sure to submit this offer?" :showm="confirmsubmit">
            <div class="ml-5 description-text-withdraw ">Your offer will be sent to the depositor</div>
        </ActionMessage>

    </div>
</template>

<script>
import { capitalize, formatTimestamp, addCommasToANumber, calculateSettlementLabel, formatNumberAbbreviated, getPossibleDayCountConvention } from '../../../../utils/commonUtils.js'
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSelectInput from '../../../auth/signup/shared/CustomSelectInput.vue'
import CustomTextInput from '../../../auth/signup/shared/CustomTextInput.vue'
import CustomDateInput from '../../../auth/signup/shared/CustomDateInput.vue'
import PostRequestSummary from './PostRequestSummary.vue'
// import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'

import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";

import CustomInput from '../../../shared/CustomInput.vue';
import CustomSelect from '../../../shared/CustomSelect.vue';
// import CustomDateInput
import FileUpload from "../../../shared/FileUpload";
import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";
import AddRateOffer from './AddRateOffer.vue'
import ViewCard from '../../../shared/ViewCard.vue'
// import addRates from './addRates.vue'
import { mapGetters } from 'vuex'
import * as types from '../../../../store/modules/repos/mutation-types'
import { userCan } from '../../../../utils/GlobalUtils'


export default {
    components: { AddRateOffer, ViewCard, PostRequestSummary, CustomSubmit, CustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
    props: ['prime_rate', 'userLoggedIn'],
    beforeMount() {
        window.addEventListener("beforeunload", this.preventNav)
        this.getTimezone()
        this.getAllDayCounts()
        this.getUrlPArams()
        // this.getSettlementDates()
        this.getProducts()
        this.getCollaterals()
        this.getTriParties()
        this.getTriBasketTypes()
        this.getBilateralBasketTypes()
    },

    beforeDestroy() {
        window.removeEventListener("beforeunload", this.preventNav);
    },

    mounted() {
        this.getAllHolidays()
        if (this.prime_rate)
            this.$store.commit('repopostrequest/' + types.SET_PRIME_RATES, JSON.parse(this.prime_rate));
        // this.getPrefferedCollateralTypes()
        if (this.getAllRequestsOffers.length == 0 && this.newreqdata) {
            let newRequestbm = this.newreqdata
            newRequestbm.reqid = this.generateRandomValue()
            this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST_OFFER, [0, newRequestbm]);
        }
    },

    data() {
        return {
            termLength: 'Days',
            step: 'addrequest',
            postrequestsCount: 1,
            deleteoffer: false,
            requestgoback: false,
            valuetodelete: null,
            depositor_demo_setup: false,
            generalError: {},
            newreqdata: {
                'reqid': null,
                'currency': 'CAD',
                'min': null,
                'max': null,
                'term_length': null,
                'term_length_type': 'Days',
                "product": null,
                "basket": null,
                "basket": null,
                "settlement_date": null,
                "rate_type": 'fixed',
                "entered_rate": null,
                "operator": '+',
                "collateralType": null,
                "collateral_id": null,
                "primaryBasket": null,
                "convention_id": null,

            },
            defcurrency: null,
            collaterals: null,
            triparties: null,
            submitting: false,
            editRequestData: null,
            postsRequests: [],
            invited: null,
            editselectedFIs: [],
            seletectedFiObjects: [],
            allFis: null,
            canchoosefi: true,
            fail: false,
            isdeleting: false,
            success: false,
            confirmsubmit: false,
            isedit: false,
            clear: false,
            selectfierror: false,
            request_id: null,
            rates_and_deposits: null,
            organization_id: null,
            unecd_id: null,
            successtitle: 'Your request has been posted.',
            invited_id: null,
            triparties_primary_baskets: null,
            bilateral_primary_baskets: null,
            formattedtimezone: null,
            holidays: null,
            daycount: null,

        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllRequestsOffers', 'getRequestSummary', 'getgloabalproducts']),

    },

    methods: {
        abreviateNumber(number) {
            return formatNumberAbbreviated(number);
        },
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
        userCan(a, b) {
            return userCan(a, b)
        },
        getTimezone() {
            axios.get('/get-formated-timezone').then(res => {
                this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        closeModalSuccess() {
            this.success = false
            window.location.href = "/repos/view-all-in-progress"

        },
        goToHome() {
            window.location.href = '/repos/view-all-new-requests'
        },
        async getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'

            // The last part of the URL should be the number part
            const numberPart = parts[parts.length - 1];
            this.request_id = numberPart


            await axios.get('/trade/CG/get-trade-request?requestId=' + numberPart).then(response => {
                // console.log(response.data, "Get Params")
                this.invited_id = response.data.invited_c_gs[0].encoded_id
                this.unecd_id = response.data.inviter.id

                // let responsedata = JSON.parse(response.data)
                this.$store.commit('repopostrequest/' + types.SET_REQUEST_SUMMARY, response.data);
            })
        },
        calculateSettlementLabel(value) {
            return calculateSettlementLabel(value)
        },
        getTriBasketTypes() {
            axios.get('/common/trade/get-basket-types?disabled=0').then(response => {
                this.triparties_primary_baskets = response.data
            })
        },
        getBilateralBasketTypes() {
            axios.get('/common/trade/get-colletarals-list?disabled=0').then(response => {
                this.bilateral_primary_baskets = response.data
                // console.log(baskets)

            })
        },
        async getSettlementDates() {
            await axios.get('/common/trade/get-settlement-dates').then(response => {
                // console.log('set data', response.data)
                let sdates = response.data
                if (sdates.length > 0) {
                    sdates.forEach((date, index) => {
                        date['formated_date'] = `${date.trade_date_label} + ${date.duration} ${date.period_label}`
                    })
                }
                // console.log(sdates,'sdates')
                this.$store.commit('repopostrequest/' + types.SET_SETTLEMENT_DATE, sdates);

            }).catch(err => {
                // console.log(err, 'Error')
            })
        },
        async getProducts() {
            await axios.get('/common/trade/get-products-list?disabled=0').then(response => {
                // console.log('set data', response.data)
                this.$store.commit('repopostrequest/' + types.SET_GLOBAL_PRODUCTS, response.data);

                // console.log(sdates,'sdates')

            }).catch(err => {
                // console.log(err, 'Error')
            })
        },
        getCollaterals() {
            axios.get('/trade/CG/get-colleterals?is_dummy=0').then(response => {
                let collateral = []
                if (response.data.length > 0) {
                    Object.values(response?.data).forEach((item, count) => {
                        // console.log(item, 'item')
                        Object.values(item.trade_organization_c_u_s_s_i_p).forEach((cusip) => {
                            if (cusip?.cusips_status == 'ACTIVE') {
                                collateral.push(
                                    {
                                        'id': cusip?.id,
                                        'primary_id': cusip?.collateral_details?.id,
                                        'cucip': cusip?.CUSIP_code,
                                        'collateral_name': cusip?.collateral_details?.collateral_name,
                                        'rating': item?.rating,
                                        'currency': item?.currency,
                                        'name': `${item?.currency}-${item?.rating}-${cusip?.CUSIP_code}`,
                                    }
                                )
                            }
                        });
                    });
                    // console.log(collateral, 'Data 2')
                    this.collaterals = collateral
                }

            })
        },
        getTriParties() {
            axios.get('/trade/CG/get-baskets?is_dummy=0').then(response => {
                let triparties = []
                if (response.data.length > 0) {
                    Object.values(response?.data).forEach((item, count) => {
                        if (item?.is_disabled == 0) {
                            item.trade_tri_basket_third_party.forEach(basket => {
                                if (basket.basket_status == 'ACTIVE' && basket.organization_id == this.unecd_id)
                                    triparties.push(
                                        {
                                            'id': basket.id,
                                            'primary_id': item.trade_basket_type.id,
                                            'basket_id': basket.basket_id,
                                            'basket_name': item.trade_basket_type.basket_name,
                                            'rating': item.rating,
                                            'currency': item.currency,
                                            'name': `${item.currency}-${item.rating}-${basket.basket_id}`,
                                        }
                                    )
                            })

                        }
                    });
                    this.triparties = triparties
                    // console.log(triparties, "Tris")
                }

            })
        },
        generateRandomValue() {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var length = 12;
            var randomValue = '';
            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * characters.length);
                randomValue += characters.charAt(randomIndex);
            }
            return randomValue;
        },
        formatDateTime(date) {
            const dateObj = new Date(date)

            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            const hours = String(dateObj.getHours()).padStart(2, '0');
            const minutes = String(dateObj.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}`;
        },
        capitalize(value) {
            return capitalize(value)
        },
        formatTimestamp(value, hasdatse = false) {
            return formatTimestamp(value, hasdatse)
        },
        addCommasToANumber(value) {
            return addCommasToANumber(value)
        },
        async getInvitedFis() {
            let invited = []
            await axios.get('/common/trade/get-collateral-givers').then(response => {
                const FIs = response.data
                this.allFis = FIs
                // FIs.forEach(element => {
                //     invited.push(element.id)
                // });
            });
            if (!this.canchoosefi) {
                this.invited = invited
            }
            if (this.canchoosefi && this.isedit) {
                this.invited = this.editselectedFIs
            }
        },
        async getPrefferedCollateralTypes() {
            let colaterals = []
            await axios.get('/common/trade/get-preferred-collateral-list?status=ACTIVE').then(response => {
                const collateral = response.data
                collateral.forEach(element => {
                    colaterals.push({ "id": element.id, 'text': element.collateral_name })
                });
            });
            this.$store.commit('repopostrequest/' + types.ADD_PREFFERED_COLLATERAL, colaterals);
        },
        selectedItems(value) {
            if (Object.values(value).length == 0)
                this.invited = null
            else {
                this.invited = Object.values(value)
                this.selectedFItems()
            }
        },
        hasError(value) {
            let key = value[0]
            if (value[1]) {
                if (!this.generalError[key]) {
                    this.generalError[key] = true; // Vue reactivity, ensuring new property is reactive
                }
            } else {
                if (this.generalError[key]) {
                    delete this.generalError[key]; // Vue reactivity, ensuring property deletion is reactive
                }
            }

        },
        setRates() {

        },

        newRequest(value) {
            var key = value[0]
            // if (this.postsRequests[key]) {
            // this.postsRequests[key] = value[1];
            this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST_OFFER, [key, value[1]]);
            // } else {
            // this.postsRequests[key] = value[1];
            // this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST_OFFER, [key, value[1]]);

            // }
        },
        selectedFItems() {
            this.seletectedFiObjects = this.allFis.filter(fiselected => this.invited.includes(fiselected.id));
        },
        addPostRequestCount() {
            this.submitRequest(true)
        },
        submitWithRates(value) {
            this.rates_and_deposits = value
            this.doSubmit()
        },
        submitRequest(addNewRequest = false) {
            for (let index = 0; index < this.postrequestsCount; index++) {
                const childComponent = this.$refs.requestitems[index];
                if (childComponent) {
                    childComponent.ableToSubmit(); // Call ableToSubmit if it's a function
                }
            }

            if (addNewRequest) {
                if (Object.keys(this.generalError).length > 0) {

                } else {
                    this.postrequestsCount += 1
                    // this.postsRequests.push(this.newreqdata);
                    let newRequest = this.newreqdata
                    newRequest.reqid = this.generateRandomValue()
                    this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST_OFFER, [this.postrequestsCount - 1, newRequest]);

                    // this.$set(this.postsRequests, this.postrequestsCount - 1, this.newreqdata)
                }
            } else {
                if (Object.keys(this.generalError).length > 0) {

                } else {
                    this.step = 'summary'
                }
            }



        },
        clearReq() {
            if (this.canchoosefi)
                this.invited = null
            this.postrequestsCount = 1
            let newRequestcl = this.newreqdata
            newRequestcl.reqid = this.generateRandomValue()
            this.$store.commit('repopostrequest/' + types.CLEAR_REQUEST_OFFERS, newRequestcl);
            this.clear = false


        },
        goBack() {

        },
        doSubmit() {
            this.submitting = true
            // invited fis
            // organization_id
            var data = new FormData()
            let reqcount = this.getAllRequestsOffers.length
            if (reqcount != 1) {
                this.successtitle = reqcount + "/" + reqcount + " requests have been posted."
            }
            // console.log(Object.keys(this.getAllRequestsOffers[0]))
            // console.log(Object.values(this.getAllRequestsOffers[0]))
            data.append('offers', JSON.stringify(this.getAllRequestsOffers))
            data.append('requestId', this.request_id)
            data.append('invite', this.invited_id)
            if (this.isedit)
                data.append('deposit_request_id', this.request_id)
            axios.post('/trade/CG/give-offers', data).then(response => {
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.submitting = false
                        window.location.href = "/repos/view-all-in-progress"
                        this.success = false
                    }, 3000)
                } else {
                    this.submitting = false
                    this.fail = true
                    this.confirmsubmit = false
                }
            }).catch(err => {
                this.submitting = false
                this.confirmsubmit = false
                this.fail = true
            })

        },
        doUpdate() {
            this.submitting = true
            // invited fis
            // organization_id
            var data = new FormData()
            let reqcount = this.getAllRequestsOffers.length
            if (reqcount != 1) {
                this.successtitle = reqcount + "/" + reqcount + " requests have been posted."
            }
            const updatedata = this.getAllRequestsOffers[0]
            // console.log(updatedata, "Update Data")
            data.append('currency', updatedata.currency ? updatedata.currency : 'CAD')
            data.append('investment_amount', updatedata.investment_amount)
            data.append('preferred_collateral', JSON.stringify(updatedata.preferred_collateral))
            data.append('term_length_type', updatedata.term_length_type)
            data.append('term_length', updatedata.term_length)
            data.append('trade_date', updatedata.trade_date)
            data.append('ctrequest', this.request_id)

            axios.post('/trade/CT/update-request', data).then(response => {
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.submitting = false
                        window.location.href = "/repos/repos-reviews"
                        this.success = false
                    }, 3000)
                } else {
                    this.submitting = false
                    this.fail = true
                    this.confirmsubmit = false
                }
            }).catch(err => {
                this.submitting = false
                this.confirmsubmit = false
                this.fail = true
            })

        },
        editRequest() {
            this.step = 'editrequest'
        },
        addRequest() {
            this.step = 'addrequest'
        },
        viewSummary() {
            this.submitRequest()
        },
        doDelete() {
            let value = this.valuetodelete
            this.$store.commit('repopostrequest/' + types.REMOVE_A_REQUEST_OFFER, value);
            if (this.postrequestsCount != 1)
                this.postrequestsCount -= 1
            this.generalError = {}
            this.valuetodelete = null
            this.deleteoffer = false

        },
        deleteRequest(value) {
            this.valuetodelete = value
            this.deleteoffer = true
            // this.postsRequests.splice(value, 1)
            // this.$store.commit('repopostrequest/' + types.REMOVE_A_REQUEST_OFFER, value);
            // if (this.postrequestsCount != 1)
            //     this.postrequestsCount -= 1
            // this.generalError = {}

        },
        preventNav(event) {
            // if (!this.isEditing) return
            // event.preventDefault()
            // event.returnValue = "Are you sure you want to leave"
        }
    }

}


</script>


<style>
.character-count {
    color: var(--Yield-Exchange-Colors-Darks, #252525);

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.advanc-option-click {

    color: var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4);
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    cursor: pointer;
}

.aditional-option-click {

    color: var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4);
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    cursor: pointer;
}

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>