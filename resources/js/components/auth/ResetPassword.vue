<template>
    <div class="SignInContainer col-md-6">
        <h2>Reset Password</h2>
        <span class="form-text text-muted text-left">Enter your registered email address and we'll send you the link to reset the password. </span>
        <div class="row" style="margin-top: 20px" v-if="formErrors">
            <b-alert show v-bind:variant="formAlertType ? formAlertType : 'warning'">{{ formErrors }}</b-alert>
        </div>

        <div class="row text-left" style="margin-top:20px">
            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="hidden" name="loginAs" v-model="loginAs" />
                <b-form-input id="email"
                              type="email"
                              v-model="email"
                              maxlength="50"
                              @keydown="compareEmailStringLength"
                              placeholder="Email"
                              @keyup.enter="attemptResetPassword"
                >
                </b-form-input>
                <b-alert v-if="emailErrors" show variant="warning">{{ emailErrors }}</b-alert>
            </div>
        </div>

        <div class="row">
            <vue-recaptcha ref="recaptcha"
                           :sitekey="recaptchaKey"
                           @verify="verifyRecaptcha"
                           @error="errorRecaptcha"

            />
        </div>

        <div class="row text-left">
            <div class="col-md-6">
                <b-button variant="link" v-bind:href="loginRoute"
                          style="font-size:13px;padding-left:0"
                >Sign In Instead</b-button>
            </div>
            <div class="col-md-6 text-right">
                <b-button v-bind:variant="(!this.email || !this.recaptchaToken) ? 'secondary' : 'primary'"
                          v-bind:disabled="!this.email || !this.recaptchaToken || submitButtonSpinner"
                          style="font-size:13px;border-radius:20px"
                          @click="attemptResetPassword"
                >
                    <b-spinner variant="light" label="Loading"
                               style="width: 1.3rem; height: 1.3rem;margin-right:5px"
                               v-if="submitButtonSpinner"
                    >
                    </b-spinner>
                    {{ submitButtonText }}
                </b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import { VueRecaptcha } from 'vue-recaptcha';
    export default {
        components: { VueRecaptcha },
        props: ['resetRoute','loginAs','loginRoute','recaptchaKey','skip_robot'],
        data() {
            console.log(this.skip_robot);
            return {
                email: '',
                formErrors:'',
                emailErrors:'',
                formAlertType: 'warning',
                submitButtonText: 'Continue',
                submitButtonSpinner: false,
                recaptchaToken: this.skip_robot  ? 1 : null,
                emailValidity: null
            }
        },
        computed: {

        },
        methods: {
            compareEmailStringLength(){
                if(this.email.length ==50){
                    this.emailErrors="Email cannot be more than 50 characters.";
                    setTimeout(() => { this.emailErrors = ""; }, 4000);
                }
            },
            validEmail(){
                return String(this.email)
                    .toLowerCase()
                    .match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            },
            errorRecaptcha(response){
                // console.log(response);
            },
            verifyRecaptcha(response){
                // console.log(response);
                this.recaptchaToken = response;
            },
            attemptResetPassword(){
                this.emailValidity = this.validEmail();
                if (this.email && this.emailValidity){

                    if(!this.recaptchaToken){
                        this.formErrors = "Enter a valid email";
                        this.formAlertType = "danger";
                        return;
                    }

                    this.submitButtonText = "Please wait..";
                    this.submitButtonSpinner = true;
                    axios.post(this.resetRoute,{
                        email: this.email,
                        loginAs: this.loginAs,
                        recaptcha: this.recaptchaToken,
                        automated_test: this.skip_robot
                    }).then(response => {
                        let data = response?.data;
                        this.formErrors = data.message;
                        this.submitButtonSpinner = false;
                        if(data.success){
                            this.$swal(data.message_title,data.message,'').then(()=> {
                                window.location.href = this.loginRoute;
                            });
                        }else{
                            this.formAlertType = data?.alert_class?.replace("alert-", "");
                        }
                        this.submitButtonText='Continue';
                    }).catch(error =>{
                        if(error?.response?.status === 419){
                            this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                        }else {
                            this.formErrors = error?.response?.data?.message;
                        }

                        // this.formErrors = error?.response?.data?.message;
                        this.formAlertType = error?.response?.data?.alert_class?.replace("alert-","");
                        this.submitButtonText='Continue';
                        this.submitButtonSpinner = false;
                    });
                }else{
                    if (!this.emailValidity) {
                        this.formErrors = "Enter a valid email";
                        this.formAlertType = "danger";
                    }else{
                        this.formErrors = "";
                        this.formAlertType = "warning";
                    }
                }
            }
        }
    }
</script>
