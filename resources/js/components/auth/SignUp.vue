<template>
    <div class="SignInContainer col-md-6 mb-5">
        <h2>{{ this.step === 1 ? 'Account Registration' : 'User Registration' }}</h2>
        <!--        <div class="row" style="margin-left: 0;margin-right: 0;" v-if="this.step === 1 && !this.referral">-->
        <!--            <div class="col-md-6 text-center p-1 SignInUserType-t-buttons"-->
        <!--                v-bind:style="(organizationType && organizationType === 'Depositor' ? 'background:#3656A6;color:white;' : '') + ' border-right: none; border-top-left-radius: 15px;border-bottom-left-radius: 15px;'"-->
        <!--                @click="selectOrganizationType('Depositor')">Depositor</div>-->
        <!--            <div class="col-md-6 text-center p-1 SignInUserType-t-buttons"-->
        <!--                v-bind:style="(organizationType && organizationType === 'Bank' ? 'background:#3656A6;color:white;' : '') + ' border-top-right-radius: 15px;border-bottom-right-radius: 15px;'"-->
        <!--                @click="selectOrganizationType('Bank')">Financial institutions</div>-->
        <!--        </div>-->

        <!--        <div class="row" style="margin-top: 20px" v-if="formErrors">-->
        <!--            <b-alert show v-bind:variant="formAlertType ? formAlertType : 'warning'">{{ formErrors }}</b-alert>-->
        <!--        </div>-->

        <!--        <div class="row col-md-12" v-if="!organizationType">-->
        <!--            <p style="font-size: 13px;margin-top:10px">Please select your organization type</p>-->
        <!--        </div>-->

        <div class="row col-md-12" v-if="organizationType">
            <p style="font-size: 13px;margin-top:10px">
                {{ this.step === 1 ?
                "Please fill your organization information." : "Please fill your user information."
                }}
            </p>
        </div>

        <div class="row mt-2" v-if="organizationType">
            <sign-up-step1 :fromUserProfile="false" v-if="step === 1" :organization-type="this.organizationType"
                :provinces="this.provinces" :naics-codes="this.naicsCodes" :potential-deposits="this.potentialDeposits"
                :wholesale-deposit-portfolios="this.depositPortfolio" :fi-types="this.fiTypes" :fis="this.fis"
                :institution_name.sync="institution_name" :profile_image.sync="profile_image" :province.sync="province"
                :naics_code.sync="naics_code" :institution_type.sync="institution_type"
                :address_line_1.sync="address_line_1" :description.sync="description"
                :address_line_2.sync="address_line_2" :city.sync="city" :postal_code.sync="postal_code"
                :telephone.sync="telephone" :website.sync="website" :potential_deposit.sync="potential_deposit"
                :form-errors="formErrors" ref="Step1" :digital_account_opening.sync="digital_account_opening"
                :show-label="showLabel" :wholesale_deposit_portfolio_id.sync="wholesale_deposit_portfolio_id"
                :persistImage="persistImage" @updatepersistImage="persistImage=$event">
            </sign-up-step1>
            <sign-up-step2 :fromUserProfile="false" v-if="step === 2" :recaptcha-key="this.recaptchaKey"
                :timezones="this.timezones" :provinces="this.provinces" :terms-route="this.termsRoute"
                :form-errors="formErrors" ref="Step2" :timezone.sync="timezone" :department.sync="department"
                :password.sync="password" :conf_password.sync="conf_password" :user_telephone.sync="user_telephone"
                :email.sync="email" :last_name.sync="last_name" :first_name.sync="first_name" :location.sync="location"
                :province2.sync="province2" :job_title.sync="job_title" :recaptcha-token.sync="recaptchaToken"
                :role_name="role_name" :show-label="showLabel" :skip_robot="skip_robot" :resetRecaptcha="resetRecaptcha"
                @updateResetRecaptcha="updateResetRecaptcha">
            </sign-up-step2>
        </div>

        <div class="row text-left">
            <div class="col-md-6">
                <b-button variant="link" v-bind:href="this.loginRoute" style="font-size:13px;padding-left:0"><span
                        style="color:black">Have an account?</span> Sign in instead</b-button>
            </div>
            <div class="col-md-6 text-right" v-if="this.organizationType">
                <b-button :variant="'secondary'" style="font-size:13px;border-radius:20px;margin-right:30px"
                    @click="gotBack()" v-if="this.step === 2">
                    Back
                </b-button>

                <b-button :variant="'primary'" :disabled="submitButtonSpinner" style="font-size:13px;border-radius:20px"
                    @click="doSubmit">
                    <b-spinner variant="light" label="Loading" style="width: 1.3rem; height: 1.3rem;margin-right:5px"
                        v-if="submitButtonSpinner">
                    </b-spinner>
                    {{ submitButtonText }}
                </b-button>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-md-12 text-right" style="margin-top: 17px">
                <span style="font-size:13px">
                    Step {{ step }} of 2
                </span>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .btn.btn-secondary[disabled] {
        background-color: #979797;
        color: black;
    }
</style>
<script>
    export default {
        props: ['registerRoute', 'loginRoute', 'recaptchaKey', 'provinces', 'timezones', 'naicsCodes', 'potentialDeposits', 'depositPortfolio', 'fiTypes', 'fis', 'termsRoute', 'homeRoute', 'skip_robot', 'referral'],
        data() {
            return {
                resetRecaptcha: false,
                step: 1,
                organizationType: 'Bank', //this.referral ? 'Depositor' : '',
                passwordVisible: false,
                formErrors: '',
                formAlertType: 'warning',
                submitButtonText: 'Continue',
                submitButtonSpinner: false,
                institution_name: '',
                profile_image: '',
                persistImage: {},
                recaptchaToken: this.skip_robot ? 1 : null,
                province: '',
                naics_code: '',
                institution_type: '',
                address_line_1: '',
                address_line_2: '',
                description: '', city: '',
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
                email: '',
                last_name: '',
                first_name: '',
                location: '',
                province2: '',
                job_title: '',
                role_name: '',
                showLabel: false,
                digital_account_opening: ''
            }
        },
        watch: {

        },
        computed: {
            allowStep2() {
                let canGoStep2 = this.canGoStep2();
                console.log(canGoStep2);
                return canGoStep2;
            },
            allowSubmit() {
                let canSubmit = this.canSubmit();
                // console.log(canSubmit);
                return canSubmit;
            }
        },
        methods: {
            updateResetRecaptcha(response) {
                this.resetRecaptcha = false;
            },
            gotBack() {
                this.step = 1;
            },
            canGoStep2() {
                return true
            },
            canSubmit() {
                this.$emit('submitTwo');
                console.log(this.recaptchaToken);
                if (this.email && this.$refs.Step2.validEmail() && this.$refs.Step2.passWordMatch() && this.first_name && this.last_name && this.timezone && this.province2 && this.location && this.job_title && this.department && this.user_telephone && this.recaptchaToken) {
                    return true;
                }
                return false;
            },
            selectOrganizationType(type) {
                this.organizationType = type;
            },
            showPassword() {
                this.passwordVisible = !this.passwordVisible;
            },
            checkRequired() {
                if (this.step === 1) {
                    this.$emit('submit');
                    if (this.organizationType && this.institution_name && this.city && this.province && this.address_line_1 && this.postal_code && this.telephone &&
                        ((this.organizationType.toUpperCase() === 'BANK' && this.institution_type) || this.organizationType.toUpperCase() === 'DEPOSITOR') &&
                        (this.organizationType.toUpperCase() === 'DEPOSITOR' || this.organizationType.toUpperCase() === 'BANK')) {
                        return true;
                    }
                    return false;
                }

                return false;
            },
            async doSubmit() {
                let this_ = this;
                if (this_.step === 1) {
                    if (!this.checkRequired()) {

                        return;
                    }
                    let response = this_.$refs.Step1.crop();
                    if (response?.canvas) {
                        this_.profile_image = await new Promise(blob => response.canvas.toBlob((blob), response.type));
                    }
                    if (this_.canGoStep2()) {
                        this.step = 2;
                    }
                    return;
                }

                if (this_.canSubmit()) {
                    this_.submitButtonText = "Please wait..";
                    this_.submitButtonSpinner = true;
                    const formData = new FormData();
                    formData.append("userType", this_.organizationType);
                    formData.append("institution_name", this_.institution_name);
                    formData.append("profile_image", this_.profile_image);
                    formData.append("recaptcha", this_.recaptchaToken);
                    formData.append("province", this_.province);
                    formData.append("naics_code", this_.naics_code?.id);
                    formData.append("institution_type", this_.institution_type?.id);
                    formData.append('description', this_.description);
                    formData.append("address", this_.address_line_1);
                    formData.append("address2", this_.address_line_2);
                    formData.append("city", this_.city);
                    formData.append("postal", this_.postal_code);
                    formData.append("telephone", this_.telephone);
                    formData.append("website", this_.website);
                    formData.append("potential_deposit", this_.potential_deposit?.id);
                    formData.append("conf_password", this_.conf_password);
                    formData.append("pass", this_.password);
                    formData.append("timezone", this_.timezone?.value);
                    formData.append("department", this_.department);
                    formData.append("user_telephone", this_.user_telephone);
                    formData.append("email", this_.email);
                    formData.append("last_name", this_.last_name);
                    formData.append("first_name", this_.first_name);
                    formData.append("location", this_.location);
                    formData.append("province2", this_.province2);
                    formData.append("job_title", this_.job_title);
                    formData.append("automated_test", this.skip_robot);
                    formData.append("referral", this_.referral);
                    formData.append("wholesale_deposit_portfolio_id", this_.wholesale_deposit_portfolio_id?.id);
                    formData.append("digital_account_opening", this_.digital_account_opening);
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
                            window.location.href = '/dashboard';//this_.homeRoute; // redirect user to dashboard to finish up adding users
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
                        this.resetRecaptcha = true;
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
            }
        }
    }
</script>