<template>
    <div class="signup-sidebar sidebar-main sidebar-expand-md h-100">
        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <!-- /sidebar mobile toggler -->
        <!-- Sidebar content -->
        <div class="sidebar-content ">
            <div class="mt-3 d-flex justify-content-center w-100">
                <img class="mt-3" style="width: 228px;height: 28.064px;" src="/assets/signup/yie-Logo.svg" alt="My logo"
                    srcset="">
            </div>

            <!-- Main navigation -->
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="mt-5">
                    <ol class="sidebar-items" style="">
                        <li v-if="!getIsConference && !getisTermsAndConditions" @click="reRoute('', 'landing')"
                            :class="{ 'active-tab': isActive('landing') }">1. &nbsp;
                            Getting
                            Started
                        </li>
                        <li v-if="getisTermsAndConditions" @click="reRoute('', 'termsandcondition')"
                            :class="{ 'active-tab': isActive('termsandcondition') }">1. &nbsp;
                            Terms and Conditions
                        </li>
                        <template
                            v-if="(userType == 'depositor' && depositorType == 'businessInvestor' && !getIsConference) || userType == 'bank'">
                            <li @click="reRoute('', 'depOrgDetails')"
                                :class="{ 'active-tab': isActive('depOrgDetails') }">
                                2.
                                &nbsp;Organization Details</li>
                            <li @click="reRoute('depOrgDetails', 'userDetails')"
                                :class="{ 'active-tab': isActive('userDetails') }">3.
                                &nbsp;User
                                Details</li>
                            <li @click="reRoute('userDetails', 'setpassword')"
                                :class="{ 'active-tab': isActive('setpassword') }">4. &nbsp;
                                Account Password</li>
                            <li @click="reRoute('setpassword', 'termsandcondition')"
                                :class="{ 'active-tab': isActive('termsandcondition') }">
                                5. &nbsp; Terms and conditions</li>
                            <li @click="reRoute('termsandcondition', 'individualandentitysummary')"
                                :class="{ 'active-tab': isActive(['entitydetails', 'individualandentitysummary', 'keyIndividuals']) }">
                                6. &nbsp;Key Individuals and Entities</li>
                            <li @click="reRoute('termsandcondition', 'documentsUpload')"
                                :class="{ 'active-tab': isActive('documentsUpload') }">
                                7. &nbsp; Document Upload</li>
                            <li @click="reRoute('termsandcondition', 'aftertermswaitingbay')"
                                :class="{ 'active-tab': isActive('aftertermswaitingbay') }">
                                8. &nbsp;Account Verification</li>
                        </template>
                        <template v-if="getIsConference">
                            <li @click="reRoute('', 'depOrgDetails')"
                                :class="{ 'active-tab': isActive('depOrgDetails') }">
                                1.
                                &nbsp;Organization Details</li>
                            <li @click="reRoute('depOrgDetails', 'userDetails')"
                                :class="{ 'active-tab': isActive('userDetails') }">2.
                                &nbsp;User
                                Details</li>
                            <li @click="reRoute('userDetails', 'setpassword')"
                                :class="{ 'active-tab': isActive('setpassword') }">3. &nbsp;
                                Account Password</li>
                            <!-- <li @click="reRoute('setpassword', 'termsandcondition')"
                                :class="{ 'active-tab': isActive('termsandcondition') }">
                                5. &nbsp; Terms and conditions</li> -->
                            <li @click="reRoute('setpassword', 'individualandentitysummary')"
                                :class="{ 'active-tab': isActive(['entitydetails', 'individualandentitysummary', 'keyIndividuals']) }">
                                4. &nbsp;Key Individuals and Entities</li>
                            <li @click="reRoute('setpassword', 'documentsUpload')"
                                :class="{ 'active-tab': isActive('documentsUpload') }">
                                5. &nbsp; Document Upload</li>
                            <!-- <li @click="reRoute('termsandcondition', 'aftertermswaitingbay')"
                                :class="{ 'active-tab': isActive('aftertermswaitingbay') }">
                                6. &nbsp;Account Verification</li> -->
                        </template>

                        <template v-if="userType == 'depositor' && depositorType == 'personalInvestor'">
                            <!-- personal incvestor sidebar  -->
                            <li @click="reRoute('landing', 'waiting')" :class="{ 'active-tab': isActive('waiting') }">2.
                                &nbsp;Join Our
                                Waitlist</li>

                        </template>
                        <!-- waiting list sidebar  -->
                        <template v-if="userType == 'waiting'">
                            <li @click="reRoute('landing', 'waitinglistdetails')"
                                :class="{ 'active-tab': isActive('waitinglistdetails') }">2. &nbsp;User Details</li>
                            <li @click="reRoute('waitinglistdetails', 'regcomplete')"
                                :class="{ 'active-tab': isActive('regcomplete') }">3.
                                &nbsp;Account Verification</li>
                        </template>
                    </ol>
                </div>

                <div style="margin-bottom: 40%;" class="ml-2">
                    <div class="d-flex justify-content-start align-items-center my-3 gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="31" viewBox="0 0 30 31" fill="none">
                            <path
                                d="M15.4166 9.19629C13.4679 9.19629 12.2057 9.9947 11.2148 11.4186C11.035 11.6769 11.0904 12.0311 11.3412 12.2212L12.3944 13.0198C12.6476 13.2118 13.0079 13.1669 13.2062 12.9185C13.8177 12.1523 14.2714 11.7112 15.2266 11.7112C15.9777 11.7112 16.9067 12.1946 16.9067 12.9229C16.9067 13.4735 16.4522 13.7563 15.7106 14.172C14.8458 14.6569 13.7013 15.2603 13.7013 16.7698V17.0088C13.7013 17.3324 13.9637 17.5947 14.2873 17.5947H16.0566C16.3802 17.5947 16.6425 17.3324 16.6425 17.0088V16.8678C16.6425 15.8215 19.7008 15.7779 19.7008 12.9463C19.7008 10.8139 17.4889 9.19629 15.4166 9.19629ZM15.1719 18.3139C14.2394 18.3139 13.4808 19.0726 13.4808 20.0051C13.4808 20.9376 14.2394 21.6963 15.1719 21.6963C16.1045 21.6963 16.8631 20.9376 16.8631 20.0051C16.8631 19.0726 16.1045 18.3139 15.1719 18.3139Z"
                                fill="#F8FAFF" />
                            <path
                                d="M4.1911 12.3287C5.00547 8.85688 7.71626 6.14609 11.188 5.33172C13.6953 4.74359 16.3047 4.74359 18.812 5.33172C22.2837 6.14609 24.9945 8.85689 25.8089 12.3287C26.397 14.8359 26.397 17.4453 25.8089 19.9526C24.9945 23.4244 22.2837 26.1352 18.812 26.9495C16.3047 27.5377 13.6953 27.5377 11.188 26.9495C7.71626 26.1352 5.00547 23.4244 4.1911 19.9526C3.60297 17.4453 3.60297 14.8359 4.1911 12.3287Z"
                                fill="#F8FAFF" fill-opacity="0.15" stroke="#F8FAFF" stroke-width="1.35"
                                stroke-linecap="round" />
                        </svg>
                        <p class="text-white m-0 p-0 support-style">
                            <a href="https://yieldexchange.tawk.help/" target="_blank">FAQs</a>
                        </p>
                    </div>

                </div>
            </div>
            <!-- /main navigation -->

        </div>
        <PopUpModal @close="fail = false" :show="fail" width="600">
            <TitleWithIcon :title="title" icon="infoicon.png">

            </TitleWithIcon>
            <p class="passwordmessage mt-3 mb-5"> {{ failmessage }}</p>
        </PopUpModal>
        <!-- /sidebar content -->
    </div>
</template>


<script>
import PopUpModal from '../shared/PopUpModal.vue'
import TitleWithIcon from '../shared/TitleWithIcon.vue'
export default {
    mounted() {
        // console.log("cureent step", this.$store.getters.getCurrentStep)
    },
    data() {
        return {
            fail: false,
            title: 'Sorry! We can’t take you there yet',
            failmessage: "Please make sure you've completed all the preceding steps before moving forward."
        }
    },
    components: {
        PopUpModal, TitleWithIcon
    },
    computed: {
        currentStep() {
            return this.$store.getters.getCurrentStep
        },
        depositorType() {
            return this.$store.getters.getDepositorType
        },
        userType() {
            return this.$store.getters.getUserType
        },
        getStepsTracker() {
            return this.$store.getters.getStepsTracker
        },
        getIsConference() {
            return this.$store.getters.getIsConference;
        },
        getisTermsAndConditions() {
            return this.$store.getters.getisTermsAndConditions;
        },
    },
    methods: {
        isActive(value) {
            if (Array.isArray(value) && value.includes(this.currentStep))
                return true
            else if (this.currentStep == value)
                return true
            else
                return false
        },
        reRoute(step, reroute) {
            if (this.getStepsTracker.includes('waitinglistdetails') || this.getStepsTracker.includes('aftertermswaitingbay')) {
                this.fail = true
                this.title = "Sorry! we cannot take you there, You have been logged out"
                this.failmessage = "Apologies, you've finished all the necessary steps, and we've logged you out of the system. Please await further updates via email. Thank you for joining us."
                setTimeout(() => {
                    this.fail = false
                    window.location.href = 'https://yieldexchange.ca/'

                }, 10000)
            } else if (this.getStepsTracker.includes(step) || step == '') {
                this.$store.dispatch('setCurrentStep', reroute)
            } else {
                this.fail = true
                // console.log("Failed")

            }

        }
    }

}

</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

.support-style {
    color: #F8FAFF;

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.support-style a {
    color: white;
}

.check_chat_badge {
    display: none;
}

.sidebar-items {
    padding-left: 0 !important;
}

.sidebar-items li,
.sidebar-items li a {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Off-White, #F4F5F6);

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    cursor: pointer;
}

.sidebar-items li {
    padding: 20px;
    /* padding: 0; */
}

.signup-sidebar {
    /* background-image: url('/assets/signup/signup-sidebar-bg.svg'); */
    height: auto !important;
    min-height: 100vh;

}

.active-tab {
    background-image: url('/assets/signup/active-navbar-background.png');
    background-repeat: no-repeat;

}
</style>
