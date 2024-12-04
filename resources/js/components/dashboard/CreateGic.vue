<template>
    <div class="row" style="width:80%;margin-bottom: 30px;">
        <h4 class="font-weight-bold text-center color-black" style="color: black"> Create {{ deposit.offer.invited.deposit_request.term_length_type==="HISA" ? "HISA" : "GIC" }}</h4>
        <p class="text-center" style="font-weight: normal">Please confirm the details for the {{ deposit.offer.invited.deposit_request.term_length_type==="HISA" ? "HISA" : "GIC" }}</p>

        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body" style="padding-top: 20px">
                    <b-row>
                        <b-col cols="3">
                            <OrganizationAvatar :size="80" :organization="organization_object" />
                        </b-col>
                        <b-col cols="5">
                            <h5 style="color: black">{{ organization.name }}</h5>
                            <p class="mb-1" style="color: black; font-weight: normal;">{{ deposit.offer.invited.deposit_request.user.demographic_data.phone }}</p>
                            <p style="color: black; font-weight: normal;">{{ deposit.offer.invited.deposit_request.user.email }}</p>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body">
                    <div class="row mt-3 mb-2">
                        <h4 class="text-center" style="color: black"> {{ deposit.offer.invited.deposit_request.term_length_type==="HISA" ? "HISA" : "GIC" }} Details</h4>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Deposit ID</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-input placeholder="Deposit ID" class="font-13"
                                      :value="deposit.reference_no"
                                          disabled
                                      id="deposit_id"
                            >
                            </b-form-input>
                        </b-col>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Product</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-input placeholder="Deposit ID" class="font-13"
                                          :value="deposit.offer.invited.deposit_request.product_name"
                                          disabled
                                          id="deposit_id"
                            >
                            </b-form-input>
                        </b-col>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Lockout Period</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-input placeholder="Lockout Period" class="font-13"
                                          :value="deposit.offer.invited.deposit_request.lockout_period_days ? deposit.offer.invited.deposit_request.lockout_period_days : '-'"
                                          disabled
                                          id="lockout_period"
                            >
                            </b-form-input>
                        </b-col>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Term Length</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-input placeholder="Term Length" class="font-13"
                                          :value="deposit.offer.invited.deposit_request.term_length_type === 'HISA' ? '-' : (deposit.offer.invited.deposit_request.term_length+' '+deposit.offer.invited.deposit_request.term_length_type)"
                                          disabled
                                          id="term_length"
                            >
                            </b-form-input>
                        </b-col>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Amount</label>
                        </b-col>
                        <b-col cols="8">
                            <b-input-group size="lg" :prepend="deposit.offer.invited.deposit_request.currency">
                                <b-form-input placeholder="Amount" class="font-13"
                                              :value="deposit.offer.invited.deposit_request.amount"
                                              disabled
                                              id="amount"
                                >
                                </b-form-input>
                            </b-input-group>
                        </b-col>
                    </div>
                    <div class="row mt-3 text-center">
                        <p style="font-weight: normal">Please enter the following information</p>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Interest Rate</label>
                        </b-col>
                        <b-col cols="8">
                            <b-input-group size="lg" append="%">
                                <b-form-input placeholder="Interest Rate" class="font-13"
                                              v-model="interest_rate"
                                              id="interest_rate"
                                              type="number"
                                >
                                </b-form-input>
                            </b-input-group>
                        </b-col>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>Start Date</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-datepicker 
                            :min="this.startDateMinDate" 
                            :max="this.startDateMaxDate" 
                            id="start_date" 
                            :value="start_date"
                            v-on:input="onChangeStartDate($event)"
                            class="mb-2"
                            size="lg"
                            calendar-width="370px"
                            >
                            </b-form-datepicker>
                        </b-col>
                    </div>
                    <div class="row mb-2" v-if="this.deposit.offer.invited.deposit_request.term_length_type!='HISA'">
                        <b-col cols="4">
                            <label>Maturity Date</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-datepicker 
                            :min="this.maturityDateMinDate" 
                            :max="this.maturityDateMaxDate" 
                            id="maturity_date" 
                            :value="maturity_date"
                            v-on:input="onChangeMaturityDate($event)"
                            class="mb-2"
                            size="lg"
                            calendar-width="370px"
                            >
                            </b-form-datepicker>
                        </b-col>
                    </div>
                    <div class="row mb-2">
                        <b-col cols="4">
                            <label>{{ deposit.offer.invited.deposit_request.term_length_type==="HISA" ? "HISA" : "GIC" }} Number</label>
                        </b-col>
                        <b-col cols="8">
                            <b-form-input 
                                        :placeholder="deposit.offer.invited.deposit_request.term_length_type === 'HISA' ? 'HISA Number' : 'GIC Number'" 
                                        class="font-13"
                                          v-model="gic_number"
                                          maxlength="20"
                                          id="gic_number"
                            >
                            </b-form-input>
                        </b-col>
                    </div>
                </div>
            </div>
        </div>

        <b-row style="margin-left: 1%;padding:0">
            <b-col cols="6" style="padding-left: 0">
                <b-button :variant="'outline-secondary'"
                          :size="'lg'"
                          pill
                          style="font-size:15px;"
                          @click="goBack"
                >
                    Back
                </b-button>
            </b-col>
            <b-col cols="6" class="text-right" style="padding-right: 0">
                <b-button :variant="!allowSubmit ? 'secondary' : 'primary'"
                          :disabled="!allowSubmit || submitButtonSpinner"
                          v-if="permittedSubmitButton"
                          :size="'lg'"
                          pill
                          style="font-size:15px;"
                          @click="doSubmit"
                >
                    <b-spinner small variant="light" label="Loading"
                               style="margin-right:5px"
                               v-if="submitButtonSpinner"
                    >
                    </b-spinner>
                    {{ submitButtonText }}
                </b-button>
            </b-col>
        </b-row>

    </div>
</template>
<style>
    .swal2-html-container {
        font-size: 15px!important;
    }
    .row label{
        /* margin-top: 10px; */
    }
    .swal2-cancel{
        color: black !important;
    }
    .form-control {
        /*padding: 0.3375rem !important;*/
    }
    .swal-button-actions{
        flex-direction: row-reverse!important;
        display: flex!important;
    }
</style>
<style scoped>    
    .card-header{
        padding-top: 0!important;
    }
    .dashboard-body .card-body {
        padding: 0;
    }
    .dashboard-body .card {
        border-radius: 10px;
    }
    .btn.btn-secondary[disabled] {
        background-color: #979797;
        color: black;
    }
    .input-group-lg>.form-control:not(textarea), .b-form-datepicker {
        height: 40px;
        font-size: 15px;
    }
    .input-group-append,.input-group-prepend{
        margin-top: 4px;
    }
</style>
<script>
    // import moment from 'moment';
    import moment from 'moment-timezone';
    import Avatar from "vue-avatar";
    import {confirmLeavePage} from "../../utils/GlobalUtils";
    import OrganizationAvatar from "../shared/OrganizationAvatar";
    export default {
        mounted(){
            confirmLeavePage(this,document);
        },
        components: {
            Avatar, OrganizationAvatar
        },
        created() {
            let format = this.format;
            let formatDate = this.formatDate;
            //  let format_ = 'YYYY-MM-DD HH:mm:ss ZZ';
            let timeZone = this?.userObject?.formatted_timezone;
            let todayDateWithUserTimezone = moment(moment().toISOString(), format).tz(timeZone);

            this.maturityDateMinDate = moment.utc(this.deposit.offer.invited.deposit_request.closing_date_time).local();
            this.startDateMinDate = moment.utc(this.deposit.offer.invited.deposit_request.closing_date_time).local();
            this.startDateMaxDate = todayDateWithUserTimezone;
            if(this.startDateMinDate > this.startDateMaxDate){
                this.startDateMaxDate = this.startDateMinDate;
            }

            this.startDateMinDate = this.startDateMinDate.format(formatDate);
            this.startDateMaxDate = this.startDateMaxDate.format(formatDate);
            this.maturityDateMinDate = this.maturityDateMinDate.format(formatDate);
            // console.log(this.maturityDateMinDate);  //undefined;
        },
        props: ["organization","deposit","submitRoute","fromPage","userObject","permittedSubmitButton"],
        data() {
            return {
                interest_rate: this.deposit.offer.interest_rate_offer,
                gic_number: '',
                start_date: null,
                maturity_date: null,
                submitButtonText: 'Submit',
                submitButtonSpinner: false,
                rateUpdateConfirmed: false,
                organization_object: this.organization,
                startDateMinDate: null,
                startDateMaxDate: null,
                maturityDateMinDate: null,
                maturityDateMaxDate: null,
                formatDate: 'YYYY-MM-DD',
                format: 'YYYY-MM-DD HH:mm:ss ZZ'
            }
        },
        methods: {
            onChangeMaturityDate(value){
                this.maturity_date = value;
            },
            onChangeStartDate(value){
                 // let format = this.format;
                let formatDate = this.formatDate;

                this.start_date = value;
                var date = moment(value);
                var date_;
                var _date;

                var termlength = this.deposit.offer.invited.deposit_request.term_length;
                var termlengthtype = this.deposit.offer.invited.deposit_request.term_length_type;

                if(termlengthtype==="MONTHS"){
                    date.add( parseInt(termlength),"months" );
                    date_ = date.clone().subtract(7,"days");
                }else{
                    date.add( parseInt(termlength),"days");
                    if ( termlength > 7) {
                        date_ = date.clone().subtract(7, "days");
                    }else{
                        date_ = moment(value);
                    }
                }
                    
                _date = date.clone().add(7, "days");
                this.maturity_date = date.format(formatDate);
                this.maturityDateMinDate = date_.format(formatDate);
                this.maturityDateMaxDate = _date.format(formatDate);
            },
            canSubmit(){
                if(this.deposit.offer.invited.deposit_request.term_length_type!="HISA"){
                    return this.gic_number && this.start_date && this.interest_rate && this.maturity_date && (this.maturity_date >= this.start_date);
                }else{
                    return this.gic_number && this.start_date && this.interest_rate;
                }
            },
            async doSubmit() {
                if (!this.canSubmit()) {
                    return;
                }

                if(this.interest_rate != this.deposit.offer.interest_rate_offer && !this.rateUpdateConfirmed){
                    this.$swal({
                        title: 'The Interest rate is different.',
                        html: 'The Interest rate entered is different than the offer rate. <br/>Do you wish to update the interest rate?',
                        showCancelButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        confirmButtonColor: '#4975E3',
                        cancelButtonColor: '#E9ECEF',
                        customClass: {
                            actions: 'swal-button-actions',
                            // cancelButton: 'order-1 right-gap',
                            // confirmButton: 'order-2',
                            // denyButton: 'order-3',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // confirmed
                            this.rateUpdateConfirmed=true;
                            this.submitRequest();
                        } else{
                            this.rateUpdateConfirmed=false;
                            // this.interest_rate = this.deposit.offer.interest_rate_offer;
                            return;
                        }
                    });
                }else{
                    this.submitRequest();
                }

            },
            goBack(){
                window.location.href = this.fromPage;
            },
            submitRequest(){
                // process
                this.submitButtonText = "Please wait..";
                this.submitButtonSpinner = true;
                axios.post(this.submitRoute,{
                    interest_rate: this.interest_rate,
                    gic_start_date: this.start_date,
                    maturity_date: this.maturity_date,
                    gic_number: this.gic_number,
                    deposit_id: this.deposit.id
                }).then(response => {
                    let data = response?.data;
                    if(data.success){

                        this.$swal({
                            title: data.message_title,
                            text: data.message,
                            confirmButtonText: 'Close',
                        }).then((result) => {
                            window.location.href = data.url ? data.url : this.fromPage;
                        });

                    }else{
                        this.submitButtonSpinner=false;
                    }
                    this.submitButtonText='Submit';
                }).catch(error =>{
                    this.submitButtonText='Submit';
                    this.submitButtonSpinner = false;
                });
            }
        },
        computed: {
            allowSubmit() {
                return this.canSubmit();
            }
        }
    }
</script>