<template>
    <TableActionButton type="table-action-btn" @click="viewSummary()" variantColor="#EFF2FE" text-color="#5063F4">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
            <path
                d="M2.005 3.50586H10.005V4.50586H2.005V3.50586ZM3.005 1.50586H9.005V2.50586H3.005V1.50586ZM10 5.50086H2C1.73478 5.50086 1.48043 5.60622 1.29289 5.79375C1.10536 5.98129 1 6.23564 1 6.50086V10.5009C1 10.7661 1.10536 11.0204 1.29289 11.208C1.48043 11.3955 1.73478 11.5009 2 11.5009H10C10.2652 11.5009 10.5196 11.3955 10.7071 11.208C10.8946 11.0204 11 10.7661 11 10.5009V6.50086C11 6.23564 10.8946 5.98129 10.7071 5.79375C10.5196 5.60622 10.2652 5.50086 10 5.50086ZM5.15 10.5009L3.5 8.88086L4.2 8.18086L5.15 9.13086L7.8 6.48086L8.5 7.18086L5.15 10.5009Z"
                fill="#5063F4" />
        </svg>
        Activate
    </TableActionButton>
</template>
<script>
import TableActionButton from "../../../../shared/Buttons/TableActionButton";
export default {
    components: {
        TableActionButton,
    },
    methods: {
        viewSummary() {
            window.location.href = '/depositor/create-gic/' + this.actionId + "?fromPage=pending-deposits"
            // console.log(this.actionObjectDetails)

        }
    },
    props: ['actionId', 'actionObjectDetails']
}
</script>