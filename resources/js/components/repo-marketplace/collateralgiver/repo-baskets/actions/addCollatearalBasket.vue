<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="lg">
        <div class="w-100 p-4">
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
                <p class="page-header-new-edit p-0 m-0">Add more counterparties to {{ basket_name }}</p>
            </div>

            <div class=" row mt-1" v-for="(cp, index) in counter_parties" :key="cp.counter_id">
                <div class="col-md-1 d-flex justify-content-center align-items-center">
                    <p class="p-0 m-0 ">{{ index + 1 }}.</p>
                </div>
                <div class="col-md-5 mb-20 ">
                    <FormLabelRequired labelText="Counterparty" :required="true" :showHelperText="false"
                        helperText="Product" helperId="product" />

                    <NewCustomSelect style="margin-top: 4px;" :haserror="counter_party_errors[index]"
                        :options="possible_counter_parties" idkey="id" valuekey="name"
                        inputStyle="font-size:  16px !important;" placeholder="Select counter party"
                        :defaultValue="counter_parties[index].counterTyID"
                        @change="ChangeCounterParty($event, index)" />

                    <!-- <b-select v-model="counter_parties[index].counterTyID" @change="ChangeCounterParty($event, index)"
                        :class="{ 'error-repo-inputs': counter_party_errors[index] }"
                        style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                        <option :value="null" selected disabled>Select counter party</option>
                        <option v-for="item in possible_counter_parties" :key="item.id" :value="item.id">{{
        item.name
    }}
                        </option>
                    </b-select> -->
                    <div v-if="counter_party_errors[index]" class="error-message">
                        {{ counter_party_errors[index] }}
                    </div>
                </div>
                <div class="col-md-5 mb-20 ">
                    <FormLabelRequired labelText="Basket ID" :required="true" :showHelperText="false"
                        helperText="Interest Rate Offer" helperId="PDSHId" />
                    <CustomInput :maxlength="16" inputType="text" c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                        p-style="width:100%" id="rate" name="Enter basket Id" :has-validation="true"
                        @inputChanged="basketIDChange($event, index)" input-type="text"
                        :defaultValue="counter_parties[index].basketId" :hasSpecificError="basketId_errors[index]" />
                    <div v-if="basketId_errors[index]" class="error-message">
                        {{ basketId_errors[index] }}
                    </div>
                </div>
                <div class="col-md-1 d-flex justify-content-center align-items-center"
                    v-if="counter_parties.length > 1">
                    <img style="width: 13.333px;height: 15px; cursor: pointer;" @click="removeCounterPartyPrompt(index)"
                        src="/assets/images/icons/deleteicon.svg" alt="" srcset="">
                </div>
            </div>
            <div class="col-md-12">
                <p class="error-message" v-if="count >= 5">
                    Counterparty limit reached
                </p>
            </div>
            <div class="d-flex justify-content-between gap-2 mt-3 align-items-center">
                <div class="d-flex justify-content-start gap-2 ">
                    <div @click="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="30" viewBox="0 0 31 30" fill="none">
                            <path
                                d="M21.75 12.5L19.25 5M9.25 12.5L11.75 5M15.5 25H9.555C8.65925 25 7.7931 24.6793 7.11329 24.096C6.43347 23.5127 5.9849 22.7053 5.84875 21.82L4.28 12.88C4.22521 12.5237 4.24811 12.1598 4.34713 11.8132C4.44615 11.4666 4.61895 11.1456 4.85368 10.872C5.08841 10.5984 5.37952 10.3789 5.70705 10.2284C6.03458 10.0779 6.39079 9.99996 6.75125 10H24.25C24.6105 9.99996 24.9667 10.0779 25.2942 10.2284C25.6217 10.3789 25.9128 10.5984 26.1476 10.872C26.3823 11.1456 26.5551 11.4666 26.6541 11.8132C26.7531 12.1598 26.776 12.5237 26.7212 12.88L26.2737 15.43"
                                :stroke="count >= 5 ? '#9CA1AA' : '#5063F4'" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M24.25 27.5V20M24.25 20L28 23.75M24.25 20L20.5 23.75M13 17.5C13 18.163 13.2634 18.7989 13.7322 19.2678C14.2011 19.7366 14.837 20 15.5 20C16.163 20 16.7989 19.7366 17.2678 19.2678C17.7366 18.7989 18 18.163 18 17.5C18 16.837 17.7366 16.2011 17.2678 15.7322C16.7989 15.2634 16.163 15 15.5 15C14.837 15 14.2011 15.2634 13.7322 15.7322C13.2634 16.2011 13 16.837 13 17.5Z"
                                :stroke="count >= 5 ? '#9CA1AA' : '#5063F4'" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <p class="aditional-option-click p-0 m-0" :class="{ 'disabled-style': count >= 5 }"
                        @click="addCounterParty">Add more counterparties</p>
                </div>
                <div>
                    <p class=" notification-badge p-0 m-0">*You can only add a maximum of 5 counterparties at a time
                    </p>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3 align-items-center">
                <CustomSubmit title="Submit" @action="addNewBaskets" />
            </div>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="counterparties added successfully" btnOneText="" btnTwoText="" :showm="success">
            <!-- <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div> -->
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Counterparties has not been added!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="removecp = false" @btnTwoClicked="removeCounterParty"
            @btnOneClicked="removecp = false" btnOneText="No" btnTwoText="Yes" icon="/assets/signup/danger.svg"
            title="Delete this counterParty" :showm="removecp">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to remove this counter party? </div>
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

import NewCustomSelect from '../../../../shared/NewCustomSelect.vue';



export default {

    props: ['show', 'action', 'product', 'basket'],
    beforeMount() {
        if (this.counter_parties.length == 0) {
            let newCounterparty = this.counterParty
            newCounterparty.counter_id = this.generateRandomValue()
            this.counter_parties.push(newCounterparty)
        }
        this.getCounterParties()

    },
    mounted() {
        this.basketid = this.basket?.encoded_id
        this.basket_name = this.basket?.trade_basket_type?.basket_name
        this.basketType = this.basket?.trade_basket_type?.encoded_id
    },
    components: {
        Modal,
        ActionMessage,
        FormLabelRequired,
        CustomInput,
        CustomSubmit,
        NewCustomSelect

    },
    data() {
        return {
            basketid: null,
            basket_name: null,
            basketType: null,
            count: 0,
            success: false,
            haserror: false,
            submitting: false,
            fail: false,
            basketId_errors: [],
            counter_party_errors: [],
            counter_party_count: 1,
            counter_party: null,
            counter_parties: [],
            cp_to_remove: null,
            removecp: false,
            counterParty: {
                'counter_id': null,
                'counterTyID': null,
                'basketId': null,
            },
            possible_counter_parties: [],
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal', false)
        },
        ChangeCounterParty(event, index) {
            this.counter_party = event
            this.counter_parties[index].counterTyID = event
            this.counter_party_errors.splice(index, 1, null)
            this.validateCounterParty(index)
            this.validateInArray(index)
        },
        addNewBaskets() {
            if (!this.canSubmit()) {
                this.doSubmit()
            } else {
                console.log("Hit elese")
            }

        },
        doSubmit() {
            this.submitting = true
            var data = new FormData()

            data.append('counterParties', JSON.stringify(this.counter_parties))
            data.append('basket', this.basketid)


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
                this.submitting = false
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
                        possible_counter_parties.push(
                            { 'id': element.encoded_id, 'name': element.name }
                        )
                    });
                }
                this.possible_counter_parties = possible_counter_parties
            })
        },
        addCounterParty() {

            var newCounterparty = {
                'counter_id': this.generateRandomValue(),
                'counterTyID': null,
                'basketId': null,
            }
            if (!this.canSubmit()) {
                if (this.counter_parties.length < 5) {
                    this.counter_parties.push(newCounterparty)
                    this.count = this.counter_parties.length
                }
            }
        },
        removeCounterPartyPrompt(request_id) {
            this.cp_to_remove = request_id
            this.removecp = true
        },
        removeCounterParty() {
            this.counter_parties.splice(this.cp_to_remove, 1)
            this.basketId_errors.splice(this.cp_to_remove, 1)
            this.counter_party_errors.splice(this.cp_to_remove, 1)
            this.count = this.counter_parties.length
            this.removecp = false

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
            return haserror
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
        basketIDChange(event, index) {
            let value = event
            let valcount = value.length
            this.counter_parties[index].basketId = value.toUpperCase()
            // this.basketId = 
            if (valcount < 16 || valcount > 16)
                this.basketId_errors[index] = "Basket ID must be 16 alphanumeric characters (" + valcount + "/16)"
            else {
                this.basketId_errors.splice(index, 1)
                this.validateCounterParty(index)
                this.validateInArray(index)
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
.disabled-style {
    color: #9CA1AA !important;
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