<template>
    <div class="SignInContainer col-md-6">
        <h2>Sign In</h2>
        <div class="row" v-if="from_signup">
            <b-alert show variant="info">Please check your email for credentials</b-alert>
        </div>
        <div class="row" style="margin-left: 0;margin-right: 0;" v-if="!isAdmin && !depositorLogin">
            <div class="col-md-6 text-center p-1 SignInUserType-t-buttons"
                v-bind:style="(organizationType && organizationType === 'Depositor' ? 'background:#3656A6;color:white;' : '') + ' border-right: none; border-top-left-radius: 15px;border-bottom-left-radius: 15px;'"
                @click="selectOrganizationType('Depositor')">Depositor</div>
            <div class="col-md-6 text-center p-1 SignInUserType-t-buttons"
                v-bind:style="(organizationType && organizationType === 'Bank' ? 'background:#3656A6;color:white;' : '') + ' border-top-right-radius: 15px;border-bottom-right-radius: 15px;'"
                @click="selectOrganizationType('Bank')">Financial institutions</div>
        </div>

        <div class="row" style="margin-top: 20px" v-if="formErrors">
            <b-alert show v-bind:variant="formAlertType ? formAlertType : 'warning'">{{ formErrors }}</b-alert>
        </div>

        <div class="row col-md-12" v-if="!organizationType && !isAdmin">
            <p style="font-size: 13px;margin-top:10px">Please select your organization type</p>
        </div>

        <div class="row text-left" v-if="organizationType || isAdmin" style="margin-top:20px">
            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="hidden" name="loginAs" v-model="organizationType" />
                <input type="email" name="email" class="form-control" @keydown="compareEmailStringLength"
                    @keyup.enter="attemptLogin" v-model="email" maxlength="50" placeholder="Email" required />
                <div class="form-control-feedback">
                    <i style="margin-top:10px;" class="icon-user text-muted"></i>
                </div>
                <b-alert v-if="emailErrors" show variant="warning">{{ emailErrors }}</b-alert>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-right">
                <input v-bind:type="passwordVisible ? 'text' : 'password'" v-model="password"
                    @keydown="comparePasswordStringLength" @keyup.enter="attemptLogin" name="password" maxlength="25"
                    class="form-control" id="password" placeholder="Password" required />
                <div class="form-control-feedback">
                    <i style="margin-top:10px;" class="icon-lock2 text-muted"></i>
                </div>
                <b-alert v-if="passErrors" show variant="warning">{{ passErrors }}</b-alert>
            </div>

        </div>

        <div class="row text-left">
            <b-row>
                <b-col sm="6">
                    <b-button variant="link"
                        v-bind:href="this.loginRoute + '?action=resetPassword&loginAs=' + this.organizationType"
                        style="font-size:13px;padding-left:0" v-bind:disabled="!this.organizationType && !isAdmin">
                        Forgot password</b-button>
                </b-col>
                <b-col sm="6" class="text-right" v-if="organizationType || isAdmin">
                    <div class="row">
                        <div class="form-check" style="padding-left:12px">
                            <label class="form-check-label" style="font-size: 12px;">
                                <input type="checkbox" class="form-input-styled" @click="showPassword()" /> Show
                                Password
                            </label>
                        </div>
                    </div>
                </b-col>
            </b-row>

            <b-row>
                <b-col sm="8">
                    <b-button variant="link" v-bind:href="this.registerRoute" style="font-size:13px;padding-left:0">
                        <span style="color:black">Dont have an account?</span> Create Account
                    </b-button>
                </b-col>
                <b-col sm="4" class="text-right">
                    <b-button v-bind:variant="(!this.email || !this.password) ? 'secondary' : 'primary'"
                        v-bind:disabled="!this.email || !this.password || submitButtonSpinner"
                        style="font-size:13px;border-radius:20px" @click="attemptLogin">
                        <b-spinner variant="light" label="Loading"
                            style="width: 1.3rem; height: 1.3rem;margin-right:5px" v-if="submitButtonSpinner">
                        </b-spinner>
                        {{ submitButtonText }}
                    </b-button>
                </b-col>
            </b-row>
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
    props: ['loginRoute', 'isAdmin', 'registerRoute', 'skip_robot'],
    data() {

        // Get the current URL
        const url = new URL(window.location.href);
        // Get a specific parameter by name
        const type = url.searchParams.get('type');
        const from_signup = url.searchParams.get('from_signup');

        return {
            organizationType: type ? type : '',
            passwordVisible: false,
            email: '',
            password: '',
            formErrors: '',
            emailErrors: '',
            passErrors: '',
            formAlertType: 'warning',
            submitButtonText: 'Continue',
            submitButtonSpinner: false,
            depositorLogin: type,
            from_signup: from_signup
        }
    },
    computed: {

    },
    methods: {
        selectOrganizationType(type) {
            // console.log(type);
            this.organizationType = type;
        },
        showPassword() {
            this.passwordVisible = !this.passwordVisible;
        },
        comparePasswordStringLength() {
            this.passErrors = "";
            if (this.password.length == 25) {
                this.passErrors = "Password cannot be more than 25 characters";
                setTimeout(() => { this.passErrors = ""; }, 4000);
            }
        },
        compareEmailStringLength() {
            if (this.email.length == 50) {
                this.emailErrors = "Email cannot be more than 50 characters.";
                setTimeout(() => { this.emailErrors = ""; }, 4000);
            }
        },
        attemptLogin() {
            if (this.email && this.password) {
                this.submitButtonText = "Please wait..";
                this.submitButtonSpinner = true;
                axios.post(this.loginRoute, {
                    email: this.email,
                    loginAs: this.isAdmin ? 'Admin' : this.organizationType,
                    password: this.password,
                    automated_test: this.skip_robot
                }).then(response => {
                    let data = response?.data;
                    this.formAlertType = data?.alert_class?.replace("alert-", "");
                    if (data.success) {
                        this.formErrors = "";
                        if (this.skip_robot){
                            window.location.href = "/dashboard";
                            return;
                        }
                        window.location.href = this.loginRoute + "?action=verifyOtp";
                    } else {
                        this.formErrors = data.message;
                        this.submitButtonSpinner = false;
                    }
                    this.submitButtonText = 'Continue';
                }).catch(error => {
                    if (error?.response?.status === 419) {
                        this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        this.formErrors = error?.response?.data?.message;
                    }
                    this.formAlertType = error?.response?.data?.alert_class?.replace("alert-", "");
                    this.submitButtonText = 'Continue';
                    this.submitButtonSpinner = false;
                });
            }
        }
    }
}
</script>
