<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-end" v-if="getCurrentStep == 'individualandentitysummary'">
            <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
                <p class="title">Include all directors, signing officers, and beneficial owners</p>
                <div class="d-flex justify-ontent-center w-100">
                    <img src="/assets/signup/keyindividuals.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                        srcset="">
                </div>
                <div class="row w-100">
                    <p class="section-title-key-ind-details mb-20">Key Individuals/Entities </p>
                    <span class="entity_question mbi-20"> Please add any individuals or entities that are Directors,
                        Signing
                        Offers, and Beneficial Owners</span>
                </div>
                <!-- individual details -->
                <div class="row w-100">
                    <div class="col-md-12 mbi-20">

                        <div class="new-individual-button" @click="addIndividual">
                            + Add New Individual
                        </div>

                    </div>
                    <div class="col-md-12 d-flex justify mb-20" v-for="(individual, index) in getkeyIndividuals "
                        :key="index">
                        <div class=" user-added w-100 d-flex justify-content-between align-items-center">
                            <div class="user-added-flex ml-3">
                                <p class="m-0 name">{{ individual.first_name + " " + individual.last_name }}</p>
                                <div v-if="individual.is_director"
                                    class="d-flex justify-content-start align-items-center gap-2">
                                    <img src="/assets/signup/checked.png" alt="">
                                    <p class="p-0 m-0"> Director</p>
                                </div>

                                <div v-if="individual.is_signingofficer"
                                    class="d-flex justify-content-start align-items-center gap-2">
                                    <img src="/assets/signup/checked.png" alt="">
                                    <p class="p-0 m-0"> Signing Officer</p>
                                </div>
                                <div v-if="individual.is_poliliticallyexposed"
                                    class="d-flex justify-content-start align-items-center gap-2">
                                    <img src="/assets/signup/checked.png" alt="">
                                    <p class="p-0 m-0"> PEP</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <div @click="deleteFunction(index, 'individual')" v-if="individual.user_id == null"
                                    style="cursor: pointer;"
                                    class="d-flex justify-content-start align-items-center gap-2 mr-4">
                                    <img src="/assets/signup/delete.svg" alt="">
                                </div>
                                <div v-if="individual.user_id != null" style="cursor: pointer;"
                                    class="d-flex justify-content-start align-items-center gap-2 mr-4">
                                    <p class="p-0 m-0">Admin</p>
                                </div>
                                <div @click="EditUser(index)" style="cursor: pointer;"
                                    class="d-flex justify-content-start align-items-center gap-2 mr-4"> Edit
                                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="12" viewBox="0 0 6 12"
                                        fill="none">
                                        <path d="M6 6L1.04907e-06 12L0 5.24537e-07L6 6Z" fill="#5063F4" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- entity details -->
                <div class="row w-100">
                    <div class="col-md-12 mbi-20">

                        <div class="new-individual-button" @click="addEntity">
                            + Add New Entities
                        </div>

                    </div>
                    <div class="col-md-12 d-flex justify mb-20" v-for="(entity, index) in  getOrganizationEntities">
                        <div class=" user-added w-100 d-flex justify-content-between align-items-center">
                            <div class="user-added-flex ml-3">
                                <p class="m-0 name">{{ entity.organizationname }}</p>
                                <!-- <div class="d-flex justify-content-start align-items-center gap-2">
                                    <img src="assets/signup/checked.png" alt="">
                                    <p class="p-0 m-0"> Director</p>
                                </div> -->

                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <img src="/assets/signup/checked.png" alt="">
                                    <p class="p-0 m-0"> {{ getOrgaType(entity.incorptype) }} </p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <div @click="deleteFunction(index, 'entity')" style="cursor: pointer;"
                                    class="d-flex justify-content-start align-items-center gap-2 mr-4">
                                    <img src="/assets/signup/delete.svg" alt="">
                                </div>
                                <div @click="EditEntity(index)" style="cursor: pointer;"
                                    class="d-flex justify-content-start align-items-center gap-2 mr-4"> Edit
                                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="12" viewBox="0 0 6 12"
                                        fill="none">
                                        <path d="M6 6L1.04907e-06 12L0 5.24537e-07L6 6Z" fill="#5063F4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ActionMessageModal @close="success = false" :show="success" width="600" title="Are you finshed?"
                icon="signup/danger.svg" primarybuttontext="Yes" outlinedbuttontext="No"
                message="Have you included all directors, signing officers, and beneficial owners?"
                @outlinedClicked="success = false" @primaryClicked="goNext">
            </ActionMessageModal>

            <ActionMessageModal @close="deleteAction = false" :show="deleteAction" width="600"
                :title="'Remove this ' + deletedsection + '!'" icon="signup/danger.svg" primarybuttontext="Yes"
                outlinedbuttontext="No" message="Please note this action is irrevasible! Do you want to proceed? "
                @outlinedClicked="deleteAction = false" @primaryClicked="beforeDeleteCheck(true)">
            </ActionMessageModal>



            <ActionMessageModal @close="ownershipError = false" :show="ownershipError" width="600"
                title="Ownership Error" icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext=""
                :message="failmessage" outlined="">
            </ActionMessageModal>

            <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Ooops! An issue occured"
                icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext="" :message="failmessage" outlined="">
            </ActionMessageModal>

            <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
            <div class="w-100 d-flex justify-content-end mt-3 gap-2">
                <CustomSubmit @action="goNext" :outline="true" title="Skip" />
                <CustomSubmit :isLoading="sending" @action="doSubmit" :outline="false" title="Next" />
            </div>
        </div>
        <KeyIndividuals :isedit="edituser" :userposition="user_pos" @stopedit="edituser = false"
            v-if="getCurrentStep == 'keyIndividuals'" />
        <EntityDetails :isedit="editentity" :entityposition="entity_pos" @stopedit="editentity = false"
            v-if="getCurrentStep == 'entitydetails'" />
    </div>

</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import Checkbox from '../shared/Checkbox.vue';
import DoubleCheckbox from '../shared/DoubleCheckbox.vue';
import ActionMessageModal from '../shared/ActionMessageModal.vue';
import KeyIndividuals from './KeyIndividuals.vue';
import EntityDetails from './EntityDetails.vue';

export default {
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput, Checkbox, DoubleCheckbox, ActionMessageModal, KeyIndividuals, EntityDetails
    },
    mounted() {
        this.$store.dispatch('setStageTitle', 'Complete your Yield Exchange Profile!')
        // this.$store.dispatch('setProgress', 50)
    },
    beforeUpdate() {

    },
    data() {
        return {
            firstName: null,
            lastName: null,
            email: null,
            telephone: null,
            jobTitle: null,
            timeZone: null,
            linkedInUrl: null,
            isDirectorValue: null,
            edituser: false,
            deleteAction: false,
            sending: false,
            delete: false,
            success: false,
            user_pos: null,
            editentity: false,
            deletedsection: null,
            deletedchecker: false,
            entity_pos: null,
            addingentity: false,
            addingindividual: false,
            sending: false,
            fail: false,
            individual_addition_error: false,
            entity_addition_error: false,
            sending: false,
            donewithentity: false,
            donewithindividual: false,
            globalid: null,
            ownershipError: false,
            failmessage: 'We are unable to update your data, please try again or contact us at info@yieldexchange.ca',
        }
    },
    computed: {
        getCurrentStep() {
            return this.$store.getters.getCurrentStep;
        },
        getkeyIndividuals() {
            return this.$store.getters.getkeyIndividuals
        },
        getOrganizationEntities() {
            return this.$store.getters.getOrganizationEntities
        },
        getOrgDetails() {
            return this.$store.getters.getOrgDetails
        },
        getOrganizationTypes() {
            return this.$store.getters.getOrganizationTypes
        },
    },
    methods: {
        getOrgaType(value) {
            let myvar = this.getOrganizationTypes.filter(element => {
                return element.id == value
            })
            return myvar[0].name
        },
        canSubmit() {
            if (this.firstName != null
                && this.lastName != null
                && this.email != null
                && this.telephone != null
                && this.jobTitle != null
                && this.timeZone != null) {
                return true;
            }
            else {
                return false
            }
        },
        directorValueChange(value) {
            this.isDirectorValue = value
        },
        EditEntity(value) {
            this.editentity = true
            this.entity_pos = value
            this.$store.dispatch('setCurrentStep', 'entitydetails')
        },
        goNext() {
            this.$store.dispatch('setCurrentStep', 'documentsUpload')
        },
        addEntity() {
            this.$store.dispatch('setCurrentStep', 'entitydetails')
        },
        newEntity(entity) {
            let newentity = {};
            newentity.organizationname = entity.organization_name
            newentity.incorptype = entity.organization_type
            newentity.provinceofincorp = entity.incorporation_province
            newentity.is_owenershipabovetwentyfive = entity.owns_over_twenty_five
            newentity.is_actingforindividual = entity.orperating_for_entity
            newentity.nature_of_relationship = entity.relationship_with_entity
            newentity.business_number = entity.inc_business_number
            newentity.cra_number = entity.cra_business_number
            newentity.percentage_ownwership = entity.percentage_ownership
            newentity.id = entity.id
            return newentity;

        },
        newIndividual(individual) {
            let newindividual = {};
            newindividual.id = individual.id
            newindividual.user_id = individual.user_id
            newindividual.first_name = individual.first_name
            newindividual.last_name = individual.last_name
            newindividual.job_title = individual.job_title
            newindividual.is_director = individual.is_director
            newindividual.is_owenershipabovetwentyfive = individual.owns_over_twenty_five
            newindividual.percentage_ownwership = individual.percentage_ownership
            newindividual.is_signingofficer = individual.is_signingofficer
            newindividual.is_poliliticallyexposed = individual.is_politicallyexposed
            newindividual.is_actingonpowerofattorney = individual.is_actingonattorneypower
            newindividual.is_actingforindividual = individual.orperating_for_entity
            newindividual.is_actingforcorporation = individual.operating_for_corporation
            newindividual.nature_of_relationship = individual.relationship_with_corporation
            return newindividual;

        },
        addEntityToDb() {
            let entities = []
            this.addingentity = true
            axios.post('add-an-entity', { 'entities': this.getOrganizationEntities, 'organization_id': this.getOrgDetails.organization_id }).then(response => {
                this.donewithentity = true
                if (response.data.success) {
                    response.data.data.forEach(element => {
                        let tempentity = this.newEntity(element)
                        entities.push(tempentity)
                    });
                    this.$store.dispatch('setOrganizationEntities', entities)
                    this.addingentity = false
                    this.entity_addition_error = false
                }

            }).catch(err => {
                this.donewithentity = true
                this.addingentity = false
                this.entity_addition_error = true
            })
        },
        addKeyIndividualToDb() {
            let individuals = []
            this.addingindividual = true
            axios.post('add-an-individual', { 'individuals': this.getkeyIndividuals, 'organization_id': this.getOrgDetails.organization_id }).then(response => {
                this.donewithindividual = true
                if (response.data.success) {
                    response.data.data.forEach(element => {
                        let tempindividual = this.newIndividual(element)
                        individuals.push(tempindividual)
                    });
                    this.$store.dispatch('setkeyIndividuals', individuals)
                    this.individual_addition_error = false
                    this.addingindividual = false
                }

            }).catch(err => {
                this.donewithindividual = true
                this.addingindividual = false
                this.individual_addition_error = true

            })
        },
        doSubmit() {
            this.failmessage = 'We are unable to update your data, please try again or contact us at info@yieldexchange.ca'
            if (this.getOrganizationEntities.length > 0) {
                this.sending = true
                this.addEntityToDb()
            } else {
                this.donewithentity = true

            }
            if (this.getkeyIndividuals.length > 0) {
                let percentage = 0
                this.getkeyIndividuals.forEach(element => {
                    if (element.is_owenershipabovetwentyfive) {
                        percentage += parseInt(element.percentage_ownwership)
                        // console.log(element.percentage_ownwership)
                    }
                })
                if (percentage <= 100) {
                    this.sending = true
                    this.addKeyIndividualToDb()
                } else {
                    this.failmessage = `"Please provide valid percentage ownership of the organization.It should add up to 100%"`
                    this.ownershipError = true
                    this.donewithindividual = true

                }
            } else {
                this.donewithindividual = true
            }
            if (this.getOrganizationEntities.length == 0 && this.getkeyIndividuals.length == 0) {
                this.success = true
            }

        },
        addIndividual() {
            this.$store.dispatch('setCurrentStep', 'keyIndividuals')
        },
        EditUser(value) {
            this.edituser = true
            this.user_pos = value
            this.$store.dispatch('setCurrentStep', 'keyIndividuals')
        },
        beforeDeleteCheck(value) {
            this.deleteAction = false
            if (value) {
                if (this.deletedsection == "entity") {
                    this.deleteEntity(this.globalid)
                } else if (this.deletedsection == "individual") {
                    this.deleteUser(this.globalid)
                }
            }
        },
        deleteFunction(index, section) {
            this.globalid = index
            if (section == "entity") {
                this.deleteAction = true
                this.deletedsection = "entity"
            } else {
                this.deleteAction = true
                this.deletedsection = "individual"

            }
        },
        deleteUser(index) {
            let individuals = []
            const usertoremove = this.getkeyIndividuals[index]

            let keyIndividuals = this.getkeyIndividuals
            if (usertoremove.id == null && usertoremove.user_id == null) {
                keyIndividuals = keyIndividuals.filter((element, ci) => {
                    return ci != index
                })
                this.$store.dispatch('setkeyIndividuals', keyIndividuals)
            } else if (usertoremove.id != null && usertoremove.user_id == null) {
                axios.post('remove-an-individual', { 'id': usertoremove.id, 'organization_id': this.getOrgDetails.organization_id }).then(response => {
                    if (response.data.success) {
                        response.data.data.forEach(element => {
                            let tempindividual = this.newIndividual(element)
                            individuals.push(tempindividual)
                        });
                        this.$store.dispatch('setkeyIndividuals', individuals)
                    }

                }).catch(err => {
                })
            } else {
            }
        },
        deleteEntity(index) {

            let entities = []
            const entitytoremove = this.getOrganizationEntities[index]

            let myentities = this.getOrganizationEntities
            if (entitytoremove.id == null) {
                myentities = myentities.filter((element, ci) => {
                    return ci != index
                })
                this.$store.dispatch('setOrganizationEntities', myentities)
            } else {
                axios.post('remove-an-entity', { 'id': entitytoremove.id, 'organization_id': this.getOrgDetails.organization_id }).then(response => {
                    if (response.data.success) {
                        response.data.data.forEach(element => {
                            let tempentity = this.newEntity(element)
                            entities.push(tempentity)
                        });
                        this.$store.dispatch('setOrganizationEntities', entities)
                    }

                }).catch(err => {
                })
            }
        },
        updateSendingState() {
            this.sending = this.addingindividual || this.addingentity;
        },
        updateSuccessOrFail() {
            if (!this.sending && !this.individual_addition_error && !this.entity_addition_error && this.donewithentity && this.donewithindividual) {
                this.success = !(this.individual_addition_error && this.donewithindividual) && !(this.entity_addition_error && this.donewithentity);
                this.fail = !this.success;
            }
        }

    },
    watch: {
        addingindividual() {
            this.updateSendingState();
        },
        addingentity() {
            this.updateSendingState();
        },
        sending() {
            this.updateSuccessOrFail();
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
    font-weight: 400;
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
    font-weight: 400;
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