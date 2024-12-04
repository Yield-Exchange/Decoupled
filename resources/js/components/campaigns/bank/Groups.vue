<template>
  <TargetDepositors :depositors="depositors" :selectable="false" />
</template>
<script>
    import FilterBox from "../../shared/Table/FilterBox";
    import Pagination from "../../shared/Table/Pagination";
    import Table from "../../shared/Table";
    import TargetDepositors from "./TargetDepositors";
    
    export default{
        mounted() {
        },
        components: {
            FilterBox,
            Pagination,
            Table,
            TargetDepositors
        },
        created() {
        },
        props: ['depositors'],
        data() {
            return {

            }
        }
    }
</script>