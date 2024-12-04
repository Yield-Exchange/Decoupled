<template>
    <div>
        <m-button text="Update" link="#" type="primary" c-style="font-size: 14px"
            xclass="font-weight-bold  btn-xs font-weight-bold mt-3 float-end " v-if="offer" @click="openModel" />
        <a href="#" class="m-3 " id="createOfferBtn" v-b-modal="'#createOffer'" v-else @click="openModel">
            <i class="fa fa-plus fa-2x mt-2" aria-hidden="true"></i>
        </a>
        <!-- Modal -->
        <b-modal :ref="'createOffer'" :id="offer ? 'createOffer' + offer.id : 'createOffer' + (modalId)"
            :title='(offer) ? "Update your GIC offer" : "Create your GIC offer"' hide-footer size="md">
            <b-row>
                <h6 class="font-w-b text-black text-center" v-if="offer">{{ offer.product_name }} <br>
                    {{ Math.round(offer.term_length) + " " + offer.term_length_type.toLowerCase() }}</h6>
                <form  enctype="multipart/form-data">
                    <div class="my-3">
                        <div class="row">
                            <div class="d-flex">  
                                <b-col cols="3" class="mx-2">
                                    <OrganizationAvatar :organization="parsedOrganization" :size="70" />
                                </b-col>
                                <div class="col-md-9">
                                    <CustomInputGroup appended-style="width: 25%" input-style="width: 75%"
                                        append="append" p-style="display: flex; justify-content: flex-end"
                                        c-style="font-weight: 400;" id="rate" name="Rate*" :has-validation="true"
                                        @inputChanged="interestRate = $event" :attributes="{ min: 0 }"
                                        :input-default-value="interestRate" input-type="number" append-text="%"
                                        :validation-failed="submitted && (!interestRate || interestRate < 1)"
                                        validation-error="Enter Rate" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <b-col>
                        <CustomSelect :data="parsedProducts"
                            :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                            c-style="font-weight: 400;border-radius: 10px;width: 100%; text-align: center"
                            id="productId" name="Product" :has-validation="true" :default-value="productId"
                            @selectChanged="productId = $event"
                            :validation-failed="submitted && (!productId || productId < 1)"
                            validation-error="Select Product" pStyle="width:100%;" firstValue="Select A Product" />
                    </b-col>
                    <b-col>
                        <CustomInputGroup appended-style="width: 40%"
                            input-style="width: 60%; text-align: center !important;" append="prepend"
                            append-id="termType" append-name="Term Length Type"
                            c-style="font-weight: 400;margin-top: 10px" :data="termsType" id="terms" name="Term Length"
                            :has-validation="true" @inputChanged="terms = $event" @selectChanged="termType = $event"
                            :append-default-value="termType" :input-default-value="terms" input-type="number"
                            pStyle="width:100%;" :validation-failed="submitted && (!terms || terms < 1)"
                            validation-error="Select Term Length" />
                    </b-col>
                    <b-col
                        v-if="productId && ['Cashable', 'Notice deposit'].includes(getProduct(productId).description)">
                        <CustomInput name="Lockout Period" id="lockoutPeriod" p-style="width: 100%;"
                            :has-validation="true" input-type="text"
                            c-style="border-radius: 10px; text-align: center !important;margin-top: 10px"
                            :value="lockoutPeriod" v-model="lockoutPeriod" @inputChanged="lockoutPeriod = $event"
                            :validation-failed="submitted && (!lockoutPeriod || lockoutPeriod < 1)"
                            validation-error="Enter Lockout Period" :defaultValue="lockoutPeriod" />
                    </b-col>
                    <b-col>
                        <CustomSelect :data="this.currrencies"
                            c-style="font-weight: 400;border-radius: 10px;width: 100%; text-align: center;"
                            id="currency" name="Currency" :has-validation="true" :default-value="currency"
                            @selectChanged="currency = $event" :validation-failed="submitted && (!currency)"
                            validation-error="Select Lockout Period" pStyle="width:100%;margin-top: 10px" />
                    </b-col>
                    <b-col>
                        <CustomInput name="Minimum Amount" id="minAmount"
                            p-style="width: 100%; text-align: center !important;" :has-validation="false"
                            input-type="number" c-style="border-radius: 10px; text-align: center; margin-top: 15px"
                            :value="minAmount" v-model="minAmount" @inputChanged="minAmount = $event"
                            :attributes="{ min: 0 }" :validation-failed="submitted && (!minAmount || minAmount < 1)"
                            validation-error="Enter Minimum Amount" :defaultValue="minAmount" />
                    </b-col>
                    <b-col>
                        <CustomInput name="Maximum Amount" id="maxAmount"
                            p-style="width: 100%; text-align: center !important;" :has-validation="false"
                            input-type="number" c-style="border-radius: 10px;text-align: center; margin-top: 15px"
                            :value="maxAmount" v-model="maxAmount" :attributes="{ min: 0 }"
                            :validation-failed="submitted && (!maxAmount || maxAmount < 1)"
                            validation-error="Enter Maximum Amount" @inputChanged="maxAmount = $event"
                            :defaultValue="maxAmount" />
                    </b-col>
                    <b-col>
                        <CustomInput name="Cumulative Total" id="cumulativeTotal"
                            p-style="width: 100%; text-align: center !important;" :has-validation="false"
                            input-type="number" c-style="border-radius: 10px;text-align: center; margin-top: 15px"
                            :value="cumulativeTotal" v-model="cumulativeTotal" :attributes="{ min: 0 }"
                            :validation-failed="submitted && ((cumulativeTotal) ? (cumulativeTotal < 1) : false)"
                            @inputChanged="cumulativeTotal = $event"
                            :defaultValue="cumulativeTotal" />
                    </b-col>
                    <b-col>
                        <CustomSelect :data="this.compoundFrequencies"
                            c-style="font-weight: 400;border-radius: 10px;width: 100%; text-align: center"
                            id="compoundFrequency" name="compoundFrequency" :has-validation="true"
                            :default-value="compoundFrequency" @selectChanged="compoundFrequency = $event"
                            :validation-failed="submitted && (!compoundFrequency)"
                            validation-error="Select Compound Frequency" pStyle="width:100%;margin:10px 1px;"
                            firstValue="Select Compound Frequency" />
                    </b-col>
                    <b-col>
                        <CustomSelect :data="this.compoundFrequencies"
                            c-style="font-weight: 400;border-radius: 10px;width: 100%; text-align: center"
                            id="interestPaid" name="interestPaid" :has-validation="true" :default-value="interestPaid"
                            @selectChanged="interestPaid = $event" :validation-failed="submitted && (!interestPaid)"
                            validation-error="Select Interest Paid" pStyle="width:100%;margin:10px 1px;"
                            firstValue="Select Interest Paid" />
                    </b-col>
                    <b-col v-if="!expired && offer" class="text-center">
                        <b-form-checkbox v-model="expireOffer" name="check-button" switch>
                            Expire Offer?
                        </b-form-checkbox>
                    </b-col>
                    <b-col>
                        <CustomInput name="Rate expiry date" id="rate_held_until" p-style="" :has-validation="true"
                            input-type="datepicker"
                            :c-style="'border-radius: 10px;text-align: center; margin-top: 15px'"
                            @inputChanged="rate_held_until = $event" :default-value="rate_held_until"
                            :attributes="{ min: new Date() }" :validation-failed="submitted && (!rate_held_until)"
                            validation-error="Enter Rate expiry date" />
                    </b-col>


                    <m-button text="Product Disclosure statement" link="#" type="secondary" c-style="font-size: 13px;color: #9d9898 !important; border: 0.5px solid #9d9898 !important; width: 100%; margin-left:10px; margin-top: 6px; padding: 8px;"
                        xclass="font-weight-bold custom-secondary btn-xs rounded btn-block" v-b-modal.modal-1/>

                    <b-modal  size="md" id="modal-1">
                        <b-row>
                            <h6>Upload file or link to file</h6>
                            <b-col  cols="12">
                                <div>
                                    <b-form-group  label="Enter Link">
                                        <b-input-group prepend="https://" class="m-0" style="height: 40px !important;">
                                            <b-form-input aria-label="Link"  v-model="product_disclosure_url" style="font-size: 18px !important; margin: 0px;"></b-form-input>
                                        </b-input-group>
                                    </b-form-group>
                                </div>

                            </b-col>
                            <b-col cols="12">
                                <b-form-group label="Add File"  class="m-0" >
                                        <b-form-file v-model="product_disclosure_statement" class="m-0"
                                            placeholder="Choose a file or drop it here..."
                                            drop-placeholder="Drop file here..."
                                            ></b-form-file>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-modal>
                    <m-button text="Cancel" link="#" type="secondary" @click="hideModal"
                        xclass="float-start font-weight-bold my-3 font-s-12">
                    </m-button>
                    <m-button text="Save" link="#" type="primary" xclass="float-end font-weight-bold my-3 font-s-12"
                        @click.native="storeOffer" :loading="isLoading">
                    </m-button>
                </form>
            </b-row>
        </b-modal>
        <LeavePageConfirmDialog size="md" @closedSuccessModal="closeSuccessModal()"
            btnOneText="Stay" btnTwoText="Leave" title="Are You Sure You want to leave this Page?"
            :showm="showLeaveWarning" @btnOneClicked="stayOnPage()" @btnTwoClicked="leaveThePage()" />
    </div>
    
</template>
<style>
div.invalid-feedback {
    background-color: lightcoral !important;
    color: #000 !important;
    padding: 2px !important;
    font-weight: 500 !important;
    margin-top: 0 !important;
}
.text-black {
    color: #000000;
}
.font-w-b {
    font-weight: 900;
}
.m-1-19 {
    margin: 1% 19%;
}
.p15 {
    padding: 15px;
}
.font-s-12 {
    font-size: 1.2em;
}
.font-s-13 {
    font-size: 13px !important;
}
.font-s-17 {
    font-size: 17px !important;
}
.font-s-20 {
    font-size: 30px !important;
}
.font-s-1 {
    font-size: .8em;
}
.image {
    width: 60px;
    border-radius: 100%;
    margin: 2px;
}
.rate {
    font-size: 2.5em;
    vertical-align: middle;
    margin: 2px;
}
.w-20 {
    width: 20em;
}
.w-30 {
    width: 30em;
}
.button-primary {
    color: #fff;
    background-color: #3656A6;
    border-color: #3d66cd;
}
.button-primary:hover {
    color: #fff;
    background-color: #456cce;
    border-color: #366aee;
}
</style>
<script>
import CustomInputGroup from "../../shared/CustomInputGroup";
import LeavePageConfirmDialog from "../../shared/messageboxes/LeavePageConfirmDialog.vue";
import CustomInput from "../../shared/CustomInput";
import CustomSelect from "../../shared/CustomSelect";
import OrganizationAvatar from "../../shared/OrganizationAvatar";
export default {
    components: {
        LeavePageConfirmDialog,
        OrganizationAvatar,
        CustomInputGroup,
        CustomSelect,
        CustomInput
    },
    props: [
        'offer', 'products', 'organization', 'store_route', 'expired', 'modalId'
    ],
    created: function () {
        this.$parent.$on('openCreateModel', this.openModel);
    },
    data() {
        return {
            showLeaveWarning:false,
            storeRoute: JSON.parse(this.store_route),
            compoundFrequencies: ["At maturity", "Monthly", "Quarterly", "Semi annually", "Annually"],
            termsType: ["DAYS", "MONTHS"],
            currrencies: ["CAD", "USD"],
            product_disclosure_statement: null,
            product_disclosure_url: null,
            expireOffer: false,
            productId: this.offer ? this.offer?.product_id : "",
            termType: this.offer ? this.offer?.term_length_type : "MONTHS",
            terms: this.offer ? Math.round(this.offer?.term_length) : "",
            lockoutPeriod: (this.offer && ['Cashable', 'Notice deposit'].includes(this.offer?.product_name)) ? Math.trunc(this.offer?.lockout_period) : "",
            interestPaid: this.offer ? this.offer?.interest_paid : "",
            interestRate: this.offer ? this.offer?.interest_rate : "",
            minAmount: this.offer ? Math.trunc(this.offer?.minimum_amount) : "",
            currency: this.offer ? this.offer?.currency : "CAD",
            maxAmount: this.offer ? Math.trunc(this.offer?.maximum_amount) : "",
            isfeatured: this.offer ? this.offer?.is_featured : false,
            compoundFrequency: this.offer ? this.offer?.compound_frequency : "",
            offerId: this.offer ? this.offer?.encoded_id : "",
            expiryStatus: this.expired ? "Yes" : "",
            cumulativeTotal: this.offer ? this.offer?.cumulative_total : "",
            formError: [],
            loading: false,
            productIdError: "",
            termTypeError: "",
            termsError: "",
            lockoutPeriodError: "",
            interestPaidError: "",
            interestRateError: "",
            minAmountError: "",
            maxAmountError: "",
            compoundFrequencyError: "",
            cumulativeTotalError: "",
            submitted: false,
            rate_held_until: this.offer ? this.offer?.rate_held_until : null,
            parsedProducts: this.products ? JSON.parse(this.products) : null,
            parsedOrganization: this.organization ? JSON.parse(this.organization) : null,
            initial_expiry_date: this.offer ? this.offer?.rate_held_until : null,
            forceHideModal: false
        }
    },
    mounted() {
        if (this.offer) this.storeRoute = this.storeRoute + "/" + this.offerId;
        let the_this = this;
        this.$root.$on('bv::modal::hide', (bvEvent, modalId) => {
            if (!['createOffer' + this.modalId, (this.offer ? 'createOffer' + this.offer.id : '')].includes(modalId)) {
                return;
            }
            if (the_this.forceHideModal) {
                the_this.forceHideModal = false;
                return;
            }
            bvEvent.preventDefault();

            the_this.$swal({
                title: "Do you want to leave this page?",
                text: "Changes you made will not be saved.",
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                confirmButtonColor: '#4975E3',
                cancelButtonColor: '#E9ECEF',
                customClass: {
                    actions: 'swal-button-actions'
                }
            }).then((response) => {
                if (response.isConfirmed) {
                    the_this.forceHideModal = true;
                    the_this.$refs['createOffer'].hide();
                }
            });
        });
    },
    computed: {
        isLoading() {
            return this.loading;
        },
    },
    methods: {
        leaveThePage(){

        },
        stayOnPage(){

        },
        getProduct(id) {
            let product = this.parsedProducts !== undefined ? Object.values(this.parsedProducts).filter(function (el) {
                return el.id === id
            }) : null;
            return product ? product[0] : { 'description': '' };
        },
        hideModal() {
            this.$refs['createOffer'].hide()
        },
        openModel() {
            this.$refs['createOffer'].show();
        },
        toggleFeatured() {
            this.isfeatured = !this.isfeatured
        },
        async storeOffer() {
            this.validate();
            if (this.formError.length > 0) {
                this.$swal({
                    title: "Invalid data",
                    text: this.formError[0],
                    confirmButtonText: 'Close'
                });
                this.formError = [];
                return;
            }
            this.submitted = true;
            const formData = new FormData();
            formData.append("is_featured", 0);
            formData.append("term_length_type", this.termType);
            formData.append("term_length", this.terms);
            formData.append("product_id", this.productId);
            formData.append("lockout_period", this.lockoutPeriod);
            formData.append("currency", this.currency);
            formData.append("minimum_amount", this.minAmount.toString().replace(/,/g, ""));
            formData.append("maximum_amount", this.maxAmount.toString().replace(/,/g, ""));
            formData.append("compound_frequency", this.compoundFrequency);
            formData.append("cumulative_total", this.cumulativeTotal.toString().replace(/,/g, ""));
            formData.append("interest_paid", this.interestPaid);
            formData.append("interest_rate", this.interestRate);
            formData.append("rate_held_until", this.rate_held_until);
            formData.append("expireOffer", this.expireOffer);
            formData.append("product_disclosure_statement", this.product_disclosure_statement);
            formData.append("product_disclosure_url", this.product_disclosure_url);
            if (!this.termType || !this.terms || !this.productId
                || !this.currency || !this.minAmount || !this.maxAmount || !this.compoundFrequency

    
          
            
    

          
    
    
  
                || !this.interestPaid || !this.interestRate || !this.cumulativeTotal
            ) {
                if(!this.minAmount ){
                    this.$swal({
                        title: "Validation error",
                        text: "Enter Min Amount: "+this.minAmount,
                        confirmButtonText: 'Close'
                    });
                    return;
                }
                if(!this.minAmount ){
                    this.$swal({
                        title: "Validation error",
                        text: "Enter Max Amount: "+this.minAmount,
                        confirmButtonText: 'Close'
                    });
                    return;
                }
                if(!this.cumulativeTotal ){
                    this.$swal({
                        title: "Validation error",
                        text: "Enter Cumulative Total: "+this.cumulativeTotal,
                        confirmButtonText: 'Close'
                    });
                    return ;
                }
                return;
            }
            
            this.loading = true;
            axios({ method: "post", url: this.storeRoute, data: formData })
                .then(response => {
                    let data = response?.data;
                    if (data.success) {
                        this.$swal({
                            title: 'Offer ' + (this.offer ? 'updated' : 'created') + ' successfully.',
                            text: data.message,
                            confirmButtonText: 'Close'
                        }).then(() => {
                            this.loading = false;
                            this.submitted = false;
                            window.location.reload();
                        });
                    } else {
                        throw new Error(response.data.message)
                    }
                }).catch(error => {
                    this.loading = false;
                    this.submitted = false;
                    let msg = Object.values(error.response.data.errors)[0][0];
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    this.$swal({
                        title: error,
                        text: msg,
                        confirmButtonText: 'Close'
                    });
                });
            // }
        },
        validate() {
            this.checkAmount();
            this.checkExpiry();
        },
        checkAmount() {
            if (this.minAmount.toString().replace(/,/g, "") > this.maxAmount.toString().replace(/,/g, "")) {
                this.formError.push("Minimum  amount can not be more that Maximum amount");
            }
        },
        checkExpiry() {
            // if (!this.expiryStatus) {
            //     this.formError.push("Select Expiry Status");
            // }
        },
        previousDay() {
            let now = new Date();
            let previousDay = now.setDate(now.getDate() - 1);
            return now.toISOString().slice(0, 10);
        }
    },
    watch: {
        expireOffer: function (newVal, oldVal) { // watch it
            if (newVal === true) {
                this.$swal({
                    title: "",
                    text: "Are you sure you want to expire the offer",
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#4975E3',
                    cancelButtonColor: '#E9ECEF',
                    customClass: {
                        actions: 'swal-button-actions'
                    }
                }).then((response) => {
                    if (response.isConfirmed) {
                        this.rate_held_until = new Date().toISOString().slice(0, 10);
                        console.log(this.rate_held_until);
                    } else {
                        this.expireOffer = false;
                        this.rate_held_until = this.initial_expiry_date;
                    }
                });
            } else {
                this.rate_held_until = this.initial_expiry_date;
            }
        }
    }
}
</script>