<template>
    <div ref="parentWidth"
        style="width: 100%;height:3.5rem; display: inline-flex;justify-content:flex-end;gap: 20px; padding-right: 0">

        <template v-if="filtered_item != null">
            <!-- {{ filtered_item }} -->
            <FilteredItem ref="filteredItemRefs" v-for="(item, index) in filtered_item " :key="index" :value="item"
                :termLengthType="termLength" :title="index" @removeSingleItem="removeSingleItem"></FilteredItem>
            <SeeAllFilters @showAllFilters="showAllFilters" v-if="showSeeAll" title="See All"></SeeAllFilters>
        </template>
        <div style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: flex">
            <FilterDropdown @clear_filters="clearFilters" :provinces="provinces" :products="products"
                :industries="industries" :itemToRemove="itemToRemove" :filterType="filterType"
                @apply_filters="applyFilters" :showFilters="showFilters" :termLengthType="termLength"
                :filtered_item="extraFilter" :fiorganizations="fiorganizations" ref="filterDropdownRef">
            </FilterDropdown>
        </div>
        <div
            style="width: 300px !important; padding-left: 16px;  padding-bottom: 8px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;cursor: pointer">
            <b-form-input type="text" :placeholder="'Search'" :class="'font-13 input-height '" :id="'date_of_deposit'"
                :aria-describedby="'input-live-help input-date_of_deposit-feedback'"
                :style="'border-radius: 10px;outline:none; box-shadow: none;width:100% !important; '"
                v-model="search_val" @keyup="search" />
        </div>
    </div>
</template>
<style>
    #dropdown-table-filter-form>button {
        background-color: white !important;
        color: black;
        border: none;
    }

    #dropdown-table-filter-form ul.dropdown-menu {
        width: 300px !important;
    }
</style>
<script>
    import CustomInput from "../../shared/CustomInput";
    import FilterDropdown from "./FilterDropdown.vue";

    import FilteredItem from "./FilteredItem.vue";
    import SeeAllFilters from "./SeeAllFilters.vue";

    export default {
        mounted() {
            // this.getWidth()
        },
        components: {
            CustomInput,
            FilterDropdown,
            FilteredItem,
            SeeAllFilters
        },
        created() {
        },
        beforeUpdate() {
            this.getTotalWidth()
            this.getWidth()
        },
        update() {
            this.getTotalWidth()

        },
        props: ['filtered', 'provinces', 'products', 'filterType', 'industries', 'fiorganizations'],
        data() {
            return {
                search_val: null,
                filtered_item: null,
                termLength: null,
                itemToRemove: null,
                fullWidth: 0,
                contentWidth: 0,
                showSeeAll: false,
                extraFilter: null,
                available_space: 0,
                constantWidth: 400,
                showFilters: false

            }
        },
        methods: {
            search() {
                this.$emit('searching', this.search_val);
            },
            applyFilters(value) {
                this.getWidth()
                this.$emit('apply_filters', value);
                this.filterAction(value)
            },
            showAllFilters(value) {
                this.showFilters = value
            },
            getWidth() {
                if (this.$refs.parentWidth) {
                    const parentWidth = this.$refs.parentWidth.offsetWidth;

                    if (parentWidth >= 0) {
                        this.parentWidth = parentWidth
                        console.log('Parent Width:', this.filterType + " Width " + parentWidth);
                    }
                }

            },
            getTotalWidth() {
                const filteredItemRefs = this.$refs.filteredItemRefs;
                let totalWidth = 0;

                if (filteredItemRefs) {
                    // Loop through the refs and sum up the offsetWidth of each item
                    filteredItemRefs.forEach((itemRef) => {
                        if (itemRef.$el) {
                            totalWidth += itemRef.$el.offsetWidth;
                        }
                    });
                    if (totalWidth >= 0) {
                        this.contentWidth = totalWidth
                        // console.log('Parent Width:', totalWidth);
                    }
                    // console.log("Total Width of Items:", totalWidth);
                } else {
                    // console.error("FilteredItem refs not found.");
                }
            },
            filterAction(value) {
                const remaining = this.parentWidth - this.constantWidth
                this.available_space = remaining
                this.showSeeAll = false

                const inputString = value
                // Split the string by "&" to separate key-value pairs
                const keyValuePairs = inputString.split('&');
                const resultObject = {};
                keyValuePairs.forEach(pair => {
                    const [key, value] = pair.split('=');
                    if (key != 'termLengthType') {
                        resultObject[key] = value.split(',');
                    }
                    if (key == 'termLengthType') {
                        this.termLength = value
                    }

                });
                // this.filtered_item = resultObject

                const filteredResults = Object.entries(resultObject)
                    .filter(([key, value]) => value.some(v => (v != '0' && v != '')))
                    .reduce((acc, [key, value]) => {
                        acc[key] = value;
                        return acc;
                    }, {});

                // length of the filtered keys 
                // work on filters to show
                const filteredItemLength = Object.keys(filteredResults).length;
                const possibleFilterItems = Math.floor(this.available_space / 300)
                let xCondition = 0
                console.log('Availabe Space', possibleFilterItems)
                if (filteredItemLength > possibleFilterItems) {
                    xCondition = possibleFilterItems;
                    this.showSeeAll = true

                } else {
                    xCondition = possibleFilterItems;

                }


                const object1 = {};
                const object2 = {};
                let counter = 0
                Object.entries(filteredResults).forEach(([key, value]) => {
                    if (counter < xCondition) {
                        object1[key] = value

                    } else {
                        object2[key] = value
                    }
                    counter++
                });
                this.filtered_item = object1
                this.extraFilter = object2

                console.log('Object 1:', object1);
                console.log('Object 2:', object2);


            },
            removeSingleItem(item) {
                this.$refs.filterDropdownRef.removeIndividualFilter(item);
            },
            clearFilters(value = null) {
                this.filtered_item = null
                this.extraFilter = null
                this.showSeeAll = false
                this.$emit('clear_filters', value);
            }
        },
        watch: {
            filtered: {
                deep: false,
                handler(newData, oldData) {
                    // console.log('Data prop changed:', newData, oldData);
                }
            },
        }
    }
</script>