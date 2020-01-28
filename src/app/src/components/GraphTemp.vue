<template>
  <div class="base">
    <canvas height="400px" ref="graph"  class="graph"></canvas>
    <div class="info"></div>
  </div>
</template>

<script>
import Chart from "chart.js"

export default {
  name: 'GraphTemp',
  pops: ['temps', 'hum', 'time'],
  data () {
  },
  methods: {
    setChart (temperature, humidity, time) {
      let ctx = this.$refs.graph.getContext('2d');
      new Chart(ctx, {
          type: 'line',
          data: {
            labels: time,
            datasets: [{
              label: 'Temperature',
              fill: false,
              data: temperature,
              pointRadius: 0,
              borderWidth: 5,
              borderColor: [
                'rgba(97, 67, 182, 1)',
              ]
            }, {
              label: 'Humidity',
              fill: false,
              data: humidity,
              pointRadius: 0,
              borderWidth: 5,
              borderColor: [
                'rgba(47, 37, 35, 1)',
              ]
            }]
          },
          options: {
            scales: {
              yAxes: [{
                stacked: false,
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        }
      )
    }
  }
}
</script>

<style scoped>
.base {
  width: 100vw;
  height: 80vh;
}
.graph {

}
</style>
