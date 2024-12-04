<template>
    <div @click="$emit('click',true)" :style="'width:100%;padding: 10px 10px;border-radius: 32px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer'">
        <div style="padding-left: 5px; padding-right: 5px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
            <div style="color: #5063F4; font-size: 15px;  font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word"><slot></slot></div>
        </div>
    </div>
</template>
<script>
    export default {

    }
</script>