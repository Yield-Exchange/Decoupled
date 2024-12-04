<template>
    <div>
        <GroupButton type="outlined" @click="show = true">Create group</GroupButton>
        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="closeSuccessModal()"
            :btnOneText="successbtnOneText" :btnTwoText="successbtnTwoText" :title="successModalTitle"
            :showm="showSuccessModal" @btnOneClicked="newGroup()" @btnTwoClicked="viewGroups()" />
        <Modal :visible.sync="show" :size="modalSize" :show="show" @closemodal="closeAddGroupModal()">

            <b-row style="width:100%;padding: 0px !important;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="12" style="width:100%;padding: 0px !important;">
                    <MessageHeaderIconized title="Add A Group " width="100"
                        title_image="/assets/dashboard/icons/Promo-pencil.svg" />
                </b-col>
            </b-row>
            <b-row
                style="width:100%;padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="12" style="padding: 4px !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Group Name" required="true" showHelperText="true"
                        helperText="Group Name" helperId="groupNameHelperId" />
                    <CustomInput c-style="font-weight: 400;width:95%;" p-style="width:100%" id="group_name" required="true"
                        :has-validation="false" name="Group name" @inputChanged="group_name = $event" input-type="text" />
                </b-col>
            </b-row>
            <b-row
                style="padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">




                <b-col md="12" lg="12" xl="12" sm="12"
                    style="padding: 10px; padding-right: 4px; !important;display: flex; flex-direction:row; align-items: center; justify-content: flex-end;">
                    <div style="width:80%; display: flex; align-items: center; justify-content: flex-start;">
                        <span style="color:#5063F4;"> *(Mandatory Fields) </span>
                    </div>
                    <div style="width:20%;display: flex; align-items: center; justify-content: flex-end">
                        <b-button @click="submit" class="message-action-btn-solid-">
                            <b-spinner label="Loading" v-if="submitted"
                                style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                            </b-spinner>
                            Submit
                        </b-button>
                    </div>


                </b-col>
            </b-row>


        </Modal>
        <!-- <AddGroupSuccess @newGroup="newGroup" @viewGroup="viewGroup" :is_create="true" :show="success_group"
            :name="group_name" /> -->
        <!-- <ApiError @cancelled="response_error = null" :title="errorTitle" :message="response_error"
            :show="response_error !== null" /> -->

        <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" />
    </div>
</template>
<style>
.message-action-btn-solid- {
    width: 30%;
    height: 40px;
    padding: 10px, 30px, 10px, 30px;
    border-radius: 20px;
    border: 2px;
    background-color: #5063F4 !important;
    color: white;
}
</style>
<script>
import MessageHeaderIconized from "../../shared/messageboxes/MessageHeaderIconized.vue";
import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
import Button from "../../shared/Buttons/Button";
import Modal from "../../shared/ModalNew";
import FormLabelRequired from "../../shared/formLabels/FormLabelRequired.vue";
import CustomInput from "../../shared/CustomInput";
import Tooltip from "../../shared/Tooltip";
import GroupButton from "./GroupButton";
import AddGroupSuccess from "./AddGroupSuccess";
import ApiError from "./ApiError";
export default {
    components: {
        TwoButtonActionMessageBox,
        GeneralNoInteractionError,
        MessageHeaderIconized,
        FormLabelRequired,
        Modal,
        Button,
        CustomInput,
        Tooltip,
        GroupButton,
        AddGroupSuccess,
        ApiError
    },
    data() {
        return {
            errorModalSize: "md",
            showErrorModal: false,
            errorModalTitle: "",
            errorModalMessage: "",
            modalSize: "md",
            show: false,
            group_name: null,
            submitted: false,
            success_group: false,
            response_error: null,
            errorTitle: "Creating A Group",
            successModalTitle: "",
            successbtnOneText: "",
            successbtnTwoText: "",
            showSuccessModal: false,
            successModalSize: "md",
        };
    },
    methods: {
        viewGroup() {
            this.show = false;
            this.success_group = false;
        },
        viewGroups() {
            this.show = false;
            this.showSuccessModal = false;
        },
        newGroup() {
            this.show = true;
            this.success_group = false;
        },
        closeAddGroupModal() {
            this.show = false;
        },
        closeErrorModal() {
            this.showErrorModal = false;
        },
        closeSuccessModal() {
            this.showSuccessModal = false;
        },
        submit() {
            if (this.submitted) {
                return;
            }
            this.submitted = true;
            let this__ = this;
            if (this.group_name == "" || this.group_name == null) {
                this.showErrorModal = true;
                this.errorModalTitle = "Add Group Error";
                this.errorModalMessage = "Please provide A Group Name";
                this.show = true;
                this.submitted = false;
                return;
            }
            axios.post('/campaigns/fi/create-group', {
                'groupName': this.group_name,
            })
                .then(response => {
                    this.submitted = false;
                    if (response?.data?.success) {
                        this.response_error = null;
                        this__.show = false;
                        this.success_group = true;
                        this.successModalTitle = "Group Created successfully";
                        this.showSuccessModal = true;
                        this.successbtnOneText = "New Group";
                        this.successbtnTwoText = "View Groups";
                        this.$emit('group_created', true);
                    } else {
                        this.showErrorModal = true;
                        this.errorModalTitle = "Add Group Error";
                        this.errorModalMessage = response?.data?.message;
                        this.show = true;
                        throw new Error(response?.data?.message);
                    }
                }).catch(error => {
                    this.success_group = false;
                    this.submitted = false;
                    this.response_error = error;
                });
        },
    }
}
</script>