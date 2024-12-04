<template>
    <div>
        <form method="post" autocomplete="off" @submit.prevent="submit">
            <div class="row">
                <div class="col-md-12 well">

                    <input type="hidden" name="user_id" v-if="user" v-model="user.id" />
                    <input type="hidden" name="organization_id" v-model="organisationId">

                    <h5 v-if="showlabel">First name</h5>
                    <div class="form-group">
                        <input maxlength="26" minlength="1" placeholder="Enter First Name*"
                            class="form-control font-13 col-md-12 " id="firstName" @keyup="checkFirstName"
                            v-model="firstName" :class="{ 'verror': firstNameError }" name="firstName" />
                        <b-alert v-if="firstNameError" show variant="danger" class="form-alert">{{ firstNameError }}
                        </b-alert>
                    </div>

                    <h5 v-if="showlabel">Last name</h5>
                    <div class="form-group">
                        <b-form-input maxlength="26" minlength="1" placeholder="Enter Last Name*"
                            class="form-control font-13 col-md-12 " id="lastName" @keyup="checkLastName"
                            v-model="lastName" :class="{ 'verror': lastNameError }" name="lastName">
                        </b-form-input>
                        <b-alert v-if="lastNameError" show variant="danger" class="form-alert">{{ lastNameError }}
                        </b-alert>
                    </div>


                    <h5 v-if="showlabel">Phone</h5>
                    <div class="form-group">
<!--                        <b-form-input maxlength="10" minlength="1" placeholder="Enter Telephone: format X-XXX-XXX-XXXX"-->
<!--                            class="form-control font-13 col-md-12 " id="telephone" @keyup="checkTelephone"-->
<!--                            v-model="telephone" :class="{ 'verror': telephoneError }" name="telephone">-->
<!--                        </b-form-input>-->
                        <vue-phone-number-input :value="telephone" id="telephone" @keyup="checkTelephone"
                                                v-model="telephone" :only-countries="['CA']" :preferred-countries="['CA']" default-country-code="CA"
                                                :class="{ 'verror': telephoneError }"
                                                placeholder="Your Telephone" />

                        <b-alert v-if="telephoneError" show variant="danger" class="form-alert">{{ telephoneError }}
                        </b-alert>
                    </div>

                    <h5 v-if="showlabel">Email</h5>
                    <div class="form-group">
                        <b-form-input maxlength="50" minlength="1" placeholder="Enter Email*"
                            class="form-control font-13 col-md-12 " @keyup="checkEmail" v-model="email"
                            :class="{ 'verror': emailError }">
                        </b-form-input>
                        <b-alert v-if="emailError" show variant="danger" class="form-alert">{{ emailError }}</b-alert>
                    </div>

                    <h5 v-if="showlabel">Department</h5>
                    <div class="form-group">
                        <b-form-input maxlength="50" minlength="1" placeholder="Enter Department*"
                            class="form-control font-13 col-md-12 " id="department" @keyup="checkDepartment"
                            v-model="department" :class="{ 'verror': departmentError }" name="department">
                        </b-form-input>
                        <b-alert v-if="departmentError" show variant="danger" class="form-alert">{{ departmentError }}
                        </b-alert>
                    </div>

                </div>
                <div class="col-md-12 well">

                    <h5 v-if="showlabel">Job Title</h5>
                    <div class="form-group">
                        <b-form-input maxlength="26" minlength="1" placeholder="Enter Job Title*"
                            class="form-control font-13 col-md-12 " id="job" @keyup="checkJob" v-model="job"
                            :class="{ 'verror': jobError }" name="job">
                        </b-form-input>
                        <b-alert v-if="jobError" show variant="danger" class="form-alert">{{ jobError }}</b-alert>
                    </div>


                    <h5 v-if="showlabel">City</h5>
                    <div class="form-group">
                        <b-form-input maxlength="26" minlength="1" placeholder="Enter City*"
                            class="form-control font-13 col-md-12 " id="city" @keyup="checkCity" v-model="city"
                            :class="{ 'verror': cityError }" name="city">
                        </b-form-input>
                        <b-alert v-if="cityError" show variant="danger" class="form-alert">{{ cityError }}</b-alert>
                    </div>

                    <h5 v-if="showlabel">Province</h5>
                    <div class="form-group">
                        <select name="province" class="form-control" @change="checkProvince"
                            :class="{ 'verror': provinceError }" v-model="province">
                            <option value="">Select Province*</option>
                            <option v-for="province in JSON.parse(provinces)" :value="province">{{ province }}</option>
                        </select>
                        <b-alert v-if="provinceError" show variant="danger" class="form-alert">{{ provinceError }}
                        </b-alert>
                    </div>

                    <h5 v-if="showlabel">Timezone</h5>
                    <div class="form-group">
                        <select name="timezone" class="form-control" @change="checkTimezone"
                            :class="{ 'verror': timezoneError }" v-model="timezone">
                            <option value="">Select Timezone</option>
                            <option v-for="(timezoneValue, key) in JSON.parse(timezones)" :value="key">{{ timezoneValue }}</option>
                        </select>
                        <b-alert v-if="timezoneError" show variant="danger" class="form-alert">{{ timezoneError }}
                        </b-alert>
                    </div>


                    <h5 v-if="showlabel">Roles</h5>
                    <div class="form-group">
                        <select name="role_id" class="form-control select2" @change="checkRole"
                            :class="{ 'verror': roleError }" v-model="role">
                            <option value="">Select Role</option>
                            <option v-for="role in JSON.parse(roles)" :value="role.id">{{ role.display_name }}
                            </option>
                        </select>
                        <b-alert v-if="roleError" show variant="danger" class="form-alert">{{ roleError }}</b-alert>
                    </div>


                    <b-col v-if="this.auth && this.auth.organization && this.auth.organization.type == 'BANK'" class="text-center" >
                        <b-form-checkbox v-model="setLimit" name="check-button" switch v-on:click="flipLimit">
                            Set Approval Limit
                        </b-form-checkbox>
                    </b-col>
                    <div v-if="setLimit == true">
                        <h5 v-if="showlabel">Minimum Limit</h5>
                        <div class="form-group">
                            <b-form-input maxlength="26" minlength="1" placeholder="Enter Minimum Limit*"
                                class="form-control font-13 col-md-12 " id="minimumLimit" @keyup="checkMinimumLimit" v-model="minimumLimit"
                                :class="{ 'verror': minimumLimitError }" name="minimumLimit">
                            </b-form-input>
                            <b-alert v-if="minimumLimitError" show variant="danger" class="form-alert">{{ minimumLimitError }}</b-alert>
                        </div>

                        <h5 v-if="showlabel">Maximum Limit</h5>
                        <div class="form-group">
                            <b-form-input maxlength="26" minlength="1" placeholder="Enter Maximum Limit*"
                                class="form-control font-13 col-md-12 " id="maximumLimit" @keyup="checkMaximumLimit" v-model="maximumLimit"
                                :class="{ 'verror': maximumLimitError }" name="maximumLimit">
                            </b-form-input>
                            <b-alert v-if="maximumLimitError" show variant="danger" class="form-alert">{{ maximumLimitError }}</b-alert>
                        </div>

                        <b-row>
                            <CustomInput name="Start Date" id="startDate" p-style="" :has-validation="true"
                                input-type="datepicker" :c-style="'border-radius: 10px;'"
                                @inputChanged="startDate = $event" :default-value="startDate" :attributes="{ min: new Date() }"
                                :validation-failed="setLimit && (startDateError)" :validation-error="startDateError" />
                        </b-row>

                        <b-row>
                            <!-- <b-form-datepicker v-model="value" :min="min" :max="max" locale="en"></b-form-datepicker> -->
                            <CustomInput name="End Date" id="endDate" p-style="" :has-validation="true"
                                input-type="datepicker" :c-style="'border-radius: 10px;'"
                                @inputChanged="endDate = $event" :default-value="null" :attributes="{ min: new Date(this.startDate) }"
                                :validation-failed="setLimit && (endDateError)" :validation-error="endDateError" />
                        </b-row>

                    </div>

                </div>
            </div>

            <div class="row" align="center">
                <div class="col-md-12 well">
                    <div class="form-group">
                        <br>
                        <input @click="[$emit('click-close-modal', $event), cancel()]" type="button"
                            class="btn btn-md custom-secondary round" data-dismiss="modal" value="Cancel">
                        <!-- <input type="submit" class="btn btn-primary mmy_btn" :value="submitButtonText" /> -->
                        <b-button :variant="'primary'" :disabled="submitButtonSpinner" :size="'md'"
                            style="font-size:15px;" @click="submit" :class="'custom-primary round'">
                            <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                                v-if="submitButtonSpinner">
                            </b-spinner>
                            {{ submitButtonText }}
                        </b-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<style>
.verror {
    border: 1px solid red;
    border-radius: 5px;
}

.form-alert {
    padding: 0.1rem 1rem;
    font-size: .9em;
    font-weight: normal;
}
</style>

<script>
import CustomInput from "../shared/CustomInput";
export default {
    props: ["provinces", "roles", "timezones", "createroute", 'listroute', 'user', 'authuser', 'organisation_id'],
    components: {
        CustomInput
    },
    data() {
        return {
            auth: this.authuser ? JSON.parse(this.authuser) : null,
            firstName: this.user ? this.user.firstname : '',
            lastName: this.user ? this.user.lastname : '',
            email: this.user ? this.user.email : '',
            telephone: this.user ? this.user.phone : '',
            department: this.user ? this.user.department : '',
            role: this.user ? this.user.role_id : '',
            province: this.user ? this.user.province : '',
            timezone: this.user ? this.user.timezone : '',
            city: this.user ? this.user.city : '',
            job: this.user ? this.user.job_title : '',
            userId: this.user ? this.user.id : '',
            organisationId: this.authuser ? this.organisation_id : '',
            maximumLimit: this.user ? this.user?.approval_limit?.maximumLimit : '',
            minimumLimit: this.user ? this.user?.approval_limit?.minimumLimit : '',
            startDate: this.user ? this.user?.approval_limit?.startDate : new Date(),
            endDate: this.user ? this.user?.approval_limit?.endDate : '',

            firstNameError: '',
            lastNameError: '',
            emailError: '',
            telephoneError: '',
            departmentError: '',
            roleError: '',
            provinceError: '',
            timezoneError: '',
            cityError: '',
            jobError: '',

            submitButtonText: "Submit",
            submitButtonSpinner: false,

            showlabel: false,

            maximumLimitError: '',
            minimumLimitError: '',
            startDateError: '',
            endDateError: '',

            setLimit: (this.user?.approval_limit?.status == "Active") ? true : false,

        }
    },
    mounted() {
        this.auth = this.authuser ? JSON.parse(this.authuser) : null;
        if (!this.organisation_id) {
            this.organisationId = 0
        }
    },
    created: function () {
        this.$parent.$on('closeModel', this.cancel)
    },
    methods: {
        checkFirstName() {
            if (!this.firstName) {
                this.firstNameError = "First Name Is Required";
            } else if (this.firstName.length > 24) {
                this.firstNameError = "First Name Can Not be more than 25 Characters";
            } else {
                this.firstNameError = "";
            }
        },
        checkLastName() {
            if (!this.lastName) {
                this.lastNameError = "Last Name Is Required";
            } else if (this.lastName.length > 24) {
                this.lastNameError = "Last Name Can Not be more than 25 Characters";
            } else {
                this.lastNameError = "";
            }
        },
        checkTelephone() {
            // this.telephone = isNaN(parseInt(this.telephone)) ? "" : parseInt(this.telephone);
            if (!this.telephone) {
                this.telephoneError = "Phone  Is Required";
            } /*else if (this.telephone.length > 10 || this.telephone.length < 9) {
                this.telephoneError = "Phone Must be 10 Numbers";
            }*/ else {
                this.telephoneError = "";
            }
        },
        checkEmail() {
            if (!this.email) {
                this.emailError = "Email  Is Required";
            } else if (this.email.length > 50) {
                this.emailError = "Email Can Not be more than 50 Numbers";
            } else if (!this.validateEmail()) {
                this.emailError = "Invalid Email Address";
            } else {
                this.emailError = "";
            }
        },
        checkDepartment() {
            if (!this.department) {
                this.departmentError = "Department Is Required";
            } else if (this.department.length > 50) {
                this.departmentError = "Department Can Not be more than 50 Characters";
            } else {
                this.departmentError = "";
            }
        },
        checkJob() {
            if (!this.job) {
                this.jobError = "Job Title Is Required";
            } else if (this.job.length > 50) {
                this.jobError = "Job Title Can Not be more than 50 Characters";
            } else {
                this.jobError = "";
            }
        },
        checkCity() {
            if (!this.city) {
                this.cityError = "City Is Required";
            } else if (this.city.length > 50) {
                this.cityError = "City Can Not be more than 50 Characters";
            } else {
                this.cityError = "";
            }
        },
        checkProvince() {
            if (!this.province) {
                this.provinceError = "Province Is Required";
            } else {
                this.provinceError = "";
            }
        },
        checkTimezone() {
            if (!this.timezone) {
                this.timezoneError = "Timezone Is Required";
            } else {
                this.timezoneError = "";
            }
        },
        checkRole() {
            if (!this.role) {
                this.roleError = "Role Is Required";
            } else {
                this.roleError = "";
            }
        },
        checkMaximumLimit() {
            if (!this.maximumLimit && this.setLimit) {
                this.maximumLimitError = "Maximum Is Required";
            } else {
                this.maximumLimitError = "";
            }
        },
        checkMinimumLimit() {
            if (!this.minimumLimit && this.setLimit) {
                this.minimumLimitError = "Minimum Is Required";
            } else {
                this.minimumLimitError = "";
            }
        },
        checkDate() {
            if(this.endDate) {
                var start_date = new Date(this.startDate);
                var end_date = new Date(this.endDate);
                if(start_date > end_date) {
                    this.endDateError = "Start Date is more than End Date";
                }
            } else {
                this.endDateError = "";
            }
        },
        validateEmail() {
            return !this.email || String(this.email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        },
        canSubmit() {
            this.checkCity();
            this.checkDepartment();
            this.checkEmail();
            this.checkFirstName();
            this.checkJob();
            this.checkLastName();
            this.checkProvince();
            this.checkRole();
            this.checkTelephone();
            this.checkTimezone();
            var setLimitValue = true;
            if(this.setLimit) {
                this.checkMaximumLimit();
                this.checkMinimumLimit();
                this.checkDate();
                if (!this.minimumLimitError && !this.maximumLimitError && !this.endDateError && !this.startDateError) {
                    setLimitValue = true;
                }else {
                    setLimitValue = false;
                }

            }
            return setLimitValue && !this.firstNameError && !this.lastNameError && !this.departmentError && !this.emailError && !this.telephoneError && !this.jobError && !this.cityError && !this.provinceError && !this.timezoneError && !this.roleError;
        },
        capitalize(thestring) {
            return thestring
                    .toLowerCase()
                    .split(' ')
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
        },
        async submit() {

            if (!this.canSubmit()) return;
            let this_ = this;

            this.toggleSubmitButton();

            const formData = new FormData();
            formData.append("firstname", this_.firstName);
            formData.append("lastname", this_.lastName);
            formData.append("timezone", this_.timezone);
            formData.append("email", this_.email);
            formData.append("department", this_.department);
            formData.append("job_title", this_.job);
            formData.append("role_id", this_.role);
            formData.append("organization_id", this_.organisationId);

            formData.append("phone", this_.telephone);
            formData.append("city", this_.city);
            formData.append("province", this_.province);
            if (this.setLimit) {
                formData.append("minimumLimit", this_.minimumLimit);
                formData.append("maximumLimit", this_.maximumLimit);
                formData.append("endDate", this_.endDate);
                formData.append("startDate", this_.startDate);
                formData.append("status", this_.setLimit);
            }
            if (this_.userId) {
                formData.append("user_id", this_.userId);
            }
            // console.log(this_.createroute);
            // return
            axios.post(JSON.parse(this_.createroute), formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {

                    let data = response?.data;

                    if (data.success) {
                        this.$swal({
                            title: (this_.userId) ? 'User Updated Successfully.' : 'User Created Successfully.',
                            text: data.message,
                            confirmButtonText: 'Close'
                        }).then(() => {

                            this_.toggleSubmitButton();
                            // window.location.href = JSON.parse(this_.listroute);
                            window.location.reload();
                        });
                    } else {

                        throw new Error(response.data.message)
                    }

                }).catch(error => {

                    this_.toggleSubmitButton();
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;

                    this.$swal({
                        title: 'Error',
                        text: error,
                        confirmButtonText: 'Close'
                    });
                });

        },
        cancel() {
            // [this.firstName, this.lastName, this.email,
            // this.telephone, this.department, this.role,
            // this.province, this.timezone, this.city,
            // this.job] = Array(10).fill("");
            [this.firstNameError, this.lastNameError,
            this.emailError, this.telephoneError, this.departmentError,
            this.roleError, this.provinceError, this.timezoneError,
            this.cityError, this.jobError, this.minimumLimit,
            this.maximumLimit, this.endDate] = Array(10).fill("");

            this.startDate = new Date();
        },
        toggleSubmitButton() {
            if (this.submitButtonSpinner) {
                this.submitButtonText = "Submit";
                this.submitButtonSpinner = false;
            } else {
                this.submitButtonText = "Please Wait...";
                this.submitButtonSpinner = true;
            }

        },
        closeModal() {
            this.$parent.closeModal();
        },
        flipLimit() {
            // console.log(this.setLimit)
        }
    }
};
</script>