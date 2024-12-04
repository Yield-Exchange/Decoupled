<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <!-- <p class="title">Welcome! What organization are you representing?</p> -->
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/dashboard/images/Building_permit-bro_1.svg"
                    style="max-height: 200px; margin: 20px auto;" alt="" srcset="">
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
                        <div class="col-md-2 deposi-image-upload" @click="uploadimage = true">
                            <img v-if="uploaded_image == null" ref="image"
                                src="/assets/dashboard/images/upload-image.svg" alt="">
                            <img v-else :src="uploaded_image" alt="">
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


                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => organizationNameError = checkerVariable"
                        label="Organization Name" :required="true" placeholder="Enter your organization name"
                        :minlength="2" :maxlength="150" input_type="text" :isemptycheck="requiredChecker"
                        v-model="organizationName" :currentValue='organizationName' />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomSelectInput label="Organization Type" :options="organizationtypes" :required="true"
                        placeholder="Select organization type" :isemptycheck="requiredChecker"
                        :currentValue="incorporationType" v-model="incorporationType" input_type="text" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => tradeNameError = checkerVariable"
                        label="Trade Name / Doing Business As" :required="false" :isemptycheck="requiredChecker"
                        :currentValue="tradeName" v-model="tradeName" placeholder="What is your trade name?"
                        :minlength="4" :maxlength="150" input_type="text" />
                </div>
                <div class="col-md-4 mb-20  ">
                    <CustomSelectInput label="Industry" :options="industries" :required="true"
                        placeholder="Select Industry" :isemptycheck="requiredChecker" :currentValue="industry"
                        v-model="industry" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => incorporationNumberError = checkerVariable"
                        label="Incorporation Number" :required="false" placeholder="Enter Incorporation Number"
                        input_type="text" :isemptycheck="requiredChecker" :currentValue="incorporationNumber"
                        :minlength="4" :maxlength="15" v-model="incorporationNumber" />
                </div>
                <div class="col-md-4 mb-20">
                    <CustomTextInput @hasError="(checkerVariable) => craNumberError = checkerVariable"
                        label="CRA Business Number" :required="false" placeholder="Enter Business Number" :minlength="3"
                        :maxlength="20" :isemptycheck="requiredChecker" :currentValue="craNumber" v-model="craNumber"
                        input_type="text" />
                </div>

                <div class="col-md-4 mb-20">
                    <CustomDateInput label="Date of Incorporation " :required="false"
                        placeholder="Select Date of Incorporation " :isemptycheck="requiredChecker"
                        :currentValue="incorporationDate" v-model="incorporationDate" input_type="text" />
                </div>

                <div class="col-md-4 mb-20 ">
                    <CustomSelectInput :options="provinces" :required="true"
                        placeholder="Select province of incorporation" label="Province of Incorporation"
                        :isemptycheck="requiredChecker" :currentValue="provinceOfIncorporation"
                        v-model="provinceOfIncorporation">
                    </CustomSelectInput>
                </div>
                <div class="col-md-4 mb-20 ">
                    <CustomTextInput @hasError="(checkerVariable) => websiteError = checkerVariable" :minlength="3"
                        :maxlength="150" placeholder="Enter website" label="Website" :isemptycheck="requiredChecker"
                        :currentValue="website" v-model="website" input_type="url"></CustomTextInput>
                </div>
                <div class="col-md-12  mb-3">
                    <label for="" class="textarea-label">Company Description</label>
                    <textarea class="textarea w-100" :class="{ 'textarea-error': companyDescError }"
                        :isemptycheck="requiredChecker" placeholder="Enter Description" v-model="companyDesc" name=""
                        @keyup="countCharaters" id="" cols="" rows="5">{{ companyDesc }}</textarea>
                    <div class="w-100 d-flex justify-content-end gap-3">
                        <p class="m-0 p-0 text-danger" v-if="companyDescError">{{ textareamessage }}</p>
                        <p class="m-0 p-0 ">{{ currentCount }}/{{ maxCount }}</p>
                    </div>
                </div>

                <div class="col-md-12  mb-3">
                    <CustomMultiSelect @customSelect="getIntent" :required="true" :isemptycheck="requiredChecker"
                        :currentValue="intendedUseOfAccount" v-model="intendedUseOfAccount"
                        placeholder="Select Intended purpose of account" label="Intended Use of Account">
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
                        @haserror="(value) => address1error = value" v-model="address1.streetAddress"
                        :isemptycheck="requiredChecker" :currentValue="address1.streetAddress" :required="true"
                        label="Street Address"
                        placeholder="Enter your organization address i.e postal code, street address and select from option provided in the dropdown" />
                </div>
                <!-- <div class="col-md-4">
                    <CustomTextInput label="Postal Code" :currentValue="address1.postalCode" v-model="address1.postalCode"
                        :isemptycheck="requiredChecker" :required="true" placeholder="X1X 1X1" input_type="text" />
                </div> -->

                <div class="col-md-12 py-1 mt-2">
                    <div class="w-100 mb-3 d-flex flex-row justify-content-between align-items-center gap-3">
                        <div class="d-flex flex-row address-subheading gap-2">
                            <span> Mailing Address</span>

                            <span class="d-flex gap-2 ">
                                <div :class="{ 'selected-radio-action': useMailingAddressOne }">
                                    Use the same as registered organization address
                                    <input class="my-radio" :isemptycheck="requiredChecker"
                                        v-model="useMailingAddressOne" :value="true" type="radio" name="address">
                                </div>
                                <div :class="{ 'selected-radio-action': !useMailingAddressOne }">
                                    Use different address
                                    <input class="my-radio" :isemptycheck="requiredChecker"
                                        v-model="useMailingAddressOne" :value="false" type="radio" name="address">
                                </div>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row p-0  m-0" v-if="!useMailingAddressOne">
                    <div class="col-md-12">
                        <CustomLocationPicker @updateLocation="mailingLocation" v-model="mailing.streetAddress"
                            @alternativeAddress="alternativeAddress2" :currentValue="mailing.streetAddress"
                            :isemptycheck="requiredChecker" :required="true" @haserror="(value) => mailingerror = value"
                            label="Street Address"
                            placeholder="Enter your organization address i.e postal code, street address and select from option provided in the dropdown" />
                    </div>

                    <!-- <div class="col-md-4">
                        <CustomTextInput label="Postal Code" :currentValue="mailing.postalCode" v-model="mailing.postalCode"
                            :isemptycheck="requiredChecker" :required="true" placeholder="X1X 1X1" input_type="text" />
                    </div> -->
                </div>

            </div>
        </div>
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <Button type="outlined" @click="goBack">Previous</Button>
            <Button type="primary" @click="submitForm">Next</Button>
        </div>
        <UploadImage :isdeleted="isdeleted" @changeStatus="uploadimage = false" :uploadimage="uploadimage"
            @seeImage="seeImage"></UploadImage>
    </div>
</template>

<script>


    // import CustomSubmit from '../shared/CustomSubmit.vue';et
    // import CustomTextInput from '../shared/CustomTextInput.vue';
    import CustomSelectInput from '../shared/CustomSelectInput.vue';
    import CustomMultiSelect from '../shared/CustomMultiSelect.vue';
    import CustomDateInput from '../shared/CustomDateInput.vue'
    import UploadImage from '../shared/UploadImage.vue';
    import CustomTextInput from '../../../../../auth/signup/shared/CustomTextInput.vue';

    import CustomLocationPicker from '../shared/CustomLocationPicker.vue'
    import Button from '../../../../../shared/Buttons/Button.vue';
    // import Button from '../../../../../shared/Buttons/Button.vue';

    export default {
        props: ['user', 'organization_id'],
        components: {
            CustomTextInput, CustomSelectInput, CustomMultiSelect,
            CustomLocationPicker, UploadImage, CustomDateInput, Button
        },
        mounted() {
            this.getUserBusinessOrganization()
            this.getAllIndustries()
            this.getAllProvinces()

        },
        data() {
            return {
                isdeleted: false,
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
                mailingerror: false,
                address1error: false,
                incorporationNumber: null,
                craNumber: null,
                incorporationDate: null,
                provinceOfIncorporation: null,
                website: null,
                companyDesc: null,
                intendedUseOfAccount: null,
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
                textareamessage: '',
                useMailingAddressOne: true,
                // define error variables
                organizationNameError: false,
                tradeNameError: false,
                incorporationNumberError: false,
                craNumberError: false,
                websiteError: false,
                companyDescError: false,
                fail: false,
                failmessage: 'We are unable to update your data, please try again or contact us at info',
                organizationtypes: [
                    {
                        id: "CORPORATION",
                        name: "Incorporation(Corporation)"
                    },
                    {
                        id: "SOLE",
                        name: "Sole Proprietorship"
                    },
                    {
                        id: "CROWN",
                        name: "Crown Organization"
                    },
                    {
                        id: "PARTNERSHIP",
                        name: "Partnership"
                    }

                ]
            }
        },
        methods: {
            updateFactory() {
                // console.log("set step clicked")
                this.$emit('updateFactory', 'orgdetails')
            },
            goBack() {
                this.$emit('goBack', 'orgdetails')
            },

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
                // console.log(this.companyDesc.length)
            },
            address1Location(value) {
                this.address1.postalCode = value.postal_code
                this.address1.city = value.city
                this.address1.province = value.province
                this.address1.streetAddress = value.full_name
            },
            mailingLocation(value) {
                // console.log(value)
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
            async getUserBusinessOrganization() {

                await axios.get(`/organization-data/${this.organization_id}`).then(response => {
                    let returndata = response.data.data
                    this.organizationName = returndata.name
                    this.incorporationType = returndata.registration_type
                    this.tradeName = returndata.trade_name
                    this.industry = returndata.industry_id
                    this.incorporationNumber = returndata.incoporation_number
                    this.craNumber = returndata.CRA_business_number
                    this.incorporationDate = returndata.incoporation_date
                    this.provinceOfIncorporation = returndata.province_of_incorporation
                    this.website = returndata.demographic_data.website
                    this.companyDesc = returndata.demographic_data.description
                    this.intendedUseOfAccount = returndata.intended_use
                    this.address1.postalCode = returndata.demographic_data.postal_code
                    this.address1.streetAddress = returndata.demographic_data.address1
                    this.mailing.streetAddress = returndata.demographic_data.address2
                    if (this.address1.streetAddress == this.mailing.streetAddress && this.mailing.streetAddress != null) {
                        this.useMailingAddressOne = true
                    } else {
                        this.useMailingAddressOne = false

                    }

                }).catch(err => {
                    // console.log(err)
                })
            },
            getAllIndustries() {
                axios.get('/get-all-industries').then(response => {
                    // console.log(response.data)
                    this.industries = response.data
                }).catch(err => {
                    // console.log(err)
                })
            },
            getAllProvinces() {
                axios.get('/get-all-provinces').then(response => {
                    // console.log(response.data)
                    this.provinces = response.data
                }).catch(err => {
                    // console.log(err)
                })
            },
            submitForm() {
                this.requiredChecker = false
                this.requiredChecker = !this.canSubmit()
                // console.log("can submit", !this.requiredChecker)
                // let formdata = null
                if (this.canSubmit()) {
                    // console.log("Submit")
                    this.submitAction()
                    // this.goNext()
                }


            },
            async submitAction() {

                const uploaddata = {
                    is_individual: 0,
                    user_id: this.user.id,
                    organization_name: this.organizationName,
                    organization_type: this.incorporationType,
                    trade_name: this.tradeName,
                    logo: this.uploaded_image,
                    industry_id: this.industry,
                    incoporation_number: this.incorporationNumber,
                    cra_business_number: this.craNumber,
                    incoporation_date: this.incorporationDate,
                    incoporation_province: this.provinceOfIncorporation,
                    website: this.website,
                    description: this.companyDesc,
                    intended_use: this.intendedUseOfAccount,
                    street: this.address1.streetAddress,
                    province: this.address1.province,
                    city: this.address1.city,
                    postal_code: this.address1.postalCode,
                    use_different_address: !this.useMailingAddressOne,
                    other_street: (!this.useMailingAddressOne) ? this.mailing.streetAddress : this.address1.streetAddress,
                    other_province: (!this.useMailingAddressOne) ? this.mailing.province : this.address1.province,
                    other_city: (!this.useMailingAddressOne) ? this.mailing.city : this.address1.city,
                    other_postal_code: (!this.useMailingAddressOne) ? this.mailing.postalCode : this.address1.postalCode,
                }
                // console.log(uploaddata)
                // console.log(uploaddata)
                await axios.post(`/update-organization/${this.organization_id}`, uploaddata, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.data.success) {
                        // console.log('Data Added Success fully')
                        this.updateFactory()
                        // this.goNext()
                    } else {
                        this.fail = true
                        // this.failmessage = this.data.message
                        setTimeout(() => {
                            this.fail = false

                        }, 3000)
                    }
                }).catch(err => {
                    this.fail = true
                    // this.failmessage = this.data.message
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                })
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
                    // this.address1.city != null &&
                    // this.address1.province != null &&
                    // this.address1.postalCode != null &&
                    !this.organizationNameError &&
                    !this.tradeNameError &&
                    !this.address1error &&
                    !this.incorporationNumberError &&
                    !this.craNumberError &&
                    !this.websiteError &&
                    !this.companyDescError

                ) {
                    if (!this.useMailingAddressOne) {
                        if (this.mailing.streetAddress != null &&
                            !this.mailingerror != null
                            // this.mailing.province != null &&
                            // this.mailing.postalCode != null
                        ) {
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
            useMailingAddressOne(newValue, oldVal) {
                // console.log(newValue)
            },
            intendedUseOfAccount(newValue, oldVal) {
                // console.log(newValue)
            }
        }
    }
</script>

<style>
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
</style>

<style scoped>
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

    .top-title {
        margin-bottom: 30px;
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
        font-weight: 700;
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
        font-size: 20px;
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

    .address-subheading {
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