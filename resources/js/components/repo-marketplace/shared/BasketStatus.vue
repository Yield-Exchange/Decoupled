<template>
    <div class="status-container" :style="displayColor">
        <div class="status-text">
            {{ capitalize(status) }}</div>
    </div>
</template>

<script>
import { capitalize } from '../../../utils/commonUtils';

export default {
    props: ['status'],
    data() {
        return {}
    },
    computed: {
        displayColor() {
            let status = this.status.toLowerCase()
            if (status == 'verified' || status == 'active') {
                return "background: #44E0AA;"
            }
            if (status == 'attention') {
                return "background: #FF2E2E;"
            }
            if (status == 'pending') {
                return "background: #F4B63C;"
            }
            if (status == 'archived') {
                return "background: #9CA1AA;"
            }
        }
    },
    methods: {
        capitalize(value) {
            return capitalize(value)
        }
    }
}
</script>

<style scoped>
.status-container {
    width: 100%;
    height: 100%;
    padding-left: 12px;
    padding-right: 12px;
    padding-top: 2px;
    padding-bottom: 2px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    display: inline-flex
}

.status-text {
    color: white;
    font-size: 16px;
    font-family: Montserrat !important;
    font-weight: 500;
    word-wrap: break-word
}
</style>