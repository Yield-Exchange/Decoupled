<template>
    <div class="text-left">
        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <b-row>
                <b-col cols="12" style="display:inline;padding-right: 0;width: 97%">
                    <b-form-file placeholder="Choose a profile image or drop it here..."
                        drop-placeholder="Drop profile image here..." accept="image/*" class="font-13 custom-file-yie"
                        @change="loadImage($event)" v-model="image_uploaded">
                    </b-form-file>
                    <b-alert v-if="profileImageErrors != ''" show variant="danger" class="form-alert"
                        style="margin-top:15px;">{{ profileImageErrors }}</b-alert>

                </b-col>
                <span style="display:inline;
                    width:3%;
                    padding:0;
                    padding-left: 5px;
                    margin-top: 2.24%;" v-b-tooltip.hover
                    title=" Max. Image size: 500 x 500 ; Allowable image types: png, jpg">
                    <b-icon icon="exclamation-circle-fill" variant="primary">
                    </b-icon>
                </span>
            </b-row>
            <b-row>
                <b-col cols="11">
                    <cropper ref="cropper" class="cropper" :src="image.src" :stencil-props="{
                        handlers: {},
                        movable: false,
                        scalable: false,
                        aspectRatio: 1,
                    }" :stencil-size="{
    width: 500,
    height: 500
}" :resize-image="{
    adjustStencil: false
}" image-restriction="stencil" />
                    <b-button variant="danger" size="sm" v-if="image.src" @click="this.reset" style="margin-top:10px">
                        <b-icon icon="x" variant="light"></b-icon>
                        Remove
                    </b-button>

                </b-col>
            </b-row>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK' && !this.fromUserProfile">
            <label v-if="showLabel" for="institution_name">Institution Name:</label>
            <v-select label="name" :options="this.getFiLists" class="font-13" placeholder="Select Institution*"
                style="color: #212529;font-weight: 400;" id="institution_name" :value="institution_name"
                @input="$emit('update:institution_name', $event.name)"
                @map-keydown="checkFInstituion()"
                :class="{ 'verror': fiErrors }"
                >
            
            </v-select>
            <b-alert v-if="fiErrors" show variant="danger" class="form-alert">{{ fiErrors
                }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK'">
            <label v-if="showLabel" for="institution_type">Institution Type:</label>
            <v-select label="description" :options="this.getFiTypes" class="font-13" id="institution_type"
                placeholder="Select Institution Type*" style="color: #212529;font-weight: 400;"
                :value="institution_type ? this.fi_description(institution_type) : ''"
                @map-keydown="checkInstituionType()"
                :class="{ 'verror': institutionTypeErrors }"
                @input="$emit('update:institution_type', $event)">
            </v-select>
                <b-alert v-if="institutionTypeErrors" show variant="danger" class="form-alert">{{ institutionTypeErrors
                }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'DEPOSITOR' && !showLabel">
            <label v-if="showLabel" for="institution_name">Organization Name:</label>
            <b-form-input maxlength="51" placeholder="Organization Name*" class="font-13" id="institution_name"
                :value="institution_name" :readonly="this.fromUserProfile" @keyup="compareInstitutionStringLength"
                @input="$emit('update:institution_name', $event)" :class="{ 'verror': institutionErrors }">
            </b-form-input>
            <b-alert v-if="institutionErrors" show variant="danger" class="form-alert">{{ institutionErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'DEPOSITOR'">
            <label v-if="showLabel" for="description">Organization Description:</label>

            <div class="d-flex">
                <b-form-textarea minlength="30" placeholder="Describe your Organization." class="font-13" id="description"
                    :value="description" v-model="thisdescription" @keyup="checkOrgDescription"
                    @input="$emit('update:description', $event)" :class="{ 'verror': descriptionErrors }" rows="5">
                </b-form-textarea>
                <span style="display:inline;width:3%;padding:0;padding-left: 5px;margin-top: 2.24%;"
                    v-b-tooltip.hover.right="{ variant: 'info' }"
                    title="ABC Company, founded in 2005, designs, manufactures, and distributes electronic products, including smartphones, tablets, laptops, and home entertainment systems. We strive to provide innovative and high-quality products while maintaining a positive impact on the environment and communities. Our target market is tech-savvy consumers in Canada, and we offer after-sales support, repair services, and accessories for our products.">
                    <b-icon icon="exclamation-circle-fill" variant="primary">
                    </b-icon>
                </span>
            </div>
            <b-alert v-if="descriptionErrors" show variant="danger" class="form-alert">{{ descriptionErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="address_line_1">Address Line 1:</label>
            <b-form-input maxlength="101" placeholder="Address Line 1*" class="font-13" id="address_line_1"
                :value="address_line_1" @keyup="compareAddress1StringLength" @input="$emit('update:address_line_1', $event)"
                :class="{ 'verror': address1Errors }">
            </b-form-input>
            <b-alert v-if="address1Errors" show variant="danger" class="form-alert">{{ address1Errors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="address_line_2">Address Line 2:</label>
            <b-form-input maxlength="101" placeholder="Address Line 2" class="font-13" id="address_line_2"
                :value="address_line_2" @keyup="compareAddress2StringLength"
                @input="$emit('update:address_line_2', $event)">
            </b-form-input>
            <b-alert v-if="address2Errors" show variant="danger" class="form-alert">{{ address2Errors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="city">City:</label>
            <b-form-input maxlength="51" placeholder="City*" class="font-13" id="city" :value="city"
                @keyup="compareCityStringLength" @input="$emit('update:city', $event)" :class="{ 'verror': cityErrors }">
            </b-form-input>
            <b-alert v-if="cityErrors" show variant="danger" class="form-alert">{{ cityErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="province">Province:</label>
            <v-select ref="province" :options="JSON.parse(this.provinces)" class="font-13" placeholder="Province*"
                id="province" style="color: #212529;font-weight: 400;" :value="province"
                @input="$emit('update:province', $event)" @map-keydown="checkProvince()"
                :class="{ 'verror': provinceErrors }">
            </v-select>
            <b-alert v-if="provinceErrors" show variant="danger" class="form-alert">{{ provinceErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="postal_code">Postal Code:</label>
            <b-form-input maxlength="11" placeholder="Postal Code*" class="font-13" :value="postal_code" id="postal_code"
                @keyup="comparePostalStringLength" @input="$emit('update:postal_code', $event)"
                :class="{ 'verror': postalErrors }">
            </b-form-input>
            <transition>
                <b-alert v-if="postalErrors" show variant="danger" class="form-alert">{{ postalErrors }}</b-alert>
            </transition>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="telephone">Telephone:</label>
            <!--            <b-form-input placeholder="Telephone*" maxlength="13" class="font-13" :value="telephone" id="telephone"-->
            <!--                @keyup="compareTelephoneStringLength" @input="$emit('update:telephone', $event)"-->
            <!--                :class="{ 'verror': telephoneErrors }">-->
            <!--            </b-form-input>-->
            <vue-phone-number-input :value="telephone" id="telephone" @keyup="compareTelephoneStringLength"
                @input="$emit('update:telephone', $event)" :only-countries="['CA']" :preferred-countries="['CA']"
                default-country-code="CA" :class="{ 'verror': telephoneErrors }" placeholder="Telephone" />
            <b-alert v-if="telephoneErrors" show variant="danger" class="form-alert">{{ telephoneErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3">
            <label v-if="showLabel" for="website">Website:</label>
            <b-form-input placeholder="Website" maxlength="51" class="font-13" :value="website" id="website"
                @keydown="compareWebsiteStringLength" @input="$emit('update:website', $event)"
                :class="{ 'verror': websiteErrors }">
            </b-form-input>
            <b-alert v-if="websiteErrors" show variant="danger" class="form-alert">{{ websiteErrors }}</b-alert>
        </b-col>
        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="this.organizationType.toUpperCase() === 'DEPOSITOR'">
            <label v-if="showLabel" for="naics_code">NAICS code 4 digits:</label>
            <v-select label="code_description" :options="this.getNaicsCodes" class="font-13"
                :placeholder="'NAICS code 4 digits*'" id="naics_code" style="color: #212529;font-weight: 400;"
                :value="naics_code ? naics_code.code_description : ''" @input="$emit('update:naics_code', $event)"
                @change="checkNaics()" :class="{ 'verror': naicsErrors }">
            </v-select>
            <b-alert v-if="naicsErrors" show variant="danger" class="form-alert">{{ naicsErrors }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'DEPOSITOR'">
            <label v-if="showLabel" for="potential_deposit">Total potential yearly deposits:</label>
            <v-select label="band" :options="this.getPotentialDeposits" class="font-13"
                placeholder="Total potential yearly deposits*" id="potential_deposit"
                style="color: #212529;font-weight: 400;" :value="potential_deposit ? potential_deposit.band : ''"
                @input="$emit('update:potential_deposit', $event)" @change="checkPotentialDeposit"
                :class="{ 'verror': potentialDepositsErrors }">
            </v-select>
            <b-alert v-if="potentialDepositsErrors" show variant="danger" class="form-alert">{{ potentialDepositsErrors
            }}</b-alert>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK'">
            <label v-if="showLabel" for="potential_deposit">Digital Account Opening:</label>
            <v-select label="digital_account_opening" :options="['Available', 'Not Available']" class="font-13"
                placeholder="Digital Account Opening*" id="digital_account_opening" style="color: #212529;font-weight: 400;"
                :value="digital_account_opening" @input="$emit('update:digital_account_opening', $event)">
            </v-select>
        </b-col>

        <b-col sm="12" class="pr-0 pl-0 mb-3"
            v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK'">
            <label v-if="showLabel" for="wholesale_deposit_portfolio_id">Wholesale Deposit Portfolio:</label>
            <v-select label="band" :options="this.getWholesaleDeposits" class="font-13"
                placeholder="Wholesale Deposit Portfolio*" id="wholesale_deposit_portfolio_id"
                style="color: #212529;font-weight: 400;"
                :value="wholesale_deposit_portfolio_id ? wholesale_deposit_portfolio_id.band : ''"
                @input="$emit('update:wholesale_deposit_portfolio_id', $event)" @change="checkWholesaleDeposit"
                :class="{ 'verror': wholesaleDepositsErrors }">
            </v-select>
            <b-alert v-if="wholesaleDepositsErrors" show variant="danger" class="form-alert">{{
                wholesaleDepositsErrors
            }}</b-alert>
        </b-col>

    </div>
</template>
<style>
.vue-preview__wrapper,.vue-preview{
    display: none !important
}
.vue-advanced-cropper__foreground{
    width:500px !important;
}
.cropper {
    max-height: 500px;
    max-width: 500px;
    background: #DDD;
    width:500px !important;
}

.font-13,
.vs__search {
    font-size: 13px
}

.vs__search {
    font-weight: 100
}

label {
    font-size: 13px;
    margin-bottom: 0;
}

.form-control {
    margin-top: 4px;
}

.form-alert {
    padding: 0.1rem 1rem;
    font-size: .8em;
    font-weight: 400;
}

.verror {
    border: 1px solid rgb(255, 0, 0);
    border-radius: 5px;
}
</style>
<script>
// This function is used to detect the actual image type,
function getMimeType(file, fallback = null) {
    const byteArray = (new Uint8Array(file)).subarray(0, 4);
    let header = '';
    for (let i = 0; i < byteArray.length; i++) {
        header += byteArray[i].toString(16);
    }
    switch (header) {
        case "89504e47":
            return "image/png";
        case "47494638":
            return "image/gif";
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
        case "ffd8ffe3":
        case "ffd8ffe8":
            return "image/jpeg";
        default:
            return fallback;
    }
}

import GeneralNoInteractionError from "../shared/messageboxes/GeneralNoInteractionError.vue";

export default {
    props: ['organizationType', 'provinces', 'naicsCodes', 'potentialDeposits', 'wholesaleDepositPortfolios', 'fiTypes', 'fis',
        'institution_name', 'institution_type', 'province', 'naics_code', 'address_line_1', 'description', 'descriptionError', 'address_line_2', 'city', 'postal_code',
        'telephone', 'website', 'persistImage', 'potential_deposit', 'wholesale_deposit_portfolio_id', 'profile_image', 'fromUserProfile', 'showLabel', 'digital_account_opening'],
    data() {
        console.log(this.persistImage, "persistImage");
        let thisimage = {};
        if (this.persistImage != null && this.persistImage != "" && this.persistImage != undefined) {
            if (Object.keys(this.persistImage).length > 0) {

                let thisblob = URL.createObjectURL(this.persistImage);
                let reader = new FileReader();
                thisimage = {
                    src: thisblob,
                    type: this.persistImage?.type,
                };

            }
        }


        return {
            thisdescription: this.description,
            profileImageErrors: '',
            institutionErrors: '',
            postalErrors: '',
            telephoneErrors: '',
            websiteErrors: '',
            address1Errors: '',
            address2Errors: '', descriptionErrors: '',
            cityErrors: '',
            naicsErrors: '',
            potentialDepositsErrors: '',
            provinceErrors: '',
            wholesaleDepositsErrors: '',
            image: (thisimage != null) ? thisimage : {
                src: null,
                type: null
            },
            image_uploaded: null,

            institutionTypeErrors: '',
            fiErrors: '',
        }
    },
    mounted() {

    },
    computed: {
        getFiTypes() {
            return JSON.parse(this.fiTypes);
        },
        getFiLists() {
            return JSON.parse(this.fis);
        },
        getPotentialDeposits() {
            return JSON.parse(this.potentialDeposits);
        },
        getWholesaleDeposits() {
            return JSON.parse(this.wholesaleDepositPortfolios);
        },
        getNaicsCodes() {
            let naicsCodes = JSON.parse(this.naicsCodes);
            let organizationType = this.organizationType;
            return naicsCodes && naicsCodes.filter(function (el) {
                return el.type === organizationType.toUpperCase();
            });
        }
    },
    created: function () {
        this.$parent.$on('submit', this.errorCheck); ``
    },
    methods: {
        fi_description(id) {
            var description;
            this.getFiTypes.forEach(item => {
                if (item.id == id) {
                    description = item.description;
                }
            });
            return description;
        },
        compareInstitutionStringLength(e) {

            if (!this.institution_name && e?.keyCode !== 9) {
                this.institutionErrors = "Institution Name is required.";
            } else if (this.institution_name.length > 50) {
                this.institutionErrors = "Institution Name cannot be more than 50 characters.";
            } else {
                this.institutionErrors = "";
            }
        },
        comparePostalStringLength(e) {
            if (!this.postal_code && e?.keyCode !== 9) {
                this.postalErrors = "Postal is required.";
            } else if (this.postal_code.length > 10) {
                this.postalErrors = "Postal Code cannot be more than 10 characters.";
                // setTimeout(e() => { this.postalErrors = ""; }, 4000);
            } else {
                this.postalErrors = "";
            }
        },
        compareTelephoneStringLength(e) {
            if (!this.telephone && e?.keyCode !== 9) {
                this.telephoneErrors = "Telephone is required.";
            } /*else if (this.telephone.length > 10) {
                this.telephoneErrors = "Telephone cannot be more than 10 characters.";
                // setTimeout(() => { this.telephoneErrors = ""; }, 4000);
            } */else {
                this.telephoneErrors = "";
            }
        },
        compareCityStringLength(e) {
            if (!this.city && e?.keyCode !== 9) {
                this.cityErrors = "City is required.";
            } else if (this.city.length > 50) {
                this.cityErrors = "City cannot be more than 50 characters.";
                // setTimeout(() => { this.cityErrors = ""; }, 4000);
            } else {
                this.cityErrors = "";
            }
        },
        compareWebsiteStringLength(e) {
            if (this.website && this.website.length > 50 && e?.keyCode !== 9) {
                this.websiteErrors = "Website cannot be more than 50 characters.";
            } else {
                this.websiteErrors = "";
            }
        },
        compareAddress1StringLength(e) {
            if (!this.address_line_1 && e?.keyCode !== 9) {
                this.address1Errors = "Address One is required.";
            } else if (this.address_line_1.length > 100) {
                this.address1Errors = "Address Line 1 cannot be more than 100 characters.";
                //setTimeout(() => { this.address1Errors = ""; }, 4000);
            } else {
                this.address1Errors = "";
            }
        },
        checkOrgDescription(e) {
            console.log(e.target.value);
            this.thisdescription = e.target.value;

            if (this.thisdescription != null && this.thisdescription != "" && this.thisdescription != undefined) {
                if (this.thisdescription.length < 30) {
                    this.descriptionErrors = "Organization summary can not be less than 30 characters";
                    this.$emit('update:descriptionError', "Organization summary can not be less than 30 characters");
                } else {
                    this.descriptionErrors = "";
                    this.$emit('update:descriptionError', "");
                }
            } else {
                this.descriptionErrors = "";
            }
            //  if (!this.description && e?.keyCode !== 9) {
            //    this.descriptionErrors = "Organization description Is required to be 30 and above characters.";
            // } else if (this.description.length < 30) {
            //   this.descriptionErrors = "Organization summary can not be less than 30 characters";
            // } else {
            //  this.descriptionErrors = "";
            //}
        },
        compareAddress2StringLength(e) {
            if (this.address_line_2 && this.address_line_2.length > 100 && e?.keyCode !== 9) {
                this.address2Errors = "Address Line 2 cannot be more than 100 characters.";
            } else {
                this.address2Errors = "";
            }
        },
        checkProvince(e) {
            if (!this.province && e?.keyCode !== 9) {
                this.provinceErrors = "Province is required.";
                // setTimeout(() => { this.provinceErrors = ""; }, 4000);
            } else {
                this.provinceErrors = "";
            }
        },
        checkInstituionType(e) {
            if (!this.institution_type && e?.keyCode !== 9) {
                this.institutionTypeErrors = "Institution type is required.";
                // setTimeout(() => { this.institutionTypeErrors = ""; }, 6000);
            } else {
                this.institutionTypeErrors = "";
            }
        },
        checkFInstituion(e) {
            if (!this.institution_name && e?.keyCode !== 9) {
                this.fiErrors = "Institution name is required.";
                // setTimeout(() => { this.fiErrors = ""; }, 6000);
            } else {
                this.fiErrors = "";
            }
        },
        checkPotentialDeposit(e) {
            if (!this.potential_deposit && e?.keyCode !== 9) {
                this.potentialDepositsErrors = "Potential Deposit is required.";
                setTimeout(() => { this.potentialDepositsErrors = ""; }, 4000);
            } else {
                this.potentialDepositsErrors = "";
            }
        },
        checkNaics(e) {
            if (!this.naics_code && e?.keyCode !== 9) {
                this.naicsErrors = "NAICS code is required.";
                setTimeout(() => { this.naicsErrors = ""; }, 4000);
            } else {
                this.naicsErrors = "";
            }
        },
        checkWholesaleDeposit(e) {
            if (!this.wholesale_deposit_portfolio_id && e?.keyCode !== 9) {
                this.wholesaleDepositsErrors = "Wholesale deposit is required.";
                setTimeout(() => { this.wholesaleDepositsErrors = ""; }, 4000);
            } else {
                this.wholesaleDepositsErrors = "";
            }
        },
        errorCheck() {
            this.compareInstitutionStringLength();
            this.comparePostalStringLength();
            this.compareTelephoneStringLength();
            this.compareAddress1StringLength();
            this.compareAddress2StringLength();
            this.compareCityStringLength();
            this.checkProvince();
            this.compareWebsiteStringLength();
            this.checkWholesaleDeposit();
            this.checkNaics();
            this.checkPotentialDeposit();
            this.checkInstituionType()
            this.checkFInstituion()
        },
        crop() {
            const { canvas } = this.$refs.cropper.getResult();
            let _this = this;
            if (!canvas || !this.image) {
                return null;
            }
            return {
                canvas: canvas, type: this.image.type
            }
            // canvas.toBlob((blob) => {
            //     _this.$emit('update:profile_image', blob);
            //     return blob;
            //     // console.log(blob);
            //     // Do something with blob: upload to a server, download and etc.
            // }, this.image.type);
        },
        reset() {
            this.image = {
                src: null,
                type: null
            };
            this.$emit('update:profile_image', '');
            this.image_uploaded = null;
        },
        loadImage(event) {
            const { files } = event.target;
            if (files && files[0]) {
                const file = files[0];
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (allowedTypes.includes(file.type) && file.size < 10 * 1024 * 1024) {
                    this.profileImageErrors = "";
                    if (this.image.src) {
                        URL.revokeObjectURL(this.image.src);
                    }
                    const blob = URL.createObjectURL(file);
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.image = {
                            src: blob,
                            type: file.type,
                        };

                        this.$emit("updatepersistImage", file);
                    };
                    reader.readAsArrayBuffer(file);
                } else {
                    this.image = {
                        src: null,
                        type: null
                    }
                    this.profileImageErrors = "File format must be JPG, JPEG, or PNG and size must be less than 10 MB";
                }
            }
        },
    },
    destroyed() {
        if (this.image.src) {
            URL.revokeObjectURL(this.image.src)
        }
    }
}
</script>
