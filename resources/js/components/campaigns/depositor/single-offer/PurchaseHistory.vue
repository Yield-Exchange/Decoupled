<template>
    <div>
        <div
            style="flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; display: flex; width: 95%;">
            <table class="table" style="background: transparent; width:100%; border-style: none !important;">
                <thead class="customHeader1" style="background: rgba(80, 99, 244, 0.05);border-collapse: collapse;">
                    <tr class="customHeader1"
                        style="border: none!important; border-radius: 10px 10px 0px 0px;background: rgba(80, 99, 244, 0.05); padding: 10px;">
                        <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px; text-align: left">
                            Offer purchase Date</th>
                        <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px; text-align: left">
                            Currency</th>
                        <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px; text-align: left">
                            Amount</th>
                        <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px; text-align: left">
                            Rate</th>
                        <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px; text-align: left">
                            Status</th>
                        <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px; text-align: left">
                            Interest earned</th>
                        <!-- <th
                            style="background-color: rgba(80, 99, 244, 0.05);color: #000;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize; padding: 10px;">
                            Action</th> -->

                    </tr>
                </thead>
                <tbody class="customBody1"
                    style="background: transparent;border-collapse: collapse; border: none!important;">
                    <tr style="background-color:#FFF; border: none!important;" v-for="(item, index) in offersDisplay"
                        :key="index">
                        <td
                            style="color: #000;font-family: Montserrat;font-size: 15px;font-style: normal;font-weight: 400;line-height: normal; padding: 20px; text-align: left">
                            {{ formatDateToCustomFormat(item.created_date) }}</td>
                        <td
                            style="color: #000;font-family: Montserrat;font-size: 15px;font-style: normal;font-weight: 400;line-height: normal; padding: 20px; text-align: left">
                            {{ item?.invited?.deposit_request?.currency }}</td>
                        <td
                            style="color: #000;font-family: Montserrat;font-size: 15px;font-style: normal;font-weight: 400;line-height: normal; padding: 20px; text-align: left">
                            {{ addCommas(item?.invited?.deposit_request?.amount) }}</td>
                        <td
                            style="color: #000;font-family: Montserrat;font-size: 15px;font-style: normal;font-weight: 400;line-height: normal; padding: 20px; text-align: left">
                            {{ parseFloat(item?.interest_rate_offer).toFixed(2) }}%</td>
                        <td v-if="item?.offer_status == 'SELECTED'">
                           <CustomInvitedStatusBadge text="PENDING" />
                        </td>
                        <td v-else>
                           <CustomInvitedStatusBadge :text="item?.offer_status" />
                        </td>
                        <td
                            style="color: #000;font-family: Montserrat;font-size: 15px;font-style: normal;font-weight: 400;line-height: normal; padding: 20px; text-align: left">
                            {{
                        addCommas(calaculateInterestRate(datum.term_length_type,
                            item?.invited?.deposit_request?.amount, datum.term_length, item.fixed_rate)
                        ) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import CustomInvitedStatusBadge from './CustomInvitedStatusBadge.vue';
export default {
    components:{
        CustomInvitedStatusBadge,
    },
    props: ['datum'],
    name: 'PurchaseHistory',
    computed: {
        offers() {
            return this.datum.offers || [];
        },
        offersDisplay() {
            return this.offers.filter((item)=>item.offer_status != 'ACTIVE')
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
        calaculateInterestRate(term_length_type, amount_offered, term_length, rate) {
            console.log(term_length_type + "," + amount_offered + "," + term_length + "," + rate);
            let cal_interest = 0;
            switch (term_length_type) {
                case "DAYS":
                    cal_interest = Math.round(
                        (((amount_offered * rate) / 100) * term_length) / 365
                    );
                    break;
                case "MONTHS":
                    cal_interest = Math.round(
                        (((amount_offered * rate) / 100) * term_length) / 12
                    );
                case "days":
                    cal_interest = Math.round(
                        (((amount_offered * rate) / 100) * term_length) / 365
                    );
                    break;
                case "months":
                    cal_interest = Math.round(
                        (((amount_offered * rate) / 100) * term_length) / 12
                    );
                    break;
            }
            return cal_interest;


        },
    },


}
</script>

<style>
thead,
tbody,
tfoot,
tr,
td,
th {
    border-style: none;
}

.customHeader1 {
    border-radius: 10px 10px 0px 0px;
    background-color: rgba(80, 99, 244, 0.05);
    color: #000;

    /* Yield Exchange Text Styles/Table Titles */
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    text-transform: capitalize;
}

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
