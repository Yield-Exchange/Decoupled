<template>
    <!-- Example single danger button -->
    <div class="btn-group">

        <button type="button" @click="toggleDropdown" class="dropdown-button" :class="{ hasFilters: startFilter }"
            data-toggle="" aria-haspopup="true" aria-expanded="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M6 12H18M3 6H21M9 18H15" v-if="!startFilter" stroke="black" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 12H18M3 6H21M9 18H15" v-else stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg> Filters
        </button>
        <div class="dropdown-menu" ref="myScrollDown" :class="{ 'd-flex': show_dropdown }"
            style="padding-bottom: 50px;">
            <div class="w-100 position-sticky bg-white top-0 pt-3 pb-2" style="background-color: white;z-index: 1;">
                <div class="d-flex justify-content-between w-100 px-3 align-items-center"
                    style="position: sticky; top:10px">
                    <button @click="submitFilter" class="apply-filters bg-white" :disabled="!startFilter">
                        Apply Filters
                    </button>
                    <a @click="clearFilter" class="clear-filter"> Clear Filters</a>
                </div>
            </div>
            <div class="w-100 bg-white top-0 pt-3 pb-2" style="background-color: white;">
                <template v-if="showFilters">
                    <!-- {{ filtered_item }} -->
                    <FilteredItem ref="filteredItemRefs" v-for="(item, index) in filtered_item " :key="index"
                        :value="item" :termLengthType="termLengthType" :title="index"
                        @removeSingleItem="removeIndividualFilter">
                    </FilteredItem>
                </template>
                <!-- number of clicks -->
                <!-- Rate Limit -->
                <!-- review offers -->
                <div v-if="from==='reviewoffers'" style="width: 100%;">
                    <CustomDropdownToggle title="Closing Dates" toggleFor="closingDateCaret" target="acc-closing-date"
                        @toggleCarret="toggleCaret" :carretState="closingDateCaret">
                        <div class="d-flex justify-content-between w-100 gap-3">
                            <div class="d-flex flex-column">
                                <p
                                    style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                    Min Date</p>
                                <input class="rounded-input" type="date" v-model="min_closing_date"
                                    :max="max_closing_date">
                            </div>
                            <div class="d-flex flex-column">
                                <p
                                    style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                    Max Date</p>
                                <input class="rounded-input" type="date" v-model="max_closing_date"
                                    :min="min_closing_date">
                            </div>
                        </div>
                    </CustomDropdownToggle>
                    <!-- request amount -->
                    <CustomDropdownToggle title="Request Amount" toggleFor="requestAmountCaret"
                        target="acc-request-amount" @toggleCarret="toggleCaret" :carretState="requestAmountCaret">
                        <div class="d-flex justify-content-between w-100 gap-3">

                            <template>
                                <div>
                                    <!-- {{  }} -->
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Min Request Amount</p>
                                    <!-- custom input here -->
                                    <b-input class="rounded-input"
                                        @keyup="validateNumber($event,'numvalue','min_req_amount')"
                                        v-model="min_req_amount" placeholder="Enter Amount" @blur="" />
                                    <!-- <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%"
                                        id="min_req_amount" :name="min_req_amount" :has-validation="true"
                                        @inputChanged="min_req_amount=$event" defal v-model="min_req_amount"
                                        input-type="number" /> -->
                                    <!-- custom input here -->
                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>
                            <template>
                                <div>
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Max Request Amount </p>
                                    <!-- custom input here -->
                                    <!-- <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%"
                                        id="max_req_amount" :name="max_req_amount" :has-validation="true"
                                        @inputChanged="max_req_amount=$event" v-model="max_req_amount"
                                        input-type="number" /> -->
                                    <b-input class="rounded-input" placeholder="Enter Amount"
                                        @keyup="validateNumber($event,'numvalue','max_req_amount')"
                                        v-model="max_req_amount" @blur="" />
                                    <!-- custom input here -->

                                </div>
                            </template>
                        </div>
                    </CustomDropdownToggle>
                    <!-- request amount -->
                    <!-- term length -->
                    <CustomDropdownToggle title="Duration" toggleFor="durationCaret" target="acc-duration"
                        @toggleCarret="toggleCaret" :carretState="durationCaret">
                        <div class="d-flex justify-content-between w-100 gap-3">

                            <template>
                                <div>
                                    <!-- {{  }} -->
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Min Duration</p>
                                    <div class="combined-input">
                                        <b-form-select class="" id="termlengthid" v-model="selectedTermLengthType"
                                            ref="termLengthSelect" @change="" :options="['months', 'days']"
                                            style="border: none;width:75%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
                                        </b-form-select>
                                        <b-form-input
                                            style="border: none; ;width:25%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                            type="text" v-model="min_term" @blur=""
                                            :class="{ 'validation-error': false }" placeholder="eg. 3" />
                                    </div>
                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>

                            <template>
                                <div>
                                    <!-- {{  }} -->
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Max Duration </p>
                                    <div class="combined-input">
                                        <b-form-select class="" id="termlengthid" v-model="selectedTermLengthType"
                                            ref="termLengthSelect" @change="" :options="['months', 'days']"
                                            style="border: none;width:75%;margin-left:15px;outline:none; box-shadow: none;text-transform: capitalize;">
                                        </b-form-select>
                                        <b-form-input
                                            style="border: none; ;width:25%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                            type="text" v-model="max_term" @blur=""
                                            :class="{ 'validation-error': false }" placeholder="eg. 3" />
                                    </div>
                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>
                        </div>
                    </CustomDropdownToggle>

                    <!-- products -->
                    <CustomDropdownToggle
                        v-if="(filterType == 'products' || filterType == 'all-products' || filterType == 'featured-products') && from==='reviewoffers'"
                        title="Product Type" toggleFor="productscaret" target="acc-products" @toggleCarret="toggleCaret"
                        :carretState="productscaret">
                        <div class="d-flex flex-column">
                            <div class="form-check" v-for="(product, index) in products" :key="index">
                                <input class="form-check-input-6" type="checkbox" name="province" v-model="product_type"
                                    :value="product.description" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{ product.description }}
                                </label>
                            </div>
                        </div>
                    </CustomDropdownToggle>
                    <!-- industry products -->
                </div>
                <!-- review offers -->
                <!-- offers sum -->
                <div v-if="from==='offerssum'" style="width: 100%;">

                    <CustomDropdownToggle title="Rate" toggleFor="rateCaret" target="rate" @toggleCarret="toggleCaret"
                        :carretState="rateCaret">
                        <div class="d-flex justify-content-between w-100 gap-3">

                            <template>
                                <div>
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Min Rate</p>
                                    <b-input class="rounded-input" type="number" min="1" max="100"
                                        v-model="min_offer_rate" @keyup="validateNumber($event,'rate','min_offer_rate')"
                                        placeholder="Enter Rate" />
                                    <!-- <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%"
                                        id="min_offer_rate" defaultValue="min_offer_rate" :name="min_offer_rate"
                                        :has-validation="true" @inputChanged="min_offer_rate=$event" input-type="number" /> -->

                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>
                            <template>
                                <div>
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Max Rate </p>
                                    <!-- custom input here -->
                                    <!-- <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%"
                                        id="max_offer_rate" v-model="max_offer_rate" :name="max_offer_rate"
                                        :has-validation="true" @inputChanged="max_offer_rate=$event" input-type="number" /> -->
                                    <b-input class="rounded-input"
                                        @keyup="validateNumber($event,'rate','max_offer_rate')" type="number" min="1"
                                        max="100" v-model="max_offer_rate" @blur="" placeholder="Enter Rate" />
                                    <!-- custom input here -->
                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>
                        </div>
                    </CustomDropdownToggle>
                    <CustomDropdownToggle title="Offer Range" toggleFor="offerRangeCaret" target="acc-offfer-range"
                        @toggleCarret="toggleCaret" :carretState="offerRangeCaret">
                        <div class="d-flex justify-content-between w-100 gap-3">
                            <template>
                                <div>
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Min Offer Amount</p>
                                    <!-- custom input here -->
                                    <!-- <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%"
                                        id="min_offer_amount" :name="min_offer_amount" v-model="min_offer_amount"
                                        :has-validation="true" @inputChanged="min_offer_amount=$event"
                                        input-type="number" /> -->
                                    <b-input class="rounded-input"
                                        @keyup="validateNumber($event,'numvalue','min_offer_amount')"
                                        v-model="min_offer_amount" @blur="" placeholder="Enter Amount" />
                                    <!-- custom input here -->
                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>
                            <template>
                                <div>
                                    <p
                                        style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
                                        Max Offer Amount </p>
                                    <!-- custom input here -->
                                    <!-- <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%"
                                        id="max_offer_amount" :name="max_offer_amount" v-model="max_offer_amount"
                                        :has-validation="true" @inputChanged="max_offer_amount=$event"
                                        input-type="number" /> -->
                                    <b-input class="rounded-input"
                                        @keyup="validateNumber($event,'numvalue','max_offer_amount')"
                                        v-model="max_offer_amount" @blur="" placeholder="Enter Amount" />
                                    <!-- custom input here -->
                                    <span v-if="false" style="color:red;margin-left: 40px;">Invalid.</span>
                                </div>
                            </template>
                        </div>
                    </CustomDropdownToggle>
                    <CustomDropdownToggle title="Financial Organizations" toggleFor="fiorganizationcaret"
                        target="acc-fiorganization" @toggleCarret="toggleCaret" :carretState="fiorganizationcaret">
                        <div class="">
                            <multiselect v-model="fiorganization" :options="options" :multiple="true" :taggable="true">
                            </multiselect>
                        </div>
                    </CustomDropdownToggle>
                </div>
                <!-- offers sum -->
            </div>



        </div>
    </div>
</template>
<script>
    import { emit } from 'process';
    import CustomFilterinput from './CustomFilterinput.vue';
    import Multiselect from 'vue-multiselect'
    import CustomDropdownToggle from './CustomDropdownToggle.vue';
    import FilteredItem from './ReviewFilteredItem.vue';
    import CustomInput from "../../shared/CustomInput";

    import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, sanitizeAmount } from "../../../utils/commonUtils";
    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/postreq/mutation-types.js';

    // filterType

    // we have the fillowing filter types that we will work with
    // campaign ,products
    export default {
        props: ['provinces', 'showFilters', 'termLengthType', 'products', 'noclicks', 'industries', 'filterType', 'fiorganizations', 'itemToRemove', 'filtered_item', 'from'],
        components: {
            CustomInput,
            CustomFilterinput,
            Multiselect,
            CustomDropdownToggle,
            FilteredItem
        },
        created() {
            if (this.fiorganizations) {
                this.fiorganizations.forEach(element => {
                    this.options.push(element.name)
                    console.log(this.options)
                    this.show_multiselect = true
                });

            }
        },
        data() {
            return {
                debounceTimeout: null,
                show_multiselect: false,
                options: [],
                fiorganization: [],
                selectedProvinces: [],
                show_dropdown: false,
                rate: '',
                filtered_data: {},
                min_term: '',
                max_term: '',
                min_closing_date: '',
                max_closing_date: '',
                min_req_amount: '',
                max_req_amount: '',
                min_offer: '',
                max_offer: '',
                product_type: [],
                selectedTermLengthType: 'months',
                startFilter: false,
                closingDateCaret: false,
                productscaret: false,
                termlengthcaret: false,
                durationCaret: false,
                requestAmountCaret: false,
                fiorganizationcaret: false,
                rateCaret: false,
                offerRangeCaret: false,
                institutions: [],
                fiorganization: [],
                max_offer_rate: '',
                min_offer_rate: '',
                min_offer_amount: '',
                max_offer_amount: ''
            }
        },
        computed: {
            myindustries() {
                if (this.industries != '')
                    return this.industries
            }
        },


        methods: {
            validateNumber(newval, type, vmodel) {
                let value = sanitizeAmount(newval.target.value);
                if (isNaN(value)) {
                    this[vmodel] = '';
                    return;
                }
                if (this.debounceTimeout) {
                    clearTimeout(this.debounceTimeout);
                }
                this.debounceTimeout = setTimeout(() => {
                    switch (type) {
                        case 'rate':
                            if (value < 1 || value > 100) {
                                this[vmodel] = '';
                            } else {
                                this[vmodel] = addCommasToANumber(value);
                            }
                        case 'numvalue':
                            if (value < 1 || value > 99999999999) {
                                this[vmodel] = '';
                            } else {
                                this[vmodel] = addCommasToANumber(value);
                            }
                    }
                }, 300);


            },
            checkFilters() {
                if (this.min_offer_amount != '' || this.max_offer_amount != '' || this.max_offer_rate != '' || this.min_offer_rate != '' || this.max_term != '' || this.min_term != '' || this.max_closing_date != '' || this.min_closing_date != '' || this.max_req_amount != '' || this.min_req_amount != '' || this.product_type.length > 0 || this.fiorganization.length > 0) {
                    this.startFilter = true;
                } else {
                    this.startFilter = false;
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
                if (caretType === 'rateCaret') {
                    this.rateCaret = !this.rateCaret;
                } else if (caretType === 'offerRangeCaret') {
                    this.offerRangeCaret = !this.offerRangeCaret;
                } else if (caretType === 'termlengthcaret') {
                    this.termlengthcaret = !this.termlengthcaret;
                }
                else if (caretType === 'productscaret') {
                    this.productscaret = !this.productscaret;
                } else if (caretType === 'fiorganizationcaret') {
                    this.fiorganizationcaret = !this.fiorganizationcaret;
                }
                else if (caretType === 'requestAmountCaret') {
                    this.requestAmountCaret = !this.requestAmountCaret;
                } else if (caretType === 'durationCaret') {
                    this.durationCaret = !this.durationCaret;
                } else if (caretType === 'closingDateCaret') {
                    this.closingDateCaret = !this.closingDateCaret;
                }

            },

            clearFilter() {
                this.filtered_data = {};
                this.min_term = '';
                this.max_term = '';
                this.min_rate = '';
                this.max_rate = '';
                this.fiorganization = [];
                this.product_type = [];
                this.min_req_amount = '';
                this.max_req_amount = '';
                this.max_closing_date = '';
                this.min_closing_date = '';
                this.max_offer_rate = '';
                this.min_offer_rate = '';
                this.min_offer_amount = '';
                this.max_offer_amount = '';


                this.$emit('clear_filters')
            },
            removeIndividualFilter(filterItem) {

                switch (filterItem.toLowerCase()) {
                    case 'closing_dates':
                        this.min_closing_date = '';
                        this.max_closing_date = '';
                        break;
                    case 'request_amount':
                        this.min_req_amount = '';
                        this.max_req_amount = '';
                        break;
                    case 'termlengthtype':
                        this.selectedTermLengthType = '';
                        this.min_term = '';
                        this.max_term = '';
                        break;
                    case 'termlength':
                        this.selectedTermLengthType = '';
                        this.min_term = '';
                        this.max_term = '';
                        break;
                    case 'product_type':
                        this.product_type = []
                        break;
                    case 'financialorganizations':
                        this.fiorganization = [];
                        this.filtered_data = {};
                        break;
                    case 'offer_rate_limit':
                        this.min_offer_rate = '';
                        this.max_offer_rate = '';
                        break;
                    case 'offer_amount_limit':
                        this.min_offer_amount = '';
                        this.max_offer_amount = '';
                        break;
                    default:
                        break
                }
                if (!this.hasFilters) {
                    this.submitFilter()
                } else {
                    this.$emit('clear_filters')

                }
            },
            submitFilter() {
                if (this.from === 'reviewoffers') {
                    this.filtered_data = `closing_dates=${this.min_closing_date ? this.min_closing_date : 0},${this.max_closing_date ? this.max_closing_date : 0}&request_amount=${this.min_req_amount ? sanitizeAmount(this.min_req_amount) : 0},${this.max_req_amount ? sanitizeAmount(this.max_req_amount) : 0}&termLengthType=${this.selectedTermLengthType}&termLength=${this.min_term ? this.min_term : 0},${this.max_term ? this.max_term : 0}&product_type=${this.product_type}`

                } else if (this.from === 'offerssum') {

                    this.filtered_data = `financialOrganizations=${this.fiorganization.join(',')}&offer_amount_limit=${this.min_offer_amount ? sanitizeAmount(this.min_offer_amount) : 0},${this.max_offer_amount ? sanitizeAmount(this.max_offer_amount) : 0}&offer_rate_limit=${this.min_offer_rate ? sanitizeAmount(this.min_offer_rate) : 0},${this.max_offer_rate ? sanitizeAmount(this.max_offer_rate) : 0}`
                }
                this.$emit('apply_filters', this.filtered_data)
                // console.log(this.filtered_data)

            },
        },
        watch: {
            min_term(newValue, oldValue) {
                this.checkFilters()
            },
            max_term(newValue, oldValue) {
                this.checkFilters()
            },
            min_closing_date(newValue, oldValue) {
                this.checkFilters()
            },
            max_closing_date(newValue, oldValue) {
                this.checkFilters()
            },
            min_req_amount(newValue, oldValue) {
                this.checkFilters()
            },
            max_req_amount(newValue, oldValue) {
                this.checkFilters()
            },
            min_offer_rate(newValue, oldValue) {
                this.checkFilters()
            },
            max_offer_rate(newValue, oldValue) {
                this.checkFilters()
            },
            product_type() {
                this.checkFilters()
            },
            fiorganization() {
                this.checkFilters()
            },
            min_offer_amount() {
                this.checkFilters()
            },
            max_offer_amount() {
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
        width: 430px;
        padding: 0px 10px;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        border-radius: 10px;
        border: 0.5px solid #D9D9D9 !important;
        background: white;
        box-shadow: 0px 4px 4px 0px #EFF2FE;
        max-height: 500px;
        overflow-y: auto;
    }



    .rounded-input {
        height: 40px;
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

    .form-control:focus {
        box-shadow: none !important;
        outline: none !important;
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