<template>
    <div>
        <div v-show="loaded" style="max-height: 100vh;overflow-y: scroll; width: 100%;" class="pdf-holder">
            <vue-pdf-embed @loaded="pdfLoaded" @progress="loadingprogress" :scale="2" :height="500"
                :source="'/assets/' + file" />
        </div>
        <div v-show="!loaded" style="max-height: 100vh;overflow-y: scroll; width: 100%;" class="pdf-holder-2">
            <p class="section-header-tnc">Depositor account terms and conditions</p>
            <div style="height: 500px;position: relative;"
                class="d-flex justify-content-center align-items-center flex-column">
                <img src="/assets/signup/Loading-state.svg" alt="" height="400" width="400">
                <div
                    class="signup-terms-and-conds-loading-state-div d-flex justify-content-center align-items-center gap-2">
                    <p class="p-0 m-0 signup-terms-and-conds-loading-text">Loading...</p>
                    <div class="spinner-border spinner-collor" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>


<script>
import VuePdfEmbed from 'vue-pdf-embed/dist/vue2-pdf-embed'

export default {
    props: ['file'],
    components: {
        VuePdfEmbed
    },
    data() {
        return {
            loaded: false
        }
    },
    methods: {
        loadingprogress(events) {
            if (events.loaded >= events.total) {
                this.loaded = true
                this.$emit('showbuttons', true)

            } else {
                this.$emit('showbuttons', false)
                this.loaded = false
            }
        },
        pdfLoaded() {
            this.loaded = true
            this.$emit('showbuttons', true)
        }
    }
}
</script>

<style>
.vue-pdf-embed canvas {
    width: 100% !important;
    height: auto !important;
}
</style>


<style scoped>
.pdf-holder::-webkit-scrollbar {
    width: 5px;
}

.pdf-holder-2::-webkit-scrollbar {
    width: 0px;
}

.spinner-collor {
    color: #44E0AA;
    height: 25px;
    width: 25px;
}

.section-header-tnc {
    color: #5063F4;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Blue titles first letter capitalized */
    font-family: Montserrat;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    margin: 0 auto;
    width: 100%;
    text-align: center;
    /* 86.667% */
}

.signup-terms-and-conds-loading-text {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    text-align: center;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Chart Titles */
    font-family: Montserrat;
    font-size: 22px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* margin-top: -200px !important; */
    /* 118.182% */
}

.signup-terms-and-conds-loading-state-div {
    position: absolute;
    top: 260px;
}

/* Track */
.pdf-holder::-webkit-scrollbar-track {
    box-shadow: inset 0 0 2px #d4dcfc;
    border-radius: 10px;
}

/* Handle */
.pdf-holder::-webkit-scrollbar-thumb {
    background: #5063F4;
    border-radius: 10px;
}

/* Handle on hover */
.pdf-holder::-webkit-scrollbar-thumb:hover {
    /* background: #009eb3; */
}
</style>