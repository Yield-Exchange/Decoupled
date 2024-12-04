<template>
    <div v-on:click="$emit('click')" :style="'width:100%;height: 20px; padding: 4px 8px;border-radius: 200px; background: '+borderColor+'; justify-content: flex-start; align-items: center; gap: 2px; display: inline-flex;color: '+fontColor+'; font-size: 13px;  font-weight: 500; line-height: 9px; word-wrap: break-word;cursor: pointer'">
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: ['variantColor','textColor'],
        data(){
            return {
                borderColor: this.variantColor ? this.variantColor : '#EFF2FE',
                fontColor: this.textColor ? this.textColor : '#5063F4',
            }
        }
    }
</script>