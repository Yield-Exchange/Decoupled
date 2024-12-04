<template>
    <div class="d-flex flex-column">
        <p
            style="color: var(--gray-700, #344054);font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 400;line-height: 150%; /* 21px */">
            {{ label }}</p>
        <input class="rounded-input" :type="type" :placeholder="placeholder" v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)">
    </div>
</template>


<script>
    import { type } from 'os';

    export default {
        props: ['value', "label", "placeholder", 'type'],
    }
</script>

<style>
    .rounded-input {
        border-radius: 999px;
        border: 1px solid #D0D5DD;
        background: #FFF;
        box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
        padding: 10px 14px;
        color: var(--gray-500, #667085);
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 150%;
        width: 140px;
        /* 24px */
    }

    .rounded-input::placeholder {
        color: var(--gray-300, #DCDCDC);
        /* Adjust the color to your preference */
    }
</style>
