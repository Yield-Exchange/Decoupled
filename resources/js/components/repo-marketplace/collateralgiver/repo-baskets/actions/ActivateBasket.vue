<template>
    <div>
        <TableActionButton type="table-action-btn" variantColor="#EFF2FE" borderColor="#EFF2FE" fontColor="#5063F4"
            @click="withdrawalRequest = true">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M6.5 11C7.09095 11 7.67611 10.8836 8.22208 10.6575C8.76804 10.4313 9.26412 10.0998 9.68198 9.68198C10.0998 9.26412 10.4313 8.76804 10.6575 8.22208C10.8836 7.67611 11 7.09095 11 6.5C11 5.90905 10.8836 5.32389 10.6575 4.77792C10.4313 4.23196 10.0998 3.73588 9.68198 3.31802C9.26412 2.90016 8.76804 2.56869 8.22208 2.34254C7.67611 2.1164 7.09095 2 6.5 2C5.30653 2 4.16193 2.47411 3.31802 3.31802C2.47411 4.16193 2 5.30653 2 6.5C2 7.69347 2.47411 8.83807 3.31802 9.68198C4.16193 10.5259 5.30653 11 6.5 11ZM6.384 8.32L8.884 5.32L8.116 4.68L5.966 7.2595L4.8535 6.1465L4.1465 6.8535L5.6465 8.3535L6.0335 8.7405L6.384 8.32Z"
                    fill="#5063F4" />
            </svg>
            Activate
        </TableActionButton>
        <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Basket activated successfully!" btnOneText="" btnTwoText="" :showm="success">
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Basket has not been archived" :showm="fail">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="withdrawalRequest = false"
            @btnTwoClicked="withdrawalRequest = false" @btnOneClicked="doSubmit" btnOneText="Yes" btnTwoText="No"
            icon="/assets/dashboard/icons/question-new.svg"
            title="Are you sure you want to activate this collateral basket?" :showm="withdrawalRequest">
        </ActionMessage>
    </div>

</template>
<script>
import Button from "../../../../shared/Buttons/Button";
import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
import ActionMessage from "../../../../shared/messageboxes/ActionMessageBox.vue";


export default {
    components: {
        TableActionButton,
        ActionMessage,
    },
    data() {
        return {
            showErrorMsg: false,
            withdrawalRequest: false,
            submitted: false,
            success: false,
            fail: false,
        }
    },
    methods: {
        doSubmit() {

            var data = new FormData()

            data.append('action', 'ACTIVE')
            data.append('thirdPartyId', this.actionId)


            axios.post('/trade/CG/update-third-party-status', data).then(response => {

                if (response.data.success) {
                    this.success = true
                    setTimeout(() => {
                        window.location.reload()
                        this.success = false
                    }, 3000)
                } else {
                    this.fail = true
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