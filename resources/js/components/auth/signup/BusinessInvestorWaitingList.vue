<template>
    <div>
        <div class="row justify-content-center">

            <div class="w-50">
                <!-- <div class="top-title">
                    <p>Enter your details To Finish Creating your Account as a depositor</p>
                </div> -->
                <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
                    <p class="title">Thanks for joining our waitlist</p>
                    <div class="d-flex justify-ontent-center w-100">
                        <img src="/assets/signup/waiting-list.png" style="max-height: 400px; margin: 20px auto;" alt=""
                            srcset="">
                    </div>

                    <p class="description">
                        {{ getWaitingText }}</p>


                </div>
                <div class="d-flex justify-content-end w-100 my-3 mr-4 gap-3">
                    <GoBack @action="goBack" :outline="true" title="Previous" />
                    <CustomSubmit @action="addToWaitList" title="Submit" />
                </div>
            </div>
        </div>
        <PopUpModal @close="success = false" :show="success" width="600">
            <TitleWithIcon title="Congratulations! You're officially on our waiting list" icon="passcheck.png">

            </TitleWithIcon>
            <p class="passwordmessage mt-3 mb-5">Keep an eye on your inbox for updates about our launch. </p>
        </PopUpModal>

        <PopUpModal @close="fail = false" :show="fail" width="600">
            <TitleWithIcon title="Ooops!An issue occured" icon="infoicon.png">

            </TitleWithIcon>
            <p class="passwordmessage mt-3 mb-5"> {{ failmessage }}</p>
        </PopUpModal>
    </div>
</template>

<script>

import CustomSubmit from './shared/CustomSubmit.vue';
import GoBack from './shared/CustomSubmit.vue';
import PopUpModal from './shared/PopUpModal.vue';
import TitleWithIcon from './shared/TitleWithIcon.vue';

export default {
    components: {
        CustomSubmit, PopUpModal, TitleWithIcon,GoBack
    },
    mounted() {
        this.$store.dispatch('setStageTitle', '')
        this.$store.dispatch('setProgress', 100)

        // this.addToWaitList()
    }, data() {
        return {
            success: false,
            fail: false,
            failmessage: 'Please retry to submit again or contact admin info@yieldexhange.ca'

        }
    },
    computed: {
        getWaitingText() {
            return this.$store.getters.getWaitingText
        },
        getDepositorType() {
            return this.$store.getters.getDepositorType
        },
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getUpdatedUserInfo() {
            return this.$store.getters.getUpdatedUserInfo
        },
        getPrevStep() {
            return this.$store.getters.getPrevStep
        }
    },
    methods: {
        goBack() {
            this.$store.dispatch('setCurrentStep', this.getPrevStep)
        },
        addToWaitList() {
            const updated_user = {
                first_name: this.getLoggedInUser.first_name,
                telephone: this.getLoggedInUser.phone,
                last_name: this.getLoggedInUser.last_name,
                user_id: this.getLoggedInUser.user_id,
                job_title: this.getUpdatedUserInfo.job_title,
                timezone: this.getUpdatedUserInfo.timezone,
                likendin: this.getUpdatedUserInfo.linkedin,
                join_waiting: 1
            }
            axios.post('/update-user-info', updated_user, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.href = "https://yieldexchange.ca/"
                    }, 5000)
                    // console.log("Data Updated")
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

        }
    }
}
</script>
<style scoped>
.top-title {
    margin-bottom: 30px;
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
    font-family: Montserrat;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;

}

.description {
    color: #252525;
    text-align: center;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */

}
</style>