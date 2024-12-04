<template>
    <!-- <div id="chart"> -->
    <apexchart type="donut" :height="dheight" :width="dwidth" :options="chartOptions" :series="series[0].data">
    </apexchart>
    <!-- </div> -->
</template>
<script>
    import VueApexCharts from 'vue-apexcharts';
    export default {
        props: ['showlegent', 'dheight', 'dwidth', 'labels', 'values', 'seriescolors'],
        components: {
            apexcharts: VueApexCharts,
        },
        data() {
            return {
                series: [{
                    name: 'Sold',
                    data: this.values,
                }],
                // colors: this.seriescolors,
                chartOptions: {
                    legend: {
                        show: this.showlegent,
                        showForSingleSeries: false,
                        showForNullSeries: true,
                        showForZeroSeries: true,
                        position: 'left',
                        horizontalAlign: 'center',
                        floating: false,
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial',
                        fontWeight: 400,
                        formatter: undefined,
                        inverseOrder: false,
                        width: undefined,
                        height: undefined,
                        tooltipHoverFormatter: undefined,
                        customLegendItems: [],
                        offsetX: 0,
                        offsetY: 0,
                        labels: {
                            colors: this.seriescolors,
                            useSeriesColors: false

                        }
                    },

                    labels: this.labels,
                    tooltip: {

                        custom: function ({ series, seriesIndex, w }) {

                            // '<span>' + series[seriesIndex] + ' - ' + ((series[seriesIndex] / sum) * 100).toFixed(2) + '%</span>' +
                            const sum = series.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            return '<div class="arrow_box" style="padding:5px;">' +
                                '<span>' + ((series[seriesIndex] / sum) * 100).toFixed(2) + '%</span>' +
                                '</div>'
                        }
                    },
                    chart: {
                        type: 'donut',
                    },
                    total: {
                        show: true,
                        showAlways: true,
                        label: 'Total',
                        fontSize: '22px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 600,
                        color: '#373d3f',
                        formatter: function (w) {
                            return w.globals.seriesTotals.reduce((a, b) => {
                                return a + b
                            }, 0)
                        }
                    }, fill: {
                        colors: this.seriescolors,
                        opacity: 0.9,
                        type: 'solid',

                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Montserrat',
                            fontFamily: '500',
                            colors: ["white"]
                        }
                    },

                },
            }
        }
    }
</script>