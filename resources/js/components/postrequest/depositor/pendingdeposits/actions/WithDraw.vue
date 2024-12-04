<template>
    <div>
        <TableActionButton type="table-action-btn" @click="viewSummary()" variantColor="#FEF8EC" text-color="#F4B63C">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="11" viewBox="0 0 10 11" fill="none"
                style="width: 10px;height: 10px;flex-shrink: 0;">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M0.869078 1.57806C1.02536 1.42178 1.23732 1.33398 1.45833 1.33398H3.95833C4.08199 1.33398 4.19926 1.38891 4.27843 1.48391L5.19516 2.58398H8.54167C8.76268 2.58398 8.97464 2.67178 9.13092 2.82806C9.2872 2.98434 9.375 3.1963 9.375 3.41732V5.29232C9.375 5.52244 9.18845 5.70898 8.95833 5.70898C8.72821 5.70898 8.54167 5.52244 8.54167 5.29232V3.41732H5C4.87634 3.41732 4.75907 3.36239 4.67991 3.26739L3.76318 2.16732H1.45833V8.83398H4.58333C4.81345 8.83398 5 9.02053 5 9.25065C5 9.48077 4.81345 9.66732 4.58333 9.66732H1.45833C1.23732 9.66732 1.02536 9.57952 0.869078 9.42324C0.712797 9.26696 0.625 9.055 0.625 8.83398V2.16732C0.625 1.9463 0.712797 1.73434 0.869078 1.57806ZM6.96129 7.08102C7.12401 7.24374 7.12401 7.50756 6.96129 7.67028L6.21426 8.41732L6.96129 9.16436C7.12401 9.32708 7.12401 9.59089 6.96129 9.75361C6.79858 9.91633 6.53476 9.91633 6.37204 9.75361L5.33037 8.71195C5.16765 8.54923 5.16765 8.28541 5.33037 8.12269L6.37204 7.08102C6.53476 6.9183 6.79858 6.9183 6.96129 7.08102Z"
                    fill="#F4B63C" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.95837 6.33398C9.18849 6.33398 9.37504 6.52053 9.37504 6.75065V8.41732C9.37504 8.64744 9.18849 8.83398 8.95837 8.83398H5.62504C5.39492 8.83398 5.20837 8.64744 5.20837 8.41732C5.20837 8.1872 5.39492 8.00065 5.62504 8.00065H8.54171V6.75065C8.54171 6.52053 8.72826 6.33398 8.95837 6.33398Z"
                    fill="#F4B63C" />
            </svg>
            Withdraw Offer
        </TableActionButton>
        <ActionMessage style="width: 600px;" @closedSuccessModal="withdrawpromt = false"
            @btnTwoClicked="withDrawDeposit" @btnOneClicked="withdrawpromt = false"
            icon="/assets/dashboard/icons/question-new.svg" title="Do you want to retract the awarded deposit?"
            :showm="withdrawpromt" btnOneText="No" btnTwoText="Yes">
            <div class="ml-5 description-text-withdraw">Are you sure you want to withdraw your awarded Deposit? It will
                no longer be available to all Financial Institutions.</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Offer Withdrawn Successfully" btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw ">Your Deposit will no longer be
                available to all Financial Institutions.</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Offer not withdrawn!" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
    </div>
</template>
<script>
import TableActionButton from "../../../../shared/Buttons/TableActionButton";
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'


export default {
    components: {
        TableActionButton, ActionMessage
    },
    data() {
        return {
            withdrawpromt: false,
            success: false,
            fail: false
        }
    },
    methods: {
        viewSummary() {
            this.withdrawpromt = true
        },
        withDrawDeposit() {
            this.withdrawpromt = false
            axios.post('/withdraw-deposit', { 'deposit_id': this.actionId }).then(response => {
                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        this.success = false
                        window.location.href = "/pending-deposits"
                    }, 3000)
                } else {
                    this.fail = true

                }
            }).catch(err => {
                this.fail = true
            })
        },
    },
    props: ['actionId']
}
</script>

<style scoped>
.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>