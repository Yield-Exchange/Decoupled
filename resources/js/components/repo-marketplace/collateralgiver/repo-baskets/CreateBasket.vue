<template>
    <div style="height: 100%">
        <div
            style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
            <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                <div style="display: flex; flex-direction: column;">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M6.25463 15.2509C7.34046 10.6218 10.9549 7.00745 15.5839 5.92162C18.9269 5.13745 22.4061 5.13745 25.7491 5.92162C30.3782 7.00745 33.9926 10.6218 35.0784 15.2509C35.8625 18.5939 35.8625 22.0731 35.0784 25.4161C33.9926 30.0452 30.3782 33.6595 25.7491 34.7454C22.4061 35.5295 18.9269 35.5295 15.5839 34.7454C10.9549 33.6595 7.34046 30.0452 6.25463 25.4161C5.47046 22.0731 5.47046 18.5939 6.25463 15.2509Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M15.8335 15.8335H15.0002C14.5581 15.8335 14.1342 16.0091 13.8217 16.3217C13.5091 16.6342 13.3335 17.0581 13.3335 17.5002V25.0002C13.3335 25.4422 13.5091 25.8661 13.8217 26.1787C14.1342 26.4912 14.5581 26.6668 15.0002 26.6668H22.5002C22.9422 26.6668 23.3661 26.4912 23.6787 26.1787C23.9912 25.8661 24.1668 25.4422 24.1668 25.0002V24.1668"
                                    stroke="#5063F4" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M23.3333 14.1668L25.8333 16.6668M26.9875 15.4876C27.3157 15.1594 27.5001 14.7142 27.5001 14.2501C27.5001 13.7859 27.3157 13.3408 26.9875 13.0126C26.6593 12.6844 26.2142 12.5 25.75 12.5C25.2858 12.5 24.8407 12.6844 24.5125 13.0126L17.5 20.0001V22.5001H20L26.9875 15.4876Z"
                                    stroke="#5063F4" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            Create new Basket
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center w-100 flex-column mt-2">
            <div class="shadow-sm w-50 p-4 bg-white d-flex justify-content-center flex-column" style="gap:10px">
                <div class="d-flex justify-content-center">
                    <p class="basket-head text-capitalize">
                        Enter the required information for the New Basket
                    </p>
                </div>
                <BasketForm :tripartycheck="tripartycheck" :formattedtimezone="formattedtimezone"
                    @deleteRequest="deleteRequest" @hasError="hasError" v-for="(value, i) in getAllBaskets"
                    @newBasket="newBasket" :count="i" :key="value.reqid" ref="basketsitems" :basket="value"
                    :candelete="getAllBaskets.length > 1" />
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center w-100 flex-column mt-2">
            <div class="w-50 py-4 d-flex"
                :class="tripartycheck != null ? 'justify-content-between' : 'justify-content-end'" style="gap:10px">
                <CustomSubmit v-if="tripartycheck != null" :previous="true" @action="goBack" title="Previous" />

                <!-- <div class="d-flex justify-content-end  gap-2"> -->
                <div class="d-flex justify-content-end  gap-2" v-if="userCan(userLoggedIn, 'bank/repos/create-basket')">
                    <CustomSubmit :outline="true" @action="submitRequest(true)" title="Add New Basket" />
                    <CustomSubmit @action="submitRequest" title="Next" />
                </div>
            </div>
        </div>

        <ActionMessage style="width: 600px;" @closedSuccessModal="closeSuccess('true')" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" title="Baskets has  been added!"
            btnOneText="" btnTwoText="" :showm="success">
            <template v-if="tripartycheck == null">
                <div class="px-3 description-text-withdraw "> Where should we take you next?</div>
                <div class="d-flex w-100 justify-content-end mt-2 gap-3">
                    <CustomSubmit @action="closeSuccess('bi')" :outline="true" title="Bilateral Baskets" />
                    <CustomSubmit @action="closeSuccess('tri')" title="Triparty Baskets" />
                </div>
            </template>

        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Your basket has not been added!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="clear = false" @btnTwoClicked="clearReq"
            @btnOneClicked="clear = false" btnOneText="No" btnTwoText="yes" icon="/assets/signup/danger.svg"
            title="Clear request" :showm="clear">
            <div class="ml-5 description-text-withdraw ">Your changes will be cleared from the request</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="confirmsubmit = false" @btnTwoClicked="doSubmit"
            @btnOneClicked="confirmsubmit = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Proceed to add your baskets now."
            :showm="confirmsubmit">
            <div class="ml-5 description-text-withdraw ">"Please ensure that all fields are correctly filled out before
                proceeding to add your baskets."</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="removebasket = false" @btnTwoClicked="removeBasket"
            @btnOneClicked="removebasket = false" btnOneText="No" btnTwoText="Yes" icon="/assets/signup/danger.svg"
            title="Delete this basket" :showm="removebasket">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to remove this basket? </div>
        </ActionMessage>


    </div>

</template>

<script>

import { addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../../utils/commonUtils";
import { mapGetters } from 'vuex';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import BasketForm from './BasketForm.vue'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
// import 
import * as types from '../../../../store/modules/baskets/mutation-types'
import { userCan } from "../../../../utils/GlobalUtils";

export default {
    props: ['userLoggedIn'],

    mounted() {
        this.getTimezone()
        if (this.newreqdata) {
            let newRequestbm = this.newreqdata
            newRequestbm.reqid = this.generateRandomValue()
            this.$store.commit('basket/' + types.ADD_BASKET, [0, newRequestbm]);
        }
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        const from = params.get('from');
        if (from) {
            if (from == 'triparty') {
                this.tripartycheck = true
            } else if (from == 'bilateral') {
                this.tripartycheck = false
            } else {
                this.tripartycheck = null
            }
        }
        // console.log(from, "From")
    },
    components: {
        CustomSubmit,
        BasketForm,
        ActionMessage

    },
    data() {
        return {
            basketcount: 1,
            tripartycheck: null,
            newreqdata: {
                'reqid': null,
                'currency': null,
                'basketType': null,
                'rating': null,
                'type': null,
                'counterParty': null,
            },
            step: 'add',
            generalError: {},
            isdeleting: false,
            success: false,
            removebasket: false,
            baskettoremove: null,
            fail: false,
            confirmsubmit: false,
            submitting: false,
            isedit: false,
            formattedtimezone: null,
            clear: false,
            successtitle: 'Your basket has been added.',

        }
    },
    computed: {
        ...mapGetters('basket', ['getAllBaskets']),
    },

    methods: {

        closeSuccess(value) {
            this.success = false
            if (value == 'tri')
                window.location.href = '/repos/view-baskets'
            else if (value = 'bi')
                window.location.href = '/repos/view-collaterals'
            else
                window.location.reload()
        },

        goBack() {
            if (this.tripartycheck != null) {
                if (this.tripartycheck)
                    window.location.href = '/repos/view-baskets'
                if (!this.tripartycheck)
                    window.location.href = '/repos/view-collaterals'
            }
        },
        userCan(a, b) {
            return userCan(a, b)

        },
        getTimezone() {
            axios.get('/get-formated-timezone').then(res => {
                this.formattedtimezone = JSON.stringify(res.data)
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
        newBasket(value) {
            var key = value[0]
            this.$store.commit('basket/' + types.ADD_BASKET, [key, value[1]]);
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
        submitRequest(addNewRequest = false) {
            for (let index = 0; index < this.basketcount; index++) {
                const childComponent = this.$refs.basketsitems[index];
                if (childComponent) {
                    childComponent.ableToSubmit(); // Call ableToSubmit if it's a function
                }
            }

            if (addNewRequest) {
                if (Object.keys(this.generalError).length > 0) {

                } else {
                    this.basketcount += 1
                    // this.postsRequests.push(this.newreqdata);
                    let newRequest = this.newreqdata
                    newRequest.reqid = this.generateRandomValue()
                    this.$store.commit('basket/' + types.ADD_BASKET, [this.basketcount - 1, newRequest]);

                    // this.$set(this.postsRequests, this.basketcount - 1, this.newreqdata)
                }
            } else {
                if (Object.keys(this.generalError).length > 0) {
                    // console.log(this.getAllBaskets, "Has errors")
                } else {
                    this.confirmsubmit = true
                }
            }
        },
        clearReq() {
            this.basketcount = 1
            let newRequestcl = this.newreqdata
            newRequestcl.reqid = this.generateRandomValue()
            this.$store.commit('basket/' + types.CLEAR_BASKETS, newRequestcl);
            // this.clear = false

        },
        doSubmit() {
            this.submitting = true

            var data = new FormData()
            let reqcount = this.getAllBaskets.length

            data.append('baskets', JSON.stringify(this.getAllBaskets))
            data.append('action', 'add')


            axios.post('/trade/CG/add-basket', data).then(response => {
                this.submitting = false
                this.confirmsubmit = false
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        if (this.tripartycheck != null) {
                            if (this.tripartycheck)
                                window.location.href = '/repos/view-baskets'
                            if (!this.tripartycheck)
                                window.location.href = '/repos/view-collaterals'
                        }
                        // this.success = false
                    }, 3000)
                } else {
                    // this.fail = true
                    // this.confirmsubmit = false
                }
            }).catch(err => {
                // this.submitting = false
                // this.confirmsubmit = false
                // this.fail = true
            })

        },
        removeBasket() {
            this.$store.commit('basket/' + types.REMOVE_BASKET, this.baskettoremove);
            if (this.basketcount != 1)
                this.basketcount -= 1
            this.generalError = {}
            this.removebasket = false

        },

        deleteRequest(value) {
            this.baskettoremove = value
            this.removebasket = true
        },
    }


}
</script>
<style scoped></style>
<style>
.basket-head {
    color: #252525;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
}
</style>