<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="lg">
        <div class="w-100 p-4">
            <form method="post" autocomplete="off" @submit.prevent="submit">
                <div class="row">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <b-form-input maxlength="50" minlength="1" placeholder="Enter Collateral Name"
                                class="form-control font-13 col-md-12 text-center p-2" id="description" @keyup=""
                                v-model="description" :class="{ 'verror': descriptionError }">
                            </b-form-input>
                            <b-alert v-if="descriptionError" show variant="danger" class="form-alert">{{
        descriptionError }}
                            </b-alert>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <textarea class="w-100 p-2 form-control" rows="6" v-model="basket_type_desc"
                                placeholder="Enter collateral description "></textarea>
                            <b-alert v-if="descriptionError" show variant="danger" class="form-alert">{{
        descriptionError }}
                            </b-alert>
                        </div>
                    </div>
                </div>

<!-- 
                <div class="row" v-if="action == 'edit' && product.is_disabled == 0">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <b-form-datepicker :class="(deactivationDateError ? 'is-invalid' : '')"
                                style="border-radius: 10px;" placeholder="Set Date to Deactivate (optional)"
                                :id="'deactivationDate' + product.id" :value="product.disabled_until"
                                class="font-13 input-height mb-2 text-center" v-bind="{ min: new Date() }">
                            </b-form-datepicker>
                            <b-alert v-if="deactivationDateError" show variant="danger" class="form-alert">{{
        deactivationDateError }}
                            </b-alert>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <b-form-select v-model="status" :options="options" class="text-center p-2"
                                @change="checkStatus"></b-form-select>
                            <b-alert v-if="statusError" show variant="danger" class="form-alert">
                                {{ statusError }}
                            </b-alert>
                        </div>
                    </div>
                </div>
                <div class="row" align="center">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <br>
                            <input type="button" class="btn btn-md custom-secondary round" data-dismiss="modal"
                                value="Cancel">

                            <b-button :variant="'primary'" :disabled="submitButtonSpinner" :size="'md'"
                                style="font-size:15px;" @click="createProduct" :class="'custom-primary round'">
                                <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                                    v-if="submitButtonSpinner">
                                </b-spinner>
                                {{ submitButtonText }}
                            </b-button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Counter has  been submitted!" btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Counter has not been submitted!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </Modal>

</template>

<script>
// import ActionMessage from ''
import Modal from '../shared/Modal.vue'
import ActionMessage from '../shared/messageboxes/ActionMessageBox.vue'

export default {

    props: ['show', 'action', 'product'],
    beforeMount() {
        console.log(this.action, "Action")
        if (this.action == 'edit') {
            this.setDefaults()
        }
    },
    components: {
        Modal,
        ActionMessage
    },
    data() {
        return {
            success: false,
            fail: false,
            add_product: false,
            products: '',
            rowParPage: 10,
            search: '',
            totalData: 1,
            currentPage: 1,
            startQueryFrom: 0,
            sortColumn: '',
            sortOrder: 'asc',
            pages: [],
            description: '',
            descriptionError: '',
            submitButtonSpinner: false,
            submitButtonText: "Create",
            basket_type_desc: null,
            length: [10, 25, 50, 100],
            columns_table_head: ["Description", "Status", " Date", "Deactivation Date"],
            columns: ['description', 'disabled', "activationDate", 'deactivationDate'],
            activationDate: '',
            activationDateError: '',
            deactivationDate: '',
            deactivationDateError: '',
            status: null,
            statusError: '',
            options: [
                { value: null, text: 'Please select a status' },
                { value: 'activate', text: 'Active' },
                { value: 'deactivate', text: 'In Active' },
            ],
            product_id: null

        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal', false)
        },
        setDefaults() {
            this.description = this.product.collateral_name
            this.basket_type_desc = this.product.collateral_description
            this.product_id = this.product.encoded_id
            this.status = this.product.is_disabled == 0 ? 'activate' : 'deactivate'

        },

        checkStatus() {
            console.log(this.status)
        },

        editProduct(id, rowid, status) {
            var description = document.querySelector("#description" + id).value;
            var activationDate = status == "yes" ? new Date(document.querySelector("#activationDate" + id + "__value_").textContent).toLocaleDateString('en-us', { year: "numeric", month: "short", day: "numeric" }) : "";
            var deactivationDate = status == "no" ? new Date(document.querySelector("#deactivationDate" + id + "__value_").textContent).toLocaleDateString('en-us', { year: "numeric", month: "short", day: "numeric" }) : "";
            let data = new FormData();
            data.append('id', id);
            data.append('description', description);
            data.append('activationDate', activationDate);
            data.append('deactivationDate', deactivationDate);
            axios.post(route('product.add'), data)
                .then(response => {
                    if (response?.data?.status == false) {
                        throw new Error("Error")
                    }
                    this.$swal({
                        title: "Success!",
                        text: "Product Edited successfully",
                        icon: "success"
                    });
                    window.location.reload();

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    this.$swal({
                        title: "Error!",
                        text: "Failed to Edit product",
                        icon: "error"
                    });

                });
        },



        createProduct() {
            this.submitButtonText = "loading";
            this.submitButtonSpinner = true;
            let newdata = new FormData();
            let data = null


            let url = null
            let action = null
            if (this.action == 'add') {
                url = '/yie-admin/trade/add-new-collateral'
                action = 'add'
                data = {
                    "collateralName": this.description,
                    "collateralDescription": this.basket_type_desc,
                    "disabled": this.status == 'activate' ? 0 : 1,
                }
            }
            else {
                url = '/yie-admin/trade/add-new-collateral'
                action = 'update'
                data = {
                    "collateralName": this.description,
                    "collateralDescription": this.basket_type_desc,
                    "disabled": this.status == 'activate' ? 0 : 1,
                    'id': this.product_id
                }
            }
            let data_to_send = [
                data
            ]
            // data_to_send.push(JSON.stringify(data))
            newdata.append('basketTypes', JSON.stringify(data_to_send));
            newdata.append('action', action);

            axios.post(url, newdata)
                .then(response => {
                    if (response?.data?.status == false) {
                        Swal({
                            title: "Error!",
                            text: response.data.message,
                            icon: "error"
                        });
                    }
                    this.submitButtonText = "Create";
                    this.submitButtonSpinner = false;
                    this.$swal({
                        title: "Success!",
                        text: "Product Added Successfully",
                        icon: "success"
                    })
                    this.closeModal()
                    window.location.reload()

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    Swal({
                        title: "Error!",
                        text: "Failed to add product",
                        icon: "error"
                    });

                });

        },
        formatDate(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        },









    },

}

</script>
<style>
.t-clock p {
    font-size: 16px !important;
    font-family: Montserrat;
    font-weight: 500;
    word-wrap: break-word
}
</style>
<style scoped>
.sect-header-counter {
    color: #5063F4;
    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}

.pr-deposit-summary-investment p {
    width: 100%;
    color: #252525;
    font-size: 16px;
    font-family: Montserrat;
    font-weight: 500;
    word-wrap: break-word
}

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>