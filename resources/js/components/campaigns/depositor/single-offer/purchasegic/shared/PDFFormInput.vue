<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="required" class="required">*</span>

            <a data-toggle="tooltip" v-if="tooltip" data-html="true" :title="tooltiptitle">
                <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
            </a></label>

        <p class="pdf-form-input" :class="{ 'form-placeholder': !inputValue }">{{ inputValue ? inputValue : placeholder }}
        </p>
        <!-- <input :class="{ 'custom-input-error ': error }" v-model="inputValue" :disabled="disabled" class="custom-input"
            :placeholder="placeholder"> -->
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'label', 'currentValue', 'placeholder', 'required', 'tooltip', 'tooltiptitle', 'value', 'disabled', 'isemptycheck', 'minlength', 'maxlength'],
        data() {
            return {
                error: false,
                errorMessage: null,
                inputValue: null
            }
        },
        mounted() {
            if (this.currentValue != null) {
                this.inputValue = this.currentValue
                // console.log(cureent)
                // this.onValueChanged()

            }
        },

        watch: {

            currentValue() {
                if (this.currentValue != null && this.currentValue != undefined) {
                    this.inputValue = this.currentValue
                    // this.onValueChanged()
                    // if (this.input_type == 'timezone') {
                    //     this.inputValue = this.currentValue
                    // }
                }
            }
        }


    }
</script>


<style>
.required {
    color: #5063F4;
}

.pdf-form-input {
    padding: 0;
    margin: 0;
    /* text-decoration: underline; */
    border-bottom: 1px solid #D9D9D9;
    background: #FFF;

    /* Shadow/Elevation 1/E 1 Rest state */
    /* box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05); */
    /* padding: 10px 14px; */
    margin-top: 5px;
    color: #252525;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 500;
    line-height: 150%;
}

.custom-label {

    color: #252525;

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.custom-input {
    border-radius: 999px;
    border: 1px solid #D9D9D9;
    background: #FFF;

    /* Shadow/Elevation 1/E 1 Rest state */
    /* box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05); */
    padding: 10px 14px;
    margin-top: 5px;
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 150%;
    /* text-transform: capitalize; */
    /* 24px */
}

.custom-input-error {
    border: 1px solid #F04438 !important;
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

.form-placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
    /* Firefox */
}

::-ms-input-placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}
</style>