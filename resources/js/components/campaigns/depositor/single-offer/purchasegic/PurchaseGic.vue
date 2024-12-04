<template>
    <div>
        <WireTransfer :depositdetails="depositDetails" :bankdetails="bankDetails" @setStep="setStep"
            v-if="currentStep == 'wiretransfer'">
        </WireTransfer>

        <ProfileUpdate :bankname="bankDetails.bankName" :organization_id="organization_id" :user="user"
            @setStep="setStep" @setFactory="setFactory" v-if="currentStep == 'profileupdate'"></ProfileUpdate>

        <CompleteDeposit :depositdetails="depositDetails" :bankdetails="bankDetails" @setStep="setStep"
            :bankname="bankDetails.bankName" v-if="currentStep == 'compledeposit'">
        </CompleteDeposit>

        <DepositorOrganizationsDetails :organization_id="organization_id" :user="user" @goBack="goBack"
            @updateFactory="updateFactory" v-if="currentStep == 'orgdetails'">
        </DepositorOrganizationsDetails>

        <UserDetails @getUserData="getUserData" :user="user" @goBack="goBack" @updateFactory="updateFactory"
            v-if="currentStep == 'userdetails'">
        </UserDetails>

        <DocumentsUpload :organization_id="organization_id" @goBack="goBack" @updateFactory="updateFactory"
            v-if="currentStep == 'documents'">
        </DocumentsUpload>
        <IndividualAndEntititySummary :organization_id="organization_id"
            v-if="currentStep == 'individualandentitysummary'" @goBack="goBack" @updateFactory="updateFactory" />
        <ShareDetails :user="user" :fiorganization_id="fiorganization_id" :depoorganization_id="organization_id"
            @goBack="goBack" :depositdetails="depositDetails" :bankdetails="bankDetails" @setStep="setStep"
            v-if="currentStep == 'sharedetails'"></ShareDetails>

    </div>
</template>

<script>
import ProfileUpdate from './ProfileUpdate.vue';
import WireTransfer from './WireTransfer.vue'
import CompleteDeposit from './CompleteDeposit.vue'
import DepositorOrganizationsDetails from './forms/DepositorOrganizationsDetails.vue';
import UserDetails from './forms/UserDetails.vue';
import DocumentsUpload from './DocumentsUpload.vue';
import ShareDetails from './ShareDetails.vue';
import FIPdfFormat from './FIPdfFormat.vue';

import IndividualAndEntititySummary from './IndividualAndEntititySummary.vue';

export default {
    props: ['offer', 'organizationdetails', 'fi_admin'],
    components: {
        WireTransfer,
        ProfileUpdate, CompleteDeposit,
        DepositorOrganizationsDetails,
        UserDetails,
        DocumentsUpload,
        ShareDetails,
        IndividualAndEntititySummary,
        FIPdfFormat
    },
    mounted() {
        let jsonoffer = null
        let bankdetail = null
        if (this.offer != null) {
            jsonoffer = JSON.parse(this.offer)
            this.setDefaulValue(jsonoffer)
        }
        if (this.organizationdetails != null) {
            bankdetail = JSON.parse(this.organizationdetails)
            this.setBankDetails(bankdetail[0])
        }
        this.getUserData()
        this.setProvinces()
    },
    data() {
        return {
            currentStep: 'wiretransfer',
            bankDetails: {
                bankName: '',
                cibcinstitutionnumber: '',
                trasnsit: '',
                beneficiaryAccount: '',
                beneficiaryName: '',
                clearingCode: ''
            },
            fiorganization_id: null,
            // deposit request Data
            depositDetails: {
                requestID: '',
                productType: '',
                termLength: '',
                depositAmount: ''
            },
            steps: [],
            organization_id: null,
            user: null,
        }
    },
    methods: {
        downloadpdf() {
            this.$refs.generatePdf.downloadPdf()

        },
        async getUserData() {
            await axios.get('/getuser').then(response => {
                this.user = response.data
                this.SetCurrentUser(response.data)
                this.organization_id = response.data.organization.id
            }).catch(err => {
                // console.log(err)
            })
        },
        async setProvinces() {
            await axios.get('/get-all-provinces-sign-up').then(response => {
                // if (response.data.success)
                // console.log(response.data)
                this.$store.dispatch('setProvinces', response.data)
            }).catch(err => {
                // console.log(err)
            })
        },
        SetCurrentUser(value) {
            const data = {
                first_name: value.firstname,
                phone: value.demographic_data.phone,
                last_name: value.lastname,
                user_id: value.id,
                email: value.email,
                job_title: value.demographic_data.job_title
            }
            this.$store.dispatch('setLoggedInUser', data)

        },

        setStep(value) {
            this.currentStep = value
        },
        setDefaulValue(offer) {
            // deposit request data
            let depositinfo = offer.invited.deposit_request
            this.depositDetails.depositAmount = depositinfo.currency + ", " + this.addCommas(depositinfo.amount)
            this.depositDetails.termLength = depositinfo.term_length + " " + depositinfo.term_length_type.toLowerCase()
            this.depositDetails.requestID = depositinfo.reference_no
            this.depositDetails.productType = depositinfo.product_name
            // bank info
            this.fiorganization_id = offer.invited.organization_id
            this.bankDetails.bankName = offer.bank_name,
                this.bankDetails.fiorganization_id = offer.invited.organization_id
        },
        setBankDetails(bankdetails) {
            // console.log(bankdetails)
            const fi = JSON.parse(this.fi_admin)
            this.bankDetails.beneficiaryName = fi.name
            if (bankdetails) {
                this.bankDetails.trasnsit = bankdetails.transit_code ? bankdetails.transit_code : "Not Available"
                this.bankDetails.cibcinstitutionnumber = bankdetails.cibcinstitutionnumber ? bankdetails.cibcinstitutionnumber : "Not Available"
                this.bankDetails.clearingCode = bankdetails.clearingcode ? bankdetails.clearingcode : "Not Available"
                this.bankDetails.beneficiaryAccount = bankdetails.beneficiary_acc_number ? bankdetails.beneficiary_acc_number : "Not Available"
            } else {
                this.bankDetails.trasnsit = "Not Available"
                this.bankDetails.cibcinstitutionnumber = "Not Available"
                this.bankDetails.clearingCode = "Not Available"
                this.bankDetails.beneficiaryAccount = "Not Available"

            }
        },
        addCommas(number) {
            return number.toLocaleString()
        },
        setFactory(value) {
            this.steps = value
            this.setStep(this.steps[0])
        },
        updateFactory(value) {
            let length = this.steps.length
            let indexofvalue = this.steps.indexOf(value)
            if (indexofvalue + 1 == length) {
                this.setStep('sharedetails')
            } else {
                this.setStep(this.steps[indexofvalue + 1])
            }
            // console.log(length, value, indexofvalue)

        },
        goBack(value) {
            let length = this.steps.length
            let indexofvalue = this.steps.indexOf(value)
            if (length == 0 || indexofvalue == 0) {
                this.setStep('profileupdate')
            } else {
                if (value == 'sharedetails') {
                    this.setStep(this.steps[length - 1])
                } else {
                    this.setStep(this.steps[indexofvalue - 1])
                }
            }
        }



    }

}
</script>


<style>
/* @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap'); */

.tooltip-inner {
    color: var(--Neutral-600, #6F6C90);

    /* Yield Exchange Text Styles/Tooltips */
    font-family: Montserrat;
    font-size: 11px;
    font-style: normal;
    font-weight: 400;
    line-height: 14px;

    /* Cards/Short Default */
    box-shadow: 0px 5px 14px 0px rgba(8, 15, 52, 0.04), 0px 1px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    /* 127.273% */
}

.mb-20 {
    margin-bottom: 15px !important;
}

.mb-10 {
    margin-bottom: 10px !important;
}

/* Bottom Tooltip */
/* Top Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=top] .arrow::before,
.tooltip.bs-tooltip-top .arrow::before {
    box-shadow: 0px 5px 14px 0px rgba(8, 15, 52, 0.04), 0px 1px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-left: -3px;
    content: "";
    border-width: 10px 10px 0;
    border-top-color: #ffffff;
}

/* Bottom Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=bottom] .arrow::before,
.tooltip.bs-tooltip-bottom .arrow::before {
    box-shadow: 0px -5px 14px 0px rgba(8, 15, 52, 0.04), 0px -1px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-top: -3px;
    content: "";
    border-width: 0 10px 10px;
    border-bottom-color: #ffffff;
}

/* Right Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=right] .arrow::before,
.tooltip.bs-tooltip-right .arrow::before {
    box-shadow: -5px 0px 14px 0px rgba(8, 15, 52, 0.04), -1px 0px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-left: 0;
    margin-right: -3px;
    content: "";
    border-width: 10px 0 10px 10px;
    border-right-color: #ffffff;
}

/* Left Tooltip */
.tooltip.bs-tooltip-auto[x-placement^=left] .arrow::before,
.tooltip.bs-tooltip-left .arrow::before {
    box-shadow: 5px 0px 14px 0px rgba(8, 15, 52, 0.04), 1px 0px 1px 0px rgba(23, 15, 73, 0.04), 0px 0px 1px 0px rgba(23, 15, 73, 0.03);
    margin-left: -3px;
    content: "";
    border-width: 10px 10px 10px 0;
    border-left-color: #ffffff;
}
</style>