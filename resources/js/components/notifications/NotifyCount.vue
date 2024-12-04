<template>
    <span v-if="notifications > 0" class="badge badge-pill bg-warning-400 ml-auto ml-md-0" :load="log(notifications)">
        {{ notifications }}
    </span>
</template>
<script>
    export default {
        props: ['notifications','_token'],
        watch: {
            notifications (n, o) {
                // console.log(n,o) // n is the new value, o is the old value.
            }
        },
        methods: {
            log(item) {
                // console.log(item,"count")
            }
        }
    };
</script>
