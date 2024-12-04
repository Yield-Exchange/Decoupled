<template>
    <div style="width: 100%; justify-content: center; align-items: center; gap: 30px; display: inline-flex;" class="no-data-wrapper">
        <div>
            <img src="/assets/dashboard/icons/Group 26809.svg" />
        </div>
        <div style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 16px; display: inline-flex">
            <div style="min-width: 30%; height: 80%; color: #252525; font-size: 28px;font-weight: 700; line-height: 32px; word-wrap: break-word">{{this.title ? this.title : ''}}</div>
            <div style="min-width: 40%; height: 20%; color: #252525; font-size: 14px; font-weight: 400; word-wrap: break-word">{{this.message ? this.message : 'Add items to populate data'}}</div>
        </div>
    </div>
</template>
<style>
    .no-data-wrapper{
        background: url('/assets/dashboard/icons/Group 1707478164.svg');
        background-repeat: no-repeat;
        background-position: left bottom;
    }
</style>
<script>
    export default {
        props: ['title','message']
    }
</script>