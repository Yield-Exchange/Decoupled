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
                        <img src="/assets/signup/waitinglist.svg" style="max-height: 400px; margin: 20px auto;" alt=""
                            srcset="">
                    </div>

                    <p class="description">
                        {{ getWaitingText }}</p>


                </div>
                <div class="d-flex justify-content-end w-100 my-3 mr-4 gap-4">
                    <CustomSubmit class="d-none" @action="goBack" :outline="true" title="Previous" />
                    <CustomSubmit :isLoading="sending" @action="addToWaitList" title="OK" />
                </div>
            </div>
        </div>

        <ActionMessageModal @close="success = false" :show="success" width="600" title="Congratulations!"
            icon="signup/success_promo.svg" primarybuttontext="" outlinedbuttontext=""
            message="You're officially on our waiting list.Keep an eye on your inbox for updates about our launch. "
            outlined="">
        </ActionMessageModal>

        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Ooops!An issue occured"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext="" :message="failmessage" outlined="">
        </ActionMessageModal>


    </div>
</template>

<script>

import CustomSubmit from './shared/CustomSubmit.vue';
import PopUpModal from './shared/PopUpModal.vue';
import TitleWithIcon from './shared/TitleWithIcon.vue';
import ActionMessageModal from './shared/ActionMessageModal.vue';

export default {
    components: {
        CustomSubmit, ActionMessageModal, TitleWithIcon
    },
    mounted() {
        this.$store.dispatch('setStageTitle', '')
        this.$store.dispatch('setProgress', 100)

        // this.addToWaitList()
    }, data() {
        return {
            success: false,
            fail: false,
            failmessage: 'Please retry to submit again or contact admin info@yieldexhange.ca',
            sending: false

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
        }
    }, methods: {
        goBack() {
            this.$store.dispatch('setCurrentStep', 'deptype')
        },
        addToWaitList() {
            if (this.getDepositorType == 'personalInvestor') {
                this.sending = true
                const data = {
                    is_individual: 1,
                    type: 'depositor',
                    user_id: this.getLoggedInUser.user_id,
                    user_email: this.getLoggedInUser.email,
                }

                axios.post('/depositor-pesonal-create-organization', data, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    this.sending = false
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
                    this.sending = false
                    this.fail = true
                    // this.failmessage = this.data.message
                    setTimeout(() => {
                        this.fail = false

                    }, 3000)
                })
            } else {
                console.log("Depositor Type", this.getDepositorType)
            }

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

.description {
    color: #252525;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */

}
</style>