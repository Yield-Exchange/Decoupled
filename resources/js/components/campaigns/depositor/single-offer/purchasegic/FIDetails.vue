<template>
    <div>
        <div class="bg-white mt-2" style="padding: 40px !important;">
            <img src="/assets/dashboard/images/yielogoblack.png" style="width: 153px;height: 23px;margin-bottom: 53px;"
                alt="">
            <div class="w-100 d-flex justify-content-center flex-column ">
                <div class="w-100 d-flex justify-content-center ">
                    <p class="m-0 p-0 pdf-header">Please transfer your funds to <br>
                        {{ bankDetails.bankName }} using the details below</p>
                </div>

                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex;margin: 15px 0px;">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="title-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                    fill="none">
                                    <path
                                        d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                        fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M22.4972 20.2614C22.4972 20.4824 22.585 20.6944 22.7413 20.8506C22.8976 21.0069 23.1096 21.0947 23.3306 21.0947C23.5516 21.0947 23.7635 21.0069 23.9198 20.8506C24.0761 20.6944 24.1639 20.4824 24.1639 20.2614V18.178H25.6581C26.7714 18.178 27.3289 16.8314 26.5414 16.0447L22.5472 12.048C22.4312 11.9319 22.2933 11.8397 22.1416 11.7769C21.9899 11.714 21.8273 11.6816 21.6631 11.6816C21.4988 11.6816 21.3362 11.714 21.1845 11.7769C21.0328 11.8397 20.895 11.9319 20.7789 12.048L16.7864 16.0447C15.9972 16.8314 16.5556 18.178 17.6689 18.178H19.1639V24.0114C19.1639 24.2324 19.2517 24.4444 19.408 24.6006C19.5643 24.7569 19.7762 24.8447 19.9972 24.8447H21.3197L18.3306 27.833L15.3422 24.8447H16.6639C16.8849 24.8447 17.0969 24.7569 17.2532 24.6006C17.4094 24.4444 17.4972 24.2324 17.4972 24.0114V21.0947C17.4972 20.8737 17.4094 20.6617 17.2532 20.5055C17.0969 20.3492 16.8849 20.2614 16.6639 20.2614C16.4429 20.2614 16.2309 20.3492 16.0746 20.5055C15.9184 20.6617 15.8306 20.8737 15.8306 21.0947V23.178H14.3364C13.2231 23.178 12.6647 24.5239 13.4531 25.3114L17.4464 29.3064C17.5625 29.4225 17.7003 29.5147 17.852 29.5775C18.0037 29.6404 18.1663 29.6728 18.3306 29.6728C18.4948 29.6728 18.6574 29.6404 18.8091 29.5775C18.9608 29.5147 19.0987 29.4225 19.2147 29.3064L23.2089 25.3114C23.9964 24.5239 23.4381 23.178 22.3247 23.178H20.8306V20.678C20.8307 20.6705 20.8307 20.663 20.8306 20.6555V17.3447C20.8306 17.1237 20.7428 16.9117 20.5865 16.7555C20.4302 16.5992 20.2182 16.5114 19.9972 16.5114H18.6747L21.6639 13.5222L24.6522 16.5114H23.3306C23.1096 16.5114 22.8976 16.5992 22.7413 16.7555C22.585 16.9117 22.4972 17.1237 22.4972 17.3447V20.2614Z"
                                        fill="#5063F4" />
                                </svg>
                            </div>
                            <div class="text-div">Wire Transfer Details</div>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <!-- <p class="transfer-desc">Please transfer your funds using the details outlined below:</p> -->
                    <div class="w-100">
                        <table class="w-100 transfer-table">
                            <tr>
                                <td class="transfer-table-s1">
                                    Canadian clearing code and format
                                </td>
                                <td class="transfer-table-s2">
                                    {{ bankDetails.clearingCode }}
                                </td>
                            </tr>
                            <tr>
                                <td class="transfer-table-s1">
                                    CIBC institution number
                                </td>
                                <td class="transfer-table-s2">
                                    {{ bankDetails.cibcinstitutionnumber }}
                                </td>
                            </tr>
                            <tr>
                                <td class="transfer-table-s1">
                                    Transit
                                </td>
                                <td class="transfer-table-s2">
                                    {{ bankDetails.trasnsit }}
                                </td>
                            </tr>
                            <tr>
                                <td class="transfer-table-s1">
                                    Beneficiary account number
                                </td>
                                <td class="transfer-table-s2">
                                    {{ bankDetails.beneficiaryAccount }}
                                </td>
                            </tr>
                            <tr>
                                <td class="transfer-table-s1">
                                    Beneficiary name
                                </td>
                                <td class="transfer-table-s2 text-capitalize">
                                    {{ bankDetails.beneficiaryName }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex;margin: 15px 0px;">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="title-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                    fill="none">
                                    <path
                                        d="M5.58813 15.5463C6.67396 10.9173 10.2883 7.30286 14.9174 6.21703C18.2604 5.43286 21.7396 5.43286 25.0826 6.21703C29.7117 7.30286 33.326 10.9173 34.4119 15.5463C35.196 18.8893 35.196 22.3685 34.4119 25.7115C33.326 30.3406 29.7117 33.955 25.0826 35.0408C21.7396 35.8249 18.2604 35.8249 14.9174 35.0408C10.2884 33.955 6.67396 30.3406 5.58813 25.7115C4.80396 22.3685 4.80396 18.8893 5.58813 15.5463Z"
                                        fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path
                                        d="M23.6667 22.4622C22.9722 22.4622 22.3819 22.2192 21.8958 21.7331C21.4097 21.247 21.1667 20.6567 21.1667 19.9622C21.1667 19.2678 21.4097 18.6775 21.8958 18.1914C22.3819 17.7053 22.9722 17.4622 23.6667 17.4622C24.3611 17.4622 24.9514 17.7053 25.4375 18.1914C25.9236 18.6775 26.1667 19.2678 26.1667 19.9622C26.1667 20.6567 25.9236 21.247 25.4375 21.7331C24.9514 22.2192 24.3611 22.4622 23.6667 22.4622ZM23.6667 20.7956C23.9028 20.7956 24.1008 20.7156 24.2608 20.5556C24.4208 20.3956 24.5006 20.1978 24.5 19.9622C24.5 19.7261 24.42 19.5284 24.26 19.3689C24.1 19.2095 23.9022 19.1295 23.6667 19.1289C23.4306 19.1289 23.2328 19.2089 23.0733 19.3689C22.9139 19.5289 22.8339 19.7267 22.8333 19.9622C22.8333 20.1984 22.9133 20.3964 23.0733 20.5564C23.2333 20.7164 23.4311 20.7961 23.6667 20.7956ZM18.6667 28.2956V25.8789C18.6667 25.5872 18.7361 25.3131 18.875 25.0564C19.0139 24.7997 19.2083 24.5947 19.4583 24.4414C19.9028 24.1775 20.3717 23.9586 20.865 23.7847C21.3583 23.6109 21.8617 23.4825 22.375 23.3997L23.6667 24.9622L24.9583 23.3997C25.4722 23.4831 25.9722 23.6117 26.4583 23.7856C26.9444 23.9595 27.4097 24.1781 27.8542 24.4414C28.1042 24.5942 28.3022 24.7992 28.4483 25.0564C28.5944 25.3136 28.6672 25.5878 28.6667 25.8789V28.2956H18.6667ZM20.3125 26.6289H22.875L21.75 25.2539C21.5 25.3234 21.2569 25.4136 21.0208 25.5247C20.7847 25.6358 20.5486 25.7539 20.3125 25.8789V26.6289ZM24.4583 26.6289H27V25.8789C26.7778 25.74 26.5486 25.6186 26.3125 25.5147C26.0764 25.4109 25.8333 25.3239 25.5833 25.2539L24.4583 26.6289ZM13.6667 26.6289C13.2083 26.6289 12.8158 26.4659 12.4892 26.1397C12.1625 25.8136 11.9994 25.4211 12 24.9622V13.2956C12 12.8372 12.1633 12.445 12.49 12.1189C12.8167 11.7928 13.2089 11.6295 13.6667 11.6289H25.3333C25.7917 11.6289 26.1842 11.7922 26.5108 12.1189C26.8375 12.4456 27.0006 12.8378 27 13.2956V17.4622C26.7778 17.1845 26.5347 16.9206 26.2708 16.6706C26.0069 16.4206 25.6944 16.2539 25.3333 16.1706V13.2956H13.6667V24.9622H17.125C17.0833 25.115 17.0522 25.2678 17.0317 25.4206C17.0111 25.5733 17.0006 25.7261 17 25.8789V26.6289H13.6667ZM15.3333 16.6289H21.1667C21.5278 16.3511 21.9236 16.1428 22.3542 16.0039C22.7847 15.865 23.2222 15.7956 23.6667 15.7956V14.9622H15.3333V16.6289ZM15.3333 19.9622H19.5C19.5 19.6706 19.5311 19.3859 19.5933 19.1081C19.6556 18.8303 19.7425 18.5595 19.8542 18.2956H15.3333V19.9622ZM15.3333 23.2956H18.2083C18.3611 23.1706 18.5244 23.0595 18.6983 22.9622C18.8722 22.865 19.0492 22.7747 19.2292 22.6914V21.6289H15.3333V23.2956ZM13.6667 24.9622V13.2956V16.1497V15.7956V24.9622Z"
                                        fill="#5063F4" />
                                </svg>
                            </div>
                            <div class="text-div">
                                Transaction Details
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <!-- <p class="transfer-desc">Please transfer your funds using the details outlined below:</p> -->
                    <div class="w-100">
                        <table class="w-100 transaction-detail-table">
                            <thead>
                                <tr>
                                    <th class="">
                                        Request Id
                                    </th>
                                    <th class="">
                                        Product Type
                                    </th>
                                    <th class="">
                                        Term
                                    </th>
                                    <th class="">
                                        Deposit Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ depositorDetails.requestID }}

                                    </td>
                                    <td class="text-capitalize">
                                        {{ depositorDetails.productType }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ depositorDetails.termLength }}
                                    </td>
                                    <td>
                                        {{ depositorDetails.depositAmount }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
import Button from '../../../../shared/Buttons/Button.vue';
export default {
    props: ['bankDetails', 'depositorDetails'],

    components: {
        Button
    },
    data() {
        return {
            bankname: "ABC Bank"
        }
    }

}
</script>


<style scoped>
.pdf-header {
    color: #252525;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 28px;
    /* 116.667% */
}

.text-div {
    color: #5063F4;
    /* font-feature-settings: 'clig' off, 'liga' off; */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.transfer-table tr {
    padding: 10px;

}

.transfer-desc {
    color: #252525;
    /* font-feature-settings: 'clig' off, 'liga' off; */

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.transfer-table .transfer-table-s2 {
    padding: 5px !important;
    color: #252525;
    font-family: Montserrat !important;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.transfer-table .transfer-table-s1 {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 11px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

tr td,
tr th {
    border-top: none !important;
    border-bottom: 5px solid white !important;
    padding: 5px 20px;
}

.transfer-table tr,
.transaction-detail-table thead {
    background-color: #F4F5F6;
}

.transaction-detail-table thead tr th {

    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 216.667% */
    text-transform: capitalize;
}

.transaction-detail-table tbody tr td {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 12px;
    font-style: normal;
    font-weight: 300;
    line-height: 26px;
    /* 216.667% */
    text-transform: capitalize;
}
</style>