<template>
    <span class="table-actions">
        <b-dropdown text="Actions" variant="outline-primary" class="action_btn">
            <slot></slot>
        </b-dropdown>
    </span>

</template>
<style>
    .table-actions {
        .action_btn>.dropdown-toggle {
            font-size: 14px;
            padding: 2px 5px;
        }

        .dropdown-menu {
            padding: 10px 0px 10px 0px !important;
            width: 97px !important;
        }

        .action_btn>.btn-outline-primary:not([disabled]):not(.disabled).active,
        .action_btn>.btn-outline-primary:not([disabled]):not(.disabled):active,
        .action_btn.show>.btn-outline-primary.dropdown-toggle,
        .action_btn>.btn-outline-primary:hover {
            color: #333 !important;
            background-color: #EFF2FE !important;
            border-color: #EFF2FE !important;
        }

        .action_btn>.btn-outline-primary {
            border-color: #ccc !important;
            color: black !important;
        }
    }
</style>
<script>
    export default {

    }
</script>