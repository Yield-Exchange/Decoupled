<template>
    <div style="width: 100%; display:flex; flex-direction:column;">

        <div style="margin-top: 15px; width:100%">

            <div
                style="flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; display: flex; width: 90%;">
                <table class="table" style="background: transparent; width:100%;">
                    <thead class="customHeader1" style="background: transparent;border-collapse: collapse;">
                        <tr style="border: none!important;">
                            <th>Campaign Type</th>
                            <th>Campaign Name</th>
                            <th>Currency</th>
                            <th>Subscription Limit</th>
                            <th>Start Date</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody class="customBody1" style="background: transparent;border-collapse: collapse;">
                        <tr style="border: none!important;">
                            <td>Ungrouped Depositors</td>
                            <td>{{ capitalize(camp?.campaign_name) }}</td>
                            <td>{{ camp?.currency }}</td>
                            <td>{{ addCommas(camp?.subscription_amount) }}</td>
                            <td>{{ start_date_date }} | {{ start_date_time }}</td>
                            <td>{{ expiry_date_date }} | {{ expiry_date_time }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div
            style="width:30%; margin-left:auto; display:flex; flex-direction:row; justify-content:flex-end; margin-top:15px;">
            <div
                style="width:100%; min-width:400px; display: flex; flex-direction: row; justify-content:space-between; ">
                <div style="width:100px; ">
                    <FilterBox :filtered="filtered" @apply_filters="getFilters" @clear_filters="clearFilters"
                        :provinces="provinces" :industries="industries" :groupings="groupings">
                    </FilterBox>
                </div>

                <div style="width:100px;">
                    <div style=" display: flex; flex-direction: column; align-items: end; justify-content: flex-end; ">
                        <b-form-input type="text" :placeholder="'Search'" :class="'font-13 input-height '"
                            :id="'search-input'" :aria-describedby="'input-live-help input-date_of_deposit-feedback'"
                            :style="'border-radius: 10px;outline:none; box-shadow: none;width:200px; margin-bottom:20px;'"
                            v-model="organizationSearchKeyWord" v-on:keyup="searchOrganization" />
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%">

            <Table v-if="hideAllActions" :columns="columns" no-data-title="No Active Depositors"
                no-data-message="No Active Depositors" :data="table_data" :has_action='false' :selectable="selectable"
                :selected_items="selected_items" :allselectable="(allselectablee) ? true : false"
                @selectedItems="getSelectedItems($event)" :select_all="select_all" />
            <Table v-else @reloadData="getData()" :columns="columns" no-data-title="No Active Depositors"
                no-data-message="No Active Depositors" :data="table_data" :has_action='true' :actions='actions'
                @productDeletedAddNew="$emit('productDeletedAddNew')" :selectable="selectable"
                :selected_items="selected_items" :allselectable="(allselectablee) ? true : false"
                :select_all="select_all" />
        </div>
        <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
    </div>
</template>

<style scoped>
    .filter-button {
        cursor: pointer;
        text-align: center;
        color: #9291A5;
        font-size: 11px;
        font-family: Montserrat;
        padding: 10px;
        font-weight: 400;
        line-height: 14px;
        word-wrap: break-word;
        margin-left: 30px;
        color: var(--neutral-colors-400, #9291A5);
        text-align: center;

        /* Yield Exchange Text Styles/Tooltips */
        font-family: Montserrat;
        font-size: 11px;
        font-style: normal;
        font-weight: 400;
        line-height: 14px;
        /* 127.273% */

    }

    .no-data {
        color: var(--yield-exchange-pallette-yield-exchange-blue, var(--yield-exchange-colors-yield-exchange-purple, #5063F4));
        text-align: center;
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .province_clicks {
        color: var(--yield-exchange-pallette-yield-exchange-grey, #9CA1AA);
        font-feature-settings: 'clig' off, 'liga' off;

        /* Yield Exchange Text Styles/Promotion Chart titles */
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 26px;
        /* 162.5% */
        text-transform: capitalize;
    }

    .province_clicks>span {
        color: #5063F4
    }

    .filter-button-active {
        background: var(--yield-exchange-colors-yield-exchange-purple, #5063F4);
        color: white;
        border-radius: 10px;
        padding-left: 15px;


    }

    .activeInsightBar {
        background: #44E0AA;
    }

    .inactiveInsightBar {
        background: #9CA1AA;
    }

    .table-vue-custom {
        background: white;
    }

    thead.customHeader1 {
        background: none !important;
        height: 51px;
    }

    thead.customHeader {
        background: #EFF2FE !important;
        height: 51px;
    }

    thead.customHeader tr {
        border-bottom: 0 solid #b3b2b2 !important;
    }

    thead.customHeader tr th {
        color: black;
        font-size: 13px !important;
        font-weight: 700;
        background: inherit !important;
        text-align: left !important;
    }

    thead.customBody tr td {
        /*text-align: center!important;*/
    }

    tbody.customBody1>tr>td {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #5063F4;
        font-size: 14px !important;
        font-weight: 800 !important;
        word-wrap: break-word;
    }

    thead.customHeader1>tr>th {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #252525;
        font-size: 16px;
        font-weight: 700;
        word-wrap: break-word;
    }

    tbody tr td {
        font-size: 13px !important;
    }

    .term_length_custom>.form-control {
        max-width: 70px !important;
    }
</style>
<script>
    import Table from "../../shared/Table";
    import Pagination from "../../shared/Table/Pagination";
    import FilterBox from "../../shared/GroupFilterBox";
    import CustomSelect from "../../shared/CustomSelect";

    import EditProduct from "./EditProduct";
    import DeleteProduct from "./DeleteProduct";
    import ViewProduct from "./ViewProduct";

    export default {
        mounted() {

        },
        components: {
            Table,
            Pagination,
            FilterBox,
            CustomSelect,
            EditProduct,
            ViewProduct
        },
        created() {
            this.refreshDisplayableDepositors();
            this.loadAllProvinces();
            this.loadAllIndustries();
        },
        props: ['depositors', 'selectable', 'selected_items', 'sync', 'campData', 'hideView', 'allselectablee', 'hideAllActions', 'select_all'],
        data() {
            console.log(this.campData, "this.campDatathis.campData")
            let act = [];
            let columnss = [];
            if (this.hideAllActions) {
                this.columnss = ['Depositor', 'Province', 'Industry'];
            } else {
                this.columnss = ['Depositor', 'Province', 'Industry'];

                if (!this.hideView) {
                    act.push({
                        name: "View",
                        component: ViewProduct,
                    })
                }
            }
            let depos = [];


            let start_d = this.campData?.start_date.split(" ");
            let expiry_d = this.campData?.expiry_date.split(" ");

            return {
                start_date_date: start_d[0],
                start_date_time: this.formatTime(start_d[1]),
                expiry_date_date: expiry_d[0],
                expiry_date_time: this.formatTime(expiry_d[1]),
                camp: this.campData,
                organizationfilterstr: '',
                organizationSearchKeyWord: '',
                groupings: ['Grouped Organizations', 'Ungrouped Organizations', 'All'],
                industries: [],
                provinces: [],
                actions: act,
                data: null,
                table_data: null,
                columns: this.columnss,
                per_page: 5,
                term_length_filter: null,
                product_type_filter: null,
                filtered: [],
                allProductsSelected: false,
                selectedOrgs: []
            }
        },
        methods: {
            selectAllGroups() {

                this.select_all = !this.select_all;
            },
            formatTime(timeString) {
                const [hourString, minute] = timeString.split(":");
                const hour = +hourString % 24;
                return (hour % 12 || 12) + ":" + minute + (hour < 12 ? " AM" : " PM");
            },
            addCommas(newvalue) {
                if (newvalue != undefined) {
                    return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

            },
            getFilters(data) {

                this.organizationfilterstr = data;
                this.refreshDisplayableDepositors();
            },
            refreshDisplayableDepositors(groups) {
                axios.post(`/campaigns/fi/get-group-unlinked-depositors?search=${this.organizationSearchKeyWord}&${this.organizationfilterstr}`, {
                    groups: this.groups_selected,
                }).then(response => {
                    console.log(response?.data, " response?.data response?.data");
                    let depositors = [];
                    response?.data.forEach((element, index) => {

                        depositors.push([element?.id, element?.name, element?.demographic_data?.province, element?.industry?.name]);
                    });
                    this.table_data = depositors;
                    let count = this.table_data.length;

                    this.$emit("listOfAllDepostors", count);


                }).catch(error => {

                });

            },
            clearFilters() {
                this.organizationfilterstr = '';
                this.refreshDisplayableDepositors();
            },
            allselected() {
                this.$emit("selectAllR", new Date());
            },
            getData(url) {

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
                    //         console.log(response?.data, "all industries");
                    this.industries = response?.data;

                }).catch(error => {

                });

            },
            searchOrganization() {
                this.refreshDisplayableDepositors();
            },
            getSelectedItems(selectedorgs) {

                this.selectedOrgs = selectedorgs;
                this.$emit('selectedOrganizations', this.selectedOrgs);

            }
        },
        watch: {
            sync(newVal, oldVal) {
                this.getData();
            }
        }
    }
</script>