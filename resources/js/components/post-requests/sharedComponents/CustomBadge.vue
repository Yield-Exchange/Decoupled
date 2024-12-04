<template>
    <div>

        <div v-if="type==='success'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #5063F4; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(state.replace(/_/g, " "))}}</div>
        </div>
        <div v-if="type==='primary'"
            style="width: 100%; height: 100%; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #44E0AA; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
            <div
                style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                {{capitalize(state.replace(/_/g, " "))}}</div>
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
        props: ["type", "state"],
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
            capitalize(thestring) {
                return sentenceCase(thestring);
            }

        },
        watch: {
        }
    }

</script>