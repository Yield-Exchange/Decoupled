<template>
    <div>
        <div
            style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%">
                <div class="page-title">
                    <div class="title-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M22.4972 20.2614C22.4972 20.4824 22.585 20.6944 22.7413 20.8506C22.8976 21.0069 23.1096 21.0947 23.3306 21.0947C23.5516 21.0947 23.7635 21.0069 23.9198 20.8506C24.0761 20.6944 24.1639 20.4824 24.1639 20.2614V18.178H25.6581C26.7714 18.178 27.3289 16.8314 26.5414 16.0447L22.5472 12.048C22.4312 11.9319 22.2933 11.8397 22.1416 11.7769C21.9899 11.714 21.8273 11.6816 21.6631 11.6816C21.4988 11.6816 21.3362 11.714 21.1845 11.7769C21.0328 11.8397 20.895 11.9319 20.7789 12.048L16.7864 16.0447C15.9972 16.8314 16.5556 18.178 17.6689 18.178H19.1639V24.0114C19.1639 24.2324 19.2517 24.4444 19.408 24.6006C19.5643 24.7569 19.7762 24.8447 19.9972 24.8447H21.3197L18.3306 27.833L15.3422 24.8447H16.6639C16.8849 24.8447 17.0969 24.7569 17.2532 24.6006C17.4094 24.4444 17.4972 24.2324 17.4972 24.0114V21.0947C17.4972 20.8737 17.4094 20.6617 17.2532 20.5055C17.0969 20.3492 16.8849 20.2614 16.6639 20.2614C16.4429 20.2614 16.2309 20.3492 16.0746 20.5055C15.9184 20.6617 15.8306 20.8737 15.8306 21.0947V23.178H14.3364C13.2231 23.178 12.6647 24.5239 13.4531 25.3114L17.4464 29.3064C17.5625 29.4225 17.7003 29.5147 17.852 29.5775C18.0037 29.6404 18.1663 29.6728 18.3306 29.6728C18.4948 29.6728 18.6574 29.6404 18.8091 29.5775C18.9608 29.5147 19.0987 29.4225 19.2147 29.3064L23.2089 25.3114C23.9964 24.5239 23.4381 23.178 22.3247 23.178H20.8306V20.678C20.8307 20.6705 20.8307 20.663 20.8306 20.6555V17.3447C20.8306 17.1237 20.7428 16.9117 20.5865 16.7555C20.4302 16.5992 20.2182 16.5114 19.9972 16.5114H18.6747L21.6639 13.5222L24.6522 16.5114H23.3306C23.1096 16.5114 22.8976 16.5992 22.7413 16.7555C22.585 16.9117 22.4972 17.1237 22.4972 17.3447V20.2614Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                    <div class="text-div">Checklist</div>
                </div>
            </div>
        </div>
        <div class="bg-white mt-2" style="padding: 40px !important;">
            <div class="w-100 d-flex justify-content-center flex-column ">
                <div class="w-100 d-flex justify-content-center flex-column align-items-center">
                    <p class="m-0 p-0 transfer-header">Review your information for {{ bankname }} </p>
                    <!-- <p class="m-0 p-0 transfer-header-desc ">This will be sent to {{ bankname }} to start processing your
                        deposit</p> -->
                </div>
                <div class="w-100 d-flex justify-content-center ">
                    <img src="/assets/dashboard/images/wire-transfer.svg" alt="">
                </div>

                <div class="w-100">
                    <p class="transfer-desc">Please confirm all details are up to date</p>
                    <div class="w-100">
                        <table class="w-100 transfer-table">
                            <thead>
                                <tr>
                                    <th class="transfer-table-s2">
                                        Profile Details
                                    </th>
                                    <th class="transfer-table-s2">
                                        Completed
                                    </th>
                                    <th class="transfer-table-s2">
                                        Last Updated
                                    </th>
                                    <th class="transfer-table-s2">
                                        Good to Go?
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-capitalize">
                                        Organization Information
                                    </td>
                                    <td>{{ businesspercentage }}%</td>
                                    <td>{{ businesprofileUpdatedate }}</td>
                                    <td>
                                        No &nbsp;<input v-model="businessInformation" :value="true" class="my-radio"
                                            type="radio" name="organizationinformation">
                                        &nbsp;
                                        Yes &nbsp;<input :disabled="businessinfodisabled" v-model="businessInformation"
                                            :value="false" class="my-radio" checked type="radio"
                                            name="organizationinformation">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-capitalize">
                                        User Details
                                    </td>
                                    <td> {{ userpercentage }} %</td>

                                    <td>{{ user_modified }}</td>
                                    <td>
                                        No &nbsp;<input v-model="userDetails" :value="true" class="my-radio"
                                            type="radio" name="userdetails"> &nbsp;
                                        Yes &nbsp;<input :disabled="userdetailsdisabled" class="my-radio" :value="false"
                                            v-model="userDetails" checked type="radio" name="userdetails">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-capitalize">
                                        Key individuals/entities attached to your organization
                                    </td>
                                    <td> {{ keyindividualpercentage }} % </td>
                                    <td>{{ keyindividualsupdatedate }}</td>
                                    <td>
                                        No &nbsp;<input v-model="keyindividuals" :value="true" class="my-radio"
                                            type="radio" name="keyindividuals"> &nbsp;
                                        Yes &nbsp;<input :disabled="keyindividualsdisabled" class="my-radio"
                                            :value="false" v-model="keyindividuals" checked type="radio"
                                            name="keyindividuals">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-capitalize">
                                        Required organization documentation
                                    </td>
                                    <td>{{ documentpercentage }}%</td>
                                    <td>{{ documentUpdateDate }}</td>
                                    <td>
                                        No &nbsp;<input v-model="businessDocumentation" :value="true" class="my-radio"
                                            type="radio" name="documentation"> &nbsp;
                                        Yes &nbsp;<input :disabled="documentsdisabled" v-model="businessDocumentation"
                                            :value="false" class="my-radio" checked type="radio" name="documentation">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <div class="w-100 d-flex justify-content-end mt-4 gap-3">
            <Button type="outlined" @click="goBack">Previous</Button>
            <Button type="primary" @click="setStepFactory">Next</Button>

        </div>
    </div>
</template>

<script>
import Button from '../../../../shared/Buttons/Button.vue';
export default {
    props: ['bankname', 'user', 'organization_id'],
    components: {
        Button
    },
    mounted() {
        this.getData()
        this.getUserData()
    },
    data() {
        return {
            businessInformation: false,
            businessinfodisabled: false,
            businessAdress: false,
            businessAdressdisabled: false,
            keyindividuals: false,
            keyindividualsdisabled: false,
            userDetails: false,
            userdetailsdisabled: false,
            businessDocumentation: false,
            documentsdisabled: false,
            // organization_id: 235,
            steps: [],
            keyindividualsupdatedate: 'Not yet updated',
            documentUpdateDate: 'Not yet updated',
            user_modified: 'Not yet updated',
            businesprofileUpdatedate: 'Not yet updated',
            // percentages
            keyindividualpercentage: 0,
            documentpercentage: 0,
            businesspercentage: 60,
            userpercentage: 0,
        }
    }, methods: {
        goBack() {
            this.$emit('setStep', 'wiretransfer')
        },
        setStep() {
            this.$emit('setStep', 'orgdetails')
        },
        async getData() {
            await axios.get(`/organization-data/${this.organization_id}`).then(response => {
                let responsedata = response.data.data
                console.log("My Organization Info", responsedata)
                if (responsedata.document.length < 2) {
                    this.businessDocumentation = true
                    this.documentsdisabled = true
                    this.documentUpdateDate = "Not yet updated"
                } else {
                    this.documentpercentage = 100
                    this.documentUpdateDate = this.formatDateToCustomFormat(responsedata.document[0].updated_at)
                }
                // check percentages for org details
                let fields = ['name', 'industry_id', 'province_of_incorporation', 'intended_use'];

                for (let field of fields) {
                    if (responsedata[field] !== null) {
                        this.businesspercentage += 10;
                    }
                }
                // checck oranization details
                if (responsedata.name == null ||
                    responsedata.industry_id == null ||
                    responsedata.province_of_incorporation == null ||
                    responsedata.intended_use == null) {
                    this.businessInformation = true
                    this.businessinfodisabled = true
                } else {
                    this.businesprofileUpdatedate = this.formatDateToCustomFormat(responsedata.updated_at)

                }
                if (responsedata.demographic_data.address1 == null) {
                    this.businessAdressdisabled = true
                    this.businessAdress = true
                }
                if (responsedata.key_individuals == null || responsedata.key_individuals.length == 0) {
                    this.keyindividualsdisabled = true
                    this.keyindividuals = true
                    this.keyindividualpercentage = 0

                } else {
                    let percentsum = 0
                    responsedata.key_individuals.forEach(element => {
                        if (element.owns_over_twenty_five) {
                            percentsum += parseInt(element.percentage_ownership)
                        }
                        this.keyindividualsupdatedate = this.formatDateToCustomFormat(element.updated_at)
                    })
                    this.keyindividualpercentage = percentsum
                }

                // console.log(response)
            }).catch(err => {
                // console.log(err)
            })
        },
        async getUserData() {
            await axios.get('/getuser').then(response => {
                let user = response.data
                let usserfields = ['firstname', 'lastname', 'email', 'demographic_data.phone', 'demographic_data.timezone'];

                for (let field of usserfields) {
                    if (user[field] !== null) {
                        this.userpercentage += 20;
                    }
                }

                if (user.firstname == null ||
                    user.lastname == null ||
                    user.email == null ||
                    user.demographic_data.phone == null ||
                    user.demographic_data.job_title == null ||
                    user.demographic_data.timezone == null
                ) {
                    this.userDetails = true
                    this.userdetailsdisabled = true

                }
                if (user.modified_date != null) {
                    this.user_modified = this.formatDateToCustomFormat(user.modified_date)
                } else {
                    this.user_modified = "Not yet updated"
                }
            }).catch(err => {
                // console.log(err)
            })
        },
        formatDateToCustomFormat(inputDate) {
            // Create a Date object from the inputDate parameter
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        setStepFactory() {
            this.steps = [];

            if (this.businessInformation || this.businessAdress) {
                this.steps.push('orgdetails');
            }

            if (this.userDetails) {
                this.steps.push('userdetails');
            }

            if (this.keyindividuals) {
                this.steps.push('individualandentitysummary');
            }

            if (this.businessDocumentation) {
                this.steps.push('documents');
            }

            if (this.steps.length === 0) {
                this.$emit('setStep', 'sharedetails');
            } else {
                this.$emit('setFactory', this.steps);
            }

        }

    },
    watch: {
        // businessDocumentation(nv, ov) {
        //     console.log(nv)
        // }
    }

}
</script>


<style scoped>
.transfer-header {
    color: #5063F4;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 32px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 81.25% */
    /* text-transform: capitalize; */
}

.transfer-header-desc {
    color: #252525;
    text-align: center;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 108.333% */
}

.transfer-desc {
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

thead {
    background-color: #EFF2FE;
}


.transfer-table-s2 {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

tbody tr td {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

thead tr th,
tbody tr td {
    padding: 10px 10px !important;
}

tr td,
tr th {
    border-top: none !important;
    border-bottom: 1px solid #D9D9D9 !important;
}

tr {
    padding: 5px 20px;
}

/* tr:nth-child(odd) {
    background-color: #F4F5F6;
}

tr:nth-child(even) {
    background-color: #EFF2FE;

} */

input[type=radio] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=radio]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=radio]:checked::before {
    transform: scale(1);
}

input[type=radio]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}
</style>