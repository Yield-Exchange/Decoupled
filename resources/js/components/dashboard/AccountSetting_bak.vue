<template>
    <div class="row" style="width:80%">
        <h4 class="font-weight-bold text-center color-black" style="color: black"> Organization Settings</h4>
        <p class="text-center" style="font-weight: normal">Please confirm and update organization details</p>

        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body" style="padding-top: 20px">
                    <b-row>
                        <b-col cols="3">
                            <avatar v-if="!organization.logo" :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                                :initials="organization.name[0]"></avatar>
                            <avatar v-if="organization.logo" :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                                :src="'image/' + organization.logo"></avatar>
                        </b-col>
                        <b-col cols="9">
                            <h5 style="color: black">{{ this.organization.name }}</h5>
                            <p style="color: black; font-weight: normal;margin-bottom: 5px;">{{
                                this.organization.demographic_data.email
                                }}</p>
                            <p style="color: black; font-weight: normal;margin-bottom: 0;">{{
                                this.organization.demographic_data.telephone
                                }}</p>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body" style="padding-top: 20px" id="tooltip_viewpoint">

                    <p v-if="organization.is_non_partnered_fi == 1 && organization.account_status == 'ACTIVE' && organization.password_changed == 0"
                        style="color: red">Please complete account settings in order to use the Yield Exchange Limited
                        Version</p>

                    <div class="row" v-if="this.formErrors">
                        <b-alert show :variant="'info'">{{ this.formErrors }}</b-alert>
                    </div>

                    <div class="row mt-2">
                        <sign-up-step1 :fromUserProfile="true" :organization-type="this.organization.type"
                            :provinces="this.provinces" :naics-codes="JSON.stringify(this.naicsCodes)"
                            :potential-deposits="JSON.stringify(this.potentialDeposits)"
                            :wholesale-deposit-portfolios="JSON.stringify(this.depositPortfolio)"
                            :fi-types="this.fiTypes" :fis="this.fis" :institution_name.sync="institution_name"
                            :profile_image.sync="profile_image" :province.sync="province" :naics_code.sync="naics_code"
                            :institution_type.sync="institution_type" :address_line_1.sync="address_line_1"
                            :address_line_2.sync="address_line_2" :city.sync="city" :postal_code.sync="postal_code"
                            :telephone.sync="telephone" :website.sync="website"
                            :potential_deposit.sync="potential_deposit" :form-errors="formErrors"
                            :show-label="showLabel" ref="Step1" :digital_account_opening.sync="digital_account_opening"
                            :wholesale_deposit_portfolio_id.sync="wholesale_deposit_portfolio_id"
                            :description.sync="description" :descriptionError.sync="descriptionError">
                        </sign-up-step1>
                    </div>

                    <b-row style="padding-left: 2%"
                        v-b-tooltip="{ customClass: 'row123', trigger: 'hover', boundary: 'viewport', container: 'tooltip_viewpoint', html: true, placement: 'right' }"
                        title="<img src='/assets/img/credit_rating.png' style='height: 250px;'/>">
                        <b-col sm="12" style="display:inline;padding-right: 0;width: 97%" class="pr-0 pl-0 mb-3"
                            v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK'">
                            <label v-if="showLabel" for="credit_rating">Short term DBRS rating:</label>
                            <v-select label="description" :options="this.getShortTermDBRSRatings" class="font-13"
                                placeholder="Short term DBRS rating*" id="credit_rating"
                                style="color: #212529;font-weight: 400;" :value="credit_rating" v-model="credit_rating"
                                :class="{ 'verror': creditRatingError }">
                            </v-select>
                            <b-alert v-if="creditRatingError" show variant="danger" class="form-alert">{{
                                creditRatingError
                                }}</b-alert>
                        </b-col>
                        <span v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK'" style="display:inline;
                            width:3%;
                            padding:0;
                            padding-left: 5px;
                            margin-top: 30px;">
                            <b-icon icon="exclamation-circle-fill" variant="primary">
                            </b-icon>
                        </span>
                    </b-row>

                    <b-col sm="12" class="pr-0 pl-0 mb-3"
                        v-if="this.organizationType && this.organizationType.toUpperCase() === 'BANK'">
                        <label v-if="showLabel" for="deposit_insurance">Deposit insurance:</label>
                        <v-select label="description" :options="this.getDepositInsurances" class="font-13"
                            placeholder="Deposit insurance:*" id="deposit_insurance"
                            style="color: #212529;font-weight: 400;" :value="deposit_insurance"
                            v-model="deposit_insurance" :class="{ 'verror': depositInsuranceError }">
                        </v-select>
                        <b-alert v-if="depositInsuranceError" show variant="danger" class="form-alert">{{
                            depositInsuranceError
                            }}</b-alert>
                    </b-col>

                </div>
            </div>
        </div>

        <div class="col-md-12" style="padding-right:0">
            <div class="text-right">
                <b-button :variant="'primary'" :disabled="!allowSubmit || submitButtonSpinner" :size="'lg'"
                    style="font-size:15px;border-radius:20px" @click="doSubmit">
                    <b-spinner variant="light" label="Loading" style="width: 1.7rem; height: 1.3rem;margin-right:5px"
                        v-if="submitButtonSpinner">
                    </b-spinner>
                    {{ submitButtonText }}
                </b-button>
            </div>
        </div>
    </div>
</template>
<style>
    .swal-button-actions {
        flex-direction: row-reverse !important;
        display: flex !important;
    }

    .row123 .tooltip-inner {
        background: transparent !important;
    }
</style>
<style scoped>
    .card-header {
        padding-top: 0 !important;
    }

    .dashboard-body .card-body {
        padding: 0;
    }

    .dashboard-body .card {
        border-radius: 10px;
    }

    .btn.btn-secondary[disabled] {
        background-color: #979797;
        color: black;
    }

    .verror {
        border: 1px solid red;
        border-radius: 5px;
    }
</style>
<script>

    import Avatar from 'vue-avatar';
    import { confirmLeavePage } from "../../utils/GlobalUtils";
    export default {
        mounted() {
            confirmLeavePage(this, document);
        },
        components: {
            Avatar
        },
        created() {
            // console.log(this.organization)  //undefined;
        },
        props: ['provinces', 'naicsCodes', 'potentialDeposits', 'depositPortfolio'
            , 'fiTypes', 'user', 'organization', 'redirectRoute', 'updateAccountSettingRoute', 'deposit_insurances', 'credit_rating_types', 'permittedSubmitButton'],
        data() {
            return {
                description: this.organization?.demographic_data?.description,
                descriptionError: "",
                formErrors: '',
                institution_name: this.organization.name,
                profile_image: '',
                recaptchaToken: '',
                province: this.organization?.demographic_data?.province,
                naics_code: this.organization?.n_a_i_c_s_code,
                institution_type: this.organization.fi_type_id,
                address_line_1: this.organization?.demographic_data?.address1,
                address_line_2: this.organization?.demographic_data?.address2,
                city: this.organization?.demographic_data?.city,
                postal_code: this.organization?.demographic_data?.postal_code,
                telephone: this.organization?.demographic_data?.telephone,
                website: this.organization?.demographic_data?.website,
                potential_deposit: this.organization?.potential_yearly_deposit,
                wholesale_deposit_portfolio_id: this.organization?.whole_sale_deposits_portfolio,
                digital_account_opening: this.organization?.digital_account_opening,
                fis: JSON.stringify([]),
                organizationType: this.organization.type,
                submitButtonText: 'Save',
                submitButtonSpinner: false,
                showLabel: true,
                credit_rating: this.organization?.deposit_credit_rating?.credit_rating,
                deposit_insurance: this.organization?.deposit_credit_rating?.insurance_rating,
                organization_id: this.organization.id,
                creditRatingError: '',
                depositInsuranceError: '',

            }
        },
        computed: {
            allowSubmit() {
                // return this.canSubmit();
                return true;
            },
            getDepositInsurances() {
                return this.deposit_insurances;
            },
            getShortTermDBRSRatings() {
                return this.credit_rating_types;
            },
        },
        methods: {
            canSubmit() {
                this.$emit('submit');
                this.checkCreditRating();
                this.checkDepositInsurance();
                this.checkDescription();
                if (this.organizationType && this.organizationType.toUpperCase() === 'BANK') {
                    return this.province && this.address_line_1 && this.city
                        && this.postal_code && this.telephone
                        && this.wholesale_deposit_portfolio_id
                        && this.credit_rating && this.deposit_insurance
                }
                return this.province && this.naics_code && this.address_line_1 && this.city
                    && this.postal_code && this.telephone && this.potential_deposit
            },
            checkCreditRating(e) {
                if (!this.credit_rating && e?.keyCode !== 9) {
                    this.creditRatingError = "Credit Rating is required.";
                } else {
                    this.creditRatingError = "";
                }
            },
            checkDescription(e) {
                if (!this.descriptionError.length != "") {
                    return true;
                } else {
                    return false;

                }
            },
            checkDepositInsurance(e) {
                if (!this.deposit_insurance && e?.keyCode !== 9) {
                    this.depositInsuranceError = "Deposit Insurance is required.";
                    // setTimeout(() => { this.depositInsuranceError = ""; }, 4000);
                } else {
                    this.depositInsuranceError = "";
                }
            },
            async doSubmit() {
                if (!this.canSubmit()) {
                    return;
                }
                console.log(this.checkDescription());
                if (this.checkDescription() === false) {
                    return;
                }

                let this_ = this;

                let response = this_.$refs.Step1.crop();
                if (response?.canvas) {
                    this_.profile_image = await new Promise(blob => response.canvas.toBlob((blob), response.type));
                }

                this_.submitButtonText = "Please wait..";
                this_.submitButtonSpinner = true;
                const formData = new FormData();
                formData.append("credit_rating", this_.credit_rating?.id);
                formData.append("userType", this_.organization.type);
                formData.append("deposit_insurance", this_.deposit_insurance?.id);
                formData.append("profile_image", this_.profile_image);
                formData.append("province", this_.province);
                formData.append("naics_code", this_.naics_code?.id);
                formData.append("institution_type", this_.institution_type?.id);
                formData.append("address", this_.address_line_1);
                formData.append("address2", this_.address_line_2);
                formData.append("city", this_.city);
                formData.append("postal", this_.postal_code);
                formData.append("telephone", this_.telephone);
                formData.append("organization_id", this_.organization_id);
                formData.append("website", this_.website);
                formData.append("digital_account_opening", this_.digital_account_opening);
                formData.append("potential_yearly_deposit", this_.potential_deposit?.id);
                formData.append("wholesale_deposit_portfolio", this_.wholesale_deposit_portfolio_id?.id);
                formData.append("description", this_.description);
                axios.post(this_.updateAccountSettingRoute, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    let data = response?.data;
                    this_.submitButtonSpinner = false;
                    if (data.success) {
                        this.$swal({
                            title: 'Account settings update.',
                            text: data.message,
                            confirmButtonText: 'Close'
                        }).then(() => {
                            window.location.href = this_.redirectRoute;
                        });
                    } else {
                        this_.formErrors = data.message;
                        this_.formAlertType = data?.alert_class?.replace("alert-", "");
                    }
                    this_.submitButtonText = 'Save';
                }).catch(error => {
                    if (error?.response?.status === 419) {
                        this.formErrors = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        this.formErrors = error?.response?.data?.message;
                    }

                    this_.submitButtonText = 'Continue';
                    this_.submitButtonSpinner = false;
                });

            }
        }
    }
</script>