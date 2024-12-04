<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <p class="title">Welcome! Please tell us about your organization</p>
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/signup/organizationdetails.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>
            <div class="row w-100">
                <div class="col-md-12 mt-3">
                    <p class="section-header">
                        Organization Information
                        <a data-toggle="tooltip" data-placement="right" data-html="true" title="<div class='signup-tooltip-wrapper'><p class='signup-tooltip-header p-0 m-0'>Why are we asking for this?<p>
                                <p class='signup-tooltip-dexc p-0 m-0'>Filling in all optional fields will help financial institutions speed up the process of buying a GIC when you are ready.</p>
                            </div>">
                            <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
                        </a>
                    </p>
                </div>
                <div class="col-md-12 mb-20">
                    <div class="row align-items-center">
                        <div class="col-md-2 deposi-image-upload">
                            <img @click="uploadimage = true" v-if="uploaded_image == null" ref="image"
                                src="assets/signup/upload-image.svg" alt="">
                            <img @click="uploadimage = true" v-else :src="uploaded_image" alt="">
                            <div class="deposi-image-upload-action">
                                <img v-if="uploaded_image == null" @click="uploadimage = true"
                                    src="assets/signup/edit.svg" alt="">
                                <img v-else @click="removeImage" src="assets/signup/remove.svg" alt="">
                            </div>
                        </div>
                        <div class="col-md-10 d-flex flex-column " style="cursor:pointer" @click="uploadimage = true">
                            <p class="logo_upload_title">Upload Your Logo</p>
                            <p class="logo_upload_desc">logo Recommended Dimensions: 400px x 400px. file formats: jpg or
                                Png
                            </p>
                        </div>

                    </div>

                </div>
                <template v-if="getUserType && getUserType == 'bank'">
                    <div class="col-md-4 mb-20">
                        <CustomSelectInput label="Organization" :options="financialInstitutions" :required="true"
                            placeholder="Select Organization" :isemptycheck="requiredChecker"
                            :currentValue="getOrgDetails != null ? getOrgDetails.organizationID : null"
                            v-model="organizationID" input_type="text" />
                    </div>
                    <div class="col-md-4 mb-20">
                        <CustomSelectInput label="Organization Type" :options="institution_types" :required="true"
                            placeholder="Select Organization Type" :isemptycheck="requiredChecker"
                            :currentValue="getOrgDetails != null ? getOrgDetails.incorporation_type : null"
                            v-model="incorporationType" input_type="text" />
                    </div>
                </template>
                <template v-else>
                    <div class="col-md-4 mb-20">
                        <CustomTextInput @hasError="(checkerVariable) => organizationNameError = checkerVariable"
                            label="Organization Name" :required="true" placeholder="Enter Organization Name"
                            input_type="text" :isemptycheck="requiredChecker" :minlength="2" :maxlength="150"
                            :currentValue="getOrgDetails != null ? getOrgDetails.organizationName : null"
                            v-model="organizationName" />
                    </div>
                    <div class="col-md-4 mb-20">
                        <CustomSelectInput label="Organization Type" :options="getOrganizationTypes" :required="true"
                            placeholder="Select Organization Type" :isemptycheck="requiredChecker"
                            :currentValue="getOrgDetails != null ? getOrgDetails.incorporation_type : null"
                            v-model="incorporationType" input_type="text" />
                    </div>
                </template>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => tradeNameError = checkerVariable"
                        label="Trade Name / Doing Business As" :required="false" :isemptycheck="requiredChecker"
                        :minlength="4" :maxlength="150"
                        :currentValue="getOrgDetails != null ? getOrgDetails.trade_name : null" v-model="tradeName"
                        placeholder="Enter Trade Name" input_type="text" />
                </div>
                <div class="col-md-4 mb-20  ">
                    <CustomSelectInput label="Industry" :options="getIndustries" :required="true"
                        placeholder="Select Industry" :isemptycheck="requiredChecker"
                        :currentValue="getOrgDetails != null ? getOrgDetails.industry : null" v-model="industry" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => incorporationNumberError = checkerVariable"
                        label="Incorporation Number" :required="false" placeholder="Enter Incorporation Number"
                        input_type="text" :isemptycheck="requiredChecker" :minlength="4" :maxlength="15"
                        :currentValue="getOrgDetails != null ? getOrgDetails.incorporation_number : null"
                        v-model="incorporationNumber" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => craNumberError = checkerVariable"
                        label="CRA Business Number" :required="false" placeholder="Enter Business Number"
                        :isemptycheck="requiredChecker" :minlength="3" :maxlength="20"
                        :currentValue="getOrgDetails != null ? getOrgDetails.cra_number : null" v-model="craNumber"
                        input_type="text" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomDateInput label="Date of Incorporation " :required="false" placeholder="2012/2/23"
                        :isemptycheck="requiredChecker" :currentValue="incorporationDate" v-model="incorporationDate"
                        input_type="text" />
                </div>

                <div class="col-md-4 mb-20">
                    <CustomSelectInput :options="getProvinces" :required="true"
                        placeholder="Select province of incorporation" label="Province of Incorporation"
                        :isemptycheck="requiredChecker"
                        :currentValue="getOrgDetails != null ? getOrgDetails.province_of_incorporation : null"
                        v-model="provinceOfIncorporation">
                    </CustomSelectInput>
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => websiteError = checkerVariable"
                        placeholder="Enter website" label="Website" :isemptycheck="requiredChecker" :minlength="4"
                        :maxlength="150" :currentValue="getOrgDetails != null ? getOrgDetails.website : null"
                        v-model="website" input_type="url">
                    </CustomTextInput>
                </div>
                <div class="col-md-12 mb-20">
                    <label for="" class="textarea-label">Company Description</label>
                    <textarea class="textarea w-100" :class="{ 'textarea-error': companyDescError }"
                        :isemptycheck="requiredChecker" placeholder="Enter Description" v-model="companyDesc" name=""
                        @keyup="countCharaters" id="" cols="" rows="5">{{
                                getOrgDetails != null ? getOrgDetails.company_desc : null }}</textarea>
                    <div class="w-100 d-flex justify-content-end gap-3">
                        <p class="m-0 p-0 text-danger" v-if="companyDescError">{{ textareamessage }}</p>
                        <p class="m-0 p-0 ">{{ currentCount }}/{{ maxCount }}</p>
                    </div>
                </div>

                <div class="col-md-12 mb-20">
                    <CustomMultiSelect @customSelect="getIntent" :required="true" :isemptycheck="requiredChecker"
                        :currentValue="getOrgDetails != null ? getOrgDetails.intended_use_of_account : null"
                        v-model="intendedUseOfAccount" placeholder="Select Intended purpose of account"
                        label="Intended Use of Account" :options="intended_use_of_account">
                    </CustomMultiSelect>

                </div>
                <div class="row w-100 justify-content-between">
                    <div class="col-md-12 mt-3">
                        <p class="section-header">
                            Registered organization address
                        </p>
                    </div>
                </div>

                <div class="col-md-12">
                    <CustomLocationPicker @alternativeAddress="alternativeAddress1" @updateLocation="address1Location"
                        v-model="address1.streetAddress" @haserror="(value) => address1error = value"
                        :currentValue="getOrgDetails != null ? getOrgDetails.business_address.streetAddress : null"
                        :isemptycheck="requiredChecker" :required="true" label="Street Address"
                        placeholder="Enter your organization address i.e postal code, street address and select from option provided in the dropdown" />
                </div>
                <!-- Differnt Business Adress -->

                <div class="col-md-12 py-1 mt-2">
                    <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                        <div class="d-flex flex-row subheading gap-2">
                            <span> Mailing Address</span>

                            <span class="d-flex gap-2 ">
                                <div :class="{ 'selected-radio-action': useRegAddressForMailingAdress }">
                                    Use the same as registered organization address
                                    <input class="my-radio" :isemptycheck="requiredChecker"
                                        v-model="useRegAddressForMailingAdress" :value="true" type="radio"
                                        name="address">
                                </div>
                                <div :class="{ 'selected-radio-action': !useRegAddressForMailingAdress }">
                                    Use different address
                                    <input class="my-radio" :isemptycheck="requiredChecker"
                                        v-model="useRegAddressForMailingAdress" :value="false" type="radio"
                                        name="address">
                                </div>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row p-0  m-0" v-if="!useRegAddressForMailingAdress">
                    <div class="col-md-12">
                        <CustomLocationPicker @alternativeAddress="alternativeAddress2"
                            @updateLocation="mailingLocation" v-model="mailing.streetAddress"
                            @haserror="(value) => mailingerror = value" :currentValue="mailing.streetAddress"
                            :isemptycheck="requiredChecker" :required="true" label="Street Address"
                            placeholder="Enter your organization address i.e postal code, street address and select from option provided in the dropdown" />
                    </div>
                </div>

            </div>
        </div>
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <GoBack @action="goBack" v-if="!getIsConference" outline="true" title="Previous" />
            <CustomSubmit :isLoading="sending" @action="submitForm" :outline="false" title="submit" />
        </div>
        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Ooops! An issue occured"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext="" :message="failmessage" outlined="">
        </ActionMessageModal>
       
        
        <UploadImage :isdeleted="isdeleted" @changeStatus="uploadimage = false" :uploadimage="uploadimage"
            @seeImage="seeImage">
        </UploadImage>

    </div>
</template>

<script>


import CustomSubmit from '../shared/CustomSubmit.vue';
import GoBack from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import CustomMultiSelect from '../shared/CustomMultiSelect.vue';
import Checkbox from '../shared/Checkbox.vue';

import ActionMessageModal from '../shared/ActionMessageModal.vue';
import TitleWithIcon from '../shared/TitleWithIcon.vue';
import CustomDateInput from '../shared/CustomDateInput.vue'
import UploadImage from '../shared/UploadImage.vue';

import CustomLocationPicker from '../shared/CustomLocationPicker.vue'

export default {
    props: ['fis', 'institution_types'],
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput, CustomMultiSelect, ActionMessageModal,
        TitleWithIcon, Checkbox, CustomDateInput, CustomLocationPicker, UploadImage, GoBack
    },
    beforeMount() {
        this.financialInstitutions = JSON.parse(this.fis)
        // console.log(this.getUserType, "User type")
        if (this.getUserType == 'bank') {
            this.intended_use_of_account = [
                { id: 'enable_gic', text: 'Post GIC Rates' },
                { id: 'enable_repos', text: 'Provide Collateral' }
                // 'Provide Collateral', 'Post GIC Rates'
            ]
        } else {
            this.intended_use_of_account = [
                { id: 'enable_gic', text: 'Invest in a GIC' },
                { id: 'enable_repos', text: 'Invest in a Repo' }
                // 'Provide Collateral', 'Post GIC Rates'
            ]
            // this.intended_use_of_account = ['Invest in a GIC', 'Invest in a Repo']
        }
        if (this.getOrgDetails) {
            if (this.getOrgDetails != null) {

                this.address1 = this.getOrgDetails.business_address
                this.mailing = this.getOrgDetails.mailing_address
                // this.intendedUseOfAccount = this.getOrgDetails.intended_use_of_account
                this.companyDesc = this.getOrgDetails.company_desc
                this.incorporationDate = this.getOrgDetails.incorporation_date
                this.useRegAddressForMailingAdress = this.getOrgDetails.useRegAddressForMailingAdress
            }
        }
        console.log(this.getOrgDetails, "Org Details")
    },
    mounted() {
        // this.getOrgadetails()
        this.$store.dispatch('setStageTitle', 'Enter your details to complete registration')
        this.$store.dispatch('setProgress', 30)


    },
    data() {
        return {
            financialInstitutions: null,
            places: null,
            uploadimage: false,
            uploaded_image: null,
            provinces: null,
            industries: null,
            maxCount: 300,
            currentCount: 0,
            countError: null,
            requiredChecker: false,
            organizationName: null,
            incorporationType: null,
            tradeName: null,
            industry: null,
            incorporationNumber: null,
            organizationID: null,
            craNumber: null,
            incorporationDate: null,
            provinceOfIncorporation: null,
            website: null,
            companyDesc: null,
            intendedUseOfAccount: null,
            mailingerror: false,
            address1error: false,
            textareamessage: '',
            address1: {
                streetAddress: null,
                city: null,
                province: null,
                postalCode: null
            },
            mailing: {
                streetAddress: null,
                city: null,
                province: null,
                postalCode: null
            },
            isdeleted: false,
            useRegAddressForMailingAdress: true,
            // define error variables
            organizationNameError: false,
            tradeNameError: false,
            incorporationNumberError: false,
            craNumberError: false,
            websiteError: false,
            companyDescError: false,
            fail: false,
            failmessage: 'We are unable to update your data, please try again or contact us at info@yieldexchange.ca',
            sending: false,
            intended_use_of_account: [],
            fi_types: [
                {
                    id: "Provincial Credit Union",
                    name: "Provincial Credit Union",
                },
                {
                    id: "Federal Credit Union",
                    name: "Federal Credit Union",
                },
                {
                    id: "Bank",
                    name: "Bank",
                },
                {
                    id: "Trust",
                    name: "Trust",
                },
            ],


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
        getIsConference() {
            return this.$store.getters.getIsConference;
        },
        getUserType() {
            return this.$store.getters.getUserType;
        },

    },
    methods: {
        removeImage() {
            this.isdeleted = true
            this.uploaded_image = null
        },
        seeImage(value) {
            this.uploaded_image = value
            this.uploadimage = false
            this.isdeleted = false
            // this.$refs['image'].el.src = value;
        },
        goNext() {
            this.$store.dispatch('setCurrentStep', 'userDetails')
            this.$store.dispatch('setPrevStep', 'depOrgDetails')
        },
        countCharaters() {
            this.currentCount = this.companyDesc.length
            if (this.sanitizeInput(this.companyDesc) != null) {
                if (this.maxCount < this.currentCount) {
                    this.companyDescError = true
                    this.textareamessage = `Please keep the description length within the
                            0 to ${this.maxCount} character range.`
                } else {
                    this.companyDescError = false
                }
            } else {
                this.companyDescError = true
                this.textareamessage = "The description may contain harmful file extension or invalid characters"

            }
        },
        address1Location(value) {
            this.address1.postalCode = value.postal_code
            this.address1.city = value.city
            this.address1.province = value.province
            this.address1.streetAddress = value.full_name
        },
        mailingLocation(value) {
            this.mailing.postalCode = value.postal_code
            this.mailing.city = value.city
            this.mailing.province = value.province
            this.mailing.streetAddress = value.full_name
        },
        alternativeAddress1(address) {
            this.address1.streetAddress = address
        },
        alternativeAddress2(address) {
            this.mailing.streetAddress = address
        },
        updateOrganization(org_id) {
            const organization = {

                'organization_id': org_id,
                'organizationName': this.organizationName,
                'incorporation_type': this.incorporationType,
                'trade_name': this.tradeName,
                'organizationID': this.organizationID,
                'industry': this.industry,
                'incorporation_number': this.incorporationNumber,
                'cra_number': this.craNumber,
                'incorporation_date': this.incorporationDate,
                'province_of_incorporation': this.provinceOfIncorporation,
                'website': this.website,
                'company_desc': this.companyDesc,
                'intended_use_of_account': this.intendedUseOfAccount,
                'business_address': this.address1,
                'uploaded_image': this.uploaded_image,
                'useRegAddressForMailingAdress': this.useRegAddressForMailingAdress,
                'mailing_address': (this.useRegAddressForMailingAdress) ? this.address1 : this.mailing,
            }
            this.$store.dispatch('setOrgDetails', organization)
        },

        submitForm() {
            this.requiredChecker = false
            this.requiredChecker = !this.canSubmit()
            console.log("can submit", !this.requiredChecker)
            if (this.canSubmit()) {

                this.submitAction()
                // this.goNext()
            }


        },
        async submitAction() {
            this.failmessage = 'We are unable to update your data, please try again or contact us at info@yieldexchange.ca'
            this.sending = true
            const uploaddata = {
                is_individual: 0,
                user_id: this.getLoggedInUser.user_id,
                organization_name: this.organizationName,
                organization_type: this.incorporationType,
                trade_name: this.tradeName,
                logo: this.uploaded_image,
                industry_id: this.industry,
                incoporation_number: this.incorporationNumber,
                cra_business_number: this.craNumber,
                incoporation_date: this.incorporationDate,
                incoporation_province: this.provinceOfIncorporation,
                institutionType: this.getUserType.toUpperCase(),
                website: this.website,
                description: this.companyDesc,
                intended_use: this.intendedUseOfAccount,
                street: this.address1.streetAddress,
                province: this.address1.province,
                status: this.getIsConference ? "ACTIVE" : "PENDING",
                city: this.address1.city,
                postal_code: this.address1.postalCode,
                use_different_address: !this.useRegAddressForMailingAdress,
                other_street: (!this.useRegAddressForMailingAdress) ? this.mailing.streetAddress : this.address1.streetAddress,
                other_province: (!this.useRegAddressForMailingAdress) ? this.mailing.province : this.address1.province,
                other_city: (!this.useRegAddressForMailingAdress) ? this.mailing.city : this.address1.city,
                other_postal_code: (!this.useRegAddressForMailingAdress) ? this.mailing.city : this.address1.city,
            }
            // console.log(uploaddata)
            await axios.post('/depositor-business-create-organization', uploaddata, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                this.sending = false
                // console.log(response.data.success)
                if (response.data.success) {
                    // console.log('Data Added Success fully')
                    this.updateOrganization(response.data.data.organization.id)
                    this.$store.dispatch('setStepsTracker', 'depOrgDetails')

                    this.goNext()
                }
                else {
                    this.fail = true
                    console.log(response, 'response')
                    this.failmessage = err.response?.data?.message[0]
                    // this.failmessage = this.data.message
                    this.failmessage = response?.data?.message
                    setTimeout(() => {
                        this.fail = false
                        this.failmessage= 'We are unable to update your data, please try again or contact us at info@yieldexchange.ca'

                    }, 3000)
                }
            }).catch(err => {
                this.sending = false
                this.fail = true
                console.log(err?.response?.data?.message, 'err response')
                this.failmessage = err.response?.data?.message[0]

                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false
                    this.failmessage= 'We are unable to update your data, please try again or contact us at info@yieldexchange.ca'

                }, 3000)
            })
        },
        goBack() {
            this.$store.dispatch('setCurrentStep', 'deptype')
            // this.$store.dispatch('setPrevStep', 'depOrgDetails')
            this.$store.dispatch('setDepositorType', null)

        },
        getIntent(value) {
            // console.log(value),
            this.intendedUseOfAccount = value
        },
        canSubmit() {
            if (
                this.organizationName != null &&
                this.incorporationType != null &&
                this.industry != null &&
                this.provinceOfIncorporation != null &&
                this.intendedUseOfAccount != null &&
                this.intendedUseOfAccount.length != 0 &&
                this.address1.streetAddress != null &&
                !this.organizationNameError &&
                !this.address1error &&
                !this.tradeNameError &&
                !this.incorporationNumberError &&
                !this.craNumberError &&
                !this.websiteError &&
                !this.companyDescError

            ) {
                if (!this.useRegAddressForMailingAdress) {
                    if (this.mailing.streetAddress != null && !this.mailingerror) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true
                }
            } else {
                return false
            }
        },
        sanitizeInput(input) {
            // Check for malicious file extensions
            if (/\.(exe|bat|cmd|msi|jar|js|vbs|ps1|zip|rar|tar)$/i.test(input)) {
                // Handle or reject the input
                return null; // Or handle it in an appropriate way
            }

            // Check for suspicious URLs
            if (
                /(javascript:|data:|<script)/i.test(input) || // JavaScript or data URLs
                /(http|https):\/\/[^\s/$.?#].[^\s]*/i.test(input) // HTTP/HTTPS URLs
            ) {
                // Handle or reject the input
                return null; // Or handle it in an appropriate way
            }

            // Check for common XSS payloads
            if (/((<\s*script)|(%3Cscript)|(%253Cscript))/i.test(input)) {
                // Handle or reject the input
                return null; // Or handle it in an appropriate way
            }

            // Check for command injection payloads
            if (/(;|\||`|\$\(|\${)/i.test(input)) {
                // Handle or reject the input
                return null; // Or handle it in an appropriate way
            }

            // Check for SQL injection payloads
            if (/(\b(select|update|delete|insert|drop|alter)\b)/i.test(input)) {
                // Handle or reject the input
                return null; // Or handle it in an appropriate way
            }

            // Replace potentially harmful characters with harmless ones
            if (input == null)
                return ''
            else {
                return input.replace(/[&<>"']/g, function (match) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[match];
                });
            }
        },
    },
    watch: {
        organizationID() {
            let organization = this.financialInstitutions.find(item => item.id == this.organizationID).name
            this.organizationName = organization
            // console.log(organization, "Organization ID")
        },
        useRegAddressForMailingAdress(newValue, oldVal) {
            // console.log(newValue)
        },
        intendedUseOfAccount(newValue, oldVal) {
            // console.log(newValue)
        }
    }
}
</script>

<style scoped>
.top-title {
    margin-bottom: 30px;

}

.deposi-image-upload {
    border-radius: 10px;
    border: 0.5px solid #D9D9D9;
    display: flex;
    width: 100px;
    height: 78px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-left: 10px;
}

.deposi-image-upload img {
    width: 68px;
    height: 69px;
    flex-shrink: 0;
}

.deposi-image-upload-action {
    position: absolute;
    width: 25px;
    height: 25px;
    bottom: 0;
    right: 0;
    margin-right: -8px;
    margin-bottom: -8px;
}

.deposi-image-upload-action img {
    width: 25px;
    height: 25px;
    cursor: pointer;


}

.mb-20 {
    margin-bottom: 20px !important;
}

.selected-radio-action {
    color: #5063F4 !important;
}

.not-selected-radio-action {
    color: #252525 !important;
}

.textarea {
    border-radius: 10px;
    border: 1px solid #D9D9D9;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;

    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 150%;
    /* text-transform: capitalize; */
}

.textarea-label {
    color: #252525;
    font-family: Montserrat !important;
    ;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;

}

.textarea-error {
    border: 1px solid #FF0101 !important;
}

.top-title p {
    color: #252525;

    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
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

.logo_upload_title {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Darks, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 162.5% */
    text-decoration-line: underline;
    text-transform: capitalize;

    padding: 0;
    margin: 0;
}

.logo_upload_desc {
    padding: 0;
    font-weight: 400;
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

::placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
    /* Firefox */
}

::-ms-input-placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}

.section-header {
    color: #252525;
    /* font-feature-settings: 'clig' off, 'liga' off; */
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 108.333% */
    text-transform: capitalize;
}

.subheading {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;

}


input[type=radio] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=radio]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=radio]:checked::before {
    transform: scale(1);
}

input[type=radio]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}
</style>
