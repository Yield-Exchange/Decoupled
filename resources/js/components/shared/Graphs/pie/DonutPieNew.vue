<template>
    <div id="chart" class="gic">
        <apexchart :height="200" type="donut" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>


<script>
import VueApexCharts from 'vue-apexcharts';
export default {
    props: ['series', 'labels'],

    components: {
        apexchart: VueApexCharts
    },
    data() {
        return {
            series: this.series,
            chartOptions: {
                chart: {
                    type: 'donut',
                    // width: "200px"
                },
                width: 200,
                legend: {
                    position: 'left'
                },
                donut: {
                    size: '65%',
                },
                labels: this.labels,
                pie: {
                    // customScale: 10.6,
                },
            }
        }
    }

}
</script>

<style>
.gic .apexcharts-legend-text {
    font-size: 14px;
    font-weight: 400;
}

.gic .apexcharts-legend {
    gap: 10px;
    justify-content: center;
}
</style>