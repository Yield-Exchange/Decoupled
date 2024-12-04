<template>
    <div class="w-100">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">Pro</span>ducts<span
                                            class="badge bg-blue badge-pill total_records_pill"></span></td>
                                    <td class="text-right">
                                        <button class="btn custom-primary round" style="color: #fff" data-toggle="modal"
                                            :data-target="'#add-product'">Add Product</button>

                                        <div :id="'add-product'" class="modal fade" role="dialog" ref="create_product">
                                            <div class="modal-dialog modal-lg">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><b>Create product</b></h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>
                                                            <form method="post" autocomplete="off"
                                                                @submit.prevent="submit">
                                                                <div class="row">
                                                                    <div class="col-md-12 well">
                                                                        <div class="form-group">
                                                                            <b-form-input maxlength="50" minlength="1"
                                                                                placeholder="Enter Description"
                                                                                class="form-control font-13 col-md-12 text-center "
                                                                                id="description"
                                                                                @keyup="checkDepartment"
                                                                                v-model="description"
                                                                                :class="{ 'verror': descriptionError }">
                                                                            </b-form-input>
                                                                            <b-alert v-if="descriptionError" show
                                                                                variant="danger" class="form-alert">{{
                                                descriptionError }}
                                                                            </b-alert>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12 well">
                                                                        <div class="form-group">
                                                                            <b-form-select v-model="status"
                                                                                :options="options" class="text-center"
                                                                                @select="checkStatus"></b-form-select>
                                                                            <b-alert v-if="statusError" show
                                                                                variant="danger" class="form-alert">{{
                                                statusError }}
                                                                            </b-alert>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="row" v-if="status == 'activate'">
                                                                    <div class="col-md-12 well">
                                                                        <div class="form-group">
                                                                            <b-form-datepicker
                                                                                :class="(deactivationDateError ? 'is-invalid' : '')"
                                                                                style="border-radius: 10px;"
                                                                                placeholder="Set Date to Deactivate (optional)"
                                                                                v-model="deactivationDate"
                                                                                class="font-13 input-height mb-2 text-center"
                                                                                v-bind="{ min: new Date() }">
                                                                            </b-form-datepicker>
                                                                            <b-alert v-if="deactivationDateError" show
                                                                                variant="danger" class="form-alert">{{
                                                deactivationDateError }}
                                                                            </b-alert>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="row" v-if="status == 'deactivate'">
                                                                    <div class="col-md-12 well">
                                                                        <div class="form-group">
                                                                            <b-form-datepicker
                                                                                :class="(activationDateError ? 'is-invalid' : '')"
                                                                                style="border-radius: 10px;"
                                                                                placeholder="Set Date to activate (optional)"
                                                                                v-model="activationDate"
                                                                                class="font-13 input-height mb-2 text-center"
                                                                                v-bind="{ min: new Date() }">
                                                                            </b-form-datepicker>
                                                                            <b-alert v-if="activationDateError" show
                                                                                variant="danger" class="form-alert">{{
                                                activationDateError }}
                                                                            </b-alert>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="row" align="center">
                                                                    <div class="col-md-12 well">
                                                                        <div class="form-group">
                                                                            <br>
                                                                            <input type="button"
                                                                                class="btn btn-md custom-secondary round"
                                                                                data-dismiss="modal" value="Cancel">

                                                                            <b-button :variant="'primary'"
                                                                                :disabled="submitButtonSpinner"
                                                                                :size="'md'" style="font-size:15px;"
                                                                                @click="createProduct"
                                                                                :class="'custom-primary round'">
                                                                                <b-spinner small variant="light"
                                                                                    label="Loading"
                                                                                    style="margin-right:5px"
                                                                                    v-if="submitButtonSpinner">
                                                                                </b-spinner>
                                                                                {{ submitButtonText }}
                                                                            </b-button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>


                                                        </div>
                                                    </div>
                                                    <div class="modal-footer"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12">
                        <div class="top-section">
                            <div class="list">
                                <span>Show</span>
                                <select v-model="rowParPage" @change="addRowPerPage">
                                    <option v-for="item in length" :value="item">{{ item }}</option>
                                </select>
                                <span>entries</span>
                            </div>
                            <div class="search">
                                <span>Search :</span>
                                <input type="text" name="" id="" v-model="search" @keyup.prevent="addSearch">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table users-table table-condensed table-hover table-stripe" ref="userTable">
                                <thead>
                                    <tr role="row" ref="tableHeadRow">
                                        <th>#</th>
                                        <th v-for="(columnHead, index) in getColumnsTableHead"
                                            @click="sortBy(getColumns[index])" :id="'header-' + getColumns[index]">
                                            {{ columnHead }}
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" v-for="product in products">
                                        <td>{{ product.id }}</td>
                                        <td>{{ product.description }}</td>
                                        <td v-html="product.disabled"></td>
                                        <td>{{ product.activationDate }}</td>
                                        <td>{{ product.deactivationDate }}</td>
                                        <td>

                                            <div :id="'edit-product' + product.product_id" class="modal fade"
                                                role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><b>Edit product</b></h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div>
                                                                <form method="post" autocomplete="off"
                                                                    @submit.prevent="submit">
                                                                    <div class="row">
                                                                        <div class="col-md-12 well">
                                                                            <div class="form-group">
                                                                                <b-form-input maxlength="50"
                                                                                    minlength="1"
                                                                                    placeholder="Enter Description"
                                                                                    class="form-control font-13 col-md-12 "
                                                                                    id="description"
                                                                                    @keyup="checkDepartment"
                                                                                    :value="product.description"
                                                                                    :id="'description' + product.product_id"
                                                                                    :class="{ 'verror': descriptionError }"
                                                                                    name="description">
                                                                                </b-form-input>
                                                                                <b-alert v-if="descriptionError" show
                                                                                    variant="danger"
                                                                                    class="form-alert">{{
                                                descriptionError }}
                                                                                </b-alert>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row"
                                                                        v-if="product.disabled_status == 'no'">
                                                                        <div class="col-md-12 well">
                                                                            <div class="form-group">
                                                                                <b-form-datepicker
                                                                                    :class="(deactivationDateError ? 'is-invalid' : '')"
                                                                                    style="border-radius: 10px;"
                                                                                    placeholder="Set Date to Deactivate (optional)"
                                                                                    :id="'deactivationDate' + product.product_id"
                                                                                    :value="product.deactivationDate"
                                                                                    class="font-13 input-height mb-2 text-center"
                                                                                    v-bind="{ min: new Date() }">
                                                                                </b-form-datepicker>
                                                                                <b-alert v-if="deactivationDateError"
                                                                                    show variant="danger"
                                                                                    class="form-alert">{{
                                                deactivationDateError }}
                                                                                </b-alert>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row"
                                                                        v-if="product.disabled_status == 'yes'">
                                                                        <div class="col-md-12 well">
                                                                            <div class="form-group">
                                                                                <b-form-datepicker
                                                                                    :class="(activationDateError ? 'is-invalid' : '')"
                                                                                    style="border-radius: 10px;"
                                                                                    placeholder="Set Date to activate (optional)"
                                                                                    :id="'activationDate' + product.product_id"
                                                                                    :value="product.activationDate"
                                                                                    class="font-13 input-height mb-2 text-center"
                                                                                    v-bind="{ min: new Date() }">
                                                                                </b-form-datepicker>
                                                                                <b-alert v-if="activationDateError" show
                                                                                    variant="danger"
                                                                                    class="form-alert">{{
                                                activationDateError }}
                                                                                </b-alert>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" align="center">
                                                                        <div class="col-md-12 well">
                                                                            <div class="form-group">
                                                                                <br>
                                                                                <input type="button"
                                                                                    class="btn btn-md custom-secondary round"
                                                                                    data-dismiss="modal" value="Cancel">

                                                                                <b-button :variant="'primary'"
                                                                                    :disabled="submitButtonSpinner"
                                                                                    :size="'md'" style="font-size:15px;"
                                                                                    @click="editProduct(product.product_id, product.id, product.disabled_status)"
                                                                                    :class="'custom-primary round'">
                                                                                    <b-spinner small variant="light"
                                                                                        label="Loading"
                                                                                        style="margin-right:5px"
                                                                                        v-if="submitButtonSpinner">
                                                                                    </b-spinner>
                                                                                    Save
                                                                                </b-button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>


                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dropdown">
                                                <button class="btn custom-primary round dropdown-toggle text-white"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item " href="javascript:void()"
                                                        data-toggle="modal"
                                                        :data-target="'#edit-product' + product.product_id">Edit</a>
                                                    <a class="dropdown-item admin-action-on-admin"
                                                        @click="toggle_state(product.product_id, product.disabled_status)">
                                                        {{ product.disabled_status == 'yes' ? "Activate" : "Deactivate"
                                                        }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="bottom-section" v-if="products">
                            <div class="counter">
                                <span>Showing {{ parseInt(startQueryFrom) }} to
                                    {{ startQueryFrom + parseInt(products.length) }} of
                                    {{ totalData }}
                                    entries</span>
                            </div>
                            <div class="paginate">
                                <span class="previous" @click="previousPage">Previous</span>
                                <span class="links">
                                    <button v-for="page in this.pages"
                                        :class="{ 'active disabled': (currentPage == page), 'disabled': (page == '...') }"
                                        @click="moveToPage(page)">{{ page }}</button>
                                </span>
                                <span class="next" @click="nextPage">Next</span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /support tickets -->
            </div>
        </div>
    </div>
</template>

<style>
.verror {
    border: 1px solid red;
    border-radius: 5px;
}

.top-section {
    display: flex;
    justify-content: space-between;
    font-size: 1.1em;
    padding: 1em;
}

.top-section .list {
    font-weight: 300 !important;
}

.top-section .list select {
    padding: .3em .5em;
}

.top-section .search {
    font-weight: 300 !important;
}

.top-section .search input {
    padding: .4em;
}

.bottom-section {
    display: flex;
    justify-content: space-between;
    font-size: 1.1em;
    padding: 1em;
}

.bottom-section .paginate .links button {
    padding: .7em;
    margin: .2em;
    background-color: rgb(231, 231, 231);
    border: 1px solid gray;
}

.bottom-section .paginate>span {
    cursor: pointer;
}

.bottom-section .paginate .previous:hover,
.bottom-section .paginate .next:hover {
    color: lightblue;
}

.bottom-section .paginate .links button:hover {
    background-color: rgb(255, 255, 255);
}

.bottom-section .paginate .links button.active.disabled {
    background-color: rgb(184, 219, 249);
    cursor: default;
}

.bottom-section .paginate .links button.disabled {
    background-color: rgb(231, 231, 231);
    cursor: default;
}

.table thead tr th {
    border-bottom: .3px solid #b3b2b2 !important;
    padding: 10px 2px;
    cursor: pointer;
}
</style>

<script>
import axios from 'axios';
import Swal from 'sweetalert2'

export default {
    data() {
        return {
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

            length: [10, 25, 50, 100],
            columns_table_head: ["Description", "Status", "Activation Date", "Deactivation Date"],
            columns: ['description', 'disabled', "activationDate", 'deactivationDate'],

            activationDate: '',
            activationDateError: '',
            deactivationDate: '',
            deactivationDateError: '',
            status: null,
            statusError: '',
            options: [
                { value: null, text: 'Please select a status' },
                { value: 'activate', text: 'Activate' },
                { value: 'deactivate', text: 'Deactivate' },
            ]
        }
    },
    mounted() {
        this.createSortIcons();
        this.get_products();
    },
    methods: {
        checkDepartment() {

        },
        checkStatus() {

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

        toggle_state(id, status) {
            let data = new FormData();
            data.append('id', id);
            data.append('status', (status == "yes") ? "no" : "yes");
            axios.post(route('product.toggle'), data)
                .then(response => {
                    if (response?.data?.status == false) {
                        throw new Error("Error")
                    }
                    this.$swal({
                        title: "Success!",
                        text: "Product Updated successfully",
                        icon: "success"
                    });
                    window.location.reload();

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    this.$swal({
                        title: "Error!",
                        text: "Failed to add product",
                        icon: "error"
                    });

                });

        },

        createProduct() {
            this.submitButtonText = "loading";
            this.submitButtonSpinner = true;
            let data = new FormData();
            data.append('description', this.description);
            data.append('status', this.status);
            data.append('activationDate', this.activationDate ? new Date(this.activationDate).toLocaleDateString('en-us', { year: "numeric", month: "short", day: "numeric" }) : "");
            data.append('deactivationDate', this.deactivationDate ? new Date(this.deactivationDate).toLocaleDateString('en-us', { year: "numeric", month: "short", day: "numeric" }) : "");
            axios.post(route('product.add'), data)
                .then(response => {
                    if (response?.data?.status == false) {
                        throw new Error("Error")
                    }
                    this.submitButtonText = "Create";
                    this.submitButtonSpinner = false;
                    this.$swal({
                        title: "Success!",
                        text: "Product Added Successfully",
                        icon: "success"
                    })
                    window.location.reload();
                    this.get_products();

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    Swal({
                        title: "Error!",
                        text: "Failed to add product",
                        icon: "error"
                    });

                });

        },

        async get_products() {
            let data = new FormData();
            data.append('columns', this.getColumns);
            data.append('search', this.search);
            data.append('rowParPage', this.rowParPage);
            data.append('startQueryFrom', this.startQueryFrom);
            data.append('sortOrder', this.sortOrder);
            data.append('sortColumn', this.sortColumn);
            let products = await axios.post(route('product.list'), data)
                .then(response => {
                    this.totalData = response.data.iTotalDisplayRecords;
                    this.makePagination(response.data.iTotalDisplayRecords);
                    return response.data.aaData;

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    Swal({
                        title: "Error!",
                        text: "Failed to get Users",
                        icon: "error"
                    });

                });

            this.products = products
        },

        addRowPerPage() {
            this.get_products();
        },

        addSearch() {
            this.get_products();
        },

        makePagination(total) {
            let totalPages = Math.ceil(total / this.rowParPage);
            let pages = Array.from({ length: totalPages }, (v, i) => i + 1);

            let currentPage = this.currentPage;
            let firstPage = pages[0];
            let lastPage = pages.length;
            let shiftRight = currentPage + 3;
            let shiftLeft = currentPage - 2;

            pages = pages.filter((page) => {
                if (page == firstPage || page == lastPage || page >= shiftLeft && page < shiftRight) {
                    return page;
                }

            })

            let finalPages = [];
            let previousPage;
            for (let presentPage of pages) {
                if (previousPage) {
                    if (presentPage - previousPage !== 1) {
                        finalPages.push('...');
                    }
                }
                finalPages.push(presentPage);
                previousPage = presentPage;
            }
            this.pages = finalPages;

        },

        nextPage() {
            if (this.pages[this.pages.length - 1] == this.currentPage) {
                return;
            }
            this.currentPage = parseInt(this.currentPage) + 1;
            this.startQueryFrom = parseInt(this.startQueryFrom) + parseInt(this.rowParPage);
            this.get_products();
        },

        previousPage() {
            if (this.pages[0] >= this.currentPage) {
                return;
            }
            this.currentPage = this.currentPage - 1;
            this.startQueryFrom = this.startQueryFrom - this.rowParPage;
            this.get_products();
        },

        moveToPage(page) {
            if (page == this.currentPage || page == '...') {
                return;
            }
            this.currentPage = page;
            this.startQueryFrom = parseInt(this.rowParPage) * (page - 1);
            this.get_products();
        },

        createSortIcons() {
            for (let i = 0; i < this.getHeaderRow.length - 3; i++) {
                let toggle = document.createElement('span');
                // toggle.style.float = 'right';
                toggle.style.color = 'lightgray';
                toggle.style.marginRight = '5px';
                toggle.innerHTML = "&#8645;";
                let header = this.getHeaderRow[i];
                header.appendChild(toggle)
            }
        },

        sortBy(value) {

            if (this.sortColumn == value) {
                this.toggleSortOrder();
            }

            this.sortColumn = value;

            this.get_products();

            let rowId = 'header-' + value;
            let rowElement = document.getElementById(rowId);
            for (let i = 0; i < this.getHeaderRow.length - 3; i++) {
                let header = this.getHeaderRow[i];
                let child = header.firstElementChild;
                if (header.innerHTML == rowElement.innerHTML) {
                    child.innerHTML = this.sortOrder == 'asc' ? '&#11014;' : '&#11015;';
                    child.style.color = 'gray';
                } else {
                    child.innerHTML = "&#8645;";
                    child.style.color = 'lightgray';
                }
            }
        },

        toggleSortOrder() {
            this.sortOrder = this.sortOrder == 'asc' ? 'desc' : 'asc';
        }

    },
    computed: {
        getColumns() {
            return (typeof this.columns === "string") ? this.columns.split(',') : this.columns;
        },
        getColumnsTableHead() {
            return (typeof this.columns_table_head === "string") ? this.columns_table_head.split(',') : this.columns_table_head;
        },
        getHeaderRow() {
            return this.$refs.tableHeadRow.children;
        },
    },

};
</script>
