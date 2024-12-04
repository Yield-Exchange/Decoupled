<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="lg">
        <div class="w-100 p-4">
            <form method="post" autocomplete="off" @submit.prevent="submit">
                <div class="row">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <b-form-input maxlength="50" minlength="1" placeholder="Enter Product Name"
                                class="form-control font-13 col-md-12 text-center p-2" id="description" @keyup=""
                                v-model="label" :class="{ 'verror': descriptionError }">
                            </b-form-input>
                            <b-alert v-if="labelError" show variant="danger" class="form-alert">{{
        labelError }}
                            </b-alert>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <textarea class="w-100 p-2 form-control" rows="6" v-model="description"
                                placeholder="Enter product description "></textarea>
                            <b-alert v-if="descriptionError" show variant="danger" class="form-alert">{{
        descriptionError }}
                            </b-alert>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 well">
                        <div class="form-group">
                            <b-form-select v-model="status" :options="options" class="text-center p-2"></b-form-select>
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
            label: '',
            labelError: '',
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
            this.submitButtonText = "Update"
            this.label = this.product.label
            this.description = this.product.description
            this.product_id = this.product.id
            this.status = this.product.status == 'ACTIVE' ? 'activate' : 'deactivate'

        },

        createProduct() {
            this.submitButtonText = "loading";
            this.submitButtonSpinner = true;
            let newdata = new FormData();
            let data = null


            let url = null
            let action = null
            url = '/yie-admin/trade/add-day-convention'
            if (this.action == 'add') {
                action = 'add'
                data = {
                    "label": this.label,
                    "description": this.description,
                    "action": this.action,
                    "status": this.status == 'activate' ? 'ACTIVE' : 'INACTIVE',
                    "non_leap_year": '365',
                    "leap_year": '366',
                }
            }
            else {
                data = {
                    "label": this.label,
                    "description": this.description,
                    "action": 'update',
                    "status": this.status == 'activate' ? 'ACTIVE' : 'INACTIVE',
                    "non_leap_year": '365',
                    "leap_year": '366',
                    'id': this.product_id
                }
            }




            axios.post(url, data)
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
                        text: "Day Count Added Successfully",
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