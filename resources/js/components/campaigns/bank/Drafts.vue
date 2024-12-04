<template>
    <div style="width: 100% ;padding-right:1rem;">
        <accordion is_open="true" title="Draft campaigns" width="100"
            title_image="/assets/dashboard/icons/Vector.svg" />
        <div class="ml-5" style="margin-top:15px;margin-bottom:15px;">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                @searching="search" filterType="draft-campaign">
                <b-col>
                    <label>Product Type</label>
                    <CustomSelect :attributes="{ 'value_field': 'description', 'text_field': 'description' }"
                        p-style="width: 100%"
                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white; "
                        :data="JSON.parse(products)" id="product_type" name="Product Types" :has-validation="false"
                        :default-value="product_type_filter"
                        @selectChanged="filterInputChanges($event, 'product_type_filter')" />
                </b-col>
            </FilterBox>
        </div>
        <div class="">
            <Table :columns="columns" @reloadData="getData()" :is_loading="is_loading_data"
                noDataMessage="Start by building a new campaign and save a draft to populate data"
                noDataTitle="No Draft Campaigns" :data="table_data" :has_action='true' :actions='actions' />
            <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
        </div>
        <div v-if="table_data && table_data?.length > 0" @click="deleteStep = 1"
            style="width: 100%; display: flex; justify-content: flex-end">

            <b-button
                style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                Delete All
            </b-button>
        </div>
        <ConfirmDeletionPrompt :size="successModalSize" @closedSuccessModal="cancelIt" btnOneText="Previous"
            btnTwoText="Delete" :title="(deleteStep === 1) ? 'Sure you want to delete all?' : 'Delete Draft'"
            :showm="deleteStep === 1" @btnOneClicked="cancelIt" @btnTwoClicked="deleteAll()"
            message="This operation cannot be undone." />
        <DeleteDraftSuccess :size="successModalSize" @closedSuccessModal="showSuccessModal = false" btnOneText="Drafts"
            btnTwoText="Campaigns" title="Drafts successfully deleted" :showm="showSuccessModal"
            @btnOneClicked="showSuccessModal = false" @btnTwoClicked="seeCampaigns()" />
        <!-- <DeleteDraftPrompt :deleting_submitted="deleting_submitted"  @cancelIt="cancelIt" @deleteIt="deleteAll" :show="deleteStep === 1" />
        <DeleteDraftSuccess @hideModal="dismissIt" :show="deleteStep === 2" />
        <ApiError @cancelled="response_error=false" title="Delete Drafts" :message="response_error" :show="response_error!==null" /> -->
    </div>
</template>
<script>
    import DeleteDraftSuccess from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import ConfirmDeletionPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";
    import MessageHeaderIconized from "../../shared/messageboxes/MessageHeaderIconized.vue";
    import Accordion from "../../shared/SectionTitleFullWidth";
    import FilterBox from "../../shared/Table/FilterBox";
    import Pagination from "../../shared/Table/Pagination";
    import Table from "../../shared/Table";
    import CustomSelect from "../../shared/CustomSelect";
    import ViewCampaign from "./ViewCampaign";
    import ActivateDraftCampaign from "./ActivateDraftCampaign"
    // import EditActivateDraftCampaign from "./EditActivateDraftCampaign"
    //  import EditCampaign from "./EditCampaign";
    import EditCampaign from "./EditActivateDraftCampaign";
    import DeleteCampaign from "./DeleteCampaign";
    import Button from "../../shared/Buttons/Button";
    import DeleteDraftPrompt from "./DeleteDraftPrompt";
    import ApiError from "./ApiError";

    export default {
        mounted() {
            this.getData(null);
        },
        components: {
            DeleteDraftSuccess,
            ConfirmDeletionPrompt,
            MessageHeaderIconized,
            Accordion,
            ApiError,
            Button,
            FilterBox,
            Pagination,
            Table,
            CustomSelect,
            ViewCampaign,
            EditCampaign,
            DeleteCampaign,
            DeleteDraftSuccess,
            DeleteDraftPrompt
        },
        created() {
        },
        props: ['products'],
        data() {
            return {
                actions: [
                    {
                        name: "View",
                        component: ViewCampaign,
                    },
                    {
                        name: "Activate",
                        component: ActivateDraftCampaign,
                    },
                    {
                        name: "Edit",
                        component: EditCampaign,
                    },
                    {
                        name: "Delete",
                        component: DeleteCampaign,
                    }
                ],
                successModalSize: "md",
                showSuccessModal: false,
                data: null,
                table_data: null,
                columns: ['Campaign Name', 'Products', 'Currency', 'Subscription Limit', 'Groups', 'Expiry Date'],
                per_page: 5,
                term_length_filter: null,
                product_type_filter: null,
                filtered: [],
                deleteStep: 0,
                deleting_submitted: false,
                response_error: null,
                is_loading_data: null
            }
        },
        methods: {
            getData(url) {
                url = url ? url : "/campaigns/fi/my-campaigns?status=DRAFT";
                let this_ = this;
                this_.is_loading_data = true;
                console.log(url)
                axios.get(url)
                    .then(response => {
                        this_.data = response?.data;
                        this_.is_loading_data = false;

                        let table_data = [];
                        Object.values(response?.data?.data).forEach((item) => {
                            table_data.push([
                                item.id,
                                item.campaign_name,
                                item.products.length,
                                item.currency,
                                item.subscription_amount,
                                (item.campaign_depositors_invite_type === "Targeted") ? 0 : item.groups.length,
                                item.expiry_date,
                            ]);
                        });

                        this_.table_data = table_data;
                    }).catch(error => {
                        console.log("error > " + error);
                        this_.data = null;
                        this_.table_data = null;
                        this_.is_loading_data = false;
                    });
            },
            search(value) {
                this.getData("/campaigns/fi/my-campaigns?search=" + value);
            },
            seeCampaigns() {
                window.location.href = "/campaigns";
            },
            submitFilters(value) {
                // console.log(value)
                this.getData("/campaigns/fi/my-campaigns?" + value + "&status=DRAFT");
            },
            filterInputChanges(value, key) {
                let valuesToCheck = [];
                switch (key) {
                    case 'term_length_filter':
                        valuesToCheck = ['MONTHS', 'DAYS'];
                        break;
                    case 'product_type_filter':
                        Object.values(this.products).forEach((item) => {
                            valuesToCheck.push(item.description);
                        });

                        let fv = Object.values(this.products).filter((item => {
                            return item.id == value
                        }));

                        value = fv.length > 0 ? fv[0].description : value;
                        break;
                }

                this.filtered = this.filtered.filter(item => !valuesToCheck.includes(item));

                if (!this.filtered.includes(value)) {
                    this.filtered.push(value);
                }
            },
            clearFilters() {
                this.getData()
            },
            deleteAll() {
                this.deleting_submitted = true;
                axios.post("/campaigns/fi/my-campaigns/delete-campaign", {
                    campaign: 'all',
                }).then(response => {
                    if (response?.data?.success) {
                        this.showSuccessModal = true;
                        this.successbtnOneText = "Add New Product";
                        this.successbtnTwoText = "View Products";
                        this.response_error = null;
                        this.deleteStep = 2;
                        this.deleting_submitted = true;
                        this.getData();
                    } else {
                        new Error(response?.data?.message);
                    }
                }).catch(error => {
                    this.deleting_submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });
            },
            cancelIt() {
                this.deleteStep = 0;
            },
            dismissIt() {
                this.deleteStep = 0;
                this.getData();
            }
        },
    }
</script>