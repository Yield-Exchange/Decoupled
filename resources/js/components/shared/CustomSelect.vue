<template>
    <div :style="pStyle">
        <b-form-select :class="'mt-1 input-height form-input-text ' + (hasValidation && isInvalid ? 'is-invalid' : '')"
            :options="options" :aria-describedby="'input-live-help input-' + (id ? id : name) + '-feedback'"
            style="border-radius: 999px;" :value-field="attrs && attrs.value_field ? attrs.value_field : 'id'"
            :text-field="attrs && attrs.text_field ? attrs.text_field : 'name'" v-bind="attrs" :value="value"
            @change="onValueChanged" filter :style="inputStyle" >


            <template #first v-if="firstValue">
                <b-form-select-option value="" selected="" disabled>{{ firstValue }}</b-form-select-option>
            </template>


        </b-form-select>
        <!-- This will only be shown if the preceding input has an invalid state -->
        <b-form-invalid-feedback :id="'input-' + (id ? id : name) + '-feedback'" :style="cStyle" v-if="hasValidation">
            {{ validationError ? validationError : 'Select ' + name }}
        </b-form-invalid-feedback>
    </div>
</template>


<style scoped>
    .red-icon {
        color: red;
    }

    .input-height {
        height: 40px;
    }





    .custom-select.is-valid,
    .was-validated .custom-select:valid {
        border-color: #ddd;
        background-image: none
    }
</style>


<script>


    export default {
        props: ["attributes", 'id', 'name', 'data', 'cStyle', 'inputStyle','padding', 'hasValidation', 'pStyle', 'defaultValue', 'validationFailed', 'validationError', 'firstValue'],
        created() {
        },
        computed: {
        },
        data() {
            console.log(this.defaultValue + " " + this.name, "default");
            return {
                attrs: this.attributes ? this.attributes : {},
                options: this.data ? this.data : null,
                value: this.defaultValue ? this.defaultValue : '',
                isInvalid: this.validationFailed
            }
        },
        methods: {
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring.charAt(0).toUpperCase() + thestring.slice(1).toLowerCase();
                }

            },
            onValueChanged(newValue) {
                this.value = newValue;
                this.$emit('selectChanged', newValue)
            }
        },
        watch: {
            validationFailed: function (newVal, oldVal) { // watch it
                this.isInvalid = newVal;
            },
            defaultValue: function (newValue) {
                this.value = newValue;
            }
        }
    }


</script>
