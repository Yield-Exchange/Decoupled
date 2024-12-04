<template>
    <div class="w-100">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">Basket</span> types<span
                                            class="badge bg-blue badge-pill total_records_pill"></span></td>
                                    <td class="text-right">
                                        <button class="btn custom-primary round" style="color: #fff" data-toggle="modal"
                                            :data-target="'#add-produt'" @click="addOrEdit('add')">Add
                                            Basket Type</button>
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
                                    <tr role="row" v-for="basket in baskets">
                                        <td>{{ basket.id }}</td>
                                        <td>{{ basket.basket_name }}</td>
                                        <td> {{ basket.is_disabled == 1 ? 'Inactive' : 'Active' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn custom-primary round dropdown-toggle text-white"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item " href="javascript:void()"
                                                        data-toggle="modal" :data-target="'#edit-' + basket.id"
                                                        @click="addOrEdit('edit', basket)">Edit</a>
                                                    <!-- <a class="dropdown-item admin-action-on-admin"
                                                        @click="toggle_state(basket.encoded_id, basket.is_disabled)">
                                                        {{ basket.is_disabled == 1 ? "Activate" : "Deactivate"
                                                        }}
                                                    </a> -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="bottom-section d-none" v-if="baskets">
                            <div class="counter">
                                <span>Showing {{ parseInt(startQueryFrom) }} to
                                    {{ startQueryFrom + parseInt(baskets.length) }} of
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
                    <AddBasketType v-if="add_product" :product="selected_product" :action="action"
                        @closeModal="add_product = false" :show="add_product" />

                </div>
                <!-- /support tickets -->

            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';
import Swal from 'sweetalert2'
// import addBasketType from './addBasketType.vue'
import AddBasketType from './addBasketType.vue';

export default {
    components: {
        AddBasketType
    },
    data() {
        return {
            action: 'add',
            add_product: false,
            baskets: '',
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
            product_desc: null,
            selected_product: null,
            length: [10, 25, 50, 100],
            columns_table_head: ["Basket Name", "Status"],
            columns: ['description', 'is_disabled', "activationDate", 'deactivationDate'],
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
        this.get_baskets();
        // this.getProductList()
    },
    methods: {
        addOrEdit(action, product = null) {
            this.action = action
            this.add_product = true
            if (product)
                this.selected_product = product
        },
        closeModal(action) {
            this.action = 'add'
            this.add_product = false
            this.selected_product = null
        },
        checkDepartment() {

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

        toggle_state(id, status) {
            let data = new FormData();
            data.append('id', id);
            data.append('is_disabled', (status == 1) ? 0 : 1);
            // data.append('is_disabledUntil', );
            axios.post('/yie-admin/trade/activate-deactivate-product/' + id, data)
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
            data.append('productName', this.description);
            data.append('description', this.product_desc);
            data.append('is_disabled', this.status == 'activate' ? 0 : 1);
            data.append('is_disabledUntil', this.status == 'activate' ? this.formatDate(this.deactivationDate) : this.formatDate(this.activationDate))
            // data.append('deactivationDate', this.deactivationDate ? new Date(this.deactivationDate).toLocaleDateString('en-us', { year: "numeric", month: "short", day: "numeric" }) : "");
            axios.post('/yie-admin/trade/add-new-product', data)
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
                    // window.location.reload();
                    this.get_baskets();

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

        async getProductList() {
            await axios.get('/common/trade/get-basket-types', response => {
                console.log(response)
            })
        },

        async get_baskets() {
            let data = new FormData();

            await axios.get('/common/trade/get-basket-types')
                .then(response => {
                    this.baskets = response.data;

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    Swal({
                        title: "Error!",
                        text: "Failed to get Users",
                        icon: "error"
                    });

                });

            // this.baskets = baskets
        },

        addRowPerPage() {
            this.get_baskets();
        },

        addSearch() {
            this.get_baskets();
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
            this.get_baskets();
        },

        previousPage() {
            if (this.pages[0] >= this.currentPage) {
                return;
            }
            this.currentPage = this.currentPage - 1;
            this.startQueryFrom = this.startQueryFrom - this.rowParPage;
            this.get_baskets();
        },

        moveToPage(page) {
            if (page == this.currentPage || page == '...') {
                return;
            }
            this.currentPage = page;
            this.startQueryFrom = parseInt(this.rowParPage) * (page - 1);
            this.get_baskets();
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

            this.get_baskets();

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
