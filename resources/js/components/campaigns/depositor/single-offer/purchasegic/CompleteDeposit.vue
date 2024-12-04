<template>
    <div>
        <div
            style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%">
                <div class="page-title">
                    <div class="title-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M22.4972 20.2614C22.4972 20.4824 22.585 20.6944 22.7413 20.8506C22.8976 21.0069 23.1096 21.0947 23.3306 21.0947C23.5516 21.0947 23.7635 21.0069 23.9198 20.8506C24.0761 20.6944 24.1639 20.4824 24.1639 20.2614V18.178H25.6581C26.7714 18.178 27.3289 16.8314 26.5414 16.0447L22.5472 12.048C22.4312 11.9319 22.2933 11.8397 22.1416 11.7769C21.9899 11.714 21.8273 11.6816 21.6631 11.6816C21.4988 11.6816 21.3362 11.714 21.1845 11.7769C21.0328 11.8397 20.895 11.9319 20.7789 12.048L16.7864 16.0447C15.9972 16.8314 16.5556 18.178 17.6689 18.178H19.1639V24.0114C19.1639 24.2324 19.2517 24.4444 19.408 24.6006C19.5643 24.7569 19.7762 24.8447 19.9972 24.8447H21.3197L18.3306 27.833L15.3422 24.8447H16.6639C16.8849 24.8447 17.0969 24.7569 17.2532 24.6006C17.4094 24.4444 17.4972 24.2324 17.4972 24.0114V21.0947C17.4972 20.8737 17.4094 20.6617 17.2532 20.5055C17.0969 20.3492 16.8849 20.2614 16.6639 20.2614C16.4429 20.2614 16.2309 20.3492 16.0746 20.5055C15.9184 20.6617 15.8306 20.8737 15.8306 21.0947V23.178H14.3364C13.2231 23.178 12.6647 24.5239 13.4531 25.3114L17.4464 29.3064C17.5625 29.4225 17.7003 29.5147 17.852 29.5775C18.0037 29.6404 18.1663 29.6728 18.3306 29.6728C18.4948 29.6728 18.6574 29.6404 18.8091 29.5775C18.9608 29.5147 19.0987 29.4225 19.2147 29.3064L23.2089 25.3114C23.9964 24.5239 23.4381 23.178 22.3247 23.178H20.8306V20.678C20.8307 20.6705 20.8307 20.663 20.8306 20.6555V17.3447C20.8306 17.1237 20.7428 16.9117 20.5865 16.7555C20.4302 16.5992 20.2182 16.5114 19.9972 16.5114H18.6747L21.6639 13.5222L24.6522 16.5114H23.3306C23.1096 16.5114 22.8976 16.5992 22.7413 16.7555C22.585 16.9117 22.4972 17.1237 22.4972 17.3447V20.2614Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                    <div class="text-div">Checklist</div>
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
        <div class="bg-white mt-2" style="padding: 40px !important;">
            <div class="w-100 d-flex justify-content-center flex-column ">
                <div class="w-100 d-flex justify-content-center ">
                    <p class="m-0 p-0 transfer-header">Congratulations on your investment!</p>
                </div>
                <div class="w-100 d-flex justify-content-center ">
                    <img src="/assets/dashboard/images/wire-transfer.svg" alt="">
                </div>

                <div class="w-100 d-none">
                    <p class="transfer-desc">{{ bankname }} will reach out to you shortly. Please prepare the following
                        documents.
                    </p>
                    <div class="w-100">
                        <table class="w-100 transfer-table">
                            <thead>
                                <tr>
                                    <th class="transfer-table-s2">
                                        Profile Details
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-capitalize">
                                        User Details
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-capitalize">
                                        Organization Summary
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-capitalize">
                                        Uploaded documentation to complete transaction
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="w-100 mt-4 p-3">
                    <p class="funds-transfer-bold"> Reminder</p>
                    <div class="tranfre-details-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33" fill="none">
                            <path
                                d="M26.125 17.875C26.5925 17.875 27.0462 17.93 27.5 17.9988V11L19.25 2.75H8.25C6.72375 2.75 5.5 3.97375 5.5 5.5V27.5C5.5 29.0263 6.72375 30.25 8.25 30.25H18.9888C18.2875 29.04 17.875 27.6375 17.875 26.125C17.875 21.5737 21.5737 17.875 26.125 17.875ZM17.875 4.8125L25.4375 12.375H17.875V4.8125ZM27.5 26.8125V24.75H22V22H27.5V19.9375L31.625 23.375L27.5 26.8125ZM24.75 27.5H30.25V30.25H24.75V32.3125L20.625 28.875L24.75 25.4375V27.5Z"
                                fill="#5063F4" />
                        </svg>
                        <p class="transfer-desc p-0 m-0">Initiate your funds transfer: <span
                                @click="downloadpdf">Download funds transfer
                                details</span>.</p>

                    </div>
                    <!-- <p class="transfer-desc mt-5">Please transfer your funds to {{ bankdetails.bankName }} using the banking details given below.</p> -->

                </div>

            </div>

        </div>

        <div class="w-100 d-flex justify-content-end mt-4 gap-3">
            <Button type="outlined" @click="goBack">Previous</Button>
            <Button type="primary" @click="goNext">Finish</Button>
        </div>
        <PDfDownload ref="downloadpdf" :bankDetails="bankdetails" :depositorDetails="depositdetails" />
    </div>
</template>

<script>
import Button from '../../../../shared/Buttons/Button.vue';
import PDfDownload from './PDfDownload.vue';

export default {
    props: ['bankname', 'bankdetails', 'depositdetails'],
    components: {
        Button, PDfDownload
    },
    data() {
        return {
            buttonStyle: {
                padding: '5px'
            }
        }
    },
    methods: {
        goNext() {
            window.location.href = "/pending-deposits"
        },
        goBack() {
            this.$emit('setStep', 'wiretransfer')
        },
        downloadpdf() {
            this.$refs.downloadpdf.downloadPdf()
        },
    }

}
</script>


<style scoped>
.funds-transfer-bold {
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;
    margin: 0 auto;

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat !important;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    text-align: start;
    /* max-width: 900px; */
    /* 130% */
    /* text-transform: capitalize; */
}

.transfer-desc span {
    color: #5063F4 !important;
    cursor: pointer;

}

.tranfre-details-wrapper {
    display: flex;
    padding: 10px;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    align-self: stretch;
    background: var(--Yield-Exchange-Pallette-Yield-Exchange-Off-White, #F4F5F6);
    margin-top: 20px !important;
}

.transfer-header {
    color: #5063F4;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 32px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 81.25% */
    /* text-transform: capitalize; */
}

.transfer-desc {
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

thead {
    background-color: #EFF2FE;
}


.transfer-table-s2 {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

tbody tr td {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

thead tr th,
tbody tr td {
    padding: 10px 10px !important;
}

tr td,
tr th {
    border-top: none !important;
    border-bottom: 1px solid #D9D9D9 !important;
}

tr {
    padding: 5px 20px;
}

/* tr:nth-child(odd) {
    background-color: #F4F5F6;
}

tr:nth-child(even) {
    background-color: #EFF2FE;

} */

input[type=radio] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=radio]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=radio]:checked::before {
    transform: scale(1);
}

input[type=radio]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}
</style>