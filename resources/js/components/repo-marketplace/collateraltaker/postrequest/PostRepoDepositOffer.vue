<template>
    <div class="w-100 d-flex justify-content-center">
        <div class="w-75" style="max-width: 1000px;">
            <div v-if="step == 'addrequest' || step == 'editrequest'">
                <!-- requests sections -->
                <div>
                    <!-- <div>-->
                    <AddRequest :holidays="holidays" :day__counts="daycount" @newRequest="newRequest"
                        :formattedtimezone="formattedtimezone" v-for="(value, i) in getAllRequests" :key="value.reqid"
                        ref="requestitems" @hasError="hasError" :editData="value" :request="value"
                        :depositor_demo_setup="depositor_demo_setup" @deleteRequest="deleteRequest" :isedit="isedit"
                        :count="i" :candelete="postrequestsCount > 1">
                    </AddRequest>

                    <!-- </div> -->
                </div>
                <!-- <div v-else>
                <AddRequest :isedit="isedit" :editData="getAllRequests" @newRequest="newRequest"
                    :formattedtimezone="formattedtimezone" ref="requestitems" @hasError="hasError"
                    :depositor_demo_setup="depositor_demo_setup" @deleteRequest="deleteRequest" :count="0">
                </AddRequest>
            </div> -->


                <div class=" w-100 d-flex justify-content-between my-4 gap-2"
                    v-if="userCan(userLoggedIn, 'depositor/repos/post-request')">
                    <div>
                        <div class="d-flex justify-content-start gap-2 mt-3" v-if="!depositor_demo_setup && !isedit">
                            <div @click="addPostRequestCount">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="29" viewBox="0 0 30 29"
                                    fill="none">
                                    <path
                                        d="M25 16.9154H17.5V24.1654H12.5V16.9154H5V12.082H12.5V4.83203H17.5V12.082H25V16.9154Z"
                                        fill="#5063F4" />
                                </svg>
                            </div>
                            <p @click="addPostRequestCount" class="aditional-option-click">Add additional request</p>
                        </div> <!-- <CustomSubmit @action="goBack()" :previous="true" title="Previous" /> -->
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <CustomSubmitWithIcon icon="/assets/dashboard/icons/aiicons/ai-icon-logo.svg"
                            @action="confirmAIPos" :outline="true" title="Post With Ai" />
                        <CustomSubmit @action="clear = true" :outline="true" title="Clear" />
                        <CustomSubmit @action="viewSummary" title="Next" />
                    </div>
                </div>
            </div>
            <div v-if="step == 'summary'">
                <!-- requests sections -->
                <template v-for="(request, i) in getAllRequests">
                    <PostRequestSummary :request="request" :count="i"></PostRequestSummary>
                </template>

                <template v-if="canchoosefi">
                    <FISummarys :finstitutionss="seletectedFiObjects"></FISummarys>
                </template>

                <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                    <div>
                        <CustomSubmit @action="editRequest()" :previous="true" title="Previous" />
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <CustomSubmit @action="editRequest()" :outline="true" title="Edit" />


                        <CustomSubmit v-if="!depositor_demo_setup" @action="doSubmit" title="Submit" />
                        <CustomSubmit v-else @action="step = 'setrates'" title="Next" />
                    </div>
                </div>
            </div>
            <!-- <div v-if="true"> -->
            <div v-if="step == 'choosefi'">
                <!-- requests sections -->
                <div>
                    <FinancialInstitutionPage :selected_items="invited" @selectedItems="selectedItems"
                        :fi_data="allFis">
                    </FinancialInstitutionPage>
                </div>


                <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                    <div>
                        <CustomSubmit @action="editRequest()" :previous="true" title="Previous" />
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <CustomSubmit v-if="!isedit" :isLoading="submitting" @action="fiGONext" title="Submit" />
                        <CustomSubmit v-if="isedit" :isLoading="submitting" @action="fiGONext" title="Update" />

                    </div>
                </div>
            </div>
            <!-- <div v-if="step == 'addrates'"> -->
            <div v-if="step == 'setrates'">
                <!-- requests sections -->
                <div v-if="seletectedFiObjects">
                    <addRates :day__counts="daycount" :requests="getAllRequests" @dosubmit="submitWithRates"
                        ref="submitrate" :selected_items="invited" @selectedItems="setRates"
                        :fi_data="seletectedFiObjects">
                    </addRates>
                </div>


                <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                    <div>
                        <CustomSubmit @action="editRequest()" :previous="true" title="Previous" />
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <!-- <CustomSubmit @action="editRequest()" :outline="true" title="Edit" /> -->
                        <CustomSubmit @action="$refs.submitrate.validateValues()" title="Submit" />
                    </div>
                </div>
            </div>
            <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
                @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" :title="successtitle"
                btnOneText="" btnTwoText="" :showm="success">
                <div class="ml-5 description-text-withdraw "> All Financial institutions are being notified..</div>
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
            <ActionMessage style="width: 600px;" @closedSuccessModal="selectfierror = false" @btnTwoClicked=""
                @btnOneClicked="selectfierror = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
                title="Please Select  at least one Collateral Giver" :showm="selectfierror">
                <!-- <div class="ml-5 description-text-withdraw ">Your changes will be cleared from the request</div> -->
            </ActionMessage>

            <ActionMessage style="width: 600px;" @closedSuccessModal="confirmsubmit = false" @btnTwoClicked=""
                @btnOneClicked="confirmsubmit = false" btnOneText="No" btnTwoText="Yes"
                icon="/assets/dashboard/icons/question-new.svg" title="Sure to submit this offer?"
                :showm="confirmsubmit">
                <div class="ml-5 description-text-withdraw ">Your offer will be sent to the depositor</div>
            </ActionMessage>
            <ActionMessage style="width: 600px;" @closedSuccessModal="confimraipost = false" @btnTwoClicked="chatWithAi"
                @btnOneClicked="confimraipost = false" btnOneText="No" btnTwoText="Yes"
                icon="/assets/dashboard/icons/question-new.svg" title="Do you want to proceed with AI?"
                :showm="confimraipost">
                <div class="ml-5 description-text-withdraw ">Changes you made will not be saved</div>
            </ActionMessage>
            <ActionMessage style="width: 600px;" @closedSuccessModal="deleterequest = false" @btnTwoClicked="doDelete"
                @btnOneClicked="deleterequest = false" btnOneText="No" btnTwoText="yes" icon="/assets/signup/danger.svg"
                title="Remove this request?" :showm="deleterequest">
                <div class="ml-5 description-text-withdraw ">Are you sure you want to remove this request</div>
            </ActionMessage>

        </div>
    </div>
</template>

<script>
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSelectInput from '../../../auth/signup/shared/CustomSelectInput.vue'
import CustomTextInput from '../../../auth/signup/shared/CustomTextInput.vue'
import CustomDateInput from '../../../auth/signup/shared/CustomDateInput.vue'
import PostRequestSummary from './PostRequestSummary.vue'
// import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'

import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";
import FinancialInstitutionPage from './FinancialInstitutionPage.vue'

import CustomInput from '../../../shared/CustomInput.vue';
import CustomSelect from '../../../shared/CustomSelect.vue';
// import CustomDateInput
import FileUpload from "../../../shared/FileUpload";
import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";
import AddRequest from './AddRequest.vue'
import FISummarys from './FISummarys.vue'
import addRates from './addRates.vue'
import { mapGetters } from 'vuex'
import * as types from '../../../../store/modules/repos/mutation-types'
import { userCan } from '../../../../utils/GlobalUtils'
import CustomSubmitWithIcon from '../../../shared/Buttons/CustomSubmitWithIcon.vue'


export default {
    components: { CustomSubmitWithIcon, addRates, FISummarys, FinancialInstitutionPage, PostRequestSummary, CustomSubmit, AddRequest, CustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
    props: ['organization', 'deposit_request', 'caninvitefis', 'formattedtimezone', 'userLoggedIn'],
    async beforeMount() {
        this.getAllDayCounts()
        this.getAllHolidays()
        this.getPrefferedCollateralTypes()

        // this.getSettlementDates()

        if (this.organization) {
            let organization = JSON.parse(this.organization)
            this.organization_id = organization.id
        }
        const url = new URL(window.location.href);
        const request_id = url.searchParams.get('request_id');
        let request = null
        if (request_id != null) {
            this.request_id = request_id
            this.isedit = true

            await axios.get('/trade/CT/get-trade-request?requestId=' + request_id).then(response => {
                request = response.data
            })

            if (request) {
                this.editRequestData = {
                    'req_id': this.generateRandomValue(),
                    'trade_date': request?.trade_time.split(' ')[0],
                    'term_length': request?.term_length,
                    'term_length_type': request.term_length_type,
                    'currency': request?.currency,
                    'investment_amount': request?.investment_amount,
                    'preferred_collateral': [],
                    'settlementDate': request?.settlement_date.split(' ')[0],
                    'convention_id': request?.interest_calculation_options_id,
                    // 'settlementDate': request?.trade_allowed_settlement_period_id ? request?.trade_allowed_settlement_period_id : null
                    // 'settlementDate': request?.trade_allowed_settlement_period_id ? request?.trade_allowed_settlement_period_id : null
                }
                if (request.preferred_collaterals.length > 0) {
                    request.preferred_collaterals.forEach(element => {
                        this.editRequestData['preferred_collateral'].push(element.preferred_collateral_id)
                    });
                } else (
                    this.editRequestData['preferred_collateral'].push(0)
                )

                // request.preferred_collaterals.forEach(element => {
                //     this.editRequestData['preferred_collateral'].push(element.preferred_collateral_id)
                // });
                let invited = []
                this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST, [0, this.editRequestData]);
                request.invited_c_gs.forEach(element => {
                    invited.push(element.organization.id)
                });
                this.invited = invited

            }

        }
        window.addEventListener("beforeunload", this.preventNav)
    },

    beforeDestroy() {
        window.removeEventListener("beforeunload", this.preventNav);
    },


    mounted() {

        // this.getPrefferedCollateralTypes()
        if (this.getAllRequests.length == 0 && this.newreqdata) {
            let newRequestbm = this.newreqdata
            newRequestbm.reqid = this.generateRandomValue()
            this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST, [0, newRequestbm]);

        }
    },

    data() {
        return {
            termLength: 'Days',
            step: 'addrequest',
            postrequestsCount: 1,
            depositor_demo_setup: false,
            generalError: {},
            newreqdata: {
                'reqid': null,
                'trade_date': null,
                'term_length': null,
                'term_length_type': 'Days',
                'currency': 'CAD',
                'deposit_amount': null,
                'investment_amount': null,
                'preferred_collateral': null,
                'settlementDate': null,
                'convention_id': 2,

            },
            submitting: false,
            deleterequest: false,
            requesttodelete: null,
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
            confimraipost: false,
            selectfierror: false,
            request_id: null,
            rates_and_deposits: null,
            organization_id: null,
            daycount: null,
            holidays: null,
            successtitle: 'Your request has been posted.'
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllRequests', 'getAllSelectedFIS']),

    },
    methods: {
        confirmAIPos() {
            if (this.getAllRequests.length > 1) {
                this.confimraipost = true
            } else {
                let currnct_investment = this.getAllRequests[0]
                if (currnct_investment.investment_amount || currnct_investment.preferred_collateral || currnct_investment.term_length) {
                    this.confimraipost = true
                } else {
                    this.chatWithAi()
                }
                // console.log(this.getAllRequests[0], "First zrequest")
            }
        },
        chatWithAi() {
            window.location.href = "/repos/post-request-with-ai"
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
                if (res.data.length > 0) {
                    this.daycount = res.data
                    console.log(this.daycount, "day count")

                }
                // console.log(res.data, "Holdays")
                // this.formattedtimezone = JSON.stringify(res.data)
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
        userCan(a, b) {
            return userCan(a, b)
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
                colaterals.push({ "id": 0, 'text': 'No Preference' })

                collateral.forEach(element => {
                    colaterals.push({ "id": element.id, 'text': element.collateral_name })
                });
            });
            this.$store.commit('repopostrequest/' + types.ADD_PREFFERED_COLLATERAL, colaterals);
        },
        fiGONext() {
            if (this.getAllSelectedFIS == null || this.getAllSelectedFIS.length == 0) {
                this.selectfierror = true
            } else {
                if (this.isedit) {
                    this.doUpdate()
                } else {
                    this.doSubmit()
                }
            }

            // this.step = 'summary'
        },
        selectedItems(value) {
            if (value) {
                if (Object.values(value).length == 0)
                    this.invited = null
                else {
                    this.invited = Object.values(value)
                    this.selectedFItems()
                }
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
            this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST, [key, value[1]]);
            // } else {
            // this.postsRequests[key] = value[1];
            // this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST, [key, value[1]]);

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
                    this.$store.commit('repopostrequest/' + types.ADD_NEW_REQUEST, [this.postrequestsCount - 1, newRequest]);

                    // this.$set(this.postsRequests, this.postrequestsCount - 1, this.newreqdata)
                }
            } else {
                if (Object.keys(this.generalError).length > 0) {

                } else {
                    if (this.canchoosefi)
                        this.step = 'choosefi'
                    else
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
            this.$store.commit('repopostrequest/' + types.CLEAR_REQUEST, newRequestcl);
            this.clear = false


        },
        goBack() {

        },
        doSubmit() {
            this.submitting = true
            // invited fis
            // organization_id
            var data = new FormData()
            let reqcount = this.getAllRequests.length
            if (reqcount != 1) {
                this.successtitle = reqcount + "/" + reqcount + " requests have been posted."
            }
            data.append('tradeRequests', JSON.stringify(this.getAllRequests))
            data.append('invited', JSON.stringify(this.getAllSelectedFIS))
            if (this.isedit)
                data.append('deposit_request_id', this.request_id)
            axios.post('/trade/CT/create-request', data).then(response => {
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.submitting = false
                        if (userCan(this.userLoggedIn, 'depositor/repos/review-offers'))
                            window.location.href = "/repos/repos-reviews"
                        else
                            window.location.reload()
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
            let reqcount = this.getAllRequests.length
            if (reqcount != 1) {
                this.successtitle = reqcount + "/" + reqcount + " requests have been posted."
            }
            const updatedata = this.getAllRequests[0]
            data.append('currency', updatedata.currency)
            data.append('investment_amount', updatedata.investment_amount)
            data.append('preferred_collateral', JSON.stringify(updatedata.preferred_collateral))
            data.append('term_length_type', updatedata.term_length_type)
            data.append('term_length', updatedata.term_length)
            data.append('trade_date', updatedata.trade_date)
            data.append('settlementDate', updatedata.settlementDate)
            data.append('ctrequest', this.request_id)
            data.append('convention_id', updatedata.convention_id)
            data.append('invited', JSON.stringify(this.getAllSelectedFIS))


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
            let value = this.requesttodelete
            this.$store.commit('repopostrequest/' + types.REMOVE_A_REQUEST, value);
            if (this.postrequestsCount != 1)
                this.postrequestsCount -= 1
            this.generalError = {}
            this.deleterequest = false
            this.requesttodelete = null

        },
        deleteRequest(value) {
            this.requesttodelete = value
            this.deleterequest = true
            // this.postsRequests.splice(value, 1)
            // this.$store.commit('repopostrequest/' + types.REMOVE_A_REQUEST, value);
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