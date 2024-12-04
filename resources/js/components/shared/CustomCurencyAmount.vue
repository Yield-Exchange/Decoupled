<template>
    <div class="input-container">
        <b-input-group class="combined-input" style="background-color: white;"
            :class="{ 'combined-input-has-error': hasError, 'disabled-bg': disabled }">
            <b-form-select :options="currencyOptions" class="currency-select currency-select-border-left"
                @change="changeCurrency($event)" v-model="scurrecny" :disabled="disabled || currdisabled"
                :class="{ 'currency-select-border-left-has-error': hasError, 'disabled-bg': disabled }"
                style="min-width:40px !important;" :style="`width:${width}% !important;`"></b-form-select>
            <div class="separator"></div>
            <b-form-input v-model="amount" class="amount-input currency-select-border-right"
                :class="{ 'currency-select-border-right-has-error': hasError, 'disabled-bg': disabled }"
                :style="`width:${100 - width}% !important; `" :disabled="disabled"
                :placeholder="placeholder ? placeholder : 'Enter amount to deposit'" @keyup="onKeyUp"></b-form-input>
        </b-input-group>
    </div>
</template>

<script>
import Button from "./Buttons/Button.vue"
import TableActionButton from "./Buttons/TableActionButton.vue";
import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../utils/commonUtils";

export default {
    beforeMount() {
        if (this.selectedCurrency) {
            this.scurrecny = this.selectedCurrency
        }
    },
    data() {
        return {
            amount: (this.defaultValue != '') ? this.addcommas(this.defaultValue) : '',
            debounceTimeout: null,
            scurrecny: 'CAD',
        }
    },
    components: {
        TableActionButton,
        Button,

    },
    computed: {
        width() {
            if (this.selector_width) {
                return Number.parseFloat(this.selector_width)
            } else {
                return 20;
            }
        }
    },
    methods: {
        addcommas(value) {
            return addCommasToANumber(value);
        },
        changeCurrency(value) {
            this.$emit('selectedCurrency', value)
        },
        onKeyUp(event) {
            const inputNumber = parseFloat(sanitizeAmount(event.target.value));
            if (!isNaN(inputNumber)) {
                clearTimeout(this.debounceTimeout);
                this.debounceTimeout = setTimeout(() => {
                    this.amount = addCommasToANumber(inputNumber);
                    this.$emit('inputChanged', this.amount);
                }, 800);
            } else {

                this.$emit('inputChanged', '');
                event.target.value = '';
                this.amount = '';
                clearTimeout(this.debounceTimeout);
            }
        }

    },
    watch: {
        selectedCurrency() {
            this.scurrecny = this.selectedCurrency
        }
    },
    props: ['disabled', 'currdisabled', 'selectedCurrency', 'currencyOptions', 'inputType', 'defaultValue', 'placeholder', 'hasError', 'selector_width']
}
</script>

<style scoped>
/* .has-error {
    border: 0.5px solid red !important;
    border-radius: 20px !important;
    padding: 0px !important;
} */

.input-container {
    display: flex;
    align-items: center;
}

.currency-select {
    margin: -3px !important;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    /* width: 20% !important; */
}

.amount-input {
    margin: -3px !important;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    /* width: 80% !important; */
    border: 0px !important;
}

.form-control:focus {
    box-shadow: none !important;
    outline: none !important;
}

.separator {
    width: 1px;
    height: 100%;
    background-color: #ccc;
    margin: 0 10px;
}

.combined-input {
    border-radius: 20px;
    border: 1px solid #ccc;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}

.combined-input-has-error {
    border-radius: 20px;
    border: 1px solid red !important;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}

.currency-select-border-right {
    border-right: 1px solid #ccc !important;
    border-top: 0px !important;
    border-bottom: 0px !important;
}

.currency-select-border-right-has-error {
    border-right: 1px solid red !important;
    border-top: 0px !important;
    border-bottom: 0px !important;
}

.currency-select-border-left {
    border-top: 0px !important;
    border-bottom: 0px !important;
    border-left: 2px solid #ccc !important;
}

.currency-select-border-left-has-error {
    border-left: 2px solid red !important;
    border-top: 0px !important;
    border-bottom: 0px !important;
}

.disabled-bg {
    background-color: #e9ecef !important;
}
</style>