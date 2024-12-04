<template>
    <div class=" d-flex gap-2 align-items-center">
        <Checkbox :currentValue="checkYesValue" @change="checkYesValueChange" v-if="checkbox1label"
            :label='checkbox1label' />
        <Checkbox :currentValue="checkNoValue" @change="checkNoValueChange" v-if="checkbox2label"
            :label='checkbox2label' />
    </div>
</template>

<script>
import Checkbox from './Checkbox.vue';

export default {
    props: ['required', 'tooltip', 'value', 'checkbox1label', 'checkbox2label', 'currentValue'],
    components: { Checkbox },
    data() {
        return {
            checkYesValue: false,
            checkNoValue: false
        }
    },
    mounted() {

        if (this.currentValue == null && this.currentValue != undefined) {
            this.checkNoValue = false
        } else if (this.currentValue) {
            this.checkYesValue = true
        } else if (this.currentValue == false) {
            this.checkNoValue = true
        }

    },
    methods: {
        checkYesValueChange(value) {
            this.checkYesValue = value
            this.checkNoValue = false
            this.updateStatus()

        },
        checkNoValueChange(value) {
            this.checkNoValue = value
            this.checkYesValue = false
            this.updateStatus()
        },
        updateStatus() {
            if (this.checkYesValue) {
                this.$emit('change', true)
            } else if (this.checkNoValue) {
                this.$emit('change', false)
            } else {
                this.$emit("change", null)
            }
        }
    },
    watch: {
        currentValue() {
            if (this.currentValue == null && this.currentValue != undefined) {
                this.checkNoValue = false
            } else if (this.currentValue) {
                this.checkYesValue = true
            } else if (this.currentValue == false) {
                this.checkNoValue = true
            }
        },
    }

}
</script>

<style scoped>
.checklabel {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: 16px;
    /* 100% */
}

.required {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Darks, #5063F4));
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 16px;
}
</style>