<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="lg">
        <div class="w-100 p-4">
            <div
                style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="text-div" style="color: #5063F4 !important;">{{ basketname }} </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class=" row">
                <div class="col-md-12 mb-20 gap-2">
                    <p class="p-0 m-0 basket-description mb-20">Basket Description</p>
                    <p class="p-0 m-0 split-text ">{{ descripition }}</p>
                </div>
                <div class="col-md-4 mb-20 ">
                    <FormLabelRequired labelText="Issuer" :required="true" :showHelperText="false" helperText="Product"
                        helperId="product" />
                    <b-select :disabled="true" v-model="issuer" @change="changeIssuer($event)"
                        :class="{ 'error-repo-inputs': issuerError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select Issuer</option>
                        <option v-for="item in collateral_issuer" :key="item.id" :value="item.id">{{ item.name }}
                        </option>
                    </b-select>
                    <div v-if="issuerError" class="error-message">
                        {{ issuerError }}
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

                    <b-select v-model="rating" @change="changeRating" :disabled="true"
                        :class="{ 'error-repo-inputs': basketRatingError }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select rating</option>
                        <option v-for="item in ratings" :key="item" :value="item">{{ item }}
                        </option>
                    </b-select>
                    <div v-if="basketRatingError" class="error-message">
                        {{ basketRatingError }}
                    </div>
                </div>
                <template>
                    <div class="col-md-4 mb-20">
                        <FormLabelRequired labelText="Collateral" :required="true" :showHelperText="false"
                            helperText="Product" helperId="product" />
                        <b-select v-model="collateralType" @change="changeCollateralType"
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

                    <div class="col-md-4 mb-20 ">
                        <FormLabelRequired labelText="CUSIP Number" :required="true" :showHelperText="false"
                            helperText="Interest Rate Offer" helperId="PDSHId" />
                        <CustomInput :maxlength="9" inputType="text"
                            c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                            id="rate" name="Enter basket Id" :has-validation="true" @inputChanged="cucipNumber($event)"
                            input-type="text" :defaultValue="cucip_no" :hasSpecificError="cucipError" />
                        <div v-if="cucipError" class="error-message">
                            {{ cucipError }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-20 ">

                        <FormLabelRequired labelText="Maturity Date" :required="true" :showHelperText="false"
                            helperText="Trade Date" helperId="PDSHId" />
                        <JQueryCustomDatePicker v-if="formattedtimezone" :cannotpicktime="true" style=""
                            :hasError="maturityError" :id="count" :start_date="addWeekdays(0)" :end_date="null"
                            :formattedtimezone="formattedtimezone" placeholder="Select Trade Date"
                            :selected_date="maturityDate" v-model="maturityDate" />
                        <div v-if="maturityError" class="error-message">
                            {{ maturityError }}
                        </div>
                    </div>
                </template>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-3 align-items-center">
                <CustomSubmit title="Submit" @action="doSubmit" />
            </div>
        </div>
        <ActionMessage style="width: 800px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="CUSIP Number updated successfully" btnOneText="" btnTwoText="" :showm="success">
            <!-- <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div> -->
        </ActionMessage>
        <ActionMessage style="width: 800px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Ooops! CUSIP not updated!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </Modal>

</template>

<script>
// import ActionMessage from ''
import ActionMessage from '../../../../../shared/messageboxes/ActionMessageBox.vue'
import Modal from '../../../../../shared/Modal.vue'
import FormLabelRequired from "../../../../../shared/formLabels/FormLabelRequired.vue";
import CustomInput from '../../../../../shared/CustomInput.vue';
import CustomSubmit from '../../../../../auth/signup/shared/CustomSubmit.vue';
import JQueryCustomDatePicker from '../../../../../shared/JQueryCustomDatePicker.vue';
import { mapGetters } from 'vuex';




export default {

    props: ['show', 'basket', 'billateral', 'actionId', 'primary', 'getCollateralIssuers', 'getBilateralCollaterals'],
    beforeMount() {
        this.getTimezone()

        // this.getCounterParties()


    },
    mounted() {
        if (this.billateral) {
            let bil = this.billateral
            this.cucip_no = bil.CUSIP_code
            this.fcucip = bil.CUSIP_code
            this.selected_currency = this.primary.currency
            this.maturityDate = bil.maturity_date.split(' ')[0]
            this.rating = this.primary.rating
        }
        if (this.getCollateralIssuers)
            this.getCollateteralIssure()
        if (this.getBilateralCollaterals)
            this.getCollaterals()
        // this.basketid = this.basket?.encoded_id
    },
    components: {
        Modal,
        ActionMessage,
        FormLabelRequired,
        CustomInput,
        CustomSubmit,
        JQueryCustomDatePicker

    },
    computed: {
        // ...mapGetters('basket', ['getCollateralIssuers', 'getBilateralCollaterals', 'getBiBaskets'])
    },
    data() {
        return {
            basketid: null,
            basketname: null,
            fcucip: null,
            descripition: null,
            count: 0,
            success: false,
            haserror: false,
            submitting: false,
            fail: false,
            basketId: null,
            basketId_errors: null,
            counter_party_errors: null,
            counter_party_count: 1,
            counter_party: null,
            counter_parties: null,
            possible_counter_parties: null,
            // 
            formattedtimezone: null,
            issuer: null,
            maturityDate: null,
            cucip_no: null,
            collateralType: null,
            selected_currency: null,
            rating: null,
            collaterals: [],
            collateral_issuer: [],
            // err
            basketTypeError: null,
            collateralTypeError: null,
            basketIDError: null,
            selectedCurrencyError: null,
            collateralIssureError: null,
            counterPartyError: null,
            basketRatingError: null,
            issuerError: null,
            cucipError: null,
            maturityError: null,

        }
    },
    computed: {
        ratings() {
            return this.$store.getters.systemRating
        },
        currencyOptions() {
            return this.$store.getters.systemCurrencies
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal', false)
        },
        ChangeCounterParty(event, index) {
            this.counter_parties = event
            this.counter_party_errors = null
        },
        changeCollateralType(event) {
            if (this.collateralType == null) {
                this.collateralTypeError = 'This field is required'
            } else {
                let collateralType = this.collaterals.find(element => event == element.encoded_id)
                this.descripition = collateralType.collateral_description
                this.basketname = collateralType.collateral_name
                this.collateralTypeError = null
            }
        },
        changeBasketType(event) {
            this.selected_basket = this.baskets.find(element => element.encoded_id == event)
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
        },
        changeIssuer(event) {
            this.issuer = event
            this.issuerError = null
        },
        validateBillateral() {
            var data = new FormData()
            data.append('CUSIPCode', this.cucip_no)
            data.append('currency', this.selected_currency)
            data.append('rating', this.rating)
            data.append('issuer_id', this.issuer)
            if (this.cucip_no && this.cucip_no.length == 9) {
                axios.post('/trade/CG/validate-bilateral-collateral', data).then(response => {
                    if (response.data.success) {
                        if (response.data.invalid) {
                            if (this.fcucip != this.cucip_no)
                                this.cucipError = "This CUCIP Number is already registered."
                        } else {
                            this.cucipError = this.cucipError

                        }

                    }
                })
            }
        },
        cucipNumber(event) {
            let value = event
            let valcount = value.length
            this.cucip_no = value.toUpperCase()
            if (valcount < 9 || valcount > 9)
                this.cucipError = "CUCIP number must be 9 alphanumeric characters (" + valcount + "/9)"
            else {
                this.cucipError = null
                this.validateBillateral()
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

        doSubmit() {
            this.canSubmit()
            if (!this.haserror) {
                this.submitting = true
                var data = new FormData()
                let datatosend = [
                    {
                        "cucipNumber": this.cucip_no,
                        "trade_organization_collateral_c_u_s_i_p": this.basket,
                        "collateralId": this.collateralType,
                        "maturityDate": this.maturityDate

                    }]

                // "issuerId": this.issuer,
                data.append('issuerId', this.issuer)
                data.append('cusips', JSON.stringify(datatosend))
                // data.append('action', 'update')


                axios.post('/trade/CG/update-cusip-to-issuer', data).then(response => {
                    this.submitting = false
                    this.confirmsubmit = false
                    if (response.data.success) {
                        this.success = true
                        setTimeout(() => {
                            this.success = false
                            window.location.reload()
                        }, 3000)
                    } else {

                    }
                }).catch(err => {

                })
            }

        },
        getTimezone() {
            axios.get('/get-formated-timezone').then(res => {
                this.formattedtimezone = JSON.stringify(res.data)
            })
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
            this.getCollateralIssuers.forEach(element => {
                if (element.item_id == this.primary.trade_collateral_issuer_id) {
                    this.issuer = element.id
                }
            });
            this.collateral_issuer = this.getCollateralIssuers

        },
        getCollaterals() {
            // axios.get('/common/trade/get-colletarals-list').then(response => {
            let collateral = this.getBilateralCollaterals

            if (collateral.length > 0) {
                this.collaterals = collateral
                // this.collateralType = bil.trade_collateral_id
                let collateralType = this.collaterals.find(element => this.billateral.trade_collateral_id == element.id)
                this.collateralType = collateralType.encoded_id
                this.descripition = collateralType.collateral_description
                this.basketname = collateralType.collateral_name
            }

            // })
        },
        getBasketTypes() {
            axios.get('/common/trade/get-basket-types').then(response => {
                let baskets = response.data
                this.baskets = baskets.length > 0 ? baskets : []

            })
        },

        canSubmit() {
            this.haserror = false
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
            // return !this.haserror
        },
        basketIDChange(event, index) {
            let value = event
            let valcount = value.length
            this.basketId = value.toUpperCase()
            // this.basketId = 
            if (valcount < 16 || valcount > 16)
                this.basketId_errors = "Basket ID must be 16 alphanumeric characters (" + valcount + "/16)"
            else
                this.basketId_errors = null

        },
    },

    watch: {
        getCollateralIssuers() {
            console.log(this.getCollateralIssuers)
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
.custom-select {
    padding: 0px 12px;
}

.basket-description {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.notification-badge {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4));
    text-align: right;

    /* Yield Exchange Text Styles/Tooltips */
    font-family: Montserrat;
    font-size: 11px;
    font-style: normal;
    font-weight: 400;
    line-height: 14px;
    /* 127.273% */
}

.page-header-new-edit {
    color: #252525;

    /* Box Titles */
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.sect-header-counter {
    color: #5063F4;
    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}

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