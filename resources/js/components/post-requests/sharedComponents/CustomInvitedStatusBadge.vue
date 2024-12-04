<template>
    <div>
        <div v-if="text === 'PENDING'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #F4B63C; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>
        <div v-else-if="text === 'ACCEPTED'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #5063F4; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>
        <div v-else-if="text === 'COUNTERED'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #44E0AA; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>
        <div v-else-if="text === 'EXPIRED'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #FF2E2E; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>
        <div v-else-if="text === 'CLOSED'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #9CA1AA; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>
        <div v-else-if="text === 'EDITED'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #EFF2FE; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: #5063F4; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>
        <div v-if="text === 'DECLINED'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #FF2E2E; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(text)}}</div>
        </div>


    </div>
</template>
<style>
    .has-error {
        border: 0.5px solid red;
    }

    .input-height {
        height: 40px;
    }

    .was-validated .form-control:valid,
    .form-control.is-valid {
        border-color: #ddd;
        background-image: none
    }

    .b-calendar-grid-body>div>div>.text-muted {
        color: #111 !important;
        font-weight: bold !important;
    }
</style>

<script>
    import { sentenceCase } from "../../../utils/commonUtils";
    export default {
        props: ["text", "varient"],
        created() { },
        components: {
        },
        computed: {
        },
        data() {

            return {
            }
        },
        methods: {
            capitalize(stringg) {
                return sentenceCase(stringg);
            }

        },
        watch: {
        }
    }

</script>