<template>
    <div>
        <div @click="show = true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
  <path d="M2.77199 9.56923C3.50492 6.44463 5.94464 4.00492 9.06924 3.27199C11.3258 2.74267 13.6742 2.74267 15.9308 3.27199C19.0554 4.00492 21.4951 6.44464 22.228 9.56924C22.7573 11.8258 22.7573 14.1742 22.228 16.4308C21.4951 19.5554 19.0554 21.9951 15.9308 22.728C13.6742 23.2573 11.3258 23.2573 9.06924 22.728C5.94464 21.9951 3.50492 19.5554 2.77199 16.4308C2.24267 14.1742 2.24267 11.8258 2.77199 9.56923Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
  <path d="M17.0601 9.8732C17.2794 9.65386 17.2794 9.28828 17.0601 9.08019L15.744 7.76412C15.5359 7.54477 15.1704 7.54477 14.951 7.76412L13.9162 8.79335L16.0252 10.9024M7.09961 15.6155V17.7246H9.20869L15.4291 11.4986L13.32 9.38952L7.09961 15.6155Z" fill="#5063F4"/>
</svg>
        </div>
        <Modal :visible.sync="show" :show="show">

            <b-row style="width:100%;padding: 0px !important;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="12" style="width:100%;padding: 0px !important;">
                    <MessageHeaderIconized title="Rename Group " width="100"
                        title_image="/assets/dashboard/icons/Promo-pencil.svg" />
                </b-col>
            </b-row>
            <b-row
                style="width:100%;padding: 0px !important;margin-top: 5px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="12" style="padding: 4px !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Group Name" required="true" showHelperText="true"
                        helperText="Group Name" helperId="groupNameHelperId" />
                    <CustomInput c-style="font-weight: 400;width:95%;" p-style="width:100%" id="group_name"
                        name="Group Name*" :has-validation="false" @inputChanged="new_group_name = $event" input-type="text"
                        :default-value="new_group_name" />
                </b-col>
            </b-row>
            <b-row
                style="width:100%; padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="5" style="padding: 4px !important;">
                    &nbsp;<br>
                    <span style="color:#5063F4;"> *(Mandatory Fields) </span>
                </b-col>
                <b-col md="7" style="padding: 10px; padding-right: 4px; !important;">
                    <div style="display: flex; align-items: center; justify-content: end;">
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

        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="success_group = false"
            btnOneText="New Group" btnTwoText="View Groups" :title="new_group_name" :showm="success_group"
            @btnOneClicked="newGroup" @btnTwoClicked="viewGroup" />
        <GeneralNoInteractionError :size="errorModalSize" @closedModal="response_error = null" title="Delete Drafts"
            :show="response_error !== null" :message="response_error" />
    </div>
</template>
<script>
import MessageHeaderIconized from "../../shared/messageboxes/MessageHeaderIconized.vue";
import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
import Button from "../../shared/Buttons/Button";
import Modal from "../../shared/Modal";
import CustomInput from "../../shared/CustomInput";
import Tooltip from "../../shared/Tooltip";
import AddGroupSuccess from "./AddGroupSuccess";
import ApiError from "./ApiError";
export default {
    components: {
        MessageHeaderIconized,
        TwoButtonActionMessageBox,
        GeneralNoInteractionError,
        Modal,
        Button,
        CustomInput,
        Tooltip,
        AddGroupSuccess,
        ApiError
    },
    data() {
        return {
            successModalTitle: "",
            successbtnOneText: "",
            successbtnTwoText: "",
            showSuccessModal: false,
            successModalSize: "md",
            errorModalSize: "md",
            showErrorModal: false,
            errorModalTitle: "",
            errorModalMessage: "",
            show: false,
            new_group_name: this.group.group_name,
            submitted: false,
            success_group: false,
            response_error: null
        };
    },
    props: ['group'],
    methods: {
        viewGroup() {
            this.show = false;
            this.success_group = false;
        },
        newGroup() {
            this.show = true;
            this.success_group = false;
        },
        submit() {
            if (this.submitted) {
                return;
            }

            this.submitted = true;
            let this__ = this;
            axios.post('/campaigns/fi/update-group', {
                'groupName': this.new_group_name,
                'group': this.group.id
            })
                .then(response => {
                    this.submitted = false;
                    if (response?.data?.success) {
                        this.response_error = null;
                        this__.show = false;
                        this.success_group = true;
                        this.$emit('group_updated', true);
                    } else {
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