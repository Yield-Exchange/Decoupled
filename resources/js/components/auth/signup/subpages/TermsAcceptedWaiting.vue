<template>
    <div class="w-100 d-flex flex-column justify-content-end align-items-center">
        <div class="w-75 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/signup/accountcreated.svg" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>

            <!-- <p class="title">Please set your user details </p> -->
            <div class="d-flex flex-column gap-2 my-3">
                <p class="title top-message">Your account is currently being reviewed.<br> You’ll have full access to
                    the
                    site once you have been approved.</p>

            </div>

        </div>
        <div class="w-75 d-flex justify-content-end my-3 gap-2">
            <CustomSubmit @action="exit" :outline="false" title="Exit" />
        </div>
    </div>
</template>

<script>

import axios from 'axios'
import CustomSubmit from '../shared/CustomSubmit.vue'

export default {
    components: {
        CustomSubmit

    },
    mounted() {
        this.$store.dispatch('setStageTitle', 'Your account setup is complete.')
        this.$store.dispatch('setProgress', 100)

    },
    methods: {
        exit() {
            axios.get('/logout')
            window.location.href = 'https://yieldexchange.ca/'
        }
    }

}
</script>

<style scoped>
.can-do {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 36px;
    font-style: normal;
    font-weight: 700;
    line-height: 40px;
    /* 111.111% */
}

.w-80 {
    width: 75%;
}

.top-message {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.message {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 300;
    line-height: normal;
}

.action-section {
    background: #F4F5F6;
    gap: 10px;
    padding: 10px;
}

.action-section p {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.action-section p span {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}
</style>