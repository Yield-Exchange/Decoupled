<template>
    <div>

        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58807 14.9174C6.6739 10.2883 10.2883 6.67395 14.9173 5.58813C18.2604 4.80396 21.7395 4.80396 25.0826 5.58813C29.7116 6.67395 33.326 10.2883 34.4118 14.9174C35.196 18.2604 35.196 21.7396 34.4118 25.0826C33.326 29.7117 29.7116 33.326 25.0825 34.4119C21.7395 35.196 18.2604 35.196 14.9173 34.4119C10.2883 33.326 6.6739 29.7117 5.58807 25.0826C4.8039 21.7396 4.8039 18.2604 5.58807 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M25.3462 18.2912H21.8187V14.7637C21.8187 14.296 21.6329 13.8474 21.3022 13.5166C20.9714 13.1858 20.5228 13 20.055 13C19.5872 13 19.1386 13.1858 18.8078 13.5166C18.4771 13.8474 18.2912 14.296 18.2912 14.7637L18.3539 18.2912H14.7637C14.296 18.2912 13.8474 18.4771 13.5166 18.8078C13.1858 19.1386 13 19.5872 13 20.055C13 20.5228 13.1858 20.9714 13.5166 21.3022C13.8474 21.6329 14.296 21.8187 14.7637 21.8187L18.3539 21.7561L18.2912 25.3462C18.2912 25.814 18.4771 26.2626 18.8078 26.5934C19.1386 26.9242 19.5872 27.11 20.055 27.11C20.5228 27.11 20.9714 26.9242 21.3022 26.5934C21.6329 26.2626 21.8187 25.814 21.8187 25.3462V21.7561L25.3462 21.8187C25.814 21.8187 26.2626 21.6329 26.5934 21.3022C26.9242 20.9714 27.11 20.5228 27.11 20.055C27.11 19.5872 26.9242 19.1386 26.5934 18.8078C26.2626 18.4771 25.814 18.2912 25.3462 18.2912Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div class="text-div">Offer {{ count + 1 }} Summary</div>
                    </div>
                    <div @click="viewMore1 = !viewMore1"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-4 flex-wrap mt-3 p-3" v-if="reqsummary && viewMore1">
            <ViewCard type="secondary" title="Product" :desc="product.product_name" />
            <ViewCard type="secondary" title="Term Length"
                :desc="reqsummary.term_length + ' ' + reqsummary.term_length_type" />
            <ViewCard type="secondary" title="Settlement Date" :desc="settelemntdate.formated_date" />
            <ViewCard type="secondary" title="Interest Rate" :desc="reqsummary.sum_rate.toFixed(2) + ' %'" />
            <ViewCard type="secondary" title="Minimum"
                :desc="reqsummary?.currency + ' ' + addCommas(reqsummary?.min)" />
            <ViewCard type="secondary" title="Maximum" :desc="reqsummary?.currency + ' ' + addCommas(reqsummary.max)" />
        </div>
        <hr>
        <div class="d-flex gap-4 flex-wrap mt-3 p-3" v-if="reqsummary && viewMore1">
            <ViewCard type="secondary" title="Basket ID" :desc="reqsummary.basket" />
            <ViewCard type="secondary" title="Basket" :desc="reqsummary.basket" />
            <ViewCard type="secondary" title="Rating" desc="NA" />
            <ViewCard type="secondary" title="Market Value" desc="NA" />
            <ViewCard type="secondary" title="Trade Date"
                :desc="reqsummary.trade_date == null ? '-' : formatDateToCustomFormat(reqsummary.trade_date, true)" />

        </div>
    </div>

</template>


<script>
import ViewCard from '../../../shared/ViewCard.vue';
import { mapGetters } from 'vuex';



export default {
    props: ['count', 'request'],

    components: { ViewCard },
    mounted() {
        if (this.request) {
            this.reqsummary = this.request
            this.settelemntdate = this.getsettlemntdate.find(item => item.id === this.reqsummary.settlement_date)
            this.product = this.getgloabalproducts.find(item => item.id === this.reqsummary.product)
            // this.reqsummary.settlement_date


        }
    },
    data() {
        return {
            reqsummary: null,
            viewMore1: true,
            settelemntdate: null,
            product: null
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getPrimeRates', 'getRateWithKey', 'getsettlemntdate', 'getgloabalproducts']),
    },
    methods: {

        formatDateToCustomFormat(inputDate, hastime = false) {
            const date = new Date(inputDate);

            const dateOptions = { month: 'short', day: '2-digit', year: 'numeric' };
            let formattedDate = date.toLocaleDateString('en-US', dateOptions);

            if (hastime) {
                const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
                const formattedTime = date.toLocaleTimeString('en-US', timeOptions);

                formattedDate += ': ' + formattedTime;
            }

            return formattedDate;
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }
        }

    }

}
</script>

<style scoped>
.collateral-item {
    display: flex;
    padding: 12px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    border-radius: 8px;
    background: #EFF2FE;
    font-family: Montserrat;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    color: #5063F4;
}
</style>