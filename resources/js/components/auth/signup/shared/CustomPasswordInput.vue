<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="required" class="required">*</span> </label>
        <input :class="{ 'custom-input-error ': error }" :type="showPassword ? 'text' : 'password'" v-model="inputValue"
            :disabled="disabled" @keyup="validatePassword" class="custom-input" :placeholder="placeholder">
        <span v-if="error" class="error-message ml-2">{{ errorMessages }}</span>
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'label', 'minLength', 'placeholder', 'required', 'tooltip', 'value', 'disabled', 'isemptycheck', 'showPassword', 'matcherror'],
        data() {
            return {
                error: false,
                errorMessages: null,
                inputValue: null,
                customErrors: null
            }
        },

        methods: {
            isEmptyCheck() {
                // this.onValueChanged()
                if (this.isemptycheck && this.required && (this.inputValue == null || this.inputValue == '')) {
                    this.errorMessages = `This field is required`
                    this.error = true
                }
            },

            validatePassword() {
                const p = this.inputValue;
                this.customErrors = [];
                let erromessage = 'Include at least one'
                let lengtherror = null

                if (p.length === 0) {
                    this.$emit('input', null);
                    return;
                }

                if (p.length < 8) {
                    lengtherror = 'Password must be at least 8 characters long';
                    this.customErrors.push(lengtherror)
                }

                if (!/[A-Z]/.test(p) || !/[a-z]/.test(p)) {
                    this.customErrors.push('uppercase and one lowercase letter');
                }

                if (!/\d/.test(p)) {
                    this.customErrors.push('Include at least one number');
                }

                if (!/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(p)) {
                    this.customErrors.push('Include at least one special character');
                }

                this.passwordStrength = [p.length >= 8, /[A-Z]/.test(p) && /[a-z]/.test(p), /\d/.test(p), /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(p)].filter(Boolean).length;

                this.$emit('strengthUpdate', this.passwordStrength)
                if (this.inputValue.length >= this.minLength) {
                    this.error = false
                    this.$emit('input', this.inputValue);
                } else {
                    this.error = true
                    this.errorMessages = `This field has to be more than 8 characters`

                }

            }
        },


        watch: {
            isemptycheck: {
                handler(newValue, oldValue) {
                    if (newValue) {
                        this.isEmptyCheck()
                    } else {
                        if (this.matcherror) {
                            this.error = false
                        }
                        this.errorMessages = "Please Match the password"
                    }
                },
                deep: true
            },
            matcherror: {
                handler(newValue, oldValue) {
                    // console.log("Match Error", newValue);
                    if (!newValue) {
                        this.error = true
                        this.errorMessages = "Please Match the password"
                    } else {
                        this.error = false
                    }
                },
                deep: true
            }

        }
    }
</script>


<style scoped>
.required {
    color: #5063F4;
}

.error-message {
    color: #F04438 !important;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
    /* text-transform: capitalize; */
    /* 142.857% */
}

.custom-input-error {
    border: 1px solid #F04438 !important;
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
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
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