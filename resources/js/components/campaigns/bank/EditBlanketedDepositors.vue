<template>
    <div style="width:100%;overflow-x:auto; overflow-y:hidden; ">
        <div class="target-depositor-container" style="width:100%; padding-right: 1rem !important;">
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
                                        d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                        fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path
                                        d="M16.4 11C15.5407 11 14.7166 11.3504 14.109 11.9742C13.5014 12.598 13.16 13.444 13.16 14.3261C13.16 15.2082 13.5014 16.0542 14.109 16.678C14.7166 17.3017 15.5407 17.6522 16.4 17.6522C17.2593 17.6522 18.0834 17.3017 18.691 16.678C19.2986 16.0542 19.64 15.2082 19.64 14.3261C19.64 13.444 19.2986 12.598 18.691 11.9742C18.0834 11.3504 17.2593 11 16.4 11ZM14.24 14.3261C14.24 14.0349 14.2959 13.7466 14.4044 13.4775C14.513 13.2085 14.6721 12.9641 14.8726 12.7582C15.0732 12.5523 15.3113 12.3889 15.5734 12.2775C15.8355 12.166 16.1163 12.1087 16.4 12.1087C16.6837 12.1087 16.9645 12.166 17.2266 12.2775C17.4887 12.3889 17.7268 12.5523 17.9274 12.7582C18.1279 12.9641 18.287 13.2085 18.3956 13.4775C18.5041 13.7466 18.56 14.0349 18.56 14.3261C18.56 14.9142 18.3324 15.4782 17.9274 15.894C17.5223 16.3099 16.9729 16.5435 16.4 16.5435C15.8271 16.5435 15.2777 16.3099 14.8726 15.894C14.4676 15.4782 14.24 14.9142 14.24 14.3261ZM23.96 12.4783C23.6291 12.4783 23.3014 12.5452 22.9956 12.6752C22.6899 12.8052 22.4121 12.9957 22.1781 13.236C21.9441 13.4762 21.7585 13.7614 21.6318 14.0752C21.5052 14.3891 21.44 14.7255 21.44 15.0652C21.44 15.4049 21.5052 15.7413 21.6318 16.0552C21.7585 16.3691 21.9441 16.6543 22.1781 16.8945C22.4121 17.1347 22.6899 17.3252 22.9956 17.4553C23.3014 17.5853 23.6291 17.6522 23.96 17.6522C24.6283 17.6522 25.2693 17.3796 25.7419 16.8945C26.2145 16.4093 26.48 15.7513 26.48 15.0652C26.48 14.3791 26.2145 13.7211 25.7419 13.236C25.2693 12.7508 24.6283 12.4783 23.96 12.4783ZM22.52 15.0652C22.52 14.6732 22.6717 14.2972 22.9418 14.0199C23.2118 13.7427 23.5781 13.587 23.96 13.587C24.3419 13.587 24.7082 13.7427 24.9782 14.0199C25.2483 14.2972 25.4 14.6732 25.4 15.0652C25.4 15.4573 25.2483 15.8333 24.9782 16.1105C24.7082 16.3877 24.3419 16.5435 23.96 16.5435C23.5781 16.5435 23.2118 16.3877 22.9418 16.1105C22.6717 15.8333 22.52 15.4573 22.52 15.0652ZM12.62 19.1304C12.1903 19.1304 11.7783 19.3056 11.4745 19.6175C11.1707 19.9294 11 20.3524 11 20.7935V21.186C11.0009 21.2458 11.005 21.3055 11.0122 21.3648C11.0245 21.4779 11.0475 21.6331 11.0914 21.8172C11.2109 22.3024 11.4231 22.7584 11.7157 23.1587C12.4458 24.1587 13.8382 25.0435 16.4 25.0435C17.5023 25.0435 18.3886 24.8794 19.0978 24.6133C19.0039 24.2463 18.9469 23.8703 18.9279 23.4913C18.326 23.7537 17.5095 23.9348 16.4 23.9348C14.1018 23.9348 13.0642 23.1565 12.5797 22.4935C12.3735 22.2115 12.2242 21.8901 12.1405 21.5481C12.1102 21.4249 12.0902 21.2993 12.0807 21.1727L12.08 21.1593V20.7935C12.08 20.6465 12.1369 20.5055 12.2382 20.4015C12.3394 20.2975 12.4768 20.2391 12.62 20.2391H19.7516C19.9888 19.8538 20.2713 19.4998 20.5926 19.1851C20.4579 19.1488 20.3192 19.1304 20.18 19.1304H12.62ZM29 23.1957C29 24.4698 28.5069 25.6918 27.6293 26.5928C26.7516 27.4938 25.5612 28 24.32 28C23.0788 28 21.8884 27.4938 21.0107 26.5928C20.1331 25.6918 19.64 24.4698 19.64 23.1957C19.64 21.9215 20.1331 20.6995 21.0107 19.7985C21.8884 18.8975 23.0788 18.3913 24.32 18.3913C25.5612 18.3913 26.7516 18.8975 27.6293 19.7985C28.5069 20.6995 29 21.9215 29 23.1957ZM24.68 20.2391C24.68 20.1411 24.6421 20.0471 24.5746 19.9778C24.507 19.9085 24.4155 19.8696 24.32 19.8696C24.2245 19.8696 24.133 19.9085 24.0654 19.9778C23.9979 20.0471 23.96 20.1411 23.96 20.2391V22.8261H21.44C21.3445 22.8261 21.253 22.865 21.1854 22.9343C21.1179 23.0036 21.08 23.0976 21.08 23.1957C21.08 23.2937 21.1179 23.3877 21.1854 23.457C21.253 23.5263 21.3445 23.5652 21.44 23.5652H23.96V26.1522C23.96 26.2502 23.9979 26.3442 24.0654 26.4135C24.133 26.4828 24.2245 26.5217 24.32 26.5217C24.4155 26.5217 24.507 26.4828 24.5746 26.4135C24.6421 26.3442 24.68 26.2502 24.68 26.1522V23.5652H27.2C27.2955 23.5652 27.387 23.5263 27.4546 23.457C27.5221 23.3877 27.56 23.2937 27.56 23.1957C27.56 23.0976 27.5221 23.0036 27.4546 22.9343C27.387 22.865 27.2955 22.8261 27.2 22.8261H24.68V20.2391Z"
                                        fill="#5063F4" />
                                </svg>
                            </div>
                            <div
                                style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                                Select The Target Depositors.</div>
                        </div>
                    </div>

                </div>
            </div>

            <div style="width: 100%; height:533px; overflow-y:scroll; ">

                <EditDepositorsList :campData="campData" :hideView="true" :hideAllActions="true" :allselectablee="true"
                    :selectable="true" @selectAllR="selectAllR"
                    @selectedOrganizations="setSelectedOrganizations($event)" :selected_items="selected_organizations"
                    :select_all="select_all" @listOfAllDepostors="setDepositorsCount" />

            </div>
            <div style="margin-top: 30px;">
                <div v-if="has_navigation"
                    style="display: flex;flex-direction: row;justify-content: flex-end;width: 100%;gap: 50px">
                    <b-button @click="selectAllGroups()"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                        Send To All
                    </b-button>
                    <b-button @click="nextStep"
                        style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                        Submit
                    </b-button>
                </div>

            </div>

            <GeneralNoInteractionError :size="box_size" @cancelled="() => showgeneralmessagebox = false"
                :title="box_title" :show="showgeneralmessagebox" :message="box_message" />
            <OKButtonActionMessageBoxVue :size="successModalSize" @closedSuccessModal="backToCampaign"
                :title="successModalTitle" :showm="showSuccessModal" @okClicked="backToCampaign" />


            <ConfirmNoOfDepositorsPrompt :size="successModalSize"
                @closedSuccessModal="showConfirmNoOfDepositorsCount=false" btnOneText="No" btnTwoText="Yes"
                :title="confirmNoOfDepositorsTitle" :showm="showConfirmNoOfDepositorsCount"
                @btnOneClicked="showConfirmNoOfDepositorsCount=false" @btnTwoClicked="proceedToNext()" />






            <DeleteGroupSuccess :size="successModalSize" @closedSuccessModal="showDeleteGroupSuccess = false"
                btnOneText="Previous" btnTwoText="View Groups" title="Group successfully Deleted"
                :showm="showDeleteGroupSuccess" @btnOneClicked="showDeleteGroupSuccess = false"
                @btnTwoClicked="showDeleteGroupSuccess = false" />
        </div>

    </div>
</template>
<style>
    .target-depositor-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .target-depositor-container .custom-checkbox .custom-control-label {
        margin-top: 0;
    }

    .target-depositor-container .custom-checkbox .custom-control-label::before {
        border-radius: 4px !important;
        border: 0.50px #5063F4 solid !important;
    }

    .target-depositor-container .active-group {
        width: 100%;
        cursor: pointer;
        background: rgba(80, 99, 244, 0.20);
    }

    .depositor-list {
        overflow: auto;
        width: 100%;
    }

    .depositor-row {
        display: flex;
        cursor: pointer;
        padding: 2px 10px;
        border-bottom: 0.5px #D9D9D9 solid;
        align-items: center;
        gap: 10px;
    }

    .checkbox-column {
        width: 5%;
        height: 39px;
        padding: 5px;
        display: flex;
        align-items: center;
    }

    .org-column {
        height: 20px;
        padding: 5px;
        background: #5063F4;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .org-label {
        text-align: center;
        color: #FDFDFD;
        font-size: 9px;
        font-weight: 500;
        word-wrap: break-word;
    }

    .name-column {
        width: 91%;
        min-height: 39px;
        height: auto;
        padding: 5px;
        color: black;
        font-size: 16px;
        font-weight: 500;
        align-items: center;
        display: flex;
    }

    .btn-add-rm-group {
        min-width: 200px;
        height: 40px !important;
        border: 0.50px #D9D9D9 solid;
        padding: 10px 30px 10px 30px !important;
        border-radius: 20px !important;
        color: #5063F4 !important;
        background-color: #F8FAFF !important;
        display: flex;
        justify-content: flex-start !important;
        /* Use flexbox for layout */

        /* Center content vertically */
    }

    .btn-add-group {
        margin-top: 200px;
        min-width: 200px;
        height: 40px !important;
        border: 0.50px #D9D9D9 solid;
        padding: 10px 30px 10px 30px !important;
        border-radius: 20px !important;
        color: #5063F4 !important;
        background-color: #F8FAFF !important;
        display: flex;
        justify-content: flex-start !important;
        /* Use flexbox for layout */

        /* Center content vertically */
    }
</style>
<script>
    import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import OKButtonActionMessageBoxVue from "../../shared/messageboxes/OKButtonActionMessageBox.vue";
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import CreateGroup from "./CreateGroup";
    import EditGroup from "./EditGroup";
    import GroupButton from "./GroupButton";
    import Tooltip from "../../shared/Tooltip";
    // import DeleteGroupPrompt from "./DeleteGroupPrompt";
    // import DeleteGroupSuccess from "./DeleteGroupSuccess";
    import ApiError from "./ApiError";
    import Button from "../../shared/Buttons/Button";
    import NoGroup from "./NoGroup";
    import Accordion from "../../shared/SectionTitleFullWidth";
    import NoData from "../../shared/NoData";

    import DeleteGroupSuccess from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import ConfirmNoOfDepositorsPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";
    import CustomInput from "../../shared/CustomInput";
    import FilterBox from "../../shared/GroupFilterBox";
    import EditDepositorsList from "./EditDepositorsList";


    export default {
        mounted() {

            this.loadAllProvinces();
            this.loadAllIndustries();
        },
        components: {
            EditDepositorsList,
            organizationfilterstr: '',
            FilterBox,
            CustomInput,
            Tooltip,
            NoData,
            OKButtonActionMessageBoxVue,
            TwoButtonActionMessageBox,
            Accordion,
            GeneralNoInteractionError,
            Button,
            EditGroup,
            CreateGroup,
            GroupButton,
            DeleteGroupSuccess,
            ConfirmNoOfDepositorsPrompt,
            ApiError,
            NoGroup
        },
        props: ['depositors', 'has_navigation', 'data', 'collected_details_data', 'selectable', 'selected_groups', 'typeOfInvite', 'multstepEdit'],
        data() {
            let organization_ids = [];
            //   console.log(this.data, "this.data?.campaign_groupsthis.data?.campaign_groups");

            if (this.data?.campaign_invite_depositors) {
                this.data.campaign_invite_depositors.forEach(e => {
                    organization_ids.push(e.organization_id);
                });
            }

            return {
                depositorsCount: 0,
                showConfirmNoOfDepositorsCount: false,
                confirmNoOfDepositorsTitle: "",
                confirmNoOfDepositorsMessage: "",
                selected_organizations: (organization_ids != null) ? organization_ids : [],
                groupings: ['Grouped Organizations', 'Ungrouped Organizations', 'All'],
                industries: [],
                provinces: [],
                filtered: [],
                organizationSearchKeyWord: '',
                groupSearchKeyWord: '',
                confirmRemoveOrg: 0,
                orgToremove: null,
                groupToRemoveFrom: null,
                confirmOrganizationRemoveMessage: '',
                showDeleteGroupSuccess: false,
                showSuccessModal: false,
                isChecked: {},
                is_open: true,
                displayableGroupDepositors: this.depositors,
                groups_lists: null,
                open_group: null,
                leftSelected: [],
                rightSelected: [],
                groups_selected: [],
                can_select_group: this.selectable !== undefined ? this.selectable : true,
                submitted: false,
                loading: false,
                success_group: false,
                response_error: null,
                group_id_to_delete: null,
                deleteStep: 0,
                showgeneralmessagebox: false,
                box_message: null,
                box_title: null,
                box_size: "md",
                successModalTitle: "",
                successbtnOneText: "",
                successbtnTwoText: "",
                showSuccessModal: false,
                successModalSize: "md",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalTitle: "",
                errorModalMessage: "",
                loadingaddorg: false,
                loadingrmorg: false,
                campData: this.data,
                select_all: false

            };
        },
        methods: {
            setDepositorsCount(val) {

                this.depositorsCount = val;
            },
            selectAllGroups() {

                this.select_all = !this.select_all;
            },
            backToCampaign() {

                this.showSuccessModal = false;

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('isDraft')) {
                    this.editableSteps = urlParams.get('steps').split(",");
                    let newEditableSteps = this.editableSteps.filter(item => item !== 'campaign_depositors');

                    var currentUrl = window.location.href;
                    var baseUrl = currentUrl.split('?')[0];
                    var newParams = `isDraft=1&steps=${newEditableSteps}&camptype=${this.data?.campaign_depositors_invite_type}`;
                    var updatedUrl = baseUrl + '?' + newParams;
                    window.location.href = updatedUrl + '&_refresh=' + Math.random();

                } else {
                    window.location.href = `/campaigns/summary/${this.data?.id}`;
                }


            },
            setSelectedOrganizations(organizations) {

                this.$emit('selectedOrganizations', organizations);
                this.selected_organizations = organizations;
            },
            onDepositorClick(depositor) {

            },
            capitalize(thestring) {
                if (thestring != undefined) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            nextStep() {

                if (this.selected_organizations.length <= 0) {
                    this.box_message = "Select Atleast 1 Target Depositor.";
                    this.box_title = "Target Depositors";
                    this.showgeneralmessagebox = !this.showgeneralmessagebox;
                    return;
                }
                this.showConfirmNoOfDepositorsCount = true;
                if (this.select_all) {
                    if ((this.depositorsCount === this.selected_organizations.length)) {
                        this.confirmNoOfDepositorsTitle = "Are you Sure You Want To Send This to All The Depositors?";
                    } else {
                        this.confirmNoOfDepositorsTitle = `Are you Sure you want to send this to ${this.selected_organizations.length} depositors?`;
                    }
                } else {
                    if ((this.depositorsCount === this.selected_organizations.length)) {
                        this.confirmNoOfDepositorsTitle = "Are you Sure You Want To Send This to All The Depositors?";
                    } else {
                        this.confirmNoOfDepositorsTitle = `Are you Sure you want to send this to ${this.selected_organizations.length} depositors?`;
                    }

                }


                console.log(this.campData, "this.campData structure");
                let groups = [];
                if (this.campData.campaign_groups) {
                    groups = this.campData?.campaign_groups.map((el, key) => {
                        return el.fi_campaign_group_id;
                    })
                } else if (this.campData?.groups) {
                    groups = this.campData?.groups.map((el, key) => {
                        return el.fi_campaign_group_id;
                    })
                }



            },
            proceedToNext() {


                this.showConfirmNoOfDepositorsCount = false;
                //check to ensure no blank depos allowed
                if (this.selected_organizations.length < 1) {
                    this.showgeneralmessagebox = true;
                    this.box_title = "Editing Target Depositors";
                    this.box_message = "Please select atleast one depositor";
                    return;
                }

                //check to ensure no blank depos allowed
                //get groups

                let groups = [];
                if (this.campData.campaign_groups) {
                    groups = this.campData?.campaign_groups.map((el, key) => {
                        return el.fi_campaign_group_id;
                    })
                } else if (this.campData?.groups) {
                    groups = this.campData?.groups.map((el, key) => {
                        return el.fi_campaign_group_id;
                    })
                }

                axios.post('/campaigns/fi/update-campaign', {
                    'campaignName': this.data?.campaign_name,
                    'expiryDate': this.data?.expiry_date,
                    'startDate': this.data?.start_date,
                    'currency': this.data?.currency,
                    'subscriptionAmount': this.data?.subscription_amount,
                    'products': this.selected_products,
                    'campaign': this.data?.id,
                    'status': this.data?.status,
                    'groups': groups,
                    'type_of_invite': this.typeOfInvite,
                    'selected_organizations': this.selected_organizations
                })
                    .then(response => {
                        this.submittedSaveDraft = false;
                        if (response?.data?.success) {
                            this.campData = response?.data?.data;
                            console.log(this.campData, " this.campData  updated");
                            if (this.multstepEdit) {
                                this.$emit("goToEditStep", 4);
                            } else {
                                this.showSuccessModal = true;
                                this.successModalTitle = "Depositors Editted Successfully";
                            }

                        } else {
                            throw new Error(response?.data?.message);
                        }
                    }).catch(error => {
                        this.$swal({
                            text: error,
                            confirmButtonText: 'Close'
                        });
                        this.submittedSaveDraft = false;
                    });
            },
            selectGroups(newValue) {
                if (this.isChecked[newValue]) {
                    if (!this.groups_selected.includes(newValue)) {
                        this.groups_selected.push(newValue);
                    }


                } else {
                    const index = this.groups_selected.indexOf(newValue);
                    if (index !== -1) {
                        console.log(index, "index to unselect -1");
                        this.groups_selected.splice(index, 1);
                    }

                }

            },
            searchGroup() {
                if (this.groupSearchKeyWord != null && this.groupSearchKeyWord != '') {
                    this.loading = true;
                    let this_ = this;
                    axios.get(`/campaigns/fi/my-groups?search=${this.groupSearchKeyWord}`)
                        .then(response => {

                            this_.groups_lists = response?.data;
                            this.loading = false;
                        }).catch(error => {
                            console.log("error > " + error);
                            this_.groups_lists = null;
                            this.loading = false;
                        });
                } else {
                    this.getGroups();
                }

            },
            getGroups() {
                this.loading = true;
                let this_ = this;
                axios.get(`/campaigns/fi/my-groups?search=${this.groupSearchKeyWord}`)
                    .then(response => {

                        this_.groups_lists = response?.data;
                        this.loading = false;
                    }).catch(error => {
                        console.log("error > " + error);
                        this_.groups_lists = null;
                        this.loading = false;
                    });
            },
            checkedDepositors(newValue) {

                if (newValue > 0) {
                    // Checkbox checked
                    this.leftSelected.push(newValue);

                } else {

                    // Checkbox unchecked
                    newValue = Math.abs(newValue);
                    const index = this.leftSelected.indexOf(newValue);
                    if (index !== -1) {
                        this.leftSelected.splice(index, 1);
                    }
                }

            },
            checkedGroups(newValue) {
                if (newValue > 0) {
                    // Checkbox checked
                    this.rightSelected.push(newValue);
                } else {
                    // Checkbox unchecked
                    newValue = Math.abs(newValue);
                    const index = this.rightSelected.indexOf(newValue);
                    if (index !== -1) {
                        this.rightSelected.splice(index, 1);
                    }
                }
            },
            async addOrgs() {
                let this_ = this;

                if (Array.isArray(this.leftSelected)) {
                    const length = this.leftSelected.length;

                    if (length === 0) {
                        this.box_message = "Please select a minimum of one depositor from your left hand side to add to your group .";
                        this.box_title = "Adding Depositor To a Group";
                        this.showgeneralmessagebox = !this.showgeneralmessagebox;

                    } else {
                        //    set open group based on selected groups
                        if (this.groups_selected.length > 1) {
                            this.box_message = "Please select only One Group.";
                            this.box_title = "Adding Depositor To a Group";
                            this.showgeneralmessagebox = !this.showgeneralmessagebox;
                            return;
                        } else {
                            this.open_group = this.groups_selected[0];
                        }
                        // select opne group based on select groups

                        if (this.open_group == null) {
                            this.box_message = "Please select a group to add to .";
                            this.box_title = "Adding Depositor To a Group";
                            this.showgeneralmessagebox = !this.showgeneralmessagebox;
                        } else {
                            this.loadingaddorg = true;
                            axios.post("/campaigns/fi/add-group-depositor", {
                                group: this.open_group,
                                depositors: this.leftSelected
                            }).then(response => {
                                if (response.data.success) {
                                    if (this.leftSelected.length > 1) {
                                        this.successModalTitle = "Depositors  Added to the Group.";
                                    } else {
                                        this.successModalTitle = "Depositor Added to the Group.";
                                    }

                                    this.showSuccessModal = true;
                                    this.successbtnOneText = "New Group";
                                    this.successbtnTwoText = "View Groups";

                                } else {
                                    this.showErrorModal = true;
                                    this.errorModalTitle = "Removing A Depositor";
                                    this.errorModalMessage = response?.data?.message;
                                }

                                console.log(response);
                                this_.getGroups();
                                this.loadingaddorg = false;
                            }).catch(error => {
                                console.log("error > " + error);
                                this.loadingaddorg = false;
                            });
                        }

                    }
                    await this_.refreshDisplayableDepositors(this.groups_selected);
                } else {
                    this.box_message = "Please select a minimum of one depositor from your left hand side to add to your group .";
                    this.box_title = "Adding a depositor to a group";
                    this.showgeneralmessagebox = !this.showgeneralmessagebox;

                }

            },
            showConfirmRemoveOrg(depositor, group) {
                this.groupToRemoveFrom = group;
                this.orgToremove = depositor;
                this.confirmOrganizationRemoveMessage = `Are you sure you want to remove ${this.orgToremove?.name} from ${this.groupToRemoveFrom?.group_name} group ?`;
                this.confirmRemoveOrg = 1;
            },
            async removeOrgs() {
                this.confirmRemoveOrg = 0;
                let this_ = this;
                this.loadingrmorg = true;
                axios.post("/campaigns/fi/remove-group-depositor", {
                    group: this.open_group,
                    depositors: [this.orgToremove?.id]
                }).then(response => {
                    if (response?.data?.success) {
                        if (this.rightSelected.length > 1) {
                            this.successModalTitle = "Depositors removed from the group";
                        } else {
                            this.successModalTitle = "Depositor removed from the group";
                        }

                        this.showSuccessModal = true;
                        this.successbtnOneText = "New Group";
                        this.successbtnTwoText = "View Groups";

                    } else {
                        this.showErrorModal = true;
                        this.errorModalTitle = "Removing a depositor";
                        this.errorModalMessage = response?.data?.message;
                    }

                    this_.getGroups();
                    // await  this.refreshDisplayableDepositors(this.open_group);
                    this.loadingrmorg = false;
                }).catch(error => {
                    console.log("error > " + error);
                    this.loadingrmorg = false;
                });
                await this_.refreshDisplayableDepositors(this.groups_selected);


            },
            async leftClick(group_id) {

                if (this.open_group == group_id) {
                    this.open_group = null;
                    // this.displayableGroupDepositors = this.depositors;
                } else {
                    this.open_group = group_id;
                    if (!this.groups_selected.includes(group_id)) {
                        this.groups_selected.push(group_id);
                    }


                    // await this.refreshDisplayableDepositors(this.groups_selected);
                }
                this.rightSelected = [];

            },
            selectAllR() {

            },
            selectedItems() {

            },
            loadAllProvinces() {

                axios.get("/get-all-provinces", {
                }).then(response => {
                    this.provinces = response?.data;

                }).catch(error => {

                });

            },
            loadAllIndustries() {

                axios.get("/get-all-industries", {
                }).then(response => {
                    //        console.log(response?.data, "all industries");
                    this.industries = response?.data;

                }).catch(error => {

                });

            },
            searchOrganization() {
                this.refreshDisplayableDepositors();
            },
            clearFilters() {
                this.organizationfilterstr = '';
                this.refreshDisplayableDepositors();
            },
            getFilters(data) {

                this.organizationfilterstr = data;
                this.refreshDisplayableDepositors();
            },
            refreshDisplayableDepositors(groups) {
                let selected_before_refresh = this.selected_organizations;
                axios.post(`/campaigns/fi/get-group-unlinked-depositors?search=${this.organizationSearchKeyWord}&${this.organizationfilterstr}`, {
                    groups: this.groups_selected,
                }).then(response => {
                    this.displayableGroupDepositors = response?.data;
                    this.selected_organizations = selected_before_refresh;
                }).catch(error => {

                });

            },
            deleteGroupInit(group_id) {
                this.group_id_to_delete = group_id;
                this.deleteStep = 1;
            },
            deleteGroup() {
                this.submitted = true;
                // let this_ = this;
                axios.post("/campaigns/fi/delete-group", {
                    group: this.group_id_to_delete,
                }).then(response => {
                    if (response?.data?.success) {
                        this.response_error = null;
                        this.success_group = true;
                        this.deleteStep = 2;
                        this.getGroups();
                        this.showDeleteGroupSuccess = true;


                    } else {
                        throw new Error(response?.data?.message);
                    }
                    this.submitted = false;
                }).catch(error => {
                    console.log("error > " + error);
                    this.success_group = false;
                    this.submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });
            },
            async deleteRefresh() {
                // await  this.refreshDisplayableDepositors(this.open_group);
                this.deleteStep = 0;
                this.group_id_to_delete = null;
                this.success_group = false;
                this.response_error = null;
                this.getGroups();

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
            toggleSelectAllDepositors(newval) {
                if (newval) {
                    this.displayableGroupDepositors.map((item, key) => {
                        this.leftSelected.push(item.id);
                    })
                    console.log(this.leftSelected, "this.leftSelected");
                } else {
                    this.leftSelected = [];
                }

            }
        },
        watch: {
            groups_selected: async function (val) {
                if (val.length === 1) {
                    this.open_group = val[0];
                }
                // await this.refreshDisplayableDepositors(this.groups_selected);

            }
        }
    }
</script>
