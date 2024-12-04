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
                                    d="M5.58813 15.2885C6.67396 10.6594 10.2883 7.04505 14.9174 5.95922C18.2604 5.17505 21.7396 5.17505 25.0826 5.95922C29.7117 7.04505 33.326 10.6594 34.4119 15.2885C35.196 18.6315 35.196 22.1107 34.4119 25.4537C33.326 30.0828 29.7117 33.6971 25.0826 34.783C21.7396 35.5671 18.2604 35.5671 14.9174 34.783C10.2884 33.6971 6.67396 30.0828 5.58813 25.4537C4.80396 22.1107 4.80396 18.6315 5.58813 15.2885Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M18.3962 14.6226C18.2093 14.4356 17.9088 14.4356 17.3071 14.4356H17.1476V13.4785C17.1476 13.3516 17.0971 13.2299 17.0074 13.1402C16.9177 13.0504 16.7959 13 16.669 13C16.5421 13 16.4204 13.0504 16.3307 13.1402C16.2409 13.2299 16.1905 13.3516 16.1905 13.4785V14.4356H16.031C15.4293 14.4356 15.1288 14.4356 14.9418 14.6226C14.7549 14.8095 14.7549 15.11 14.7549 15.7117V15.8419C14.8838 15.8081 15.0139 15.7844 15.1415 15.7672C15.5524 15.7117 16.0597 15.7117 16.6116 15.7117H16.7265C17.2784 15.7117 17.785 15.7117 18.1965 15.7672C18.3241 15.7838 18.4543 15.8081 18.5832 15.8419V15.7117C18.5832 15.11 18.5832 14.8095 18.3962 14.6226Z"
                                    fill="#5063F4" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M13.4785 25.1216C13.3516 25.1216 13.2299 25.1721 13.1402 25.2618C13.0504 25.3515 13 25.4733 13 25.6002C13 25.7271 13.0504 25.8488 13.1402 25.9386C13.2299 26.0283 13.3516 26.0787 13.4785 26.0787H26.2395C26.3664 26.0787 26.4881 26.0283 26.5779 25.9386C26.6676 25.8488 26.718 25.7271 26.718 25.6002C26.718 25.4733 26.6676 25.3515 26.5779 25.2618C26.4881 25.1721 26.3664 25.1216 26.2395 25.1216H25.6015V16.522C25.6015 15.667 25.6015 15.2389 25.3743 14.9109C25.1472 14.583 24.7465 14.4324 23.9451 14.1325C22.3787 13.5449 21.5958 13.2514 21.0464 13.6317C20.4971 14.0132 20.4971 14.8491 20.4971 16.522V18.2626H20.5545C21.1064 18.2626 21.613 18.2626 22.0246 18.3181C22.4782 18.3787 22.958 18.5223 23.3517 18.9166C23.7467 19.3109 23.8902 19.7907 23.9515 20.2438C24.0063 20.6559 24.0063 21.1625 24.0063 21.7151V25.1216H23.0493V21.7719C23.0493 20.5685 23.0493 19.9675 22.6754 19.5936C22.3015 19.2197 21.7004 19.2197 20.4971 19.2197H19.221C18.0176 19.2197 17.4166 19.2197 17.0427 19.5936C16.6688 19.9675 16.6688 20.5685 16.6688 21.7719V25.1216H15.7117V21.7145C15.7117 21.1625 15.7117 20.6559 15.7672 20.2444C15.8278 19.7907 15.9714 19.3109 16.3657 18.9173C16.76 18.5223 17.2398 18.3787 17.6928 18.3175C18.105 18.2626 18.6116 18.2626 19.1642 18.2626H19.2101C19.1846 17.6654 19.1049 17.2992 18.8471 17.0414C18.4732 16.6675 17.8721 16.6675 16.6688 16.6675C15.4654 16.6675 14.8644 16.6675 14.4905 17.0414C14.1166 17.4153 14.1166 18.0163 14.1166 19.2197V25.1216H13.4785ZM18.1044 21.1338C18.1044 21.0069 18.1548 20.8852 18.2446 20.7955C18.3343 20.7057 18.456 20.6553 18.5829 20.6553H21.1351C21.262 20.6553 21.3838 20.7057 21.4735 20.7955C21.5632 20.8852 21.6137 21.0069 21.6137 21.1338C21.6137 21.2607 21.5632 21.3825 21.4735 21.4722C21.3838 21.562 21.262 21.6124 21.1351 21.6124H18.5829C18.456 21.6124 18.3343 21.562 18.2446 21.4722C18.1548 21.3825 18.1044 21.2607 18.1044 21.1338ZM18.1044 23.048C18.1044 22.9211 18.1548 22.7993 18.2446 22.7096C18.3343 22.6199 18.456 22.5694 18.5829 22.5694H21.1351C21.262 22.5694 21.3838 22.6199 21.4735 22.7096C21.5632 22.7993 21.6137 22.9211 21.6137 23.048C21.6137 23.1749 21.5632 23.2966 21.4735 23.3864C21.3838 23.4761 21.262 23.5265 21.1351 23.5265H18.5829C18.456 23.5265 18.3343 23.4761 18.2446 23.3864C18.1548 23.2966 18.1044 23.1749 18.1044 23.048Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div class="text-div">Invited Istitutions</div>
                    </div>
                    <!-- <div @click="toggleView(1)"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div> -->
                </div>
            </div>
        </div>
        <div class="d-flex gap-4 flex-wrap mt-3 p-3" v-if="fis">

            <div class="selected-fis" v-for="(item, i) in fis" :key="i">{{ i + 1 + ". " + item.name }}</div>
        </div>
    </div>

</template>


<script>
import ViewCard from '../../../shared/ViewCard.vue';



export default {
    props: ['count', 'finstitutionss'],

    components: { ViewCard },
    beforeMount() {
        if (this.finstitutionss) {
            this.fis = this.finstitutionss
            console.log(this.fis)

        }
    },
    data() {
        return {
            fis: null
        }
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

<style>
.selected-fis {
    color: var(--Yield-Exchange-Colors-Darks, #252525);
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */
}
</style>