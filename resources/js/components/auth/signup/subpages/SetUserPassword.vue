<template>
    <div class="w-100 d-flex flex-column">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <p class="title">Please set your password</p>

            <div class="d-flex justify-ontent-center w-100 mb-4">
                <img src="/assets/signup/userpassword.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>
            <div class="w-100 ml-2">

                <!-- <p class="title">{{ getLoggedInUser.first_name + " " + getLoggedInUser.last_name }} <span> please set your
                        password</span></p> -->

            </div>

            <div class="row w-100 gap-y-3">
                <div class="col-md-6 mb-20 ">
                    <CustomPasswordInput @strengthUpdate="strengthUpdate" label="Password" :showPassword="showPassword"
                        minLength="8" :matcherror="true" :isemptycheck="passisemptycheck" v-model="password"
                        :required="true" placeholder="Enter your password" input_type="password" />
                </div>
                <div class="col-md-6 mb-20 my-2">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        <p class="p-0 m-0 pass-label">Password Strength </p>
                        <div class="d-flex flex-column justify-content-between ">
                            <div v-if="passwordStrength > 3"> Strong Password</div>
                            <div v-else> Add numbers or symbols to strengthen your password </div>
                            <div class="d-flex mt-2 gap-2">
                                <div class="strength-indicator " v-for="i in 5" :key="i" :class="currentIndicator">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-20">
                    <CustomPasswordInput label="Confirm Password" :showPassword="showPassword" :required="true"
                        minLength="8" :isemptycheck="cpassisemptycheck" :matcherror="isPasswordMatch"
                        v-model="confirmPassword" placeholder="Confirm your password" input_type="text" />
                </div>
                <div class="col-md-6 mb-20 my-2">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        <p class="p-0 m-0 pass-label">Password Strength </p>
                        <div class="d-flex flex-column justify-content-between ">
                            <div v-if="isPasswordMatch">Password match </div>
                            <div v-else>Please ensure the passwords match </div>
                            <div class="d-flex mt-2 gap-2">
                                <div class="strength-indicator" v-for="i in 5" :key="i"
                                    :class="{ 'indicate-success': isPasswordMatch, 'indicate-low': !isPasswordMatch && confirmPassword !== '' && confirmPassword != null, 'indicate-none': confirmPassword == null || confirmPassword == '' }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-20 my-3">
                    <div class="d-flex gap-2">
                        <input type="checkbox" v-model="showPassword" name="" id="">
                        <div>Show Password</div>

                    </div>
                </div>

            </div>
        </div>
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <GoBack @action="goBack" :outline="true" title="Previous" />
            <CustomSubmit :isLoading="sending" @action="setPassword" :outline="false" title="submit" />

        </div>

        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Password has not been set !"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext="" :message="failmessage" outlined="">
        </ActionMessageModal>
        <ActionMessageModal @close="success = false" :show="success" width="600" title="Password set succesfully"
            icon="signup/success_promo.svg" primarybuttontext="" outlinedbuttontext="" :message="redirectMessage"
            outlined="">
        </ActionMessageModal>
    </div>
</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import GoBack from '../shared/CustomSubmit.vue';
import CustomPasswordInput from '../shared/CustomPasswordInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import ActionMessageModal from '../shared/ActionMessageModal.vue';
import TitleWithIcon from '../shared/TitleWithIcon.vue';

export default {
    components: {
        CustomSubmit, CustomPasswordInput, CustomSelectInput,
        ActionMessageModal, TitleWithIcon, GoBack
    },

    mounted() {
        this.$store.dispatch('setProgress', 50)
        this.$store.dispatch('setStageTitle', 'Enter Your Details to Complete Registration')
    },
    data() {
        return {
            showPassword: false,
            passwordStrength: 0,
            currentIndicator: 'indicate-none',
            password: null,
            confirmPassword: null,
            isPasswordMatch: false,
            isemptycheck: false,
            success: false,
            fail: false,
            passisemptycheck: false,
            cpassisemptycheck: false,
            sending: false,
            redirectMessage: "Check your email to confirm your account registration.",
            failmessage: 'Please retry to set the password or contact admin info@yieldexhange.ca '
        }
    },
    computed: {
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getIsConference() {
            return this.$store.getters.getIsConference;
        },
    },
    methods: {
        setPassword() {
            if (!this.isPasswordMatch) {
                // this.passisemptycheck = false
                this.passisemptycheck = true
                this.cpassisemptycheck = true
            } else {
                this.passisemptycheck = false
                this.cpassisemptycheck = false
                this.isemptycheck = false
                this.submitAction()

            }
        },
        currentIndicators() {
            if (this.passwordStrength > 0 && this.passwordStrength <= 3) {
                return 'indicate-low'

            } else if (this.passwordStrength >= 4) {

                return 'indicate-success'
            } else {
                return 'indicate-none'
            }
        },
        async submitAction() {
            this.sending = true
            const password = {
                pass: this.password,
                cpass: this.confirmPassword,
                from: "sign_up"
            }
            // console.log(password)
            await axios.post('/reset-password-final-step', password, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                this.sending = false
                if (response.data.success) {
                    this.success = true
                    if (this.getIsConference) {
                        this.redirectMessage = null
                    }
                    setTimeout(() => {
                        this.success = false
                        this.$store.dispatch('setStepsTracker', 'setpassword')
                        this.goNext()
                    }, 5000)
                    // console.log("Data Updated")
                } else {
                    this.fail = true
                    this.failmessage = response.data
                    console.log("response", response.data)
                    setTimeout(() => {
                        this.fail = false
                    }, 3000)
                }
            }).catch(err => {
                this.sending = false
                this.fail = true
                this.failmessage = err?.response?.data?.message
                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false

                }, 3000)
            })
        },
        goNext() {

            if (this.getIsConference) {
                this.$store.dispatch('setCurrentStep', 'keyIndividuals')
                // this.$store.dispatch('setPrevStep', 'termsandcondition')
                this.$store.dispatch('setStepsTracker', 'individualandentitysummary')
                // this.$store.dispatch('setCurrentStep', 'termsandcondition')
            } else {

                this.$store.dispatch('setCurrentStep', 'termsandcondition')
            }

        },
        goBack() {
            this.$store.dispatch('setCurrentStep', 'userDetails')
        },
        strengthUpdate(value) {
            this.passwordStrength = value
            this.currentIndicator = this.currentIndicators()
        },
        confirmPasswordChecker() {
            if (this.confirmPassword != null && this.password != null) {
                if (this.confirmPassword == this.password) {
                    this.isPasswordMatch = true
                } else {
                    this.isPasswordMatch = false
                }

            } else {
                this.isPasswordMatch = false
                this.cpassisemptycheck = true
            }
        }
    },
    watch: {
        password() {
            if (this.password == null) {
                this.passisemptycheck = true
            } else {
                this.passisemptycheck = false

            }
            this.confirmPasswordChecker()
        },
        confirmPassword() {
            if (this.confirmPassword == null) {
                this.cpassisemptycheck = true
            } else {
                this.cpassisemptycheck = false

            }
            this.confirmPasswordChecker()
        }
    }
}
</script>
<style scoped>
.strength-indicator {
    border-radius: 5px;
    width: 70px;
    height: 6px;

}

.passwordmessage {
    color: #2A2A2A;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

.indicate-none {
    background: #D9D9D9;

}

.indicate-success {

    background: #44E0AA;
}

.indicate-low {
    background: #FF2E2E;

}

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

.textarea {
    border-radius: 10px;
    border: 1px solid #D9D9D9;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
}

.title {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */

}

.title span {
    color: black;
}
</style>