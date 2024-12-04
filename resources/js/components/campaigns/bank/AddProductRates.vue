<template>
    <div
        style="width: 100%; height: 100%; border-radius: 10px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 50px; display: inline-flex;">
        <div
            style="min-height: 154px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: flex;width:100%">
            <div
                style="width: 100%; height: 60px; padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 20px; display: inline-flex">
                <div
                    style="width: 100%; justify-content: center; align-items: flex-start; gap: 569px; display: inline-flex">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.326 10.2883 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.326 29.7117 29.7117 33.326 25.0826 34.4119C21.7396 35.196 18.2604 35.196 14.9174 34.4119C10.2884 33.326 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M24.875 13L13 24.8758L14.5802 26.456L26.4552 14.581L24.875 13ZM23.6891 21.3125C24.001 21.3128 24.3097 21.3746 24.5978 21.4942C24.8858 21.6138 25.1474 21.789 25.3678 22.0098C25.5881 22.2306 25.7628 22.4926 25.8818 22.7808C26.0009 23.0691 26.062 23.378 26.0617 23.6899C26.0614 24.0018 25.9997 24.3105 25.88 24.5986C25.7604 24.8866 25.5852 25.1482 25.3644 25.3686C25.1436 25.5889 24.8817 25.7636 24.5934 25.8826C24.3051 26.0017 23.9962 26.0628 23.6843 26.0625C23.0544 26.0619 22.4506 25.811 22.0057 25.3652C21.5607 24.9194 21.3111 24.315 21.3117 23.6851C21.3123 23.0552 21.5632 22.4514 22.009 22.0064C22.4549 21.5615 23.0592 21.3119 23.6891 21.3125ZM23.6867 22.8958C23.4767 22.8958 23.2754 22.9792 23.1269 23.1277C22.9784 23.2762 22.895 23.4775 22.895 23.6875C22.895 23.8975 22.9784 24.0988 23.1269 24.2473C23.2754 24.3958 23.4767 24.4792 23.6867 24.4792C23.8967 24.4792 24.098 24.3958 24.2465 24.2473C24.395 24.0988 24.4784 23.8975 24.4784 23.6875C24.4784 23.4775 24.395 23.2762 24.2465 23.1277C24.098 22.9792 23.8967 22.8958 23.6867 22.8958ZM15.7724 13.3958C16.4023 13.3965 17.0061 13.6473 17.4511 14.0931C17.8961 14.539 18.1457 15.1433 18.145 15.7732C18.1444 16.4031 17.8936 17.0069 17.4477 17.4519C17.0019 17.8968 16.3976 18.1465 15.7677 18.1458C15.1378 18.1452 14.5339 17.8944 14.089 17.4485C13.644 17.0027 13.3944 16.3983 13.395 15.7685C13.3957 15.1386 13.6465 14.5347 14.0923 14.0898C14.5382 13.6448 15.1425 13.3952 15.7724 13.3958ZM15.77 14.9792C15.5601 14.9792 15.3587 15.0626 15.2102 15.211C15.0618 15.3595 14.9784 15.5609 14.9784 15.7708C14.9784 15.9808 15.0618 16.1822 15.2102 16.3306C15.3587 16.4791 15.5601 16.5625 15.77 16.5625C15.98 16.5625 16.1814 16.4791 16.3298 16.3306C16.4783 16.1822 16.5617 15.9808 16.5617 15.7708C16.5617 15.5609 16.4783 15.3595 16.3298 15.211C16.1814 15.0626 15.98 14.9792 15.77 14.9792Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            Add rates to your products</div>
                    </div>
                </div>
            </div>
            <div
                style="flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; display: flex; width: 90%;">

                <table class="table" style="background: transparent">
                    <thead class="customHeader1" style="background: transparent;border-collapse: collapse;">
                        <tr style="border: none!important;">
                            <th>Campaign Name</th>
                            <th>Products</th>
                            <th>Currency</th>
                            <th>Subscription Limit</th>
                            <th>Invitees</th>
                            <th>Start Date</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody class="customBody1" style="background: transparent;border-collapse: collapse;">
                        <tr style="border: none!important;">
                            <td>{{ collected_details_data?.campaign_name }}</td>
                            <td>{{ getCampaignSelectedProducts.length }}</td>
                            <td>{{ collected_details_data?.currency }}</td>
                            <td>{{ addCommans(collected_details_data?.subscription_amount) }}</td>
                            <td>{{ depos_count }}</td>
                            <td>{{ collected_details_data?.start_date }}</td>
                            <td>{{ collected_details_data?.expiry_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="width: 100%">
            <table class="table ">
                <thead class="customHeader" style="background: transparent">
                    <tr>
                        <th style="padding-left: 10px;">Product&nbsp;Name</th>
                        <th>Term&nbsp;Length</th>

                        <th style="padding-left: 10px;">
                            <div style="display: flex; flex-direction: row; gap: 5px">
                                Rate
                                <span style="color:#5063F4;height:100%;">(%)</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                    fill="none" id="ratenumber">
                                    <path
                                        d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                        stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <Tooltip placement="top" target="ratenumber"
                                    message="Has to be a number between 0 and 100." />
                            </div>

                        </th>

                        <th style="padding-left: 10px;">
                            <div style="display: flex; flex-direction: row; gap: 5px">
                                Minimum
                                <span style="color:#5063F4;height:100%;">({{ collected_details_data?.currency }})
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                    fill="none" id="maxnumber">
                                    <path
                                        d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                        stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <Tooltip placement="top" target="maxnumber"
                                    message="Has to be a number between 0  and 999,999,999,999. It should be less than minimum amount. " />
                            </div>
                        </th>

                        <th style="padding-left: 10px;">
                            <div style="display: flex; flex-direction: row; gap: 5px;">
                                Maximum
                                <span style="color:#5063F4;height:100%;">({{ collected_details_data?.currency }})
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                    fill="none" id="minnumber">
                                    <path
                                        d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                        stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <Tooltip placement="top" target="minnumber"
                                    message="Has to be a number between 0 and 9,999,999,999,999. It should be higher than minimum amount. " />
                            </div>
                        </th>
                        <!-- <th style="padding-left: 10px;">
                            <div style="display: flex; flex-direction: row; gap: 5px">
                                Order&nbsp;Limit
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                    fill="none" id="ol">
                                    <path
                                        d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                        stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <Tooltip placement="top" target="ol" message="Order Limit Is required" />
                            </div>
                        </th> -->
                        <th style="padding-left: 10px;">
                            <div style="display: flex; flex-direction: row; gap: 5px">
                                PDS
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                    fill="none" id="pds">
                                    <path
                                        d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                        stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <Tooltip placement="top" target="pds" message="Product disclosure statement" />
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="getCampaignSelectedProducts"
                        v-for="(selected_product, index) in getCampaignSelectedProducts">
                        <td>
                            <div style="margin-top: 20px !important;">
                                {{ capitalize(selected_product.product_name) }}
                            </div>

                        </td>
                        <td style="width:100px;">
                            <div style="margin-top: 20px !important;">
                                {{ selected_product.term_length }} <span style="color:grey;"> ({{
                                    capitalize(selected_product.term_length_type) }})</span>
                            </div>


                        </td>
                        <td>

                            <CustomInput inputType="number"
                                c-style="font-weight: 400; width: 100%; margin-top: 0px; height: 100%;"
                                p-style="width: 100%;" :id="'rate_' + index" name="Rate*" :has-validation="true"
                                @inputChanged="setProductRateOnChange(selected_product.product_id,$event)"
                                input-type="number" :defaultValue="getProductInfo(selected_product.product_id).rate"
                                :hasSpecificError="(fieldErrors[`rate_${selected_product.product_id}`])" />

                            <span class="error-message">
                                {{ fieldErrors[`rate_${selected_product.product_id}`] }}
                            </span>
                        </td>
                        <td>

                            <CustomInput inputType="number"
                                c-style="font-weight: 400; width: 100%; margin-top: 0px; height: 100%;"
                                p-style="width: 100%;" :id="'minimum_' + index" name="Minimum*" :has-validation="true"
                                @inputChanged="setProductMinimumOnChange(selected_product.product_id,$event)"
                                input-type="number"
                                :defaultValue="addCommans(getProductMinimum(selected_product.product_id))"
                                :hasSpecificError="(fieldErrors[`minimum_${selected_product.product_id}`])" />
                            <span class="error-message">
                                {{ fieldErrors[`minimum_${selected_product.product_id}`]}}
                            </span>

                        </td>

                        <td>
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                :id="'maximum_' + index" name="Maximum*" :has-validation="true"
                                @inputChanged="setProductMaximumOnChange(selected_product.product_id,$event)"
                                input-type="number"
                                :defaultValue="addCommans(getProductMaximum(selected_product.product_id))"
                                :hasSpecificError="(fieldErrors[`maximum_${selected_product.product_id}`])" />
                            <span class="error-message">
                                {{ fieldErrors[`maximum_${selected_product.product_id}`] }}
                            </span>

                        </td>
                        <td>
                            <div v-if="selected_product.pds" style="margin-top: 20px !important;">
                                <a :href="'/uploads/pds/' + selected_product.pds" target="_blank"
                                    v-if="selected_product.pds">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" style="cursor: pointer">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.33301 3.33341C3.33301 2.89139 3.5086 2.46746 3.82116 2.1549C4.13372 1.84234 4.55765 1.66675 4.99967 1.66675H14.9997C15.4417 1.66675 15.8656 1.84234 16.1782 2.1549C16.4907 2.46746 16.6663 2.89139 16.6663 3.33341V16.6667C16.6663 17.1088 16.4907 17.5327 16.1782 17.8453C15.8656 18.1578 15.4417 18.3334 14.9997 18.3334H4.99967C4.55765 18.3334 4.13372 18.1578 3.82116 17.8453C3.5086 17.5327 3.33301 17.1088 3.33301 16.6667V3.33341ZM14.9997 3.33341H4.99967V16.6667H14.9997V3.33341ZM6.66634 7.50008C6.66634 7.27907 6.75414 7.06711 6.91042 6.91083C7.0667 6.75455 7.27866 6.66675 7.49967 6.66675H12.4997C12.7207 6.66675 12.9326 6.75455 13.0889 6.91083C13.2452 7.06711 13.333 7.27907 13.333 7.50008C13.333 7.7211 13.2452 7.93306 13.0889 8.08934C12.9326 8.24562 12.7207 8.33341 12.4997 8.33341H7.49967C7.27866 8.33341 7.0667 8.24562 6.91042 8.08934C6.75414 7.93306 6.66634 7.7211 6.66634 7.50008ZM7.49967 10.8334C7.27866 10.8334 7.0667 10.9212 6.91042 11.0775C6.75414 11.2338 6.66634 11.4457 6.66634 11.6667C6.66634 11.8878 6.75414 12.0997 6.91042 12.256C7.0667 12.4123 7.27866 12.5001 7.49967 12.5001H9.99967C10.2207 12.5001 10.4326 12.4123 10.5889 12.256C10.7452 12.0997 10.833 11.8878 10.833 11.6667C10.833 11.4457 10.7452 11.2338 10.5889 11.0775C10.4326 10.9212 10.2207 10.8334 9.99967 10.8334H7.49967Z"
                                            fill="#5063F4" />
                                    </svg>
                                </a>
                            </div>
                            <div v-else style="margin-top: 20px !important;">
                                -
                            </div>


                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="justify-content: flex-end; align-items: center; display: inline-flex;width: 100%">
            <div style="justify-content: flex-end; align-items: flex-start; gap: 50px; display: inline-flex">
                <b-button @click="previousBtn"
                    style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                    Previous
                </b-button>
                <b-button @click="showSaveAsDraftConfirmation = true"
                    style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #5063F4 2px solid !important;background-color: #EFF2FE !important;color:#5063F4;  !important;">
                    Save as Draft
                </b-button>
                <b-button @click="submitRates"
                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                    Next
                </b-button>
            </div>
        </div>
        <TwoButtonActionMessageBox1 size="md" @closedSuccessModal="showSaveAsDraftConfirmation = false" btnOneText="No"
            btnTwoText="Yes" title="Would You Like To Save This Campaign To Drafts."
            :showm="showSaveAsDraftConfirmation" @btnOneClicked="showSaveAsDraftConfirmation = false"
            @btnTwoClicked="saveAsDraft(false, false,true)" />
        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
            :btnOneText="successbtnOneText" :btnTwoText="successbtnTwoText" :title="successModalTitle"
            :showm="showSuccessModal" :message="successMessage" @btnOneClicked="showSuccessModal = false"
            @btnTwoClicked="showSuccessModal = false" />
        <GeneralNoInteractionError :size="errorModalSize" @closedModal="showErrorModal = false" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" />
        <TwoButtonActionMessageBox2 size="md" @closedSuccessModal="goingPrevious = false" btnTwoText="Yes"
            btnOneText="No" :title="confirmdraftmsg" :showm="goingPrevious" @btnOneClicked="handleProceed"
            @btnTwoClicked="handleSaveAndProceed" />
        <!-- <ConfirmDialogBoxForPrevAtRatesStage   @proceed="handleProceed" @saveendproceed="handleSaveAndProceed" @cancelledProceed="handleSaveAndProceed" title="" :message="confirmdraftmsg" :show="goingPrevious"  /> -->
    </div>
</template>
<style scoped>
    .table-vue-custom {
        background: white;
    }
</style>
<style scoped>
    thead.customHeader1 {

        height: 51px;
    }

    thead.customHeader {
        background: #EFF2FE !important;
        height: 51px;
    }

    thead.customHeader tr {
        border-bottom: 0 solid #b3b2b2 !important;
    }

    thead.customHeader tr th {
        color: black;
        font-size: 13px !important;
        font-weight: 700;
        background: inherit !important;
        text-align: left !important;
    }

    tbody.customBody1>tr> {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #5063F4 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        word-wrap: break-word;
        padding: 5px !important;
    }

    tbody.customBody1>tr>td {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #5063F4 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        word-wrap: break-word;

    }


    thead.customHeader1>tr>th {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #252525;
        font-size: 16px;
        font-weight: 700;
        word-wrap: break-word;
    }

    tbody tr td {
        padding-top: 5px !important;
        font-size: 13px !important;
        min-width: 200px !important;
        vertical-align: top !important;
    }

    .term_length_custom>.form-control {
        max-width: 70px !important;
    }

    .error-message {
        height: 100%;
        min-width: 200px !important;
        color: #FF2E2E;
        font-family: Montserrat;
        font-size: 11px;
        font-weight: 400;
        line-height: 19px;
        letter-spacing: 0em;
        text-align: left;
        width: 100% !important;
    }
</style>
<script>
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import TwoButtonActionMessageBox1 from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import TwoButtonActionMessageBox2 from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";


    import Header from "../../shared/Table/Header";
    import Tooltip from "../../shared/Tooltip";
    import CustomSelect from "../../shared/CustomSelect";
    import CustomInput from "../../shared/CustomInput";
    import Button from "../../shared/Buttons/Button";
    import SaveAsDraftPrompt from "./SaveAsDraftPrompt";
    import SaveAsDraftSuccess from "./SaveAsDraftSuccess";
    import ConfirmDialogBoxForPrevAtRatesStage from "./ConfirmDialogBoxForPrevAtRatesStage";
    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/campaigns/mutation-types.js';

    export default {
        mounted() {
            this.getMyProducts();
        },
        computed: {
            ...mapGetters('campaign', ['getCampaignSelectedProducts', 'getCampaignSelectedProductIDS', 'getSelectedProductAtribute']),
        },
        components: {
            GeneralNoInteractionError,
            TwoButtonActionMessageBox1,
            TwoButtonActionMessageBox,
            TwoButtonActionMessageBox2,
            Tooltip,
            Header,
            CustomSelect,
            CustomInput,
            Button,
            SaveAsDraftPrompt,
            SaveAsDraftSuccess,
            ConfirmDialogBoxForPrevAtRatesStage,

        },
        props: ['selected_products', 'data', 'collected_details_data', 'selected_groups', 'isSavedDraftAlready', 'type_of_invite', 'selected_organizations', 'depos_count'],
        data() {

            const urlParams = new URLSearchParams(window.location.search);
            let campaign_id = urlParams.get('campaign_id');
            return {
                isDraftAlready: (campaign_id ? true : false),
                showSaveAsDraftConfirmation: false,
                errorModalTitle: "",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalMessage: "",
                successModalTitle: "",
                successMessage: "",
                successbtnOneText: "",
                successbtnTwoText: "",
                showSuccessModal: false,
                successModalSize: "md",
                rate: [],
                term_length: [],
                order_limit: [],
                maximum: [],
                minimum: [],
                term_length_type: [],
                submittedSaveDraft: false,
                products_data: null,
                saveAsDraftSteps: 0,
                fieldErrors: {},
                goingPrevious: false,
                confirmdraftmsg: "Do you wish to save the changes as Draft?."
            }
        },
        methods: {
            // this.fieldErrors[`maximum_${e.id}`] = 'n';
            // this.fieldErrors[`minimum_${e.id}`] = 'j';
            //  this.fieldErrors[`rate_${e.id}`] = 'h';
            setProductRateOnChange(product, value) {
                this.rate[product] = value;
                this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                    product_id: product,
                    field: 'rate',
                    value: value
                });
                console.log("Checking rate for " + product + " value is " + value, " is Nan" + isNaN(value));
                if (this.rate[product] === null || this.rate[product] === "" || isNaN(value) || this.rate[product] === undefined) {
                    this.$set(this.fieldErrors, `rate_${product}`, 'Enter a rate value between 1 and 100.');
                } else {
                    if ((this.sanitizeAmount(this.rate[product]) < 1) || (this.sanitizeAmount(this.rate[product]) > 100)) {
                        this.$set(this.fieldErrors, `rate_${product}`, 'Enter a rate value between 1 and 100.');
                    } else {
                        this.$set(this.fieldErrors, `rate_${product}`, '');
                    }

                }

            },
            setProductMinimumOnChange(product, value) {
                console.log("Checking minimum for " + product + " value is " + value);
                this.minimum[product] = value;
                this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                    product_id: product,
                    field: 'minimum',
                    value: value
                });

                if (this.minimum[product] === null || this.minimum[product] === "" || this.minimum[product] === undefined || isNaN(this.sanitizeAmount(value))) {

                    this.$set(this.fieldErrors, `minimum_${product}`, 'Enter valid Minimum amount.');
                }
                else {
                    if ((this.sanitizeAmount(this.minimum[product]) < 1) || (this.sanitizeAmount(this.minimum[product]) > 999999999999)) {
                        this.$set(this.fieldErrors, `minimum_${product}`, 'Enter Valid Minimum Amount .');
                    } else {

                        if ((this.sanitizeAmount(this.maximum[product]) !== 0) && (this.sanitizeAmount(this.minimum[product]) > this.sanitizeAmount(this.maximum[product]))) {
                            this.$set(this.fieldErrors, `minimum_${product}`, 'Cannot be greater than maximum');
                            this.$set(this.fieldErrors, `maximum_${product}`, 'Cannot be less than minimum');
                        } else {
                            this.$set(this.fieldErrors, `minimum_${product}`, '');
                            this.$set(this.fieldErrors, `maximum_${product}`, '');
                        }

                    }
                }

            },
            setProductMaximumOnChange(product, value) {
                console.log("Checking maximum for " + product + " value is " + value);
                this.maximum[product] = value;
                this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                    product_id: product,
                    field: 'maximum',
                    value: value
                });
                if (this.maximum[product] === null || this.maximum[product] === "" || this.maximum[product] === undefined || isNaN(this.sanitizeAmount(value))) {

                    this.$set(this.fieldErrors, `maximum_${product}`, 'Enter valid maximum amount.');
                }
                else {
                    if ((this.sanitizeAmount(this.maximum[product]) < 1) || (this.sanitizeAmount(this.maximum[product]) > 999999999999)) {
                        this.$set(this.fieldErrors, `maximum_${product}`, 'Enter Valid maximum Amount .');
                    } else {

                        if ((this.sanitizeAmount(this.minimum[product]) !== 0) && (this.sanitizeAmount(this.maximum[product]) < this.sanitizeAmount(this.minimum[product]))) {
                            this.$set(this.fieldErrors, `minimum_${product}`, 'Cannot be greater than maximum');
                            this.$set(this.fieldErrors, `maximum_${product}`, 'Cannot be less than minimum');
                        } else {
                            this.$set(this.fieldErrors, `minimum_${product}`, '');
                            this.$set(this.fieldErrors, `maximum_${product}`, '');
                        }

                    }
                }


            },
            closeSuccessModal() {
                this.submitted = false;
                this.showSuccessModal = false;
            },
            closeErrorModal() {
                this.submitted = false;
                this.showErrorModal = false;
            },
            addCommans(newvalue) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            previousBtn() {
                //this.goingPrevious = true                
                let products__ = this.saveAsDraft(true, false, true);
                if (products__) {
                    this.$emit("back-step", 4);
                    // console.log('collected_rates',products__);
                    this.$emit("collected_rates", products__);
                }


            },
            handleProceed() {
                this.goingPrevious = true;
                let products__ = this.selected_products;
                if (products__) {

                    this.$emit("back-step", 4);
                    this.$emit("collected_rates", products__);
                }
            },
            handleSaveAndProceed() {
                this.goingPrevious = false;
                this.isPreviousBTN = true;
                let products__ = this.saveAsDraft(true, false, true);
                if (products__) {

                    this.$emit("back-step", 4);
                    this.$emit("collected_rates", products__);
                }


            },
            getProductMinimum(product) {

                return this.getSelectedProductAtribute(product, 'minimum');
                // return (product.minimum) ? product.minimum.toLocaleString() : '';
            },
            getProductMaximum(product) {
                return this.getSelectedProductAtribute(product, 'maximum');
                //  return (product.maximum) ? product?.maximum.toLocaleString() : '';
            },
            getProductOrderLimit(product) {
                return this.getSelectedProductAtribute(product, 'maximum');
                //  return (product.order_limit) ? product.order_limit.toLocaleString() : '';
            },
            getProductInfo(product_id, place_holder = true, field = "") {

                let d = this.selected_products.filter(e => {
                    let p = e?.fi_campaign_product_id;
                    if (p === undefined) {
                        p = e?.product_id;
                    }

                    if (p === undefined) {
                        p = e;
                    }

                    return p == product_id;
                });

                if (d.length > 0) {
                    // console.log(field);
                    if (field === "") {
                        return d[0];
                    } else if (field == "maximum") {
                        return 200000;
                    } else if (field == "minimum") {
                        return 20000;
                    }
                }

                if (!place_holder) {
                    if (field === "") {
                        return null;
                    } else if (field == "maximum") {

                    } else if (field == "minimum") {

                    }

                }
                return {
                    'term_length': null,
                    'order_limit': null,
                    'maximum': null,
                    'minimum': null,
                    'term_length_type': null,
                    'rate': null,
                }
            },
            submitRates() {
                let products__ = this.saveAsDraft(true);
                if (products__) {
                    this.$emit("back-step", 6);
                    // console.log('collected_rates',products__);
                    this.$emit("collected_rates", products__);
                }
            },
            setProductEntries(products) {
                console.log(products, "loaded product products");
                const updateProductPromises = products.map(e => {
                    this.$set(this.fieldErrors, `rate_${e.id}`, '');
                    this.$set(this.fieldErrors, `minimum_${e.id}`, '');
                    this.$set(this.fieldErrors, `maximum_${e.id}`, '');

                    this.maximum[e.id] = (this.getSelectedProductAtribute(e.id, 'maximum')) ? this.getSelectedProductAtribute(e.id, 'maximum') : '';
                    this.minimum[e.id] = (this.getSelectedProductAtribute(e.id, 'minimum')) ? this.getSelectedProductAtribute(e.id, 'minimum') : '';
                    this.rate[e.id] = (this.getSelectedProductAtribute(e.id, 'rate')) ? this.getSelectedProductAtribute(e.id, 'rate') : '';
                    return Promise.all([
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'name',
                            value: e.custom_product_name
                        }),
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'termLength',
                            value: e.term_length
                        }),
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'termLengthType',
                            value: e.term_length_type
                        }),
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'maximum',
                            value: (this.getSelectedProductAtribute(e.id, 'maximum')) ? this.getSelectedProductAtribute(e.id, 'maximum') : ''
                        }),
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'minimum',
                            value: (this.getSelectedProductAtribute(e.id, 'minimum')) ? this.getSelectedProductAtribute(e.id, 'minimum') : ''
                        }),
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'rate',
                            value: (this.getSelectedProductAtribute(e.id, 'rate')) ? this.getSelectedProductAtribute(e.id, 'rate') : ''
                        }),
                        this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCT_ENTRY, {
                            product_id: e.id,
                            field: 'pds',
                            value: e.pds
                        }),



                    ]);

                });

                // Wait for all promises to resolve
                Promise.all(updateProductPromises)
                    .then(() => {
                        console.log("All products updated successfully");
                    })
                    .catch(error => {
                        console.error("Error updating products:", error);
                    });

            },
            getMyProducts() {
                let this_ = this;
                let prod_ids = [];
                // console.log("this.selected_products", this.selected_products);
                // console.log("this.selected_groups", this.selected_groups);
                this.getCampaignSelectedProducts.forEach(e => {
                    let p = e?.fi_campaign_product_id;
                    if (p === undefined) {
                        p = e?.product_id;
                    }
                    prod_ids.push(p);
                });
                axios.get("/campaigns/fi/my-products?product_ids=" + prod_ids + "&is_active=true&per_page=" + prod_ids.length + "")
                    .then(response => {
                        this_.products_data = response?.data?.data;
                        this.setProductEntries(response?.data?.data);

                    }).catch(error => {
                        this_.products_data = null;
                    });
            },
            addCommas(newValue) {
                let commavalue = newValue ? parseFloat(newValue.replace(/,/g, '')).toLocaleString() : '';
                return commavalue;
            },
            saveAsDraft(isNext = false, isPreviousBTN = false, novalidation = false) {
                this.selected_products = this.getCampaignSelectedProducts;
                this.showSaveAsDraftConfirmation = false;
                if (novalidation && (isNext == false)) {
                    let products_ = [];

                    this.selected_products.forEach((e, index) => {
                        if (e?.term_length && e?.product_id) {
                            products_.push({
                                'product_id': e.product_id,
                                'term_length': this.sanitizeAmount(e.term_length),
                                'order_limit': this.sanitizeAmount(e.order_limit),
                                'maximum': this.sanitizeAmount(e.maximum),
                                'minimum': this.sanitizeAmount(e.minimum),
                                'term_length_type': e.term_length_type,
                                'rate': this.sanitizeAmount(e.rate),
                            });
                            return;
                        }
                        let p = e?.fi_campaign_product_id;
                        if (p === undefined) {
                            p = e?.product_id;
                        }
                        console.log(this.term_length, "this.term_length");
                        products_.push({
                            'product_id': p,
                            'term_length': this.term_length[p],
                            'order_limit': this.order_limit[p],
                            'maximum': this.maximum[p],
                            'minimum': this.minimum[p],
                            'term_length_type': this.term_length_type[p],
                            'rate': this.rate[p],
                        });

                    });
                    this.processSaveAsDraftRequest(products_);
                    return;

                }

                this.showSaveAsDraftConfirmation = false;
                if (this.submittedSaveDraft) {
                    return;
                }

                let products_ = [];
                let has_empty_product_ = false;
                let has_max_exceed_ = false;
                let has_limit_exceed_ = false;
                let has_rate_invalid_ = false;
                let sub_more_than_order_limit = false;
                let order_limit_total = 0;
                let has_any_data = false;
                if (this.selected_products) {
                    //row validation rules
                    console.log(this.selected_products, "this.selected_products");
                    let errorindex = 0;
                    this.selected_products.forEach((e1, index1) => {
                        console.log(e1, "index1");

                        let p = e1?.fi_campaign_product_id;

                        if (p === undefined) {
                            p = e1?.product_id;
                        }

                        if (p === undefined) {
                            p = e1;
                        }
                        console.log(p, "p");
                        if (p) {

                            if (novalidation == false) {
                                if (this.maximum[p] === undefined || this.maximum[p] === "" || this.maximum[p] === null || this.sanitizeAmount(this.maximum[p]) < 1) {

                                    has_empty_product_ = true;
                                    this.$set(this.fieldErrors, `maximum_${p}`, 'Please Provide Maximum Amount');
                                } else {

                                    if (isNaN(this.sanitizeAmount(this.maximum[p]))) {
                                        this.$set(this.fieldErrors, `maximum_${p}`, 'Enter Valid Number beteen 1 and 999,999,999,999');
                                    }
                                }
                                if (this.minimum[p] === undefined || this.minimum[p] === "" || this.minimum[p] === null || this.sanitizeAmount(this.minimum[p]) < 1) {


                                    has_empty_product_ = true;
                                    this.$set(this.fieldErrors, `minimum_${p}`, 'Please provide Minimim amount');
                                } else {
                                    this.$set(this.fieldErrors, `minimum_${p}`, '');
                                    if (isNaN(this.sanitizeAmount(this.minimum[p]))) {
                                        this.$set(this.fieldErrors, `minimum_${p}`, 'Enter Valid Number beteen 1 and 999,999,999,999');
                                    } else {
                                        if ((!isNaN(this.sanitizeAmount(this.maximum[p])))) {
                                            if (this.sanitizeAmount(this.minimum[p]) > this.sanitizeAmount(this.maximum[p])) {
                                                has_empty_product_ = true;
                                                this.$set(this.fieldErrors, `minimum_${p}`, 'This amount is greater than the maximum amount of ' + this.maximum[p]);
                                            }
                                        }
                                    }

                                }
                                if (this.rate[p] === undefined || this.rate[p] == "" || this.rate[p] == null) {
                                    has_empty_product_ = true;
                                    this.$set(this.fieldErrors, `rate_${p}`, 'Please Provide Rate');
                                } else {
                                    if ((!isNaN(this.sanitizeAmount(this.rate[p])))) {
                                        if ((this.sanitizeAmount(this.rate[p]) > 100) || (this.sanitizeAmount(this.rate[p]) < 1)) {
                                            has_empty_product_ = true;

                                            this.$set(this.fieldErrors, `rate_${p}`, 'Your rate has to be between 1 and 100');

                                        }
                                    } else {
                                        this.$set(this.fieldErrors, `rate_${p}`, '');
                                    }

                                }
                            }

                        }
                    });

                    if (has_empty_product_) {
                        console.log(this.fieldErrors, "fieldErrors");
                        this.showErrorModal = true;
                        this.errorModalTitle = isNext ? 'Failed' : 'Save as Draft Error.';
                        this.errorModalMessage = "Please fill in all fields correctly.";
                        return;
                    }
                    //row validation rules
                    // console.log('selected_products',this.selected_products);
                    this.selected_products.forEach((e, index) => {

                        if (e?.term_length && e?.order_limit && e?.product_id) {
                            products_.push({
                                'product_id': e.product_id,
                                'term_length': this.sanitizeAmount(e.term_length),
                                'order_limit': this.sanitizeAmount(e.order_limit),
                                'maximum': this.sanitizeAmount(e.maximum),
                                'minimum': this.sanitizeAmount(e.minimum),
                                'term_length_type': e.term_length_type,
                                'rate': this.sanitizeAmount(e.rate),
                            });

                            return;
                        }

                        let p = e?.fi_campaign_product_id;
                        if (p === undefined) {
                            p = e?.product_id;
                        }


                        if (p) {

                            // console.log('p',p);
                            // console.log('this.order_limit',this.order_limit);
                            if (!has_any_data) {
                                if (this.order_limit[p] || this.maximum[p]
                                    || this.minimum[p] || this.rate[p]) {
                                    has_any_data = true;
                                }
                            }

                            if (!has_empty_product_) {
                                if (!has_max_exceed_ && novalidation == false) {

                                    has_max_exceed_ = this.sanitizeAmount(this.minimum[p]) > this.sanitizeAmount(this.maximum[p]);

                                }
                                if (!has_rate_invalid_ && novalidation == false) {
                                    has_rate_invalid_ = (this.sanitizeAmount(this.rate[p]) > 100 || (this.sanitizeAmount(this.rate[p]) < 0));
                                }

                                if (!sub_more_than_order_limit && novalidation == false) {
                                    sub_more_than_order_limit = this.sanitizeAmount(this.collected_details_data.subscription_amount) < this.sanitizeAmount(this.maximum[p]);
                                }

                                if (isPreviousBTN) {
                                    products_.push({
                                        'product_id': p,
                                        'term_length': this.term_length[p],
                                        'order_limit': this.order_limit[p],
                                        'maximum': this.maximum[p],
                                        'minimum': this.minimum[p],
                                        'term_length_type': this.term_length_type[p],
                                        'rate': this.rate[p],
                                    });
                                } else {
                                    products_.push({
                                        'product_id': p,
                                        'term_length': this.term_length[p],
                                        'order_limit': this.order_limit[p],
                                        'maximum': this.maximum[p],
                                        'minimum': this.minimum[p],
                                        'term_length_type': this.term_length_type[p],
                                        'rate': this.rate[p],
                                    });

                                }

                                if (!has_empty_product_) {

                                    if (typeof this.order_limit[p] === 'string') {
                                        order_limit_total = order_limit_total + parseFloat(this.order_limit[p].replace(/,/g, ''));
                                    } else if (typeof this.amount === 'number') {

                                        const limitstring = String(this.amount);
                                        order_limit_total = order_limit_total + parseFloat(limitstring.replace(/,/g, ''));
                                    }

                                }

                            } else {
                                if (isPreviousBTN) {
                                    products_.push({
                                        'product_id': p,
                                        'term_length': undefined,
                                        'order_limit': undefined,
                                        'maximum': undefined,
                                        'minimum': undefined,
                                        'term_length_type': undefined,
                                        'rate': undefined,
                                    });
                                }
                            }
                        }

                    });



                } else {
                    // console.log('ELSE',' >>> ')
                }

                // console.log("isPreviousBTN",isPreviousBTN);
                // console.log("has_empty_product_",has_empty_product_);
                if (has_empty_product_ && !isPreviousBTN) {
                    console.log(fieldErrors, "fieldErrors");
                    this.showErrorModal = true;
                    this.errorModalTitle = isNext ? 'Failed' : 'Save as draft.';
                    this.errorModalMessage = "Please fill in all fields correctly.";
                    return;
                }

                // if(isPreviousBTN && has_any_data){
                //     this.$swal({
                //         title: isNext ? 'Failed' : 'Save as draft.',
                //         text: "Your data will be lost. Please save as draft first.",
                //         confirmButtonText: 'Close'
                //     });
                //     return;
                // }

                if (has_max_exceed_) {

                    this.showErrorModal = true;
                    this.errorModalTitle = isNext ? 'Failed' : 'Save as draft.';
                    this.errorModalMessage = "Minimum amount is greater than maximum amount.";
                    return;
                }

                if (order_limit_total >= this.sanitizeAmount(this.collected_details_data.subscription_amount)) {

                    this.showErrorModal = true;
                    this.errorModalTitle = isNext ? 'Failed' : 'Save as draft.';
                    this.errorModalMessage = "Your total order limit is greater than the subscription amount.";
                }

                if (has_limit_exceed_) {

                    this.showErrorModal = true;
                    this.errorModalTitle = isNext ? 'Failed' : 'Save as draft.';
                    this.errorModalMessage = "Order limit should be within minimum and maximum amount.";
                    return;
                }

                if (has_rate_invalid_) {
                    this.showErrorModal = true;
                    this.errorModalTitle = isNext ? 'Failed' : 'Save as draft.';
                    this.errorModalMessage = "Either your Rate exceeds 100% or it is less than 0%.";
                    return;
                }

                if (isNext) { // move to the next page
                    return products_;
                }
                this.processSaveAsDraftRequest(products_);
            },
            processSaveAsDraftRequest(products) {
                let groups_ = [];
                if (this.selected_groups) {
                    this.selected_groups.forEach((e) => {
                        let p = e?.fi_campaign_group_id;
                        if (p === undefined) {
                            p = e;
                        }

                        if (p) {
                            groups_.push(p);
                        }
                    });
                }
                const urlParams = new URLSearchParams(window.location.search);
                let campaign_id = urlParams.get('campaign_id');
                console.log(this.isSavedDraftAlready, "this.isSavedDraftAlreadythis.isSavedDraftAlready");
                if (this.collected_details_data != null) {

                    if (this.isSavedDraftAlready > 0) {
                        campaign_id = this.isSavedDraftAlready;
                    }
                }
                this.submittedSaveDraft = true;
                axios.post(campaign_id ? '/campaigns/fi/update-campaign' : '/campaigns/fi/create-campaign', {
                    'campaignName': this.collected_details_data.campaign_name,
                    'expiryDate': this.collected_details_data.expiry_date,
                    'startDate': this.collected_details_data.start_date,
                    'currency': this.collected_details_data.currency,
                    'subscriptionAmount': this.sanitizeAmount(this.collected_details_data.subscription_amount),
                    'status': 'DRAFT',
                    'products': products,
                    'campaign': campaign_id,
                    'groups': groups_,
                    'type_of_invite': this.type_of_invite,
                    'selected_organizations': this.selected_organizations
                })
                    .then(response => {
                        this.submittedSaveDraft = false;
                        console.log(response?.data, "response?.data?response?.data?");
                        if (response?.data?.success) {
                            if (this.goingPrevious) {
                                this.$emit("collected_rates", products);
                                this.$emit('back-step', 4);
                            } else {
                                this.successModalTitle = "Saved Successfully to Drafts.";
                                this.showSuccessModal = true;
                            }
                            this.$emit("persistDraftData", response?.data?.data);
                            this.collected_details_data.id = response?.data?.id
                            this.saveAsDraftSteps = 2;

                        } else {
                            throw new Error(response?.data?.message);
                        }
                    }).catch(error => {
                        this.$swal({
                            title: 'Save as draft.',
                            text: error,
                            confirmButtonText: 'Close'
                        });
                        this.submittedSaveDraft = false;
                    });
            },
            sanitizeAmount(val) {
                try {
                    // return parseFloat(val.replace(",", "", val).replace(" ", "", val));
                    return parseFloat(val.replace(/,/g, "").replace(/ /g, ""));
                } catch (e) {
                    return val;
                }
            }, capitalize(thestring) {
                return thestring
                    .toLowerCase()
                    .split(' ')
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }, addOrUpdateParameter(url, key, value) {
                const [baseUrl, queryParamsString] = url.split('?');

                if (queryParamsString) {
                    // URL already has query parameters
                    const queryParams = queryParamsString.split('&');
                    let found = false;

                    // Check if the parameter already exists and update its value
                    for (let i = 0; i < queryParams.length; i++) {
                        const [paramKey, paramValue] = queryParams[i].split('=');
                        if (paramKey === key) {
                            queryParams[i] = `${key}=${value}`;
                            found = true;
                            break;
                        }
                    }

                    // If the parameter doesn't exist, add it to the existing parameters
                    if (!found) {
                        queryParams.push(`${key}=${value}`);
                    }

                    // Reconstruct the URL with the modified parameters
                    return `${baseUrl}?${queryParams.join('&')}`;
                } else {
                    // URL doesn't have query parameters, add them
                    return `${url}?${key}=${value}`;
                }
            }
        }
    }
</script>