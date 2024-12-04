<template>
    <div>
        <Modal :show="show" @isVisible="closeModal" modalsize="md">
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
                            :has-validation="haserror != null" :default-value="reason_for_withdraw"
                            v-model="reason_for_withdraw" @selectChanged="withDrawRequestReason" />
                        <div v-if="haserror" class="error-message">
                            {{ haserror }}
                        </div>
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
        <SucessMessageBox :title="successTitle" :message="successMsg" :show="showSuccessMsg" size="md"
            :showokbtn="false" @closedSuccessModal="redirectToDetailsPage('reloadpage')" btnOneText="Review Offers"
            btnTwoText="Pending Deposits" @btnOneClicked="redirectToDetailsPage('review')"
            @btnTwoClicked="redirectToDetailsPage('pending')" />
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
import { mapGetters } from "vuex";

export default {
    props: ['show', 'offer_id'],
    mounted() {

    },
    components: {
        SucessMessageBox,
        FormLabelRequired,
        CustomSelect,
        MessageHeaderIconized,
        Modal,
        ConfirmWithdrwalPrompt,
        TableActionButton,
        Button
    },
    data() {
        return {
            showErrorMsg: false,
            errorMsg: '',
            errorTitle: '',
            showSuccessMsg: false,
            successMsg: '',
            successTitle: '',
            reason_for_withdraw: '',
            titlespan: "",
            showSuccessModal: false,
            successModalSize: "md",
            response_error: null,
            withdrawalRequest: false,
            haserror: null,
            submitted: false,
            reasonsForWithdraw: [
                { 'id': 'Rate no longer available', 'description': 'Rate no longer available' },
                { 'id': 'Reached my deposit limit', 'description': 'Reached my deposit limit' },
                { 'id': 'Oversubscribed rate', 'description': 'Oversubscribed rate' },
                { 'id': 'No longer interested', 'description': 'No longer interested' }]
        }
    },
    computed: {
        ...mapGetters('repopostrequest', ['getAllOffersInReview', 'getsettlemntdate', 'getgloabalproducts']),
    },
    methods: {
        redirectToDetailsPage(to) {
            this.withdrawalRequest = false;
            this.showSuccessMsg = false;
            location.reload();

        },
        closeModal() {
            this.$emit('closeModal', false)
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
        withDrawRequestReason(value) {
            this.reason_for_withdraw = value
            this.haserror = null
        },
        withDrawRequest() {
            //submit
            if (this.reason_for_withdraw != '') {
                let url = `/trade/CG/withdraw-offer`;
                let formData = new FormData();
                formData.append("offerId", this.offer_id);
                formData.append("reason", this.reason_for_withdraw);
                axios.post(url, formData, {
                }).then(response => {


                    if (response?.data?.success) {
                        // console.log(response, "success");
                        this.successTitle = "Withdraw Request.";
                        this.successMsg = response?.data?.message;
                        this.withdrawalRequest = false
                        setTimeout(() => {
                            this.showSuccessModal = false
                            if (this.getAllOffersInReview.length > 1)
                                location.reload();
                            else {
                                window.location.href = "/repos/view-all-in-progress"
                            }
                        }, 3000)
                        this.showSuccessMsg = true;
                    } else {
                        // console.log(response, "failure 2");
                        this.errorTitle = response?.data?.message_title;
                        this.errorMsg = response?.data?.message;
                        this.showErrorMsg = true;
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
            } else {
                this.haserror = "Please select a reason"
            }
            //submit


        },
        cancelIt() {
            this.withdrawalRequest = 0;
        }

    },
}
</script>