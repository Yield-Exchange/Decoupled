<template>
    <div style="width: 100%;padding:25px;">
        <table class="table" style="width: 100%;">
            <thead class="customHeader">
                <tr>
                    <th>#</th>
                    <th>Counter Offer Date</th>
                    <th>Deposit Amount</th>
                    <th>Counter Rate</th>
                    <th>Interest Rate Change</th>
                    <th>Counter</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(value, index) in counterOffers" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <div class="textContainer">
                            {{ formatDateToCustomFormat(value?.created_at) }}</div>
                    </td>
                    <td>
                        <div class="textContainer">{{ datum.currency }} {{ addCommas(value?.maximum_amount) }}</div>
                    </td>
                    <td>
                        <div class="textContainer"> {{ (value?.offered_interest_rate).toFixed(2) }} %</div>
                    </td>
                    <td>
                        <div class="textContainer"> {{ (value?.offered_interest_rate -
                    datum?.rate).toFixed(2) }}
                            %</div>
                    </td>
                    <td>
                        <CustomInvitedStatusBadge :text="value?.status" />
                    </td>
                </tr>
            </tbody>


        </table>

   </div>
</template>


<script>
import CustomInvitedStatusBadge from "../single-offer/CustomInvitedStatusBadge.vue";
export default {
    components: {
        CustomInvitedStatusBadge
    },
    mounted() {
        //console.log('66 ', this.counterOffers);
    },
    name: 'CounterOfferLogs',
    props: ['datum', 'offers'],
    data() {
        return {

        }
    },
    methods: {
        formatDateToCustomFormat(inputDate) {
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        addCommas(newvalue) {
            return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    },
    computed: {
        counterOffers() {
            return this.offers.flatMap(item => item?.counter_offers ?? []);
        }
    }
}
</script>

<style>
.pending-button {
    display: flex;
    width: 120px;
    padding: 2px 12px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    color: #FFF;
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    text-transform: capitalize;
    background: #44E0AA;
}

.accept-button {
    display: flex;
    width: 120px;
    padding: 2px 12px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    color: #FFF;
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    text-transform: capitalize;
    background: #5063F4;
}

.counter-values {
    color: #252525;
    font-family: Montserrat;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.counter-labels {
    color: #000;
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.log-container {
    display: flex;
    padding: 10px;
    justify-content: space-between;
    gap: 34px;
    align-self: stretch;
    background: #EFF2FE;
    margin-bottom: 6px;
}

.header_section {
    color: #5063F4;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    text-transform: capitalize;
}
</style>
