<template>
    <div style="height: 100%">
        <div
            style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
            <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                <div style="display: flex; flex-direction: column;">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.3261 10.2884 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.3261 29.7117 29.7117 33.3261 25.0826 34.4119C21.7396 35.1961 18.2604 35.1961 14.9174 34.4119C10.2884 33.3261 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M20.9828 13.0027C17.0247 12.8938 13.782 16.0743 13.782 20.0013H12.39C12.0401 20.0013 11.869 20.4213 12.1178 20.6623L14.2874 22.8397C14.4429 22.9952 14.684 22.9952 14.8395 22.8397L17.0091 20.6623C17.0628 20.6075 17.0991 20.538 17.1134 20.4626C17.1277 20.3872 17.1194 20.3092 17.0895 20.2385C17.0595 20.1678 17.0093 20.1076 16.9452 20.0654C16.8811 20.0232 16.8059 20.0009 16.7292 20.0013H15.3372C15.3372 16.9686 17.8101 14.5191 20.8583 14.558C23.7511 14.5968 26.1851 17.0308 26.224 19.9236C26.2628 22.9641 23.8133 25.4447 20.7806 25.4447C19.5286 25.4447 18.3699 25.017 17.4523 24.2938C17.3034 24.1765 17.1165 24.118 16.9273 24.1295C16.738 24.141 16.5596 24.2216 16.4259 24.356C16.0993 24.6826 16.1226 25.2348 16.4881 25.5147C17.7098 26.4809 19.223 27.0044 20.7806 27C24.7076 27 27.8881 23.7573 27.7792 19.7992C27.6781 16.1521 24.6298 13.1038 20.9828 13.0027ZM20.5862 16.8908C20.2674 16.8908 20.003 17.1552 20.003 17.4741V20.3357C20.003 20.6079 20.1507 20.8645 20.384 21.0045L22.8102 22.4431C23.0901 22.6064 23.4478 22.5131 23.6111 22.2409C23.7744 21.961 23.6811 21.6033 23.409 21.44L21.1694 20.1102V17.4663C21.1694 17.1552 20.905 16.8908 20.5862 16.8908Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            History
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <b-tabs content-class="mt-5" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom"
                style="width:100%;">
                <b-tab title="Trades" active>
                    <DepositsTab />
                </b-tab>
                <b-tab title="Offers">
                    <OffersTab />
                </b-tab>
            </b-tabs>
        </div>

    </div>
</template>

<script>

import { addDaysToDate, addCommasToANumber, formatTimestamp, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber } from "../../../../utils/commonUtils";

import DepositsTab from "./osummary/DepositsTab.vue";
import OffersTab from "./osummary/OffersTab.vue";
export default {
    mounted() {
        // this.getData();
    },
    computed: {

    },
    components: {
        DepositsTab,
        OffersTab
    },
    created() {
    },
    props: ['offers', 'products', 'titlespan'],
    data() {
        return {

        }
    },
    watch: {

    },
    methods: {

    }
}
</script>