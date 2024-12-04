<template>
    <div class="input-container">
        <b-input-group class="combined-input">
            <b-form-select v-model="selectVal"
                class="currency-select currency-select-border-right currency-select-border-left"
                style="min-width:40px !important;" @change="onChangeSel">
                <option v-for="item in currencyOptions" :key="item.id" :value="item">{{ item.oparatorSymbol
                    }}
                </option>
            </b-form-select>
            <div class="separator"></div>
            <b-form-input type="number" step="0.01" v-model="amount" class="amount-input currency-select-border-right"
                placeholder="Enter amount to Deposit" @keyup="onKeyUp" @change="onKeyUp"></b-form-input>
        </b-input-group>
    </div>
</template>
<style scoped>
    .input-container {
        display: flex;
        align-items: center;
    }

    .currency-select {
        margin: -3px !important;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        width: 20% !important;
    }

    .amount-input {
        margin: -3px !important;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
        width: 80% !important;
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

    .currency-select-border-right {
        border-right: 1px solid #ccc !important;
        border-top: 0px !important;
        border-bottom: 0px !important;
    }

    .currency-select-border-left {
        border-left: 2px solid #ccc !important;
    }
</style>
<script>
    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/PostTableActionButton";
    import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../../utils/commonUtils";

    export default {
        data() {
            return {
                amount: (this.defaultValue != '') ? this.addcommas(this.defaultValue) : '',
                debounceTimeout: null,
                selectVal: this.selectedCurrency
            }
        },
        components: {
            TableActionButton,
            Button,

        },
        methods: {
            addcommas(value) {
                return addCommasToANumber(value);
            },
            onChangeSel(newVal) {

                this.$emit('selectedValue', newVal);
            },
            onKeyUp() {

                const inputNumber = parseFloat(sanitizeAmount(event.target.value));
                if (!isNaN(inputNumber)) {
                    clearTimeout(this.debounceTimeout);
                    this.debounceTimeout = setTimeout(() => {
                        this.amount = addCommasToANumber(inputNumber);
                        this.$emit('inputChanged', this.amount);
                    }, 800);
                } else {

                    event.target.value = '';
                    this.amount = '';
                    clearTimeout(this.debounceTimeout);
                }
            }

        },
        props: ['selectedCurrency', 'currencyOptions', 'inputType', 'defaultValue']
    }
</script>