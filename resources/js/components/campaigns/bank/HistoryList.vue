<template>
    <div>
        <ActiveList @reloadData="$emit('reloadData',true)" :is_history="true" />
    </div>
</template>
<script>
    import ActiveList from "../../campaigns/bank/ActiveList";
    
    export default{
        mounted() {
        },
        components: {
            ActiveList,
        },
        created() {
        },
        props: [],
        data() {
            return {
            }
        }
    }
</script>