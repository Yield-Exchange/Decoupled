<template>
    <div></div>
</template>
<script>
    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    export default {
        data() {
            let columnss = ['Closing Date & Time', 'Request ID', 'Product', 'Request Amount', 'Duration', 'Offers', 'Highest', 'Lowest', 'Action'];
            let act = [
                {
                    name: "View Offers",
                    component: ViewOffer
                },
                {
                    name: "Withdraw Request",
                    component: WithdrawRequest
                },
                {
                    name: "Edit Request",
                    component: EditRequest
                }
            ];
            return {
                details: null,
                existing: null,
                actions: act,
                columns: columnss,
                is_modal: false,
                table_data: [],
                links: [],
                data: [],
                term_length_filter: null,
                product_type_filter: null,
                filtered: []
            }
        },
        components: {
        },
        methods: {

        },
        props: ['actionId']
    }
</script>