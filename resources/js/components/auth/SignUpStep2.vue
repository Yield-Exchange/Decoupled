<template>
    <div class="text-left">
        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="fromUserProfile">
            <label v-if="showLabel" for="assigned_role">Assigned Role:</label>
            <b-form-input placeholder="Assigned Role" class="font-13" :value="role_name" readonly id="assigned_role"
                :class="{ 'verror': roleErrors }">
            </b-form-input>
            <b-alert v-if="roleErrors" show variant="danger">{{ roleErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="first_name">First Name:</label>
            <b-form-input maxlength="26" placeholder="First Name*" class="font-13" :value="first_name" id="first_name"
                autofocus @keyup="compareFirstnameStringLength" @input="$emit('update:first_name', $event)"
                :class="{ 'verror': firstnameErrors }">
            </b-form-input>
            <b-alert v-if="firstnameErrors" show variant="danger">{{ firstnameErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="last_name">Last Name:</label>
            <b-form-input maxlength="26" placeholder="Last Name*" class="font-13" :value="last_name" id="last_name"
                @keyup="compareLastnameStringLength" @input="$emit('update:last_name', $event)"
                :class="{ 'verror': lastnameErrors }">
            </b-form-input>
            <b-alert v-if="lastnameErrors" show variant="danger">{{ lastnameErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="email">Email:</label>
            <b-form-input maxlength="51" placeholder="Email*" class="font-13" :value="email" id="email"
                :state="validateEmail ? null : false" :readonly="fromUserProfile" @keyup="compareEmailStringLength"
                @input="$emit('update:email', $event)" :class="{ 'verror': emailErrors }">
            </b-form-input>
            <b-alert v-if="emailErrors" show variant="danger">{{ emailErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="user_telephone">Your Telephone:</label>
            <vue-phone-number-input :value="user_telephone" id="telephone" @keyup="compareTelephoneStringLength"
                @input="$emit('update:user_telephone', $event)" :only-countries="['CA']" :preferred-countries="['CA']"
                default-country-code="CA" :class="{ 'verror': telephoneErrors }" placeholder="Your Telephone" />

            <!--            <b-form-input maxlength="13" placeholder="Your Telephone:  format X-XXX-XXX-XXXX" class="font-13" :value="user_telephone"-->
            <!--                id="user_telephone" @keyup="compareTelephoneStringLength"-->
            <!--                @input="$emit('update:user_telephone', $event)" :class="{ 'verror': telephoneErrors }">-->
            <!--            </b-form-input>-->
            <b-alert v-if="telephoneErrors" show variant="danger">{{ telephoneErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="department">Department:</label>
            <b-form-input placeholder="Department" maxlength="51" class="font-13" :value="department" id="department"
                @keyup="compareDepartmentStringLength" @input="$emit('update:department', $event)"
                :class="{ 'verror': departmentErrors }">
            </b-form-input>
            <b-alert v-if="departmentErrors" show variant="danger">{{ departmentErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="job_title">Job Title:</label>
            <b-form-input placeholder="Job Title" maxlength="51" class="font-13" :value="job_title" id="job_title"
                @keyup="compareJobtitleStringLength" @input="$emit('update:job_title', $event)"
                :class="{ 'verror': jobErrors }">
            </b-form-input>
            <b-alert v-if="jobErrors" show variant="danger">{{ jobErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="city">City:</label>
            <b-form-input placeholder="City" maxlength="51" class="font-13" :value="location" id="city"
                @keyup="compareCityStringLength" @input="$emit('update:location', $event)"
                :class="{ 'verror': cityErrors }">
            </b-form-input>
            <b-alert v-if="cityErrors" show variant="danger">{{ cityErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="province">Province:</label>
            <v-select :options="JSON.parse(this.provinces)" class="font-13" placeholder="Province*"
                style="color: #212529;font-weight: 400;" :value="province2" id="province"
                @input="$emit('update:province2', $event)" :class="{ 'verror': provinceErrors }"
                @map-keydown="checkProvince()">
            </v-select>
            <b-alert v-if="provinceErrors" show variant="danger">{{ provinceErrors }}</b-alert>
        </b-col>
        <input type="text" style="display:none">
        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="timezone">Timezone:</label>
            <v-select :options="this.getTimezones" class="font-13" placeholder="Select Timezone*"
                style="color: #212529;font-weight: 400;" id="timezone" :value="timezone"
                @input="$emit('update:timezone', $event)" :class="{ 'verror': timezoneErrors }"
                @map-keydown="checkTimezone()">
            </v-select>
            <b-alert v-if="timezoneErrors" show variant="danger">{{ timezoneErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="!fromUserProfile">
            <label v-if="showLabel" for="password">Password:</label>
            <b-form-input id="password" v-bind:type="passwordVisible ? 'text' : 'password'" placeholder="Password*"
                maxlength="25" :value="password" @input="$emit('update:password', $event)"
                :class="{ 'verror': passErrors }" @keydown="comparePasswordStringLength">
            </b-form-input>
            <password v-model="password" :strength-meter-only="true" @score="showScore" @feedback="showFeedback" />
            <b-alert v-if="passErrors" show variant="danger">{{ passErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="!fromUserProfile">
            <label v-if="showLabel" for="conf_password">Confirm Password:</label>
            <b-form-input id="conf_password" v-bind:type="passwordVisible ? 'text' : 'password'"
                placeholder="Confirm Password*" maxlength="26" @keyup="comparedConfirmPassword" :value="conf_password"
                @input="$emit('update:conf_password', $event)" :class="{ 'verror': passErrors }">
            </b-form-input>

        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="!fromUserProfile">
            <div class="form-check" style="padding-left:0">
                <label class="form-check-label" style="font-size: 12px;">
                    <input type="checkbox" class="form-input-styled" @click="showPassword()" autocomplete="off" /> Show
                    Password
                </label>
            </div>
        </b-col>
        <b-col sm="12" class="pr-0 pl-0 mb-3" hidden>
            <b-form-checkbox id="accept-terms-and-conditions" name="checkbox-1" value="accepted"
                unchecked-value="not_accepted" style="" class="font-13">
                <b-button variant="link" v-bind:href="this.termsRoute" style="font-size:13px;padding-left:0">I accept
                    the terms and conditions</b-button>
            </b-form-checkbox>
        </b-col>
        <div class="row" v-if="!fromUserProfile&&skip_robot">
            <vue-recaptcha ref="recaptcha" :resetrecaptcha="resetrecaptcha" :sitekey="recaptchaKey"
                @verify="verifyRecaptcha" @error="errorRecaptcha" errorRecaptcha />
        </div>
    </div>
</template>
<style>
    .font-13,
    .vs__search {
        font-size: 13px
    }

    .vs__search {
        font-weight: 100
    }

    .alert {
        padding: 0.1rem 1rem;
        font-size: .8em;
    }

    .verror {
        border: 1px solid red;
        border-radius: 5px;
    }
</style>
<script>
    import Password from 'vue-password-strength-meter';
    import { VueRecaptcha } from 'vue-recaptcha';
    export default {
        created() {
            this.$parent.$on("submitTwo", this.errorCheck)
        },
        mounted() {
            // Initialize the watcher for resetRecaptcha
            this.$watch('resetRecaptcha', (newValue, oldValue) => {
                console.log(newValue + "refresh recapthca" + oldValue);
                if (newValue === true) {
                    if (this.$refs.recaptcha) {
                        this.$refs.recaptcha.reset();
                        this.$emit("updateResetRecaptcha");
                    } else {
                        console.error("$refs.recaptcha is not available yet.");
                    }
                }
            });
        },
        watch: {

            // "email":function (newName, oldName) {
            //     console.log("newName:", newName);
            //     console.log("newName:", oldName);
            // }
        },
        components: { Password, VueRecaptcha },
        props: ['recaptchaToken', 'recaptchaKey', 'resetRecaptcha', 'provinces', 'timezones', 'termsRoute', 'conf_password', 'password',
            'formErrors', 'timezone', 'department', 'user_telephone',
            'email', 'last_name', 'first_name', 'location', 'province2', 'job_title', 'fromUserProfile', 'role_name', 'showLabel', 'skip_robot'],
        data() {

            return {
                passwordVisible: false,
                passwordMatches: false,
                passErrors: '',
                jobErrors: '',
                firstnameErrors: '',
                lastnameErrors: '',
                telephoneErrors: '',
                roleErrors: '',
                provinceErrors: '',
                timezoneErrors: '',
                cityErrors: '',
                emailErrors: '',
                departmentErrors: ''
            }
        },
        computed: {
            getTimezones() {
                return JSON.parse(this.timezones);
            },
            validateEmail() {
                return this.validEmail();
            }
        },
        methods: {
            updateResetRecaptcha(response) {

            },
            errorRecaptcha(response) {
                // console.log(response);
            },
            verifyRecaptcha(response) {
                // console.log(response);
                // this.recaptchaToken = response;
                this.$emit('update:recaptchaToken', response);
            },
            validEmail() {
                return !this.email || String(this.email)
                    .toLowerCase()
                    .match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            },
            comparePasswordStringLength(e) {
                this.passErrors = "";
                if (!this.password && e?.keyCode !== 9) {
                    this.passErrors = "Password is Required";
                } else if (this.password.length > 25) {
                    this.passErrors = "Password cannot be more than 25 characters";
                    setTimeout(() => { this.passErrors = ""; }, 4000);
                }
            },
            compareTelephoneStringLength(e) {
                if (!this.user_telephone && e?.keyCode !== 9) {
                    this.telephoneErrors = "Telephone is Required";

                } /*else if (this.user_telephone.length > 10) {
                this.telephoneErrors = "Telephone cannot be more than 10 characters.";
                // setTimeout(() => { this.telephoneErrors = ""; }, 4000);
            }*/ else {
                    this.telephoneErrors = "";
                }
            },
            compareCityStringLength(e) {
                if (!this.location && e?.keyCode !== 9) {
                    this.cityErrors = "City is Required";
                } else if (this.location.length > 50) {
                    this.cityErrors = "City cannot be more than 50 characters.";
                    // setTimeout(() => { this.cityErrors = ""; }, 4000);
                } else {
                    this.cityErrors = "";
                }
            },
            compareFirstnameStringLength(e) {
                if (!this.first_name && e?.keyCode !== 9) {
                    this.firstnameErrors = "First Name is Required";
                } else if (this.first_name.length > 25) {
                    this.firstnameErrors = "First Name cannot be more than 25 characters.";
                    // setTimeout(() => { this.firstnameErrors = ""; }, 4000);
                } else {
                    this.firstnameErrors = "";
                }
            },
            compareLastnameStringLength(e) {
                if (!this.last_name && e?.keyCode !== 9) {
                    this.lastnameErrors = "Last Name is Required";
                } else if (this.last_name.length > 25) {
                    this.lastnameErrors = "Last Name cannot be more than 25 characters.";
                    //setTimeout(() => { this.lastnameErrors = ""; }, 4000);
                } else {
                    this.lastnameErrors = "";
                }
            },
            compareDepartmentStringLength(e) {
                if (!this.department && e?.keyCode !== 9) {
                    this.departmentErrors = "Department is Required";
                } else if (this.department.length > 50) {
                    this.departmentErrors = "Department cannot be more than 50 characters.";
                    //setTimeout(() => { this.departmentErrors = ""; }, 4000);
                } else {
                    this.departmentErrors = "";
                }
            },
            compareJobtitleStringLength(e) {
                if (!this.job_title && e?.keyCode !== 9) {
                    this.jobErrors = "Job Title is Required";
                } else if (this.job_title.length > 50) {
                    this.jobErrors = "Job Title cannot be more than 50 characters.";
                    //setTimeout(() => { this.jobErrors = ""; }, 4000);
                } else {
                    this.jobErrors = "";
                }
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
            passWordMatch() {
                return this.password && this.conf_password && this.password === this.conf_password;
            },
            comparedConfirmPassword() {
                this.passwordMatches = this.passWordMatch();
                this.passErrors = "";
                if (!this.passwordMatches) {
                    this.passErrors = "Password do not match";
                }
            },
            checkProvince(e) {
                if (!this.province2 && e?.keyCode !== 9) {
                    this.provinceErrors = "Select a Province";
                    setTimeout(() => { this.provinceErrors = ""; }, 4000);
                } else {
                    this.provinceErrors = "";
                }
            },
            checkTimezone(e) {
                if (!this.timezone && e?.keyCode !== 9) {
                    this.timezoneErrors = "Select a Timezone";
                    setTimeout(() => { this.timezoneErrors = ""; }, 4000);
                } else {
                    this.timezoneErrors = "";
                }
            },
            checkRole(e) {
                if (!this.assign && e?.keyCode !== 9) {
                    this.roleErrors = "Select a Role";
                    setTimeout(() => { this.roleErrors = ""; }, 4000);
                } else {
                    this.roleErrors = "";
                }
            },
            showFeedback({ suggestions, danger }) {
                this.passErrors = "";
                if (suggestions) {
                    for (let i = 0; i < suggestions.length; i++) {
                        this.passErrors += suggestions[i] + " ";
                    }
                }
                // console.log(errors);
                // this.$emit('update:formErrors', errors);
            },
            showScore(score) {
            },
            showPassword() {
                this.passwordVisible = !this.passwordVisible;
            },
            errorCheck() {
                this.comparePasswordStringLength();
                this.compareFirstnameStringLength();
                this.compareEmailStringLength();
                this.compareJobtitleStringLength();
                this.compareDepartmentStringLength();
                this.compareLastnameStringLength();
                this.compareCityStringLength();
                this.compareTelephoneStringLength();
                this.checkProvince();
                this.checkTimezone();
            }
        }
    }
</script>
