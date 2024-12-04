<template>
    <div id="chart">
        <apexchart type="radialBar" :height="dheight" :width="600" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts';
export default {
    props: ['showlegent', 'dheight', 'dwidth', 'labels', 'values', 'seriescolors', 'counter_series'],
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            series: [this.counter_series],
            chartOptions: {
                chart: {
                    offsetY: -20,
                    type: 'radialBar',
                },
                colors: ["#5063F4"],
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90, 
                        track: {
                            background: "#EFF2FE",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: '#999',
                                opacity: 1,
                                blur: 2
                            }
                        },
                        dataLabels: {
                            
                            name: {
                                show: false,
                                color: "#888",
                                fontSize: "15px"
                            },
                            value: {
                                //offsetY: -2,
                                color: "#111",
                                fontSize: "30px",
                                show: true
                            }
                        }
                    }
                },
                labels: ['Sold'],
                
            },


        }
    }


}
</script>
