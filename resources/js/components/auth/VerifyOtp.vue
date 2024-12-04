<template>
    <div class="SignInContainer col-md-6">
        <h2>2-step verification</h2>
        <span class="form-text text-muted text-center">Please check your email to find your pin. This extra step proves its really you trying to sign in.</span>
        <div class="row" style="margin-top: 20px" v-if="formErrors">
            <b-alert show v-bind:variant="formAlertType ? formAlertType : 'warning'">{{ formErrors }}</b-alert>
        </div>

        <div class="row text-left" style="margin-top:25px;margin-bottom:0">
            <div class="form-group form-group-feedback form-group-feedback-right">
                <b-form-input id="pin"
                              type="text"
                              v-model="pin"
                              placeholder="Pin"
                              @keyup.enter="verifyPin"
                >
                </b-form-input>
            </div>
        </div>
        <b-row>
            <b-col sm="6" class="text-left">
                <b-button variant="link" v-bind:href="loginRoute"
                          style="font-size:13px;padding-left:0"
                >Sign In</b-button>
            </b-col>
            <b-col sm="6" class="text-right">
                <b-button variant="link" @click="resendPin"
                        style="font-size:13px;border:none"
                        href="javascript:void()"
                        v-bind:disabled="resendPinButtonSpinner"
                >
                <b-spinner variant="primary" label="Loading"
                            style="width: 1.3rem; height: 1.3rem;margin-right:5px"
                            v-if="resendPinButtonSpinner"
                >
                </b-spinner>
                {{resendPinButtonText}}
            </b-button>
            </b-col>
        </b-row>
        <div class="row text-left">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right">
                <b-button v-bind:variant="!this.pin ? 'secondary' : 'primary'"
                          v-bind:disabled="!this.pin || submitButtonSpinner"
                          style="font-size:13px;border-radius:20px"
                          @click="verifyPin"
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
    export default {
        props: ['loginRoute','resendPinRoute','verifyOtpRoute','dashboardRoute','userId'],
        data() {
            return {
                pin: '',
                formErrors:'',
                formAlertType: 'warning',
                submitButtonText: 'Continue',
                submitButtonSpinner: false,
                resendPinButtonText: 'Resend PIN',
                resendPinButtonSpinner: false,
            }
        },
        computed: {

        },
        methods: {
            resendPin(){
                this.resendPinButtonText = "Please wait..";
                this.resendPinButtonSpinner = true;
                axios.post(this.resendPinRoute,{
                    user_id: this.userId,
                }).then(response => {
                    let data = response?.data;
                    this.formErrors = data.message;
                    this.resendPinButtonSpinner = false;
                    if(data.success){
                        this.pin="";
                        // this.$swal('',data.message,'').then(()=> {
                            // window.location.reload();
                        // });
                    }else{
                        this.formAlertType = data?.alert_class?.replace("alert-", "");
                    }
                    this.resendPinButtonText='Resend PIN';
                }).catch(error =>{
                    this.formErrors = error?.response?.data?.message;
                    this.formAlertType = error?.response?.data?.alert_class?.replace("alert-","");
                    this.resendPinButtonText='Resend PIN';
                    this.resendPinButtonSpinner = false;
                });
            },
            verifyPin(){
                if (this.pin){
                    this.submitButtonText = "Please wait..";
                    this.submitButtonSpinner = true;
                    axios.post(this.verifyOtpRoute,{
                        pin: this.pin,
                        user_id: this.userId
                    }).then(response => {
                        let data = response?.data;
                        this.submitButtonSpinner = false;
                        if(data.success){
                            // this.$swal('',data.message,'').then(()=> {
                                window.location.href = this.dashboardRoute;
                            // });
                        }else{
                            this.formErrors = data.message;
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