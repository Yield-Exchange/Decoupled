<template>
    <div class="w-100 d-flex flex-column">
        <label class="custom-label" for="">{{ label }} <span v-if="required" class="required">*</span> <img v-if="tooltip"
                src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset=""> </label>
        <input ref="locationsearch" v-model="inputValue" :class="{ 'custom-input-error ': error }" class="custom-input"
            maxlength="200" :placeholder="placeholder">
        <span v-if="error" class="error ml-2 text-danger error-message ">{{ errorMessage }}</span>
    </div>
</template>



<script>
export default
    {
        props: ['input_type', 'label', 'currentValue', 'placeholder', 'required', 'tooltip', 'value', 'disabled', 'isemptycheck', 'tooltip'],
        data() {
            return {
                error: false,
                errorMessage: null,
                inputValue: null
            }
        },
        mounted() {
            if (this.currentValue != null && this.currentValue != undefined && this.currentValue != '') {
                this.$refs.locationsearch.value = this.currentValue
            }
            this.isEmptyCheck()
            const autocomplete = new google.maps.places.Autocomplete(this.$refs["locationsearch"])

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.address_components) {
                    // Handle no results/error
                    return;
                }

                let postalCode, streetAddress, province, city;

                // Iterate through each address component
                place.address_components.forEach(component => {
                    // Check types array to identify specific address components
                    if (component.types.includes("postal_code")) {
                        postalCode = component.long_name;
                    } else if (component.types.includes("route")) {
                        streetAddress = component.long_name;
                    } else if (component.types.includes("administrative_area_level_1")) {
                        province = component.long_name;
                    } else if (component.types.includes("locality")) {
                        city = component.long_name;
                    }
                });
                // this.address1.city = city
                // this.address1.postalCode = postalCode
                // this.address1.province = province
                const data = {
                    'postal_code': postalCode !== undefined ? postalCode : '',
                    'street_address': streetAddress !== undefined ? streetAddress : '',
                    'province': province !== undefined ? province : '',
                    'city': city !== undefined ? city : '',
                    'full_name': this.updateInputValue(place)
                }
                this.$refs.locationsearch.value = this.updateInputValue(place)
                this.inputValue = this.updateInputValue(place)

                this.$emit('updateLocation', data)
                this.error = false
            });
        },
        computed: {

        },
        methods: {
            updateInputValue(place) {
                let concatenatedAddress = place.formatted_address;
                if (place.name) {
                    if (concatenatedAddress.includes(place.name)) {
                        concatenatedAddress
                    } else {
                        concatenatedAddress = `${place.name}, ${concatenatedAddress}`;
                    }
                }
                return concatenatedAddress
            },
            sanitizeInput(input) {
                // Check for malicious file extensions
                if (/\.(exe|bat|cmd|msi|jar|js|vbs|ps1|zip|rar|tar)$/i.test(input)) {
                    // Handle or reject the input
                    return null; // Or handle it in an appropriate way
                }

                // Check for suspicious URLs
                if (
                    /(javascript:|data:|<script)/i.test(input) || // JavaScript or data URLs
                    /(http|https):\/\/[^\s/$.?#].[^\s]*/i.test(input) // HTTP/HTTPS URLs
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

            isEmptyCheck() {
                // this.onValueChanged()
                if (this.isemptycheck && this.required && this.$refs.locationsearch.value == '') {
                    this.errorMessage = `This field is required`
                    this.error = true
                }
            },
        },

        watch: {
            isemptycheck: {
                handler(newValue, oldValue) {
                    // console.log('myObject changed', newValue);

                    this.isEmptyCheck();
                },
                deep: true // Enable deep watching
            },
            currentValue() {
                if (this.currentValue != null && this.currentValue != undefined && this.currentValue != '') {
                    this.$refs.locationsearch.value = this.currentValue
                }
            },
            inputValue() {
                // console.log(this.inputValue)
                if (this.sanitizeInput(this.inputValue) === null) {
                    this.error = true
                    this.errorMessage = 'Input contains harmful file extension.'
                    this.$emit('haserror', true)
                    this.$emit('alternativeAddress', null);
                    // this.$emit('input', null);
                } else {
                    this.error = false
                    this.$emit('haserror', false)
                    this.$emit('alternativeAddress', this.inputValue);
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