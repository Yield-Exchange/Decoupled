<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="lg">
        <div class="w-100 p-4 ">
            <div class="d-flex gap-2 align-items-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <path
                        d="M6.25488 15.2504C7.3407 10.6214 10.9551 7.00696 15.5841 5.92113C18.9272 5.13696 22.4063 5.13697 25.7494 5.92114C30.3784 7.00696 33.9928 10.6214 35.0786 15.2504C35.8628 18.5934 35.8628 22.0726 35.0786 25.4156C33.9928 30.0447 30.3784 33.6591 25.7494 34.7449C22.4063 35.5291 18.9272 35.5291 15.5841 34.7449C10.9551 33.6591 7.3407 30.0447 6.25488 25.4156C5.4707 22.0726 5.47071 18.5934 6.25488 15.2504Z"
                        fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                    <path
                        d="M15.8333 15.833H14.9999C14.5579 15.833 14.134 16.0086 13.8214 16.3212C13.5088 16.6337 13.3333 17.0576 13.3333 17.4997V24.9997C13.3333 25.4417 13.5088 25.8656 13.8214 26.1782C14.134 26.4907 14.5579 26.6663 14.9999 26.6663H22.4999C22.9419 26.6663 23.3659 26.4907 23.6784 26.1782C23.991 25.8656 24.1666 25.4417 24.1666 24.9997V24.1663"
                        stroke="#5063F4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M23.3333 14.1668L25.8333 16.6668M26.9875 15.4876C27.3157 15.1594 27.5001 14.7142 27.5001 14.2501C27.5001 13.7859 27.3157 13.3408 26.9875 13.0126C26.6593 12.6844 26.2142 12.5 25.75 12.5C25.2858 12.5 24.8407 12.6844 24.5125 13.0126L17.5 20.0001V22.5001H20L26.9875 15.4876Z"
                        stroke="#5063F4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="page-header-new-edit p-0 m-0">Edit your collateral basket information</p>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 mb-20 ">
                    <FormLabelRequired labelText="Counterparty" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />
                    <b-select v-model="counter_parties" @change="ChangeCounterParty($event)"
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
                    <CustomInput :maxlength="16" inputType="text" c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                        p-style="width:100%" id="rate" name="Enter basket Id" :has-validation="true"
                        @inputChanged="basketIDChange($event)" input-type="text" :defaultValue="basketId"
                        :hasSpecificError="basketId_errors" />
                    <div v-if="basketId_errors" class="error-message">
                        {{ basketId_errors }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3 align-items-center">
                <CustomSubmit title="Submit" @action="addNewBaskets" />
            </div>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Collateral basket edited successfully" btnOneText="" btnTwoText="" :showm="success">
            <!-- <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div> -->
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Counterparties has not been edited!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </Modal>

</template>

<script>
// import ActionMessage from ''
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'
import Modal from '../../../../shared/Modal.vue'
import FormLabelRequired from "../../../../shared/formLabels/FormLabelRequired.vue";
import CustomInput from '../../../../shared/CustomInput.vue';
import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue';
import { mapGetters } from 'vuex';



export default {

    props: ['show', 'basket', 'triparty'],
    beforeMount() {

        console.log(this.triparty, "Triparty")
        this.basketId = this.triparty.basket_id
        this.fixedbasketId = this.triparty.basket_id
        this.getCounterParties()
        this.getUrlPArams()

    },
    mounted() {
        this.basketid = this.basket
        this.trade_basket = this.getTriBaskets.trade_basket_type

    },
    components: {
        Modal,
        ActionMessage,
        FormLabelRequired,
        CustomInput,
        CustomSubmit

    },
    data() {
        return {
            basketid: null,
            count: 0,
            success: false,
            haserror: false,
            submitting: false,
            fail: false,
            basketId: null,
            fixedbasketId: null,
            basketId_errors: null,
            counter_party_errors: null,
            counter_party_count: 1,
            counter_party: null,
            counter_parties: null,
            possible_counter_parties: null,
            main_basket_id: null,
            trade_basket: null
        }
    },
    computed: {
        ...mapGetters('basket', ['getTriBaskets'])
    },
    methods: {
        getUrlPArams() {
            const url = window.location.pathname; // Get the current URL path
            const parts = url.split('/'); // Split the URL by '/'
            const numberPart = parts[parts.length - 1];
            this.main_basket_id = numberPart

        },
        closeModal() {
            this.$emit('closeModal', false)
        },
        ChangeCounterParty(event, index) {
            this.counter_parties = event
            this.counter_party_errors = null,
                this.validateCounterParty()
        },
        addNewBaskets() {
            if (!this.canSubmit()) {
                this.doSubmit()
            } else {
                console.log("Hit elese")
            }

        },
        validateCounterParty() {
            var data = new FormData()
            data.append('basketType', this.trade_basket.encoded_id)
            data.append('counterTyID', this.counter_parties)
            data.append('basketId', this.basketId)
            if (this.basketId && this.basketId.length == 16 && this.counter_parties && this.trade_basket && (this.basketId != this.fixedbasketId)) {
                axios.post('/trade/CG/validate-counter-party-entry', data).then(response => {
                    if (response.data.success) {
                        if (response.data.invalid) {
                            this.basketId_errors = "This Basket ID is already registered."
                        } else {
                            this.basketId_errors = null

                        }

                    }
                })
            }
        },
        doSubmit() {
            this.submitting = true
            var data = new FormData()
            let dataobj = [{
                'counterTyID': this.counter_parties,
                'basketId': this.basketId,
                'thirdPartyId': this.basketid,
            }]

            data.append('counterParties', JSON.stringify(dataobj))
            data.append('basket', this.main_basket_id)
            data.append('action', 'update')


            axios.post('/trade/CG/add-third-parties-to-basket', data).then(response => {
                this.submitting = false
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        window.location.reload()
                        this.success = false
                    }, 3000)
                } else {
                    this.fail = true
                    // this.confirmsubmit = false
                }
            }).catch(err => {
                // this.submitting = false
                // this.confirmsubmit = false
                this.fail = true
            })

        },
        getCounterParties() {
            axios.get('/common/trade/get-counterparties').then(response => {
                let organizations = response.data.data
                let possible_counter_parties = []
                if (organizations.length > 0) {

                    organizations.forEach(element => {
                        if (element.id == this.triparty.organization_id) {
                            this.counter_parties = element.encoded_id
                        }
                        possible_counter_parties.push(
                            { 'id': element.encoded_id, 'name': element.name }
                        )
                    });
                }
                this.possible_counter_parties = possible_counter_parties
            })
        },


        canSubmit() {
            this.haserror = false
            if (this.counter_parties == null) {
                this.counter_party_errors = "This field is required"
                this.haserror = true
            }
            if (this.basketId == null || this.basketId_errors != null) {
                this.haserror = true
                this.basketId_errors = "This field is required"
            }
            return this.haserror
        },
        basketIDChange(event, index) {
            let value = event
            let valcount = value.length
            this.basketId = value.toUpperCase()
            // this.basketId = 
            if (valcount < 16 || valcount > 16)
                this.basketId_errors = "Basket ID must be 16 alphanumeric characters (" + valcount + "/16)"
            else {
                this.basketId_errors = null
                this.validateCounterParty()
            }


        },




    },

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