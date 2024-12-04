<template>
    <apexchart type="line" height="570" :options="chartOptions" :series="series"></apexchart>
</template>
<script>
import VueApexCharts from 'vue-apexcharts';
export default {
    props: ['data', 'height', 'barcolor', 'values', 'labels', 'seriesName'],
    components: {
        apexcharts: VueApexCharts,
    },
    data() {
        return {
            series: [{
                name: 'Market Rate',
                type: 'column',
                data: [23, 11, 22, 27, 13],
                color: "#5063F4",

            }, {
                name: 'My Rate',
                type: 'area',
                data: [20, 8, 20, 34, 15],
                color: "#44E0AA",
            }],
            chartOptions: {
                chart: {
                    height: 350,
                    type: 'line',
                    stacked: false,
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    width: [0, 2, 5],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%'
                    }
                },

                fill: {
                    opacity: [0.85, 0.25, 1],
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                        opacityFrom: 0.85,
                        opacityTo: 0.55,
                        stops: [0, 100, 100, 100]
                    }
                },
                labels: ['Redeemable', 'Non-Redeemable', 'Overnight', 'High Interest', 'Cashable'],
                markers: {
                    size: 0
                },
                xaxis: {
                    type: 'text'
                },
                yaxis: {
                    title: {
                        text: 'Percentage(%)',
                    },
                    min: 0
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0) + " ";
                            }
                            return y;

                        }
                    }
                }
            }
        }
    }


}
</script>
