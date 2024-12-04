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
                <p class="section-title-key-ind-details mb-20"> Entity Details </p>
                <div class="row w-100 m-0 p-0 mb-20">
                    <div class="col-md-4">
                        <CustomTextInput :currentValue="organizationName"
                            @hasError="(checkerVariable) => organizationNameError = checkerVariable"
                            v-model="organizationName" :required="true" label="Organization Name"
                            placeholder="Enter your organization name" input_type="text" :isemptycheck="requiredChecker"
                            :exceptValue="exceptValue" />

                    </div>
                    <div class="col-md-4">
                        <CustomSelectInput label="Organization Type" :options="getOrganizationTypes" :required="true"
                            placeholder="Select Organization Type" :isemptycheck="requiredChecker"
                            :currentValue="incorporationType" v-model="incorporationType" input_type="text" />
                    </div>
                    <div class="col-md-4">
                        <CustomSelectInput :options="getProvinces" :required="true"
                            placeholder="Select province of incorporation" label="Province of Incorporation"
                            :isemptycheck="requiredChecker" :currentValue="provinceOfIncorporation"
                            v-model="provinceOfIncorporation">
                        </CustomSelectInput>
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
                <div v-if="!isOwnershipOverTwentyFive && isOwnershipOverTwentyFive != null"
                    class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center gap-3">
                    <span class="entity_question"> Is this entity operating this account on behalf of another individual
                        or entity
                    </span>
                    <div class="d-flex gap-2">
                        <DoubleCheckbox :currentValue="isIndividualActingforAnotherIndividual" @change="updateIndivdual"
                            checkbox1label="Yes" checkbox2label="No" />
                    </div>
                </div>

                <div class="row w-100 m-0 p-0 mb-20">
                    <div class="col-md-4 mb-20">
                        <CustomTextInput :currentValue="incorporationNumber"
                            @hasError="(checkerVariable) => incorporationNumberError = checkerVariable"
                            v-model="incorporationNumber" :minlength="5" :required="false" label="Incorporation Number"
                            placeholder="Enter Incorporation Number " input_type="text" />
                    </div>
                    <div class="col-md-4 mb-20">
                        <CustomTextInput @hasError="(checkerVariable) => craNumberError = checkerVariable"
                            label="CRA Business Number" :required="false" placeholder="Enter Business Number"
                            :isemptycheck="requiredChecker" :minlength="3" :maxlength="20" :currentValue="craNumber"
                            v-model="craNumber" input_type="text" />
                    </div>
                    <div class="col-md-4 mb-20">
                        <CustomTextInput ref="percent"
                            @hasError="(checkerVariable) => ownershipPercentageError = checkerVariable"
                            label="Percentage Ownership" :required="isOwnershipOverTwentyFive"
                            placeholder="Enter Percentage Ownership" :isemptycheck="requiredChecker" :minlength="2"
                            :maxlength="3" :disabled="!isOwnershipOverTwentyFive || isOwnershipOverTwentyFive == null"
                            :currentValue="ownershipPercentage" v-model="ownershipPercentage" input_type="percent" />
                    </div>
                </div>
                <div v-if="isIndividualActingforAnotherIndividual && !isOwnershipOverTwentyFive && isOwnershipOverTwentyFive != null"
                    class="col-md-12 m-0 mbi-20 d-flex justify-content-between align-items-center w-100 ">
                    <div class="row d-flex align-items-center w-100 gx-0">
                        <div class="col-md-6">
                            <span class="entity_question"> What is the nature of relationship with the third party ?
                                <span class="span-required"> * </span> </span>

                        </div>
                        <div class="col-md-6">
                            <CustomTextInput :currentValue="natureOfRelationship" :isemptycheck="requiredChecker"
                                @hasError="(checkerVariable) => natureOfRelationshipError = checkerVariable"
                                v-model="natureOfRelationship" :minlength="3" :required="true" :dontshowrequired="true"
                                placeholder="Enter the nature of relationship" input_type="text" />
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <CustomSubmit @action="goNext" :outline="true" title="Skip" />
            <CustomSubmit @action="submitEntity" :outline="false" title="Next" />
        </div>
        <ActionMessageModal @close="submiterror = false" :show="submiterror" width="500" title="Entity Details Error!"
            icon="signup/danger.svg" primarybuttontext="Ok" outlinedbuttontext="" :message="failmessage"
            @outlinedClicked="" @primaryClicked="submiterror = false">
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
        this.$store.dispatch('setProgress', 50)
        if (this.isedit) {
            this.setDefaultsValue()
        }
        this.setExcepts()

    },
    props: ['isedit', 'entityposition'],
    data() {
        return {
            organizationName: null,
            incorporationType: null,
            provinceOfIncorporation: null,
            isOwnershipOverTwentyFive: null,
            incorporationNumber: null,
            craNumber: null,
            ownershipPercentage: null,
            isIndividualActingforAnotherIndividual: null,
            natureOfRelationship: null,
            incorporationNumberError: false,
            craNumberError: false,
            organizationNameError: false,
            incorporationTypeError: false,
            provinceOfIncorporationError: false,
            natureOfRelationshipError: false,
            requiredChecker: false,
            entity_id: null,
            submiterror: false,
            failmessage: null,
            exceptValue: [],
            entitydetails: {
                'organizationname': null,
                'incorptype': null,
                'provinceofincorp': null,
                'is_owenershipabovetwentyfive': null,
                'is_actingforindividual': null,
                'nature_of_relationship': null,
                'business_number': null,
                'cra_number': null,
                'percentage_ownwership': null,
                'id': null
            },
            collectedEntity: [],
        }
    },
    computed: {
        getOrgDetails() {
            return this.$store.getters.getOrgDetails;
        },
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getIndustries() {
            return this.$store.getters.getIndustries
        },
        getProvinces() {
            return this.$store.getters.getProvinces
        },
        getOrganizationTypes() {
            return this.$store.getters.getOrganizationTypes
        },
        getOrganizationEntities() {
            return this.$store.getters.getOrganizationEntities
        },
    },
    methods: {
        updatePercentage(value) {
            if (value) {
                this.isOwnershipOverTwentyFive = value
                this.natureOfRelationshipError = false
            } else {
                this.ownershipPercentage = null
                this.ownershipPercentageError = false
                this.isOwnershipOverTwentyFive = value
                this.$refs.percent.error = false
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
        canSubmit() {
            let checkervar = false
            if (
                this.organizationName != null &&
                this.incorporationType != null &&
                this.provinceOfIncorporation != null &&
                !this.incorporationNumberError &&
                !this.organizationNameError &&
                !this.craNumberError &&
                !this.natureOfRelationshipError 
                // !this.ownershipPercentageError

            ) {

                checkervar = !(this.isOwnershipOverTwentyFive == null ||
                    (!this.isOwnershipOverTwentyFive &&
                        (this.isIndividualActingforAnotherIndividual == null)));



                if (!checkervar) {
                    this.submiterror = true
                    this.failmessage = "Please ensure all inputs are checked accurately."
                    return checkervar
                }


                if (!this.isOwnershipOverTwentyFive) {
                    return !(!this.isOwnershipOverTwentyFive && this.isIndividualActingforAnotherIndividual && !this.natureOfRelationshipError && this.natureOfRelationship == null);
                } else {
                    return !(this.isOwnershipOverTwentyFive && this.ownershipPercentage < 25 && this.ownershipPercentageError);
                }
            }
            else {
                return false
            }
        },
        setExcepts() {
            if (this.isedit) {
                this.getOrganizationEntities.forEach((element, index) => {
                    if (index != this.entityposition)
                        this.exceptValue.push(element.organizationname)
                })
            } else {
                this.getOrganizationEntities.forEach(element => {
                    this.exceptValue.push(element.organizationname)
                })
            }
        },
        addEntity() {
            this.entitydetails.organizationname = this.organizationName
            this.entitydetails.incorptype = this.incorporationType
            this.entitydetails.provinceofincorp = this.provinceOfIncorporation
            this.entitydetails.is_owenershipabovetwentyfive = this.isOwnershipOverTwentyFive
            this.entitydetails.is_actingforindividual = this.isIndividualActingforAnotherIndividual
            this.entitydetails.nature_of_relationship = this.natureOfRelationship
            this.entitydetails.business_number = this.incorporationNumber
            this.entitydetails.cra_number = this.craNumber
            this.entitydetails.percentage_ownwership = this.isOwnershipOverTwentyFive ? this.ownershipPercentage : null;
            this.entitydetails.id = this.entity_id;
        },
        setDefaultsValue() {
            let currentEntity = this.getOrganizationEntities[this.entityposition];
            this.organizationName = currentEntity.organizationname
            this.incorporationType = currentEntity.incorptype
            this.incorporationNumber = currentEntity.business_number
            this.provinceOfIncorporation = currentEntity.provinceofincorp
            this.isOwnershipOverTwentyFive = currentEntity.is_owenershipabovetwentyfive
            this.isIndividualActingforAnotherIndividual = currentEntity.is_actingforindividual
            this.natureOfRelationship = currentEntity.nature_of_relationship
            this.craNumber = currentEntity.cra_number
            this.ownershipPercentage = currentEntity.percentage_ownwership
            this.entity_id = currentEntity.id

        },
        directorValueChange(value) {
            this.isDirectorValue = value

        },
        goNext() {
            this.$emit('stopedit', false)
            this.$store.dispatch('setCurrentStep', 'individualandentitysummary')
        },
        submitEntity() {
            if (this.canSubmit()) {
                this.addEntity()
                this.collectedEntity.push(this.entitydetails)
                this.setStoreEntity()
                this.goNext()
            } else {
                this.requiredChecker = true
            }
        },
        setStoreEntity() {
            if (this.isedit) {
                let entities = this.getOrganizationEntities;
                entities[this.entityposition] = this.entitydetails
                this.$store.dispatch('setOrganizationEntities', entities)

            } else {
                if (this.getOrganizationEntities.length > 0) {
                    let entities = this.getOrganizationEntities;
                    entities.push(this.entitydetails)
                    console.log(entities)
                    this.$store.dispatch('setOrganizationEntities', entities)
                } else {
                    this.$store.dispatch('setOrganizationEntities', this.collectedEntity)
                }
            }
        },

    },
    watch: {
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