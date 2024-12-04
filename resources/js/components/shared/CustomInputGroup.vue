<template>
    <div :style="pStyle">
        <b-input-group :style="cStyle"
            :aria-describedby="'input-live-help input-' + (id ? id : 'form-input') + '-feedback'">
            <b-input-group-prepend v-if="append === 'prepend'" :style="appendedStyle">
                <CustomSelect :attributes="appendAttributes" :data="data" :id="appendId" :name="appendName"
                    :has-validation="false" @selectChanged="this.selectValue" :default-value="appendVal"
                    p-style="width: 100%;border-bottom-left-radius: 10px"
                    c-style="border-bottom-left-radius: 10px;border-top-left-radius: 10px;height: 40px !important;" :firstValue="firstValue" />
            </b-input-group-prepend>
            <CustomInput :name="name" :id="id" :p-style="inputStyle" :has-validation="hasValidation"
                :input-type="inputType" :c-style="appendText ? 'border-radius: 10px; height: 40px !important;' : 'border-radius: 0;height: 40px !important;border-top-' + (append === 'prepend' ? 'right' : 'left') + '-radius: 10px;' +
                'border-bottom-' + (append === 'prepend' ? 'right' : 'left') + '-radius: 10px'"
                @inputChanged="this.inputValue" :default-value="inputVal" :validation-failed="validationFailed" :attributes="attributes"
                :validation-error="validationError" @onKeyUp="inputValueOnKeyUp" />
            <b-input-group-prepend v-if="append === 'append'" :style="appendedStyle">
                <span v-if="appendText" class="font-s-20 pl-2">{{ appendText }}</span>
                <CustomSelect v-if="!appendText" :attributes="appendAttributes" :data="data" :id="id" :name="name" :has-validation="false"
                    :default-value="appendVal" @selectChanged="this.selectValue" p-style="width: 100%;"
                    c-style="border-bottom-right-radius: 10px;border-top-right-radius: 10px;height: 40px !important;" :firstValue="firstValue" />
            </b-input-group-prepend>
        </b-input-group>
    </div>
</template>

<style scoped>
.input-height {
    height: 40px;
}
</style>

<script>

import CustomInput from "./CustomInput";
import CustomSelect from "./CustomSelect";
export default {
    components: { CustomSelect, CustomInput },
    props: ["attributes", 'id', 'submitted', 'name', 'append', 'data', 'cStyle', 'appendAttributes', 'appendId', 'appendName', 'pStyle', 'inputType','attributes',
        'appendedStyle', 'inputStyle', 'hasValidation', 'inputDefaultValue', 'appendDefaultValue', 'validationFailed', 'validationError', 'firstValue','appendText'],
    computed: {
    },
    data() {
        return {
            inputVal: this.inputDefaultValue,
            appendVal: this.appendDefaultValue,
        }
    },
    methods: {
        inputValueOnKeyUp(newValue) {
            this.$emit('onKeyUp', newValue)
        },
        inputValue(newValue) {
            this.$emit('inputChanged', newValue)
        },
        selectValue(newValue) {
            this.$emit('selectChanged', newValue)
        }
    },
    watch: {
        inputDefaultValue: function (newValue) {
            this.inputVal = newValue;
        },
        appendDefaultValue: function (newValue) {
            console.log(newValue);
            this.appendVal = newValue;
        }
    }
}

</script>
