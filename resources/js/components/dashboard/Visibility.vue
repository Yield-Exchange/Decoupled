<template>
    <div>
        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body" style="padding-top: 20px">
                    <p style="font-weight: normal;text-align: center;margin: 0;padding: 0"><i>Note: You can select multiple options. <br/>
                        The table shows the list of organizations that you are visible to. </i></p>
                    <b-row>
                        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="organization.show_province_visibility === 1">
                            <label for="province">Provinces:</label>
                            <v-select multiple ref="province" :options="JSON.parse(this.provinces)" class="font-13" placeholder="All Provinces"
                                      @input="doFilter('visible_for_provinces',$event)"
                                      id="province" style="color: #212529;font-weight: 400;" :value="visible_for_provinces">
                            </v-select>
                        </b-col>
                        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="organization.show_naics_codes_visibility === 1">
                            <label for="province">NAICS Codes To Block:</label>
                            <v-select multiple label="code_description" :options="this.getNaicsCodes" class="font-13"
                                      @input="doFilter('visible_for_naics_codes',$event)"
                                      :placeholder="'None'" id="naics_code" style="color: #212529;font-weight: 400;" :value="visible_for_naics_codes">
                            </v-select>
                        </b-col>
                        <b-col sm="12" class="pr-0 pl-0 mb-3" v-if="organization.show_customers_visibility ===1">
                            <label for="customers">Customers:</label>
                            <v-select ref="customers" :options="['All Customers','Only Financial Institutions','Only none financial institutions']" class="font-13" placeholder="Customers"
                                      @input="doFilter('visible_for_customers',$event)"
                                      id="customers" style="color: #212529;font-weight: 400;" :value="visible_for_customers">
                            </v-select>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" class="text-right" style="padding-right: 0">
                            <b-button :variant="'primary'" :disabled="submitButtonSpinner" :size="'lg'"
                                      style="font-size:15px;border-radius:20px" @click="doSubmit">
                                <b-spinner small variant="light" label="Loading" style="margin-right:5px"
                                           v-if="submitButtonSpinner">
                                </b-spinner>
                                {{ submitButtonText }}
                            </b-button>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header header-elements-inline">
                <div class="card-body" style="padding-top: 20px">
                    <h4>Organizations</h4>
                    <table class="table users-table table-condensed" ref="userTable">
                        <thead>
                        <tr role="row">
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Province</th>
                            <th>NAICS code</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr role="row" v-for="(org,index) in visible_organizations">
                            <td>{{ index+1 }}</td>
                            <td>{{ org.name}}</td>
                            <td style="text-transform: capitalize;">{{ org.type.toLowerCase()}}</td>
                            <td>{{ org?.demographic_data?.province }}</td>
                            <td>{{ org?.n_a_i_c_s_code?.code_description ? org?.n_a_i_c_s_code?.code_description : '-'}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    /*.form-control, .vs__dropdown-toggle, .custom-file-input {*/
    /*    height: auto!important;*/
    /*}*/
</style>
<style scoped>
    /*.card-header {*/
    /*    padding-top: 0 !important;*/
    /*}*/

    /*.dashboard-body .card {*/
    /*    border-radius: 10px;*/
    /*}*/

    /*.btn.btn-secondary[disabled] {*/
    /*    background-color: #979797;*/
    /*    color: black;*/
    /*}*/
</style>
<script>
    export default {
        mounted() {
            this.doFilter("","");
        },
        components: {
        },
        created() {
        },
        props: ['provinces', 'naicsCodes', 'organization','organizations_list','updateAccountSettingRoute'],
        data() {
            let _this = this;
            return {
                visible_for_provinces: this.organization.visible_for_provinces ? this.organization.visible_for_provinces.split(",") : [],
                visible_for_customers: this.organization.visible_for_customers ? this.organization.visible_for_customers : "All Customers",
                visible_for_naics_codes: this.organization.visible_for_naics_codes ? this.naicsCodes.filter(function(ep){
                    let codes_array = _this.organization.visible_for_naics_codes.split(",");
                    return codes_array.length > 0 && codes_array.includes(ep?.code_description);
                }) : [],
                submitButtonText: "Save",
                submitButtonSpinner: false,
                visible_organizations: this.organizations_list ? this.organizations_list : []
            }
        },
        computed: {
            allowSubmit() {
                return true;
            },
            getNaicsCodes() {
                return this.naicsCodes;
                // let naicsCodes = this.naicsCodes;
                // let organizationType = this.organization.type;
                // return naicsCodes && naicsCodes.filter(function (el) {
                //     return true;
                //     // return el.type === organizationType.toUpperCase();
                // });
            }
        },
        methods: {
            async doSubmit() {
                this.submitButtonText = "Please wait..";
                this.submitButtonSpinner = true;
                const formData = new FormData();
                formData.append("organization_id", this.organization.id);
                formData.append("visible_for_provinces", this.visible_for_provinces);
                formData.append("visible_for_customers", this.visible_for_customers);
                let naics_ids = [];
                this.visible_for_naics_codes && this.visible_for_naics_codes.map((e)=>{
                    naics_ids.push(e.code_description)
                });
                formData.append("visible_for_naics_codes", naics_ids);
                formData.append("update_visibility", 1);
                let this_ = this;
                axios.post(this.updateAccountSettingRoute, formData).then(response => {
                    let data = response?.data;
                    this_.submitButtonSpinner = false;
                    this.$swal({
                        title: 'Visibility update.',
                        text: data.message,
                        confirmButtonText: 'Close'
                    }).then(() => {
                        window.location.reload();
                    });
                    this_.submitButtonText = 'Save';
                }).catch(error => {
                    console.log(error);
                    let msg;
                    if (error?.response?.status === 419) {
                        msg = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        msg = error?.response?.data?.message;
                    }

                    this.$swal({
                        title: "Visibility update failed",
                        text: msg,
                        confirmButtonText: 'Close'
                    });

                    this_.submitButtonText = 'Save';
                    this_.submitButtonSpinner = false;
                });

            },
            doFilter(state, value){
                if (state === "visible_for_provinces") {
                    this.visible_for_provinces = value;
                } else if (state === "visible_for_customers") {
                    this.visible_for_customers = value;
                } else if (state === "visible_for_naics_codes") {
                    this.visible_for_naics_codes = value;
                }

                if(!value){
                    value = this.visible_for_customers;
                }

                let this_type = "";
                if (value === "All Customers") {
                    // no need to filter
                } else if (value === "Only Financial Institutions") {
                    this_type = "BANK"
                } else if (value === "Only none financial institutions") {
                    this_type = "DEPOSITOR";
                }

                let _this = this;

                this.visible_organizations = this.organizations_list.filter(function (el) {
                    return ( (this_type.length > 0) ? el?.type === this_type :  true )
                    && ( _this.organization.show_naics_codes_visibility === 1 &&_this.visible_for_naics_codes.length > 0 ? _this.visible_for_naics_codes.filter(function(ep){
                            return el?.naics_code_id !== ep?.id
                        }).length > 0 :  true )
                    && ( _this.organization.show_province_visibility === 1 && _this.visible_for_provinces.length > 0 ? _this.visible_for_provinces.includes(el?.demographic_data?.province) : true )
                });

            }
        }
    }
</script>