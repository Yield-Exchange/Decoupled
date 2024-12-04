<template>
    <div class="row" style="width:80%">
        <h4 class="font-weight-bold text-center color-black" style="color: black"> Profile Settings</h4>
        <p class="text-center" style="font-weight: normal">Please confirm and update your profile details</p>
        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group row">
                            <div class="col-lg-8">
                                <div class="row" style="margin-top: 10px" v-if="this.formErrors">
                                    <b-alert show :variant="'info'">{{ this.formErrors }}</b-alert>
                                </div>
                            </div>
                        </div>
                    </div>

                    <sign-up-step2 :fromUserProfile="true" :timezones="this.timezones" :provinces="this.provinces"
                        :form-errors="formErrors" ref="Step2" :timezone.sync="timezone" :department.sync="department"
                        :password.sync="password" :conf_password.sync="conf_password"
                        :user_telephone.sync="user_telephone" :email.sync="email" :last_name.sync="last_name"
                        :first_name.sync="first_name" :location.sync="location" :province2.sync="province2"
                        :job_title.sync="job_title" :recaptcha-token.sync="recaptchaToken"
                        :recaptcha-key="this.recaptchaKey" :terms-route="this.termsRoute" :role_name="role_name"
                        :show-label="showLabel" :skip_robot="false">
                    </sign-up-step2>
                </div>
            </div>
        </div>

        <b-row style="margin-left: 1%;padding:0">
            <b-col cols="6" style="padding-left: 0">
                <b-button :variant="'success'" :disabled="submitButtonResetPassSpinner" :size="'md'"
                    style="font-size:15px;border-radius:20px" @click="doResetPassword">
                    <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                        v-if="submitButtonResetPassSpinner">
                    </b-spinner>
                    {{ submitButtonResetPassText }}
                </b-button>
            </b-col>
            <b-col cols="6" class="text-right" style="padding-right: 0">
                <b-button :variant="'primary'" :disabled="submitButtonSpinner" :size="'lg'"
                    style="font-size:15px;border-radius:20px" @click="doSubmit">
                    <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                        v-if="submitButtonSpinner">
                    </b-spinner>
                    {{ submitButtonText }}
                </b-button>
            </b-col>
        </b-row>
    </div>
</template>
<style>
.swal-button-actions {
    flex-direction: row-reverse !important;
    display: flex !important;
}
</style>
<style scoped>
    .card-header {
        padding-top: 0 !important;
    }

    .dashboard-body .card-body {
        padding: 0;
    }

    .dashboard-body .card {
        border-radius: 10px;
    }

    .btn.btn-secondary[disabled] {
        background-color: #979797;
        color: black;
    }
</style>
<script>

import {confirmLeavePage} from "../../utils/GlobalUtils";
export default {
    mounted() {
        confirmLeavePage(this,document);
    },
    created() {
        // console.log(JSON.parse(this.user))  //undefined;
    },
    props: {
        timezones: {
            type: String
        },
        provinces: {
            type: String
        },
        user: {
            type: String
        },
        updateProfileRoute: {
            type: String
        },
        resetPasswordRoute: {
            type: String
        },
        loggedInAs: {
            type: String
        }
    },
    data() {
        return {
            formErrors: '',
            formAlertType: 'warning',
            submitButtonText: 'Save',
            submitButtonSpinner: false,
            submitButtonResetPassText: 'Reset Password',
            submitButtonResetPassSpinner: false,
            conf_password: '',
            password: '',
            timezone: JSON.parse(this.user).current_timezone,
            department: JSON.parse(this.user).demographic_data?.department,
            user_telephone: JSON.parse(this.user).demographic_data?.phone,
            email: JSON.parse(this.user).email,
            last_name: JSON.parse(this.user).lastname,
            first_name: JSON.parse(this.user).firstname,
            location: JSON.parse(this.user).demographic_data?.city,
            province2: JSON.parse(this.user).demographic_data?.province,
            job_title: JSON.parse(this.user).demographic_data?.job_title,
            recaptchaToken: '',
            recaptchaKey: '',
            termsRoute: '',
            role_name: JSON.parse(this.user).role_name,
            showLabel: true
        }
    },
    watch: {

    },
    computed: {
        allowSubmit() {
            return this.canSubmit();
        }
    },
    methods: {
        canSubmit() {
            this.$emit('submitTwo');
            return this.first_name && this.timezone && this.department && this.user_telephone && this.location &&
                this.last_name && this.province2 && this.job_title
        },
        async doSubmit() {
            if (!this.canSubmit()) {
                return;
            }

            this.submitButtonText = "Please wait..";
            this.submitButtonSpinner = true;
            axios.post(this.updateProfileRoute, {
                email: this.email,
                firstname: this.first_name,
                lastname: this.last_name,
                phone: this.user_telephone,
                job_title: this.job_title,
                department: this.department,
                city: this.location,
                location: this.province2,
                timezone: this.timezone?.value,
            }).then(response => {
                let data = response?.data;
                this.$swal({
                    title: "Profile settings update.",
                    text: data.message,
                    confirmButtonText: 'Close'
                }).then(() => {
                    window.location.reload();
                });
                this.submitButtonSpinner = false;
                this.submitButtonText = 'Save';
            }).catch(error => {
                if (error?.response?.status === 419) {
                    this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                } else {
                    this.formErrors = error?.response?.data?.message;
                }
                this.submitButtonText = 'Save';
                this.submitButtonSpinner = false;
            });
        },
        async doResetPassword() {
            this.submitButtonResetPassText = "Please wait..";
            this.submitButtonResetPassSpinner = true;
            axios.post(this.resetPasswordRoute, {
                email: this.email,
                // loginAs: this.user.is_super_admin ? 'Admin' : this.organizationType,
                fromLoggedInUser: 1,
                loginAs: this.loggedInAs
            }).then(response => {
                let data = response?.data;
                this.$swal({
                    title: data.message_title,
                    text: data.message,
                    confirmButtonText: 'Close'
                });
                this.submitButtonResetPassSpinner = false;
                this.submitButtonResetPassText = 'Reset Password';
            }).catch(error => {
                if (error?.response?.status === 419) {
                    this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                } else {
                    this.formErrors = error?.response?.data?.message;
                }
                this.submitButtonText = 'Reset Password';
                this.submitButtonResetPassSpinner = false;
            });
        }
    }
}
</script>
