<template>
    <div style="height: 100%">

    </div>
</template>

<script>
    import { userCan } from "../../../utils/GlobalUtils";
    export default {
        mounted() {

        },
        computed: {

        },
        components: {
        },
        created() {
        },
        props: ['offers'],
        data() {

            return {
                details: null,
                existing: null,
                action: 'view',
                is_modal: false
            }
        },
        watch: {

        },
        methods: {
            userCan(user, permission) {
                return userCan(user, permission);
            },
        }
    }
</script>