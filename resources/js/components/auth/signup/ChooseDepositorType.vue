<template>
    <div>

        <div class="row justify-content-center">
            <div class="w-100">


                <ChoiceCard stage="deptype" userType="businessInvestor" title="Organizational Investments"
                image="orgainvestmest.svg"
                desc="I'm using Yield Exchange for my organization" />
                <ChoiceCard stage="deptype" userType="personalInvestor" title="Personal Investments"
                    image="pinves.svg" desc="Coming soon! Click here to join our waitlist" />
                
                <div class="w-100 mt-2 d-flex justify-content-end">
                    <CustomSubmit @action="goBack" :outline="true" title="Previous">
                    </CustomSubmit>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ChoiceCard from './shared/ChoiceCard.vue'
import CustomSubmit from './shared/CustomSubmit.vue';

export default {
    components: { ChoiceCard, CustomSubmit },
    mounted() {
        this.$store.dispatch('setStageTitle', 'What type of investment are you interested in?')
        this.$store.dispatch('setProgress', 20)

    },
    computed: {
        getDepositorFICount() {
            return this.$store.getters.getDepositorFICount
        }
    },
    data() {
        return {
            message: "choose Accpount"
        }
    },
    methods: {
        goBack() {
            this.$store.dispatch('setCurrentStep', 'landing')
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
</style>

