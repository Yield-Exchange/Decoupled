<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <p class="title">Fill in once for all your investments</p>
            <p class="kyc-sub-title p-0 m-0">Finish your profile in 10 minutes and make investing seamless. Your
                progress is saved if you need to come back later!</p>
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/signup/keyindividuals.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>


            <div class="row w-100">
                <p class="section-title-key-ind-details mb-20"> Individual Details
                    <a data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='signup-tooltip-wrapper'><p class='signup-tooltip-header p-0 m-0'>Why are we asking for this?<p>
                                <p class='signup-tooltip-dexc p-0 m-0'>Financial institutions need to know about the key individuals and entities before they can open an account for you</p>
                            </div>">
                        <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
                    </a>
                </p>
                <div class="row w-100 m-0 p-0 mb-20">
                    <div class="col-md-4">

                        <CustomTextInput :disabled="disabled" :currentValue="lastName" :isemptycheck="requiredChecker"
                            @hasError="(checkerVariable) => lastNameError = checkerVariable" v-model="lastName"
                            :required="true" label="Last Name" placeholder="Enter your last  name" input_type="text" />
                    </div>
                    <div class="col-md-4">
                        <CustomTextInput :disabled="disabled" :currentValue="firstName" :isemptycheck="requiredChecker"
                            @hasError="(checkerVariable) => firstNameError = checkerVariable" v-model="firstName"
                            :required="true" label="First Name" placeholder="Enter your fisrt name" input_type="text" />
                    </div>
                    <div class="col-md-4">
                        <CustomTextInput :disabled="disabled" :currentValue="jobTitle" :isemptycheck="requiredChecker"
                            @hasError="(checkerVariable) => jobTitleError = checkerVariable" v-model="jobTitle"
                            :required="false" label="Job Title" placeholder="Enter Job Title" input_type="text" />
                    </div>
                </div>

                <!-- is director question -->
                <div class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is this individual a director ?
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isDirectorValue" @change="directorValueChange"
                            checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>

                <!-- 25 % question  -->
                <div class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Does this person/entities own <b>25% or more </b> of the
                        organization
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isOwnershipOverTwentyFive" @change="updatePercentage"
                            checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>
                <!-- part 2 -->
                <div v-if="isOwnershipOverTwentyFive"
                    class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Percentage Ownership <span class="span-required">*</span> <a
                            data-toggle="tooltip" data-placement="right" data-html="true" :title="`
                            <div class='signup-tooltip-wrapper'>
                                <p class='signup-tooltip-header p-0 m-0'>Ownership Percentage?<p>
                                <p class='signup-tooltip-dexc p-0 m-0'>${tooltiptitle}</p>
                            </div>`">
                            <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
                        </a>
                    </span>
                    <div class="d-flex gap-2" style="width: 122px;">
                        <CustomTextInput class="m-0" v-model="ownershipPercentage" :currentValue="ownershipPercentage"
                            :ownership="totalpercent" :isemptycheck="requiredChecker"
                            @hasError="(checkerVariable) => ownershipPercentageError = checkerVariable" :minlength="2"
                            :dontshowrequired="true" :required="true" placeholder="Percentage Ownership"
                            input_type="percent" />
                    </div>
                </div>
                <!-- Signing Officer -->
                <div class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is this individual a signing officer?
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isSigningOfficer" @change="(value) => isSigningOfficer = value"
                            checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>
                <!-- Politically Exposed Individual -->
                <div class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is this individual a politically exposed person?
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isPoliticalyExposed"
                            @change="(value) => isPoliticalyExposed = value" checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>
                <!-- action on behalf of entity -->
                <div class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is this individual operating this account on behalf of another
                        individual or entity?
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isIndividualActingforAnotherIndividual" @change="updateIndivdual"
                            checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>
                <!-- part 2 -->
                <div v-if="isIndividualActingforAnotherIndividual"
                    class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is the individual acting on their own behalf or through power of
                        attorney?
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isActingOnThePowerOfAttorney"
                            @change="(value) => isActingOnThePowerOfAttorney = value" checkbox1label="Yes"
                            checkbox2label="No" />
                    </div>
                </div>
                <div v-if="isIndividualActingforAnotherIndividual"
                    class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is this individual an agent acting on behalf of corporation or
                        entity?
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isIndividualActingforAnotherCorporation"
                            @change="updateCorpration" checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>
                <div v-if="isIndividualActingforAnotherCorporation && isIndividualActingforAnotherIndividual"
                    class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> What is the nature of relationship with the third party ? <span
                            class="span-required"> * </span>
                    </span>
                    <div class="d-flex gap-2" style="width: 320px;">
                        <CustomTextInput :currentValue="natureOfRelationship" :isemptycheck="requiredChecker"
                            @hasError="(checkerVariable) => natureOfRelationshipError = checkerVariable"
                            :dontshowrequired="true" v-model="natureOfRelationship" :minlength="1" :required="true"
                            placeholder="Enter nature of relationship" input_type="text" />
                    </div>
                </div>
            </div>
        </div>

        <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <CustomSubmit @action="goNext" :outline="true" title="Skip" />
            <CustomSubmit @action="submitIndividual" :outline="false" title="Next" />
        </div>
        <ActionMessageModal @close="submiterror = false" :show="submiterror" width="500"
            title="Individual Details Error!" icon="signup/danger.svg" primarybuttontext="Ok" outlinedbuttontext=""
            :message="failmessage" @outlinedClicked="" @primaryClicked="submiterror = false">
        </ActionMessageModal>
    </div>
</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import Checkbox from '../shared/Checkbox.vue';
import DoubleCheckbox from '../shared/DoubleCheckbox.vue';
import ActionMessageModal from '../shared/ActionMessageModal.vue';


export default {
    components: {
        ActionMessageModal, CustomSubmit, CustomTextInput, CustomSelectInput, Checkbox, DoubleCheckbox
    },
    mounted() {
        this.$store.dispatch('setStageTitle', 'Complete your Yield Exchange Profile!')
        // this.$store.dispatch('setProgress', 50)
        if (this.isedit) {
            this.setDefaultsValue()
        } else if (this.getkeyIndividuals.length == 0) {
            this.firstName = this.getLoggedInUser.first_name
            this.lastName = this.getLoggedInUser.last_name
            this.user_id = this.getLoggedInUser.user_id
            this.disabled = true
            this.jobTitle = this.getUpdatedUserInfo.job_title
        }
        this.countPercentage()
    },
    props: ['isedit', 'userposition'],
    data() {
        return {
            firstName: null,
            lastName: null,
            jobTitle: null,
            disabled: false,
            isDirectorValue: null,
            isOwnershipOverTwentyFive: null,
            isSigningOfficer: null,
            ownershipPercentage: null,
            isPoliticalyExposed: null,
            isIndividualActingforAnotherIndividual: null,
            isIndividualActingforAnotherCorporation: null,
            natureOfRelationship: null,
            requiredChecker: false,
            showIfDirectorIsNoOrNull: true,
            isActingOnThePowerOfAttorney: null,
            totalpercent: 100,
            submiterror: false,
            failmessage: null,
            tooltiptitle: null,
            // errors
            firstNameError: false,
            lastNameError: false,
            ownershipPercentageError: false,
            natureOfRelationshipError: false,
            ownershipPercentageError: false,
            jobTitleError: false,
            natureOfRelationshipError: false,
            individual_id: null,
            user_id: null,
            individualdetails: {
                'first_name': null,
                'last_name': null,
                'job_title': null,
                'is_director': null,
                'is_owenershipabovetwentyfive': null,
                'is_signingofficer': null,
                'is_poliliticallyexposed': null,
                'is_actingforindividual': null,
                'is_actingforcorporation': null,
                'is_actingonpowerofattorney': null,
                'percentage_ownwership': null,
                'nature_of_relationship': null,
                'id': null,
                'user_id': null,
            },
            keyIndividuals: []


        }
    },
    computed: {
        getkeyIndividuals() {
            return this.$store.getters.getkeyIndividuals
        },
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getUpdatedUserInfo() {
            return this.$store.getters.getUpdatedUserInfo
        },
    },
    methods: {
        updatePercentage(value) {
            if (value) {
                this.isOwnershipOverTwentyFive = value
            } else {
                this.ownershipPercentageError = false
                this.isOwnershipOverTwentyFive = value
            }
        },
        updateCorpration(value) {
            if (value) {
                this.isIndividualActingforAnotherCorporation = value
            } else {
                this.natureOfRelationshipError = false
                this.isIndividualActingforAnotherCorporation = value
            }
        },
        updateIndivdual(value) {
            if (value) {
                this.isIndividualActingforAnotherIndividual = value
            } else {
                this.natureOfRelationshipError = false
                this.isIndividualActingforAnotherIndividual = value
            }
        },
        countPercentage() {
            let ownershipPercent = 0
            let currntPercent = 0
            if (this.isedit)
                currntPercent = this.getkeyIndividuals[this.userposition].percentage_ownwership
            this.getkeyIndividuals.forEach(element => {
                if (element.is_owenershipabovetwentyfive) {
                    ownershipPercent += parseInt(element.percentage_ownwership)
                }
            })
            if (this.isedit && ownershipPercent > 0 && currntPercent != null) {
                this.totalpercent = (100 - ownershipPercent) + parseInt(currntPercent)
            } else if (ownershipPercent > 0 && (!this.isedit || this.isedit != null)) {
                this.totalpercent = (100 - ownershipPercent)
            }
            if (this.totalpercent < 25)
                this.tooltiptitle = `Only ${this.totalpercent}%  ownership remains, and it doesn't need to be explicitly declared.`
            else
                this.tooltiptitle = `The percentage of ownership should be between 25% and ${this.totalpercent}%. `
        },
        setStoreIndividual() {
            if (this.isedit) {
                let individuals = this.getkeyIndividuals;
                individuals[this.userposition] = this.individualdetails
                this.$store.dispatch('setkeyIndividuals', individuals)

            } else {
                if (this.getkeyIndividuals.length > 0) {
                    let individuals = this.getkeyIndividuals;
                    individuals.push(this.individualdetails)
                    this.$store.dispatch('setkeyIndividuals', individuals)
                } else {
                    this.$store.dispatch('setkeyIndividuals', this.keyIndividuals)
                }
            }
        },
        canSubmit() {
            let checkervar = false
            if (
                this.firstName != null &&
                this.lastName != null &&
                !this.lastNameError &&
                !this.firstNameError &&
                !this.jobTitleError &&
                // !this.ownershipPercentageError &&
                !this.natureOfRelationshipError
            ) {
                checkervar = !(this.isDirectorValue == null ||
                    this.isOwnershipOverTwentyFive == null ||
                    this.isSigningOfficer == null ||
                    this.isPoliticalyExposed == null ||
                    this.isIndividualActingforAnotherIndividual == null ||
                    (this.isIndividualActingforAnotherIndividual &&
                        (this.isActingOnThePowerOfAttorney == null || this.isIndividualActingforAnotherCorporation == null)));



                if (!checkervar) {
                    this.submiterror = true
                    this.failmessage = "Please ensure all inputs are checked accurately."
                    return checkervar
                }


                if (!this.isOwnershipOverTwentyFive) {
                    if (this.isIndividualActingforAnotherCorporation && this.isIndividualActingforAnotherIndividual) {
                        return (!this.natureOfRelationshipError && this.natureOfRelationship != null)
                    } else {
                        return true
                    }
                } else {
                    if (this.ownershipPercentage < 25 || this.ownershipPercentageError || this.ownershipPercentage > this.totalpercent) {
                        this.submiterror = true
                        if (this.totalpercent < 25)
                            this.failmessage = `Only ${this.totalpercent}%  ownership remains, and it doesn't need to be explicitly declared.`
                        else
                            this.failmessage = `The percentage of ownership should be between 25% and ${this.totalpercent}%. `
                        return false;
                    } else {
                        if (this.isIndividualActingforAnotherCorporation && this.isIndividualActingforAnotherIndividual) {
                            return (!this.natureOfRelationshipError && this.natureOfRelationship != null)
                        } else {
                            return true
                        }
                    }
                }
            } else {
                return false;
            }

        },
        addIndividual() {
            this.individualdetails.first_name = this.firstName
            this.individualdetails.job_title = this.jobTitle
            this.individualdetails.last_name = this.lastName
            this.individualdetails.is_director = this.isDirectorValue
            this.individualdetails.id = this.individual_id
            this.individualdetails.user_id = this.user_id
            this.individualdetails.is_owenershipabovetwentyfive = this.isOwnershipOverTwentyFive
            this.individualdetails.percentage_ownwership = this.isOwnershipOverTwentyFive ? this.ownershipPercentage : null;
            this.individualdetails.is_signingofficer = this.isSigningOfficer
            this.individualdetails.is_poliliticallyexposed = this.isPoliticalyExposed
            this.individualdetails.is_actingonpowerofattorney = this.isActingOnThePowerOfAttorney
            this.individualdetails.is_actingforcorporation = this.isIndividualActingforAnotherCorporation
            this.individualdetails.is_actingforindividual = this.isIndividualActingforAnotherIndividual
            this.individualdetails.nature_of_relationship = this.natureOfRelationship
        },
        submitIndividual() {
            if (this.canSubmit()) {
                this.addIndividual()
                this.keyIndividuals.push(this.individualdetails)
                this.setStoreIndividual()
                this.goNext()
            } else {
                this.requiredChecker = true
            }
        },
        directorValueChange(value) {
            this.isDirectorValue = value
        },
        setDefaultsValue() {
            let currentUser = this.getkeyIndividuals[this.userposition];
            this.firstName = currentUser.first_name
            this.jobTitle = currentUser.job_title
            this.lastName = currentUser.last_name
            this.isDirectorValue = currentUser.is_director
            this.isOwnershipOverTwentyFive = currentUser.is_owenershipabovetwentyfive
            this.ownershipPercentage = currentUser.percentage_ownwership
            this.isSigningOfficer = currentUser.is_signingofficer
            this.isPoliticalyExposed = currentUser.is_poliliticallyexposed
            this.isActingOnThePowerOfAttorney = currentUser.is_actingonpowerofattorney
            this.isIndividualActingforAnotherCorporation = currentUser.is_actingforcorporation
            this.isIndividualActingforAnotherIndividual = currentUser.is_actingforindividual
            this.natureOfRelationship = currentUser.nature_of_relationship
            this.individual_id = currentUser.id
            this.user_id = currentUser.user_id
            if (currentUser.user_id != null) {
                this.disabled = true
            }
        },
        goNext() {
            this.$emit('stopedit', false)
            this.$store.dispatch('setCurrentStep', 'individualandentitysummary')
        }

    },
    watch: {
        getLoggedInUser() {
            if (this.getkeyIndividuals.length == 0) {
                this.disabled = true
                this.firstName = this.getLoggedInUser.first_name
                this.lastName = this.getLoggedInUser.last_name
                this.user_id = this.getLoggedInUser.user_id
                this.jobTitle = this.getUpdatedUserInfo.job_title

            }
        }
    }
}
</script>

<style scoped>
.top-title {
    margin-bottom: 30px;
}

.kyc-sub-title {
    color: #252525;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.entity_question {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 16px;
    /* 100% */
}

.entity_question b {
    font-weight: 700;
}

.sectionHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 18px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 144.444% */
    /* text-transform: capitalize; */
}

.new-individual-button {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 133.333% */
    /* text-transform: capitalize; */
    cursor: pointer;
}

.section-title-key-ind-details {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    /* padding: 0;
    margin: 0 !important; */
}

.user-added {
    background: var(--Yield-Exchange-Colors-Yield-Exchange-Light-Purple, #EFF2FE);
    box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
}

.user-added-flex {
    display: flex;
    height: 50px;
    padding: 5px 20px;
    align-items: center;
    gap: 10px;
    align-self: stretch;

}

.user-added-flex .name {
    color: #000;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.thirdpartytext {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
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

    font-family: Montserrat !important;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

.title {
    color: #5063F4;
    font-family: Montserrat;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    text-align: center;
    width: 100%;
    margin: 0 auto;

}
</style>