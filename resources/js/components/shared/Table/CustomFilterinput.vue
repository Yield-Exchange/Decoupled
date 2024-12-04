<template>
    <div class="d-flex flex-column">
        <p class="label"> {{ label }}</p>
        <input class="rounded-input" v-model="modelValue" :type="inputType" :placeholder="placeholder"
            v-bind:value="value" v-on:input="$emit('input', $event.target.value.replace(/\,/g, ''))"
            :class="{ darkenText: darkenText, maxerror: maxError, minerror: minError }">
    </div>
</template>


<script>
    import { type } from 'os';

    export default {
        props: ['value', "label", "placeholder", 'type', 'min', 'max'],
        data() {
            return {
                maxError: false,
                minError: false,
                modelValue: '',
                darkenText: false,
                inputType: 'text'
            }
        },


        methods: {
            // Function to check if the value exceeds the maximum
            checkMaximum(value) {
                if (parseInt(value) > parseInt(this.max) && parseInt(this.max) != '') {
                    this.maxError = true;
                } else {
                    this.maxError = false;
                }
                // console.log("maximum ", this.max)
            },
            checkMinimum(value) {
                if (parseInt(value) < parseInt(this.min) && parseInt(this.min) != '') {
                    this.minError = true;
                } else {
                    this.minError = false;
                }
            },
            addComma(newValue) {

                try {
                    if (isNaN(parseFloat(newValue.toString().replace(/,/g, '')))) {
                        return '';
                    } else {
                        let commavalue = newValue ? parseFloat(newValue.toString().replace(/,/g, '')).toLocaleString() : '';
                        if (newValue > 999999999999) {
                            return commavalue;
                        } else {
                            if (this.label === "Rate*") {

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
            }

        },
        watch: {
            modelValue(newValue, oldValue) {
                newValue = newValue.replace(/\,/g, '')
                if (newValue != '')
                    this.darkenText = true
                // console.log(newValue);

                if (this.max != '') {
                    this.checkMaximum(newValue)
                }

                if (this.min != '') {
                    this.checkMinimum(newValue)
                }
                if (this.min == '') {
                    this.minError = false
                }
                if (this.max == '') {
                    this.maxError = false
                }
                this.modelValue = this.addComma(newValue)
            },
            value(newValue, oldValue) {
                // console.log("new value")
                if (newValue == '') {
                    this.modelValue = ''
                }
            }
        }

    }
</script>

<style scoped>
    .label {
        color: var(--gray-700, #344054);
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
    }

    .darkenText {
        color: black !important;
    }

    .rounded-input {
        border-radius: 999px;
        border: 1px solid #D0D5DD;
        background: #FFF;
        box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
        padding: 10px 14px;
        color: var(--gray-500, #667085);
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        width: 140px;
        /* 24px */
    }

    .minerror {
        border: 1px solid red !important;

    }

    .maxerror {
        border: 1px solid red !important;

    }
</style>