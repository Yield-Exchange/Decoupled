<template>
    <ActivateCollateral :actionId="actionId" v-if="activate"></ActivateCollateral>
    <div v-else>
        <TableActionButton type="table-action-btn" variantColor="#FFEBEB" borderColor="#FFEBEB" fontColor="#FF2E2E"
            @click="withdrawalRequest = true">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                <path
                    d="M7.24845 6.5L9.34339 4.40505C9.44282 4.3058 9.49875 4.17112 9.49887 4.03064C9.49899 3.89015 9.44331 3.75537 9.34406 3.65594C9.24481 3.55652 9.11012 3.50059 8.96964 3.50047C8.82915 3.50034 8.69437 3.55603 8.59495 3.65528L6.5 5.75023L4.40505 3.65528C4.30563 3.55586 4.17078 3.5 4.03017 3.5C3.88956 3.5 3.75471 3.55586 3.65528 3.65528C3.55586 3.75471 3.5 3.88956 3.5 4.03017C3.5 4.17078 3.55586 4.30563 3.65528 4.40505L5.75023 6.5L3.65528 8.59495C3.55586 8.69437 3.5 8.82922 3.5 8.96983C3.5 9.11044 3.55586 9.24529 3.65528 9.34472C3.75471 9.44414 3.88956 9.5 4.03017 9.5C4.17078 9.5 4.30563 9.44414 4.40505 9.34472L6.5 7.24977L8.59495 9.34472C8.69437 9.44414 8.82922 9.5 8.96983 9.5C9.11044 9.5 9.24529 9.44414 9.34472 9.34472C9.44414 9.24529 9.5 9.11044 9.5 8.96983C9.5 8.82922 9.44414 8.69437 9.34472 8.59495L7.24845 6.5Z"
                    fill="#FF2E2E" />
            </svg>
            Archive
        </TableActionButton>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg" title="CUSIP Archived successfully"
            btnOneText="" btnTwoText="" :showm="success">
            <!-- <div class="ml-5 description-text-withdraw "> The collateral giver has been notified..</div> -->
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="CUSIP has not been archived" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="withdrawalRequest = false"
            @btnTwoClicked="withdrawalRequest = false" @btnOneClicked="doSubmit" btnOneText="Yes" btnTwoText="No"
            icon="/assets/dashboard/icons/question-new.svg" title="Are you sure you want to archive this CUSIP basket"
            :showm="withdrawalRequest">
            <div class="ml-5 description-text-withdraw ">You can re-activate again using the actions.</div>
        </ActionMessage>
    </div>

</template>
<script>
import ActivateCollateral from "./ActivateCollateral.vue";
import Button from "../../../../../shared/Buttons/Button";
import TableActionButton from "../../../../../shared/Buttons/PostTableActionButton";
import ActionMessage from "../../../../../shared/messageboxes/ActionMessageBox.vue";
import { mapGetters } from "vuex";


export default {
    components: {
        TableActionButton,
        ActionMessage,
        ActivateCollateral
    },
    beforeMount() {
        this.checkStatus()
    },
    data() {
        return {
            showErrorMsg: false,
            withdrawalRequest: false,
            submitted: false,
            success: false,
            fail: false,
            activate: false,
        }
    },

    computed: {
        ...mapGetters('basket', ['getBiBaskets'])
    },
    methods: {
        checkStatus() {
            this.billateral = this.getBiBaskets.trade_organization_c_u_s_s_i_p.find(item => item.encoded_id == this.actionId)
            if (this.billateral.cusips_status == 'ARCHIVED') {
                this.activate = true
            }
        },

        doSubmit() {

            var data = new FormData()
            let datatosend = [
                {
                    'status': 'ARCHIVED',
                    "trade_organization_collateral_c_u_s_i_p": this.actionId,
                }]

            data.append('cusips', JSON.stringify(datatosend))

            axios.post('/trade/CG/update-cusip-to-issuer', data).then(response => {

                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        window.location.reload()
                        this.success = false
                    }, 3000)
                } else {

                }
            }).catch(err => {

                this.fail = true
            })

        },

        cancelIt() {
            this.withdrawalRequest = 0;
        }

    },
    mounted() {

    },
    props: ['actionId']
}
</script>