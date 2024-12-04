<template>
    <div>
        <div class="">
            <!-- <FilterDropdown @apply_filters="submitCustomFilters" :provinces="provinces" :products="products"> -->
            <!-- </FilterDropdown> -->
            <FilterBox :filtered="filtered" :filterType="filterType" @apply_filters="submitFilters"
                @clear_filters="clearFilters" @searching="search" :provinces="provinces" :products="products">
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
        <div class="">
            <Table @reloadData="getData()" :columns="columns"
                no-data-message="Start by building a new campaign to populate data"
                :no-data-title="is_history ? 'No Campaign history' : 'No Active Campaigns'" :data="table_data"
                :has_action='true' :actions='actions' :is_loading="is_fetching_data" />
            <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
        </div>
    </div>
</template>
<style></style>
<script>
    import FilterBox from "../../shared/Table/FilterBox";
    import Pagination from "../../shared/Table/Pagination";
    import Table from "../../shared/Table";
    import CustomSelect from "../../shared/CustomSelect";
    import ViewCampaign from "./ViewCampaign";
    import EditCampaign from "./EditCampaign";
    import DeactivateCampaign from "./DeactivateCampaign";
    import TimerClock from "../TimerClock";
    import FilterDropdown from "../../shared/Table/FilterDropdown.vue";
    import { userCan } from "../../../utils/GlobalUtils";

    export default {
        mounted() {
            this.getData(null);
        },
        components: {
            FilterBox,
            Pagination,
            Table,
            CustomSelect,
            ViewCampaign,
            EditCampaign,
            DeactivateCampaign,
            FilterDropdown
        },
        created() {
        },
        props: ['is_history', 'products', 'provinces', 'filterType', 'userloggedin', 'timezone'],
        data() {
            let cols = ['Campaign Name', 'Products', 'Currency', 'Subscription Limit', 'Invitees', 'Order Filling'];
            if (!this.is_history) {
                cols.push("Expiry Date");
            }

            cols.push("Status");
            cols.push("Actions");
            let act = [
                {
                    name: "View",
                    component: ViewCampaign,
                }
            ];

            if (!this.is_history) {
                if (userCan(this.userloggedin, 'bank/my-campaigns/deactivate-campaign')) {
                    act.push({
                        name: "Deactivate",
                        component: DeactivateCampaign,
                    });
                }

                if (userCan(this.userloggedin, 'bank/my-campaigns/edit-campaign-details') ||
                    userCan(this.userloggedin, 'bank/my-campaigns/edit-campaign-products') ||
                    userCan(this.userloggedin, 'bank/my-campaigns/edit-target-depositors') ||
                    userCan(this.userloggedin, 'bank/my-campaigns/edit-product-rates')
                ) {
                    act.unshift({
                        name: "Edit",
                        component: EditCampaign,
                    });
                }
            }

            return {
                actions: act,
                columns: cols,
                data: null,
                table_data: null,
                per_page: 5,
                term_length_filter: null,
                product_type_filter: null,
                filtered: [],
                is_fetching_data: false
            }
        },
        methods: {
            userCan(user, permission) {
                return userCan(user, permission);
            },
            submitCustomFilters(value) {
                // console.log(value)
                this.getData("/campaigns/fi/my-campaigns?" + value);
            },
            renderExpiryDate(ex_date, startdate, useertimezone) {

                if (startdate === "ACTIVE") {
                    return ({ 'component': TimerClock, 'attrs': { targetTime: ex_date, timezone: useertimezone } });
                } else {
                    return ex_date;
                }

            },
            getData(url) {
                url = url ? url : "/campaigns/fi/my-campaigns?";
                // url+='&per_page='+this.per_page;
                if (this.is_history) {
                    url += '&status=history';
                } else {
                    url += '&status=ACTIVE,SCHEDULED';
                }

                // console.log(url)
                let this_ = this;
                this_.is_fetching_data = true;
                axios.get(url)
                    .then(response => {
                        this_.data = response?.data;

                        let table_data = [];
                        Object.values(response?.data?.data).forEach((item) => {
                            let datum = [
                                item.id,
                                this.capitalize(item.campaign_name),
                                item?.products.length,
                                item?.currency,
                                item?.subscription_amount,
                                item?.campaign_depositors_count,
                                item?.current_order_amount,
                            ];

                            if (!this_.is_history) {
                                datum.push(() => {
                                    return this.renderExpiryDate(item.expiry_date, item.status, this.timezone);
                                });
                            }
                            datum.push(() => {
                                return '<div>' + item.status.charAt(0).toUpperCase() + item.status.slice(1).toLowerCase() + '</div>';
                            });
                            // }

                            table_data.push(datum);
                        });

                        this_.table_data = table_data;
                        this_.is_fetching_data = false;
                    }).catch(error => {
                        // console.log("error > "+ error);
                        this_.data = null;
                        this_.table_data = null
                        this_.is_fetching_data = false;
                    });
            }, capitalize(thestring) {
                if (thestring != undefined) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            search(value) {
                console.log(value)
                this.getData("/campaigns/fi/my-campaigns?search=" + value);
            },
            submitFilters(value) {
                // let filters = (this.filtered);
                // console.log(value)
                this.getData("/campaigns/fi/my-campaigns?" + value);
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
            }
        },
    }
</script>