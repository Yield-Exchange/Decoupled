<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-start bg-white align-items-start p-4">
            <p class="sub-sctio-title">Please set your user details </p>
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/signup/userdetailsshort.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>


            <div class="row w-100">
                <div class="col-md-6 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => firstNameError = checkerVariable"
                        :isemptycheck="requiredChecker" v-model="firstName" :required="true" label="First Name"
                        placeholder="John" :currentValue="getLoggedInUser.first_name" input_type="text" />
                </div>
                <div class="col-md-6 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => lastNameError = checkerVariable"
                        :isemptycheck="requiredChecker" v-model="lastName" :required="true" label="Last Name"
                        placeholder="Doe" :currentValue="getLoggedInUser.last_name" input_type="text" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput :isemptycheck="requiredChecker" v-model="email" :disabled="true" :required="true"
                        label="Email" :currentValue="getLoggedInUser.email" placeholder="Johndoe@org.com"
                        input_type="email" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTelInput @hasError="(checkerVariable) => telError = checkerVariable"
                        :isemptycheck="requiredChecker" v-model="telephone" :required="true" label="Telephone"
                        placeholder="Enter Telephone Number" :currentValue="getLoggedInUser.phone" input_type="tel" />
                </div>

                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => countryError = checkerVariable"
                        :isemptycheck="requiredChecker" v-model="country" :required="true" :tooltip="true"
                        tooltiptitle="Enter your country of residense here."
                        :currentValue="getWaitingListData != null ? getWaitingListData.country : null" label="Country"
                        placeholder="Enter Country of Residense" input_type="text" />
                </div>
                <div class="col-md-6 mb-20">
                    <CustomSelectInput :isemptycheck="requiredChecker" v-model="whereyouheardaboutus" :tooltip="true"
                        :currentValue="getWaitingListData != null ? getWaitingListData.whereyouheardaboutus : null"
                        :required="false" label="Where are you signing up from" :options="heardaboutus"
                        tooltiptitle="Select where you heard about the platform from"
                        placeholder="Select where you heard about the platform from" input_type="text" />
                </div>
                <div class="col-md-6 mb-20 ">
                    <CustomTextInput @hasError="(checkerVariable) => specifyError = checkerVariable"
                        :isemptycheck="requiredChecker" v-model="specify" label="Specify here"
                        tooltiptitle="Specify the platform,eg LinkedIn"
                        :currentValue="getWaitingListData != null ? getWaitingListData.specify : null" :tooltip="true"
                        :placeholder="placeholdertext" input_type="text" />
                </div>
            </div>
            <p class="tnc">*By clicking <span style="font-weight: 700;">Submit</span> you consent to receiving
                marketplace
                and rate update emails from Yield
                Exchange </p>
        </div>
        <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <GoBack @action="goBack" :outline="true" title="Previous" />
            <CustomSubmit :isLoading="sending" @action="submitForm" :outline="false" title="submit" />
        </div>


        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Ooops! An issue occured"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext=""
            message="You've not been added to our waiting list yet!please try again or contact info@yieldexchange.ca"
            outlined="">
        </ActionMessageModal>
    </div>
</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import GoBack from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomTelInput from '../shared/CustomTelInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import Checkbox from '../shared/Checkbox.vue';
import ActionMessageModal from '../shared/ActionMessageModal.vue';
import TitleWithIcon from '../shared/TitleWithIcon.vue';

export default {
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput, Checkbox, ActionMessageModal, TitleWithIcon, GoBack, CustomTelInput
    },
    mounted() {
        this.$store.dispatch('setStageTitle', 'Enter your details to join our waiting list')
        this.$store.dispatch('setProgress', 50)

    },
    data() {
        return {
            firstName: null,
            lastName: null,
            email: null,
            telephone: null,
            country: null,
            whereyouheardaboutus: null,
            specify: null,
            requiredChecker: false,
            firstNameError: false,
            lastNameError: false,
            telError: false,
            specifyError: false,
            countryError: false,
            fail: false,
            sending: false,
            failmessage: 'Please ensure all fields are filled in correctly and retry. If the issue persists, please contact us at info@yieldexchange.ca.',
            heardaboutus: [
                'In-Person Events', 'Social Media', 'Referral', 'Email Campaign', 'Google Search', 'Word of Mouth'
            ],
            placeholdertext: 'Please where you heard about us'
        }
    },
    computed: {
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getWaitingListData() {
            return this.$store.getters.getWaitingListData
        },

    },
    methods: {

        submitForm() {
            if (this.canSubmit()) {
                this.submitAction()
            } else {
                // console.log("Issues found")
                this.requiredChecker = true
            }

        },
        async submitAction() {
            this.sending = true
            const waitingUser = {
                first_name: this.firstName,
                phone_number: this.telephone,
                last_name: this.lastName,
                user_id: this.getLoggedInUser.user_id,
                email: this.email,
                platform: this.whereyouheardaboutus,
                country: this.country,
                specify_platform: this.specify
            }
            await axios.post('/keep-me-informed', waitingUser, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                this.sending = false
                if (response.data.success) {
                    this.$store.dispatch('setStepsTracker', 'waitinglistdetails')
                    this.goNext()
                } else {
                    this.fail = true
                    // this.failmessage = this.data.message
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                }
            }).catch(err => {
            this.sending=false
                this.fail = true
                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false

                }, 3000)
            })
        },
        canSubmit() {
            if (this.firstName != null
                && this.lastName != null
                // && this.email != null
                && !this.specifyError && !this.firstNameError &&
                !this.lastNameError && !this.countryError && !this.telError
                && this.telephone != null
                && this.country != null) {
                const loogeddata = {
                    first_name: this.firstName,
                    phone: this.telephone,
                    last_name: this.lastName,
                    user_id: this.getLoggedInUser.user_id,
                    email: this.email,
                }
                const waitingData = {
                    country: this.country,
                    whereyouheardaboutus: this.whereyouheardaboutus,
                    specify: this.specify
                }
                this.$store.dispatch('setLoggedInUser', loogeddata)
                this.$store.dispatch('setWaitingListData', waitingData)
                return true;
            }
            else {
                return false
            }
        },
        setPlaceholder(value) {
            console.log(value === 'In-Person Events')
            switch (value) {
                case 'In-Person Events':
                    this.placeholdertext = 'Please specify the event location';
                    break;
                case 'Social Media':
                    this.placeholdertext = 'Please specify the social media platform';
                    break;
                case 'Referral':
                    this.placeholdertext = 'Please specify the referral source';
                    break;
                case 'Email Campaign':
                    this.placeholdertext = 'Please specify the email campaign details';
                    break;
                case 'Google Search':
                    this.placeholdertext = 'Please specify the search query';
                    break;
                case 'Word of Mouth':
                    this.placeholdertext = 'Please specify who referred';
                    break;
                default:
                    this.placeholdertext = 'Please specify the details here';
            }
        },

        goNext() {
            this.$store.dispatch('setCurrentStep', 'regcomplete')
        },
        goBack() {
            this.$store.dispatch('setCurrentStep', 'landing')
            this.$store.dispatch('setUserType', null)

        }

    },
    watch: {
        whereyouheardaboutus() {
            this.setPlaceholder(this.whereyouheardaboutus)
            // console.log(this.whereyouheardaboutus)
        },
    }
}
</script>
<style scoped>
.top-title {
    margin-bottom: 30px;
}

.entity_question {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 16px;
    /* 100% */
}

.sectionHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 144.444% */
    text-transform: capitalize;
}

.tnc {
    color: #000;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}

.thirdpartyHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    padding: 0;
    margin: 0 !important;
}

.thirdpartytext {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 18px;
    padding: 0;
    /* 112.5% */
}


.textarea {
    border-radius: 10px;
    border: 1px solid #D9D9D9;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
}

.top-title p {
    color: #252525;

    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.title {
    color: #5063F4;
    font-family: Montserrat;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;

}

.sub-sctio-title {
    color: #5063F4;
    font-family: Montserrat;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    text-align: center;
    width: 100%;
    margin: 0 auto;
    /* 130% */
    /* text-transform: capitalize; */

}
</style>