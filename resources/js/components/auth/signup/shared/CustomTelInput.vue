<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="required && !dont_show" class="required">*</span>

            <a data-toggle="tooltip" v-if="tooltip" data-html="true" :title="tooltiptitle">
                <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
            </a></label>
        <span style="height: 5px;"></span>

        <vue-phone-number-input :error="!numberValididty" :value=inputValue @update="countryChanged"
            @input="onValueChanged" :required="required" class="mynumber" v-model="inputValue" :only-countries="['CA']"
            :no-example="no_example" :border-radius="border_radius" :preferred-countries="['CA']" :no-flags="noflags"
            default-country-code="CA" color="#252525" error-color="#F04438" />
        <span style="height: 2px;"></span>
        <span v-if="error" class="error ml-2 mt-1 error-message ">{{ errorMessage }}</span>
        <!-- <button type="button" class="btn btn-secondary" Tooltip with HTML </button> -->
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'dont_show', 'no_example', 'border_radius', 'label', 'currentValue', 'placeholder', 'required', 'tooltip', 'tooltiptitle', 'value', 'disabled', 'isemptycheck', 'noflags'],
        data() {
            return {
                error: false,
                errorMessage: null,
                inputValue: null,
                country: null,
                formatInternational: null,
                numberValididty: true
            }
        },
        mounted() {
            if (this.currentValue != null) {
                this.inputValue = this.currentValue
                // console.log(cureent)
                // this.onValueChanged()

            }
        },
        computed: {

        },
        methods: {
            countryChanged(country) {
                this.formatInternational = country.formatInternational
                if (country.countryCallingCode == 1) {
                    if (this.validatePhoneNumber(country.phoneNumber)) {
                        this.numberValididty = true
                    } else {
                        const testnumber = country.phoneNumber.replace(/[()\s]/g, "")
                        if (testnumber.length > 8 && testnumber.length < 12) {
                            this.numberValididty = true
                        } else {
                            this.numberValididty = false
                        }
                    }

                    // console.log("number validity", this.validatePhoneNumber(country.phoneNumber), "phone", country.phoneNumber)

                } else {
                    if (!country.isValid) {
                        if (country.nationalNumber.length >= 8 && country.nationalNumber.length <= 10) {
                            this.numberValididty = true
                        } else
                            this.numberValididty = false
                    } else {
                        this.numberValididty = true
                    }
                }

            },
            validatePhoneNumber(phoneNumber) {
                // Regular expression to match phone numbers optionally starting with (3 digits), 3 digits, and 4 digits
                const regex = /^(?:\(\d{3}\))?\d{3}-\d{4}$/;
                const formattedPhoneNumber = phoneNumber.replace(/\s/g, '');
                return regex.test(formattedPhoneNumber);
            },
            isEmptyCheck() {
                // this.onValueChanged()
                if (this.isemptycheck && this.required && this.inputValue == null) {
                    this.errorMessage = `This field is required`
                    this.error = true
                    this.$emit('hasError', true);
                }
                if (this.isemptycheck && this.required && this.inputValue != null && this.inputValue.length < 10 && !this.numberValididty) {
                    this.errorMessage = `Provide a valid telephone number`
                    this.error = true
                    this.$emit('hasError', true);
                }
            },
            onValueChanged() {
                let newValue = this.inputValue
                // console.log(newValue, this.country)
                if (this.inputValue != null) {
                    if (this.required && !this.numberValididty) {
                        this.errorMessage = `Provide a valid telephone number`
                        this.error = true
                    } else {
                        this.error = false
                    }
                    if (!this.error) {
                        if (this.formatInternational != null) {
                            this.$emit('input', this.formatInternational);
                            this.$emit('hasError', false);
                        } else {
                            this.$emit('input', this.inputValue);
                            this.$emit('hasError', false);
                        }
                    } else {
                        this.$emit('input', this.formatInternational);
                        this.$emit('hasError', true);
                        this.error = true
                        // this.$emit('errorUpdate', true);
                        // this.$emit('input', null);
                    }
                } else {
                    this.$emit('input', null);

                }
            },
        },
        watch: {
            isemptycheck() {
                this.isEmptyCheck()
                // this.onValueChanged()
            },
            currentValue() {
                if (this.currentValue != null && this.currentValue != undefined) {
                    this.inputValue = this.currentValue
                }
            },
            inputValue() {
                this.onValueChanged()
            }
        }


    }
</script>

<style>
.mynumber .country-selector__input {
    border: none !important;
    border: solid 1px #ccc !important;
    border-radius: 999px 0 0 999px !important;
    height: 45px !important;
}

.mynumber .input-tel__input {
    border: none !important;
    border: solid 1px #ccc !important;
    border-radius: 0 999px 999px 0 !important;
    height: 45px !important;
    font-family: Montserrat !important;
    font-weight: 600 !important;
}

.error {}

.mynumber .input-tel__input::placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
    /* Firefox */
}

.mynumber .input-tel__input::-ms-input-placeholder {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA);
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}
</style>
<style scoped>
.required {
    color: #5063F4;
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
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
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

::placeholder {
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