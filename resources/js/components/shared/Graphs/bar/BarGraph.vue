<template>
    <apexchart type="bar" :height="(height) ? height : '350'" :options="chartOptions" :series="series"></apexchart>
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
                name: this.seriesName,
                data: this.values
            }],

            chartOptions: {
                colors: [
                    (this.barcolor) ? this.barcolor : "#44E0AA",
                ],

                chart: {
                    type: 'bar',
                    height: 200,
                    toolbar: {
                        show: false
                    }
                },
                bar: {
                    horizontal: true,
                    borderRadius: 0,
                    borderRadiusApplication: 'end',
                    borderRadiusWhenStacked: 'all',
                    columnWidth: '100%',
                    barHeight: '100%',
                    distributed: true,
                    rangeBarOverlap: true,
                    rangeBarGroupRows: false,
                    hideZeroBarsWhenGrouped: false,
                    isDumbbell: false,
                    dumbbellColors: undefined,
                    isFunnel: false,
                    isFunnel3d: true,
                    colors: {
                        ranges: [{
                            from: 0,
                            to: 0,
                            color: undefined
                        }],
                        backgroundBarColors: '#EFF2FE',
                        backgroundBarOpacity: 1,
                        backgroundBarRadius: 0,
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 2,
                        horizontal: true,
                        padding: 0,
                        margin: 0
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: this.labels,
                },
                grid: {
                    show: true,
                    borderColor: '#90A4AE',
                    strokeDashArray: 0,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                fill: {
                    colors: undefined,
                    opacity: 0.9,
                    type: 'solid',

                }


            }
        }
    }


}
</script>
