<template>
    <div class="w-100 mt-2">
        <!-- header -->
        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="text-div-test"> {{ `${(count + 1)}. ${basketname != null ? basketname :
                            'New' +
                            " Basket"}` }} </div>
                    </div>
                    <div @click="viewMore1 = !viewMore1"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div>
                </div>
            </div>
        </div>

        <!-- header 2 -->

        <div class="row my-3 w-100 mx-auto bg-white" v-if="viewMore1">

            <div class="col-md-12">
                <div class="d-flex justify-content-between gap-3 split-text">
                    <p class="split-text">
                        Will this basket be used for triparty transactions?
                    </p>
                    <div class="d-flex justify-content-start gap-3 split-text">
                        Yes <input v-model="istriparty" :value="true" type="radio" :name="`triparty` + count">
                        No<input v-model="istriparty" :value="false" type="radio" :name="`triparty` + count">
                    </div>
                </div>
                <span v-if="istriparty_selected_error" class="error-message">
                    Make a selection to proceed to the next step
                </span>
            </div>
            <template v-if="istriparty != null">
                <div class="col-md-12 mb-20 gap-2">
                    <p class="p-0 m-0 basket-description mb-20">Basket Description</p>
                    <p class="p-0 m-0 split-text ">{{ descripition ? descripition : '-' }}</p>
                </div>
                <div class="col-md-4 mb-20 " v-if="istriparty">
                    <FormLabelRequired labelText="Basket Type" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />

                    <NewCustomSelect style="margin-top: 4px;" :haserror="basketTypeError" :options="baskets"
                        idkey="encoded_id" valuekey="basket_name" placeholder=" Select basket"
                        :defaultValue="basketType" @change="changeBasketType" />

                    <div v-if="basketTypeError" class="error-message">
                        {{ basketTypeError }}
                    </div>
                </div>
                <div class="col-md-4 mb-20" v-else>
                    <FormLabelRequired labelText="Collateral" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />

                    <NewCustomSelect style="margin-top: 4px;" :haserror="collateralTypeError" :options="collaterals"
                        idkey="encoded_id" valuekey="collateral_name" placeholder=" Select collateral"
                        :defaultValue="collateralType" @change="changeCollateralType" />

                    <div v-if="collateralTypeError" class="error-message">
                        {{ collateralTypeError }}
                    </div>
                </div>

                <div class="col-md-4 mb-20">

                    <FormLabelRequired labelText="Currency" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />

                    <NewCustomSelect style="margin-top: 4px;" :haserror="selectedCurrencyError"
                        :options="systemCurrencies" idkey="" valuekey="" placeholder="Select currency"
                        :defaultValue="selected_currency" @change="changeSelectedCurrency" />


                    <div v-if="selectedCurrencyError" class="error-message">
                        {{ selectedCurrencyError }}
                    </div>
                </div>

                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Rating" :required="true" :showHelperText="false" helperText="Product"
                        helperId="product" />

                    <NewCustomSelect style="margin-top: 4px;" :haserror="basketRatingError" :options="ratings" idkey=""
                        valuekey="" placeholder=" Select rating" :defaultValue="rating" @change="changeRating" />

                    <div v-if="basketRatingError" class="error-message">
                        {{ basketRatingError }}
                    </div>
                </div>
                <template v-if="istriparty">
                    <div class=" row" v-for="(cp, index) in counter_parties" :key="cp.counter_id">
                        <div class="col-md-1 d-flex justify-content-center align-items-center">
                            <p class="p-0 m-0 split-text">{{ index + 1 }}.</p>
                        </div>
                        <div class="col-md-5 mb-20 ">
                            <FormLabelRequired labelText="Counterparty" :required="true" :showHelperText="false"
                                helperText="Product" helperId="product" />

                            <NewCustomSelect style="margin-top: 4px;" :haserror="counter_party_errors[index]"
                                :options="possible_counter_parties" idkey="id" valuekey="name"
                                placeholder="Select counter party" :defaultValue="counter_parties[index].counterTyID"
                                @change="ChangeCounterParty($event, index)" />

                            <div v-if="counter_party_errors[index]" class="error-message">
                                {{ counter_party_errors[index] }}
                            </div>
                        </div>
                        <div class="col-md-5 mb-20 ">
                            <FormLabelRequired labelText="Basket ID" :required="true" :showHelperText="false"
                                helperText="Interest Rate Offer" helperId="PDSHId" />
                            <CustomInput inputType="text" inputStyle="font-size:14px !important" p-style="width:100%"
                                id="rate" name="Enter basket Id" :has-validation="true"
                                @inputChanged="basketIDChange($event, index)" input-type="text"
                                :defaultValue="counter_parties[index].basketId"
                                :hasSpecificError="basketId_errors[index]" :maxlength="16" />
                            <div v-if="basketId_errors[index]" class="error-message">
                                {{ basketId_errors[index] }}
                            </div>
                        </div>
                        <div class="col-md-1 d-flex justify-content-center align-items-center"
                            v-if="counter_parties.length > 1">
                            <img style="width: 13.333px;height: 15px; cursor: pointer;"
                                @click="removeCounterPartyPrompt(index)" src="/assets/images/icons/deleteicon.svg"
                                alt="" srcset="">
                        </div>
                    </div>
                    <div class="d-flex justify-content-start gap-2 mt-3">
                        <div @click="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="30" viewBox="0 0 31 30"
                                fill="none">
                                <path
                                    d="M21.75 12.5L19.25 5M9.25 12.5L11.75 5M15.5 25H9.555C8.65925 25 7.7931 24.6793 7.11329 24.096C6.43347 23.5127 5.9849 22.7053 5.84875 21.82L4.28 12.88C4.22521 12.5237 4.24811 12.1598 4.34713 11.8132C4.44615 11.4666 4.61895 11.1456 4.85368 10.872C5.08841 10.5984 5.37952 10.3789 5.70705 10.2284C6.03458 10.0779 6.39079 9.99996 6.75125 10H24.25C24.6105 9.99996 24.9667 10.0779 25.2942 10.2284C25.6217 10.3789 25.9128 10.5984 26.1476 10.872C26.3823 11.1456 26.5551 11.4666 26.6541 11.8132C26.7531 12.1598 26.776 12.5237 26.7212 12.88L26.2737 15.43"
                                    stroke="#5063F4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M24.25 27.5V20M24.25 20L28 23.75M24.25 20L20.5 23.75M13 17.5C13 18.163 13.2634 18.7989 13.7322 19.2678C14.2011 19.7366 14.837 20 15.5 20C16.163 20 16.7989 19.7366 17.2678 19.2678C17.7366 18.7989 18 18.163 18 17.5C18 16.837 17.7366 16.2011 17.2678 15.7322C16.7989 15.2634 16.163 15 15.5 15C14.837 15 14.2011 15.2634 13.7322 15.7322C13.2634 16.2011 13 16.837 13 17.5Z"
                                    stroke="#5063F4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <p class="aditional-option-click" @click="addCounterParty">Add more counterparties</p>
                    </div>
                </template>
                <template v-else>
                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Issuer" :required="true" :showHelperText="false"
                            helperText="Product" helperId="product" />

                        <NewCustomSelect style="margin-top: 4px;" :haserror="issuerError" :options="collateral_issuer"
                            idkey="id" valuekey="name" placeholder="Select issuer" :defaultValue="issuer"
                            @change="changeIssuer($event)" />
                        <div v-if="issuerError" class="error-message">
                            {{ issuerError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="CUSIP Number" :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput inputType="text" inputStyle="font-size:14px !important" p-style="width:100%"
                            id="rate" name="Enter CUSIP No" :has-validation="true" @inputChanged="cucipNumber($event)"
                            input-type="text" :defaultValue="cucip_no" :maxlength="9" :hasSpecificError="cucipError" />
                        <div v-if="cucipError" class="error-message">
                            {{ cucipError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">

                        <FormLabelRequired labelText="Maturity Date" :required="true" :showHelperText="false"
                            helperText="Trade Date" helperId="PDSHId" />
                        <JQueryCustomDatePicker inputStyle="font-size:14px !important" v-if="formattedtimezone"
                            :cannotpicktime="true" style="margin-top: 4px;" :hasError="maturityError" :id="count"
                            :start_date="addWeekdays(0)" :end_date="null" :formattedtimezone="formattedtimezone"
                            placeholder="Maturity date" :selected_date="maturityDate" v-model="maturityDate" />
                        <div v-if="maturityError" class="error-message">
                            {{ maturityError }}
                        </div>
                    </div>
                </template>
            </template>
            <div class="d-flex justify-content-between gap-2">
                <div class="d-flex justify-content-start gap-2" v-if="candelete" @click="deleteRequest">
                    <p class="advanc-option-click">Remove Basket </p>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.2502 2.5H8.75022C8.58445 2.5 8.42548 2.56585 8.30827 2.68306C8.19106 2.80027 8.12522 2.95924 8.12522 3.125V3.75H11.8752V3.125C11.8752 2.95924 11.8094 2.80027 11.6922 2.68306C11.5749 2.56585 11.416 2.5 11.2502 2.5ZM13.7502 3.75V3.125C13.7502 2.46196 13.4868 1.82607 13.018 1.35723C12.5491 0.888392 11.9133 0.625 11.2502 0.625H8.75022C8.08717 0.625 7.45129 0.888392 6.98245 1.35723C6.51361 1.82607 6.25022 2.46196 6.25022 3.125V3.75H2.81396C2.56532 3.75 2.32687 3.84877 2.15105 4.02459C1.97524 4.2004 1.87646 4.43886 1.87646 4.6875C1.87646 4.93614 1.97524 5.1746 2.15105 5.35041C2.32687 5.52623 2.56532 5.625 2.81396 5.625H3.20396L3.60021 15.1562C3.64053 16.1231 4.05301 17.0368 4.75141 17.7066C5.44981 18.3763 6.38007 18.7502 7.34771 18.75H12.654C13.6214 18.7499 14.5513 18.3759 15.2495 17.7062C15.9476 17.0364 16.3599 16.1228 16.4002 15.1562L16.7977 5.625H17.1877C17.4364 5.625 17.6748 5.52623 17.8506 5.35041C18.0264 5.1746 18.1252 4.93614 18.1252 4.6875C18.1252 4.43886 18.0264 4.2004 17.8506 4.02459C17.6748 3.84877 17.4364 3.75 17.1877 3.75H13.7502ZM14.9202 5.625H5.08021L5.47397 15.0775C5.49397 15.561 5.70014 16.0181 6.04935 16.3531C6.39857 16.6881 6.86379 16.8751 7.34771 16.875H12.654C13.1377 16.8748 13.6026 16.6876 13.9515 16.3526C14.3005 16.0177 14.5065 15.5608 14.5265 15.0775L14.9202 5.625ZM7.18772 8.125V14.375C7.18772 14.6236 7.28649 14.8621 7.4623 15.0379C7.63812 15.2137 7.87657 15.3125 8.12522 15.3125C8.37386 15.3125 8.61231 15.2137 8.78813 15.0379C8.96394 14.8621 9.06272 14.6236 9.06272 14.375V8.125C9.06272 7.87636 8.96394 7.6379 8.78813 7.46209C8.61231 7.28627 8.37386 7.1875 8.12522 7.1875C7.87657 7.1875 7.63812 7.28627 7.4623 7.46209C7.28649 7.6379 7.18772 7.87636 7.18772 8.125ZM11.8752 7.1875C12.1239 7.1875 12.3623 7.28627 12.5381 7.46209C12.7139 7.6379 12.8127 7.87636 12.8127 8.125V14.375C12.8127 14.6236 12.7139 14.8621 12.5381 15.0379C12.3623 15.2137 12.1239 15.3125 11.8752 15.3125C11.6266 15.3125 11.3881 15.2137 11.2123 15.0379C11.0365 14.8621 10.9377 14.6236 10.9377 14.375V8.125C10.9377 7.87636 11.0365 7.6379 11.2123 7.46209C11.3881 7.28627 11.6266 7.1875 11.8752 7.1875Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="removecp = false" @btnTwoClicked="removeCounterParty"
            @btnOneClicked="removecp = false" btnOneText="No" btnTwoText="Yes" icon="/assets/signup/danger.svg"
            title="Delete this counterParty" :showm="removecp">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to remove this counter party? </div>
        </ActionMessage>
    </div>
</template>

<script>
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import JQueryCustomDatePicker from '../../../shared/JQueryCustomDatePicker.vue';
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';

import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";


import CustomInput from '../../../shared/CustomInput.vue';

import { mapGetters } from 'vuex'
import { capitalize } from '../../../../utils/commonUtils';
import NewCustomSelect from '../../../shared/NewCustomSelect.vue';

export default {
    components: { NewCustomSelect, ActionMessage, FormLabelRequired, CustomInput, CustomSubmit, JQueryCustomDatePicker },
    props: ['prime_rate', 'candelete', 'editData', 'depositor_demo_setup', 'isedit', 'formattedtimezone', 'tripartycheck', 'count', 'basket', 'defcurrency'],
    beforeMount() {
        if (this.basket) {
            this.setDefaults(this.basket)
        }
        this.getCounterParties()
        this.getCollateteralIssure()
        this.getCollaterals()
        this.getBasketTypes()

        if (this.tripartycheck != null) {
            this.istriparty = this.tripartycheck
        }


    },
    mounted() {

    },

    data() {
        return {
            viewMore1: true,
            basketType: null,
            collateralType: null,
            selected_currency: null,
            rating: null,
            counter_party: null,
            basketId: null,
            istriparty: null,
            istriparty_selected_error: false,
            issuer: null,
            maturityDate: null,
            cucip_no: null,
            baskets: [],
            removecp: false,
            cp_to_remove: null,
            // errors
            basketTypeError: null,
            collateralTypeError: null,
            basketIDError: null,
            counterPartyError: null,
            basketRatingError: null,
            issuerError: null,
            cucipError: null,
            maturityError: null,
            selectedCurrencyError: null,
            collateralIssureError: null,
            possible_counter_parties: [],
            collateral_issuer: [],
            reqid: null,
            selected_basket: null,
            descripition: null,
            basketname: null,

            // counter parties
            collaterals: [],
            basketId_errors: [],
            counter_party_errors: [],
            counter_party_count: 1,
            counter_parties: [],
            counterParty: {
                'counter_id': null,
                'counterTyID': null,
                'basketId': null,
            }

        }
    },
    computed: {
        systemCurrencies() {
            return this.$store.getters.systemCurrencies
        },
        ratings() {
            return this.$store.getters.systemRating
        },

        // ...mapGetters('systemCurrencies'),
        ...mapGetters('basket', ['getAllBaskets']),

        ...mapGetters('repopostrequest', ['getPrimeRates', 'getsettlemntdate', 'getgloabalproducts']),
    },
    methods: {

        // counter party logic
        // add counter party
        addCounterParty() {
            var newCounterparty = {
                'counter_id': this.generateRandomValue(),
                'counterTyID': null,
                'basketId': null,
            }
            if (this.canSubmit())
                this.counter_parties.push(newCounterparty)

        },

        canSubmit() {
            let haserror = false
            this.counter_parties.forEach((element, index) => {
                if (element.counterTyID == null) {
                    this.counter_party_errors.splice(index, 1, "This field is required")
                    haserror = true
                }
                if (element.basketId == null || (this.basketId_errors[index] && this.basketId_errors[index] != null)) {
                    haserror = true
                    this.basketId_errors.splice(index, 1, "This field is required")
                }
            })
            return !haserror
        },
        removeCounterPartyPrompt(request_id) {
            this.cp_to_remove = request_id
            this.removecp = true
        },
        removeCounterParty() {
            this.counter_parties.splice(this.cp_to_remove, 1)
            this.basketId_errors.splice(this.cp_to_remove, 1)
            this.counter_party_errors.splice(this.cp_to_remove, 1)
            this.removecp = false

        },
        editCounterParty() {

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

        // end counter party logic
        createBasket() {
            if (this.basketType == null) {
                this.basketType_selected_error = true
            } else {
                this.basketType_selected_error = false
            }
        },
        changeCollateralType(event) {
            this.collateralType = event
            console.log(event, "event")
            this.collateralTypeError = null
            if (this.collateralType == null) {
                // this.collateralTypeError = 'This field is required'
            } else {
                this.selected_basket = this.collaterals.find(element => element.encoded_id == event)
                this.basketname = this.selected_basket.collateral_name
                this.descripition = this.selected_basket.collateral_description

            }
        },
        changeBasketType(event) {
            this.selected_basket = this.baskets.find(element => element.encoded_id == event)
            this.basketname = this.selected_basket.basket_name
            this.descripition = this.selected_basket.basket_description
            this.basketType = event
            this.basketTypeError = null
        },
        changeSelectedCurrency(event) {
            this.selected_currency = event
            this.selectedCurrencyError = null
            this.validateBillateral()
        },
        changeRating(event) {
            this.rating = event
            this.basketRatingError = null
            this.validateBillateral()

        },

        ChangeCounterParty(event, index) {
            this.counter_party = event
            this.counter_parties[index].counterTyID = event
            this.counter_party_errors.splice(index, 1, null)
            this.validateCounterParty(index)
            this.validateInArray(index)

        },
        changeIssuer(event) {
            this.issuer = event
            this.issuerError = null
            this.validateBillateral()
        },
        basketIDChange(event, index) {
            let value = event
            let valcount = value.length
            this.counter_parties[index].basketId = value.toUpperCase()
            if (valcount < 16 || valcount > 16)
                this.basketId_errors[index] = "Basket ID must be 16 alphanumeric characters (" + valcount + "/16)"
            else {
                this.basketId_errors.splice(index, 1)
                this.validateCounterParty(index)
                this.validateInArray(index)
            }
        },
        addWeekdays(daysToAdd = 0) {
            var currentDate = new Date();
            var addedDays = 0;
            while (addedDays < daysToAdd) {
                currentDate.setDate(currentDate.getDate() + 1);
                if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
                    addedDays++;
                }
            }
            return currentDate;
        },
        cucipNumber(event) {
            let value = event
            let valcount = value.length
            this.cucip_no = value.toUpperCase()
            if (valcount < 9 || valcount > 9)
                this.cucipError = "CUCIP number must be 9 alphanumeric characters (" + valcount + "/9)"
            else {
                this.validateBillateral()
                this.cucipError = null
            }


        },
        getCounterParties() {
            axios.get('/common/trade/get-counterparties').then(response => {
                let organizations = response.data.data
                let possible_counter_parties = []
                if (organizations.length > 0) {
                    organizations.forEach(element => {
                        possible_counter_parties.push(
                            { 'id': element.encoded_id, 'name': element.name }
                        )
                    });
                }
                this.possible_counter_parties = possible_counter_parties
            })
        },
        getCollateteralIssure() {
            axios.get('/trade/CG/get-colleterals-issuers').then(response => {
                let organizations = response.data
                let collateral_issuer = []
                if (organizations.length > 0) {
                    organizations.forEach(element => {
                        collateral_issuer.push(
                            { 'id': element.encoded_id, 'name': element.name }
                        )
                    });
                }
                this.collateral_issuer = collateral_issuer
            })
        },
        getCollaterals() {
            axios.get('/common/trade/get-colletarals-list?disabled=0').then(response => {
                let collateral = response.data
                if (collateral.length > 0) {
                    this.collaterals = collateral
                }
            })
        },
        getBasketTypes() {
            axios.get('/common/trade/get-basket-types?disabled=0').then(response => {
                let baskets = response.data
                let type = this.istriparty
                this.baskets = baskets.length > 0 ? baskets : []
            })
        },
        validateInArray(incomingindex) {
            let thridapartyorg = this.counter_parties[incomingindex].counterTyID
            let basket_id = this.counter_parties[incomingindex].basketId
            let itemfound = false
            this.counter_parties.forEach((element, index) => {
                this.basketId_errors[index] = this.basketId_errors[index] ? this.basketId_errors[index] : null

                if (incomingindex != index && element.counterTyID == thridapartyorg && element.basketId == basket_id) {
                    this.basketId_errors.splice(incomingindex, 1, "Bakset ID is already added to the list")
                }

            })
        },
        validateCounterParty(index) {
            var data = new FormData()
            data.append('basketType', this.basketType)
            data.append('counterTyID', this.counter_parties[index].counterTyID)
            data.append('basketId', this.counter_parties[index].basketId)
            // if (this.counter_parties[index].basketId && this.counter_parties[index].basketId.length == 16 && this.counter_parties[index].counterTyID && this.basketType) {
            if (this.counter_parties[index].basketId && this.counter_parties[index].basketId.length == 16) {
                axios.post('/trade/CG/validate-counter-party-entry', data).then(response => {
                    if (response.data.success) {
                        if (response.data.invalid) {
                            this.basketId_errors.splice(index, 1, "This Basket ID is already registered.")
                        } else {
                            this.basketId_errors.splice(index, 1, null)

                        }

                    }
                })
            }
        },
        validateInBasketList() {
            let ignoreindex = this.count
            let baskets = this.getBasketTypes.splice(ignoreindex,1)
        },
        validateBillateral() {
            var data = new FormData()
            data.append('CUSIPCode', this.cucip_no)
            // data.append('collateralType', this.collateralType)
            data.append('currency', this.selected_currency)
            data.append('rating', this.rating)
            data.append('issuer_id', this.issuer)
            if (this.cucip_no && this.cucip_no.length == 9 && this.selected_currency && this.rating && this.issuer) {
                axios.post('/trade/CG/validate-bilateral-collateral', data).then(response => {
                    if (response.data.success) {
                        if (response.data.invalid) {
                            this.cucipError = "This CUCIP Number is already registered."
                        } else {
                            this.cucipError = this.cucipError

                        }

                    }
                })
            }
        },


        capitalize(thestring) {
            return capitalize(thestring)

        },

        setDefaults(defvalue) {
            if (defvalue != undefined) {
                this.reqid = defvalue.reqid
                this.selected_currency = defvalue.currency
                this.basketType = defvalue.basketType
                this.rating = defvalue.rating
                this.selected_basket = this.baskets.find(element => element.encoded_id == defvalue.basketType)
                // this.basketId = defvalue.basketId
                this.istriparty = (defvalue.type == 'tri') ? true : (defvalue.type == 'bi' ? false : null);
                if (defvalue.type == 'tri') {
                    this.counter_parties = defvalue.counterParty
                }

            }

        },
        ableToSubmit() {
            this.haserror = false
            if (this.istriparty == null) {
                this.haserror = true
                this.istriparty_selected_error = true
            } else {
                // product data

                if (this.rating == null) {
                    this.basketRatingError = "This field is required"
                    this.haserror = true

                }
                if (this.selected_currency == null) {
                    this.selectedCurrencyError = "This field is required"
                    this.haserror = true
                }
                if (this.istriparty) {
                    if (this.basketType == null) {
                        this.basketTypeError = "This field is required"
                        this.haserror = true
                    }
                    this.counter_parties.forEach((element, index) => {
                        if (element.counterTyID == null) {
                            this.counter_party_errors.splice(index, 1, "This field is required")
                            this.haserror = true
                        } else {

                            this.counter_party_errors.splice(index, 1, null)
                        }
                        if (element.basketId == null || (this.basketId_errors[index] && this.basketId_errors[index] != null)) {
                            this.haserror = true
                            this.basketId_errors.splice(index, 1, this.basketId_errors[index] ? this.basketId_errors[index] : "This field is required")
                            // this.basketId_errors[index] = "This field is required"
                        } else {
                            this.basketId_errors.splice(index, 1, null)
                        }
                    })
                } else {
                    if (this.collateralType == null) {
                        this.collateralTypeError = "This field is required"
                        this.haserror = true
                    }
                    if (this.issuer == null) {
                        this.issuerError = "This field is required"
                        this.haserror = true
                    } if (this.cucip_no == null || this.cucipError != null) {
                        this.cucipError = this.cucipError ? this.cucipError : "This field is required"
                        this.haserror = true

                    } if (this.maturityDate == null) {
                        this.maturityError = "This field is required"
                        this.haserror = true
                    }
                }
            }


            this.$emit('hasError', [this.count, this.haserror])
            const data = {
                'reqid': this.reqid,
                'currency': this.selected_currency,
                'basketType': this.basketType,
                'rating': this.rating,
                'basketId': this.basketId,
                'type': this.istriparty ? 'tri' : 'bi',
                'counterParty': this.counter_parties,
                'maturityDate': this.maturityDate,
                'collateral': this.collateralType,
                'issuer': this.issuer,
                'cucipNumber': this.cucip_no,
            }
            if (!this.haserror)
                this.$emit('newBasket', [this.count, data])
        },
        deleteRequest() {
            this.$emit('deleteRequest', this.count)
        },

    },
    watch: {
        istriparty(newVal, oldVal) {
            // if (newVal) {
            if (this.counter_parties.length == 0) {
                let newCounterparty = this.counterParty
                newCounterparty.counter_id = this.generateRandomValue()
                this.counter_parties.push(newCounterparty)
            }
            this.basketType = null
            this.collateralType = null
            this.basketname = null
            this.descripition = null
            // }
            if (newVal != null)
                this.istriparty_selected_error = false
        },
        generalErrors() {
            // console.log(this.generalErrors)
        },
        maturityDate() {
            this.maturityError = null
        },
        getAllBaskets() {
            console.log("baskets", this.getAllBaskets)
        }


    }

}


</script>
<style scoped>
.custom-select {
    padding: 0px 12px;
}

.aditional-option-click {
    color: #5063F4;
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 500;
    line-height: 26px;
    text-transform: capitalize;

}

.split-text {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

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

.basket-description {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}
</style>

<style>
.custom-select {
    padding: 0px 12px;
}

.text-div-test {
    color: #5063F4 !important;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Chart Titles */
    font-family: Montserrat;
    font-size: 22px;
    font-style: normal;
    font-weight: 500;
    line-height: 26px;
    /* 118.182% */
}

.error-repo-inputs {
    border: 0.5px solid red !important;
}

.disabled-div {
    background-color: #F4F5F6 !important;
}

.disabled-input {
    background-color: transparent !important;
}

.demo-bg {
    background: var(--Yield-Exchange-Pallette-Yield-Exchange-Off-White, #F4F5F6) !important;
    box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.15);
}

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

.repo-multiselect .select2-selection__rendered {
    max-height: 45px;
}

.repo-multiselect-scroll .select2-selection__rendered {
    max-height: 45px !important;
    overflow-y: scroll !important;
}

.repo-notify {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 500;
    line-height: 26px;
    /* 130% */
}
</style>