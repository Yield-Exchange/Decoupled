<template>
    <div class="SignInContainer col-md-6">
        <h2>{{ !isFromLoggedInUser ? 'Complete Reset Password' : 'Set Password' }}</h2>
        <div class="row" style="margin-top: 20px" v-if="formErrors">
            <b-alert show v-bind:variant="formAlertType ? formAlertType : 'warning'">
            <p v-if="Array.isArray(formErrors)" v-for="value in formErrors"> {{ value  }}</p>
            <p v-if="!Array.isArray(formErrors)"> {{ formErrors  }}</p>
            </b-alert>
        </div>

        <div class="row text-left">
            <b-form-input id="password"
                          v-bind:type="passwordVisible ? 'text' : 'password'"
                          v-model="password"
                          maxlength="25"
                          placeholder="Password"
                          @keydown="comparePasswordStringLength"
                          @keyup.enter="attemptChangePassword"
            >
            </b-form-input>
            <password v-model="password"
                      :strength-meter-only="true"
                      @score="showScore"
                      @feedback="showFeedback"
            />
            <b-alert v-if="passErrors" show variant="warning">{{ passErrors }}</b-alert>
        </div>

        <div class="row text-left">
            <b-form-input id="conf_password"
                          v-bind:type="passwordVisible ? 'text' : 'password'"
                          v-model="conf_password"
                          maxlength="25"
                          placeholder="Confirm Password"
                          @keyup="comparedConfirmPassword"
                          @keyup.enter="attemptChangePassword"
            >
            </b-form-input>
        </div>
        <br/>
        <div class="row text-left">
            <div class="form-check" style="padding-left:12px">
                <label class="form-check-label" style="font-size: 12px;">
                    <input type="checkbox" class="form-input-styled" @click="showPassword()" /> Show Password
                </label>
            </div>
        </div>

        <div class="row text-left">
            <div class="col-md-6">
                <b-button variant="link" v-bind:href="loginRoute" v-if="!isFromLoggedInUser"
                          style="font-size:13px;padding-left:0"
                >Sign In Instead</b-button>
            </div>
            <div class="col-md-6 text-right">
                <b-button v-bind:variant="(!this.password || !this.conf_password || this.password !== this.conf_password) ? 'secondary' : 'primary'"
                          v-bind:disabled="!this.password || !this.conf_password || this.password !== this.conf_password || submitButtonSpinner"
                          style="font-size:13px;border-radius:20px"
                          @click="attemptChangePassword"
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
    import Password from 'vue-password-strength-meter'
    export default {
        components: { Password },
        props: ['passwordChangeRoute','loginRoute','resetCode','isFromLoggedInUser'],
        data() {
            return {
                password: '',
                conf_password: '',
                formErrors:'',
                passErrors:'',
                formAlertType: 'warning',
                submitButtonText: 'Continue',
                submitButtonSpinner: false,
                passwordVisible:false,
                passwordMatches:false
            }
        },
        computed: {

        },
        methods: {
            comparedConfirmPassword(){
                this.passwordMatches = this.password && this.conf_password && this.password === this.conf_password;
                if (!this.passwordMatches){
                    this.formErrors="The passwords you have entered do not match.";
                }else{
                    this.formErrors="";
                }
            },
            comparePasswordStringLength(){
                this.passErrors="";
                if(this.password.length ==25){
                    this.passErrors="Password cannot be more than 25 characters";
                    setTimeout(() => { this.passErrors = ""; }, 4000);
                }
            },
            showFeedback ({suggestions, warning}) {
                this.formErrors = "";
                if(suggestions){
                    for (let i = 0; i < suggestions.length ; i++) {
                        this.formErrors += suggestions[i]+" ";
                    }
                }
            },
            showScore (score) {
            },
            showPassword(){
                this.passwordVisible = !this.passwordVisible;
            },
            attemptChangePassword(){
                if ( this.password ){
                    this.submitButtonText = "Please wait..";
                    this.submitButtonSpinner = true;
                    axios.post(this.passwordChangeRoute,{
                        pass: this.password,
                        code: this.resetCode,
                        cpass: this.conf_password
                    }).then(response => {
                        let data = response?.data;
                        this.formErrors = data.message;
                        this.submitButtonSpinner = false;
                        if(data.success){
                            this.$swal('',data.message,'').then(()=> {
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
                }
            }
        }
    }
</script>