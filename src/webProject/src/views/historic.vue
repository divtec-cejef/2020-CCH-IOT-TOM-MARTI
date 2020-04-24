<template>
  <div class="historic">
    <canvas class="chart" ref="chart"></canvas>
  </div>
</template>

<script>
  import Chart from 'chart.js';
  import axios from "axios";
  export default {
    name: 'historic',
    data () {
      return {
        labels: [],
        maxTemp: [],
        minTemp: [],
        temp: [],
        maxHum: [],
        minHum: [],
        hum: [],
        chart: Object
      }
    },
    methods: {
      setDate () {
        let d = new Date();
        let today = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        d.setDate(d.getDate() - 7);
        let lastWeek = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        return {from: lastWeek, to: today};
      },
      loadMeasures () {
        let date = this.setDate();
        axios.get('https://temp.martitom.ch/api/measures/' + date.from + "/" + date.to).then(response => {
          response.data.forEach(item => {
            this.labels.push(item.date);
            this.maxTemp.push(item.temperature.max);
            this.minTemp.push(item.temperature.min);
            this.temp.push(item.temperature.last);
            this.maxHum.push(item.humidity.max);
            this.minHum.push(item.humidity.min);
            this.hum.push(item.humidity.last);
          });
          this.setChart();
        })
      },
      setChart () {
        let ctx = this.$refs.chart.getContext('2d');
        ctx.canvas.height = "calc(100vh - 200px)";
        this.chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: this.labels,
            datasets: [{
              label: 'Temperature',
              fill: false,
              data: this.temp,
              pointRadius: 0,
              borderWidth: 5,
              borderColor: [
                'rgba(97, 67, 182, 1)',
              ]
            }, {
              label: 'Humidity',
              fill: false,
              data: this.hum,
              pointRadius: 0,
              borderWidth: 5,
              borderColor: [
                'rgba(47, 37, 35, 1)',
              ]
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
              xAxes: [{
                gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                }
              }],
              yAxes: [{
                gridLines: {
                  color: "rgba(0, 0, 0, 0)",
                }
              }]
            }
          }
          }
        )
      }
    },
    mounted() {
      this.loadMeasures();
    }
  }
</script>

<style lang="scss" scoped>
  .chart, .historic {
    height: calc(100vh - 40px);
  }
</style>
