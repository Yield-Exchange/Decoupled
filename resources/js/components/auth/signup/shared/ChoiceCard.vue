<template>
    <div>
        <div @click="updateUserType" :class="(hover ? 'headerHover' : 'acc-type-header')" @mouseleave="hover = false" style="width: 600px !important;"
            @mouseenter="hover = true">
            <div class="d-flex">
                <img :src="'/assets/signup/' + image" width="40" height="40" alt="">

                <h2 class="acc-header-title" :class="(hover ? 'hovered-text ' : 'not-hovered-text')">{{
                    title }}</h2>
                <!-- <img src="assets/signup/yie-Logo.svg" alt=""> -->

            </div>
            <hr>
            <p class="acc-header-desc" :class="(hover ? 'hovered-text ' : 'not-hovered-text')"> {{ desc
            }} </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['title', 'desc', 'image', 'userType', 'stage'],
    data() {
        return {
            message: "choose Accpount",
            hover: false
        }
    },
    methods: {
        updateUserType() {
            if (this.stage == 'landing') {
                if (this.userType == 'depositor') {
                    this.$store.dispatch('setUserType', this.userType)
                    this.$store.dispatch('setCurrentStep', 'deptype')
                    this.$store.dispatch('setPrevStep', this.stage)
                } else if (this.userType == 'waiting') {
                    this.$store.dispatch('setUserType', this.userType)
                    this.$store.dispatch('setCurrentStep', 'waitinglistdetails')
                    this.$store.dispatch('setPrevStep', this.stage)

                } else if (this.userType == 'bank') {
                    this.$store.dispatch('setUserType', this.userType)
                    // window.location.href = 'sign-up'
                    this.$store.dispatch('setCurrentStep', 'depOrgDetails')
                    this.$store.dispatch('setPrevStep', this.stage)

                }
            } else if (this.stage == 'deptype') {
                if (this.userType == 'personalInvestor') {
                    this.$store.dispatch('setCurrentStep', 'waiting')
                    this.$store.dispatch('setPrevStep', this.stage)
                    this.$store.dispatch('setDepositorType', this.userType)
                }
                else if (this.userType == 'businessInvestor') {
                    this.$store.dispatch('setCurrentStep', 'depOrgDetails')
                    this.$store.dispatch('setPrevStep', this.stage)
                    this.$store.dispatch('setDepositorType', this.userType)
                }
            }
        }
    },
    watch: {
        hover() {
            // console.log(this.hover)
        }
    },
}
</script>

<style scoped>
.acc-type-header {
    background-color: white;
    border-radius: 4px;
    /* background: #FFF; */
    box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
    padding: 10px;
    margin-bottom: 10px;
    cursor: pointer;
}

.headerHover {
    /* background-color: white; */
    border-radius: 4px;
    /* background: #FFF; */
    box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
    padding: 10px;
    margin-bottom: 10px;
    background-color: #5063F4 !important;
    cursor: pointer;
}

.acc-header-title {

    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
    margin-left: 10px;
}

.not-hovered-text {
    color: #252525;
}

.hovered-text {
    color: white;
}

.acc-header-desc {
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: normal;
}
</style>