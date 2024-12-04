<template>
    <div style="width:100%;">
        <div
            style="width: 100%; height: 60px; padding: 11px 532px 9px 11px;background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
            <div
                style="width:100%;align-self: stretch; justify-content: flex-start; align-items: center; gap: 800px; display: inline-flex">
                <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                    <div style="width: 40px; height: 40px; position: relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.3261 10.2884 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.3261 29.7117 29.7117 33.3261 25.0826 34.4119C21.7396 35.1961 18.2604 35.1961 14.9174 34.4119C10.2884 33.3261 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                            <path
                                d="M29.4213 11.0651C29.1825 10.9106 28.9095 10.8171 28.6261 10.7929C28.3428 10.7687 28.0578 10.8145 27.7963 10.9262L19.2269 14.3012C19.024 14.3837 18.807 14.4262 18.588 14.4262H13.1019C12.6414 14.4262 12.1998 14.6091 11.8742 14.9347C11.5487 15.2603 11.3657 15.7018 11.3657 16.1623V16.3012H9.62964V20.4678H11.3657V20.6484C11.3766 21.1016 11.5643 21.5325 11.8887 21.8492C12.2132 22.1658 12.6485 22.343 13.1019 22.3428L15.1852 26.7595C15.3263 27.0569 15.5484 27.3085 15.826 27.4854C16.1036 27.6623 16.4255 27.7573 16.7546 27.7595H17.6296C18.0877 27.7558 18.5257 27.5713 18.8483 27.2461C19.1709 26.9209 19.3519 26.4814 19.3519 26.0234V22.5095L27.7963 25.8845C28.004 25.9672 28.2255 26.0096 28.4491 26.0095C28.7959 26.0039 29.1336 25.8978 29.4213 25.704C29.6497 25.5497 29.8382 25.3433 29.971 25.1018C30.1038 24.8603 30.1773 24.5906 30.1852 24.3151V12.4956C30.1839 12.213 30.1137 11.935 29.9805 11.6857C29.8474 11.4364 29.6555 11.2233 29.4213 11.0651ZM17.6157 16.1623V20.6484H13.1019V16.1623H17.6157ZM17.6157 26.0234H16.7407L15.0324 22.3428H17.6157V26.0234ZM19.8657 20.8567C19.7008 20.7725 19.5289 20.7028 19.3519 20.6484V16.0651C19.5272 16.0289 19.6991 15.9778 19.8657 15.9123L28.4491 12.4956V24.2734L19.8657 20.8567ZM30.2269 16.6484V20.1206C30.6873 20.1206 31.1289 19.9377 31.4545 19.6121C31.7801 19.2865 31.963 18.845 31.963 18.3845C31.963 17.9241 31.7801 17.4825 31.4545 17.1569C31.1289 16.8313 30.6873 16.6484 30.2269 16.6484Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                    <div
                        style="width: 457px; height: 25px; color: #252525; font-size: 30px; font-weight: 800; line-height: 32px; word-wrap: break-word">
                        My Active Campaigns</div>
                </div>
            </div>
        </div>

        <div style="width:100%;display: flex; flex-direction: column; gap: 20px; justify-content: flex-start">
            <div class="ml-5" style="width: 100% !important;margin-top:30px">
                <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                    @searching="search" filterType="campaign">
                    <b-col>
                        <label>Product Type</label>
                        <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                            p-style="width: 100%"
                            c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                            :data="products" id="product_type" name="Product Types" :has-validation="false"
                            :default-value="product_type_filter"
                            @selectChanged="filterInputChanges($event, 'product_type_filter')" />
                    </b-col>
                </FilterBox>
            </div>
            
            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                <b-tab title="Active Campaigns" active @click="handleActiveTabChange('active')">

                </b-tab>
                <b-tab title="Matured Campaigns" @click="handleActiveTabChange('matured')">

                </b-tab>
                <Table no-data-title="No Active Campaign Found"
                    no-data-message="No active campaign was found for selection. Please use create a new campaign button below."
                    @selectedItems="selectedCampaign = $event" :selectable="true" :allselectable="false"
                    :columns="columns" :data="table_data" :has_action='true' :actions='actions' />
                <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
            </b-tabs>



            <div
                style="justify-content: flex-end; align-items: flex-start; gap: 50px; display: inline-flex ; width: 100%;">
                <Button v-if="!table_data || table_data && table_data.length === 0" @click="prevStep"
                    type="outlined-gray">Add
                    New Campaign</Button>

                <b-button @click="nextStep"
                    style=" width:166px; height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                    Next
                </b-button>
            </div>
        </div>
        <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" />
    </div>
</template>
<style></style>
<script>
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import FilterBox from "../../shared/Table/FilterBox";
    import Pagination from "../../shared/Table/Pagination";
    import Table from "../../shared/Table";
    import TableExpired from "../../shared/Table";
    import CustomSelect from "../../shared/CustomSelect";
    import ViewCampaign from "./ViewCampaign";
    import Button from "../../shared/Buttons/Button";

    export default {
        mounted() {
            this.getData(null);
            // this.getExpiredData(null);
        },
        components: {
            GeneralNoInteractionError,
            FilterBox,
            Pagination,
            Table,
            TableExpired,
            CustomSelect,
            ViewCampaign,
            Button
        },
        created() {
        },
        props: ['is_history', 'products'],
        data() {
            let cols = ['Campaign Name', 'Products', 'Currency', 'Subscription Limit', 'Invitees', 'Order Filling', 'Expiry Date', 'Actions'];

            let act = [
                {
                    name: "View",
                    component: ViewCampaign,
                }
            ];

            return {
                errorModalTitle: "",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalMessage: "",
                actions: act,
                columns: cols,
                data: null,
                data_expired: null,
                table_data: null,
                table_data_expired: null,
                per_page: 5,
                term_length_filter: null,
                product_type_filter: null,
                filtered: [],
                selectedCampaign: null
            }
        },
        methods: {
            handleActiveTabChange(status) {

                if (status === "active") {
                    this.getData();
                } else {
                    this.getExpiredData();
                }
            },
            closeErrorModal() {
                this.showErrorModal = false;
            },
            prevStep() {
                this.$emit('back-step', 2);
                this.$emit('choice', 'new');
            },
            nextStep() {
                if (!this.selectedCampaign || this.selectedCampaign?.length === 0) {

                    this.showErrorModal = true;
                    this.errorModalTitle = "Select Existing Campaign";
                    this.errorModalMessage = "Please select campaign.";

                    return;
                }

                if (this.selectedCampaign?.length > 1) {

                    this.showErrorModal = true;
                    this.errorModalTitle = "Select Existing Campaign.";
                    this.errorModalMessage = "Please select only 1 campaign";

                    return;
                }

                let c_id = null;
                this.selectedCampaign.forEach(e => {
                    c_id = e;
                });

                this.$emit('selectedExistingCampaign', c_id)
            },
            getData(url) {
                url = url ? url : "/campaigns/fi/my-campaigns?";
                url += '&status=ACTIVE,SCHEDULED';
                let this_ = this;
                axios.get(url)
                    .then(response => {
                        this_.data = response?.data;
                        // console.log(this_.data);

                        let table_data = [];
                        Object.values(response?.data?.data).forEach((item) => {
                            let datum = [
                                item.id,
                                item.campaign_name,
                                item.products.length,
                                item.currency,
                                item.subscription_amount,
                                item.campaign_invite_depositors.length,
                                item.current_order_amount,
                                item.expiry_date
                            ];
                            table_data.push(datum);
                        });
                        this_.table_data = [];
                        this_.table_data = table_data;
                    }).catch(error => {
                        // console.log("error > "+ error);
                        this_.data = null;
                        this_.table_data = null
                    });
            },
            getExpiredData(url) {
                url = url ? url : "/campaigns/fi/my-campaigns?";
                url += '&status=EXPIRED,COMPLETED';
                let this_ = this;
                axios.get(url)
                    .then(response => {
                        this_.data = response?.data;
                        // console.log(this_.data);

                        let table_data = [];
                        Object.values(response?.data?.data).forEach((item) => {
                            let datum = [
                                item.id,
                                item.campaign_name,
                                item.products.length,
                                item.currency,
                                item.subscription_amount,
                                item.campaign_invite_depositors.length,
                                item.current_order_amount,
                                item.expiry_date
                            ];
                            table_data.push(datum);
                        });
                        this_.table_data = [];
                        this_.table_data = table_data;
                    }).catch(error => {
                        // console.log("error > "+ error);
                        this_.data = null;
                        this_.table_data = null
                    });
            },
            search(value) {
                this.getData("/campaigns/fi/my-campaigns?search=" + value);
            },
            submitFilters(value) {
                this.getData("/campaigns/fi/my-campaigns?filters=" + value);
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
            clearFilters(value) {
                this.getData()
            }
        },
    }
</script>