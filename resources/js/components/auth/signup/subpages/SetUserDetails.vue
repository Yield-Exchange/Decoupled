<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <p class="title">Who will be using Yield Exchange?</p>
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/signup/userdetailsshort.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>


            <div class="row w-100">
                <div class="col-md-6 mb-10">
                    <CustomTextInput @hasError="(checkerVariable) => firstNameError = checkerVariable"
                        :isemptycheck="requiredChecker" :currentValue="getLoggedInUser.first_name" v-model="firstName"
                        :required="true" label="First Name" placeholder="Enter First Name" input_type="text" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTextInput @hasError="(checkerVariable) => lastNameError = checkerVariable"
                        :isemptycheck="requiredChecker" :currentValue="getLoggedInUser.last_name" v-model="lastName"
                        :required="true" label="Last Name" placeholder="Enter Last Name" input_type="text" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTextInput :isemptycheck="requiredChecker" :currentValue="getLoggedInUser.email"
                        v-model="email" :required="true" label="Email" :disabled="true" aria-disabled="true"
                        :minlength="4" :maxlength="150" placeholder="Enter Email Address" input_type="email" />
                </div>
                <div class="col-md-6 mb-10">
                    <CustomTelInput @hasError="(checkerVariable) => telephoneError = checkerVariable"
                        :isemptycheck="requiredChecker" :currentValue="getLoggedInUser.phone" v-model="telephone"
                        :required="true" label="Telephone" placeholder="Enter telephone Here" input_type="tel" />
                </div>

                <div class="col-md-6 mb-10">
                    <CustomTextInput @hasError="(checkerVariable) => jobTitleError = checkerVariable"
                        :isemptycheck="requiredChecker" :minlength="2" :maxlength="150"
                        :currentValue="getUpdatedUserInfo != null ? getUpdatedUserInfo.job_title : null"
                        v-model="jobTitle" :required="true" :tooltip="true"
                        tooltiptitle="Enter your role here eg (Treasurer, Secretary etc)" label="Job Title"
                        placeholder="Enter Job Title" input_type="text" />
                </div>
                <div class="col-md-6 mb-10" @click="chooseTimezoneModal = true">
                    <CustomTextInput :isemptycheck="requiredChecker" v-model="timeZone" :required="true"
                        label="Timezone" :currentValue="timeZone" :tooltip="true" :minlength="4" :maxlength="150"
                        tooltiptitle="We prefilled this field based on your current timezone , feel free to change it"
                        placeholder="Set Based on IP Address" input_type="timezone" />
                </div>
                <div class="col-md-12 mb-3">
                    <CustomTextInput @hasError="(checkerVariable) => linkedInUrlError = checkerVariable"
                        :isemptycheck="requiredChecker" :tooltip="true" :minlength="4" :maxlength="150"
                        tooltiptitle="Provide us with your linkedIn url , it will help the Financial Institutions know you better"
                        :currentValue="getUpdatedUserInfo != null ? getUpdatedUserInfo.linkedin : null"
                        v-model="linkedInUrl" label="LinkedIn Profile " placeholder="Enter your LinkedIn Profile "
                        input_type="url" />
                </div>
            </div>
        </div>
        <!-- <CustomSubmit :outline="true" title="Register Later" /> -->
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <GoBack @action="goBack" :outline="true" title="Previous" />
            <CustomSubmit :isLoading="sending" @action="submitForm" :outline="false" title="submit" />
        </div>
        <!-- modals -->
        <PopUpModal @close="chooseTimezoneModal = false" :show="chooseTimezoneModal" sync="" width="600">
            <TitleWithIcon icon="location.svg">We identified your timezone as <br>
                <span style="color: #5063F4 !important;">{{ timeZone }}</span> <br>
                Confirm the time zone
            </TitleWithIcon>

            <CustomSelectInput v-model="newTimezone" class="my-4" :options="getTimezones"
                placeholder="Or select a new timezone">
            </CustomSelectInput>
            <div class="w-100 d-flex justify-content-end my-3 gap-2">
                <CustomSubmit @action="submitTimezones" :outline="false" title="submit" />
            </div>
        </PopUpModal>
        <ActionMessageModal @close="timezoneErrorModal = false" :show="timezoneErrorModal" width="600"
            title="Coming Soon to you region!" icon="signup/danger.svg" primarybuttontext="Join Our Waitlist"
            outlinedbuttontext="Change Address"
            message="Sorry we're not available in your area yet, but stay tuned as we're expanding into other markets soon!"
            @outlinedClicked="changeAddress" @primaryClicked="waitingList">
        </ActionMessageModal>

        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Ooops! An issue occured"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext="" :message="failmessage" outlined="">
        </ActionMessageModal>
    </div>
</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import ChangeAdress from '../shared/CustomSubmit.vue';
import GoBack from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomTelInput from '../shared/CustomTelInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import Checkbox from '../shared/Checkbox.vue';
import PopUpModal from '../shared/PopUpModal.vue';
import ActionMessageModal from '../shared/ActionMessageModal.vue';
import TimezoneErrorModal from '../shared/PopUpModal.vue';
import TitleWithIcon from '../shared/TitleWithIcon.vue';

export default {
    props: ['ipinfokey'],
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput, Checkbox, ActionMessageModal,
        PopUpModal, TitleWithIcon, TimezoneErrorModal, ChangeAdress, GoBack, CustomTelInput
    },
    mounted() {
        this.getTimeZone();
        this.$store.dispatch('setStageTitle', 'Enter your details to complete registration')
        this.$store.dispatch('setProgress', 40)

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
            foundkey: null,
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
            sending:false,
            failmessage: 'Please ensure all fields are filled in correctly and retry. If the issue persists, please contact us at info@yieldexchange.ca.',
        }
    },
    computed: {
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getUpdatedUserInfo() {
            return this.$store.getters.getUpdatedUserInfo
        },
        getTimezones() {
            this.ourTimezones = this.$store.getters.getTimezones
            return this.$store.getters.getTimezones
        },

    },
    methods: {
        submitForm() {
            this.requiredChecker = false
            this.canSubmit();
            if (this.canSubmit()) {
                this.submitTimezones()

                if (this.okTimezone) {
                    this.submitAction()
                }
                // this.goNext()
            } else {
                this.requiredChecker = true
            }
        },
        canSubmit() {
            if (this.firstName != null
                && this.lastName != null
                && this.email != null
                && this.telephone != null
                && this.jobTitle != null
                && this.timeZone != null
                && !this.linkedInUrlError
                && !this.firstNameError
                && !this.lastNameError
                && !this.telephoneError
                && !this.jobTitleError
            ) {
                this.updateWaitingStore()
                return true;
            }
            else {
                return false
            }
        },
        updateWaitingStore() {
            const loogeddata = {
                first_name: this.firstName,
                phone: this.telephone,
                last_name: this.lastName,
                user_id: this.getLoggedInUser.user_id,
                email: this.email,
            }
            const updatedUser = {
                job_title: this.jobTitle,
                timezone: this.timeZone,
                foundkey: this.foundkey,
                linkedin: this.linkedInUrl
            }
            this.$store.dispatch('setLoggedInUser', loogeddata)
            this.$store.dispatch('updateUserInfo', updatedUser)
        },
        async submitAction() {
            this.sending=true
            const updated_user = {
                first_name: this.firstName,
                telephone: this.telephone,
                last_name: this.lastName,
                user_id: this.getLoggedInUser.user_id,
                job_title: this.jobTitle,
                timezone: this.foundkey,
                linkedin: this.linkedInUrl,
                join_waiting: 0,
                from: "sign_up"
            }
            // console.log(updated_user)
            await axios.post('/update-user-info', updated_user, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
            this.sending=false
                if (response.data.success) {
                    this.goNext()
                    this.$store.dispatch('setStepsTracker', 'userDetails')

                    // console.log("Data Updated")
                } else {
                    this.fail = true
                    // this.failmessage = this.data.message
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                }
            }).catch(err => {
            this.sending=false
                this.fail = true
                // this.failmessage = this.data.message
                setTimeout(() => {
                    this.fail = false

                }, 3000)
            })
        },
        async getTimeZone() {
            try {
                const response = await axios.get(`https://ipinfo.io/json?token=${this.ipinfokey}`, { mode: 'no-cors' });
                const data = response.data;
                const timeZone = data.timezone || 'Unknown';
                this.timeZone = timeZone;
                this.myTimezone = timeZone;
                // console.log(timeZone)
            } catch (error) {
                // console.error('Error fetching time zone:', error);
                this.timeZone = 'Unknown';
            }
        },
        goNext() {
            this.$store.dispatch('setCurrentStep', 'setpassword')
        },
        goBack() {
            this.$store.dispatch('setCurrentStep', 'depOrgDetails')
        },
        submitTimezones() {
            this.chooseTimezoneModal = false
            // console.log(Object.keys(this.ourTimezones))
            if (this.newTimezone != null) {
                if (this.newTimezone == this.timeZone) {
                    // console.log("timezone Matches")
                    Object.entries(this.ourTimezones).forEach(([key, value]) => {
                        if (value.includes(this.newTimezone)) {
                            this.foundkey = key
                        }
                    });
                    this.okTimezone = true
                } else {
                    if (Object.values(this.ourTimezones).some(timezone => timezone.includes(this.myTimezone))) {
                        Object.entries(this.ourTimezones).forEach(([key, value]) => {
                            if (value.includes(this.myTimezone)) {
                                this.foundkey = key
                            }
                        });
                        this.okTimezone = true
                    } else {
                        this.timezoneErrorModal = true
                        this.foundkey = this.myTimezone

                        this.okTimezone = false

                    }
                }
            } else {
                if (Object.values(this.ourTimezones).some(timezone => timezone.includes(this.myTimezone))) {
                    Object.entries(this.ourTimezones).forEach(([key, value]) => {
                        if (value.includes(this.myTimezone)) {
                            this.foundkey = key
                        }
                    });
                    this.okTimezone = true

                } else {
                    this.okTimezone = false
                    this.foundkey = this.myTimezone
                    this.timezoneErrorModal = true
                    // console.log("timezone error")
                }

            }
        },
        changeAddress() {
            this.timezoneErrorModal = false
            this.chooseTimezoneModal = true
        },
        waitingList() {
            this.updateWaitingStore()
            this.timezoneErrorModal = false
            this.$store.dispatch('setCurrentStep', 'biwaiting')
            this.$store.dispatch('setPrevStep', 'userDetails')

        }

    },
    watch: {
        newTimezone(newValue, oldVal) {
            if (newValue != this.timeZone) {
                this.timeZone = newValue
            }

        }
    }
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
    font-family: Montserrat !important;
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
    font-family: Montserrat !important;
    font-size: 18px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 144.444% */
    text-transform: capitalize;
}

.thirdpartyHead {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    padding: 0;
    margin: 0 !important;
}

.thirdpartytext {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-family: Montserrat !important;
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

    font-family: Montserrat !important;
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
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */

}
</style>