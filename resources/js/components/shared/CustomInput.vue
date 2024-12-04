<template>
    <div :style="pStyle">

        <b-form-input v-if="!inputType || ['number', 'text', 'password'].includes(inputType)" autocomplete="off"
            :type="inputType == 'password' ? 'password' : 'text'" :placeholder="name + ''"
            :class="'font-16 input-height rounded-field form-input-text ' + (hasValidation && isInvalid ? 'is-invalid' : '') + (hasSpecificError ? 'has-error' : '')"
            :style="inputStyle" style="border-radius: 25px;outline:none; box-shadow: none;" :id="id ? id : 'form-input'"
            :aria-describedby="'input-live-help input-' + (id ? id : name) + '-feedback'" v-bind="attr" v-model="value"
            :value="value" @change="onValueChanged" @keyup="handleInputChange" @paste="handleInputChange" :maxlength="maxNameLength"
            :disabled="(disabled) ? true : false" />
        <b-form-textarea v-if="inputType && inputType === 'textarea'" :placeholder="name + ''"
            :class="'font-16 input-height form-input-text ' + (isInvalid ? 'is-invalid' : '')"
            :id="id ? id : 'form-input'" :aria-describedby="'input-live-help input-' + (id ? id : name) + '-feedback'"
            v-bind="attr" :maxlength="maxNameLength" @change="onValueChanged" @keyup="handleInputChange"
            style="border-radius: 25px;outline:none; box-shadow: none;">
        </b-form-textarea>
        <b-form-textarea v-if="inputType && inputType === 'textareanew'" :placeholder="name + ''"
            :class="'font-16 input-height form-input-text ' + (isInvalid ? 'is-invalid' : '') + (hasSpecificError ? 'has-error' : '')"
            :id="id ? id : 'form-input'" :aria-describedby="'input-live-help input-' + (id ? id : name) + '-feedback'"
            v-bind="attr" v-model="value" :maxlength="maxNameLength" @change="onValueChanged" @keyup="handleInputChange"
            :rows="rows" style="border-radius: 10px;outline:none; box-shadow: none;">
        </b-form-textarea>
        <b-form-datepicker
            :class="'font-16 input-height form-input-text' + (hasValidation && isInvalid ? 'is-invalid' : '')"
            :aria-describedby="'input-live-help input-' + (id ? id : name) + '-feedback'"
            v-if="inputType && inputType === 'datepicker'" :style="cStyle" :placeholder="name + ''"
            :id="id ? id : 'form-input'" :value="value" v-on:input="onValueChanged" v-bind="attr" class="mb-2"
            style="border-radius: 25px;outline:none; box-shadow: none;">
        </b-form-datepicker>
        <!-- This will only be shown if the preceding input has an invalid state -->
        <b-form-invalid-feedback :id="'input-' + (id ? id : name) + '-feedback'" :style="cStyle" v-if="hasValidation">
            {{ validationError ? validationError : 'Enter ' + name }}
        </b-form-invalid-feedback>
        <TransactionFailed :error="transError" :show="hasTransError && dontShowErrorPopUp" title_="Error"
            @toggleShow="toggleShow" />
    </div>
</template>

<script>
import TransactionFailed from "../campaigns/depositor/single-offer/TransactionFailed";
export default {
    props: ["disabled", 'maxlength', 'inputStyle', "attributes", 'id', 'dontShowErrorModal', 'name', 'cStyle', 'pStyle', 'hasValidation', 'defaultValue', 'validationFailed', 'inputType', 'validationError', 'hasSpecificError', 'rows'],
    created() { },
    components: {
        TransactionFailed
    },
    mounted() {
        if (this.maxlength) {
            this.maxNameLength = this.maxlength
        }
    },
    computed: {
        dontShowErrorPopUp() {
            if (this.dontShowErrorModal) {
                return false
            } else {
                return true
            }
        }
    },
    data() {
        return {
            debounceTimeout: null,
            attr: this.attributes,
            value: this.defaultValue ? (this.inputType === 'number' ? this.addComma(this.defaultValue) : this.defaultValue) : '',
            isInvalid: this.validationFailed,
            maxNameLength: 50,
            hasTransError: false,
            transError: '',
        }
    },
    methods: {
        toggleShow(t) {
            this.hasTransError = false;
            this.transError = '';
        }, onValueChanged(newValue) {
            if (this.name === "Rate*") {

            }
        },
        debounceUpdate() {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                if (this.name === "Rate*") {
                    this.value = (this.inputType === 'number') && this.value != null && this.value != ""
                        ? parseFloat(this.value).toFixed(2)
                        : this.value;
                } else {
                    this.value = this.inputType === 'number' ? this.addComma(this.value) : this.value;
                }
                this.$emit('inputChanged', this.value);
            }, this.name === "Rate*" ? 2000 : 800);
        },

        handleInputChange(value) {
            this.debounceUpdate();
        },
        // onKeyUp(value) {
        //     if (this.name === "Rate*") {
        //         clearTimeout(this.debounceTimeout);
        //         this.debounceTimeout = setTimeout(() => {
        //             this.value = (this.inputType === 'number') && this.value != null && this.value != "" ? parseFloat(this.value).toFixed(2) : this.value;
        //             this.$emit('inputChanged', this.value);
        //         }, 800);
        //     } else {
        //         this.value = this.inputType === 'number' ? this.addComma(this.value) : value.target.value;
        //         this.$emit('inputChanged', this.value);
        //         // console.log(value.target.value)
        //     }

        //     //this.$emit('onKeyUp', this.inputType === 'number' ? this.addComma(this.value) : this.value)
        // },
        addComma(newValue) {
            try {
                if (isNaN(parseFloat(newValue.replace(/,/g, '')))) {
                    return '';
                } else {
                    let commavalue = newValue ? parseFloat(newValue.replace(/,/g, '')).toLocaleString() : '';
                    if (newValue.replace(/,/g, '') > 9999999999999) {
                        this.hasTransError = true;
                        this.transError = "Please provide a valid number.";
                        return commavalue;
                    } else {
                        this.hasTransError = false;
                        this.transError = "";

                        if (this.name === "Rate*") {

                            let formattedValue = parseFloat(commavalue).toFixed(2);
                            return formattedValue;

                        } else {
                            return commavalue;
                        }


                    }
                }

            } catch (e) {
                return newValue
            }
        },
        removeComma(newValue) {
            try {
                return newValue ? parseFloat(newValue.toString().replace(/,/g, '')) : 0;
            } catch (e) {
                return newValue
            }
        },
        forceNumeric(newValue) {
            try {
                return newValue ? newValue.toString().replace(/[^\d.]+/g, '') : 0;
            } catch (e) {
                return newValue
            }
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


<style>
.has-error {
    border: 0.5px solid red !important;
}

.input-height {
    height: 40px;
}

.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #ddd;
    background-image: none
}

.b-calendar-grid-body>div>div>.text-muted {
    color: #111 !important;
    font-weight: bold !important;
}

.form-control::placeholder {
    color: #9CA1AA !important;
}

.form-control {
    font-size: 14px !important;
}
</style>
