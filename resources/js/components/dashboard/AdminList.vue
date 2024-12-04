<template>
    <div class="w-100">
        <div class="top-section">
            <div class="list">
                <span>Show</span>
                <select v-model="rowParPage" @change="addRowPerPage">
                    <option value="1">1</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
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
                    <tr role="row">
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" v-for="user in users">
                        <td>{{ user.sno }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role_name }}</td>
                        <td v-html="user.status"></td>
                        <td>

                            <div :id="'edit-user-' + user.id" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><b>Update User</b></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <user-form :provinces="provinces" :timezones="timezones" :roles="roles"
                                                    :createroute="createroute" :listroute="listroute" :user="user.data"
                                                    :authuser="authuser">
                                                </user-form>
                                            </div>
                                        </div>
                                        <div class="modal-footer"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown" v-if="authuser.id != user.data.id">
                                <button class="btn custom-primary round dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item " href="javascript:void()" data-toggle="modal"
                                        :data-target="'#edit-user-' + user.id">Edit</a>
                                    <a class="dropdown-item admin-action-on-admin"
                                        @click="suspendUser(user.data.encoded_user_id)"
                                        v-if="['ACTIVE', 'LOCKED'].includes(user.data.account_status)">Suspend</a>
                                    <a class="dropdown-item admin-action-on-admin"
                                        @click="activateUser(user.data.encoded_user_id)"
                                        v-if="['SUSPENDED', 'LOCKED'].includes(user.data.account_status)">Activate</a>
                                    <!-- <a href=" javascript:void()" class="dropdown-item"
                                        user-id="' . CustomEncoder::urlValueEncrypt($record->id) . '"
                                        onclick="return closeUser(this)">Close</a> -->
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
                <!-- <span>Showing {{parseInt(startQueryFrom)}} to {{parseInt(rowParPage) + parseInt(startQueryFrom)}} of
                    {{totalData}}
                    entries</span> -->
            </div>
            <div class="paginate">
                <span class="previous" @click="previousPage">Previous</span>
                <span class="links">
                    <button v-for="page in this.pages" :class="{ 'active disabled': (currentPage == page) }"
                        @click="moveToPage(page)">{{ page }}</button>
                </span>
                <span class="next" @click="nextPage">Next</span>
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

.bottom-section .paginate .links button.active.disabled {
    background-color: rgb(184, 219, 249);
    cursor: default;
}

.bottom-section .paginate .links button:hover {
    background-color: rgb(255, 255, 255);
}

.table thead tr th {
    border-bottom: 1px solid #b3b2b2;
}
</style>

<script>
import axios from 'axios';
import Swal from 'sweetalert2'

export default {
    props: ['provinces', 'timezones', 'roles', "createroute", 'listroute', 'listusersroute', 'authuser', 'suspendroute', 'activiateroute', 'deleteroute'],
    data() {
        return {
            users: {},
            rowParPage: 10,
            search: '',
            totalData: 1,
            currentPage: 1,
            startQueryFrom: 0,
            pages: [],
        }
    },
    mounted() {
        this.authuser = JSON.parse(this.authuser);
        this.getUsers();
    },
    methods: {

        async getUsers() {


            let data = new FormData();
            data.append('search', this.search);
            data.append('rowParPage', this.rowParPage);
            data.append('startQueryFrom', this.startQueryFrom);
            let users = await axios.post(JSON.parse(this.listusersroute), data)
                .then(response => {

                    this.totalData = response.data.iTotalDisplayRecords;
                    this.makePagination(response.data.iTotalDisplayRecords);
                    return response.data.aaData;

                }).catch(error => {


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
                            window.location.href = JSON.parse(this.listroute);
                        });

                    } else {
                        throw new Error(response.data.message)
                    }

                }).catch(error => {

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
                            window.location.href = JSON.parse(this.listroute);
                        });

                    } else {
                        throw new Error(response.data.message)
                    }

                }).catch(error => {

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
                buttons: ["No", "Yes"],
                className: "withdraw-modl",

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
                                    window.location.href = JSON.parse(this.listroute);
                                });

                            } else {
                                throw new Error(response.data.message)
                            }

                        }).catch(error => {

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
        makePagination(total) {
            let totalPages = Math.ceil(total / this.rowParPage);
            let pages = Array.from({ length: totalPages }, (v, i) => i + 1);
            this.pages = pages;
        },
        nextPage() {
            if (this.pages.length == this.currentPage) {
                return;
            }
            this.currentPage = this.currentPage + 1;
            this.startQueryFrom = this.startQueryFrom + this.rowParPage;
            this.getUsers();
        },
        previousPage() {
            if (this.pages[0] >= this.currentPage) {
                return;
            }
            this.currentPage = this.currentPage - 1;
            this.startQueryFrom = this.startQueryFrom - this.rowParPage;
            this.getUsers();
        },
        moveToPage(page) {
            if (page == this.currentPage) {
                return;
            }
            this.currentPage = page;
            this.startQueryFrom = this.rowParPage * (page - 1);
            this.getUsers();
        }
    },

};
</script>