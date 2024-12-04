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

        <div class="dropdown-menu pb-3" ref="myScrollDown" :class="{ 'd-flex': show_dropdown }">
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
                    :title="index" @removeSingleItem="removeIndividualFilter">
                </FilteredItem>
            </template>

            <!-- Status  -->
            <CustomDropdownToggle v-if="showSection('status')" title="Status" toggleFor="statuscarret"
                target="acc-sub-limit" @toggleCarret="toggleCaret" :carretState="statuscarret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <div class="col-md-12">
                        <FormLabelRequired labelText="Status" :required="false" :showHelperText="false"
                            helperText="Product" helperId="product" />
                        <b-select v-model="status" @change=""
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                            <option :value="null" selected>Choose Status</option>
                            <option v-for="item in statuses" :key="item" :value="item">{{ capitalize(item) }}
                            </option>
                        </b-select>

                    </div>
                </div>
            </CustomDropdownToggle>

            <!-- basket Type -->
            <CustomDropdownToggle v-if="showSection('btype')" title="Basket Type" toggleFor="basketcarret"
                target="basketcarret" @toggleCarret="toggleCaret" :carretState="basketcarret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <div class="col-md-12">
                        <FormLabelRequired labelText="Basket Type" :required="false" :showHelperText="false"
                            helperText="Basket Type" helperId="product" />
                        <b-select v-model="basketType" @change=""
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                            <option :value="null" selected>Choose Basket type</option>
                            <option v-for="item in gettripartyCollaterals" :key="item.id" :value="item.basket_name">{{
            item.basket_name }}
                            </option>
                        </b-select>
                    </div>
                </div>
            </CustomDropdownToggle>
            <CustomDropdownToggle v-if="showSection('ctype')" title="Collateral Type" toggleFor="collateralcarret"
                target="collateralcarret" @toggleCarret="toggleCaret" :carretState="collateralcarret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <div class="col-md-12">
                        <FormLabelRequired labelText="Collateral Type" :required="false" :showHelperText="false"
                            helperText="Collateral Type" helperId="product" />
                        <b-select v-model="collateral" @change="" v-if="getBilateralCollaterals"
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                            <option :value="null" selected>Choose Collateral</option>
                            <option v-for="item in getBilateralCollaterals" :key="item.id"
                                :value="item.collateral_name">{{
            item.collateral_name }}
                            </option>
                        </b-select>
                    </div>
                </div>
            </CustomDropdownToggle>

            <CustomDropdownToggle v-if="showSection('issuer')" title="Collateral Issuer" toggleFor="issuercarret"
                target="issuercarret" @toggleCarret="toggleCaret" :carretState="issuercarret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <div class="col-md-12">
                        <FormLabelRequired labelText="Collateral Type" :required="false" :showHelperText="false"
                            helperText="Collateral Type" helperId="product" />
                        <b-select v-model="issuer" @change=""
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                            <option :value="null" selected>Select Issuer</option>
                            <option v-for="item in getCollateralIssuers" :key="item.id" :value="item.name">{{ item.name
                                }}
                            </option>
                        </b-select>
                    </div>
                </div>
            </CustomDropdownToggle>

            <!-- Rating -->
            <CustomDropdownToggle v-if="showSection('rating')" title="Rating" toggleFor="ratingcarret"
                target="ratingcarret" @toggleCarret="toggleCaret" :carretState="ratingcarret">
                <div class="d-flex justify-content-between w-100 gap-3">
                    <div class="col-md-12">
                        <FormLabelRequired labelText="Rating" :required="false" :showHelperText="false"
                            helperText="Rating" helperId="product" />
                        <b-select v-model="rating" @change=""
                            style="border-radius: 999px; height:40px; margin-top:5px;font-weight: 400 !important;width:100%; font-size:  16px !important;">
                            <option :value="null" selected>Choose a rate</option>
                            <option v-for="item in ratings" :key="item" :value="item">{{ item }}
                            </option>
                        </b-select>
                    </div>
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
import FormLabelRequired from '../../../../shared/formLabels/FormLabelRequired.vue';
import { capitalize } from '../../../../../utils/commonUtils';
import { mapGetters } from 'vuex';

export default {
    props: ['showFilters', 'dontshow', 'filterType', 'filtered_item', 'from'],
    components: {
        CustomFilterinput,
        Multiselect,
        CustomDropdownToggle,
        FilteredItem,
        FormLabelRequired
    },
    mounted() {
        // this.collateralTypes = this.getBilateralCollaterals
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

        return {
            statuses: ['ACTIVE', 'PENDING', 'ARCHIVED', 'ATTENTION', 'VERIFIED'],
            basketTypes: ['HQLA', 'Bank of Canada'],
            collateralTypes: null,
            status: null,
            collateral: null,
            rating: null,
            basketType: null,
            issuer: null,


            // carrets
            statuscarret: false,
            ratingcarret: false,
            basketcarret: false,
            collateralcarret: false,
            issuercarret: false,

            show_multiselect: false,
            show_dropdown: false,
            filtered_data: {},
            startFilter: true,


        }
    },
    computed: {
        ratings() {
            return this.$store.getters.systemRating
        },
        ...mapGetters('basket', ['getCollateralIssuers', 'getBilateralCollaterals', 'gettripartyCollaterals'])
    },

    methods: {

        capitalize(stringtext) {
            return capitalize(stringtext)
        },
        showSection(value) {
            if (this.dontshow) {
                return !this.dontshow.includes(value)
            } else {
                return true
            }
        },
        checkFilters() {
            if (this.status == null && this.issuer == null && this.collateral == null && this.basketType == null && this.rating == null) {
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
            if (caretType === 'statuscarret') {
                this.statuscarret = !this.statuscarret;
            }
            if (caretType === 'issuercarret') {
                this.issuercarret = !this.issuercarret;
            }
            if (caretType === 'basketcarret') {
                this.basketcarret = !this.basketcarret;
            }
            if (caretType === 'ratingcarret') {
                this.ratingcarret = !this.ratingcarret;
            }
            if (caretType === 'collateralcarret') {
                this.collateralcarret = !this.collateralcarret;
            }
        },

        clearFilter() {
            this.filtered_data = {},
                this.startFilter = true,
                this.$emit('clear_filters')
        },
        removeIndividualFilter(filterItem) {
            switch (filterItem.toLowerCase()) {
                case 'status':
                    this.status = null
                    break;
                case 'collateralname':
                    this.collateral = null
                    break;
                case 'baskettype':
                    this.basketType = null
                    break;
                case 'issuer':
                    this.issuer = null
                    break;
                case 'rating':
                    this.rating = null
                    break;
                case 'counterpartystatus':
                    this.status = null
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
            if (this.filterType == "colaterals") {
                let filters = ''
                if (this.status) {
                    filters += filters == '' ? `status=${this.status}` : `&status=${this.status}`
                }
                if (this.collateral) {
                    filters += filters == '' ? `collateralName=${this.collateral}` : `&collateralName=${this.collateral}`
                }
                if (this.rating) {
                    filters += filters == '' ? `rating=${this.rating}` : `&rating=${this.rating}`
                }
                if (this.issuer) {
                    filters += filters == '' ? `issuer=${this.issuer}` : `&issuer=${this.issuer}`
                }
                this.filtered_data = filters
                this.$emit('apply_filters', this.filtered_data)

            } else if (this.filterType == "triparty_baskets") {

                let filters = ''
                if (this.from == 'tribasket') {
                    if (this.status) {
                        filters += filters == '' ? `counterPartyStatus=${this.status}` : `&counterPartyStatus=${this.status}`
                    }
                } else {
                    if (this.status) {
                        filters += filters == '' ? `status=${this.status}` : `&status=${this.status}`
                    }

                }
                if (this.basketType) {
                    filters += filters == '' ? `basketType=${this.basketType}` : `&basketType=${this.basketType}`
                }
                if (this.rating) {
                    filters += filters == '' ? `rating=${this.rating}` : `&rating=${this.rating}`
                }
                this.filtered_data = filters
                this.$emit('apply_filters', this.filtered_data)

            }
        },
    },
    watch: {
        basketType() {
            this.checkFilters()
        },
        rating() {
            this.checkFilters()
        },
        collateral() {
            this.checkFilters()
        },
        status() {
            this.checkFilters()
        },
        issuer() {
            this.checkFilters()
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
<style scoped>
.label {
    color: var(--gray-700, #344054);
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
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
</style>
