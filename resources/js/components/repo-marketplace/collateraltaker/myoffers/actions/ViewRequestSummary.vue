<template>
    <TableActionButton type="table-action-btn" variantColor="#EFF2FE" borderColor="#FEF8EC" fontColor="#F4B63C"
        @click="viewSummary()">
        <img src="/assets/dashboard/icons/building.svg">
        Request
    </TableActionButton>
</template>
<script>
    import Button from "../../../../shared/Buttons/Button";
    import TableActionButton from "../../../../shared/Buttons/TableActionButton";
    export default {
        components: {
            TableActionButton,
            Button,
        },
        methods: {
            viewSummary() {
                window.location.href = '/request-summary/' + this.actionId;
            } 
        },
        props: ['actionId']
    }
</script>