<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="" v-if="label">{{ label }} <span v-if="required" class="required">*</span>

            <a data-toggle="tooltip" v-if="tooltip" data-html="true" :title="tooltiptitle">
                <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
            </a></label>

        <select :class="{ 'custom-input-error ': error }" name="" id="" class="custom-input text-capitalize"
            v-model="modelValue" @change="selectItem">
            <option style="color: #9CA1AA !important;" disabled>{{
                placeholder + " "
            }}</option>
            <option class="text-capitalize" v-for="(option, index) in options" :key="index" :value="isObject(option, 0)">
                {{ isObject(option, 1).toLowerCase() }}</option>
        </select>
        <span v-if="error" class="error ml-2 text-danger  error-message">{{ errorMessage }}</span>
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'label', 'placeholder', 'options', 'required', 'isemptycheck', 'currentValue', 'tooltiptitle', 'tooltip'],
        data() {
            return {
                modelValue: this.placeholder,
                error: false,
                errorMessage: null,
                showplaceholder: true,
            }
        },
        mounted() {
            if (this.currentValue != '' && this.currentValue != null && this.currentValue != undefined) {
                this.modelValue = this.currentValue
                this.selectItem()
            }
        },
        methods: {
            isEmptyCheck() {
                // console.log('Model Value', this.modelValue, "Placeholder ", this.placeholder)
                if (this.isemptycheck && this.required && (this.modelValue == null || this.modelValue == this.placeholder)) {
                    this.errorMessage = `This field is required`
                    this.error = true
                    // console.log("Called in custom select")
                }
            },
            isObject(value, position) {
                if (typeof value === 'object' && value !== null) {
                    return Object.values(value)[position]
                } else {
                    return value;

                }
            },
            selectItem() {
                this.$emit('input', this.modelValue);
                this.error = false
            }
        },
        watch: {
            isemptycheck() {
                this.isEmptyCheck()
            },
            currentValue() {
                if (this.currentValue != '' && this.currentValue != null && this.currentValue != undefined) {
                    this.modelValue = this.currentValue
                    this.selectItem()
                }
            }
        }
    }

</script>


<style scoped> .custom-label {
     color: #252525;

     /* Yield Exchange Text Styles/Table Body */
     font-family: Montserrat !important;
     font-size: 14px;
     font-style: normal;
     font-weight: 400;
     line-height: normal;
 }

 option {
     background-color: #f0f0f0;
     /* Background color for options */
     color: #333;
     /* Text color for options */
     padding: 8px !important;
 }

 .custom-input {
     border-radius: 999px;
     border: 1px solid #D9D9D9;
     background: #FFF;
     appearance: none;
     /* Shadow/Elevation 1/E 1 Rest state */
     background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M5 7.5L10 12.5L15 7.5' stroke='%23D9D9D9' stroke-width='1.66667' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 15px center;
     background-size: 20px;
     box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
     padding: 10px 14px;
     margin-top: 5px;
     font-family: Montserrat !important;
     font-size: 16px;
     font-style: normal;
     font-weight: 600;
     line-height: 150%;
 }

 .required {
     color: #5063F4;
 }

 .custom-input-error {
     border: 1px solid #FF0101 !important;
 }

 .error-message {
     color: #F04438 !important;
     font-family: Montserrat !important;
     font-size: 14px;
     font-style: normal;
     font-weight: 400;
     line-height: 20px;
     /* 142.857% */
 }

 /* select option:disabled {
     color: grey;
 } */

 select:has(option:checked:disabled) {
     color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
     font-family: Montserrat;
     font-size: 16px;
     font-style: normal;
     font-weight: 400;
     line-height: 150%;
     /* 24px */
 }
</style>