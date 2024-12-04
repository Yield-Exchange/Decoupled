<template>
    <div>
        <m-button class="mb-1" :c-style="'font-size: 14px;width: 80%;'" :text="!from_featured ? 'Select' : 'Buy Now'"
            link="#" type="primary" xclass="font-weight-bold m-1 custom-buttons" @click="openModel" />

        <b-modal ref="buyNowModal" :id="'buyNowModal' + random" title="Would you like to buy this GIC?" hide-footer
            size="md">
            <div style="text-align: center" class="mt-2 mb-2">

                <h3 style="font-size: 18px; font-weight: 600;color: black" class="pt-0 m-0">{{ data.product_name }}</h3>
                <p style="font-size: 18px; font-weight: 600;color: black" class="pt-0 m-0">{{
                    parseInt(data.term_length).toFixed(0) + " " + data.term_length_type.toLowerCase()
                    }}</p>
                <b-row class="pt-2 pb-2 mb-1">
                    <b-col cols="4">
                        <OrganizationAvatar :pStyle="'display: flex; justify-content: flex-end'"
                            :organization="data.organization" :size="60" />
                    </b-col>
                    <b-col cols="7" class="text-left pl-1 mt-2">
                        <span style="font-weight: bold;font-size: 25px" class="ml-3">{{ data.interest_rate }}%</span>
                    </b-col>
                </b-row>
                <b-row class="mt-2 mb-2 text-center">
                    <p class="p-0 m-0" style="font-size: 15px;font-weight: 600">{{ data.organization.name }}</p>
                </b-row>
                <b-row>
                    <div style="font-weight: normal"><span>Min: {{ this.c_amount(this.data.currency,
                            this.data.minimum_amount)
                            }}</span>
                        <span class="ml-3">Max: {{ this.c_amount(this.data.currency, this.data.maximum_amount) }}</span>
                    </div>
                </b-row>
                <b-row class="mb-1">
                    <CustomInputGroup appended-style="width: 20%" input-style="width: 80%" append="prepend"
                        append-id="investment_amount" append-name="Investment Amount"
                        c-style="font-weight: 400;text-align: center" :data="[data.currency]" id="investment_amount"
                        name="Investment Amount" :has-validation="false" @inputChanged="amount = $event"
                        :append-default-value="data.currency" :input-default-value="amount" input-type="number"
                        :validation-failed="submitted && (!amount || amount < 1)" :attributes="{ min: 0 }"
                        validation-error="Enter Investment Amount" />
                </b-row>
                <b-row>
                    <CustomInput name="Date Of deposit" id="date_of_deposit" p-style="" :has-validation="true"
                        input-type="datepicker" :c-style="'border-radius: 10px;'"
                        @inputChanged="date_of_deposit = $event" :default-value="null" :attributes="{ min: new Date() }"
                        :validation-failed="submitted && (!date_of_deposit)" validation-error="Enter Date Of deposit" />
                </b-row>
                <m-button text="Cancel" link="#" type="secondary" data-dismiss="modal" ref="btnShow" @click="hideModal"
                    xclass="float-start font-weight-bold my-3 font-s-12">
                </m-button>
                <m-button text="Buy" link="#" type="primary" @click="submit" :attributes="submitAttributes"
                    :loading="loading" xclass="float-end font-weight-bold my-3 font-s-12">
                </m-button>
            </div>
        </b-modal>
    </div>
</template>

<style scoped>
    .modal-title {
        width: 100%;
        text-align: center;
        color: black;
        font-weight: 600;
        font-size: 18px;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 370px;
            margin: 1.75rem auto;
        }
    }
</style>

<script>
    import OrganizationAvatar from "../../shared/OrganizationAvatar";
    import CustomInput from "../../shared/CustomInput";
    import CustomInputGroup from "../../shared/CustomInputGroup";

    export default {
        components: {
            CustomInputGroup,
            OrganizationAvatar,
            CustomInput
        },
        props: ['data', 'from_featured', 'buy_url'],
        mounted() {
            let the_this = this;
            this.$root.$on('bv::modal::hide', (bvEvent, modalId) => {

                if (!['buyNowModal' + the_this.random].includes(modalId)) {
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
                        the_this.hideModal();
                        the_this.amount = null;
                        the_this.date_of_deposit = null;
                    }
                });
            });

            this.$root.$on('bv::modal::hidden', (bvEvent, modalId) => {
                this.$emit('modalOpen', false);
            });

            this.$root.$on('bv::modal::show', (bvEvent, modalId) => {
                this.$emit('modalOpen', true);
            });
        },
        data() {
            return {
                date_of_deposit: null,
                amount: null, //Math.floor(this.data.maximum_amount),
                loading: false,
                submitted: false,
                submitAttributes: {
                    // 'disabled': ''
                },
                forceHideModal: false,
                random: (new Date()).getTime()
            }
        },
        computed: {
        },
        methods: {
            addComma(newValue) {
                return newValue;// ? parseFloat(newValue.replace(/,/g, '')).toLocaleString() : '';
            },
            c_amount(currency, value) {
                if (value.length > 6 && value.length < 10) {
                    var newValue = value.split("").reverse().join("").slice(6).split("").reverse().join("");
                    var note = "K";
                } else if (value.length >= 10) {
                    var newValue = value.split("").reverse().join("").slice(9).split("").reverse().join("");
                    var note = "M";
                } else {
                    var newValue = value;
                    var note = "";
                }
                return currency + ' ' + this.addComma(Math.floor(newValue)) + note;
            },
            showModal() {
                // this.$root.$emit('bv::show::modal', 'modal-1', '#btnShow')
            },
            openModel() {
                this.$refs['buyNowModal'].show();
            },
            hideModal() {
                this.$refs['buyNowModal'].hide();
                // this.$root.$emit('bv::hide::modal', '#buyNow' + this.modalId, '#btnShow')
            },
            submit() {
                this.submitted = true;
                this.amount = this.amount.toString().replace(/,/g, "");
                if (!this.amount || !this.date_of_deposit) {
                    if (!this.amount) {
                        this.$swal({
                            title: 'Submit failed',
                            text: 'Amount is required',
                            confirmButtonText: 'Close'
                        });
                    }
                    return;
                }

                if (parseFloat(this.amount) < this.data.minimum_amount || parseFloat(this.amount) > this.data.maximum_amount) {
                    this.$swal({
                        title: 'Submit failed',
                        text: 'Amount Can not be more than ' + this.c_amount(this.data.currency, this.data.maximum_amount) + ' or less than ' + this.c_amount(this.data.currency, this.data.minimum_amount),
                        confirmButtonText: 'Close'
                    });
                    return;
                }

                this.loading = true;
                this.submitAttributes = {
                    'disabled': ''
                };

                axios.post(this.buy_url, {
                    amount: this.amount,
                    date_of_deposit: this.date_of_deposit,
                    market_place_offer_id: this.data.id
                }).then(response => {
                    if (response?.data?.success) {
                        this.loading = false;
                        window.location.href = response?.data?.url;
                        return;
                    }

                    this.$swal({
                        title: 'Submit failed',
                        text: response?.data?.message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                    this.submitAttributes = {
                        // 'disabled': ''
                    };
                }).catch(error => {
                    let message;
                    if (error?.response?.status === 419) {
                        message = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        message = error?.response?.data?.message;
                    }

                    this.$swal({
                        title: 'Submit failed',
                        text: message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                    this.submitAttributes = {
                        // 'disabled': ''
                    };
                });
            },
        },
    }

</script>
