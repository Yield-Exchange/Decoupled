<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="showRequired" class="required">*</span>

            <a data-toggle="tooltip" v-if="tooltip" data-html="true" :title="tooltiptitle">
                <img src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
            </a></label>


        <input :class="{ 'custom-input-error ': error, 'disabled-input': disabled }" :maxlength="maxLength"
            v-model="inputValue" :disabled="disabled" @keyup="onValueChanged" class="custom-input"
            :placeholder="placeholder" :style="inputStyle">
        <span v-if="error" class="error ml-2  error-message ">{{ errorMessage }}</span>
        <!-- <button type="button" class="btn btn-secondary" Tooltip with HTML </button> -->
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'label', 'inputStyle', 'ownership', 'currentValue', 'placeholder', 'required', 'tooltip', 'tooltiptitle', 'value', 'disabled', 'isemptycheck', 'minlength', 'maxlength', 'dontshowrequired', 'exceptValue'],
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
            if (this.isemptycheck) {
                this.onValueChanged()
            }
        },
        computed: {
            showRequired() {
                if (this.required) {
                    if (this.dontshowrequired) {
                        return false
                    } else {
                        return true
                    }
                } else {
                    return false
                }
            },
            minLength() {
                if (this.minlength == undefined || this.minlength == null || this.minlength == '') {
                    return 2
                } else {
                    return this.minlength
                }
            },
            maxLength() {
                if (this.maxlength == undefined || this.maxlength == null || this.maxlength == '') {
                    return 200
                } else {
                    return this.maxlength
                }
            }
        },
        methods: {
            isEmptyCheck() {
                // this.onValueChanged()
                if (this.isemptycheck && this.required && this.inputValue == null) {
                    this.errorMessage = `This field is required`
                    this.error = true
                }
            },
            onValueChanged() {

                if (this.sanitizeInput(this.inputValue) === null) {
                    this.error = true
                    this.errorMessage = 'Input contains harmful file extension.'

                    this.$emit('input', null);
                } else {
                    let newValue = this.inputValue
                    if (this.inputValue != null) {
                        this.inputValue = this.input_type === 'number' ? this.addComma(newValue) : newValue;
                        this.inputValue = this.input_type === 'tel' ? this.formatCanadianPhoneNumber(newValue) : newValue;

                        if (this.required && newValue.length < this.minLength) {
                            this.error = true
                            if (this.label != null && this.label.includes("Telephone"))
                                this.errorMessage = `The telephone number is too short`
                            else
                                this.errorMessage = this.label != null ? `The ${this.label.toLowerCase()} is too short` : "Required"
                        } else if (!this.required && this.inputValue != '' && newValue.length < this.minLength) {
                            this.error = true
                            if (this.label != null && this.label.includes("Telephone"))
                                this.errorMessage = `The telephone number is too short`
                            else
                                this.errorMessage = this.label != null ? `The ${this.label.toLowerCase()} is too short` : "Required"
                        } else if (this.input_type == 'number') {
                            this.error = false
                        } else if (this.input_type == "email") {
                            this.validateEmail(newValue)
                        } else if (this.input_type == "url") {
                            if (newValue.length != 0) {
                                if (this.label != null && this.label.toLowerCase().includes("website")) {
                                    this.websiteValidation(newValue)
                                } else {
                                    this.validateLinkedIn(newValue)

                                }
                            } else {
                                this.error = false
                            }
                        } else if (this.input_type == "postal") {
                            if (newValue.length != 0)
                                this.validatePostal(newValue)
                        } else if (this.exceptValue != null && this.exceptValue.length > 0) {
                            let exceptValue = this.exceptValue.map(value => value.toLowerCase())
                            if (exceptValue.includes(newValue.toLowerCase())) {
                                this.error = true
                                this.errorMessage = this.label != null ? `The ${this.label.toLowerCase()} is not available` : "Required"

                            } else {
                                this.error = false

                            }
                        } else if (this.input_type == "percent") {
                            // console.log(this.ownership, ',', newValue, ',truthy', newValue <= (this.ownership != null ? this.ownership : 100))
                            if (newValue >= 25 && newValue <= (this.ownership != null ? this.ownership : 100)) {
                                this.error = false
                            }
                            else {
                                this.error = true
                                this.errorMessage = "Invalid"
                            }

                        } else {
                            this.error = false
                        }
                        if (!this.error) {
                            this.$emit('input', this.inputValue);
                            this.$emit('hasError', false);
                        } else {
                            this.$emit('input', this.inputValue);
                            this.$emit('hasError', true);
                            // this.$emit('errorUpdate', true);
                            // this.$emit('input', null);
                        }
                    } else {
                        this.$emit('input', null);

                    }
                }
            },
            sanitizeInput(input) {
                input = input ? input.toString() : input
                // Check for malicious file extensions
                if (/\.(exe|bat|cmd|msi|jar|js|vbs|ps1|zip|rar|tar)$/i.test(input)) {
                    // Handle or reject the input
                    return null; // Or handle it in an appropriate way
                }

                // Check for suspicious URLs
                if (
                    /(javascript:|data:|<script)/i.test(input)
                ) {
                    // Handle or reject the input
                    return null; // Or handle it in an appropriate way
                }

                // Check for common XSS payloads
                if (/((<\s*script)|(%3Cscript)|(%253Cscript))/i.test(input)) {
                    // Handle or reject the input
                    return null; // Or handle it in an appropriate way
                }

                // Check for command injection payloads
                if (/(;|\||`|\$\(|\${)/i.test(input)) {
                    // Handle or reject the input
                    return null; // Or handle it in an appropriate way
                }

                // Check for SQL injection payloads
                if (/(\b(select|update|delete|insert|drop|alter)\b)/i.test(input)) {
                    // Handle or reject the input
                    return null; // Or handle it in an appropriate way
                }

                // Replace potentially harmful characters with harmless ones
                if (input == null)
                    return ''
                else {
                    return input.replace(/[&<>"']/g, function (match) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;'
                        }[match];
                    });
                }
            },




            validatePostal(input) {
                // Remove any non-alphanumeric characters
                newValue = input.trim()
                const postalRegex = /^[a-zA-Z]\d[a-zA-Z]\d[a-zA-Z]\d$/;
                const inputlength = newValue.trim().length
                this.inputValue = newValue.toUpperCase()
                if (inputlength > 6) {
                    this.error = true
                    this.errorMessage = "Oops , your postal code is too long (Expected 6 characters)"
                } else if (inputlength < 6) {
                    this.error = true
                    this.errorMessage = "Oops , your postal code is too short (Expected 6 characters))"

                } else if (inputlength == 6) {
                    if (postalRegex.test(newValue)) {
                        this.error = false
                        if (!this.error) {
                            this.inputValue = newValue.replace(/^(.{3})(.{1,3})$/, '$1 $2');

                        } else {

                            this.inputValue = newValue.trim();
                        }
                        // this.inputValue.toUpperCase()
                    } else {
                        this.error = true
                        this.errorMessage = "The postal code is invalid "
                    }
                }
            },
            validateEmail(value) {
                const emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
                this.error = emailRegex.test(value) ? false : true;
                if (this.error) {
                    this.errorMessage = "Invalid email address"
                }
                // this.canSubmit()
            },
            websiteValidation(url) {
                // console.log("Website test")
                // let urlPattern = /^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:[0-9]+)?(\/[^\s]*)?$/i;
                if (url) {
                    // let t = urlPattern.test(url);
                    if (!this.validateWebsite(url)) {
                        if (this.label != null && this.label.toLowerCase().includes("website"))
                            this.errorMessage = `Invalid website address.`;
                        else
                            this.errorMessage = `Invalid linkedIn profile.`;
                        // console.log(this.errorMessage)
                        this.error = true
                    } else {
                        this.errorMessage = "";
                        this.error = false
                    }
                } else {
                    this.error = false
                    this.errorMessage = "";
                }
            },
            validateLinkedIn(url) {
                // console.log("Website test")
                var pattern = /^https?:\/\/(?:www\.)?linkedin\.com\/(?:in|profile|company)\/[\w-]+\/?$/;
                if (url) {
                    let t = pattern.test(url);
                    if (!t) {
                        this.errorMessage = `Invalid linkedIn profile.`;
                        this.error = true
                    } else {
                        this.errorMessage = "";
                        this.error = false
                    }
                } else {
                    this.error = false
                    this.errorMessage = "";
                }
            },

            validateWebsite(url) {
                const urlRegex = /^(?:(?:(?:https?|ftp):)?\/\/)?(?:(?:[\w\-.])+\.)+[a-z]{2,}(?::\d{1,5})?(?:\/[^\s]*)?$/i;
                if (urlRegex.test(url)) {
                    let allexceptProtocol;
                    if (url.startsWith("http://")) {
                        allexceptProtocol = url.split("http://")[1];
                    } else if (url.startsWith("https://")) {
                        allexceptProtocol = url.split("https://")[1];
                    } else {
                        allexceptProtocol = url;
                    }

                    let wwwReplaced = allexceptProtocol.replace(/^www\./i, "");
                    const remainingUrlRegex = /^((?!www)[\w-]+\.)+[a-z]{2,}$/i;
                    if (remainingUrlRegex.test(wwwReplaced)) {
                        console.log(wwwReplaced, "Domain Without WWW");
                        return true; // URL is valid
                    } else {
                        return false; // URL is not valid
                    }
                } else {
                    return false;
                }
            },

            addCommas(newvalue) {
                if (newvalue != undefined) {
                    return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } else {
                    return "";
                }

            },
            addComma(newValue) {
                return newValue ? parseFloat(newValue.toString().replace(/,/g, '')).toLocaleString() : '';
            },
            removeComma(newValue) {
                return newValue ? parseFloat(newValue.toString().replace(/,/g, '')) : 0;
            },
            forceNumeric(newValue) {
                return newValue ? newValue.toString().replace(/[^\d.]+/g, '') : 0;
            },
            formatCanadianPhoneNumber(phoneNumber) {
                if (phoneNumber.length > 0) {
                    const cleanedNumber = phoneNumber.replace(/\D/g, '');
                    const formattedNumber = `(${cleanedNumber.substring(0, 3)}) ${cleanedNumber.substring(3, 6)} ${cleanedNumber.substring(6, 10)}`;
                    return formattedNumber;
                }
            }
        },
        watch: {
            isemptycheck() {
                this.isEmptyCheck()
                this.onValueChanged()
            },
            currentValue() {
                if (this.currentValue != null && this.currentValue != undefined) {
                    this.inputValue = this.currentValue
                    this.onValueChanged()
                    if (this.input_type == 'timezone') {
                        this.inputValue = this.currentValue
                    }
                }
            }
        }


    }
</script>


<style scoped>
.required {
    color: #5063F4;
}

.disabled-input {
    background-color: #D9D9D9 !important;
    cursor: not-allowed;
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