<template>
    <div class="timer-clock running">
        <p class="class">{{ formattedTime }}</p>
    </div>
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
    props: ['targetTime', 'timezone'],
    data() {
        console.log(this.timezone, "this.timezone");
        // console.log(this.targetTime, "this.timezone");
        let currentTimestr
        if (this.timezone)
            currentTimestr = new Date().toLocaleString("en-US", { timeZone: this.timezone });
        else
            currentTimestr = new Date().toLocaleString("en-US");
        // console.log(currentTimestr, "currentTimestrcurrentTimestr");
        return {
            targetTimeInSeconds: new Date(this.targetTime).getTime(),
            currentTime: new Date(currentTimestr).getTime(),
            intervalId: null
        };
    },
    computed: {
        formattedTime() {

            const remainingTime = this.targetTimeInSeconds - this.currentTime;
            if (remainingTime <= 0) {
                clearInterval(this.intervalId);
                return 'Expired';
            }
            const seconds = Math.floor(remainingTime / 1000) % 60;
            const minutes = Math.floor(remainingTime / 1000 / 60) % 60;
            const hours = Math.floor(remainingTime / 1000 / 60 / 60) % 24;
            const days = Math.floor(remainingTime / 1000 / 60 / 60 / 24);
            let dayslabel = (parseInt(days) > 0) ? `${days}d ` : '';
            let hrslabel = (hours > 0) ? `${this.pad(hours)}h ` : '';
            let minabel = (minutes > 0) ? `${this.pad(minutes)}m ` : '';
            let secondslabel = (seconds > 0) ? `${this.pad(seconds)}s ` : '';

            return `${dayslabel}${hrslabel}${minabel}${secondslabel}`;
        },
    },
    methods: {
        pad(value) {
            return value.toString().padStart(2, '');
        },
        updateCurrentTime() {
            let currentTimestr = new Date().toLocaleString("en-US", { timeZone: this.timezone });
            this.currentTime = new Date(currentTimestr).getTime();
        }
    },
    created() {
        this.intervalId = setInterval(this.updateCurrentTime, 1000);
    },
    beforeDestroy() {
        clearInterval(this.intervalId);
    }
};
</script>

<style scoped>
.timer-clock {
    text-align: center;
}
</style>