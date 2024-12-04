<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="required" class="required">*</span> </label>
        <input :max="maxdate" type="date" v-model="inputValue" @change="onValueChanged" class="custom-input"
            :class="{ 'custom-input-not-empty': selected }" placeholder="Select Date of Incorporation ">
        <span v-if="error" class="error ml-2 text-danger">{{ errorMessage }}</span>
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'label', 'placeholder', 'required', 'tooltip', 'value', 'currentValue'],
        data() {
            return {
                error: false,
                errorMessage: null,
                inputValue: null,
                maxdate: new Date().toISOString().split('T')[0],
                selected: false
            }
        },

        methods: {

            onValueChanged() {
                this.$emit('input', this.inputValue);
                this.selected = true
            }
        },
        watch: {
            currentValue() {
                if (this.currentValue != '' && this.currentValue != null && this.currentValue != undefined) {
                    this.inputValue = this.currentValue
                    this.selected = true
                    // console.log("Date", this.currentValue)
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
    font-family: Montserrat;
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
    font-size: 16px;
    font-style: normal;
    color: #9CA1AA;
}

.custom-input-not-empty {
    color: #252525;
    font-weight: 600;
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