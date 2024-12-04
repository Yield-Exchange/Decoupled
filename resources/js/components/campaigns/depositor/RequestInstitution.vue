<template>
    <Table :columns="columns" no-data-title="No Institutions""
    no-data-message=" No Institutions available for review" :data="[]" :has_action='true' :actions='actions'
        :selectable="false" />
</template>
<script>
    import Table from "../../shared/PostOffersTable";
    import Pagination from "../../shared/Table/Pagination";
    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    export default {
        components: {
            Table
        },
        methods: {

        },
        props: ['actionId']
    }
</script>