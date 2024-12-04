<template>
    <div style="background: red">
        <Table @reloadData="$emit('reloadData', true)" :columns="columns" :data="table_data" :has_action='true'
            :actions='actions' />
    </div>
</template>
<script>
    import Table from "../../shared/Table";
    import ViewOffer from "./ViewOffer";
    import TimerClock from "../TimerClock";
    export default {
        components: {
            Table,
            ViewOffer,
            TimerClock
        },
        props: ['data', 'formattedtimezone'],
        data() {
            return {
                table_data: this.tableData(this.data),
                actions: [
                    {
                        name: "View",
                        component: ViewOffer,
                    }
                ],
                columns: ["Financial Institution", "Product Type", "Term Length", "Rate", "Currency", "Min Amount", "Max Amount", "Days to expiry", "Actions"]
            }
        },
        computed: {

        },
        methods: {
            tableData(d) {
                let table_data = [];
                Object.values(d?.data).forEach((item) => {
                    let datum = [
                        item.id,
                        item.campaign?.organization?.name,
                        item.description,
                        item.term_length + ' ' + item.term_length_type,
                        item.rate + '%',
                        item.currency,
                        this.formatNumberAbbreviated(item.minimum),
                        this.formatNumberAbbreviated(item.maximum),

                    ];
                    datum.push(() => {
                        return this.renderExpiryDate(item.expiry_date, item.status, this.formattedtimezone);
                    });
                    table_data.push(datum);
                });
                // console.log("ListView",table_data);
                return table_data;
            },
            renderExpiryDate(ex_date, startdate, useertimezone) {

                if (startdate === "ACTIVE") {
                    return ({ 'component': TimerClock, 'attrs': { targetTime: ex_date, timezone: useertimezone } });
                } else {
                    return ex_date;
                }

            },
            formatNumberAbbreviated(number) {
                const SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

                const tier = (Math.log10(number) / 3) | 0;

                if (tier === 0) return number;

                const suffix = SI_SYMBOL[tier];
                const scale = Math.pow(10, tier * 3);

                const scaledNumber = number / scale;

                return scaledNumber.toFixed(0) + suffix;
            }
        },
        watch: {
            data(newVal, oldVal) {
                this.table_data = this.tableData(newVal);
            }
        }
    }
</script>