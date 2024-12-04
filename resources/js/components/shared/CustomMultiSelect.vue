<template>
    <div class="w-100 d-flex flex-column">
        <!-- <label class="custom-label" for="">{{ label }} <span v-if="required" class="required">*</span></label> -->
        <MultiSelect :class="{ 'custom-input-error ': haserror }" class="custom-input1" :placeholder="placeholder"
            v-model="selectedValue" :options="selectoptions" @select="mySelectEvent($event)"
            @change="updateSelectedValue" :settings="setting">
        </MultiSelect>
        <span v-if="error" class="error ml-2 text-danger ">{{ errorMessage }}</span>
    </div>
</template>



<script>
import Select2 from 'v-select2-component';
export default
    {
        props: ['input_type', 'label', 'maximumSelectionLength', 'placeholder', 'required', 'isemptycheck', 'currentValue', 'options', 'haserror'],
        components: {
            MultiSelect: Select2
        },
        mounted() {
            if (this.currentValue) {
                this.selectedValue = this.currentValue
            }
        },
        data() {
            return {
                setting: {
                    allowClear: true,
                    multiple: true,
                    maximumSelectionLength: this.maximumSelectionLength ? this.maximumSelectionLength : 0
                },
                error: false,
                errorMessage: null,
                selectedValue: null,
                // selectedValue: ['GIC Acquisition']
            }
        },
        computed: {

            selectoptions() {
                return this.options
            }
        },
        methods: {
            updateSelectedValue(newValue) {
                if (newValue.length > 1) {
                    const citem = '0';
                    newValue = newValue.filter(item => item !== citem);
                }
                // console.log(newValue, 'new value')
                this.selectedValue = newValue
            },
            mySelectEvent(value) {
                // console.log('mySelectEvent ', value)
                if (value?.id && value?.id == 0) {
                    this.selectedValue = ['0']
                }
            },
            isEmptyCheck() {
                if (this.isemptycheck && this.required) {
                    if (this.selectedValue == null || this.selectedValue.length == 0) {
                        this.errorMessage = `This field is required`
                        this.error = true
                    } else {
                        this.error = false
                    }
                    // console.log("Called in custom select")
                }
            },
            validateCheck() {
                if (this.required) {
                    if (this.selectedValue == null || this.selectedValue.length == 0) {
                        this.errorMessage = `This field is required`
                        this.error = true
                    } else {
                        this.error = false
                    }
                    // console.log("Called in custom select")
                }
            },
        },
        watch: {
            selectedValue() {
                this.$emit('selectChanged', this.selectedValue)
                // console.log('selected values ', )
                this.validateCheck()
            },
            isemptycheck() {
                this.isEmptyCheck()
            }
        }
    }
</script>

<style>
.select2-results__option[aria-selected=true] {
    display: none;
}

.required {
    color: #5063F4;
}

.select2-selection__choice {
    background: #EFF2FE !important;
    color: #5063F4 !important;
    border: none !important;
    padding: 3px;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: 150%;
    padding: 10px;
}

.select2-selection__choice span {
    color: #5063F4 !important;

}

.select2-selection--multiple .select2-selection__clear {
    color: #5063F4 !important;
}

.select2-selection {
    border-radius: 999px !important;
    border: 1px solid #D9D9D9;
    background: #FFF;
    appearance: none;
    /* Shadow/Elevation 1/E 1 Rest state */
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M5 7.5L10 12.5L15 7.5' stroke='%23D9D9D9' stroke-width='1.66667' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 15px center;
    background-size: 20px;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    /* padding: 0px 4px; */
    /* margin-top: 5px; */
}

.select2-selection__rendered {
    padding: 8px !important;
}

.custom-input-error {
    border-radius: 999px !important;
    border: 1px solid #FF0101 !important;
}

.select2-search__field::placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
    /* Firefox */
}

.select2-search__field::-ms-input-placeholder {
    color: #9CA1AA;
    font-family: Montserrat !important;
    ;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}
</style>
<style scoped>
.error {
    color: #F04438 !important;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
}

.custom-label {
    color: #252525;

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    margin-bottom: 5px;
}

option {
    background-color: #f0f0f0;
    /* Background color for options */
    color: #333;
    /* Text color for options */
    padding: 8px !important;
}
</style>