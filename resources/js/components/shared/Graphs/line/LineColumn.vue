<template>
    <div id="chart">
        <apexchart type="line" height="500" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts';
export default {
    components: {
        apexcharts: VueApexCharts,
    },
    props: ['marketRates', 'myRate', 'labels'],
    mounted() {
        // console.log(this.series, "Mounted Series")
    },
    data() {
        return {
            series: [{
                name: 'Market Rate',
                type: 'column',
                data: this.marketRates,
                color: "#5063F4",
            }, {
                name: 'My Rate',
                type: 'line',
                data: this.myRate,
                color: "#44E0AA",
            }],
            chartOptions: {
                chart: {
                    height: 570,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: true,
                        type: 'x',
                        resetIcon: {
                            offsetX: -10,
                            offsetY: 0,
                            fillColor: '#fff',
                            strokeColor: '#37474F'
                        },
                        selection: {
                            background: '#90CAF9',
                            border: '#0D47A1'
                        }
                    }
                },
                stroke: {
                    width: [0, 4]
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [1]
                },
                labels: this.labels,
                xaxis: {
                    type: 'text',
                    labels: {
                        rotate: 0
                    }
                },
                yaxis: [{
                    title: {
                        text: 'Market Rate',

                    },

                }, {
                    opposite: true,
                    labels: {
                        formatter: function (value) {
                            return `${(value).toFixed(0)}%`;
                        }
                    },
                    title: {
                        text: 'My Rate'
                    }
                }]
            },


        }
    }


}
</script>


<style scoped>
.apexcharts-xaxis-texts-g {
    margin: 100px !important;
}
</style>
