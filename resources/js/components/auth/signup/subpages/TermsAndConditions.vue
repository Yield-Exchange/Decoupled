<template>
    <div class="w-100">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <div class="d-flex justify-ontent-center w-100 mb-4 d-none">
                <img src="/assets/signup/accepttandc.png" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>
            <div class="w-100 ml-2 d-none">

                <p class="title">Depositor User Account Terms And Conditions</p>

            </div>
            <div class="w-100">
                <PdfViewer @showbuttons="(value) => showbuttons = value"
                    file="Agreement_Yield_Exchange_Depositor_Acct.pdf">
                </PdfViewer>
            </div>

        </div>
        <div class="d-flex justify-content-end w-100 gap-2 mt-3" v-if="showbuttons">
            <Decline @action="dontAccept = true" :outline="true" title="Decline" />
            <CustomSubmit :isLoading="sending" @action="acceptTerms" :outline="false" title="Accept" />
        </div>
        <ActionMessageModal @close="dontAccept = false" :show="dontAccept" width="600"
            title="We’re sorry to see you go!" icon="signup/danger.svg" primarybuttontext="Decline"
            outlinedbuttontext="Cancel" message="Feel free to reach out to info@yieldexchange.ca if you have any
                questions" @outlinedClicked="dontAccept = false" @primaryClicked="declineTerms">
        </ActionMessageModal>
        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Oops !Something Went wrong"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext="" message="" outlined="">
        </ActionMessageModal>
        <ActionMessageModal @close="iAccept = false" :show="iAccept" width="600" title="Terms & Conditions Accepted"
            icon="signup/success_promo.svg" primarybuttontext="" outlinedbuttontext="" message="" outlined="">
        </ActionMessageModal>
    </div>
</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import Decline from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import PdfViewer from '../shared/PdfViewer.vue';
import ActionMessageModal from '../shared/ActionMessageModal.vue'
import TitleWithIcon from '../shared/TitleWithIcon.vue'
import axios from 'axios';


export default {
    props: ['oldonboarding'],
    mounted() {
        this.$store.dispatch('setStageTitle', 'Please read through our Terms and Conditions')
        if (this.oldonboarding != undefined) {
            if (this.oldonboarding) {
                this.$store.dispatch('setProgress', 10)
            } else {
                this.$store.dispatch('setProgress', 60)
            }
            console.log(this.oldonboarding)
        }

    },
    computed: {
        getisTermsAndConditions() {
            return this.$store.getters.getisTermsAndConditions;
        },
    },
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput,
        PdfViewer, ActionMessageModal, TitleWithIcon, Decline
    },
    data() {
        return {
            dontAccept: false,
            reviewLater: false,
            iAccept: false,
            fail: false,
            showbuttons: false,
            sending: false
        }

    },
    methods: {
        goToWishlist() {
            this.$store.dispatch('setCurrentStep', 'waiting')
        },
        goNext() {
            this.$store.dispatch('setCurrentStep', 'keyIndividuals')
            this.$store.dispatch('setPrevStep', 'termsandcondition')
            this.$store.dispatch('setStepsTracker', 'individualandentitysummary')
            // window.location.href = '/';
        },
        joinWaitList() {
            this.$store.dispatch('setCurrentStep', 'biwaiting')
            this.$store.dispatch('setPrevStep', 'termsandcondition')
        },

        declineTerms() {
            // this.dontAccept = true
            this.submitAction('DECLINE_TERMS_AND_CONDITIONS')
        },
        acceptTerms() {
            this.submitAction('ACCEPT_TERMS_AND_CONDITIONS')

        },
        async submitAction(value) {
            console.log(this.getisTermsAndConditions, "terms and conditions")
            this.sending = true
            let oldonboardingstate = false
            if (this.oldonboarding) {
                oldonboardingstate = true
            }
            const termsandconditions = {
                action: value,
                oldonboardingstate: oldonboardingstate,
                from: "sign_up"
            }
            // console.log(termsandconditions)
            await axios.post('/depositor-terms-review', termsandconditions, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                this.sending = false
                if (response.data.success) {
                    this.$store.dispatch('setStepsTracker', 'termsandcondition')
                    if (value == 'ACCEPT_TERMS_AND_CONDITIONS') {
                        this.iAccept = true

                        setTimeout(() => {
                            if (this.oldonboarding) {
                                axios.get('logout')
                                window.location.href = 'https://yieldexchange.ca/';
                            } else {
                                this.iAccept = false;
                                this.goNext()
                            }
                        }, 6000)

                    } else {
                        setTimeout(() => {
                            // this.iAccept = false
                            window.location.href = 'https://yieldexchange.ca/';
                        }, 2000)

                    }

                    // console.log("Data Updated")
                } else {
                    this.fail = true
                    // this.failmessage = this.data.message
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                }
            }).catch(err => {
                this.sending = false
                this.fail = true
                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false

                }, 3000)
            })
        },
    },
    watch: {
        // iAccept(newValue, oldValue) {
        //     if (newValue) {
        //         setTimeout(this.goNext, 5000)
        //     }
        // }
    }
}
</script>

<style scoped>
.pass-label {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.top-title {
    margin-bottom: 30px;
}

.title {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
    width: 100%;
    text-align: center;

}
</style>