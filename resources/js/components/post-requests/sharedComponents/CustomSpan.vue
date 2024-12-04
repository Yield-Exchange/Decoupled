<template>
    <div>
        <span>{{value}}</span>
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
    export default {
        props: ["value"],
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

        },
        watch: {
        }
    }

</script>