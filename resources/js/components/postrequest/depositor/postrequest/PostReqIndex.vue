<template>
    <div class="w-100 d-flex justify-content-center align-items-center">
        <div class="w-75">
            <div v-if="step == 'addrequest' || step == 'editrequest'">
                <!-- requests sections -->
                <div v-if="!isedit && editRequestData == null">
                    <!-- <div>-->
                    <AddRequest @newRequest="newRequest" :formattedtimezone="formattedtimezone"
                        v-for="(value, i) in getAllRequests" :key="value.reqid" ref="requestitems" @hasError="hasError"
                        :request="value" :depositor_demo_setup="depositor_demo_setup" @deleteRequest="deleteRequest"
                        :count="i" :deposit_insurances='deposit_insurances' :candelete="postrequestsCount > 1"
                        :credit_rating_types='credit_rating_types' :products='products'>
                    </AddRequest>
                    <!-- </div> -->
                </div>
                <div v-else>
                    <AddRequest :isedit="isedit" :editData="getAllRequests" @newRequest="newRequest"
                        v-for="(value, i) in getAllRequests" :key="value.reqid" ref="requestitems"
                        :formattedtimezone="formattedtimezone" @hasError="hasError"
                        :depositor_demo_setup="depositor_demo_setup" @deleteRequest="deleteRequest" :count="i"
                        :deposit_insurances='deposit_insurances' :credit_rating_types='credit_rating_types'
                        :products='products'>
                    </AddRequest>
                </div>

                <div class="d-flex justify-content-start gap-2 mt-3" v-if="!depositor_demo_setup && !isedit">
                    <div @click="addPostRequestCount">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="29" viewBox="0 0 30 29" fill="none">
                            <path
                                d="M25 16.9154H17.5V24.1654H12.5V16.9154H5V12.082H12.5V4.83203H17.5V12.082H25V16.9154Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                    <p @click="addPostRequestCount" class="aditional-option-click">Add additional request</p>
                </div>
                <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                    <div>
                        <!-- <CustomSubmit @action="goBack()" :previous="true" title="Previous" /> -->
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <CustomSubmit v-if="!isedit" @action="clear = true" :outline="true" title="Clear" />
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


                        <CustomSubmit v-if="!depositor_demo_setup" :is-loading="submitting" @action="doSubmit"
                            title="Submit" />
                        <CustomSubmit v-else @action="step = 'setrates'" title="Next" />
                    </div>
                </div>
            </div>
            <!-- <div v-if="true"> -->
            <div v-if="step == 'choosefi'">
                <!-- requests sections -->
                <div v-if="allFis">
                    <FinancialInstitutionPage :selected_items="invited" @selectedItems="selectedItems"
                        :fi_data="allFis">
                    </FinancialInstitutionPage>
                </div>


                <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                    <div>
                        <CustomSubmit @action="editRequest()" :previous="true" title="Previous" />
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <!-- <CustomSubmit @action="editRequest()" :outline="true" title="Edit" /> -->
                        <CustomSubmit @action="fiGONext" title="Next" />
                    </div>
                </div>
            </div>
            <!-- <div v-if="step == 'addrates'"> -->
            <div v-if="step == 'setrates'">
                <!-- requests sections -->
                <div v-if="seletectedFiObjects">
                    <addRates :requests="getAllRequests" @dosubmit="submitWithRates" ref="submitrate"
                        :selected_items="invited" @selectedItems="setRates" :fi_data="seletectedFiObjects">
                    </addRates>
                </div>


                <div class=" w-100 d-flex justify-content-between my-4 gap-2">
                    <div>
                        <CustomSubmit @action="goBackToStep('summary')" :previous="true" title="Previous" />
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <!-- <CustomSubmit @action="editRequest()" :outline="true" title="Edit" /> -->
                        <CustomSubmit :is-loading="submitting" @action="$refs.submitrate.validateValues()"
                            title="Submit" />
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
                title="Please Select  atleast one financial Institition" :showm="selectfierror">
                <!-- <div class="ml-5 description-text-withdraw ">Your changes will be cleared from the request</div> -->
            </ActionMessage>

            <ActionMessage style="width: 600px;" @closedSuccessModal="confirmsubmit = false" @btnTwoClicked=""
                @btnOneClicked="confirmsubmit = false" btnOneText="No" btnTwoText="Yes"
                icon="/assets/dashboard/icons/question-new.svg" title="Sure to submit this offer?"
                :showm="confirmsubmit">
                <div class="ml-5 description-text-withdraw ">Your offer will be sent to the depositor</div>
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
import * as types from '../../../../store/modules/postrequestflow/mutation-types'


export default {
    components: { addRates, FISummarys, FinancialInstitutionPage, PostRequestSummary, CustomSubmit, AddRequest, CustomSelect, CustomCurrencyValueInput, FileUpload, JQueryCustomDatePicker, FormLabelRequired, CustomDateInput, CustomInput, CustomSubmit, CustomTextInput, ActionMessage, CustomSelectInput },
    props: ['deposit_insurances', 'organization', 'deposit_request', 'caninvitefis', 'credit_rating_types', 'products', 'formattedtimezone'],
    beforeMount() {
        if (this.deposit_insurances)
            this.deposit_insurance = JSON.parse(this.deposit_insurances)
        if (this.credit_rating_types)
            this.ratingtypes = JSON.parse(this.credit_rating_types)
        if (this.products) {
            this.availableproducts = JSON.parse(this.products)
            this.productType = this.availableproducts.map(element => element.description);
            // this.postsRequests[1] = this.newreqdata
        }
        if (this.organization) {
            let organization = JSON.parse(this.organization)
            this.organization_id = organization.id
        }
        const urlParams = new URLSearchParams(window.location.search);
        let isdemoSettup = urlParams.get('demo_setup');
        if (isdemoSettup) {
            this.depositor_demo_setup = true
        }
        if (this.deposit_request) {
            if (JSON.parse(this.deposit_request) !== null) {
                let dep_request = JSON.parse(this.deposit_request)
                this.request_id = dep_request.id
                this.isedit = true
                let date_of_deposit = this.formatDateTime(dep_request.date_of_deposit)
                // console.log(date_of_deposit)
                this.editRequestData = {
                    'req_id': this.generateRandomValue(),
                    'date_of_deposit': date_of_deposit,
                    'term_length': dep_request.term_length,
                    'product': dep_request.product_name,
                    'product_id': dep_request.product_id,
                    'deposit_currency': dep_request.currency,
                    'term_type': dep_request.term_length_type,
                    'lockout_period': dep_request.lockout_period_days,
                    'deposit_amount': dep_request.amount,
                    'compound_frequency': dep_request.compound_frequency,
                    'credit_rating': dep_request.requested_short_term_credit_rating,
                    'deposit_insurance': dep_request.requested_deposit_insurance,
                    'specinstructions': dep_request.special_instructions != 'null' ? dep_request.special_instructions : null,
                }

                // let newRequestbm = this.newreqdata
                // this.editRequestData.reqid = this.generateRandomValue()
                this.$store.commit('postrequestflow/' + types.ADD_NEW_REQUEST, [0, this.editRequestData]);

                dep_request.invited.forEach(element => {
                    this.editselectedFIs.push(element.organization_id)
                });
            }
        }
        window.addEventListener("beforeunload", this.preventNav)
    },

    beforeDestroy() {
        window.removeEventListener("beforeunload", this.preventNav);
    },

    mounted() {
        if (this.caninvitefis) {
            let choosefi = JSON.parse(this.caninvitefis)
            if (choosefi || this.depositor_demo_setup)
                this.canchoosefi = true
            else
                this.canchoosefi = false
            this.getInvitedFis()
        }
        // if (this.postsRequests.length == 0 && this.newreqdata) {
        if (this.getAllRequests.length == 0 && this.newreqdata) {
            let newRequestbm = this.newreqdata
            newRequestbm.reqid = this.generateRandomValue()
            this.$store.commit('postrequestflow/' + types.ADD_NEW_REQUEST, [0, newRequestbm]);

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
                'date_of_deposit': null,
                'term_length': null,
                'product': 'Short Term',
                'product_id': null,
                'deposit_currency': 'CAD',
                'term_type': 'Months',
                'lockout_period': null,
                'deposit_amount': null,
                'compound_frequency': 'At maturity',
                'credit_rating': 'Any/Not Rated',
                'deposit_insurance': 'Any',
                'specinstructions': null,
            },
            editRequestData: null,
            postsRequests: [],
            invited: null,
            editselectedFIs: [],
            seletectedFiObjects: [],
            allFis: null,
            canchoosefi: false,
            submitting: false,
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
            successtitle: 'Your request has been posted.'
        }
    },
    computed: {
        ...mapGetters('postrequestflow', ['getAllRequests']),

    },
    methods: {
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
            // console.log(date, "Date")
            let dfr = new Date(date).toLocaleString("en-US", { timeZone: JSON.parse(this.formattedtimezone) });
            const dateObj = new Date(dfr)

            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            const hours = String(dateObj.getHours()).padStart(2, '0');
            const minutes = String(dateObj.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}`;
            // return `${year}-${month}-${day} ${hours}:${minutes}`;
        },
        async getInvitedFis() {
            let invited = []
            await axios.get('/post-request-invites').then(response => {
                const FIs = response.data.aaData
                this.allFis = FIs
                FIs.forEach(element => {
                    invited.push(element.id)
                });
            });
            if (!this.canchoosefi) {
                this.invited = invited
            }
            if (this.canchoosefi && this.isedit) {
                this.invited = this.editselectedFIs
            }
        },
        fiGONext() {
            if (this.invited == null) {
                this.selectfierror = true
            } else
                this.step = 'summary'
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
        goBackToStep(step) {
            this.step = step
        },

        newRequest(value) {
            var key = value[0]
            this.$store.commit('postrequestflow/' + types.ADD_NEW_REQUEST, [key, value[1]]);


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
                    this.$store.commit('postrequestflow/' + types.ADD_NEW_REQUEST, [this.postrequestsCount - 1, newRequest]);

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
            this.$store.commit('postrequestflow/' + types.CLEAR_REQUEST, newRequestcl);
            this.clear = false


        },
        goBack() {

        },
        doSubmit() {
            // invited fis
            // organization_id
            var data = new FormData()
            let reqcount = this.getAllRequests.length
            if (reqcount != 1) {
                this.successtitle = reqcount + "/" + reqcount + " requests have been posted."
            }
            this.getAllRequests.forEach((element) => {
                let reqdata = element
                data.append('closing_date_time[]', reqdata.date_of_deposit)
                data.append('term_length[]', reqdata.term_length)
                data.append('product_id[]', reqdata.product)
                data.append('deposit_currency[]', reqdata.deposit_currency)
                data.append('term_type[]', reqdata.term_type.toLowerCase())
                data.append('lockout_period[]', reqdata.lockout_period)
                data.append('deposit_amount[]', reqdata.deposit_amount)
                data.append('compound_frequency[]', reqdata.compound_frequency)
                data.append('credit_rating[]', reqdata.credit_rating)
                data.append('deposit_insurance[]', reqdata.deposit_insurance)
                data.append('special_instructions[]', reqdata.specinstructions)
            });

            data.append('invited', this.invited.join(','))
            data.append('organization_id', this.organization_id)
            if (this.isedit)
                data.append('deposit_request_id', this.request_id)
            if (this.depositor_demo_setup) {
                data.append('depositor_demo_setup', this.depositor_demo_setup)
                data.append('rates_and_deposits', JSON.stringify(this.rates_and_deposits))
            }
            this.submitting = true
            axios.post('/post-request-submit', data).then(response => {
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        window.location.href = "/review-offers"
                        this.success = false
                        this.submitting = false

                    }, 3000)
                } else {
                    this.fail = true
                    this.confirmsubmit = false
                    this.submitting = false

                }
            }).catch(err => {
                this.confirmsubmit = false
                this.fail = true
                this.submitting = false

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
        deleteRequest(value) {
            // this.postsRequests.splice(value, 1)
            this.$store.commit('postrequestflow/' + types.REMOVE_A_REQUEST, value);
            if (this.postrequestsCount != 1)
                this.postrequestsCount -= 1
            this.generalError = {}
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