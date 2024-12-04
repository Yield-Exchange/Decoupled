<template>
    <div>
        <div class="ml-5">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" :filterType="filterType"
                @clear_filters="clearFilters" @searching="search" :products="products">
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
                no-data-title="No Active Campaign Products" :data="table_data" :has_action='true' :actions='actions'
                :has_featured_label="true" :nonRenderbleItems="nonRenderbleItems"
                :nonRenderbleColumns="nonRenderbleColumns" />
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
    import ViewCampaignProduct from "./ViewCampaignProduct";
    import DeactivateCampaignProduct from "./DeactivateCampaignProduct";
    import FeatureUnfeatureProduct from "./FeatureUnfeatureProduct";
    import FeatureProduct from "./FeatureProduct";
    import FeatureProductLabel from "../../shared/FeaturedProduct"
    import UnFeatureProduct from "./UnFeatureProduct";
    import TimerClock from "../TimerClock";
    import {userCan} from "../../../utils/GlobalUtils";

    export default {
        mounted() {
            this.getData(null);
        },
        components: {
            FeatureProductLabel,
            TimerClock,
            FilterBox,
            Pagination,
            Table,
            CustomSelect,
            ViewCampaignProduct,
            DeactivateCampaignProduct,
            FeatureUnfeatureProduct
        },
        created() {
        },
        props: ['is_history', 'products', 'filterType', 'userloggedin'],
        data() {
            let cols = ['Campaign Name', 'Product Name', 'Term & Length', 'Rate (%)', 'Currency', 'Minimum', 'Maximum', 'Status', 'Featured', 'Actions', 'isFeatured'];

            let actions_ = [
                {
                    name: "View",
                    component: ViewCampaignProduct,
                },
                {
                    name: "UnFeatureProduct",
                    component: FeatureProduct,
                    conditionChecker: 'isFeatured',
                    conditionCheckerValue: 0,
                    condition_checker_index: 10
                },
                {
                    name: "FeatureProduct",
                    component: UnFeatureProduct,
                    conditionChecker: 'isFeatured',
                    conditionCheckerValue: 1,
                    condition_checker_index: 10
                }
            ];

            if(userCan(this.userloggedin,'depositor/my-offers---campaigns/remove-campaign-products')){
                actions_.push({
                    name: "Remove",
                    component: DeactivateCampaignProduct,
                })
            }

            return {

                actions: actions_,
                nonRenderbleColumns: ['isFeatured'],
                nonRenderbleItems: [10],
                columns: cols,
                data: null,
                table_data: null,
                per_page: 5,
                term_length_filter: null,
                product_type_filter: null,
                filtered: []
            }
        },
        methods: {
            userCan(user, permission) {
                return userCan(user, permission);
            },
            getData(url) {
                url = url ? url : `/campaigns/fi/my-campaign-products?status=ACTIVE,SCHEDULED`;
                // url+='?per_page='+this.per_page;
                let this_ = this;
                axios.get(url)
                    .then(response => {
                        this_.data = response?.data;
                        let table_data = [];
                        Object.values(response?.data?.data).forEach((item) => {
                            let datum = [
                                item.id,
                                this.capitalize(item.campaign_name),
                                this.capitalize(item.custom_product_name),
                                item.term_length + ' ' + item.term_length_type.charAt(0).toUpperCase() + item.term_length_type.slice(1).toLowerCase(),
                                item.rate,
                                item.currency,
                                item.minimum,
                                item.maximum,
                                this.capitalize(item.status),
                                () => {
                                    return ({ 'component': FeatureProductLabel, 'attrs': { featured: item.isFeatured } });
                                }, item.isFeatured
                            ];

                            table_data.push(datum);
                        });

                        this_.table_data = table_data;
                    }).catch(error => {
                        this_.data = null;
                        this_.table_data = null
                    });
            },
            capitalize(thestring) {
                return thestring
                    .toLowerCase()
                    .split(' ')
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            },
            search(value) {
                this.getData("/campaigns/fi/my-campaign-products?search=" + value);
            },
            submitFilters(value) {
                // let filters = (this.filtered);
                // console.log(value)
                this.getData("/campaigns/fi/my-campaign-products?search=&" + value);
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