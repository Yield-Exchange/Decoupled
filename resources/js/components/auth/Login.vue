<template>
    <div class="w-100 d-flex gap-4" style=" height: calc(100vh + 0px);overflow: hidden !important;">
        <div class="w-50 sign-in-half-one d-flex flex-column justify-content-center align-items-center gap-2">
            <div>
                <img src="/assets/signup/yie-logo-sign-in.svg" alt="Yield Exchange Logo">
            </div>
            <div>
                <p class="p-0 m-0 shopping-rates-sign-in">Simplifying the process of <br> shopping for rates.</p>
            </div>
        </div>
        <div class="w-50 d-flex flex-column justify-content-between" style="margin: 20px 20px 0 0;">
            <div class="w-100 d-flex gap-3 justify-content-end align-items-center">

                <div class="mnavbar-login-style" @click="redirect('home')">
                    Home
                </div>
                <div class="mnavbar-login-style" @click="redirect('new-acc')">
                    Sign Up
                </div>
            </div>
            <div class="w-100 d-flex flex-column justify-content-center">
                <div class="w-100" style="max-width: 1020px !important;">

                    <!-- login -->
                    <div v-if="step == 'login'"
                        class="d-flex h-100 flex-column justify-content-center align-items-center">
                        <div class="sign-in-card">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="/assets/signup/signin-in-key.svg" alt="Yield Exchange Logo">.
                                <p class="p-0 m-0 main-sign-in-action"> Sign in to your account</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-20 ">
                                    <FormLabelRequired labelText="Email Address" :required="false"
                                        :showHelperText="false" helperText="Email Address " helperId="PDSHId" />
                                    <CustomInput inputType="text" inputStyle="font-size:14px !important"
                                        p-style="width:100%" id="email" name="Enter email address"
                                        :has-validation="true" @inputChanged="emailChange($event)" input-type="text"
                                        :defaultValue="email" :hasSpecificError="emailerror" :maxlength="50" />
                                    <div v-if="emailerror" class="error-message">
                                        {{ emailerror }}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-20 ">
                                    <FormLabelRequired labelText="Password" :required="false" :showHelperText="false"
                                        helperText="Email Address " helperId="PDSHId" />
                                    <CustomInput :inputType="showpassword ? 'text' : 'password'"
                                        inputStyle="font-size:14px !important" p-style="width:100%" id="password"
                                        name="Enter your password" :has-validation="true"
                                        @inputChanged="passwordChange($event)" input-type="text"
                                        :defaultValue="password" :hasSpecificError="passworderror" :maxlength="50" />
                                    <div v-if="passworderror" class="error-message">
                                        {{ passworderror }}
                                    </div>
                                </div>


                                <div class="col-md-12 mb-20 d-flex justify-content-between align-items-center w-100">
                                    <p class="p-0 m-0 forgot-password" @click="redirect('forgot-pass')">Forgot Password?
                                    </p>
                                    <div class="d-flex justify-content-end gap-2">
                                        <input type="checkbox" v-model="showpassword" name="show-pass" id=""> Show
                                        Password
                                    </div>
                                </div>
                                <div class="col-md-12 mb-20 d-flex justify-content-start w-100">
                                    <p class="make-a-request"> Don't have an account with us? <span
                                            style="color: #5063f4;font-weight: 500;cursor: pointer;"
                                            @click="redirect('new-acc')">Sign Up</span> </p>
                                </div>
                                <div class="col-md-12 mb-20 d-flex justify-content-end w-100">
                                    <CustomSubmit @action="Login" :is-loading="submitting" title="Login" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- otp -->
                    <div v-if="step == 'otp'"
                        class=" d-flex h-100 flex-column justify-content-center align-items-center">
                        <div class="sign-in-card">
                            <div class="d-flex justify-content-center align-items-center mb-20">
                                <img src="/assets/signup/otp-verification.svg" alt="Yield Exchange Logo">.
                                <p class="p-0 m-0 main-sign-in-action"> Account Verification</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-20 d-flex justify-content-start w-100">
                                    <p class="make-a-request"> Please enter the 6-digit PIN that was sent to your email
                                        account to continue. </p>
                                </div>
                                <div class="col-md-12 mb-20 ">
                                    <FormLabelRequired labelText="Enter PIN" :required="false" :showHelperText="false"
                                        helperText="Email Address " helperId="PDSHId" />
                                    <CustomInput inputType="text" inputStyle="font-size:14px !important"
                                        p-style="width:100%" id="newpin" name="Enter one time PIN"
                                        :has-validation="true" @inputChanged="pinChange($event)" input-type="text"
                                        :defaultValue="PIN" :hasSpecificError="PINerror" :maxlength="10" />
                                    <div v-if="PINerror" class="error-message">
                                        {{ PINerror }}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-20 d-flex justify-content-start align-items-center w-100">

                                    <p class="make-a-request">
                                        <span v-if="resending"
                                            style="color: #5063f4;font-weight: 500;cursor: pointer;">Resending
                                            PIN</span>
                                        <span @click="resendPin" v-else
                                            style="color: #5063f4;font-weight: 500;cursor: pointer;">Resend
                                            PIN</span>
                                        <span v-if="resending" class="spinner-border blue-spinner" role="status">
                                        </span>
                                    </p>
                                    <!-- <p class="make-a-request"> The Pin is valid for <span
                                            style="color: #5063f4;font-weight: 500;">
                                            30
                                        </span>
                                        minutes
                                    </p> -->

                                </div>
                                <div class="col-md-12 mb-20 d-flex justify-content-end w-100">
                                    <CustomSubmit :is-loading="submitting" @action="verifyPin" title="Verify" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- reset password -->
                    <div v-if="step == 'reset-pass'"
                        class="d-flex h-100 flex-column justify-content-center align-items-center">
                        <div class="sign-in-card">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="/assets/signup/reset-password.svg" alt="Yield Exchange Logo">.
                                <p class="p-0 m-0 main-sign-in-action"> Reset your password</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <FormLabelRequired labelText="New Password" :required="false"
                                        :showHelperText="false" helperText="Email Address " helperId="PDSHId" />
                                    <CustomInput :inputType="showpassword ? 'text' : 'password'"
                                        inputStyle="font-size:14px !important" p-style="width:100%" id="newpass"
                                        name="Enter your new password" :has-validation="true"
                                        @inputChanged="passwordChange($event)" input-type="text"
                                        :defaultValue="password" :hasSpecificError="passworderror" :maxlength="50" />
                                    <p v-if="passworderror" class="error-message">
                                        {{ passworderror }}
                                    </p>
                                </div>
                                <div class="col-md-12 mb-20 ">
                                    <div class="d-flex mt-2 gap-2 mx-2 justify-content-between">
                                        <div class="strength-indicator " v-for="i in 6" :key="i"
                                            :class="currentIndicator">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 ">
                                    <FormLabelRequired labelText="Confirm Password" :required="false"
                                        :showHelperText="false" helperText="Email Address " helperId="PDSHId" />
                                    <CustomInput :inputType="showpassword ? 'text' : 'password'"
                                        inputStyle="font-size:14px !important" p-style="width:100%" id="confirmpass"
                                        name="Confirm your new password" :has-validation="true"
                                        @inputChanged="confirmPasswordChange($event)" input-type="text"
                                        :defaultValue="confirmpass" :hasSpecificError="confirmpasserror"
                                        :maxlength="50" />
                                    <div v-if="confirmpasserror" class="error-message">
                                        {{ confirmpasserror }}
                                    </div>
                                </div>
                                <div class="col-md-12 mb-20 ">
                                    <div class="d-flex mt-2 gap-2 mx-2 justify-content-between">
                                        <div class="strength-indicator" v-for="i in 6" :key="i"
                                            :class="{ 'indicate-success': (confirmpass == password) && (confirmpass != null || password != null), 'indicate-low': !(confirmpass == password) && confirmpass !== '' && confirmpass != null, 'indicate-none': confirmpass == null || confirmpass == '' }">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-20 d-flex justify-content-end align-items-center w-100">
                                    <div class="d-flex justify-content-end gap-2">
                                        <input type="checkbox" v-model="showpassword" name="show-pass" id=""> Show
                                        Password
                                    </div>
                                </div>

                                <div class="col-md-12 mb-20 d-flex justify-content-end w-100">
                                    <CustomSubmit :is-loading="submitting" @action="ChangePassword" title="Submit" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- request reset password -->
                    <div v-if="step == 'forgot-pass'"
                        class="d-flex h-100 flex-column justify-content-center align-items-center">
                        <div class="sign-in-card">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="/assets/signup/reset-password.svg" alt="Yield Exchange Logo">.
                                <p class="p-0 m-0 main-sign-in-action"> Reset your password</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12  my-4 d-flex justify-content-start w-100">
                                    <p class="make-a-request"> Please enter your email address. If it matches one in our
                                        system,
                                        we
                                        will send you a password reset link. </p>
                                </div>
                                <div class="col-md-12 mb-20 ">
                                    <FormLabelRequired labelText="Email Address" :required="false"
                                        :showHelperText="false" helperText="Email Address " helperId="PDSHId" />
                                    <CustomInput inputType="text" inputStyle="font-size:14px !important"
                                        p-style="width:100%" id="emailaddress" name="Enter email address"
                                        :has-validation="true" @inputChanged="emailChange($event)" input-type="text"
                                        :defaultValue="email" :hasSpecificError="emailerror" :maxlength="50" />
                                    <div v-if="emailerror" class="error-message">
                                        {{ emailerror }}
                                    </div>
                                </div>
                                <div class="col-md-12 mb-20 d-flex justify-content-end w-100">
                                    <CustomSubmit @action="resetPassword" title="Submit" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- confirm email -->
                    <div class="d-none d-flex h-100 flex-column justify-content-center align-items-center">
                        <div class="sign-in-card">
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="/assets/signup/reset-password.svg" alt="Yield Exchange Logo">.
                                <p class="p-0 m-0 main-sign-in-action"> Reset your password</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12  my-4 d-flex justify-content-start w-100">
                                    <p class="make-a-request">Request has been submitted </p>
                                </div>
                                <div class="col-md-12 mb-20 ">
                                    <div class="d-flex justify-content-center align-items-center email-confirm">
                                        mike@gmail.com
                                    </div>
                                </div>

                                <div class="col-md-12  my-4 d-flex justify-content-start w-100">
                                    <p class="make-a-request">
                                        Click the link in the email to verify your address and set your password. If you
                                        don’t
                                        see
                                        the email, please check your spam or junk folders. If it's still not there, the
                                        email
                                        address might not be registered in our system.
                                    </p>
                                </div>
                                <div class="col-md-12 mb-20 d-flex justify-content-end w-100">
                                    <CustomSubmit @action="" title="Login" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <ActionMessage style="width: 600px;" @closedSuccessModal="closeSuccess($event)" @btnTwoClicked=""
                        @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" :title="successTitle"
                        btnOneText="" btnTwoText="" :showm="success">
                        <div class="ml-5 description-text-withdraw ">{{ successDesc }}</div>


                    </ActionMessage>
                    <ActionMessage style="width: 600px;" @closedSuccessModal="pinresent = false" @btnTwoClicked=""
                        @btnOneClicked="pinresent = false" icon="/assets/signup/success_promo.svg" title="New Pin Sent"
                        btnOneText="" btnTwoText="" :showm="pinresent">
                        <div class="ml-5 description-text-withdraw ">New 6 Digit pin code sent to your email</div>
                    </ActionMessage>
                    <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
                        @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
                        :title="failTitle" :showm="fail">
                        <div class="ml-5 description-text-withdraw ">{{ failDesc }}</div>
                    </ActionMessage>
                </div>
                <div>
                </div>

            </div>
            <div></div>
        </div>

    </div>

</template>

<script>
import CustomInput from '../shared/CustomInput.vue';
import FormLabelRequired from '../shared/formLabels/FormLabelRequired.vue';
import CustomSubmit from './signup/shared/CustomSubmit.vue';
import { validateEmail } from '../../utils/commonUtils'
import ActionMessage from './../shared/messageboxes/ActionMessageBox.vue'
import ChangePassword from './ChangePassword.vue';
export default {
    components: { CustomInput, FormLabelRequired, CustomSubmit, ActionMessage },
    props: ['loginRoute', 'isAdmin', 'passwordChangeRoute', 'resetCode', 'resetRoute', 'registerRoute', 'skip_robot', 'action', 'resendPinRoute', 'verifyOtpRoute', 'dashboardRoute', 'userId'],
    mounted() {
        // console.log(this.action, " actions is admin ", this.isAdmin)
        if (this.action == 'verifyOtp')
            this.step = 'otp'
        if (this.action == 'resetPassword')
            this.step = 'forgot-pass'
        if (this.action == 'changePassword')
            this.step = 'reset-pass'
    },

    data() {
        return {
            showpassword: false,
            email: null,
            emailerror: null,
            password: null,
            passwordstrength: 0,
            currentIndicator: 'indicate-none',
            passworderror: null,
            confirmpass: null,
            confirmpasserror: null,
            confirmpassstrength: null,
            PIN: null,
            PINerror: null,
            step: 'login',
            submitting: false,
            resending: false,
            pinresent: false,
            success: false,
            fail: false,
            successTitle: false,
            failTitle: false,
            successDesc: false,
            failDesc: false,
            redirect_to: null,
            genError: "Something's not right,please try again or contact info@yieldechange.ca"
        }
    },
    created() {
        document.addEventListener('keydown', (e) => {
            if (e.key == 'Enter') {
                setTimeout(() => {
                    this.handleKeyDown(e)
                }, 1000)
            }
        });
    },
    methods: {
        handleKeyDown(e) {
            if (e.key === 'Enter') {
                // console.log(this.step, "Key Down");
                if (this.step == 'login')
                    this.Login()
                if (this.step == 'otp')
                    this.verifyPin()
                if (this.step == 'forgot-pass')
                    this.resetPassword()
                if (this.step == 'reset-pass')
                    this.ChangePassword()
                // Optionally, prevent default behavior
                // e.preventDefault();
            }
        },
        closeSuccess(event, redirect) {
            this.success = false
        },
        strengthUpdate(value) {
            // this.passwordstrength = value
            this.currentIndicator = this.currentIndicators()
        },
        currentIndicators() {
            if (this.passwordstrength > 0 && this.passwordstrength <= 3) {
                return 'indicate-low'

            } else if (this.passwordstrength >= 4) {

                return 'indicate-success'
            } else {
                return 'indicate-none'
            }
        },
        redirect(value) {
            switch (value) {
                case 'forgot-pass':
                    this.step = 'forgot-pass';
                    break;
                case 'otp':
                    this.step = 'otp';
                    break;
                case 'new-acc':
                    window.location.href = "/request-an-account";
                    break;
                case 'home':
                    window.location.href = "https://yieldexchange.ca/";
                    break;
                default:
                    console.error('Unknown redirect value:', value);
                    break;
            }
        },
        emailChange(value) {
            this.email = value
            if (value == '') {
                this.emailerror = 'This field is required'
            } else {

                if (validateEmail(value)) {
                    this.emailerror = null
                } else {
                    this.emailerror = 'Provide a valid email'
                }
            }
        },
        passwordChange(value) {
            this.password = value.trim().replace(/\s/g, '')
            if (value == '') {
                // this.passworderror = 'This field is required'
            } else {
                if (this.step == 'reset-pass') {
                    const { strength, message } = this.checkPasswordStrength(this.password)
                    // console.log(message, strength)
                    if (strength <= 4)
                        this.passworderror = message
                    else
                        this.passworderror = null

                    this.passwordstrength = strength
                    this.strengthUpdate()
                    // this.passworderror = null

                } else {
                    this.passworderror = null
                }
            }
        },
        checkPasswordStrength(password) {
            let strength = 0;
            let message = '';

            // Check password length
            if (password.length >= 8) strength++;
            if (password.length > 12) strength++;

            // Check for uppercase letters
            if (/[A-Z]/.test(password)) strength++;
            // Check for lowercase letters
            if (/[a-z]/.test(password)) strength++;
            // Check for numbers
            if (/[0-9]/.test(password)) strength++;
            // Check for special characters
            if (/[\W_]/.test(password)) strength++;

            // Cap the strength at 6
            strength = Math.min(strength, 6);

            // Determine message
            switch (strength) {
                case 0:
                    message = "Very Weak: Too short and lacks complexity. Add uppercase letters, numbers, and special characters.";
                    break;
                case 1:
                    message = "Weak: Needs improvement. Use at least 8 characters with uppercase, numbers, and special characters.";
                    break;
                case 2:
                    message = "Moderate: Somewhat strong. Use 8+ characters with a mix of uppercase, numbers, and special characters.";
                    break;
                case 3:
                    message = "Strong: Good password. Add more characters or a wider range of special characters for better security.";
                    break;
                case 4:
                    message = "Very Strong: Excellent. Consider using a passphrase with more characters for maximum security.";
                    break;
                case 5:
                    message = "Excellent: Well done. It’s secure with a good mix of characters. Consider a password manager.";
                    break;
                case 6:
                    message = "Outstanding: Highly secure and well-constructed. Keep it private and update it periodically.";
                    break;
                default:
                    message = "Invalid: Error evaluating your password. Please try again.";
            }


            return { strength, message };
        },
        confirmPasswordChange(value) {
            this.confirmpass = value
            if (value == '') {
                this.confirmpasserror = 'This field is required'
            } else {
                if (this.confirmpass == this.password)
                    this.confirmpasserror = null
                else {
                    this.confirmpasserror = "Password Mismatch"
                }
                // console.log(value)
            }
        },
        pinChange(value) {
            this.PIN = value
            if (value == '') {
                this.PINerror = 'This field is required'
            } else {
                this.PINerror = null
                // console.log(value)
            }
        },
        Login() {
            if ((this.email != null && this.email != '') && this.password != null && this.password != '' && this.emailerror == null && this.passworderror == null) {

                this.submitting = true;
                axios.post(this.loginRoute, {
                    email: this.email,
                    loginAs: this.isAdmin ? 'Admin' : this.organizationType,
                    password: this.password,
                    automated_test: this.skip_robot
                }).then(response => {
                    let data = response?.data;
                    if (data.success) {
                        // this.success = true
                        this.successTitle = "Login successful"
                        this.successDesc = "Login successful. Please wait while we redirect you to the next step."
                        this.submitting = false;
                        setTimeout(() => {
                            this.success = false
                            if (this.skip_robot) {
                                window.location.href = "/dashboard";
                                return;
                            }
                            window.location.href = this.loginRoute + "?action=verifyOtp";
                            this.step = 'otp'
                        }, 500)
                    } else {
                        this.submitting = false;
                        this.fail = true
                        this.failTitle = "Login failed"
                        this.failDesc = "Login failed. Please verify that you have entered the correct details.."
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                        }, 2000)
                    }

                }).catch(error => {
                    this.submitting = false;
                    if (error?.response?.status === 419) {
                        this.fail = true
                        this.failTitle = "Login failed"
                        this.failDesc = "The page has expired due to inactivity. Please refresh the page and try again."
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                            window.location.reload()
                        }, 3000)
                    } else {
                        // console.log(error.response.data.message)
                        this.fail = true
                        this.failTitle = "Login failed"
                        this.failDesc = error.response.data.message
                        // this.failDesc = "Login failed. Please verify that you have entered the correct details.."
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                        }, 5000)
                    }


                });
            } else {
                if (this.email == null || this.email == '') {
                    this.emailerror = "This field is required"
                }
                if (this.password == null || this.password == '') {
                    this.passworderror = "This field is required"
                }
            }
        },
        resendPin() {
            // this.resendPinButtonText = "Please wait..";
            // this.resendPinButtonSpinner = true;
            this.resending = true
            axios.post(this.resendPinRoute, {
                user_id: this.userId,
            }).then(response => {
                let data = response?.data;
                // this.formErrors = data.message;
                this.resending = false
                this.resendPinButtonSpinner = false;
                if (data.success) {
                    this.pin = "";
                    this.pinresent = true
                    setTimeout(() => {
                        this.pinresent = false
                    }, 5000)
                    // this.$swal('',data.message,'').then(()=> {
                    // window.location.reload();
                    // });
                } else {
                    // this.formAlertType = data?.alert_class?.replace("alert-", "");
                }
                // this.resendPinButtonText='Resend PIN';
            }).catch(error => {
                this.resending = false
                // this.formErrors = error?.response?.data?.message;
                // this.formAlertType = error?.response?.data?.alert_class?.replace("alert-","");
                // this.resendPinButtonText='Resend PIN';
                // this.resendPinButtonSpinner = false;
            });
        },

        verifyPin() {
            // console.log(this.userId, "user id")
            if (this.PIN != null && this.PIN != '' && this.PINerror == null) {
                this.submitting = true
                axios.post(this.verifyOtpRoute, {
                    pin: this.PIN,
                    user_id: this.userId
                }).then(response => {
                    let data = response?.data;
                    if (data.success) {
                        this.success = true
                        this.successTitle = "Account verification complete"
                        this.successDesc = "You will be redirected shortly."
                        this.submitting = false;
                        setTimeout(() => {
                            this.success = false
                            window.location.href = this.dashboardRoute;

                        }, 2000)

                    } else {
                        this.fail = true
                        this.failTitle = "Account verification failed"
                        this.failDesc = "Account verification failed. Please try again or request a new PIN.";
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false

                        }, 3000)
                    }
                    // this.submitButtonText = 'Continue';
                }).catch(error => {
                    this.fail = true
                    this.failTitle = "Account verification failed"
                    if (error?.response?.status === 419) {
                        this.failDesc = "The page has expired due to inactivity. Please refresh the page and try again."
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                            window.location.href = this.loginRoute;

                        }, 3000)

                    }
                    this.failDesc = "Account verification failed. Please try again or request a new PIN.";
                    this.submitting = false;
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                });
            } else {
                this.PINerror = "This field is required"
                // console.log("else checker")
            }
        },
        resetPassword() {
            let emailValidity = validateEmail(this.email);
            if (this.email && emailValidity && this.emailerror == null) {

                // if (!this.recaptchaToken) {
                //     this.formErrors = "Enter a valid email";
                //     this.formAlertType = "danger";
                //     return;
                // }


                axios.post(this.resetRoute, {
                    email: this.email,
                    loginAs: this.isAdmin ? 'Admin' : null,
                    // recaptcha: this.recaptchaToken,
                    automated_test: this.skip_robot
                }).then(response => {
                    let data = response?.data;
                    // this.formErrors = data.message;
                    // this.submitButtonSpinner = false;
                    if (data.success) {
                        this.success = true
                        this.successTitle = "Password update"
                        this.successDesc = "If email exist in our system, you will get a reset link."
                        this.submitting = false;
                        setTimeout(() => {
                            this.success = false
                            window.location.href = this.loginRoute
                        }, 2000)
                    } else {
                        // this.formAlertType = data?.alert_class?.replace("alert-", "");
                    }
                    // this.submitButtonText = 'Continue';
                }).catch(error => {
                    if (error?.response?.status === 419) {
                        this.fail = true
                        this.failTitle = "Password reset failed"
                        this.failDesc = "The page has expired due to inactivity. Please refresh the page and try again."
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                            window.location.reload()
                        }, 3000)

                    } else {
                        this.fail = true
                        this.failTitle = "Password reset failed"
                        this.failDesc = "Login failed. Please verify that you have entered the correct details.."
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                        }, 5000)
                    }
                    // this.formErrors = error?.response?.data?.message;
                });
            } else {
                if (!this.emailValidity && this.email) {
                    this.emailerror = "Provide a valid email";
                } else if (this.email == null || this.email != '') {
                    this.emailerror = "This field is required";

                }
            }
        },
        ChangePassword() {
            this.failTitle = "Password has not been changed"

            if (this.password != null && this.confirmpass != null && this.confirmpasserror == null && this.passworderror == null) {
                this.submitting = true
                axios.post(this.passwordChangeRoute, {
                    pass: this.password,
                    code: this.resetCode,
                    cpass: this.confirmpass
                }).then(response => {
                    let data = response?.data;
                    // this.formErrors = data.message;
                    // this.submitButtonSpinner = false;
                    this.submitting = false
                    if (data.success) {
                        this.success = true
                        this.successTitle = "Password has been changed"
                        this.successDesc = data.message
                        this.submitting = false;
                        setTimeout(() => {
                            this.success = false
                            window.location.href = this.loginRoute;

                        }, 3000)

                    } else {
                        this.fail = true
                        this.failDesc = data.message;
                        this.submitting = false;
                        setTimeout(() => {
                            this.fail = false
                            // window.location.href = this.loginRoute;
                        }, 5000)
                    }
                    // this.submitButtonText='Continue';
                }).catch(error => {
                    this.submitting = false
                    if (error?.response?.status === 419) {
                        this.failDesc = "The page has expired due to inactivity. Please refresh the page and try again."
                        this.fail = true;
                        setTimeout(() => {
                            this.fail = false
                            window.location.href = this.loginRoute;
                        }, 3000)
                    } else {
                        this.failDesc = error?.response?.data?.message;
                        this.fail = true;
                        setTimeout(() => {
                            this.fail = false
                            // window.location.href = this.loginRoute;
                        }, 5000)
                        // this.formErrors = error?.response?.data?.message;
                    }
                    // this.formErrors = error?.response?.data?.message;
                    // this.formAlertType = error?.response?.data?.alert_class?.replace("alert-","");
                    // this.submitButtonText='Continue';
                    // this.submitButtonSpinner = false;
                });
            } else {
                if (this.password == null || this.password == '')
                    this.passworderror = "This field is required"
                if (this.confirmpass == null || this.confirmpass == '')
                    this.confirmpasserror = "This field is required"
                // console.log(this.password, " ", this.confirmpass, ' ', this.confirmpasserror, ' ', this.passworderror)
            }
        }
    }
}

</script>

<style scoped>
.strength-indicator {
    border-radius: 5px;
    width: 70px;
    height: 6px;

}

.blue-spinner {
    height: 20px;
    width: 20px;
    color: #5063F4;
}

.passwordmessage {
    color: #2A2A2A;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

.indicate-none {
    background: #D9D9D9;

}

.indicate-success {

    background: #44E0AA;
}

.indicate-low {
    background: #FF2E2E;

}

.mnavbar-login-style {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4));
    text-align: right;
    font-feature-settings: 'liga' off, 'clig' off;

    /* Box Titles */
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    cursor: pointer;
    /* 130% */
    text-transform: capitalize;
}

.email-confirm {
    background: #EFF2FE;
    padding: 20px 0px;
    color: #000;
    font-family: Montserrat;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 24px;
}

.sign-in-card {
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.10);
    padding: 60px 40px;

}

.forgot-password {
    color: #5063F4;
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 18px;
    cursor: pointer;
    /* 128.571% */
}

.make-a-request {
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 18px;
    /* 128.571% */
}

.sign-in-half-one {
    background: #F0F1F2;
    min-height: 100%;
    background-image: url('/assets/signup/signinpattern.svg');
    background-size: cover;
    background-repeat: no-repeat;
}

.shopping-rates-sign-in {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 32px;
    font-style: normal;
    font-weight: 400;
    line-height: 36px;
    letter-spacing: -1px;
}

.main-sign-in-action {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
}
</style>