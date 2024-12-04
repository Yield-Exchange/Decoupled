<template>

    <div
        style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
        <template v-if="hasCounters && requireAction">
            <div class="counter-state-container" style="">
                <div class="counter-state">
                    New Counter Offer</div>
            </div>
            <div class="rate-type-offer" style="">

                {{ rateTypeCheckCounter(counter_rate_type,
                counter_rate_operator,
                counter_rate_spread_rate_value,counter_rate_applied_prime) }}

            </div>
            <div class="divider-offer">
            </div>
            <p class="main-rate" style="color: rgb(244, 182, 60);">
                {{ counter_rate }}</p>
            <p class="strike-through">
                {{ interest_rate.toFixed(2) }}%</p>
        </template>
        <template v-else>
            <div class="counter-state-container-current" style="">
                <div class="counter-state-current">
                    Current Offer</div>
            </div>
            <div class="rate-type-offer" style="">
                {{ rateTypeCheck(rate_type, rate_operator, variable_rate_value) }}
            </div>
            <div class="divider-offer">
            </div>
            <p class="main-rate">
                {{ interest_rate.toFixed(2) }}%
            </p>
        </template>
        <div v-if="from_counter"
            style="flex-direction: column; justify-content: center; align-items: center; display: flex">
            <!-- <div
                style="padding: 5px; color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                {{ offer?.invitee?.organization?.name }}
            </div> -->
            <div class="my-2" v-if="!showInterestEarned"
                style="background-color: #EFF2FE;padding: 5px; color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                Interest Earned: <span style="white-space: nowrap;">{{ selectedCurrency + " " +
                    addCommas(new_offer_interest_rate) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { addCommasToANumber, rateTypeCheck, rateTypeCheckCounter } from '../../../utils/commonUtils';
    export default {
        props: ['showInterestEarned', 'rate_type', 'new_offer_interest_rate', 'selectedCurrency', 'offer', 'from_counter', 'rate_operator', 'interest_rate', 'counter_rate_applied_prime', 'counter_rate_type', 'counter_rate', 'variable_rate_value', 'counter_rate_operator', 'counter_rate_spread_rate_value', 'requireAction', 'hasCounters'],

        methods: {
            rateTypeCheck(x, y, z) {
                return rateTypeCheck(x, y, z)
            },
            rateTypeCheckCounter(x, y, z, w) {
                return rateTypeCheckCounter(x, y, z, w)
            },
            addCommas(number) {
                if (number)
                    return addCommasToANumber(number)
                else {
                    return '0.00'
                }
            }
        }
    }



</script>

<style scoped>
    .main-rate {
        text-align: center;
        color: #5063F4;
        font-size: 55px;
        font-family: Montserrat !important;
        font-weight: 700;
        word-wrap: break-word;
        margin-top: 10px;
    }

    .rate-type-offer {
        color: #252525;
        font-size: 16px;
        font-family: Montserrat !important;
        font-weight: 700 !important;
        text-transform: capitalize;
        word-wrap: break-word
    }

    .strike-through {
        color: #9CA1AA;
        text-align: center;
        font-family: Montserrat !important;
        font-size: 30px;
        font-style: italic;
        font-weight: 400;

        text-decoration-line: line-through;
    }

    .divider-offer {
        width: 128px;
        height: 0px;
        border: 0.51px #9CA1AA solid
    }

    .counter-state-container {
        width: 100%;
        padding: 5px;
        background: #44E0AA;
        justify-content: center;
        align-items: center;
        display: inline-flex
    }

    .counter-state {
        color: white;
        font-size: 14px;
        font-family: Montserrat !important;
        font-weight: 600;
        text-transform: capitalize;
        word-wrap: break-word
    }

    .counter-state-container-current {
        width: 100%;
        padding: 5px;
        background: #EFF2FE;
        justify-content: center;
        align-items: center;
        display: inline-flex
    }

    .counter-state-current {
        color: #5063F4;
        font-size: 14px;
        font-family: Montserrat !important;
        font-weight: 600;
        text-transform: capitalize;
        word-wrap: break-word
    }
</style>