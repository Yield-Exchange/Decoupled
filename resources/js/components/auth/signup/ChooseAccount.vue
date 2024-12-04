<template>
    <div>

        <div class="row justify-content-center">
            <div class="w-100">
                <!-- <div class="top-title">
                    <p>What type of account would you like to set up</p>
                </div> -->

                <ChoiceCard stage="landing" userType="depositor" title="Corporate" image="depositor.svg"
                    :desc="`Connect immediately to ${getDepositorFICount[1]}  financial institutions`" />
                <ChoiceCard stage="landing" userType="bank" title="Financial Institution" image="fi.svg"
                    :desc="`Connect with ${getDepositorFICount[0]} depositors by submitting rates`" />
                <!-- <ChoiceCard stage="landing" userType="depositor" title="Depositor" image="depositor.svg"
                    :desc="`Connect immediately to ${getDepositorFICount[1]}  financial institutions`" />
                <ChoiceCard stage="landing" userType="fi" title="Financial Institution" image="fi.svg"
                    :desc="`Connect with ${getDepositorFICount[0]} depositors by submitting rates`" /> -->
                <!-- <ChoiceCard stage="landing" userType="waiting" title="Join Our Waitlist" image="waitinglisticon.svg"
                    desc="Join the waiting list to keep and get weekly rate update" /> -->
            </div>
        </div>
    </div>
</template>

<script>
import ChoiceCard from './shared/ChoiceCard.vue'
export default {
    components: { ChoiceCard },
    mounted() {
        this.$store.dispatch('setProgress', 10)
        this.$store.dispatch('setStageTitle', 'What type of account are you setting up?')

    },
    data() {
        return {
            message: "choose Accpount"
        }
    },
    computed: {
        getDepositorFICount() {
            return this.$store.getters.getDepositorFICount
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
