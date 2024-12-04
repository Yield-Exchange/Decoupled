<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="required" class="required">*</span> </label>
        <input :min="mindate" :max="maxdate" type="date" v-model="inputValue" @change="onValueChanged"
            class="custom-input" :placeholder="placeholder"
            :class="{ 'custom-input-not-empty': inputValue, 'has-error': error }">
        <span v-if="error" class="error ml-2 text-danger">{{ errorMessage }}</span>
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'min', 'max', 'label', 'placeholder', 'required', 'tooltip', 'value', 'currentValue', 'isemptycheck'],
        data() {
            return {
                error: false,
                errorMessage: null,
                inputValue: null,
                selected: false,
                maxdate: null,
                mindate: null
            }
        },
        mounted() {
            if (this.min != undefined && this.min != null) {
                this.mindate = new Date(this.min).toISOString().split('T')[0]
            }
            if (this.max != undefined && this.max != null)
                this.maxdate = new Date(this.max).toISOString().split('T')[0]

        },

        methods: {

            onValueChanged() {
                this.error = false
                this.$emit('input', this.inputValue);
            },
            isEmptyCheck() {
                // this.onValueChanged()
                if (this.isemptycheck && this.required && this.inputValue == null) {
                    this.errorMessage = `This field is required`
                    this.error = true
                }
            },
        },
        watch: {
            isemptycheck() {
                this.isEmptyCheck()
            },
            max() {
                if (this.max != undefined && this.max != null) {
                    this.maxdate = this.max.split('T')[0]
                }
            },
            min() {
                if (this.min != undefined && this.min != null) {
                    this.mindate = this.min.split('T')[0]
                }
            }


        }


    }
</script>


<style scoped>
.required {
    color: #5063F4;
}

.custom-label {

    color: #252525;

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.custom-input {
    border-radius: 999px;
    border: 1px solid #D9D9D9;
    background: #FFF;

    /* Shadow/Elevation 1/E 1 Rest state */
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
    font-family: Montserrat !important;
    font-weight: 400;
    font-size: 16px;
    font-style: normal;
    color: #9CA1AA;
}

.custom-input-not-empty {
    color: black;
    font-weight: 400;
    line-height: 150%;
}

.custom-input-unselected {
    font-weight: 400;
    line-height: 150%;
}

.custom-input::placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
    /* Firefox */
}

.custom-input::-ms-input-placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}
</style>