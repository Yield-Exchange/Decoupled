<template>
    <SignUpLayout>
        <div class="w-100">
            <div class="w-100 d-flex flex-column align-items-center justify-content-center">
                <p class="progress-text p-0 m-0">Progress</p>
                <div class="w-25 d-flex justify-content-center gap-2 align-items-center" style="margin-bottom: 30px;">
                    <LvProgressBar class="progressbar w-75" :value="getProgress" color="#44E0AA" />
                    <p class="p-0 m-0 progress-value">{{ getProgress }} %</p>
                </div>
            </div>
            <p class="title-bar py-3"
                v-if="getsStageTitle != '' && getCurrentStep != 'landing' && getCurrentStep != 'deptype'">{{
                        getsStageTitle
                    }}
            </p>
            <div v-if="(getCurrentStep == 'landing' || getCurrentStep == 'deptype') && !getIsConference && !getisTermsAndConditions"
                style="min-height: 65vh; display: flex;justify-content: center;width:100%;align-items: center; flex-direction: column;">
                <p class="title-bar py-3" v-if="getsStageTitle != ''">{{ getsStageTitle }}</p>
                <ChooseAccount v-if="getCurrentStep == 'landing'" />
                <ChooseDepositorType v-if="getCurrentStep == 'deptype'" />
            </div>
            <WaitingList v-if="getCurrentStep == 'waiting'" />
            <BusinessInvestorWaitingList v-if="getCurrentStep == 'biwaiting'" />
            <div v-if="!getIsConference && !getisTermsAndConditions" class="px-5">
                <DepositorOrganizationDetails v-if="getCurrentStep == 'depOrgDetails'"
                    :institution_types="institution_types" :fis="fis" />
                <SetUserDetails :ipinfokey="ipinfokey" v-if="getCurrentStep == 'userDetails'" />
                <SetUserPassword v-if="getCurrentStep == 'setpassword'" />
                <TermsAndConditions v-if="getCurrentStep == 'termsandcondition'" />
                <!-- <SetUserPassword v-if="getCurrentStep == 'userpassword'"/> -->
                <RegisterdBusinessAddress v-if="getCurrentStep == 'registeredbusinessaddress'" />
                <IndividualAndEntititySummary
                    v-if="['entitydetails', 'individualandentitysummary', 'keyIndividuals'].includes(getCurrentStep)" />
                <DocumentsUpload v-if="getCurrentStep == 'documentsUpload'" />
                <WaitingListDetails v-if="getCurrentStep == 'waitinglistdetails'" />
                <RegistrationComplete v-if="getCurrentStep == 'regcomplete'" />
                <TermsAcceptedWaiting v-if="getCurrentStep == 'aftertermswaitingbay'" />
            </div>
            <div v-else class="px-5">

                <DepositorOrganizationDetails v-if="getIsConference && getCurrentStep == 'depOrgDetails'"
                    :institution_types="institution_types" :fis="fis" />
                <SetUserDetails :ipinfokey="ipinfokey" v-if="getIsConference && getCurrentStep == 'userDetails'" />
                <SetUserPassword v-if="getIsConference && getCurrentStep == 'setpassword'" />
                <RegisterdBusinessAddress v-if="getIsConference && getCurrentStep == 'registeredbusinessaddress'" />
                <IndividualAndEntititySummary
                    v-if="['entitydetails', 'individualandentitysummary', 'keyIndividuals'].includes(getCurrentStep)" />
                <DocumentsUpload v-if="getIsConference && getCurrentStep == 'documentsUpload'" />
                <WaitingListDetails v-if="getIsConference && getCurrentStep == 'waitinglistdetails'" />
                <RegistrationComplete v-if="getIsConference && getCurrentStep == 'regcomplete'" />
                <TermsAndConditions v-if="getCurrentStep == 'termsandcondition' && getisTermsAndConditions"
                    :oldonboarding="true" />
            </div>
        </div>
    </SignUpLayout>
</template>

<script>
import BusinessInvestorWaitingList from './BusinessInvestorWaitingList.vue'
import ChooseAccount from './ChooseAccount.vue';
import ChooseDepositorType from './ChooseDepositorType.vue';
import DepositorStep from './DepositorStep.vue';
import WaitingList from './WaitingList.vue';
import IndividualAndEntititySummary from './subpages/IndividualAndEntititySummary'
import DepositorUserDetails from './subpages/DepositorUserDetails.vue';
import SetUserDetails from './subpages/SetUserDetails.vue'
import SetUserPassword from './subpages/SetUserPassword.vue';
import TermsAndConditions from './subpages/TermsAndConditions.vue'
import DepositorOrganizationDetails from './subpages/DepositorOrganizationDetails.vue';
import RegisterdBusinessAddress from './subpages/RegisterdBusinessAddress.vue';
import KeyIndividuals from './subpages/KeyIndividuals.vue';
import RegistrationComplete from './subpages/RegistrationComplete.vue'
import SignUpLayout from './layouts/SignUpLayout.vue'
import TermsAcceptedWaiting from './subpages/TermsAcceptedWaiting.vue';
import EntityDetails from './subpages/EntityDetails.vue';
// import the component
// import LvProgressBar from 'lightvue/progressbar';

import LvProgressBar from 'lightvue/progress-bar'
import WaitingListDetails from './subpages/WaitingListDetails.vue';
import DocumentsUpload from './subpages/DocumentsUpload.vue';

export default {
    props: ['timezones', 'user', 'ipinfokey', 'fis', 'fi_types'],
    beforeMount() {
        if (this.fi_types) {
            let fitypes = JSON.parse(this.fi_types);
            fitypes.forEach(item => {
                item['name'] = item.description
            })
            this.institution_types = fitypes
        }
    },
    beforeDestroy() {
        window.removeEventListener('beforeunload', this.handleBeforeUnload);
    },
    mounted() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        window.addEventListener('beforeunload', this.handleBeforeUnload);

        // Accessing URL parameters
        const conferencelink = urlParams.get('isconfrence');
        const termslink = urlParams.get('termsandconditions');
        this.setIsConference(conferencelink)
        this.setisTermsAndConditions(termslink)
        // console.log(this.timezones)
        if (this.timezones != null)
            this.$store.dispatch('setTimezones', JSON.parse(this.timezones))
        if (this.user != null) {
            let userjson = JSON.parse(this.user)
            this.SetCurrentUser(userjson)
            if (userjson.organization) {
                this.updateOrganization(userjson)
                console.log(userjson.organization, "JSON")
            }

        }
        this.setProvinces()
        this.setIndustries()
        this.setDepositorsandFiCount()
    },
    components: {
        EntityDetails, IndividualAndEntititySummary, LvProgressBar, DepositorOrganizationDetails, TermsAndConditions, SetUserPassword, ChooseAccount, ChooseDepositorType, WaitingList, DepositorStep, DepositorUserDetails, SetUserDetails, RegisterdBusinessAddress, KeyIndividuals,
        WaitingListDetails, RegistrationComplete, SignUpLayout, BusinessInvestorWaitingList, TermsAcceptedWaiting, DocumentsUpload
    },
    data() {
        return {
            institution_types: null
        }
    },
    beforeUpdate() {
        if (['entitydetails', 'individualandentitysummary', 'keyIndividuals'].includes(this.getCurrentStep)) {
            if (this.getCurrentStep == "entitydetails") {
                this.$store.dispatch('setProgress', 70)

            } else if (this.getCurrentStep == "individualandentitysummary") {
                this.$store.dispatch('setProgress', 80)

            } else if (this.getCurrentStep == "keyIndividuals") {
                this.$store.dispatch('setProgress', 70)

            }
        }
    },
    computed: {

        getUserType() {
            return this.$store.getters.getUserType;
        },
        getDepositorType() {
            return this.$store.getters.getDepositorType;

        },
        getsStageTitle() {
            return this.$store.getters.getsStageTitle;
        },
        getCurrentStep() {
            return this.$store.getters.getCurrentStep;
        },
        getProgress() {
            return this.$store.getters.getProgress;
        },
        getIsConference() {
            return this.$store.getters.getIsConference;
        },
        getisTermsAndConditions() {
            return this.$store.getters.getisTermsAndConditions;
        },

    },
    methods: {
        handleBeforeUnload(event) {
            // event.preventDefault()
            // const confirmationMessage = "You have unsaved changes. Are you sure you want to leave?";
            // event.returnValue = confirmationMessage; // For most browsers
            // console.log(event)
            // return confirmationMessage; // For some browsers

            // 

            // this.$refs.unsavedChangesModal.show();
            event.preventDefault();
            event.returnValue = null;
        },
        SetCurrentUser(value) {
            // console.log(value)
            const updatedUser = {
                job_title: value.demographic_data.job_title,
                timezone: value.demographic_data.timezone,
                foundkey: this.foundkey,
                linkedin: value.demographic_data.linkedin,
            }
            const data = {
                first_name: value.firstname,
                phone: value.demographic_data.phone,
                job_title: value.demographic_data.job_title,
                last_name: value.lastname,
                user_id: value.id,
                email: value.email,
            }
            this.$store.dispatch('setLoggedInUser', data)
            this.$store.dispatch('updateUserInfo', updatedUser)

        },
        setIsConference(value) {
            if (value == "isconfrence") {
                this.$store.dispatch('setIsConference', true)
                this.$store.dispatch('setCurrentStep', 'depOrgDetails')

            } else {
                // console.log("Not Conference")
            }
        },
        setisTermsAndConditions(value) {
            if (value == "termsandconditions") {
                this.$store.dispatch('setisTermsAndConditions', true)
                this.$store.dispatch('setCurrentStep', 'termsandcondition')

            } else {
                // console.log("Not Conference")
            }
        },
        updateOrganization(user) {
            let userorg = user.organization
            // console.log(userorg)
            let organization = {
                'organization_id': userorg.id,
                'organizationName': userorg.name,
                'trade_name': userorg.trade_name,
                'industry': userorg.industry_id,
                'fi_type_id': userorg.fi_type_id,
                'incorporation_number': userorg.incoporation_number,
                'cra_number': userorg.CRA_business_number,
                'incorporation_date': userorg.incoporation_date,
                'province_of_incorporation': userorg.province_of_incorporation,
                'intended_use_of_account': JSON.parse(userorg.intended_use),
                'website': userorg.demographic_data.website,
                'company_desc': userorg.demographic_data.description,
                'business_address': {
                    'streetAddress': userorg.demographic_data.address1
                },
                // 'uploaded_image': userorg.logo,
                'useRegAddressForMailingAdress': userorg.demographic_data.address1 == userorg.demographic_data.address2 ? true : false,
                'mailing_address': {
                    'streetAddress': userorg.demographic_data.address2
                },
            }
            if (userorg.type == 'BANK') {
                let financialInstitutions = JSON.parse(this.fis)
                let foundorganization = financialInstitutions.find(item => item.name == userorg.name).id
                organization['organizationID'] = foundorganization.toString()
                organization['incorporation_type'] = userorg.fi_type_id.toString()
            } else {
                organization['incorporation_type'] = userorg.registration_type
            }

            this.$store.dispatch('setOrgDetails', organization)

        },
        async setProvinces() {
            await axios.get('/get-all-provinces-sign-up').then(response => {
                // if (response.data.success)
                // console.log(response.data)
                this.$store.dispatch('setProvinces', response.data)
            }).catch(err => {
                // console.log(err)
            })
        },
        async setDepositorsandFiCount() {
            let counter = []
            await axios.get('/depositors-Fi-count').then(response => {
                response.data.forEach(element => {
                    if (element.type == "DEPOSITOR")
                        counter[0] = this.describeCount(element.count)
                    if (element.type == "BANK")
                        counter[1] = element.count
                });
                // console.log(counter)
                this.$store.dispatch('setDepositorFICount', counter)
            }).catch(err => {
                // console.log(err)
            })
        },
        async setIndustries() {
            await axios.get('/industries').then(response => {
                // if (response.data.success)
                // console.log(response.data)

                this.$store.dispatch('setIndustries', response.data)
            }).catch(err => {
                // console.log(err)
            })
        },
        describeCount(number) {
            if (number > 1000) {
                return "thousands";
            } else if (number > 500) {
                return "over five hundred";
            } else if (number > 100) {
                return "hundreds";
            } else if (number <= 100 && number > 50) {
                return "upto one hundred";
            } else if (number <= 50 && number >= 20) {
                return "upto fifty";
            } else {
                return "upto twenty ";
            }
        }
    }
}

</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

.tooltip-inner {
    color: var(--Neutral-600, #6F6C90);

    /* Yield Exchange Text Styles/Tooltips */
    font-family: Montserrat !important;
    font-size: 11px;
    font-style: normal;
    font-weight: 400;
    line-height: 14px;

    /* Cards/Short Default */
    box-shadow: 0px 5px 14px 0px rgba(8, 15, 52, 0.04), 0px 1px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    /* 127.273% */
}

.mb-20 {
    margin-bottom: 15px !important;
}

.mbi-20 {
    margin-bottom: 20px !important;

}

.mb-10 {
    margin-bottom: 10px !important;
}

/* Bottom Tooltip */
/* Top Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=top] .arrow::before,
.tooltip.bs-tooltip-top .arrow::before {
    box-shadow: 0px 5px 14px 0px rgba(8, 15, 52, 0.04), 0px 1px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-left: -3px;
    content: "";
    border-width: 10px 10px 0;
    border-top-color: #ffffff;
}

/* Bottom Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=bottom] .arrow::before,
.tooltip.bs-tooltip-bottom .arrow::before {
    box-shadow: 0px -5px 14px 0px rgba(8, 15, 52, 0.04), 0px -1px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-top: -3px;
    content: "";
    border-width: 0 10px 10px;
    border-bottom-color: #ffffff;
}

/* Right Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=right] .arrow::before,
.tooltip.bs-tooltip-right .arrow::before {
    box-shadow: -5px 0px 14px 0px rgba(8, 15, 52, 0.04), -1px 0px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-left: 0;
    margin-right: -3px;
    content: "";
    border-width: 10px 0 10px 10px;
    border-right-color: #ffffff;
}

/* Left Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=left] .arrow::before,
.tooltip.bs-tooltip-left .arrow::before {
    box-shadow: 5px 0px 14px 0px rgba(8, 15, 52, 0.04), 1px 0px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-left: -3px;
    content: "";
    border-width: 10px 10px 10px 0;
    border-left-color: #ffffff;
}

.span-required {
    color: #5063F4;
}



.lv-progressbar--determinate__label {
    display: none !important;
}

.lv-progressbar {
    height: 8px !important;
}

.progress-value {
    color: #000;

    /* Yield Exchange Text Styles/Tooltip Buttons */
    font-family: Montserrat !important;
    font-size: 12px;
    font-style: normal;
    font-weight: 600;
    line-height: 11px;
    /* 91.667% */
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

.signup-tooltip-header {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));

    /* Yield Exchange Text Styles/Buttons Bold */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    /* line-height: 20px; */
    /* 125% */
    /* text-transform: capitalize; */
}

.signup-tooltip-dexc {
    color: var(--Neutral-600, #6F6C90);
    /* Yield Exchange Text Styles/Tooltips */
    font-family: Montserrat !important;
    font-size: 11px;
    font-style: normal;
    font-weight: 400;
    line-height: 14px;
    text-align: start;

    /* 127.273% */
}

.signup-tooltip-wrapper {
    display: flex;
    flex-direction: column;
    /* gap: 10px; */
    justify-content: flex-start;
    align-items: flex-start;
    /* padding: 10px; */
}

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>

<style scoped>
.title-bar {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
    width: 100%;
    text-align: center;
}

.progress-text {
    color: #000;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    /* line-height: normal; */
}

.progressbar {
    /* height: 10px; */
    border-radius: 100px;
}
</style>