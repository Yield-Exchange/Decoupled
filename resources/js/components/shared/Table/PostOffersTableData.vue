<template>
    <tbody class="customBody">
        <tr v-for="(datum, index) in  data" :key="'TableRow' + index">
            <td v-for="(datum1, index1) in datum" :key="'TableData' + index1">
                <template v-if="selectable && index1 === 0">

                    <b-form-checkbox :id="'checkbox-' + index" name="checkbox-1" class="checkbox-select-table"
                        :value="datum1" :unchecked-value="-datum1" @change="selected"
                        :checked="(selectedOptions && selectedOptions.includes(datum1)) ? datum1 : null" />

                </template>
                <template v-else-if="typeof datum1 === 'function'">
                    <div v-if="!datum1()?.component" v-html="(datum1())"></div>
                    <component v-else :is="(datum1()?.component)" v-bind="(datum1()?.attrs)" />
                </template>
                <template v-else-if="index1 !== 0  ">
                    {{ formatIfNumber(datum1) }}
                </template>
            </td>
            <td v-if='has_action'>
                <Actions>
                    <b-dropdown-item v-on:click="() => false" href="#"
                        v-if="actions && conditionIsMet(action?.condition_checker_index, index, action.conditionCheckerValue)"
                        v-for="(action, index8) in actions" :key="'DropDownItem' + index + '-' + index8">
                        <component @reloadData="reloadData" :actionId="data[index][0]"
                            :actionObjectDetails="data[index]" :is="action.component" v-bind="action.attrs"
                            @productDeletedAddNew="$emit('productDeletedAddNew')">{{ action.name }}
                        </component>
                    </b-dropdown-item>
                </Actions>
            </td>
        </tr>
    </tbody>
</template>

<script>
    import ModalNew from "../ModalNew.vue";
    import Actions from "./ActionsReviewOffers";
    import { mapGetters } from 'vuex';


    import * as types from '../../../store/modules/campaigns/mutation-types.js';

    export default {
        mounted() {

        },
        components: {
            Actions,
        },
        props: ['nonRenderbleItems', 'data', 'has_action', 'actions', 'selectable'],
        data() {

            // console.log(this.actions, "actionsactions");

            return {

            }
        },
        computed: {
            ...mapGetters('campaign', ['getCampaignSelectedProducts', 'getCampaignSelectedProductIDS']),
        },
        methods: {
            selected(newValue) {

                if (newValue > 0) {
                    // Checkbox checked
                    this.selectedOptions.push(newValue);
                } else {
                    // Checkbox unchecked
                    newValue = Math.abs(newValue);
                    const index = this.selectedOptions.indexOf(newValue);
                    if (index !== -1) {
                        this.selectedOptions.splice(index, 1);
                    }
                }
                this.$store.commit('campaign/' + types.UPDATE_CAMPAIGN_SELECTED_PRODUCTS_IDS, [newValue]);
                this.$store.commit('campaign/' + types.UPDATE_SELECTED_PRODUCTS, [{
                    product_id: newValue
                }]);
                this.$emit("selectedItems", this.selectedOptions);
            },
            slicedData(datum) {
                console.log(datum, "datumdatum");
                return datum;

            },
            conditionIsMet(condition_checker_index, index, conditionCheckerValue) {
                if (!condition_checker_index) {
                    return true;
                }
                try {
                    //     console.log("Index" + this.data[index][condition_checker_index] + "Condition_checker" + conditionCheckerValue);
                    return ((this.data[index][condition_checker_index] === conditionCheckerValue));
                } catch (e) { }
                return true
            },
            reloadData(e) {
                this.$emit('reloadData', e);
            },
            formatDateTimeWithoutSeconds(dateTime) {
                if (!(dateTime instanceof Date) || isNaN(dateTime)) {
                    return dateTime; // Return unchanged if not a valid Date object
                }
                const year = dateTime.getFullYear();
                const month = String(dateTime.getMonth() + 1).padStart(2, '0');
                const day = String(dateTime.getDate()).padStart(2, '0');
                const hour = String(dateTime.getHours()).padStart(2, '0');
                const minute = String(dateTime.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day} ${hour}:${minute}`;
            },
            formatIfNumber(value) {
                if (typeof value === 'number') {
                    value = value.toString(); // Convert to string for further processing
                    // Check if commas are missing
                    const parts = value.split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Add commas for thousands
                    value = parts.join('.');
                }
                return value;
            },
        }, watch: {
            selectAllRecords(newValue, oldValue) {

                if (newValue) {
                    this.data.map((item, key) => {
                        this.selectedOptions.push(item[0]);
                    })
                } else {

                    this.selectedOptions = [];
                }

                console.log(this.selectedOptions, "this.selectedOptionsthis.selectedOptions");
                this.selectedOptions = [...new Set(this.selectedOptions)];
                this.$emit("selectedItems", this.selectedOptions);
            }
        }
    }
</script>