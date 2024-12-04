<template>
    <div id="chart" class="multiline">
        <apexchart type="line" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>
<script>
    import VueApexCharts from 'vue-apexcharts';
    export default {
        props: ['shorttermdatalist', 'cashabledatalist', 'nonredeemabledatalist', 'reedemabledatalist', 'noticedepositdatalist', 'highinterrestratelist'],
        components: {
            apexchart: VueApexCharts,
        },
        data() {
            console.log(this.cashabledatalist, "this.cashabledatalist");
            return {
                series: [
                    {
                        name: "Short Term",
                        data: this.shorttermdatalist
                    }, {
                        name: "Cashable",
                        data: this.cashabledatalist
                    },
                    {
                        name: "Non-Redeemable",
                        data: this.nonredeemabledatalist
                    },
                    {
                        name: 'Redeemable',
                        data: this.reedemabledatalist
                    },
                    {
                        name: 'Notice Deposit',
                        data: this.noticedepositdatalist
                    },
                    {
                        name: 'High Interest Savings',
                        data: this.highinterrestratelist
                    }
                ],
                chartOptions: {
                    chart: {
                        height: 350,
                        type: 'line',
                        zoom: {
                            enabled: false
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    legend: {
                        // position: 'top',
                        fontSize: '14px',
                        fontWeight: 400,
                        tooltipHoverFormatter: function (val, opts) {
                            return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
                        }
                    },
                    markers: {
                        size: 0,
                        hover: {
                            sizeOffset: 6
                        }
                    },
                    xaxis: {
                        categories: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return `${(value / 1000000).toFixed(2)}M`;
                            }
                        },
                    },
                    tooltip: {
                        enabled: true,
                        followCursor: true
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                    }
                },
            }


        },

    }
</script>

<style>
    .multiline .apexcharts-legend-series {
        border-radius: 10px;
        border: 2px solid gray;
        padding: 10px;
        border-radius: 9.623px;
        border: 1.203px solid #E5E5EF;

    }

    .multiline .apexcharts-menu-icon {
        display: none;
    }
</style>
