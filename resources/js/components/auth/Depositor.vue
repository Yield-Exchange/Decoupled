<template>
    <div class="bg-white">
        <div class="SignInContainer col-md-6 mb-5 d-none">
            <h2>{{ 'Account Registration' }}</h2>

            <div class="row" style="margin-top: 20px" v-if="formErrors">
                <b-alert show v-bind:variant="formAlertType ? formAlertType : 'warning'">{{ formErrors }}</b-alert>
            </div>

            <div class="row col-md-12" v-if="!organizationType">
                <p style="font-size: 13px;margin-top:10px">Please select your organization type</p>
            </div>

            <div class="row col-md-12" v-if="organizationType">
                <p style="font-size: 13px;margin-top:10px">
                    Please fill your information.
                </p>
            </div>

            <div class="row mt-2" v-if="organizationType">

                <div class="text-left">


                    <b-col sm="12" class="pr-0 pl-0 mb-3"
                        v-if="this.organizationType && this.organizationType.toUpperCase() === 'DEPOSITOR' && !showLabel">
                        <label v-if="showLabel" for="institution_name">Organization Name:</label>
                        <b-form-input maxlength="51" placeholder="Organization Name*" class="font-13"
                            id="institution_name" v-model="institution_name" @keyup="compareInstitutionStringLength"
                            :class="{ 'verror': institutionErrors }">
                        </b-form-input>
                        <b-alert v-if="institutionErrors" show variant="danger" class="form-alert">{{ institutionErrors
                            }}</b-alert>
                    </b-col>


                    <b-col sm="12" class="pr-0 pl-0 mb-3">
                        <label v-if="showLabel" for="first_name">First Name:</label>
                        <b-form-input maxlength="26" placeholder="First Name*" class="font-13" v-model="first_name"
                            id="first_name" @keyup="compareFirstnameStringLength"
                            :class="{ 'verror': firstnameErrors }">
                        </b-form-input>
                        <b-alert v-if="firstnameErrors" show variant="danger">{{ firstnameErrors }}</b-alert>
                    </b-col>


                    <b-col sm="12" class="pr-0 pl-0 mb-3">
                        <label v-if="showLabel" for="last_name">Last Name:</label>
                        <b-form-input maxlength="26" placeholder="Last Name*" class="font-13" v-model="last_name"
                            id="last_name" @keyup="compareLastnameStringLength" :class="{ 'verror': lastnameErrors }">
                        </b-form-input>
                        <b-alert v-if="lastnameErrors" show variant="danger">{{ lastnameErrors }}</b-alert>
                    </b-col>


                    <b-col sm="12" class="pr-0 pl-0 mb-3">
                        <label v-if="showLabel" for="email">Email:</label>
                        <b-form-input maxlength="51" placeholder="Email*" class="font-13" v-model="email" id="email"
                            :state="validateEmail ? null : false" @keyup="compareEmailStringLength"
                            :class="{ 'verror': emailErrors }">
                        </b-form-input>
                        <b-alert v-if="emailErrors" show variant="danger">{{ emailErrors }}</b-alert>
                    </b-col>

                    <b-col sm="12" class="pr-0 pl-0 mb-3">
                        <label v-if="showLabel" for="telephone">Telephone No:</label>
                        <vue-phone-number-input :value="telephone" id="telephone" @keyup="telephoneValidation"
                            v-model="telephone" :only-countries="['CA']" :preferred-countries="['CA']"
                            default-country-code="CA" :class="{ 'verror': telephoneErrors }" placeholder="Telephone" />
                        <b-alert v-if="telephoneErrors" show variant="danger">{{ telephoneErrors }}</b-alert>
                    </b-col>

                    <b-col sm="12" class="pr-0 pl-0 mb-3">
                        <label v-if="showLabel" for="website">Website:</label>
                        <b-form-input maxlength="26" placeholder="Website" class="font-13" v-model="website"
                            id="website" @keyup="WebsiteValidation" :class="{ 'verror': websiteErrors }">
                        </b-form-input>
                        <b-alert v-if="websiteErrors" show variant="danger">{{ websiteErrors }}</b-alert>
                    </b-col>

                    <b-col sm="12" class="pr-0 pl-0 mb-3" hidden>
                        <b-form-checkbox id="accept-terms-and-conditions" name="checkbox-1" value="accepted"
                            unchecked-value="not_accepted" style="" class="font-13">
                            <b-button variant="link" v-bind:href="this.loginRoute"
                                style="font-size:13px;padding-left:0">I
                                accept
                                the terms and conditions</b-button>
                        </b-form-checkbox>
                    </b-col>
                    <!--                <div class="row" v-if="skip_robot">-->
                    <!--                    <vue-recaptcha ref="recaptcha" :sitekey="recaptchaKey" @verify="verifyRecaptcha"-->
                    <!--                        @error="errorRecaptcha" />-->
                    <!--                </div>-->


                </div>
            </div>

            <div class="row text-left">
                <div class="col-md-6">
                    <b-button variant="link" v-bind:href="this.loginRoute" style="font-size:13px;padding-left:0"><span
                            style="color:black">Have an account?</span> Sign in instead</b-button>
                </div>
                <div class="col-md-6 text-right" v-if="this.organizationType">

                    <b-button :variant="'primary'" :disabled="submitButtonSpinner"
                        style="font-size:13px;border-radius:20px" @click="doSubmit">
                        <b-spinner variant="light" label="Loading"
                            style="width: 1.3rem; height: 1.3rem;margin-right:5px" v-if="submitButtonSpinner">
                        </b-spinner>
                        {{ submitButtonText }}
                    </b-button>

                </div>
            </div>
        </div>
        <RequestAccount @submit="submitAction" :is_conference="true" :registerRoute="registerRoute"
            :recaptchaKey="recaptchaKey"></RequestAccount>
    </div>
</template>
<style scoped>
.btn.btn-secondary[disabled] {
    background-color: #979797;
    color: black;
}
</style>
<script>
import RequestAccount from './RequestAccount.vue';
import { VueRecaptcha } from 'vue-recaptcha';
export default {
    props: ['registerRoute', 'recaptchaKey', 'skip_robot', 'loginRoute', 'referral'],
    components: { RequestAccount },
    data() {
        return {
            step: 1,
            organizationType: 'Depositor',
            passwordVisible: false,
            formErrors: '',
            formAlertType: 'warning',
            submitButtonText: 'Register',
            submitButtonSpinner: false,
            institution_name: '',
            profile_image: '',
            recaptchaToken: this.skip_robot ? 1 : null,
            province: '',
            naics_code: '',
            institution_type: '',
            address_line_1: '',
            address_line_2: '',
            description: '',
            city: '',
            postal_code: '',
            telephone: '',
            website: '',
            potential_deposit: '',
            wholesale_deposit_portfolio_id: '',
            conf_password: '',
            password: '',
            timezone: '',
            department: '',
            user_telephone: '',
            location: '',
            province2: '',
            job_title: '',
            role_name: '',
            showLabel: false,
            digital_account_opening: '',


            telephoneErrors: '',
            websiteErrors: '',
            email: '',
            last_name: '',
            first_name: '',
            institutionErrors: '',
            firstnameErrors: '',
            lastnameErrors: '',
            emailErrors: '',
        }
    },
    watch: {

    },
    computed: {
        allowSubmit() {
            let canSubmit = this.canSubmit();
            return canSubmit;
        },
        validateEmail() {
            return this.validEmail();
        }
    },
    methods: {
        submitAction(value) {
            // console.log(value)
            this.institution_name = value.organization_name
            this.email = value.email
            this.last_name = value.last_name
            this.first_name = value.first_name
            this.telephone = value.phone_number
            this.recaptchaToken = value.recaptcha
            this.doSubmit()
        },
        compareEmailStringLength(e) {
            if (!this.email && e?.keyCode !== 9) {
                this.emailErrors = "Email is Required";
            } else if (this.email.length > 50) {
                this.emailErrors = "Email cannot be more than 50 characters.";
                //setTimeout(() => { this.emailErrors = ""; }, 4000);
            } else {
                this.emailErrors = "";
            }
        },
        errorRecaptcha(response) {
            // console.log(response);
        },
        validEmail() {
            return !this.email || String(this.email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        },
        verifyRecaptcha(response) {
            this.recaptchaToken = response;
        },
        compareFirstnameStringLength(e) {
            if (!this.first_name && e?.keyCode !== 9) {
                this.firstnameErrors = "First Name is Required";
            } else if (this.first_name.length > 25) {
                this.firstnameErrors = "First Name cannot be more than 25 characters.";
            } else {
                this.firstnameErrors = "";
            }
        },
        compareLastnameStringLength(e) {
            if (!this.last_name && e?.keyCode !== 9) {
                this.lastnameErrors = "Last Name is Required";
            } else if (this.last_name.length > 25) {
                this.lastnameErrors = "Last Name cannot be more than 25 characters.";
            } else {
                this.lastnameErrors = "";
            }
        },
        compareInstitutionStringLength(e) {
            if (!this.institution_name && e?.keyCode !== 9) {
                this.institutionErrors = "Institution Name is required.";
            } else if (this.institution_name.length > 50) {
                this.institutionErrors = "Institution Name cannot be more than 50 characters.";
            } else {
                this.institutionErrors = "";
            }
        },
        telephoneValidation(e) {
            if (!this.telephone && e?.keyCode !== 9) {
                this.telephoneErrors = "Telephone is required.";
            } /*else if (this.telephone.length > 10) {
                this.telephoneErrors = "Telephone cannot be more than 10 characters.";
                // setTimeout(() => { this.telephoneErrors = ""; }, 4000);
            } */else {
                this.telephoneErrors = "";
            }
        },
        WebsiteValidation(e) {
            let urlPattern = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:[0-9]+)?(\/[^\s]*)?$/i;
            if (this.website) {
                let t = urlPattern.test(this.website);
                if (!t) {
                    this.websiteErrors = "Invalid website";
                } else {
                    this.websiteErrors = "";
                }
            } else {
                this.websiteErrors = "";
            }
        },
        canSubmit() {
            if (this.organizationType && this.institution_name && this.email && this.validEmail() && this.first_name && this.last_name) {
                return true;
            }
            return false;
        },
        isValidURL(url) {
            return true;
            // Regular expression for a more comprehensive URL validation
            // var urlPattern = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:[0-9]+)?(\/[^\s]*)?$/i;
            // return urlPattern.test(url);
        },
        async doSubmit() {
            let this_ = this;

            if (this_.canSubmit()) {
                this_.submitButtonText = "Please wait..";
                this_.submitButtonSpinner = true;
                const formData = new FormData();
                formData.append("userType", this_.organizationType);
                formData.append("institution_name", this_.institution_name);
                formData.append("recaptcha", this_.recaptchaToken);
                formData.append("email", this_.email);
                formData.append("last_name", this_.last_name);
                formData.append("first_name", this_.first_name);
                formData.append("automated_test", this.skip_robot);
                formData.append("referral", this_.referral);
                formData.append("website", this_.website);
                formData.append("telephone", this_.telephone);
                formData.append("conference_form", 1);
                // console.log('here');
                axios.post(this_.registerRoute, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    let data = response?.data;
                    this_.submitButtonSpinner = false;
                    if (data.success) {
                        // this.$swal({
                        //     title: 'Registration successful',
                        //     text: data.message,
                        //     confirmButtonText: 'Close'
                        // }).then(() => {
                        window.location.href = '/proceed-to-reg?termsandconditions=termsandconditions';//this_.homeRoute; // redirect user to dashboard to finish up adding users
                        // });
                    } else {
                        this.$swal({
                            title: 'Registration failed',
                            text: data.message,
                            confirmButtonText: 'Close'
                        });
                        // this_.formErrors = data.message;
                        // this_.formAlertType = data?.alert_class?.replace("alert-", "");
                    }
                    this_.submitButtonText = 'Continue';
                }).catch(error => {
                    if (error?.response?.status === 419) {
                        this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        // this.formErrors = error?.response?.data?.message;
                    }

                    // this_.formErrors = error?.response?.data?.message;
                    // this_.formAlertType = error?.response?.data?.alert_class?.replace("alert-","");

                    this.$swal({
                        title: 'Registration failed',
                        text: error?.response?.data?.message,
                        confirmButtonText: 'Close'
                    });

                    this_.submitButtonText = 'Continue';
                    this.submitButtonSpinner = false;
                });
            }
        },
        errorCheck() {
            this.compareFirstnameStringLength();
            this.compareEmailStringLength();
            this.compareLastnameStringLength();
        }
    }
}
</script>
