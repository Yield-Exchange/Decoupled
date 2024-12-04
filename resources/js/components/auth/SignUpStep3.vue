<template>
    <div class="row dashboard-vue-container">
        <h4 class="font-weight-bold text-center color-black" style="color: black"> Confirm Users</h4>
        <p class="text-center" style="font-weight: normal;">Your YieldExchange account includes <b
                style="font-size: 16px;">{{ this.this_organization.users_limit }}</b> seats.
            Finish adding users, you can add, edit, change and delete users at any time through your settings menu.</p>

        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body">
                    <div class="row">
                        <h4 class="text-left mb-3" style="color: black; font-size: 17px"> Registered Users</h4>
                    </div>

                    <div class="row" v-for="(user, indx) in this_users" v-if="this_users">
                        <div class="row">User {{ indx + 1 }}</div>
                        <div class="row mb-3">
                            <b-col cols="3">
                                <label>Name</label>
                            </b-col>
                            <b-col cols="9">
                                <b-form-input placeholder="Name" class="font-13" :value="user.name" disabled id="name">
                                </b-form-input>
                            </b-col>
                        </div>
                        <div class="row mb-3">
                            <b-col cols="3">
                                <label>Job Title</label>
                            </b-col>
                            <b-col cols="9">
                                <b-form-input placeholder="Job Title" class="font-13"
                                    :value="user.demographic_data.job_title" disabled id="job_title">
                                </b-form-input>
                            </b-col>
                        </div>
                    </div>
                    <div class="row d-none" v-if="organization_object.users_limit > this_users.length">
                        <div class="row">User {{ this_users.length + 1 }}</div>
                        <div class="row text-center">
                            <b-col cols="12" class="text-right " >
                                <b-button :variant="'primary'" style="font-size:15px;" v-b-modal.modal-add-user
                                    class="custom-primary round">
                                    Add User
                                </b-button>

                                <b-modal ref="modal-add-user" id="modal-add-user" size="md" title="Add User"
                                    :hide-footer="true">
                                    <user-form :provinces="provinces" :timezones="timezones" :roles="roles"
                                        :createroute="createroute" :authuser="authUser.id"
                                         :organisation_id="organization_object.id"
                                        @click-close-modal="hideModal">
                                    </user-form>
                                </b-modal>
                            </b-col>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <p class="text-left mb-3" style="color: black; font-size: 14px;font-weight:normal">
                            Need more users? Request additional seats.
                        </p>
                        <div class="row mb-2">
                            <b-col cols="3"></b-col>
                            <b-col cols="4" class="text-right">
                                <b-input-group>
                                    <b-input-group-append @click="adjustSeats(false)"
                                        style="margin-top: 4px;cursor: pointer">
                                        <span class="input-group-text"><i style="font-weight: bold"
                                                class="fa fa-angle-left"></i></span>
                                    </b-input-group-append>
                                    <b-form-input placeholder="Users count" class="font-13" id="NoOfSeats"
                                        v-model="NoOfSeats" type="number">
                                    </b-form-input>
                                    <b-input-group-prepend @click="adjustSeats(true)"
                                        style="margin-top: 4px;cursor: pointer">
                                        <span class="input-group-text"><i style="font-weight: bold"
                                                class="fa fa-angle-right"></i></span>
                                    </b-input-group-prepend>
                                </b-input-group>
                            </b-col>
                            <b-col cols="5" class="text-right">
                                <b-button :variant="'primary'" style="font-size:15px;margin-top: 4px"
                                    @click="addSeatsRequest" class="custom-primary round">
                                    <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                                        v-if="requestSeatButtonSpinner">
                                    </b-spinner>
                                    {{ requestSeatButtonText }}
                                </b-button>
                            </b-col>
                        </div>
                        <div class="row mb-2" v-if="(this_organization.status==='PENDING' && !this_organization.is_partially_approved)">
                            <b-col cols="12">
                                <br />
                                <b-alert show variant="info">Account is pending approval. One of our representatives
                                    will get back to you shortly regarding the next steps for your new account.
                                </b-alert>
                            </b-col>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <b-row style="margin-left: 1%;padding:0">
            <b-col cols="6" style="padding-left: 0">
                <b-button :variant="'outline-secondary'" class="custom-secondary round" :size="'lg'" pill
                    style="font-size:15px;" @click="goBack">
                    Back
                </b-button>
            </b-col>
            <b-col cols="6" class="text-right" style="padding-right: 0">
                <b-button :variant="'primary'" v-if="permittedSubmitButton" :size="'lg'" pill style="font-size:15px;"
                    @click="submitRequest" class="custom-primary round">
                    <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                        v-if="submitButtonSpinner">
                    </b-spinner>
                    {{ submitButtonText }}
                </b-button>
            </b-col>
        </b-row>

    </div>
</template>
<style>

</style>
<style scoped>
label {
    font-weight: normal;
}

.dashboard-vue-container {
    max-width: 550px;
    min-width: 500px;
    margin: 0 auto;
}
</style>
<script>
import { confirmLeavePage } from "../../utils/GlobalUtils";
export default {
    mounted() {
        confirmLeavePage(this, document);
    },
    components: {
    },
    created() {
    },
    props: ["organization", "submitRoute", "authUser", "users", "requestSeatRoute", 'organization_seat_rate', "provinces", "roles", "timezones", "createroute", 'listroute'],
    data() {
        let this_organization = JSON.parse(this.organization);
        let this_users = JSON.parse(this.users);
        // console.log('!this_organization.is_partially_approved',!this_organization.is_partially_approved);
        return {
            submitButtonText: 'Next',
            submitButtonSpinner: false,
            this_organization,
            this_users,
            organization_object: this_organization,
            NoOfSeats: this_organization.users_limit,
            permittedSubmitButton: (this_organization.status==='PENDING' && this_organization.is_partially_approved==1) || this_organization.status==='ACTIVE',
            requestSeatButtonSpinner: false,
            requestSeatButtonText: 'Request Seat',
        }
    },
    methods: {
        hideModal() {
            this.$refs['modal-add-user'].hide()
        },
        /*showModal() {
            this.$refs['my-modal'].show()
        },
        toggleModal() {
            // We pass the ID of the button that we want to return focus to
            // when the modal has hidden
            this.$refs['my-modal'].toggle('#toggle-btn')
        },*/
        adjustSeats(add = true) {
            if (add) {
                this.NoOfSeats++;
            } else {
                if (this.NoOfSeats > 1) {
                    this.NoOfSeats--;
                }
            }
        },
        goBack() {
            window.location.href = '/';
        },
        addSeat() {
            this.requestSeatButtonText = "Please wait..";
            this.requestSeatButtonSpinner = true;
            axios.post(this.requestSeatRoute, {
                seats: this.NoOfSeats
            }).then(response => {
                let data = response?.data;
                if (data.success) {

                    this.$swal({
                        title: data.message_title,
                        text: data.message,
                        confirmButtonText: 'Close',
                    }).then((result) => { });

                    this.this_organization = data.organization;
                    this.organization_object = data.organization;
                    this.NoOfSeats = data.organization.users_limit;
                    this.this_users = data.users;
                }
                this.requestSeatButtonSpinner = false;
                this.requestSeatButtonText = 'Request Seat';
            }).catch(error => {
                this.requestSeatButtonText = 'Request Seat';
                this.requestSeatButtonSpinner = false;
            });
        },
        addSeatsRequest() {
            let seat_rate = parseFloat(this.organization_seat_rate);
            if (this.organization_object.users_limit < this.NoOfSeats) {
                let new_seats = (this.NoOfSeats - this.organization_object.users_limit);
                this.$swal({
                    title: 'Would you like to proceed?',
                    // html: 'Each Additional is $' + seat_rate + ' <br/> Your request will generate an invoice of $' + (seat_rate * new_seats),
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#4975E3',
                    cancelButtonColor: '#E9ECEF',
                    customClass: {
                        actions: 'swal-button-actions',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.addSeat();
                    } else {
                        return;
                    }
                });
            } else if (this.organization_object.users_limit > this.NoOfSeats) {
                let new_seats = this.NoOfSeats;
                if (new_seats < this.this_users.length) {
                    this.$swal({
                        title: "Failed",
                        text: 'You already have ' + this.this_users.length + ' users.',
                        confirmButtonText: 'Close',
                    }).then((result) => {

                    });
                    return;
                }
                this.$swal({
                    title: 'Would you like to proceed?',
                    // html: 'You are reducing the seats for your organization. Each Seat is ' + seat_rate + ' $ <br/> Your request will generate an invoice of ' + (seat_rate * new_seats),
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#4975E3',
                    cancelButtonColor: '#E9ECEF',
                    customClass: {
                        actions: 'swal-button-actions',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.addSeat();
                    } else {
                        return;
                    }
                });
            } else if (this.organization_object.users_limit === this.NoOfSeats) {
                this.$swal({
                    title: "Nothing Updated",
                    text: 'Update the no of seats to proceed.',
                    confirmButtonText: 'Close',
                }).then((result) => {

                });
            }
        },
        submitRequest() {
            this.$swal({
                title: 'Are you finished adding users?',
                html: 'You can add, edit, change and delete users at any time through your settings menu.',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                confirmButtonColor: '#4975E3',
                cancelButtonColor: '#E9ECEF',
                customClass: {
                    actions: 'swal-button-actions',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submitButtonText = "Please wait..";
                    this.submitButtonSpinner = true;
                    axios.post(this.submitRoute, {
                    }).then(response => {
                        let data = response?.data;
                        if (data.success) {

                            this.$swal({
                                title: data.message_title,
                                text: data.message,
                                confirmButtonText: 'Close',
                            }).then((result) => {
                                window.location.href = '/dashboard';
                            });

                        } else {
                            this.submitButtonSpinner = false;
                        }
                        this.submitButtonText = 'Next';
                    }).catch(error => {
                        this.submitButtonText = 'Next';
                        this.submitButtonSpinner = false;
                    });
                } else {
                    return;
                }
            });

        }
    },
    computed: {
    }
}
</script>