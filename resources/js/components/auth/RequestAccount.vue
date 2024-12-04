<template>
    <div class="mt-5">
        <div class="margin-top">
            <div class="container bg-white">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <h1 class="intro-text">
                            Getting access to competitive rates is easy <span>(like really easy) </span>with Yield
                            Exchange
                        </h1>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-container shadow-sm">
                            <div>

                                <div class="row">

                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="required-label" for="first_name">First Name</label>
                                            <input type="text " id="first_name" @input="validateFirstName"
                                                v-model="first_name" class="form-control" required>
                                            <div v-if="first_name_error != ''" class="validation-error" role="alert">
                                                {{ first_name_error }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="required-label" for="last_name">Last Name</label>
                                            <input type="text " id="last_name" @input="validateLastName"
                                                v-model="last_name" class="form-control" required>
                                            <div v-if="last_name_error != ''" class="validation-error" role="alert">
                                                {{ last_name_error }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label class="required-label" for="business_email">Email</label>
                                            <span style="height: 5px;"></span>
                                            <input style="margin-top: 11px !important;" type="email" id="business_email"
                                                @input="validateEmail" v-model="business_email" class="form-control"
                                                required>
                                            <div v-if="business_email_error != ''" class="validation-error"
                                                role="alert">
                                                {{ business_email_error }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">

                                        <div class="form-group">
                                            <label class="required-label" for="phone_number">Phone Number</label>
                                            <CustomTelInput
                                                @hasError="(checkerVariable) => phone_number_error = checkerVariable"
                                                input_type="tel" :required="true" :dont_show="true"
                                                :currentValue="phone_number" v-model="phone_number" :no_example="true"
                                                :border_radius="0" :noflags="false" class="req-account w-100 ">
                                            </CustomTelInput>
                                            <!-- <input type="tel" id="phone_number" @input="validatePhone"
                                                v-model="phone_number" class="form-control" required> -->
                                            <!-- <div v-if="phone_number_error != ''" class="validation-error" role="alert">
                                                {{ phone_number_error }}
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12" v-if="is_conference">
                                        <div class="form-group">
                                            <label class="required-label" for="organization_name">Organization
                                                Name</label>
                                            <input type="text " id="organization_name" @input="validateOrganizationName"
                                                v-model="organization_name" class="form-control" required>
                                            <div v-if="organization_name_error != ''" class="validation-error"
                                                role="alert">
                                                {{ organization_name_error }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex flex-column">
                                        <div class="">
                                            <vue-recaptcha ref="recaptcha1" :sitekey="recaptchaKey"
                                                @verify="verifyRecaptcha" @error="errorRecaptcha" />
                                        </div>
                                        <div class="mt-2">
                                            <button @click="doSubmit" :disabled="false"
                                                class="btn-submit">Continue</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- carousel  -->
                <div class="mt-5 slide-section">
                    <div class="d-flex justify-content-center">
                        <h2 class="institutions-title">Connect with dozens of financial institutions.</h2>
                    </div>

                    <VueSlickCarousel v-bind="settings">
                        <!-- <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/vancitynl.png" class="slide-logo" alt=""
                                srcset="">
                        </div> -->
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/wealthonenl.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/summerlandnl.png" class="slide-logo" alt="" srcset="">
                        </div>

                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/rockycreditnl.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/bowvalleynl.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/peoplesbanknl.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/firstwestnl.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/NewNonFederal.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/vcib_logo.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/ocu-horizontal-lockup-full-color-rgb-900px@144ppi.png"
                                class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/nlunknown.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/lsm_vert_logo_col.png" class="slide-logo" alt="" srcset="">
                        </div>

                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/gulfandfrasernl.png" class="slide-logo" alt="" srcset="">
                        </div>
                        <div class="d-flex justify-content-center slide-item">
                            <img src="/images/testimonials/2/laurentbanknl.png" class="slide-logo" alt="" srcset="">
                        </div>

                    </VueSlickCarousel>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="container p-5">
                    <div class="row step-row ">
                        <div class="col-12 col-md-6">
                            <img src="images/companylogos/post-request-step-1.svg" class="bank-step-image w-100" alt=""
                                srcset="">
                        </div>
                        <div class="col-12 col-md-6 d-flex flex-column justify-content-center px-5">
                            <span class="step-main-header">Financial matchmaking</span>
                            <h3 class="step-header">
                                Find the right match
                            </h3>
                            <p class="step-description">
                                Yield Exchange connects you directly to financial institutions, making it easier than
                                ever
                                shop around for rates.
                            </p>
                        </div>
                    </div>
                    <div class="row step-row flex-row-reverse">
                        <div class="col-12 col-md-6">
                            <img src="images/companylogos/post-request-step-2.svg" class="bank-step-image w-100" alt=""
                                srcset="">
                        </div>
                        <div class="col-12 col-md-6 d-flex flex-column justify-content-center px-5">
                            <span class="step-main-header">Financial empowerment</span>
                            <h3 class="step-header">
                                You're in control
                            </h3>
                            <p class="step-description">
                                Yield Exchange brings the financial institutions to you, giving you all of the
                                information
                                you need to make the right investment decision.
                            </p>
                        </div>
                    </div>
                    <div class="row step-row ">
                        <div class="col-12 col-md-6">
                            <img src="images/companylogos/post-request-step-1.svg" class="bank-step-image w-100" alt=""
                                srcset="">
                        </div>
                        <div class="col-12 col-md-6 d-flex flex-column justify-content-center px-5">
                            <span class="step-main-header">Risk-free Simplicity</span>
                            <h3 class="step-header">
                                Zero risk, zero obligation
                            </h3>
                            <p class="step-description">
                                Yield Exchange is free to use, easy to use, and offers no risk since we never hold onto
                                any
                                funds - we work for you.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MyCustomInput from "./MyCustomInput.vue"
import VueSlickCarousel from 'vue-slick-carousel'
import { VueRecaptcha } from 'vue-recaptcha';
import CustomTelInput from "./signup/shared/CustomTelInput.vue";

export default {
    components: { VueSlickCarousel, VueRecaptcha, CustomTelInput },
    props: ['registerRoute', 'is_conference', 'recaptchaKey', 'verifyCodeRoute', 'verifyCode'],
    data() {
        return {
            first_name: "",
            last_name: "",
            business_email: "",
            phone_number: "",
            org_name: "",
            website_url: "",

            // error messages

            website_url_error: "",
            first_name_error: "",
            business_email_error: "",
            phone_number_error: "",
            org_name_error: "",
            last_name_error: "",
            organization_name: "",
            organization_name_error: "",

            // submit
            recaptchaToken: null,
            // recapta

            organizationType: "Depositor",
            disablebtn: true,
            settings: {
                "arrows": false,
                "dots": false,
                "infinite": true,
                "slidesToShow": 3,
                "slidesToScroll": 1,
                "edgeFriction": 0.35,
                "autoplay": true,
                "speed": 500,
                "autoplaySpeed": 5000,
                "cssEase": "linear",
                "centerMode": false,
                "adaptiveHeight": false,
                "centerPadding": '20px'

            }
        }

    },
    mounted() {
        this.verifyEmailRequest();
    },
    methods: {
        goNext() {
            window.location.href = '/proceed-to-reg'
        },
        verifyRecaptcha(response) {
            // console.log(response);
            this.recaptchaToken = response;
            this.canSubmit()
        },
        errorRecaptcha(response) {
            // console.log(response);
        },
        WebsiteValidation(e) {
            let urlPattern = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:[0-9]+)?(\/[^\s]*)?$/i;
            if (this.website_url) {
                let t = urlPattern.test(this.website_url);
                if (!t) {
                    this.website_url_error = "Invalid website";
                    // console.log(this.website_url_error)
                } else {
                    this.website_url_error = "";
                }
            } else {
                this.website_url_error = "";
            }
            this.canSubmit()
        },
        validateOrganizationName() {
            this.organization_name_error = this.organization_name.length < 2 ? 'Organization Name must be at least 2 characters' : '';
            this.canSubmit()
        },
        validateFirstName() {
            this.first_name_error = this.first_name.length < 3 ? 'Name must be at least 3 characters' : '';
            this.canSubmit()
        },
        validateLastName() {
            this.last_name_error = this.last_name.length < 3 ? 'Name must be at least 3 characters' : '';
            this.canSubmit()
            // this.canSubmit
        },
        validateEmail() {
            const emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
            this.business_email_error = emailRegex.test(this.business_email) ? '' : 'Invalid email address';
            this.canSubmit()
        },
        validatePhone() {
            this.phone_number_error = this.phone_number.length < 9 ? 'Too short' : '';
            this.formattedPhoneNumber();
            this.canSubmit();
            // this.phone_number_error = this.phone_number.length < 9 ? 'Too shot' : '';
        },
        async doSubmit() {
            let this_ = this;
            if (this_.canSubmit()) {
                const data = {
                    email: this_.business_email,
                    last_name: this_.last_name,
                    first_name: this_.first_name,
                    phone_number: this_.phone_number,
                    recaptcha: this.recaptchaToken,
                    organization_name: this.organization_name
                }
                if (!this.is_conference) {
                    axios.post('/new-sign-up', data, {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        let data = response?.data;
                        if (data.success) {
                            window.location.href = '/proceed-to-reg'
                            // window.location.href = '/dashboard';//this_.homeRoute; // redirect user to dashboard to finish up adding users

                        } else {
                            // this.recaptchaToken = null
                            this.$swal({
                                title: 'Registration failed',
                                text: data.message,
                                confirmButtonText: 'Close'
                            });
                            this.$refs.recaptcha1.reset();

                        }
                        this_.submitButtonText = 'Continue';
                    }).catch(error => {
                        // this.recaptchaToken = null
                        if (error?.response?.status === 419) {
                            this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                        } else {
                            // this.formErrors = error?.response?.data?.message;
                        }
                        this.$swal({
                            title: 'Registration failed',
                            text: error?.response?.data?.message,
                            confirmButtonText: 'Close'
                        });

                        this.$refs.recaptcha1.reset();
                        this_.submitButtonText = 'Continue';
                        this.submitButtonSpinner = false;
                    });
                } else {
                    this.$emit('submit', data)
                }
            }
        },
        formattedPhoneNumber() {
            const cleaned = this.phone_number.replace(/\D/g, '');

            const formatted =
                cleaned.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '($2) $3-$4');

            this.phone_number = formatted;
        },
        canSubmit() {
            if (this.first_name_error || this.business_email_error || this.phone_number_error || this.last_name_error ||
                (this.business_email == '' || this.first_name == '' || this.last_name == '' || this.phone_number == '')) {
                this.disablebtn = true
                return false
            } else {
                // console.log("Can submit")
                if (this.is_conference) {
                    if (this.organization_name != '' && !this.organization_name_error) {
                        this.disablebtn = true
                        return true
                    } else {
                        this.disablebtn = true
                        return false
                    }
                } else {
                    this.disablebtn = false
                    return true
                }
            }
        },
        verifyEmailRequest() {
            // console.log("huku");
            if (!this.verifyCode) {
                return;
            }
            let this_ = this;
            const formData = new FormData();
            axios.post(this_.verifyCodeRoute, formData, {
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
                        title: 'Verification failed',
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
                    title: 'Verification failed',
                    text: error?.response?.data?.message,
                    confirmButtonText: 'Close'
                });

                this_.submitButtonText = 'Continue';
                this.submitButtonSpinner = false;
            });
        }

    },
}
</script>
<style>
.req-account .mynumber .country-selector__input {
    border: none !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.332) !important;
    /* border: solid 1px #ccc !important; */
    border-radius: 0 !important;
    height: 45px !important;
    font-family: Montserrat !important;
    font-weight: 600 !important;
}

.req-account .mynumber .input-tel__input {
    border: none !important;
    /* border-bottom: solid 1px #ccc !important; */
    border-bottom: 1px solid rgba(0, 0, 0, 0.332) !important;
    border-radius: 0 !important;
    height: 45px !important;
    font-family: Montserrat !important;
    font-weight: 600 !important;
}

.req-account .mynumber .country-selector__label {
    display: none;
}

.req-account .mynumber .select-country-container {
    min-width: 100px !important;
    flex: 0 0 100px !important;
}

.error {}

.req-account .mynumber .input-tel__input::placeholder {
    color:transparent !important;
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9ca1aa00);
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
    /* Firefox */
}

.req-account .mynumber .input-tel__input::-ms-input-placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}
</style>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

.margin-top {
    /* margin-top: 80px !important; */
}

.intro-text {
    line-height: 1.15;
    font-size: 50px;
    font-family: Poppins, sans-serif;
    font-style: normal;
    font-weight: 700;
    text-decoration: none;
    color: #231f20;
}


.intro-text>span {
    color: #4975e3;
    font-style: italic;
}

.form-control {
    border: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.332);
    border-radius: 0px;
}

.form-container {
    padding: 2rem;
    /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.209); */
    border-radius: 5px;

}

.required-label:after {
    content: " *";
    color: red;
}

label {
    /* font-family: Poppins, sans-serif !important; */
    padding-top: 6px;
    padding-right: 0px;
    padding-bottom: 4px;
    padding-left: 0px;
    font-family: Montserrat, sans-serif;
    font-style: normal;
    font-weight: normal;
    text-decoration: none;
    color: #231f20;
    font-size: 1rem;
}

input {
    padding: 1rem !important;
    font-size: 16px !important;
    font-family: Montserrat !important;
    font-weight: 600 !important;
}

input:focus {
    outline: none !important;
    border: 1px solid black;
    box-shadow: none;
    padding: 10px !important;
    border-radius: 3px;
}

.btn-submit {
    background-color: rgba(73, 117, 227, 1.0);
    font-weight: bold;
    padding-top: 12px;
    padding-right: 30px;
    padding-bottom: 12px;
    padding-left: 30px;
    border-radius: 32px;
    color: white;
    outline: none;
    border: none;
}

.institutions-title {
    font-family: Poppins, serif;
    font-style: normal;
    font-weight: 700;
    text-decoration: none;
    color: #231f20;
    font-size: 2rem;
    text-transform: none;
    padding-bottom: 1rem;
}

.logo {
    height: 100px;
}

/* content wrapper */

.content-wrapper {
    background-color: rgba(244, 246, 250, 1) !important;
    /* height: 100vh; */
}

.bank-step-image {
    /* height: 400px; */
}

.step-main-header {
    /* text-decoration: ; */
    font-family: Poppins, sans-serif;
    font-weight: bolder;
    text-transform: uppercase;
    color: #4975e3;
    font-size: 16px;
    line-height: 1;
}

.step-header {
    font-family: Poppins, sans-serif;
    font-style: normal;
    font-weight: normal;
    text-decoration: none;
    color: #231f20;
    font-size: 28px;
    text-transform: none;
}

.step-descrption {
    font-family: Poppins, sans-serif;
    font-style: normal;
    font-weight: normal;
    text-decoration: none;
    font-size: 1rem;
}

.step-row {
    margin-top: 2rem;
    margin-bottom: 1.5rem;
    padding: 2rem;
}

.slide-logo {
    /* max-width: 120px !important; */
    width: 230px;
    margin: auto;
    height: 80px;
    overflow-clip-margin: content-box;
    /* overflow: cli; */
}

.slide-section {
    padding: 20px 20px;
}

.slide-item {
    width: 120px !important;
    align-items: center;
    margin: 0px !important;
    /* background-color: #ef6b51 !important; */
    /* height: 100px; */
}

.validation-error {
    color: #F04438 !important;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
}

@media screen and (max-width:780px) {
    .intro-text {
        font-size: 25px;
        text-align: center;

    }

    .institutions-title {
        font-size: 25px;
        text-align: center;
    }
}

@media screen and (max-width:820px) {
    .intro-text {
        font-size: 35px;
        /* text-align: center; */
    }
}
</style>