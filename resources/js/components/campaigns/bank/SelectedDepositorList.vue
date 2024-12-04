<template>
    <div style="width: 100%">
        <div style="width: 100%">
            <Table :columns="['Organization/Group']"
                   :data="data" :has_action='false' :actions='actions' />
        </div>
    </div>
</template>
<script>
    import Table from "../../shared/Table";

    export default{
        mounted() {
        },
        components: {
            Table,
        },
        created() {
        },
        props: [],
        data() {
            return {
                actions: [

                ],
                data: [
                    [
                        "ABC BANK",
                    ],
                    [
                        "ABC BANK",
                    ],
                    [
                        "British Columbia Group",
                    ]
                ]
            }
        }
    }
</script>