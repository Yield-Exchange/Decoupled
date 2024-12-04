<template>
    <!-- Example single danger button -->
    <div class="btn-group" @blur="toggleDropdown">
        <button type="button" @click="toggleDropdown" class="dropdown-button" :class="{ hasFilters: !startFilter }"
            data-toggle="" aria-haspopup="true" aria-expanded="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M6 12H18M3 6H21M9 18H15" v-if="startFilter" stroke="black" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 12H18M3 6H21M9 18H15" v-else stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg> Filters
        </button>
        <div class="dropdown-menu" :class="{ 'd-flex': show_dropdown }">
            <div class="d-flex justify-content-between w-100 px-3 align-items-center">
                <a @click="submitFilter" class="apply-filters"> Apply Filters</a>
                <a @click="clearFilter" class="clear-filter"> Clear Filters</a>
            </div>
            <!-- grouping status -->

            <CustomDropdownToggle title="Grouping" toggleFor="groupingscaret" target="acc-grouping"
                @toggleCarret="toggleCaret" :carretState="groupingscaret">
                <div class="d-flex flex-column">
                    <div class="form-check" v-for="(grouping, index) in groupings" :key="index">
                        <div style="display: flex; flex-direction: row;  align-items: center;">
                            <div style="height: 100%; display:flex; align-items:center; ">
                                <input class="form-check-input" type="checkbox" name="grouping" :value="grouping"
                                    v-model="selectedGrouping" id="flexCheckDefault">
                            </div>
                            <div style="height: 100%; display:flex; align-items:center; font-family: Montserrat;
                            font-size: 15px;
                            font-weight: 500;
                            line-height: 14px;
                            letter-spacing: 0px;
                            margin-top:5px;
                            ">

                                {{ grouping }}

                            </div>

                        </div>

                    </div>
                </div>
            </CustomDropdownToggle>

            <!-- grouping status -->
            <!-- provinces -->
            <CustomDropdownToggle title="Provinces" toggleFor="provincescaret" target="acc-provinces"
                @toggleCarret="toggleCaret" :carretState="provincescaret">
                <div class="d-flex flex-column">
                    <div class="form-check" v-for="(province, index) in provinces" :key="index">
                        <div style="display: flex; flex-direction: row;  align-items: center;">
                            <div style="height: 100%; display:flex; align-items:center; ">
                                <input class="form-check-input" type="checkbox" name="province" :value="province.province_name
                                " v-model="selectedProvinces" id="flexCheckDefault">
                            </div>
                            <div style="height: 100%; display:flex; align-items:center; font-family: Montserrat;
                            font-size: 15px;
                            font-weight: 500;
                            margin-top:5px;
                            letter-spacing: 0px;
                            ">

                                {{ province.province_name
                                }}

                            </div>

                        </div>


                    </div>
                </div>
            </CustomDropdownToggle>
            <!-- provinces -->
            <!-- industry -->
            <CustomDropdownToggle title="Industries" toggleFor="industriescaret" target="acc-industries"
                @toggleCarret="toggleCaret" :carretState="industriescaret">
                <div class="d-flex flex-column">
                    <div class="form-check" v-for="(industry, index) in industries" :key="index">
                        <div style="display: flex; flex-direction: row;  align-items: center;">
                            <div style="height: 100%; display:flex; align-items:center; ">
                                <input class="form-check-input" type="checkbox" name="province" :value="industry.id
                                " v-model="selectedIndustries" id="flexCheckDefault">
                            </div>
                            <div style="height: 100%; display:flex; align-items:center; font-family: Montserrat;
                            font-size: 15px;
                            font-weight: 500;
                            margin-top:5px;
                            letter-spacing: 0px;
                            ">
                                {{ industry.name
                                }}
                            </div>

                        </div>



                    </div>
                </div>
            </CustomDropdownToggle>
            <!-- industry -->
        </div>
    </div>
</template>
<script>
    import { emit } from 'process';
    import CustomFilterinput from './CustomFilterinput.vue';
    import CustomDropdownToggle from './CustomDropdownToggle.vue';
    // filterType

    // we have the fillowing filter types that we will work with
    // campaign ,products
    export default {
        props: ['provinces', 'groupings', 'products', 'industries', 'noclicks', 'industry', 'filterType'],
        components: {
            CustomDropdownToggle,
            CustomFilterinput
        },
        data() {
            return {

                startFilter: true,
                groupingg: [],
                selectedProvinces: [],
                show_dropdown: false,
                rate: '',
                filtered_data: {},
                selectedIndustries: [],
                selectedGrouping: [],
                groupingscaret: false,
                provincescaret: false,
                industriescaret: false,
            }
        },
        watch: {
            min_expiry() {
                this.min_date = this.min_expiry
            },
            max_expiry() {
                this.max_date = this.max_expiry
            }
        },
        methods: {
            toggleDropdown() {

                this.show_dropdown = !this.show_dropdown
                if (this.show_dropdown) {
                    this.startFilter = false;
                } else {
                    this.startFilter = true;
                }
            },
            toggleCaret(caretType) {
                if (caretType === 'groupingscaret') {
                    this.groupingscaret = !this.groupingscaret;
                }
                if (caretType === 'provincescaret') {
                    this.provincescaret = !this.provincescaret;
                }
                if (caretType === 'industriescaret') {
                    this.industriescaret = !this.industriescaret;
                }
            },
            clearFilter() {
                this.show_dropdown = false;
                this.selectedGrouping = [];
                this.selectedProvinces = [];
                this.selectedIndustries = [];
                this.$emit('clear_filters', '');
            },
            submitFilter() {
                this.show_dropdown = false;
                this.filtered_data = `grouping=${this.selectedGrouping ? this.selectedGrouping.join(",") : ''}&provinces=${this.selectedProvinces ? this.selectedProvinces.join(",") : ''}&industries=${this.selectedIndustries ? this.selectedIndustries.join(",") : ''}`
                this.$emit('apply_filters', this.filtered_data);
            },
        },
        watch: {

        }
    }
</script>


<style scoped>
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
        width: 395px;
        padding: 20px 0px;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        border-radius: 10px;
        border: 0.5px solid #D9D9D9 !important;
        background: white;
        box-shadow: 0px 4px 4px 0px #EFF2FE;
        max-height: 400px;
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