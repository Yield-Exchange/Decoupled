<template>
    <div class="w-100">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">Day Count</span> Convention<span
                                            class="badge bg-blue badge-pill total_records_pill"></span></td>
                                    <td class="text-right">
                                        <button class="btn custom-primary round" style="color: #fff" data-toggle="modal"
                                            :data-target="'#add-produt'" @click="addOrEdit('add')">Add
                                            Day Count</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12">
                        <div class="top-section">
                            <div class="list">
                                <span>Show</span>
                                <!-- <select v-model="rowParPage" @change="addRowPerPage">
                                    <option v-for="item in length" :value="item">{{ item }}</option>
                                </select>
                                <span>entries</span> -->
                            </div>
                            <div class="search">
                                <!-- <span>Search :</span> -->
                                <!-- <input type="text" name="" id="" v-model="search" @keyup.prevent="addSearch"> -->
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
                                    <tr role="row" v-for="basket in dayCounts">
                                        <td>{{ basket.id }}</td>
                                        <td>{{ basket.label }}</td>
                                        <td> {{ capitalize(basket.status) }}</td>
                                        <td> {{ basket.slug }}</td>
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
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

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
import AddBasketType from './addDayCountModal.vue';
import { capitalize } from '../../utils/commonUtils';

export default {
    components: {
        AddBasketType
    },
    data() {
        return {
            action: 'add',
            add_product: false,
            dayCounts: '',
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
            columns_table_head: ["Convention Name", 'slug', "Status"],
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
        // this.createSortIcons();
        this.get_dayCounts();
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
        capitalize(value) {
            return capitalize(value)
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
        async get_dayCounts() {
            let data = new FormData();

            await axios.get('/common/trade/get-all-interest-calculation-options')
                .then(response => {
                    this.dayCounts = response.data;

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    Swal({
                        title: "Error!",
                        text: "Failed to get Users",
                        icon: "error"
                    });

                });

            // this.dayCounts = dayCounts
        },
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
