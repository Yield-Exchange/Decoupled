<template>

</template>
<style scoped>
    .timer-clock {
        text-align: left !important;
    }

    .timer-clock>p {
        margin: 0 !important;
    }

    .timer-clock.expiring>p {
        color: #FF2E2E;
    }

    .timer-clock.running>p {
        color: #44E0AA;
    }
</style>
<script>
    export default {
        props: ['timestamp', 'timezone'],
        data() {

            return {
                targetTimeInSeconds: 
            };
        },
        computed: {

        },
        methods: {

        },
        created() {
            this.intervalId = setInterval(this.updateCurrentTime, 1000);
        }
    };
</script>

<style scoped>
    .timer-clock {
        text-align: center;
    }
</style>