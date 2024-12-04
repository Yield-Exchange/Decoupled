<template>
    <div>

        <div @click="showModal"
            :style="'width:100%;height: 20px; padding: 4px 8px;border-radius: 200px; background: #FEF8EC;justify-content: flex-start; align-items: center; gap: 2px; display: inline-flex;color: F4B63C; font-size: 13px;  font-weight: 500; line-height: 9px; word-wrap: break-word;cursor: pointer'">
            <img src="/assets/dashboard/icons/withdraw.svg">
            <p class="p-0 m-0"> Withdraw Offer </p>
        </div>
        <Modal v-if="withdrawalRequest" :show="withdrawalRequest" @isVisible="withdrawalRequest = $event"
            modalsize="md">
            <b-container class="bv-example-row" style="width: 100% !important;padding: 0px !important;">

                <b-row style="width:100%;padding: 0px !important;">
                    <b-col md="12" style="width:100%;padding: 0px !important;">
                        <MessageHeaderIconized title="Withdraw This Offer" :titlespan="titlespan" width="100"
                            title_image="/assets/dashboard/icons/withdrawrequest.svg" />
                    </b-col>
                </b-row>
                <b-row style="margin-top: 30px;width:100%;padding: 0px !important; margin-top: 15px;">
                    <b-col md="12" class="align-items-left "
                        style="width:100%;padding: 0px !important; margin-left:10px !important; ">
                        <FormLabelRequired labelText="Reason for withdrawing the request" required="true"
                            showHelperText="true" helperText="Reason For Withdrawing the request"
                            helperId="reasonforwith" />
                        <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                            p-style="width: 100%;"
                            c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                            :data="reasonsForWithdraw" id="reason_for_withdraw" name="Reason for Withdraw*"
                            :has-validation="false" :default-value="reason_for_withdraw" v-model="reason_for_withdraw"
                            @selectChanged="reason_for_withdraw = $event" />
                    </b-col>
                </b-row>
                <b-row style="width:100%; padding: 0px !important;margin-top: 15px;">

                    <b-col md="12" style="padding: 10px; padding-right: 4px !important;">
                        <div style="display: flex; align-items: center; justify-content: end;">

                            <b-button @click="withDrawRequest()"
                                style="width: 40% !important; height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                Withdraw
                            </b-button>
                        </div>
                    </b-col>
                </b-row>

            </b-container>
        </Modal>
        <ActionMessage style="width: 600px;" @closedSuccessModal="cannotwithdraw = false"
            @btnTwoClicked="cannotwithdraw = false" @btnOneClicked="cannotwithdraw = false" btnOneText=""
            btnTwoText="Close" icon="/assets/signup/danger.svg" title="You Cannot withdraw this offer!"
            :showm="cannotwithdraw">
            <div class="ml-5 description-text-withdraw ">This offer has been bought and hence cannot be withdrawn </div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked="fail = false"
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="Close" icon="/assets/signup/danger.svg"
            title="Offer not withdrawn!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">{{ errorMsg }}</div>
        </ActionMessage>
        <SucessMessageBox :title="successTitle" :message="successMsg" :show="showSuccessMsg" size="md"
            :showokbtn="false" @closedSuccessModal="redirectToDetailsPage('reloadpage')" btnOneText="" btnTwoText=""
            @btnOneClicked="redirectToDetailsPage('review')" @btnTwoClicked="redirectToDetailsPage('pending')" />
    </div>

</template>
<script>
    import Button from "../../../../shared/Buttons/Button";
    import Modal from "../../../../shared/Modal";
    import FormLabelRequired from "../../../../shared/formLabels/FormLabelRequired.vue";
    import CustomSelect from "../../../../shared/CustomSelect";
    import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
    import ConfirmWithdrwalPrompt from "../../../../shared/messageboxes/ConfirmPrompt.vue";
    import MessageHeaderIconized from "../../../../shared/messageboxes/MessageHeaderIconized.vue";
    import SucessMessageBox from "../../../../shared/messageboxes/OneButtonActionMessageBox";
    import ActionMessage from "../../../../shared/messageboxes/ActionMessageBox.vue";


    export default {
        components: {
            SucessMessageBox,
            FormLabelRequired,
            CustomSelect,
            MessageHeaderIconized,
            Modal,
            ConfirmWithdrwalPrompt,
            TableActionButton,
            ActionMessage,
            Button
        },
        data() {
            return {
                showErrorMsg: false,
                errorMsg: '',
                errorTitle: '',
                showSuccessMsg: false,
                cannotwithdraw: false,
                fail: false,
                successMsg: '',
                successTitle: '',
                reason_for_withdraw: '',
                titlespan: "",
                showSuccessModal: false,
                successModalSize: "md",
                response_error: null,
                withdrawalRequest: false,
                submitted: false,
                reasonsForWithdraw: [
                    { 'id': 'Rate no longer available', 'description': 'Rate no longer available' },
                    { 'id': 'Reached my deposit limit', 'description': 'Reached my deposit limit' },
                    { 'id': 'Oversubscribed rate', 'description': 'Oversubscribed rate' },
                    { 'id': 'No longer interested', 'description': 'No longer interested' }]
            }
        },
        methods: {
            redirectToDetailsPage(to) {
                this.withdrawalRequest = false;
                this.showSuccessMsg = false;
                location.reload();

            },
            async showModal() {
                let url = `/trade/market-place/CG/get-offer-details?offerId=${this.actionId}`;
                let this_ = this
                await axios.get(url)
                    .then(response => {
                        this.offerDetails = response?.data
                        if (this.offerDetails?.purchase_history.length > 0) {
                            this_.cannotwithdraw = true
                        } else {
                            this_.withdrawalRequest = true;
                            console.log(true)
                        }
                    }).catch(error => {
                        this.is_loading = false;

                    });
                //get offer details
            },
            getReasonsForWithdraw(url) {
                url = url ? url : "/get-reasons-for-withdraw";
                axios.get(url)
                    .then(response => {

                    }).catch(error => {
                        this.is_loading = false;
                        // console.log("error > " + error);
                    });
            },
            withDrawRequest() {
                console.log(this.actionId)
                //submit
                let url = `/trade/CG/withdraw-request-offer`;
                let formData = new FormData();
                formData.append("offerID", this.actionId);
                // formData.append("reason", this.reason_for_withdraw);
                axios.post(url, formData, {
                }).then(response => {


                    if (response?.data?.success) {
                        // console.log(response, "success");
                        this.successTitle = "Withdraw Request.";
                        this.successMsg = response?.data?.message;
                        this.withdrawalRequest = false
                        setTimeout(() => {
                            this.showSuccessModal = false
                            location.reload();
                        }, 3000)
                        this.showSuccessMsg = true;
                    } else {
                        // console.log(response, "failure 2");
                        // this.errorTitle = response?.data?.message_title;
                        this.errorMsg = response?.data?.message;
                        this.fail = true;
                    }


                }).catch(error => {
                    if (error.response) {
                        this.errorTitle = "Error Submitting Selected Offers";
                        this.errorMsg = "Please contact admin.";
                        this.showErrorMsg = true;
                    } else if (error.request) {
                        // console.log(response, "failure 4");
                        // console.error("Request made but no response received:", error.request);
                    } else {
                        // console.log(response, "failure 5");
                        // console.error("Error during request setup:", error.message);
                    }

                });
                //submit


            },
            cancelIt() {
                this.withdrawalRequest = 0;
            }

        },
        mounted() {

        },
        props: ['actionId']
    }
</script>