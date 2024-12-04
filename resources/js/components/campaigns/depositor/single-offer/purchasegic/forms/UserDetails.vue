<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <p class="title">Update your profile details!</p>
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/dashboard/images/userdetails.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>
            <div class="row w-100">
                <div class="col-md-6 mb-10">
                    <CustomTextInput @hasError="(checkerVariable) => firstNameError = checkerVariable"
                        :isemptycheck="requiredChecker" :currentValue="firstName" v-model="firstName" :required="true"
                        label="First Name" placeholder="John" input_type="text" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTextInput @hasError="(checkerVariable) => lastNameError = checkerVariable"
                        :isemptycheck="requiredChecker" :currentValue="lastName" v-model="lastName" :required="true"
                        label="Last Name" placeholder="Doe" input_type="text" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTextInput :isemptycheck="requiredChecker" :currentValue="email" v-model="email" :required="true"
                        label="Email" :disabled="true" aria-disabled="true" placeholder="Johndoe@org.com"
                        input_type="email" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTelInput @hasError="(checkerVariable) => telephoneError = checkerVariable"
                        :isemptycheck="requiredChecker" :currentValue="telephone" v-model="telephone" :required="true"
                        label="Telephone" aria-disabled="true" placeholder="+1 34345456 " input_type="tel" />
                </div>

                <div class="col-md-6 mb-10">
                    <CustomTextInput @hasError="(checkerVariable) => jobTitleError = checkerVariable" :minlength="2"
                        :maxlength="150" :isemptycheck="requiredChecker" :currentValue="jobTitle" v-model="jobTitle"
                        :required="true" :tooltip="true" tooltiptitle="Enter your role here eg (Treasurer, Secretary etc)"
                        label="Job Title" placeholder="Enter Job Title" input_type="text" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTextInput :isemptycheck="requiredChecker" v-model="timeZone" :required="true" label="Timezone"
                        :currentValue="timeZone" :disabled="true" :tooltip="true"
                        tooltiptitle="This is same as what you set up as you were signing in."
                        placeholder="Your timezone here" input_type="timezone" />
                </div>
                <div class="col-md-12 mb-3">
                    <CustomTextInput @hasError="(checkerVariable) => linkedInUrlError = checkerVariable"
                        :isemptycheck="requiredChecker" :tooltip="true" :minlength="2" :maxlength="150"
                        tooltiptitle="Provide us with your linkedIn url , it will help the Financial Institutions know you better"
                        :currentValue="linkedInUrl" v-model="linkedInUrl" label="LinkedIn Profile "
                        placeholder="Enter your LinkedIn Profile " input_type="url" />
                </div>
            </div>
        </div>
        <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <Button @click="goBack" type="outlined">Previous</Button>
            <Button @click="submitForm" type="primary">Next</Button>
        </div>
        <ErrorMessage style=" width: 600px;" @closedSuccessModal="fail = false"
            icon="/assets/dashboard/icons/Promo-error.svg" title="Oops! We failed to update your data. Please try again"
            :showm="fail" btnOneText="" btnTwoText="">
        </ErrorMessage>

    </div>
</template>

<script>

// import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import Button from '../../../../../shared/Buttons/Button.vue';
import CustomTelInput from '../shared/CustomTelInput.vue'
import ErrorMessage from '../../../../../shared/messageboxes/ActionMessageBox.vue';
import CustomTextInput from '../../../../../auth/signup/shared/CustomTextInput.vue';



export default {
    props: ['user'],
    components: {
        CustomTextInput, CustomSelectInput, CustomTelInput,
        Button, ErrorMessage
    },
    mounted() {

        if (this.user) {
            // console.log("User Details Mounted")
            console.log(this.user)
            this.getUser()
        }
    },
    data() {
        return {
            firstName: null,
            lastName: null,
            email: null,
            telephone: null,
            jobTitle: null,
            timeZone: null,
            linkedInUrl: null,
            requiredChecker: false,
            newTimezone: null,
            myTimezone: null,
            ourTimezones: null,
            chooseTimezoneModal: false,
            timezoneErrorModal: false,
            firstNameError: false,
            lastNameError: false,
            telephoneError: false,
            jobTitleError: false,
            linkedInUrlError: false,
            okTimezone: false,
            fail: false,
            failmessage: 'Please ensure all fields are filled in correctly and retry. If the issue persists, please contact us at info@yieldexchange.ca.',
        }
    },
    methods: {
        updateFactory() {
            this.$emit('updateFactory', 'userdetails')
        },
        goBack() {
            this.$emit('goBack', 'userdetails')
        },
        submitForm() {
            this.requiredChecker = false
            this.canSubmit();
            if (this.canSubmit()) {
                this.submitAction()
            } else {
                this.requiredChecker = true
            }
        },
        canSubmit() {
            if (this.firstName != null
                && this.lastName != null
                && this.email != null
                && this.telephone != ""
                && this.telephone != null
                && this.jobTitle != null
                && this.timeZone != null
                && !this.linkedInUrlError
                && !this.firstNameError
                && !this.lastNameError
                && !this.telephoneError
                && !this.jobTitleError
            ) {
                // this.updateWaitingStore()
                return true;
            }
            else {
                return false
            }
        },
        async submitAction() {
            const updated_user = {
                first_name: this.firstName,
                telephone: this.telephone,
                last_name: this.lastName,
                user_id: this.user.id,
                job_title: this.jobTitle,
                timezone: this.timeZone,
                linkedin: this.linkedInUrl,
                // join_waiting: 0
            }
            // console.log(updated_user)
            await axios.post('/user-update', updated_user, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.data.success) {
                    // console.log("Data Updated")
                    this.$emit('getUserData')
                    this.updateFactory()
                } else {
                    this.fail = true
                    // this.failmessage = this.data.message
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                }
            }).catch(err => {
                this.fail = true
                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false

                }, 3000)
            })
        },
        getUser() {
            let user = this.user
            this.firstName = user.firstname
            this.lastName = user.lastname
            this.email = user.email
            this.telephone = user.demographic_data.phone
            this.jobTitle = user.demographic_data.job_title
            this.timeZone = user.demographic_data.timezone
            this.linkedInUrl = user.demographic_data.linkedin
        }
    },

}
</script>

<style scoped>
.top-title {
    margin-bottom: 30px;
}

.entity_question {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 16px;
    /* 100% */
}

.notice {
    color: #2A2A2A;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

.sectionHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 144.444% */
    text-transform: capitalize;
}

.thirdpartyHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    padding: 0;
    margin: 0 !important;
}

.thirdpartytext {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 18px;
    padding: 0;
    /* 112.5% */
}


.textarea {
    border-radius: 10px;
    border: 1px solid #D9D9D9;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
}

.top-title p {
    color: #252525;

    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.title {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */

}
</style>