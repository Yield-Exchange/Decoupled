<template>
    <div style="width: 100%">
        <div style="width: 100%;">
            <div
                :style="'display: flex; flex-direction: row;width: 100%;' + (action && action === 'edit' ? 'justify-content: flex-start;' : 'justify-content: flex-end;')">
                <TableActionButton @click="showModal" v-if="action && action === 'edit'" variant-color="" text-color="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.98987 1.90362C8.25777 1.6358 8.62106 1.48535 8.99987 1.48535C9.37868 1.48535 9.74197 1.6358 10.0099 1.90362L10.0959 1.98966C10.3637 2.25756 10.5142 2.6209 10.5142 2.99971C10.5142 3.37852 10.3638 3.74181 10.096 4.00971L4.30292 9.80276C4.24799 9.85768 4.17917 9.89665 4.10381 9.91549L2.10381 10.4155C1.95777 10.452 1.80327 10.4092 1.69682 10.3028C1.59038 10.1963 1.54758 10.0418 1.58409 9.89577L2.08409 7.89577C2.10293 7.82041 2.1419 7.75159 2.19682 7.69666L7.19656 2.69693L7.98987 1.90362ZM7.49987 3.6058L2.88685 8.21882L2.58888 9.4107L3.78076 9.11273L8.39378 4.49971L7.49987 3.6058ZM8.99987 3.89362L8.10596 2.99971L8.59587 2.5098C8.59586 2.50982 8.59589 2.50979 8.59587 2.5098C8.70302 2.4027 8.84837 2.34249 8.99987 2.34249C9.15137 2.34249 9.29667 2.40266 9.40382 2.50976C9.40381 2.50974 9.40384 2.50977 9.40382 2.50976L9.48978 2.59571C9.48976 2.59569 9.48979 2.59573 9.48978 2.59571M6.0713 9.99971C6.0713 9.76302 6.26318 9.57114 6.49987 9.57114H10.4999C10.7366 9.57114 10.9284 9.76302 10.9284 9.99971C10.9284 10.2364 10.7366 10.4283 10.4999 10.4283H6.49987C6.26318 10.4283 6.0713 10.2364 6.0713 9.99971Z"
                            fill="#5063F4" />
                    </svg>
                    Edit
                </TableActionButton>
                <b-button v-else @click="showModal"
                    style="min-width: 15%; width:auto; !important;height: 40px !important; margin-top:10px; padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 1px solid #5063F4 !important;background-color: white !important;color: #5063F4;">
                    Add New Product
                </b-button>
            </div>
        </div>
        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="closeSuccessModal()"
            :btnOneText="successbtnOneText" :btnTwoText="successbtnTwoText" :title="successModalTitle"
            :showm="showSuccessModal" @okClicked="closeSuccessModal()" />
        <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" />
        <Modal :visible.sync="show" modalsize="lg" :show="show" :modalHeight="modalHeight"
            @closemodal="closeAddProductModal()" @productDeletedAddNew="show = true">

            <b-row
                style="width:100%;padding: 0px !important; margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="12" style="width:100%;padding: 0px !important;">
                    <MessageHeaderIconized :title="modalTitle" width="100"
                        title_image="/assets/dashboard/icons/Promo-pencil.svg" />
                </b-col>
            </b-row>
            <b-row
                style="width:100%;padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="4" style="padding: 4px !important;">
                    <FormProductTypeLabelRequired style="padding: 4px;" labelText="Product Type" required="true"
                        showHelperText="true" helperText="" helperId="productTypeHId" />
                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                        :data="available_products" id="product_type_id" name="Product Type*" :has-validation="false"
                        :default-value="product_type_id" @selectChanged="product_type_id = $event"
                        v-model="product_type_id" />
                    <span v-if="isInvalidTermLength" style="color:red;">Invalid.</span>
                </b-col>
                <b-col md="4" style="padding: 8px; !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Term Length" required="true"
                        showHelperText="true"
                        helperText="The term length of a deposit refers to the length of time that an investor agrees to deposit their money with a financial institution. This period can vary from a short time, such as a day, to several years. At the end of the term, the investor can choose to withdraw their initial deposit plus any earned interest, or renew the deposit for another term."
                        helperId="termLengthHId" />
                    <template>
                        <div>
                            <!-- {{  }} -->
                            <div class="combined-input">
                                <b-form-select class="" id="termlengthid" v-model="selectedTermLengthType"
                                    ref="termLengthSelect" @change="generateName" :options="termlengths"
                                    style="border: none;width:75%;margin-left:15px;outline:none; box-shadow: none;">
                                </b-form-select>
                                <b-form-input
                                    style="border: none; ;width:25%; margin-right:13px;outline:none; box-shadow: none; padding:0px;"
                                    type="text" v-model="formattedTermLength" @blur="generateName"
                                    :class="{ 'validation-error': submitted && !term_length }" placeholder="eg. 3" />
                            </div>
                            <span v-if="isInvalidTermLength" style="color:red">Invalid.</span>
                        </div>
                    </template>
                </b-col>
                <b-col md="4" style="padding: 4px !important;">
                    <FormLabelRequired style="padding: 4px;" :labelText="lockout_period_label" required="true"
                        showHelperText="true"
                        helperText="A lock-out period is a specific period of time during which you cannot access your money or withdraw funds from an account. It is used to discourage early withdrawals, and if you try to withdraw funds before the end of the lock-out period, you may face penalties or fees. The length of the lock-out period can vary depending on the type of account or investment."
                        helperId="lockoutHId" />

                    <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%" id="lockout_period"
                        :name="lockout_period_name" :has-validation="true" @inputChanged="setLockOutPeriod($event)"
                        input-type="number" :default-value="lockout_period"
                        :validation-failed="submitted && (!lockout_period)" @blur="generateName"
                        validation-error="Lock out Period" :disabled="showLockoutDisabled" />

                    <span v-if="isInvalidLockoutPeriod" style="color:red;margin-top: 1px;">Invalid.</span>
                </b-col>
            </b-row>
            <b-row
                style="width:100%;padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="6" sm="12" style="padding: 4px !important;">
                    <FormLabelRequired style="padding:4px;" labelText="Interest Paid" required="true"
                        :showHelperText="false" helperText="" helperId="interestPaidHId" />
                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                        p-style="width: 100%;"
                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                        :data="interest_paid_frequencies" id="interest_paid" name="Interest Paid*"
                        :has-validation="false" :default-value="interest_paid" v-model="selectedInterestPaid"
                        @selectChanged="interest_paid = $event" />
                    <span v-if="isInvalidTermLength" style="color:red;">Invalid.</span>
                </b-col>
                <b-col md="6" sm="12" style="padding: 4px !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Compounding Frequency" required="true"
                        :showHelperText="false" helperText="" helperId="compoundingFreHId" />
                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                        p-style="width: 100%;"
                        c-style="font-weight: 400;width:100%;margin: 0 auto;border-radius: 10px;background:white"
                        :data="compounding_frequencies" id="compounding_frequency" name="Compounding Frequency*"
                        :has-validation="false" :default-value="compounding_frequency"
                        v-model="selectedCompoundingFrequency" @selectChanged="compounding_frequency = $event" />
                    <span v-if="isInvalidTermLength" style="color:red;">Invalid.</span>
                </b-col>
            </b-row>
            <b-row
                style="width:100%;padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="5" sm="12" style="padding:4px !important;">
                    <FormLabelRequired labelText="PDS" required="true" showHelperText="true"
                        helperText="Product Disclosure Statement" helperId="PDSHId" />

                    <FileUpload :file_selected.sync="pds_file" />
                    <span v-if="isInvalidTermLength" style="color:red;">Invalid.</span>

                </b-col>
                <b-col md="7" sm="12" style="padding: 4px; !important;">
                    <FormLabelRequired labelText="Custom Product Name" required="true" showHelperText="true"
                        helperText="Enter your own product name" helperId="customProductHId" />
                    <b-form-input type="text" placeholder="Custom Product Name*" :class="'font-13 input-height '"
                        id="custom_product_name"
                        :aria-describedby="'input-live-help input-default_product_name-feedback'" @focus="generateName"
                        style="font-weight: 400;width:100%; border-radius: 25px;outline:none; box-shadow: none;"
                        v-model="custom_product_name" :value="custom_product_name" />
                    <span v-if="isInvalidTermLength" style="color:red;">Invalid.</span>
                </b-col>
            </b-row>
            <b-row
                style="width:100%; padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="5" sm="12" style="padding: 4px !important;">
                    &nbsp;<br>
                    <span style="color:#5063F4;"> *(Mandatory Fields) </span>
                </b-col>
                <b-col md="7" sm="12" style="padding: 5px; padding-right: 4px; !important;">
                    <div style="display: flex; align-items: center; justify-content: end;">
                        <b-button @click="submit()"
                            style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                            Submit
                        </b-button>
                    </div>
                </b-col>
            </b-row>


        </Modal>
    </div>
</template>
<script>
    import Modal from "../../shared/Modal";
    import ModalOld from "../../shared/ModalNew";

    import MessageHeaderIconized from "../../shared/messageboxes/MessageHeaderIconized.vue";
    import FormLabelRequired from "../../shared/formLabels/FormLabelRequired.vue";
    import FormProductTypeLabelRequired from "../../shared/formLabels/FormProductTypeLabelRequired.vue";
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import TwoButtonActionMessageBox from "../../shared/messageboxes/OKButtonActionMessageBox.vue";
    // FormLabelRequired
    import Button from "../../shared/Buttons/Button";

    import CustomSelect from "../../shared/CustomSelect";
    import CustomInput from "../../shared/CustomInput";
    import Tooltip from "../../shared/Tooltip";
    import FileUpload from "../../shared/FileUpload";
    import AddProductSuccess from "./AddProductSuccess";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    export default {
        mounted() {

            if (this.action === "edit") {
                this.modalTitle = "Edit Product";
            }
        },
        components: {
            FormProductTypeLabelRequired,
            GeneralNoInteractionError,
            TwoButtonActionMessageBox,
            FormLabelRequired,
            MessageHeaderIconized,
            TableActionButton,
            Modal,
            Button,
            CustomSelect,
            CustomInput,
            Tooltip,
            FileUpload,
            AddProductSuccess,

        },
        props: ['products', 'action', 'product_id', 'showmodal'],
        data() {
            return {
                errorModalTitle: "",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalMessage: "",
                successModalTitle: "",
                successbtnOneText: "",
                successbtnTwoText: "",
                showSuccessModal: false,
                successModalSize: "md",
                modalSize: "lg",
                modalTitle: "Add New Product",
                show: (this.showmodal) ? true : false,
                submitted: false,
                modalHeight: 'auto',
                product_type_id: null,
                term_length_type: "MONTHS",
                default_product_name: null,
                custom_product_name: null,
                compounding_frequencies: [
                    { 'id': 'ANNUALLY', 'description': 'Annually' },
                    { 'id': 'SEMI-ANNUALLY', 'description': 'Semi-Annually' },
                    { 'id': 'QUARTERLY', 'description': 'Quarterly' },
                    { 'id': 'MONTHLY', 'description': 'Monthly' },
                    { 'id': 'DAILY', 'description': 'Daily' }],
                interest_paid_frequencies: [
                    { 'id': 'AT MATURITY', 'description': 'At Maturity' },
                    { 'id': 'ANNUALLY', 'description': 'Annually' },
                    { 'id': 'SEMI-ANNUALLY', 'description': 'Semi-Annually' },
                    { 'id': 'QUARTERLY', 'description': 'Quarterly' },
                    { 'id': 'MONTHLY', 'description': 'Monthly' },
                    { 'id': 'DAILY', 'description': 'Daily' }],
                compounding_frequency: "ANNUALLY",
                lockout_period: null,
                lockout_period_label: "Lockout Period",
                lockout_period_name: "Lock Out Period",
                term_length: null,
                pds_file: null,
                available_products: this.products,
                interest_paid: "AT MATURITY",
                data: null,
                show_product_success: false,
                isInvalidTermLength: false,
                isInvalidLockoutPeriod: false,
                isInputReadOnly: true,
                maxNameLength: 50,
                updateItem: null,
                termlengths: [
                    { value: 'MONTHS', text: 'Months' },
                    { value: 'DAYS', text: 'Days' }
                ],
            }
        },
        computed: {
            formattedTermLength: {
                get() {

                    if (typeof this.term_length === 'string') {
                        const valueWithoutCommas = this.term_length.replace(/,/g, '');
                        return valueWithoutCommas.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    } else if (typeof this.term_length === 'number') {
                        this.term_length = String(this.term_length);
                        const valueWithoutCommas = this.term_length.replace(/,/g, '');
                        return valueWithoutCommas.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }

                },
                set(newValue) {
                    const valueWithoutCommas = newValue.replace(/,/g, '');
                    if (!isNaN(valueWithoutCommas)) {
                        if (valueWithoutCommas > 999999999999) {
                            this.isInvalidTermLength = true;
                        } else {
                            this.isInvalidTermLength = false;
                            this.term_length = valueWithoutCommas;
                        }
                    } else {
                        this.isInvalidTermLength = true;
                        this.term_length = '';
                    }

                },
            },
            selectedTermLengthType: {
                get() {
                    return this.term_length_type;
                },
                set(lengthType) {
                    console.log(lengthType, "lengthType");
                    this.term_length_type = lengthType;
                }
            },
            selectedInterestPaid: {
                get() {
                    return this.interest_paid;
                },
                set(interestPaid) {
                    console.log(interestPaid, "interestPaid");
                    this.interest_paid = interestPaid;

                }
            },
            selectedCompoundingFrequency: {
                get() {
                    return this.compounding_frequency;
                },
                set(compoundingfreq) {
                    console.log(compoundingfreq, "compoundingfreq");
                    this.compounding_frequency = compoundingfreq;

                }
            },
            formattedLockoutPeriod: {
                get() {

                    if (typeof this.lockout_period === 'string') {
                        const valueWithoutCommas = this.lockout_period.replace(/,/g, '');
                        return valueWithoutCommas.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    } else if (typeof this.lockout_period === 'number') {
                        this.lockout_period = String(this.lockout_period);
                        const valueWithoutCommas = this.lockout_period.replace(/,/g, '');
                        return valueWithoutCommas.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    }

                },
                set(newValue) {
                    const valueWithoutCommas = newValue.replace(/,/g, '');
                    if (!isNaN(valueWithoutCommas)) {
                        if (valueWithoutCommas > 999999999999) {
                            this.isInvalidLockoutPeriod = true;
                        } else {
                            this.isInvalidLockoutPeriod = false;
                            this.lockout_period = valueWithoutCommas;
                        }
                    } else {
                        this.isInvalidLockoutPeriod = true;
                        this.lockout_period = '';
                    }

                },
            },
            showLockoutDisabled() {

                this.default_product_name = this.generateProdName();

                if (!this.product_type_id) {
                    return true;
                }
                let this_ = this;
                let filter = Object.values(this.products).filter((item) => item.id == this_.product_type_id);
                console.log(this.capitalize(filter[0].description));
                // console.log((filter[0].description !== "Cashable" || filter[0].description !== "Notice deposit"), "returned filter");
                if (filter && filter.length > 0) {
                    if ((this.capitalize(filter[0].description) === "Cashable" || this.capitalize(filter[0].description) === "Notice deposit" || this.capitalize(filter[0].description) === "Notice   Deposit")) {

                        if (this.capitalize(filter[0].description) === "Notice deposit") {
                            this.lockout_period_label = "Notice Period";
                            this.lockout_period_name = "Notice Period";
                        } else if (this.capitalize(filter[0].description) === "Notice Deposit") {
                            this.lockout_period_label = "Notice Period";
                            this.lockout_period_name = "Notice Period";
                        } else {
                            this.lockout_period_label = "Lockout Period";
                            this.lockout_period_name = "Lockout Period";
                        }
                        return false;
                    } else {
                        this.lockout_period = "";
                        this.lockout_period_label = "Lockout Period";
                        this.lockout_period_name = "Lockout Period";
                        return true;
                    }
                } else {
                    return true;
                }
                // return !(filter && filter.length > 0 && (filter[0].description !== "Cashable" || filter[0].description !== "Notice deposit");
            },
            evaluateProductName() {
                return this.generateProdName();
            }
        },
        methods: {
            compareTermLengthAndLockOut(pop) {
                let total_termlength = this.term_length;
                if ((this.term_length_type).toUpperCase() === "MONTHS") {

                    total_termlength = this.term_length * 30;

                }

                if (parseInt(total_termlength) < parseInt(this.lockout_period)) {

                    if (pop) {
                        this.showErrorModal = true;
                        this.errorModalTitle = "Invalid Lockout Period";
                        this.errorModalMessage = "Lockout Period Cannot be greater than term length";
                        this.show = true;
                    }
                    this.isInvalidLockoutPeriod = true;
                }
            },
            setLockOutPeriod(lock) {
                this.lockout_period = lock;
                this.compareTermLengthAndLockOut(false);
            },
            closeAddProductModal() {
                this.show = false;
            },
            closeSuccessModal() {
                this.showSuccessModal = false;
            },
            generateName() {
                this.default_product_name = this.generateProdName();
            },
            resetForm() {
                this.product_type_id = null;
                this.term_length_type = "MONTHS";
                this.default_product_name = null;
                this.custom_product_name = null;
                this.compounding_frequency = "ANNUALLY";
                this.lockout_period = null;
                this.term_length = null;
                this.pds_file = null;
                this.interest_paid = "AT MATURITY";
            },
            viewP() {
                // console.log("viewProduct");
                this.show_product_success = false;
                this.showSuccessModal = false;
                this.show = false;
            },
            newP() {
                // console.log("newProduct");
                this.showSuccessModal = false;
                this.show_product_success = false;
                this.show = true;
            },
            closeErrorModal() {
                this.showErrorModal = false;
            },
            submit() {
                if (this.submitted) {
                    return;
                }
                this.compareTermLengthAndLockOut(false);


                if (!this.product_type_id || !this.term_length_type || !this.compounding_frequency
                    || !this.default_product_name || !this.interest_paid || !this.term_length || !this.custom_product_name) {
                    this.showErrorModal = true;
                    this.errorModalTitle = "Create Product Error";
                    this.errorModalMessage = "Fill all required fields";
                    this.show = true;
                    return;
                }
                if (this.isInvalidTermLength) {
                    this.showErrorModal = true;
                    this.errorModalTitle = "Create Product Error";
                    this.errorModalMessage = "Your term length can only be a number.";

                    return;
                }
                if (this.isInvalidLockoutPeriod) {
                    this.showErrorModal = true;
                    this.errorModalTitle = "Create Product Error";
                    this.errorModalMessage = "Your Lockout/notice period can only be a number less than the term length.";
                    return;
                }
                if ((parseInt(this.term_length) > 120) && (this.term_length_type === "MONTHS")) {
                    this.showErrorModal = true;
                    this.errorModalTitle = "Create Product Error";
                    this.errorModalMessage = "Your term length cannot exceed 120 months.";

                    return;
                }
                if ((parseInt(this.term_length) > 3650) && (this.term_length_type === "DAYS")) {
                    this.showErrorModal = true;
                    this.errorModalTitle = "Create Product Error";
                    this.errorModalMessage = "Your term length cannot exceed 3650 Days.";

                    return;
                }

                // console.log(this.updateItem);
                const formData = new FormData();
                formData.append("productType", this.product_type_id);
                formData.append("termLengthType", this.term_length_type.toUpperCase());
                formData.append("compoundFrequency", this.compounding_frequency.toUpperCase());
                formData.append("defaultProductName", this.default_product_name);


                if (this.custom_product_name == null) {
                    formData.append("customProductName", this.default_product_name);
                } else {
                    formData.append("customProductName", this.custom_product_name);
                }
                formData.append("interestPaid", this.interest_paid.toUpperCase());
                if (this.showLockoutDisabled) {
                    formData.append("lockoutPeriod", "");
                } else {

                    formData.append("lockoutPeriod", this.lockout_period);
                }

                formData.append("pds", this.pds_file);

                formData.append("termLength", this.sanitizeAmount(this.term_length));
                // console.log(this.data, "this.data");
                if (this.action && this.action === "edit") {
                    formData.append("product", this.updateItem);
                }
                axios.post(this.action && this.action === "edit" ? '/campaigns/fi/update-fi-campaign-product' : '/campaigns/fi/create-fi-campaign-product', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {

                    if (response?.data?.success == false) {
                        this.showErrorModal = true;
                        if (this.action && this.action === "edit") {
                            this.errorModalTitle = "Edit Product Error";
                        } else {
                            this.errorModalTitle = "Create Product Error";
                        }

                        this.errorModalMessage = response.data.message;
                    } else {
                        if (this.action && this.action === "edit") {
                            this.successModalTitle = "Edited successfully";

                        } else {
                            this.successModalTitle = "Created successfully";
                        }
                        this.$emit('reloadData', new Date());
                        this.$emit('productUpdated', new Date());
                        this.showSuccessModal = true;
                        this.successbtnOneText = "Add New Product";
                        this.successbtnTwoText = "View Products";

                        this.show = false;
                        this.submitted = false;
                        this.resetForm();
                    }

                    // });
                }).catch(error => {

                    this.$swal({
                        title: this.action && this.action === "edit" ? "Edit Product" : 'Create Product.',
                        text: error,
                        confirmButtonText: 'Close'
                    });
                    this.submitted = false;
                });
            },
            generateProdName() {
                // console.log(this?.term_length + "f" + this?.term_length_type + "fg" + this?.product_type_id);
                if (!this.term_length || !this.term_length_type || !this.product_type_id) { return; }

                let this_ = this;
                let filter = Object.values(this.products).filter((item) => item.id == this_.product_type_id);
                let prod_name = filter && filter.length > 0 ? filter[0].description : '';
                // console.log(this.generateProdNameProdTypeshortcut(prod_name));
                return ((this.lockout_period !== null) ? this.generateProdNameNoticeshortcut(this.lockout_period) : "") + this.generateProdNameProdTypeshortcut(prod_name) + this.term_length + (this.term_length_type ? this.term_length_type.substring(0, 1) : '') + "(" + this.generateProdNameRatesShortcut(this.interest_paid) + ")" + "(" + this.generateProdNameRatesShortcut(this.compounding_frequency) + ")";
            },
            generateProdNameNoticeshortcut(notice) {
                return notice + "D";
            },
            generateProdNameProdTypeshortcut(prodname) {

                let prodnamesub = "";
                if (prodname === "Non-Redeemable") {
                    prodnamesub = "NR";
                } else if (prodname === "Short Term") {
                    prodnamesub = "ST";
                }
                else if (prodname === "Cashable") {
                    prodnamesub = "Cash";
                }
                else if (prodname === "Notice Deposit" || prodname === "Notice deposit") {
                    prodnamesub = "ND";
                }
                else if (prodname === "High Interest Savings") {
                    prodnamesub = "HISA";
                } else {
                    prodnamesub = prodnamesub;
                }
                return prodnamesub;

            },
            generateProdNameRatesShortcut(ratetype) {
                let ratet = "";
                if (ratetype === "AT MATURITY") {
                    ratet = "At Mat";
                } else if (ratetype === "ANNUALLY") {
                    ratet = "Ann";
                } else if (ratetype === "SEMI-ANNUALLY") {
                    ratet = "Sem Ann";
                } else if (ratetype === "QUARTERLY") {
                    ratet = "Qua";
                }
                else if (ratetype === "MONTHLY") {
                    ratet = "Qua";
                }
                else if (ratetype === "DAILY") {
                    ratet = "Dai";
                }
                return ratet;
            },
            sanitizeAmount(val) {
                try {
                    return parseFloat(val.replace(",", "", val).replace(" ", "", val));
                } catch (e) {
                    return val;
                }
            },
            getThisProduct() {
                let this_ = this;
                let prod_ids = [this.product_id];
                axios.get("/campaigns/fi/my-products?product_ids=" + prod_ids)
                    .then(response => {

                        if (response?.data?.data.length === 1) {
                            console.log(response);
                            this_.data = response?.data?.data[0];
                            this_.updateItem = response?.data?.data[0].id;
                            this_.product_type_id = response?.data?.data[0]?.product_type_id;
                            this_.lockout_period = this_.data.lockout_period;
                            this_.term_length = this_.data.term_length;
                            this_.pds_file = this_.data.pds_file;
                            this_.default_product_name = this_.data.default_product_name;
                            this_.custom_product_name = this_.data.custom_product_name;
                            this_.term_length_type = this_.data.term_length_type;
                            this_.interest_paid = response?.data?.data[0].interest_paid;
                            this_.compounding_frequency = this_.data.compound_frequency;
                        }
                    }).catch(error => {
                        this_.data = null;
                    });
            },
            showModal() {
                this.show = true;
                if (this.action === "edit") {
                    this.getThisProduct();
                }
            },
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring.charAt(0).toUpperCase() + thestring.slice(1).toLowerCase();
                }

            }

        },
    }


</script>
<style scoped>
    .combined-input {
        padding-top: 7px;
        padding-bottom: 7px;
        display: flex;
        height: 40px;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-radius: 999px;
        border: 0.5px solid #ccc;
        font-size: 16px;
    }

    .disabled-input {
        background-color: #f0f0f0;
        /* Change to your preferred disabled input style */
        cursor: not-allowed;
        /* Change to your preferred cursor style */
        /* Add any other disabled input styles you want */
    }
</style>
