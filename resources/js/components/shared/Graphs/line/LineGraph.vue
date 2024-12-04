<template>
    <div id="chart">
        <apexchart type="line" height="100%" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts';
export default {
    components: {
        apexcharts: VueApexCharts,
    },
    data() {
        return {
            series: [{
                name: "Clicks",
                data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
            }],
            chartOptions: {
                chart: {
                    height: "100%",
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8', 'Day 9'],
                }
            }

        }
    }


}
</script>