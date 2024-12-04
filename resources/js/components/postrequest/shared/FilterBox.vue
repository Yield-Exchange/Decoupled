<template>

    <div class="w-100 d-flex justify-content-end align-items-center" ref="parentWidth">

        <template v-if="filtered_item != null">
            <!-- {{ filtered_item }} -->
            <FilteredItem ref="filteredItemRefs" v-for="(item, index) in filtered_item " :key="index" :value="item"
                :termLengthType="termLength" :title="index" @removeSingleItem="removeSingleItem"></FilteredItem>

            <SeeAllFilters @showAllFilters="showAllFilters" v-if="showSeeAll" title="See All"></SeeAllFilters>
        </template>
        <div v-if="fiorganizations"
            style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: flex">
            <FilterDropdown @clear_filters="clearFilters" :provinces="provinces" :products="products"
                :isfromrequest="isfromrequest" :industries="industries" :itemToRemove="itemToRemove"
                :filterType="filterType" :noRate="noRate" @apply_filters="applyFilters" :showFilters="showFilters"
                :termLengthType="termLength" :filtered_item="extraFilter" :fiorganizations="fiorganizations"
                ref="filterDropdownRef">
            </FilterDropdown>

        </div>
        <div
            style="width: 300px ; padding-left: 16px;  padding-bottom: 8px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;cursor: pointer">
            <b-form-input type="text" :placeholder="'Search'" :class="'font-13 input-height '" :id="'date_of_deposit'"
                :aria-describedby="'input-live-help input-date_of_deposit-feedback'"
                :style="'border-radius: 10px;outline:none; box-shadow: none;'" v-model="search_val" @keyup="search" />
        </div>
    </div>

</template>


<script>

import SeeAllFilters from './SeeAllFilters.vue'
import FilterDropdown from './FilterDropdown'
import FilteredItem from './FilteredItem.vue'
export default {
    props: ['filtered', 'filterType', 'isfromrequest', 'noRate'],
    components: { SeeAllFilters, FilterDropdown, FilteredItem },

    mounted() {
        this.getWidth()
        this.setProductTypes()
        this.setFis()
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
            showFilters: false,
            provinces: null,
            industries: null,
            products: null,
            fiorganizations: null,
            current_filters: null

        }
    },
    methods: {
        search(event) {
            let filterstring = null
            if (this.current_filters != null) {
                filterstring = this.current_filters + "&search=" + event.target.value
            } else {
                filterstring = "search=" + event.target.value

            }
            this.$emit('apply_filters', filterstring);
        },
        applyFilters(value) {
            // console.log(value)
            this.current_filters = value
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
                // console.log("Parent Width", parentWidth)
                if (parentWidth >= 0) {
                    this.parentWidth = parentWidth
                    // console.log('Parent Width:', this.filterType + " Width " + parentWidth);
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

            // console.log('Object 1:', object1);
            // console.log('Object 2:', object2);


        },
        removeSingleItem(item) {
            this.$refs.filterDropdownRef.removeIndividualFilter(item);
        },
        clearFilters(value = null) {
            this.filtered_item = null
            this.extraFilter = null
            this.current_filters = null
            this.showSeeAll = false
            this.$emit('clear_filters', value);
        },
        async setProvinces() {
            await axios.get('/get-all-provinces-sign-up').then(response => {
                // if (response.data.success)
                // console.log(response.data)
                this.provinces = response.data
            }).catch(err => {
                // console.log(err)
            })
        },
        async setIndustries() {
            await axios.get('/get-all-industries').then(response => {
                // if (response.data.success)
                // console.log(response.data)
                this.industries = response.data
            }).catch(err => {
                // console.log(err)
            })
        },
        async setProductTypes() {
            await axios.get('/get-all-products').then(response => {

                this.products = response.data
            }).catch(err => {
                // console.log(err)
            })
        },
        async setFis() {
            await axios.get('/get-all-organizations/BANK').then(response => {
                this.fiorganizations = response.data
            }).catch(err => {
                // console.log(err)
            })
        },
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