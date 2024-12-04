<template>
    <div style="width: 100%">
        <div class="ml-10">
            <FilterBox :filtered="filtered" @apply_filters="submitFilters" @clear_filters="clearFilters"
                filterType="all-products" :products="products" @searching="search">
                <b-col>
                    <label>Term Length</label>
                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                        p-style="width: 100%"
                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                        :data="['MONTHS', 'DAYS']" id="term_length" name="Term Length" :has-validation="false"
                        @selectChanged="filterInputChanges($event, 'term_length_filter')" />
                </b-col>

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
        <div style="width: 100%">

            <Table v-if="hideAllActions" @reloadData="getData()" :columns="columns"
                @selectedItems="$emit('selectedItems', $event)" @selectAllR="allselected"
                no-data-title="No Active Products" no-data-message="Start by adding a new product to populate data"
                :data="table_data" :has_action='false' @productDeletedAddNew="$emit('productDeletedAddNew')"
                :selectable="selectable" :selected_items="selected_items"
                :allselectable="(allselectablee) ? true : false" />


            <Table v-else @reloadData="getData()" :columns="columns" @selectedItems="$emit('selectedItems', $event)"
                @selectAllR="allselected" no-data-title="No Active Products"
                no-data-message="Start by adding a new product to populate data" :data="table_data" :has_action='true'
                :actions='actions' @productDeletedAddNew="$emit('productDeletedAddNew')" :selectable="selectable"
                :selected_items="selected_items" :allselectable="(allselectablee) ? true : false" />


        </div>
        <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
    </div>
</template>
<script>
    import Table from "../../shared/ProdTable";
    import Pagination from "../../shared/Table/Pagination";
    import FilterBox from "../../shared/Table/FilterBox";
    import CustomSelect from "../../shared/CustomSelect";

    import EditProduct from "./EditProduct";
    import DeleteProduct from "./DeleteProduct";
    import ActivateProduct from "./ActivateProduct";
    import ViewProduct from "./ViewProduct";

    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/campaigns/mutation-types.js';

    export default {
        mounted() {
            this.getData(null);
        },
        computed: {
            ...mapGetters('campaign', ['getCampaignSelectedProducts', 'getCampaignSelectedProductIDS', 'getSelectAllProducts', 'getAllActiveProducts']),
        },
        components: {
            ActivateProduct,
            Table,
            Pagination,
            FilterBox,
            CustomSelect,
            EditProduct,
            ViewProduct
        },
        created() {
        },
        props: ['products', 'selectable', 'selected_items', 'sync', 'hideView', 'allselectablee', 'hideAllActions', 'activeOnly'],
        data() {
            let act = [];
            let columnss = [];
            if (this.hideAllActions) {
                this.columnss = ['Product Name', 'Product Type', 'Term Length', 'Notice Period', 'Lockout Period', 'Status', 'PDS'];
            } else {
                this.columnss = ['Product Name', 'Product Type', 'Term Length', 'Notice Period', 'Lockout Period', 'Status', 'PDS', 'Actions'];
                act = [
                    {
                        name: "Edit",
                        component: EditProduct,
                        attrs: { products: this.products },
                    },
                    {
                        name: "Delete",
                        component: DeleteProduct,
                        conditionChecker: 'Status',
                        conditionCheckerValue: 'Active',
                        condition_checker_index: 6
                    }, {
                        name: "Activate",
                        component: ActivateProduct,
                        conditionChecker: 'Status',
                        conditionCheckerValue: 'Inactive',
                        condition_checker_index: 6
                    }
                ];

                if (!this.hideView) {
                    act.push({
                        name: "View",
                        component: ViewProduct,
                    })
                }
            }


            return {
                actions: act,
                data: null,
                table_data: null,
                columns: this.columnss,
                per_page: 5,
                term_length_filter: null,
                product_type_filter: null,
                filtered: [],
                allProductsSelected: false,
            }
        },
        methods: {
            allselected() {
                this.$emit("selectAllR", new Date());

            },
            getData(url) {
                let satatus = [];
                const urlParams = new URLSearchParams(window.location.search);
                let campaign_id = urlParams.get('campaign_id');
                if (this.activeOnly) {

                    url = url ? url + "&status=ACTIVE" : "/campaigns/fi/my-products?status=ACTIVE";
                } else {
                    if (campaign_id) {

                        url = url ? url + "&status=ACTIVE&campaign_id=" + campaign_id + "&current_prods=" + this.getCampaignSelectedProductIDS + "" : "/campaigns/fi/my-products?status=ACTIVE&campaign_id=" + campaign_id + "&current_prods=" + this.getCampaignSelectedProductIDS + "";
                    } else {

                        url = url ? url : "/campaigns/fi/my-products";
                    }

                }

                // url+='&per_page='+this.per_page;
                let this_ = this;
                axios.get(url)
                    .then(response => {
                        let table_data = [];
                        let selectedIds = [];
                        let selectedProds = [];
                        if (campaign_id) {
                            console.log(response?.data?.active_products?.data, "response?.data?.active_products?.data");
                            this_.data = response?.data?.active_products;

                            Object.values(response?.data?.active_products?.data).forEach((item) => {
                                let pds_file = '/uploads/pds/' + item.pds;
                                if (item.status === "ACTIVE" || response?.data?.thiscamp_products?.includes(item.id)) {

                                    selectedIds.push(item?.id);
                                    selectedProds.push({ product_id: item?.id });

                                    table_data.push([
                                        item.id,
                                        this.capitalize(item.custom_product_name),
                                        this.capitalize(item.product_type.description),
                                        item.term_length + " " + item.term_length_type.charAt(0).toUpperCase() + item.term_length_type.slice(1).toLowerCase(),
                                        item.product_type.description == "Notice deposit" && item.lockout_period > 0 ? item.lockout_period + " Days " : '-',
                                        item.product_type.description == "Cashable" && item.lockout_period > 0 ? item.lockout_period + " Days" : '-',
                                        this.capitalize(item.status),
                                        item.pds ? () => '<a href="' + pds_file + '" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" style="cursor: pointer">\n' +
                                            '  <path fill-rule="evenodd" clip-rule="evenodd" d="M3.33301 3.33341C3.33301 2.89139 3.5086 2.46746 3.82116 2.1549C4.13372 1.84234 4.55765 1.66675 4.99967 1.66675H14.9997C15.4417 1.66675 15.8656 1.84234 16.1782 2.1549C16.4907 2.46746 16.6663 2.89139 16.6663 3.33341V16.6667C16.6663 17.1088 16.4907 17.5327 16.1782 17.8453C15.8656 18.1578 15.4417 18.3334 14.9997 18.3334H4.99967C4.55765 18.3334 4.13372 18.1578 3.82116 17.8453C3.5086 17.5327 3.33301 17.1088 3.33301 16.6667V3.33341ZM14.9997 3.33341H4.99967V16.6667H14.9997V3.33341ZM6.66634 7.50008C6.66634 7.27907 6.75414 7.06711 6.91042 6.91083C7.0667 6.75455 7.27866 6.66675 7.49967 6.66675H12.4997C12.7207 6.66675 12.9326 6.75455 13.0889 6.91083C13.2452 7.06711 13.333 7.27907 13.333 7.50008C13.333 7.7211 13.2452 7.93306 13.0889 8.08934C12.9326 8.24562 12.7207 8.33341 12.4997 8.33341H7.49967C7.27866 8.33341 7.0667 8.24562 6.91042 8.08934C6.75414 7.93306 6.66634 7.7211 6.66634 7.50008ZM7.49967 10.8334C7.27866 10.8334 7.0667 10.9212 6.91042 11.0775C6.75414 11.2338 6.66634 11.4457 6.66634 11.6667C6.66634 11.8878 6.75414 12.0997 6.91042 12.256C7.0667 12.4123 7.27866 12.5001 7.49967 12.5001H9.99967C10.2207 12.5001 10.4326 12.4123 10.5889 12.256C10.7452 12.0997 10.833 11.8878 10.833 11.6667C10.833 11.4457 10.7452 11.2338 10.5889 11.0775C10.4326 10.9212 10.2207 10.8334 9.99967 10.8334H7.49967Z" fill="#5063F4"/>\n' +
                                            '</svg></a>' : '-'
                                    ]);
                                }

                            });

                            console.log(table_data, "table_data");

                        } else {
                            this_.data = response?.data;

                            Object.values(response?.data?.data).forEach((item) => {
                                let pds_file = '/uploads/pds/' + item.pds;
                                selectedIds.push(item?.id);
                                selectedProds.push({ product_id: item?.id });
                                table_data.push([
                                    item.id,
                                    this.capitalize(item.custom_product_name),
                                    this.capitalize(item.product_type.description),
                                    item.term_length + " " + item.term_length_type.charAt(0).toUpperCase() + item.term_length_type.slice(1).toLowerCase(),
                                    item.product_type.description == "Notice deposit" && item.lockout_period > 0 ? item.lockout_period + " Days " : '-',
                                    item.product_type.description == "Cashable" && item.lockout_period > 0 ? item.lockout_period + " Days" : '-',
                                    this.capitalize(item.status),
                                    item.pds ? () => '<a href="' + pds_file + '" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" style="cursor: pointer">\n' +
                                        '  <path fill-rule="evenodd" clip-rule="evenodd" d="M3.33301 3.33341C3.33301 2.89139 3.5086 2.46746 3.82116 2.1549C4.13372 1.84234 4.55765 1.66675 4.99967 1.66675H14.9997C15.4417 1.66675 15.8656 1.84234 16.1782 2.1549C16.4907 2.46746 16.6663 2.89139 16.6663 3.33341V16.6667C16.6663 17.1088 16.4907 17.5327 16.1782 17.8453C15.8656 18.1578 15.4417 18.3334 14.9997 18.3334H4.99967C4.55765 18.3334 4.13372 18.1578 3.82116 17.8453C3.5086 17.5327 3.33301 17.1088 3.33301 16.6667V3.33341ZM14.9997 3.33341H4.99967V16.6667H14.9997V3.33341ZM6.66634 7.50008C6.66634 7.27907 6.75414 7.06711 6.91042 6.91083C7.0667 6.75455 7.27866 6.66675 7.49967 6.66675H12.4997C12.7207 6.66675 12.9326 6.75455 13.0889 6.91083C13.2452 7.06711 13.333 7.27907 13.333 7.50008C13.333 7.7211 13.2452 7.93306 13.0889 8.08934C12.9326 8.24562 12.7207 8.33341 12.4997 8.33341H7.49967C7.27866 8.33341 7.0667 8.24562 6.91042 8.08934C6.75414 7.93306 6.66634 7.7211 6.66634 7.50008ZM7.49967 10.8334C7.27866 10.8334 7.0667 10.9212 6.91042 11.0775C6.75414 11.2338 6.66634 11.4457 6.66634 11.6667C6.66634 11.8878 6.75414 12.0997 6.91042 12.256C7.0667 12.4123 7.27866 12.5001 7.49967 12.5001H9.99967C10.2207 12.5001 10.4326 12.4123 10.5889 12.256C10.7452 12.0997 10.833 11.8878 10.833 11.6667C10.833 11.4457 10.7452 11.2338 10.5889 11.0775C10.4326 10.9212 10.2207 10.8334 9.99967 10.8334H7.49967Z" fill="#5063F4"/>\n' +
                                        '</svg></a>' : '-'
                                ]);
                            });

                        }

                        if (this.getSelectAllProducts) {

                            //   this.$store.commit('campaign/' + types.UPDATE_CAMPAIGN_SELECTED_PRODUCTS_IDS_MANUAL, selectedIds, true);
                            //  this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCTS_MANUAL, selectedProds, true);

                        }
                        this.$store.commit('campaign/' + types.SET_ACTIVE_PRODUCT_LIST, selectedIds);

                        this_.table_data = table_data;
                    }).catch(error => {
                        console.log("error > " + error);
                        this_.data = null;
                        this_.table_data = null
                    });
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
            search(value) {
                let url = "";
                if (this.activeOnly) {
                    url = `${url}/campaigns/fi/my-products?search=${value}&status=ACTIVE`;
                } else {
                    url = `${url}/campaigns/fi/my-products?search=${value}`;
                }
                this.getData(url);
            },
            submitFilters(value) {
                let url = "";
                if (this.activeOnly) {
                    url = `${url}/campaigns/fi/my-products?search=${value}&status=ACTIVE`;
                } else {
                    url = `${url}/campaigns/fi/my-products?${value}`;
                }
                this.getData(url);
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
            deletionDone() {

            }
        },
        watch: {
            getAllActiveProducts(newval) {


                const allInB = newval.every(item => this.getCampaignSelectedProductIDS.includes(item));
                if (allInB) {
                    this.$store.commit('campaign/' + types.SET_ALL_PRODUCTS_SELECTED, allInB);
                } else {
                    this.$store.commit('campaign/' + types.SET_ALL_PRODUCTS_SELECTED, false);
                }

            },
            getCampaignSelectedProductIDS(newval) {


                const allInB = this.getAllActiveProducts.every(item => newval.includes(item));
                if (allInB) {
                    this.$store.commit('campaign/' + types.SET_ALL_PRODUCTS_SELECTED, allInB);
                } else {
                    this.$store.commit('campaign/' + types.SET_ALL_PRODUCTS_SELECTED, false);
                }

            },
            sync(newVal, oldVal) {
                this.getData();
            }
        }
    }
</script>