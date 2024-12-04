<template>
    <div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-border-double">
                                    <td colspan="3" class="my_h"><span class="b_b">Req</span>uests<span
                                            class="badge bg-blue badge-pill total_records_pill"></span></td>
                                    <td class="text-right">
                                        <div class="input-group"  style="width:50%!important; float: right;">
                                            <div class="input-group-prepend" >
                                                <span class="input-group-text pt-2" id="basic-addon3" style="border:none; background-color: transparent;">Select Status</span>

                                            </div>
                                            <select v-model="status" @change="getRequests" class="form-control w-50">
                                                <option v-for="status in statuses" :value="status">{{status}}</option>
                                            </select>
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
                                    <option  v-for="item in length" :value="item">{{ item }}</option>
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
                                        <th v-for="(columnHead, index) in getColumnsTableHead"
                                            @click="sortBy(getColumns[index])" :id="'header-' + getColumns[index]">
                                            {{ columnHead }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" v-for="request in requests">
                                        <td>{{ request.request_no }}</td>
                                        <td>{{ request.investors }}</td>
                                        <td>{{ request.amount }}</td>
                                        <td>{{ request.locked_in }}</td>
                                        <td>{{ request.terms }}</td>
                                        <td>{{ request.closure_date }}</td>
                                        <td>{{ request.deposit_date }}</td>
                                        <td v-html="request.status"></td>
                                        <!-- <td v-html="request.deposit_status"></td> -->
                                        <td colspan="3" v-html="request.fis" style="width: 30px !important;"></td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="bottom-section" v-if="requests">
                            <div class="counter">
                                <span>Showing {{ parseInt(startQueryFrom) }} to
                                    {{ startQueryFrom + parseInt(requests.length) }} of
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
            requests: '',
            rowParPage: 10,
            search: '',
            totalData: 1,
            currentPage: 1,
            startQueryFrom: 0,
            sortColumn: '',
            sortOrder: 'asc',
            pages: [],

            length: [10, 25, 50, 100],
            statuses: ['Completed', 'Review Offer', 'Pending Offer', 'Active Deposit', 'Expired', 'Withdrawn',],
            status: "Review Offer",
            columns_table_head: ["Request No", "Investors", "Amount", "Locked In", "Terms", "Closure Date and time", "Date Of Deposit", "Status", "Invited FIS", "Rate", "Selected"],
            columns: ['request_no', 'investors', 'amount', 'locked_in', 'terms', 'closure_date', 'deposit_date', 'status', 'fis']
        }
    },
    mounted() {
        this.createSortIcons();
        this.getRequests();
    },
    methods: {

        async getRequests() {
            let data = new FormData();
            data.append('columns', this.getColumns);
            data.append('search', this.search);
            data.append('rowParPage', this.rowParPage);
            data.append('startQueryFrom', this.startQueryFrom);
            data.append('sortOrder', this.sortOrder);
            data.append('sortColumn', this.sortColumn);
            data.append('status', this.status.toLocaleUpperCase());
            let requests = await axios.post(route('active.request.fetch'), data)
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

            this.requests = requests
        },
        addRowPerPage() {
            this.getRequests();
        },
        addSearch() {
            this.getRequests();
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
            this.getRequests();
        },
        previousPage() {
            if (this.pages[0] >= this.currentPage) {
                return;
            }
            this.currentPage = this.currentPage - 1;
            this.startQueryFrom = this.startQueryFrom - this.rowParPage;
            this.getRequests();
        },
        moveToPage(page) {
            if (page == this.currentPage || page == '...') {
                return;
            }
            this.currentPage = page;
            this.startQueryFrom = parseInt(this.rowParPage) * (page - 1);
            this.getRequests();
        },
        createSortIcons() {
            for (let i = 0; i < this.getHeaderRow.length-3; i++) {
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

            this.getRequests();

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