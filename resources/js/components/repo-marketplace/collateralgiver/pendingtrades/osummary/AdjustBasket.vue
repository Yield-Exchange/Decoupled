<template>
    <Modal :show="show" @isVisible="$emit('closeModal', $event)" modalsize="xl">
        <div class="row my-3 w-100 mx-auto bg-white px-3 py-2">
            <div
                style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="text-div-test"> {{ basketname }} </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="d-flex justify-content-between gap-3 split-text">
                    <p class="basket-intro-section basket-description-header" v-if="!is_dummy">
                        Please review the information below before proceeding.</p>
                    <p class="basket-intro-section basket-description-header" v-else>
                        The current trade setup uses a placeholder basket. To proceed,
                        please update the collateral basket accordingly.</p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between gap-3 split-text">
                    <p class="split-text">
                        Will this basket be used for triparty transactions?
                    </p>
                    <div class="d-flex justify-content-start gap-3 split-text">
                        Yes <input disabled v-model="istriparty" :value="true" type="radio" :name="`triparty`">
                        No<input disabled v-model="istriparty" :value="false" type="radio" :name="`triparty`">
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
                    <b-select disabled v-model="basketType" @change="changeBasketType"
                        :class="{ 'error-repo-inputs': basketTypeError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select basket</option>
                        <option v-for="item in baskets" :key="item.id" :value="item.encoded_id">{{ item?.basket_name }}
                        </option>
                    </b-select>
                    <div v-if="basketTypeError" class="error-message">
                        {{ basketTypeError }}
                    </div>
                </div>
                <div class="col-md-4 mb-20" v-else>
                    <FormLabelRequired labelText="Collateral" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />
                    <b-select :disabled="true" v-model="collateralType" @change="changeCollateralType"
                        :class="{ 'error-repo-inputs': collateralTypeError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select collateral</option>
                        <option v-for="item in collaterals" :key="item.id" :value="item.encoded_id">{{
        item?.collateral_name }}
                        </option>
                    </b-select>
                    <div v-if="collateralTypeError" class="error-message">
                        {{ collateralTypeError }}
                    </div>
                </div>

                <div class="col-md-4 mb-20">

                    <FormLabelRequired labelText="Currency" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />
                    <b-select :disabled="true" v-model="selected_currency" @change="changeSelectedCurrency"
                        :class="{ 'error-repo-inputs': selectedCurrencyError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select currency</option>
                        <option v-for="item in currencyOptions" :key="item" :value="item">{{ item }}
                        </option>
                    </b-select>
                    <div v-if="selectedCurrencyError" class="error-message">
                        {{ selectedCurrencyError }}
                    </div>
                </div>

                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Rating" :required="true" :showHelperText="false" helperText="Product"
                        helperId="product" />

                    <NewCustomSelect :disabled="!is_dummy" style="margin-top: 4px;" :haserror="basketRatingError"
                        :options="ratings" idkey="" valuekey="" placeholder="Select rating" :defaultValue="rating"
                        @change="changeRating" />

                    <!-- <b-select :disabled="!is_dummy" v-model="rating" @change="changeRating"
                        :class="{ 'error-repo-inputs': basketRatingError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select rating</option>
                        <option v-for="item in ratings" :key="item" :value="item">{{ item }}
                        </option>
                    </b-select> -->
                    <div v-if="basketRatingError" class="error-message">
                        {{ basketRatingError }}
                    </div>
                </div>
                <template v-if="istriparty">
                    <div class=" row">
                        <div class="col-md-1 d-flex justify-content-center align-items-center">
                            <p class="p-0 m-0 split-text">1.</p>
                        </div>
                        <div class="col-md-5 mb-20 ">
                            <FormLabelRequired labelText="Counterparty" :required="true" :showHelperText="false"
                                helperText="Product" helperId="product" />
                            <b-select disabled v-model="counterTyID" @change="ChangeCounterParty($event)"
                                :class="{ 'error-repo-inputs': counter_party_errors }"
                                style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                                <option :value="null" selected disabled>Select counter party</option>
                                <option v-for="item in possible_counter_parties" :key="item.id" :value="item.id">{{
        item.name
    }}
                                </option>
                            </b-select>
                            <div v-if="counter_party_errors" class="error-message">
                                {{ counter_party_errors }}
                            </div>
                        </div>
                        <div class="col-md-5 mb-20 ">
                            <FormLabelRequired labelText="Basket ID" :required="true" :showHelperText="false"
                                helperText="Interest Rate Offer" helperId="PDSHId" />
                            <CustomInput inputType="text" :disabled="!is_dummy" :maxlength="16"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                id="rate" name="Enter basket Id" :has-validation="true"
                                @inputChanged="basketIDChange($event)" input-type="text" :defaultValue="basketId"
                                :hasSpecificError="basketIDError" />
                            <div v-if="basketIDError" class="error-message">
                                {{ basketIDError }}
                            </div>
                        </div>
                        <!-- <div class="col-md-1 d-flex justify-content-center align-items-center"
                            v-if="counter_parties.length > 1">
                            <img style="width: 13.333px;height: 15px; cursor: pointer;"
                                @click="removeCounterPartyPrompt(index)" src="/assets/images/icons/deleteicon.svg"
                                alt="" srcset="">
                        </div> -->
                    </div>
                </template>
                <template v-else>
                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="Issuer" :required="true" :showHelperText="false"
                            helperText="Product" helperId="product" />
                        <b-select :disabled="!is_dummy" v-model="issuer" @change="changeIssuer($event)"
                            :class="{ 'error-repo-inputs': issuerError }"
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                            <option :value="null" selected disabled>Select Issuer</option>
                            <option v-for="item in collateral_issuer" :key="item.id" :value="item.id">{{
        item.name
    }}
                            </option>
                        </b-select>
                        <div v-if="issuerError" class="error-message">
                            {{ issuerError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="CUSIP Number" :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput :disabled="!is_dummy" inputType="text" :maxlength="9"
                            c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                            id="rate" name="Enter CUSIP No" :has-validation="true"
                            @inputChanged="cucipNumberChange($event)" input-type="text" :defaultValue="cucip_no"
                            :hasSpecificError="cucipError" />
                        <div v-if="cucipError" class="error-message">
                            {{ cucipError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">

                        <FormLabelRequired labelText="Maturity Date" :required="true" :showHelperText="false"
                            helperText="Trade Date" helperId="PDSHId" />
                        <JQueryCustomDatePicker :disabled="!is_dummy" :cannotpicktime="true" style=""
                            :hasError="maturityError" :id="1" :start_date="addWeekdays(0)" :end_date="null"
                            :formattedtimezone="formattedtimezone" placeholder="Select Trade Date"
                            :selected_date="maturityDate" v-model="maturityDate" />
                        <div v-if="maturityError" class="error-message">
                            {{ maturityError }}
                        </div>
                    </div>
                </template>
                <div class="col-md-12 my-2">
                    <div class="d-flex justify-content-end">

                        <CustomSubmit title="Submit" @action="ableToSubmit"></CustomSubmit>

                    </div>

                </div>
            </template>

        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="The trade has been activated" btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral taker has been notified..</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Oops!The trade has not been activated!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="start_submit = false" @btnTwoClicked="processRepo"
            @btnOneClicked="start_submit = false" btnOneText="No" btnTwoText="yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Start proccesing the repo?" :showm="start_submit">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to keep the changes as they are and start
                with the processing process now</div>
        </ActionMessage>

        <template v-if="same_product_type">
            <ActionMessage size="lg" style="width: 600px;" @closedSuccessModal="same_id = false"
                @btnTwoClicked="overlookErrors" @btnOneClicked="same_id = false" btnOneText="No" btnTwoText="yes"
                icon="/assets/dashboard/icons/question-new.svg"
                :title="'This ' + (!istriparty ? 'CUSIP Number' : 'basket id') + ' already exists'" :showm="same_id">
                <div class="ml-2 description-text-withdraw mb-20">
                    Would you like to assign this trade to the same {{ istriparty ? 'Bakset Id' : 'CUSIP Number?' }}
                </div>
                <div class="d-flex w-100">
                    <div style="padding: 10px 5px;background:  #EFF2FE;" class="w-100">
                        <p class="p-0 m-0 mb-20"
                            style="color:#252525;font-family: Montserrat !important;font-size: 15px;font-style: normal;font-weight: 700;line-height: normal;">
                            Basket Details</p>
                        <div class="d-flex w-100 gap-2" v-if="istriparty">
                            <ViewCard title="Basket Type" :desc="found_record?.trade_basket_type?.basket_name" />
                            <ViewCard title="Currency" :desc="found_record?.currency" />
                            <ViewCard title="Rating" :desc="found_record?.rating" />
                            <ViewCard title="Basket ID Number" :is_red="true" :desc="basketId" />
                        </div>
                        <div class="d-flex w-100 gap-3" v-else>
                            <ViewCard title="Colateral Type"
                                :desc="found_record?.trade_organization_c_u_s_s_i_p[0]?.collateral_details?.collateral_name" />
                            <ViewCard title="Currency" :desc="found_record?.currency" />
                            <ViewCard title="Rating" :desc="found_record?.rating" />
                            <ViewCard title="CUSIP Number" :is_red="true" :desc="cucip_no" />
                        </div>
                    </div>

                </div>
            </ActionMessage>
        </template>
        <template v-else>
            <ActionMessage size="lg" style="width: 600px;" @closedSuccessModal="same_id = false"
                @btnTwoClicked="same_id = false" @btnOneClicked="same_id = false" btnOneText="" btnTwoText="Ok"
                icon="/assets/dashboard/icons/question-new.svg"
                :title="'This ' + (!istriparty ? 'CUSIP Number' : 'basket id') + ' already exists'" :showm="same_id">
                <div class="ml-2 description-text-withdraw mb-20 " v-if="istriparty">
                    Please issue a new Basket ID. ID <b>{{ basketId }}</b> has been registered under a different Basket
                    Type.
                </div>
                <div class="ml-2 description-text-withdraw mb-20 " v-else>
                    Please issue a new CUSIP No. ID <b>{{ cucip_no }}</b> has been registered under a different
                    Collateral type.
                </div>
                <div class="d-flex w-100">
                    <div style="padding: 10px 5px;background:  #EFF2FE;" class="w-100">
                        <p class="p-0 m-0 mb-20"
                            style="color:#252525;font-family: Montserrat !important;font-size: 15px;font-style: normal;font-weight: 700;line-height: normal;">
                            Basket Details</p>
                        <div class="d-flex w-100 gap-2" v-if="istriparty">
                            <ViewCard title="Basket Type" :desc="found_record?.trade_basket_type?.basket_name" />
                            <ViewCard title="Currency" :desc="found_record?.currency" />
                            <ViewCard title="Rating" :desc="found_record?.rating" />
                            <ViewCard title="Basket ID Number" :is_red="true" :desc="basketId" />
                        </div>
                        <div class="d-flex w-100 gap-3" v-else>
                            <ViewCard title="Colateral Type"
                                :desc="found_record?.trade_organization_c_u_s_s_i_p[0]?.collateral_details?.collateral_name" />
                            <ViewCard title="Currency" :desc="found_record?.currency" />
                            <ViewCard title="Rating" :desc="found_record?.rating" />
                            <ViewCard title="CUSIP Number" :is_red="true" :desc="cucip_no" />
                        </div>
                    </div>

                </div>
            </ActionMessage>
        </template>

    </Modal>

</template>

<script>
import Modal from '../../../../shared/Modal.vue';
import CustomInput from '../../../../shared/CustomInput.vue';
import { addDaysOrMonths, formatTimestamp } from '../../../../../utils/commonUtils'
import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue';
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue';
import FormLabelRequired from '../../../../shared/formLabels/FormLabelRequired.vue';
import JQueryCustomDatePicker from '../../../../shared/JQueryCustomDatePicker.vue';
import { mapGetters } from 'vuex';
import ViewCard from '../../../../shared/ViewCard.vue';
import NewCustomSelect from '../../../../shared/NewCustomSelect.vue';


export default {
    components: { NewCustomSelect, Modal, ActionMessage, FormLabelRequired, CustomInput, CustomSubmit, JQueryCustomDatePicker, ViewCard },
    props: ['candelete', 'defcurrency', 'formattedtimezone', 'show', 'basket_type', 'basket', 'is_dummy', 'depositID'],
    beforeMount() {
        this.istriparty = this.basket_type
        this.getCounterParties()
        this.getCollateteralIssure()
        this.getCollaterals()
        this.getBasketTypes()
        if (this.basket) {
            this.setDefaults(this.basket)
        }

    },
    mounted() {
        if (this.defcurrency)
            this.selected_currency = this.defcurrency
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
            same_id: false,
            cp_to_remove: null,
            data_to_submit: null,
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
            counter_party_errors: null,
            counter_party_count: 1,
            counter_parties: [],
            counterTyID: null,
            counterParty: {
                'counter_id': null,
                'counterTyID': null,
                'basketId': null,
            },

            success: false,
            fail: false,
            start_submit: false,
            use_existing: false,
            found_record: null,
            collateral_name: null,
            basket_test_name: null,
            same_product_type: true,

        }
    },
    computed: {
        ratings() {
            return this.$store.getters.systemRating
        },
        ...mapGetters('repopostrequest', ['getPrimeRates', 'getsettlemntdate', 'getgloabalproducts']),
        currencyOptions() {
            return this.$store.getters.systemCurrencies
        }
    },
    methods: {

        // end counter party logic

        changeCollateralType(event) {
            if (this.collateralType == null) {
                this.collateralTypeError = 'This field is required'
            } else {
                this.selected_basket = this.collaterals.find(element => element.encoded_id == event)
                // this.basketname = this.selected_basket.collateral_name
                // this.descripition = this.selected_basket.collateral_description

                this.collateralTypeError = null
            }
        },
        changeBasketType(event) {
            this.selected_basket = this.baskets.find(element => element.encoded_id == event)
            // this.basketname = this.selected_basket.basket_name
            // this.descripition = this.selected_basket.basket_description
            this.basketType = event
            this.basketTypeError = null
        },
        changeSelectedCurrency(event) {
            this.selected_currency = event
            this.selectedCurrencyError = null
        },
        changeRating(event) {
            this.rating = event
            this.basketRatingError = null
            // this.validateBillateral()

        },

        ChangeCounterParty(event) {
            this.counter_party = event
            this.counterTyID = event
            this.counter_party_errors = null
        },
        changeIssuer(event) {
            this.issuer = event
            this.issuerError = null
            // this.validateBillateral()
        },
        basketIDChange(event) {
            let value = event
            let valcount = value.length
            this.basketId = value.toUpperCase()
            if (valcount < 16 || valcount > 16)
                this.basketIDError = "Basket ID must be 16 alphanumeric characters (" + valcount + "/16)"
            else {
                this.basketIDError = null
                this.validateCounterParty()
                // this.validateInArray(index)
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
        cucipNumberChange(event) {
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
                        if (this.istriparty) {
                            if (this.basket.organization_id == element.id) {
                                this.counterTyID = element.encoded_id
                            }
                        }
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
                        if (!this.istriparty) {
                            if (element.id == this.basket.trade_collateral_issuer_id)
                                this.issuer = element.encoded_id
                        }
                        collateral_issuer.push(
                            { 'id': element.encoded_id, 'name': element.name }
                        )
                    });
                }
                this.collateral_issuer = collateral_issuer
            })
        },

        getCollaterals() {
            axios.get('/common/trade/get-colletarals-list').then(response => {
                let collateral = response.data
                if (collateral.length > 0) {
                    this.collaterals = collateral
                    if (!this.istriparty) {
                        let col = collateral.filter(col => col.id == this.basket.trade_collateral_id)
                        this.collateralType = col[0].encoded_id
                        this.collateral_name = col[0].collateral_name
                        console.log(col[0])
                    }
                }
            })
        },
        getBasketTypes() {
            axios.get('/common/trade/get-basket-types').then(response => {
                let baskets = response.data
                this.baskets = baskets.length > 0 ? baskets : []
                if (this.istriparty) {
                    let basket = baskets.filter(basket => basket.id == this.basket?.basket_details?.trade_basket_type_id)
                    this.basketType = basket[0].encoded_id
                    this.basket_test_name = basket[0].basket_name

                    // console.log(col)
                }
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
        validateCounterParty() {
            this.found_record = null
            this.use_existing = false
            var data = new FormData()
            data.append('basketType', this.basketType)
            data.append('counterTyID', this.counterTyID)
            data.append('basketId', this.basketId)
            data.append('rating', this.rating)
            data.append('currency', this.selected_currency)
            if (this.basketId && this.basketId.length == 16) {
                axios.post('/trade/CG/validate-counter-party-entry', data).then(response => {
                    if (response.data.success) {
                        if (response.data.invalid) {
                            this.found_record = response.data.data
                            this.same_product_type = this.basket_test_name === this.found_record?.trade_basket_type?.basket_name
                            this.same_id = true
                            this.basketIDError = "This Basket ID is already registered."
                        } else {
                            this.basketIDError = null
                        }

                    }
                })
            }
        },
        validateBillateral() {
            this.found_record = null
            this.use_existing = false
            var data = new FormData()
            data.append('CUSIPCode', this.cucip_no)
            // data.append('collateralType', this.collateralType)
            data.append('currency', this.selected_currency)
            data.append('rating', this.rating)
            data.append('issuer_id', this.issuer)
            if (this.cucip_no != null && this.cucip_no.length == 9) {
                axios.post('/trade/CG/validate-bilateral-collateral', data).then(response => {
                    if (response.data.success) {
                        if (response.data.invalid) {
                            this.found_record = response.data.data
                            this.same_product_type = this.collateral_name === this.found_record?.trade_organization_c_u_s_s_i_p[0]?.collateral_details?.collateral_name


                            this.same_id = true

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
        overlookErrors() {
            this.same_id = false
            this.cucipError = false
            this.basketIDError = false
            this.use_existing = true
            if (!this.istriparty) {
                this.maturityDate = this.found_record?.trade_organization_c_u_s_s_i_p[0]?.maturity_date
            }
        },

        setDefaults(defvalue) {
            if (defvalue != undefined) {

                if (this.istriparty) {
                    this.selected_currency = defvalue?.basket_details?.currency ? defvalue?.basket_details?.currency : null
                    this.rating = this.ratings.includes(defvalue.basket_details.rating) ? defvalue.basket_details.rating : null
                    this.basketname = defvalue.basket_details.trade_basket_type.basket_name
                    this.descripition = defvalue.basket_details.trade_basket_type.basket_description
                    if (!this.is_dummy) {
                        this.basketId = defvalue.basket_id
                    }

                } else {
                    this.selected_currency = defvalue.currency
                    this.basketname = defvalue.collateral_details.collateral_name
                    this.descripition = defvalue.collateral_details.collateral_description
                    if (!this.is_dummy) {
                        this.cucip_no = defvalue.CUSIP_code
                        this.maturityDate = defvalue.maturity_date.split(' ')[0]
                        this.rating = this.ratings.includes(defvalue.rating) ? defvalue.rating : null
                        this.selected_basket = this.baskets.find(element => element.encoded_id == defvalue.basketType)
                    }

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

                    if (this.counterTyID == null) {
                        this.counter_party_errors = "This field is required"
                        this.haserror = true
                    } else {

                        this.counter_party_errors = null
                    }
                    if (this.basketId == null || (this.basketIDError && this.basketIDError != null)) {
                        this.haserror = true
                        this.basketIDError = (this.basketIDError && this.basketIDError != null) ? this.basketIDError : "This field is required"
                    } else {
                        this.basketIDError = null
                    }

                } else {
                    if (this.collateralType == null) {
                        this.collateralTypeError = "This field is required"
                        this.haserror = true
                    }
                    if (this.issuer == null) {
                        this.issuerError = "This field is required"
                        this.haserror = true
                    }
                    if (this.cucip_no == null || (this.cucipError && this.cucipError != null)) {
                        this.haserror = true
                        this.cucipError = (this.cucipError && this.cucipError != null) ? this.cucipError : "This field is required"
                    } else {
                        this.cucipError = null
                    }
                    if (this.maturityDate == null) {
                        this.maturityError = "This field is required"
                        this.haserror = true
                    }
                }
            }

            if (!this.haserror) {
                let data = {
                    'type': this.istriparty ? 'tri' : 'bi',
                    'currency': this.selected_currency,
                    'rating': this.rating,
                }
                if (this.istriparty) {
                    data['basketType'] = this.basketType;
                    data['basketId'] = this.basketId;
                    data['counterParty'] = [{
                        'basketId': this.basketId,
                        'counterTyID': this.counterTyID,
                    }]

                } else {
                    data['collateral'] = this.collateralType;
                    data['maturityDate'] = this.maturityDate;
                    data['issuer'] = this.issuer;
                    data['cucipNumber'] = this.cucip_no;
                }
                this.start_submit = true
                this.data_to_submit = data
            }
        },
        goBack() {
            window.location.href = "/repos/cg-repos-pending-trades"
        },
        async processRepo() {
            this.start_submit = false

            let formdata = new FormData()

            formdata.append('collateral_exists', this.use_existing ? '1' : '0')
            if (this.use_existing) {
                if (this.istriparty) {

                    formdata.append('collateral_id', this.found_record?.encoded_id)
                    // formdata.append('collateral_id', this.found_record?.id)
                } else {
                    formdata.append('collateral_id', this.found_record?.trade_organization_c_u_s_s_i_p[0].encoded_id)
                }
            } else {
                formdata.append('collateral_details', JSON.stringify(this.data_to_submit))
            }
            formdata.append('depositId', this.depositID)

            axios.post('/trade/CG/activate-trade', formdata).then(response => {
                console.log(response.data.success)
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        this.goBack()
                        this.$emit('closeModal', false)
                    }, 5000)

                } else {
                    this.fail = true
                }
            }).catch(err => {
                this.fail = true
            })
        },



    },
    watch: {
        defcurrency() {
            this.selected_currency = this.defcurrency
        },
        maturityDate() {
            this.maturityError = null
        },


    }

}

</script>
<style scoped>
.basket-description-header {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    text-align: center;
    font-feature-settings: 'liga' off, 'clig' off;

    /* Box Titles */
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

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

.form-control:disabled {
    background-color: #f5f5f5 !important;
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