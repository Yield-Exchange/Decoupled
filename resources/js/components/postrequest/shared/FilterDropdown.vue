<template>
    <!-- Example single danger button -->
    <div class="btn-group">
        <button type="button" @click="toggleDropdown" class="dropdown-button" :class="{ hasFilters: !startFilter }"
            data-toggle="" aria-haspopup="true" aria-expanded="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M6 12H18M3 6H21M9 18H15" v-if="startFilter" stroke="black" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 12H18M3 6H21M9 18H15" v-else stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg> Filters
        </button>
        <div class="dropdown-menu" ref="myScrollDown" :class="{ 'd-flex': show_dropdown }">
            <div class="w-100 position-sticky bg-white top-0 pt-3 pb-2" style="background-color: white;z-index: 1;">
                <div class="d-flex justify-content-between w-100 px-3 align-items-center"
                    style="position: sticky; top:10px">
                    <button @click="submitFilter" class="apply-filters bg-white" :disabled="startFilter"> Apply
                        Filters</button>
                    <a @click="clearFilter" class="clear-filter"> Clear Filters</a>
                </div>
            </div>
            <template v-if="showFilters">
                <!-- {{ filtered_item }} -->
                <FilteredItem ref="filteredItemRefs" v-for="(item, index) in filtered_item " :key="index" :value="item"
                    :termLengthType="termLengthType" :title="index" @removeSingleItem="removeIndividualFilter">
                </FilteredItem>
            </template>

            <!-- number of clicks -->
            <CustomDropdownToggle v-if="filterType == 'campaign'" title="Number of Clicks" toggleFor="numberofclicks"
                target="acc-no-clicks" @toggleCarret="toggleCaret" :carretState="numberofclicks">
                <!-- <div class="collapse collapse-rate mx-4" id="acc-no-clicks"> -->
                <div class="d-flex justify-content-between w-100 gap-3">
                    <CustomFilterinput type="number" v-model="min_clicks" :max="max_clicks" placeholder="1"
                        label="Min Clicks">
                    </CustomFilterinput>
                    <CustomFilterinput type="number" v-model="max_clicks" :min="min_clicks" placeholder="10"
                        label="Max Clicks">
                    </CustomFilterinput>
                </div>
            </CustomDropdownToggle>
            <!-- </div> -->
            <!-- Subscription Limit -->
            <CustomDropdownToggle title="Offered Amount" toggleFor="subscriptioncaret" target="acc-sub-limit"
                @toggleCarret="toggleCaret" :carretState="subscriptioncaret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <CustomFilterinput type="number" v-model="min_deposit_amount" :max="max_deposit_amount"
                        placeholder="1,000,000" label="Min ">
                    </CustomFilterinput>
                    <CustomFilterinput type="number" v-model="max_deposit_amount" :min="min_deposit_amount"
                        placeholder="2,0000,000" label="Max">
                    </CustomFilterinput>
                </div>
            </CustomDropdownToggle>

            <!-- Rate Limit -->
            <CustomDropdownToggle v-if="!isfromrequest && !noRate" title="Rate (%)" toggleFor="ratecaret"
                target="acc-rate" @toggleCarret="toggleCaret" :carretState="ratecaret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <CustomFilterinput type="number" v-model="min_rate" :max="max_rate" placeholder="10"
                        label="Min Rate">
                    </CustomFilterinput>
                    <CustomFilterinput type="number" v-model="max_rate" :min="min_rate" placeholder="20"
                        label="Max Rate">
                    </CustomFilterinput>
                </div>
            </CustomDropdownToggle>
            <!-- Lockout  -->
            <CustomDropdownToggle
                v-if="filterType == 'products' || filterType == 'all-products' || filterType == 'featured-products'"
                title="Lock-Out / Notice Period(Days)" toggleFor="lockoutcaret" target="acc-loclout-period"
                @toggleCarret="toggleCaret" :carretState="lockoutcaret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <CustomFilterinput type="number" v-model="min_lockperiod" :max="max_lockperiod" placeholder="10"
                        label="Min">
                    </CustomFilterinput>
                    <CustomFilterinput type="number" v-model="max_lockperiod" :min="min_lockperiod" placeholder="20"
                        label="Max">
                    </CustomFilterinput>
                </div>
            </CustomDropdownToggle>
            <!-- term length -->
            <CustomDropdownToggle title="Term Length" toggleFor="termlengthcaret" target="acc-term-length"
                @toggleCarret="toggleCaret" :carretState="termlengthcaret">
                <div class="d-flex justify-content-between w-100 gap-3">

                    <template>
                        <div>
                            <!-- {{  }} -->
                            <p
                                style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                Min Term</p>
                            <div class="combined-input">
                                <b-form-select class="" id="termlengthid" v-model="selectedTermLengthType"
                                    ref="termLengthSelect" @change="" :options="['months', 'days']"
                                    style="border: none;width:75%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                </b-form-select>
                                <b-form-input
                                    style="border: none; ;width:25%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                    type="text" v-model="min_term" @blur="" :class="{ 'validation-error': false }"
                                    placeholder="eg. 3" />
                            </div>
                            <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                        </div>
                    </template>
                    <!-- <CustomFilterinput type="number" v-model="min_term" placeholder="1 Months" label="Min Term">
                    </CustomFilterinput> -->

                    <template>
                        <div>
                            <!-- {{  }} -->
                            <p
                                style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                Max Term</p>
                            <div class="combined-input">
                                <b-form-select class="" id="termlengthid" v-model="selectedTermLengthType"
                                    ref="termLengthSelect" @change="" :options="['months', 'days']"
                                    style="border: none;width:75%;margin-left:15px;outline:none; box-shadow: none;text-transform: capitalize;">
                                </b-form-select>
                                <b-form-input
                                    style="border: none; ;width:25%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                    type="text" v-model="max_term" @blur="" :class="{ 'validation-error': false }"
                                    placeholder="eg. 3" />
                            </div>
                            <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                        </div>
                    </template>
                </div>
            </CustomDropdownToggle>


            <!--  -->
            <!-- products -->
            <CustomDropdownToggle title="Product Type" toggleFor="productscaret" target="acc-products"
                @toggleCarret="toggleCaret" :carretState="productscaret">

                <div class="d-flex flex-column">
                    <div class="form-check" v-for="(product, index) in products" :key="index">
                        <input class="form-check-input-6" type="checkbox" name="province" v-model="productType"
                            :value="product.description" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            {{ product.description }}
                        </label>
                    </div>
                </div>
            </CustomDropdownToggle>
            <!-- industry products -->
            <!-- products -->
            <!-- Financial Institution -->

            <CustomDropdownToggle v-if="filterType == 'depositor' && !isfromrequest" title="Financial Organizations"
                toggleFor="fiorganizationcaret" target="acc-fiorganization" @toggleCarret="toggleCaret"
                :carretState="fiorganizationcaret">
                <div class="">
                    <multiselect v-model="fiorganization" :options="options" :multiple="true" :taggable="true">
                    </multiselect>
                </div>
            </CustomDropdownToggle>
            <CustomDropdownToggle v-if="isfromrequest" title="Status" toggleFor="statuscarret" target="accstatuscarret"
                @toggleCarret="toggleCaret" :carretState="statuscarret">
                <div class="">
                    <multiselect v-model="status" :options="statusOption" :multiple="true" :taggable="true">
                    </multiselect>
                </div>
            </CustomDropdownToggle>
        </div>
    </div>
</template>
<script>
    import { emit } from 'process';
    import CustomFilterinput from './CustomFilterinput.vue';
    import Multiselect from 'vue-multiselect'
    import CustomDropdownToggle from './CustomDropdownToggle.vue';
    import FilteredItem from './FilteredItem.vue';

    // filterType

    // we have the fillowing filter types that we will work with
    // campaign ,products
    export default {
        props: ['provinces', 'isfromrequest', 'showFilters', 'termLengthType', 'products', 'noRate', 'noclicks', 'industries', 'filterType', 'fiorganizations', 'itemToRemove', 'filtered_item'],
        components: {
            CustomFilterinput,
            Multiselect,
            CustomDropdownToggle,
            FilteredItem
        },
        created() {
            if (this.fiorganizations) {
                this.fiorganizations.forEach(element => {
                    this.options.push(element.name)
                    // console.log(this.options)
                    this.show_multiselect = true
                });

            }
        },
        data() {

            let statuses = [
                'Expired', 'Withdrawn', 'No Offers Received'
            ]
            return {
                show_multiselect: false,
                options: [],
                fiorganization: [],
                selectedProvinces: [],
                show_dropdown: false,
                rate: '',
                filtered_data: {},
                min_term: '',
                max_term: '',
                min_clicks: '',
                max_clicks: '',
                min_deposit_amount: '',
                max_deposit_amount: '',
                min_expiry: '',
                max_expiry: '',
                min_date: '',
                max_date: '',
                min_rate: '',
                max_rate: '',
                min_lockperiod: '',
                max_lockperiod: '',
                productType: [],
                startFilter: true,
                selectedTermLengthType: "months",
                selectedIndustries: [],
                status: [],
                statusOption: statuses,

                // dropdownclicks
                statuscarret: false,
                numberofclicks: false,
                subscriptioncaret: false,
                ratecaret: false,
                lockoutcaret: false,
                termlengthcaret: false,
                expirycaret: false,
                productscaret: false,
                industrycaret: false,
                fiorganizationcaret: false,
            }
        },
        computed: {
            myindustries() {
                if (this.industries != '')
                    return this.industries
            }
        },


        methods: {
            checkFilters() {
                if ((this.max_term == '' && this.min_term == '') && (this.min_clicks == '' && this.max_clicks == '')
                    && (this.min_deposit_amount == '' && this.max_deposit_amount == '') && (this.min_expiry == '' && this.max_expiry == '')
                    && (this.min_rate == '' && this.max_rate == '') && (this.min_lockperiod == '' && this.max_lockperiod == '') && this.productType.length == 0 && this.fiorganization == 0 && this.status.length == 0) {
                    this.startFilter = true
                } else {
                    this.startFilter = false
                }
            },
            toggleDropdown() {
                this.show_dropdown = !this.show_dropdown
            },
            scrollDown() {
                const element = this.$refs.myScrollDown;
                // Setting the scrollTop property to 0
                if (element) {
                    element.scrollTop = 0;
                }
            },
            toggleCaret(caretType) {
                if (caretType === 'numberofclicks') {
                    this.numberofclicks = !this.numberofclicks;
                }
                if (caretType === 'subscriptioncaret') {
                    this.subscriptioncaret = !this.subscriptioncaret;
                }
                if (caretType === 'ratecaret') {
                    this.ratecaret = !this.ratecaret;
                }
                if (caretType === 'lockoutcaret') {
                    this.lockoutcaret = !this.lockoutcaret;
                }
                if (caretType === 'termlengthcaret') {
                    this.termlengthcaret = !this.termlengthcaret;
                }
                if (caretType === 'expirycaret') {
                    this.expirycaret = !this.expirycaret;
                }
                if (caretType === 'productscaret') {
                    this.productscaret = !this.productscaret;
                }
                if (caretType === 'statuscarret') {
                    this.statuscarret = !this.statuscarret;
                }
                if (caretType === 'fiorganizationcaret') {
                    this.fiorganizationcaret = !this.fiorganizationcaret;
                }
            },

            clearFilter() {
                this.filtered_data = {},
                    this.min_term = '',
                    this.max_term = '',
                    this.min_clicks = '',
                    this.max_clicks = '',
                    this.min_deposit_amount = '',
                    this.max_deposit_amount = '',
                    this.min_expiry = '',
                    this.max_expiry = '',
                    this.min_date = '',
                    this.max_date = '',
                    this.min_rate = '',
                    this.max_rate = '',
                    this.min_lockperiod = '',
                    this.max_lockperiod = '',
                    this.fiorganization = [],
                    this.productType = [],
                    this.status = [],
                    this.startFilter = true,
                    this.$emit('clear_filters')
            },
            removeIndividualFilter(filterItem) {
                switch (filterItem.toLowerCase()) {
                    case 'termlength':
                        this.min_term = ''
                        this.max_term = ''
                        break;
                    case 'clicks':
                        this.min_clicks = ''
                        this.max_clicks = ''
                        break;
                    case 'offer':
                        this.min_deposit_amount = ''
                        this.max_deposit_amount = ''
                        break;
                    case 'lockoutperiod':
                        this.min_lockperiod = ''
                        this.max_lockperiod = ''
                        break;
                    case 'products':
                        this.productType = []
                        break;
                    case 'rate':
                        this.min_rate = ''
                        this.max_rate = ''
                        break;
                    case 'financialorganizations':
                        this.fiorganization = []
                        break;
                    case 'expiry':
                        this.min_expiry = ''
                        this.max_expiry = ''
                        this.min_date = ''
                        this.max_date = ''
                        break;
                    case 'status':
                        this.status = []
                        break;
                    default:
                        // console.log("No Matching case")
                        break
                }
                if (!this.hasFilters) {
                    this.submitFilter()
                } else {
                    this.$emit('clear_filters')

                }
            },
            submitFilter() {
                if (this.filterType == "depositor") {

                    this.filtered_data = `rate=${this.min_rate ? this.min_rate : 0},${this.max_rate ? this.max_rate : 0}&offer=${this.min_deposit_amount ? this.min_deposit_amount : 0},${this.max_deposit_amount ? this.max_deposit_amount : 0}&termLengthType=${this.selectedTermLengthType}&termLength=${this.min_term ? this.min_term : 0},${this.max_term ? this.max_term : 0}`
                    if (this.productType.length > 0)
                        this.filtered_data += `&products=${this.productType.join(',')}`
                    if (this.fiorganization.length > 0)
                        this.filtered_data += `&financialOrganizations=${this.fiorganization.join(',')}`
                    if (this.status.length > 0)
                        this.filtered_data += `&status=${this.status.join(',')}`
                    this.$emit('apply_filters', this.filtered_data)
                    // console.log(this.filtered_data)
                } else if (this.filterType == "bank") {

                    this.filtered_data = `rate=${this.min_rate ? this.min_rate : 0},${this.max_rate ? this.max_rate : 0}&offer=${this.min_deposit_amount ? this.min_deposit_amount : 0},${this.max_deposit_amount ? this.max_deposit_amount : 0}&termLengthType=${this.selectedTermLengthType}&termLength=${this.min_term ? this.min_term : 0},${this.max_term ? this.max_term : 0}`
                    if (this.productType.length > 0)
                        this.filtered_data += `&products=${this.productType.join(',')}`
                    if (this.status.length > 0)
                        this.filtered_data += `&status=${this.status.join(',')}`
                    this.$emit('apply_filters', this.filtered_data)
                    // console.log(this.filtered_data)
                }
            },
        },
        watch: {
            min_term(newValue, oldValue) {

                this.checkFilters()
            },
            max_term(newValue, oldValue) {
                this.checkFilters()
            },
            min_clicks(newValue, oldValue) {
                this.checkFilters()
            },
            max_clicks(newValue, oldValue) {
                this.checkFilters()
            },
            min_deposit_amount(newValue, oldValue) {
                this.checkFilters()
            },
            max_deposit_amount(newValue, oldValue) {
                this.checkFilters()
            },
            min_rate(newValue, oldValue) {
                this.checkFilters()
            },
            max_rate(newValue, oldValue) {
                this.checkFilters()
            },
            min_lockperiod(newValue, oldValue) {
                this.checkFilters()
            },
            max_lockperiod(newValue, oldValue) {
                this.checkFilters()
            },
            productType() {
                this.checkFilters()
            },
            fiorganization() {
                this.checkFilters()
                // console.log(this.fiorganization, "Financial Organizations")
            },
            status() {
                this.checkFilters()
                // console.log(this.status, "Financial Organizations")
            },
            min_expiry() {
                this.min_date = this.min_expiry
                this.checkFilters()

            },
            max_expiry() {
                this.max_date = this.max_expiry
                this.checkFilters()
            },
            options(newValue, oldValue) {
                this.options = newValue
            },
            showFilters(newValue, oldValue) {
                if (newValue) {
                    this.scrollDown()
                }
            }
        },
    }
</script>


<style>
    .multiselect__content-wrapper {
        position: relative;
    }

    .multiselect {
        width: 90%;
    }

    .multiselect__tag {
        color: black;
        background-color: #EFF2FE;
    }

    .multiselect__tags {
        overflow-y: scroll;
        border-radius: 10px;
        border: 1px solid var(--Gray-5, #E0E0E0);
        background: var(--White, #FFF);
        /* overflow-x: break; */
        word-wrap: break-word;


        /* overflow-wrap: normal; */
    }

    .multiselect__select {
        display: none;
    }

    .combined-input {
        padding-top: 7px;
        padding-bottom: 7px;
        display: flex;
        height: 40px;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-radius: 999px;
        border: 0.5px solid #ccc;
        font-size: 16px;
    }

    .display_none {
        display: none;
    }

    .dropdown-button {
        display: flex;
        padding: 8px 16px;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        border-radius: 8px;
        border: 1px solid #E0E0E0;
        /* background: #5063F4; */
        color: black;
        text-align: center;
        justify-content: center;
        background-color: white;
        /* Yield Exchange Text Styles/Table Body */
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .hasFilters {
        border: 1px solid #E0E0E0 !important;
        background: #5063F4 !important;
        color: white !important;
    }

    .clear-filter {
        color: var(--yield-exchange-pallette-yield-exchange-grey, #9CA1AA);
        font-feature-settings: 'clig' off, 'liga' off;
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 16px;
        /* 114.286% */
        text-decoration-line: underline;
        text-transform: capitalize;
        cursor: pointer;

    }

    .apply-filters {
        color: var(--yield-exchange-pallette-yield-exchange-blue, var(--yield-exchange-colors-yield-exchange-purple, #5063F4));
        font-feature-settings: 'clig' off, 'liga' off;
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 16px;
        /* 114.286% */
        text-transform: capitalize;
        padding: 10px;
        align-items: center;
        border-radius: 10px;
        cursor: pointer;
        border: 1px solid var(--yield-exchange-pallette-yield-exchange-blue, #5063F4);
    }

    .collapse {
        color: var(--gray-700, #344054);
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        /* 21px */
    }

    .dropdown-menu {
        display: none;
        width: 410px !important;
        padding: 0px 10px;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        border-radius: 10px;
        border: 0.5px solid #D9D9D9 !important;
        background: white;
        box-shadow: 0px 4px 4px 0px #EFF2FE;
        max-height: 300px;
        overflow-y: auto;
    }



    .rounded-input {
        border-radius: 999px;
        border: 1px solid #D0D5DD;
        background: #FFF;
        box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
        padding: 10px 14px;
        color: var(--gray-500, #667085);
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        width: 140px;
        /* 24px */
    }

    .form-check-label {
        color: var(--yield-exchange-colors-darks, #252525);
        text-align: center;
        font-family: Montserrat;
        font-size: 15px;
        font-style: normal;
        font-weight: 500;
        /* line-height: 14px; */
        /* 93.333% */
        margin-left: 5px;
    }

    .form-check-input-6 {
        width: 16px;
        height: 16px;
    }
</style>
