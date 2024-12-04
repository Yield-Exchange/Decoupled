<template>
    <tbody class="customBody">
        <tr v-for="(datum, index) in  data" :key="'TableRow' + index">

            <td v-for="(datum1, index1) in slicedData(datum)" :key="'TableData' + index1">

                <template v-if="selectable && index1 === 0">

                    <b-form-checkbox :id="'checkbox-' + index" name="checkbox-1" class="checkbox-select-table"
                        :value="datum1" :unchecked-value="-datum1" @change="selected"
                        :checked="(selectedOptions && selectedOptions.includes(datum1)) ? datum1 : null" />

                </template>
                <template v-else-if="typeof datum1 === 'function'">
                    <div v-if="!datum1()?.component" v-html="(datum1())"></div>
                    <component v-else :is="(datum1()?.component)" v-bind="(datum1()?.attrs)" />
                </template>
                <template v-else-if="index1 !== 0">
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
                            @productDeletedAddNew="$emit('productDeletedAddNew')">{{
            action.name }}
                        </component>

                    </b-dropdown-item>
                </Actions>
            </td>
        </tr>
    </tbody>
</template>
<style>
tbody.customBody {
    background: #FFFF !important;
    height: 51px;
}

tbody.customBody tr {
    border-bottom: 0.50px #D9D9D9 solid !important;
}

tbody.customBody tr td {
    border: none !important;
    color: black;
    font-size: 14px !important;
    font-weight: normal !important;
    background: inherit !important;
    max-width: 200px;
}

tbody.customBody tr td>div,
tbody.customBody tr td>div>div {
    font-weight: normal !important;
}

tbody.customBody .custom-checkbox .custom-control-label {
    margin-top: 0;
}

tbody.customBody .custom-checkbox .custom-control-label::before {
    border-radius: 4px !important;
    border: 0.50px #5063F4 solid !important;
}
</style>
<script>
import ModalNew from "../ModalNew.vue";
import Actions from "./Actions";
export default {
    mounted() {
    },
    components: {
        Actions
    },
    created() {
    },
    props: ['nonRenderbleItems', 'data', 'has_action', 'actions', 'selectable', 'selected_items', 'columns', 'selectAllRecords'],
    data() {
        return {
            selectedOptions: this.selected_items ? this.selected_items : [],
        }
    },
    computed: {

    },
    methods: {
        selected(newValue) {
            if (newValue > 0) {
                // Checkbox checked
                this.selectedOptions.push(newValue);
            } else {
                // Checkbox unchecked
                newValue = Math.abs(newValue).toString();

                const index = this.selectedOptions.indexOf(newValue);
                if (index !== -1) {
                    this.selectedOptions.splice(index, 1);
                }
            }
            this.$emit("selectedItems", this.selectedOptions);
        },
        slicedData(datum) {
            if (this.nonRenderbleItems) {
                return datum.slice(0, this.columns.length - 1);
            } else {
                if (datum) {
                    return datum.slice(0, this.columns.length + 1);
                }
            }

            return [];
        },
        conditionIsMet(condition_checker_index, index, conditionCheckerValue) {
            if (!condition_checker_index) {
                return true;
            }

            try {
                //     console.log("Index" + this.data[index][condition_checker_index] + "Condition_checker" + conditionCheckerValue);
                return ((this.data[index][condition_checker_index] === conditionCheckerValue));
            } catch (e) {
            }

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