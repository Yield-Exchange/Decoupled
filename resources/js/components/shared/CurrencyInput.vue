<template>

    <div v-if="nocurrency">
        <b-form-input v-model="amount" class="input-height rounded-field form-input-text"
            :class="{ 'disabled-bg': disabled, 'has-error': hasErrorValidator }" :disabled="disabled"
            pattern="[A-Za-z0-9]" style="border-radius: 25px;outline:none; box-shadow: none;"
            :maxlength="maxlength ? maxlength : 20"
            :placeholder="placeholder ? placeholder : 'Enter 10M for 10,000,000 (Ten million)'" :style="inputStyle"
            @focus="removeItemsInBrackets()" @blur="handleInput"></b-form-input>
    </div>
    <div v-else>
        <div class="input-container">
            <b-input-group class="combined-input" style="background-color: white;"
                :class="{ 'combined-input-has-error': hasErrorValidator, 'disabled-bg7': disabledcurrency }">
                <b-form-select :options="currencyOptions" class="currency-select currency-select-border-left"
                    @change="changeCurrency($event)" v-model="scurrecny" :disabled="disabledcurrency"
                    :class="{ 'currency-select-border-left-has-error': hasErrorValidator, 'disabled-bg': disabledcurrency }"
                    style="min-width:40px !important;" :style="`width:${width}% !important;`"></b-form-select>
                <div class="separator"></div>
                <b-form-input v-model="amount" class="amount-input currency-select-border-right"
                    :class="{ 'currency-select-border-right-has-error': hasErrorValidator, 'disabled-bg': disabled }"
                    :style="`width:${100 - width}% !important; `" :disabled="disabled"
                    :maxlength="maxlength ? maxlength : 20"
                    :placeholder="placeholder ? placeholder : 'Enter 10M for 10,000,000 (Ten million)'"
                    @focus="removeItemsInBrackets()" @paste="handleInput" @blur="handleInput"></b-form-input>
            </b-input-group>
        </div>
    </div>
</template>

<script>
import { addCommasToANumber, formatNumberAbbreviatedFullDescription, sentenceCase, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../utils/commonUtils";

export default {
    props: ['disabled', 'disabledcurr', 'selectedCurrency', 'inputStyle', 'currencyOptions', 'inputType', 'maxlength', 'defaultValue', 'placeholder', 'hasError', 'selector_width', 'allownull', 'nocurrency'],
    beforeMount() {
        if (this.selectedCurrency) {
            this.scurrecny = this.selectedCurrency
        }
        if (this.defaultValue)
            this.calculateValue(this.defaultValue.toString())
    },
    data() {
        return {
            amount: (this.defaultValue != '') ? this.addcommas(this.defaultValue) : '',
            debounceTimeout: null,
            scurrecny: 'CAD',
            formattedValue: null,
            error: false
        }
    },
    computed: {
        disabledcurrency() {
            if (this.disabled || this.disabledcurr) {
                return true
            } else {
                return false
            }
        },
        width() {
            if (this.selector_width) {
                return Number.parseFloat(this.selector_width)
            } else {
                return 20;
            }
        },
        hasErrorValidator() {
            if (this.hasError || this.error) {
                return true
            } else {
                return false
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
        },
        removeItemsInBrackets() {
            if (this.amount)
                this.amount = this.amount.toString().replace(/\(.*?\)/g, '').trim();
        },
        calculateValue(value) {
            const processedValue = value
                .replace(/,/g, '')
                .split(' ')[0];


            // Regex to match numbers with optional M or B
            const regex = /^(\d+(\.\d+)?)([MB])?$/i;
            if (regex.test(processedValue)) {

                const match = processedValue.match(regex);
                if (match) {
                    let number = parseFloat(match[1]);

                    // Handle suffixes
                    if (match[3]) {
                        switch (match[3].toUpperCase()) {
                            case 'M':
                                number *= 1_000_000;
                                number = Math.round(number)
                                break;
                            case 'B':
                                number *= 1_000_000_000;
                                number = Math.round(number)
                                break;
                        }
                    }
                    if (parseFloat(number) > 999999999999) {
                        this.error = true
                        this.$emit('currencyError', 'Invalid amount provided');
                        this.$emit('inputChanged', number);
                        // console.log('Console 9');

                    } else {
                        this.$emit('currencyError', null);
                        this.$emit('inputChanged', number);
                        // console.log('Console 8');

                        if (number > 1000)
                            this.amount = this.addcommas(number) + ' (' + formatNumberAbbreviatedFullDescription(number) + ')'
                        else {
                            this.amount = this.addcommas(number)
                        }
                        this.error = false
                        // this.$emit('currencyError', null);
                    }

                }
                else {
                    // console.log(value, ',', value != null, ',', value != '', ',', !this.allownull)
                    if ((value != null || value != '') && !this.allownull) {
                        this.error = true
                        this.$emit('currencyError', 'Invalid amount provided');
                        this.$emit('inputChanged', '');
                        this.inputValue = '';
                        this.formattedValue = '';
                        // console.log('Console 5');

                    } else {
                        this.$emit('inputChanged', '');
                        // console.log('Console 4');
                    }
                }
            } else {
                if (!this.allownull) {
                    this.$emit('currencyError', 'Invalid amount provided');
                    this.error = true
                    this.$emit('inputChanged', '');
                    // console.log('Console 3');
                    this.inputValue = '';
                    this.formattedValue = '';

                } else {
                    this.$emit('currencyError', null);
                    this.inputValue = '';
                    this.formattedValue = '';
                    this.$emit('inputChanged', '');
                    this.error = false
                    // console.log('Console 2');

                }

            }
        },
        handleInput(event) {
            // console.log(event, "Events")
            const target = event.target;
            const value = String(target.value.replace(/\s+/g, '')).trim();
            // console.log(value, 'value')
            this.calculateValue(value)
        },
    },
    watch: {
        selectedCurrency() {
            this.scurrecny = this.selectedCurrency
        },
        defaultValue() {
            if (this.defaultValue)
                this.calculateValue(this.defaultValue.toString())
        }
    }
}
</script>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

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
    /* font-family: 'Montserrrat' !important; */
    font-weight: 400;
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
    background-color: #f5f5f5 !important;
}
</style>