<template>
  <div id="app">
    <live-temp  v-show="!isGraph" :temp="selectedTemp.temperature !== undefined ? selectedTemp.temperature : '--'" :day="day"></live-temp>
    <graph-temp v-show="isGraph" :temps="temp" :hum="hum" :time="time" ref="graphO"></graph-temp>
    <div>
      <room-menu :rooms="rooms"></room-menu>
      <live-humidity v-show="!isGraph" :hum="selectedTemp.humidity !== undefined ? selectedTemp.humidity : '--'"></live-humidity>
      <back v-show="isGraph"></back>
    </div>
  </div>
</template>

<script>
// import LiveTemp from "./components/LiveTemp";
import LiveHumidity from "./components/LiveHumidity";
import RoomMenu from "./components/RoomMenu";
import GraphTemp from "./components/GraphTemp";
import Back from "./components/Back";
import LiveTemp from "./components/LiveTemp";
import axios from "axios"

export default {
  name: 'app',
  components: {
    // LiveTemp,
    LiveHumidity,
    RoomMenu,
    GraphTemp,
    Back,
    LiveTemp
  },
  data () {
    return {
      selectedTemp: {},
      rooms: [],
      temps: [],
      temp: [],
      hum: [],
      time: [],
      isGraph: false,
      day: undefined
    }
  },
  methods: {
    getRooms () {
      axios.get('https://temp.martitom.ch/rooms').then(response => {
        this.rooms = response.data;
      })
    },
    getTemps (id) {
      Date.prototype.addDays = function(days) {
        let day = this.getDate()+days;
        this.setDate(day);
      };
      let date = new Date();
      let day = date.getDate();
      let year = date.getFullYear();
      let month = date.getMonth() + 1;

      date.addDays(-7);
      let mday = date.getDate();
      let myear = date.getFullYear();
      let mmonth = date.getMonth() + 1;
      this.temps = [];
      this.selectedTemp = {};
      this.temp = [];
      this.hum = [];
      this.time = [];
      axios.get('https://temp.martitom.ch/rooms/' + id + '/measures/'+myear+'-'+mmonth+'-'+mday+'/'+year+'-'+month+'-'+day).then(response => {
        this.temps = response.data;
        this.selectedTemp = this.temps[this.temps.length - 1];
        this.temps.forEach(temp => {
          this.temp.push(temp.temperature);
          this.hum.push(temp.humidity);
          let tDate = new Date(parseInt(temp.time)*1000);
          this.time.push(tDate.getFullYear()+'-'+(tDate.getMonth() + 1) +'-'+tDate.getDate());
        });
        this.$refs.graphO.setChart(this.temp, this.hum, this.time);
      })
    }
  },
  mounted () {
    this.getRooms();
    this.getTemps(1);
  }
}
</script>

<style>
  @import url('https://fonts.googleapis.com/css?family=Quicksand&display=swap');
  * {
    margin: 0;
    padding: 0;
    font-family: 'Quicksand', sans-serif;
  }
#app {
  background-image: linear-gradient(to bottom right, red, yellow);
  height: 100vh;
  max-height: 100vh;
  max-width:100vw;
  overflow: hidden;
}
  div div {
    display: inline-block;
  }
</style>
