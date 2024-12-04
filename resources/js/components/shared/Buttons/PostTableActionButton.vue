<template>
    <div v-on:click="$emit('click')"
        :style="'width:100%;height: 20px; padding: 4px 15px;border-radius: 200px; background: '+backGroundColor+';  border: 0.5px solid '+borderColor+'; '+backGroundColor+'; justify-content: flex-start; align-items: center; gap: 2px; display: inline-flex;color: '+fontColorr+'; font-size: 13px;  font-weight: 500; line-height: 9px; word-wrap: break-word;cursor: pointer'">
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: ['variantColor', 'textColor', 'fontColor'],
        data() {
            return {
                backGroundColor: this.variantColor ? this.variantColor : '#EFF2FE',
                borderColor: this.borderColor ? this.borderColor : '#EFF2FE',
                fontColorr: this.fontColor ? this.fontColor : '#5063F4',
            }
        }
    }
</script>