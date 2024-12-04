<template>
    <div class="w-100">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <tbody>
                                <tr class="table-border-double">
                                    <td colspan="3" class="my_h">
                                        {{ getAuthUser.is_super_admin ? getOrganisation.name : "" }} <span
                                            class="b_b">U</span>sers <span
                                            class="badge bg-blue badge-pill total_records_pill"></span></td>
                                    <td class="text-right">
                                        <button type="button"
                                            class="btn custom-primary round mmy_btn pull-right text-white"
                                            v-if="cancreate" :class="{ 'users-limit-exceeded': limit }"
                                            :data-toggle="limit ? '' : 'modal'"
                                            :data-target="limit ? '' : '#create2'"><i class="fa fa-plus"></i> Add
                                            User</button>

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
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                                <span>entries</span>
                            </div>
                            <div class="search">
                                <span>Search :</span>
                                <input type="text" name="" id="" v-model="search" @keyup.prevent="addSearch">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table users-table table-condensed" ref="userTable">
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
                                    <tr v-if="user.data" role="row" v-for="user in users.data">
                                        <td>{{ user.sno }}</td>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.role }}</td>
                                        <td>{{ user.account_opening_date }}</td>
                                        <td>{{ user.account_status }}</td>
                                        <td>{{ user.last_login }}</td>
                                        <td>{{ user.creator }}</td>
                                        <td>
                                            <div :id="'edit-user-' + user.id" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-md d-flex justify-content-center">
                                                    <!-- Modal content-->
                                                    <div class="modal-content" style="width: 500px !important;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><b>Update User</b></h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close" @click="closeModel">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div>
                                                                <user-form :provinces="provinces" :timezones="timezones"
                                                                    :roles="roles" :createroute="createroute"
                                                                    :listroute="listroute" :user="user.data"
                                                                    :authuser="authuser"
                                                                    :organisation_id="getOrganisation.id">
                                                                </user-form>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown" v-if="getAuthUser.id != user.id">
                                                <button class="btn custom-primary dropdown-toggle round text-white"
                                                    type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item " href="javascript:void()"
                                                        data-toggle="modal"
                                                        :data-target="'#edit-user-' + user.id">Edit</a>
                                                    <a class="dropdown-item admin-action-on-admin"
                                                        @click="suspendUser(user.data.encoded_user_id)"
                                                        v-if="['ACTIVE'].includes(user.data.account_status)">Suspend</a>
                                                    <a class="dropdown-item admin-action-on-admin"
                                                        @click="activateUser(user.data.encoded_user_id)"
                                                        v-if="['SUSPENDED'].includes(user.data.account_status)">Activate</a>
                                                    <a class="dropdown-item admin-action-on-admin"
                                                        @click="activateUser(user.data.encoded_user_id)"
                                                        v-if="['LOCK'].includes(user.data.account_status)">Unlock</a>
                                                    <a class="dropdown-item admin-action-on-admin"
                                                        @click="activateUser(user.data.encoded_user_id)"
                                                        v-if="['LOCKED'].includes(user.data.account_status)">Lock</a>
                                                    <a href=" javascript:void()" class="dropdown-item"
                                                        @click="closeUser(user.data.encoded_user_id)">Close</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="bottom-section">
                            <div class="counter">
                                <span>Showing {{ users && users.from }} to
                                    {{ users && users.to }} of
                                    {{ totalData }}
                                    entries</span>
                            </div>
                            <div class="paginate">
                                <span class="links" v-if="users && users.links">
                                    <button v-for="page in users.links" :class="{ 'active disabled': (page.active) }"
                                        @click="moveToPage(page.url)" v-html="page.label" />
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /support tickets -->
            </div>
        </div>
        <div id="create2" class="modal fade hide userModal" role="dialog">
            <div class="modal-dialog modal-md d-flex justify-content-center">
                <!-- Modal content-->
                <div class="modal-content" style="width: 500px !important;">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Add New User</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModel">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <user-form :provinces="provinces" :timezones="timezones" :roles="roles"
                                :createroute="createroute" :listroute="listroute" :authuser="authuser"
                                :organisation_id="getOrganisation.id"></user-form>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
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

.table thead tr {
    border-bottom: 2.1px solid #b3b2b2 !important;
    padding: 10px 2px;
    cursor: pointer;
}
</style>

<script>
import axios from 'axios';
import Swal from 'sweetalert2'

export default {
    props: ['columns', 'columns_table_head', 'fetchusersroute', 'authuser', 'listroute', 'suspendroute', 'activiateroute', 'deleteroute', 'provinces', 'timezones', 'roles', "createroute", 'organisation', 'cancreate', 'limit'],
    data() {
        return {
            users: {},
            rowParPage: 10,
            search: '',
            totalData: 1,
            currentPage: 1,
            startQueryFrom: 0,
            sortColumn: '',
            sortOrder: 'asc',
            pages: [],
        }
    },
    mounted() {
        this.createSortIcons();
        this.getUsers();
    },
    methods: {

        async getUsers(url = JSON.parse(this.fetchusersroute)) {

            let data = new FormData();
            data.append('columns', this.getColumns);
            data.append('search', this.search);
            data.append('rowParPage', this.rowParPage);
            data.append('startQueryFrom', this.startQueryFrom);
            data.append('sortOrder', this.sortOrder);
            data.append('sortColumn', this.sortColumn);
            let users = await axios.post(url, data)
                .then(response => {

                    let data = response?.data?.data;
                    this.totalData = data?.total;
                    this.startQueryFrom = data?.from;
                    // this.makePagination(response.data.iTotalDisplayRecords);
                    return data;

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    this.$swal({
                        title: 'Error',
                        text: error,
                        confirmButtonText: 'Close'
                    });

                });

            this.users = users
        },
        async suspendUser(userId) {
            let data = new FormData();
            data.append('user_id', userId);
            let action = await axios.post(JSON.parse(this.suspendroute), data)
                .then(response => {

                    if (response.data.success) {

                        this.$swal({
                            title: 'Success',
                            text: response.data.message,
                            confirmButtonText: 'Close'
                        }).then(() => {
                            // window.location.href = JSON.parse(this.listroute);
                            window.location.reload();
                        });

                    } else {
                        throw new Error(response.data.message)
                    }

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    this.$swal({
                        title: 'Error',
                        text: error,
                        confirmButtonText: 'Close'
                    });

                });

        },
        async activateUser(userId) {

            let data = new FormData();
            data.append('user_id', userId);
            let action = await axios.post(JSON.parse(this.activiateroute), data)
                .then(response => {

                    if (response.data.success) {

                        this.$swal({
                            title: 'Success',
                            text: response.data.message,
                            confirmButtonText: 'Close'
                        }).then(() => {
                            // window.location.href = JSON.parse(this.listroute);
                            window.location.reload();
                        });

                    } else {
                        throw new Error(response.data.message)
                    }

                }).catch(error => {
                    error = error?.response?.data?.message ? error?.response?.data?.message : error;
                    this.$swal({
                        title: 'Error',
                        text: error,
                        confirmButtonText: 'Close'
                    });

                });

        },
        closeUser(userId) {

            Swal.fire({
                title: "Do you want to close this admin?",
                text: "The admin will no longer be able to access Yield Exchange.",
                className: "withdraw-modl",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#EFEFEF',
                cancelButtonText: 'No',
                reverseButtons: true,
                confirmButtonText: 'Yes'

            }).then((response) => {

                if (response.value) {

                    let data = new FormData();
                    data.append('id', userId);
                    axios.post(JSON.parse(this.deleteroute), data)
                        .then(response => {
                            if (response.data.success) {

                                this.$swal({
                                    title: 'Success',
                                    text: response.data.message,
                                    confirmButtonText: 'Close'
                                }).then(() => {
                                    // window.location.href = JSON.parse(this.listroute);
                                    window.location.reload();
                                });

                            } else {
                                throw new Error(response.data.message)
                            }

                        }).catch(error => {
                            error = error?.response?.data?.message ? error?.response?.data?.message : error;
                            this.$swal({
                                title: 'Error',
                                text: error,
                                confirmButtonText: 'Close'
                            });

                        });

                }

            });

        },
        addRowPerPage() {
            this.getUsers();
        },
        addSearch() {
            this.getUsers();
        },
        closeModel() {
            this.$emit('closeModel')
        },
        // makePagination(total) {
        //     let totalPages = Math.ceil(total / this.rowParPage);
        //     let pages = Array.from({ length: totalPages }, (v, i) => i + 1);
        //
        //     let currentPage = this.currentPage;
        //     let firstPage = pages[0];
        //     let lastPage = pages.length;
        //     let shiftRight = currentPage + 3;
        //     let shiftLeft = currentPage - 2;
        //
        //     pages = pages.filter((page) => {
        //         if (page == firstPage || page == lastPage || page >= shiftLeft && page < shiftRight) {
        //             return page;
        //         }
        //
        //     })
        //
        //     let finalPages = [];
        //     let previousPage;
        //     for (let presentPage of pages) {
        //         if (previousPage) {
        //             if (presentPage - previousPage !== 1) {
        //                 finalPages.push('...');
        //             }
        //         }
        //         finalPages.push(presentPage);
        //         previousPage = presentPage;
        //     }
        //     this.pages = finalPages;
        // },
        // nextPage() {
        //     if (this.pages[this.pages.length - 1] == this.currentPage) {
        //         return;
        //     }
        //     this.startQueryFrom = parseInt(this.startQueryFrom) + parseInt(this.rowParPage);
        //     this.getUsers();
        //     this.currentPage = this.currentPage + 1;
        // },
        // previousPage() {
        //     if (this.pages[0] >= this.currentPage) {
        //         return;
        //     }
        //     this.startQueryFrom = this.startQueryFrom - this.rowParPage;
        //     this.getUsers();
        //     this.currentPage = this.currentPage - 1;
        // },
        moveToPage(url) {
            if (!url) {
                return;
            }
            // if (page == this.currentPage) {
            //     return;
            // }
            // this.startQueryFrom = this.rowParPage * (page - 1);
            this.getUsers(url);
            // this.currentPage = page;
        },
        createSortIcons() {
            for (let i = 1; i < this.getHeaderRow.length - 1; i++) {
                let toggle = document.createElement('span');
                toggle.style.float = 'right';
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

            this.getUsers();

            let rowId = 'header-' + value;
            let rowElement = document.getElementById(rowId);
            for (let i = 1; i < this.getHeaderRow.length - 1; i++) {
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
        getAuthUser() {
            return JSON.parse(this.authuser);
        },
        getOrganisation() {
            return JSON.parse(this.organisation);
        },
        getColumns() {
            return this.columns.split(',');
        },
        getColumnsTableHead() {
            return this.columns_table_head.split(',');
        },
        getHeaderRow() {
            return this.$refs.tableHeadRow.children;
        },
    },

};
</script>