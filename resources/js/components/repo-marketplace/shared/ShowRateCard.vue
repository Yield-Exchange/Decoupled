<template>

    <div
        style="width:100%; height: 100%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
        <!-- <template v-if="hasCounters && requireAction">
            <div class="counter-state-container" style="">
                <div class="counter-state">
                    New Counter Offer</div>
            </div>
            <div class="rate-type-offer" style="">
                {{ rateTypeCheck(counter_rate_type, counter_rate_rate_operator, counter_rate_variable_rate_value) }}
            </div>
            <div class="divider-offer">
            </div>
            <p class="main-rate">
                {{ counter_rate }}</p>
            <p class="strike-through">
                {{ interest_rate.toFixed(2) }}%</p>
        </template> -->
        <template>
            <div class="counter-state-container-cancel" v-if="needsRespond" style="">
                <div class="counter-state-cancel">Cancellation in progress</div>
            </div>
            <template v-else>
                <div class="show-rate-card current-offer" v-if="counterStatus == 'current'" style="">
                    <div class="counter-text">
                        Current Offer</div>
                </div>
                <div class="show-rate-card counter-pending" v-if="counterStatus == 'pending'" style="">
                    <div class="counter-text">
                        Counter Pending <br> {{ latest_counter.offer_interest_rate.toFixed(2)
                        }}%</div>
                </div>
                <div class="show-rate-card accepted-offer" v-if="counterStatus == 'acceptedoffer'" style="">
                    <div class="counter-text">
                        Accepted Counter</div>
                </div>
            </template>
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
            <div class="my-2"
                style="background-color: #EFF2FE;padding: 5px; color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                Interest Earned: <span style="white-space: nowrap;">{{ selectedCurrency + " " +
                addCommas(new_offer_interest_rate) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import { addCommasToANumber, rateTypeCheck } from '../../../utils/commonUtils';
export default {
    props: ['rate_type', 'counters', 'needsRespond', 'new_offer_interest_rate', 'selectedCurrency', 'offer', 'from_counter', 'rate_operator', 'interest_rate', 'counter_rate_type', 'counter_rate', 'variable_rate_value'],
    beforeMount() {
        if (this.counters && this.counters.length > 0) {

            let latest_counter = this.counters[0]
            let status = latest_counter.status ? latest_counter.status.toLowerCase() : 'accepted'
            this.latest_counter = latest_counter
            if (status == 'pending') {
                this.counterStatus = 'pending'
            } else
                if (status == 'countered' || status == 'accepted') {
                    this.counterStatus = 'acceptedoffer'
                }
            console.log(this.counters, "Counters")
        }
    },
    data() {
        return {
            latest_counter: null,
            hasCounters: false,
            requireAction: false,
            counterStatus: 'current'
        }
    },
    methods: {
        rateTypeCheck(x, y, z) {
            return rateTypeCheck(x, y, z)
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

.current-offer {
    color: #5063F4;
    background: #EFF2FE;
}

.counter-state-container-cancel {
    width: 100%;
    padding: 5px;
    background: #FFEBEB;
    justify-content: center;
    align-items: center;
    display: inline-flex
}

.show-rate-card {
    width: 100%;
    padding: 5px;
    justify-content: center;
    align-items: center;
    display: inline-flex
}

.show-rate-card div {
    text-align: center;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: 24px;
    /* 171.429% */
    text-transform: capitalize;
}

.counter-state-cancel {
    color: #FF2E2E;
    font-size: 14px;
    font-family: Montserrat !important;
    font-weight: 600;
    text-transform: capitalize;
    word-wrap: break-word
}

.counter-pending {
    background: rgba(244, 182, 60, 0.50);
    color: #C2850D;
}

.accepted-offer {
    color: #EAF5EA;
    background: #44E0AA;
}
</style>