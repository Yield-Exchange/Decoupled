<template>
    <div class=" d-flex gap-2 align-items-center">
        <span class="checklabel">{{ label }} <span v-if="required" class="required"> *</span> </span>
        <img v-if="tooltip" src="/assets/signup/help-circle.png" width="16" height="16" alt="" srcset="">
        <input v-model="checkValue" @change="valueChange" style="width: 14px;height: 14px;" class="custom-checkbox"
            type="checkbox" name="Director" id="">
    </div>
</template>

<script>
export default {
    props: ['required', 'tooltip', 'value', 'label', 'currentValue'],
    data() {
        return {
            checkValue: false,
        }
    },
    methods: {
        valueChange() {
            this.$emit('change', this.checkValue)
        },
    },
    watch: {
        currentValue() {
            if (this.currentValue != null && this.currentValue != undefined) {
                this.checkValue = this.currentValue
            }
        },
    }
}
</script>

<style scoped>
.checklabel {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 16px;
    /* 100% */
}

.required {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 16px;
}

.custom-checkbox {
    appearance: none;
    background-color: #fff;
    width: 20px !important;
    height: 20px !important;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

.custom-checkbox::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

.custom-checkbox:checked::before {
    transform: scale(1);
}

.custom-checkbox:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}
</style>