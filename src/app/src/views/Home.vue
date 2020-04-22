<template>
  <div class="home">
    <div class="editbutton">
      <router-link :to="'/edit/' + temp.last.id + '/' + temp.last.temperature + '/' + temp.last.humidity">
        <img src="../assets/edit-solid.svg" alt="">
      </router-link>
    </div>
    <p class="date">{{ date }}</p>
    <div class="order">
      <div class="temp">
        <p class="legend">Température</p>
        <p>{{ temp.last.temperature }}°</p>
        <p class="min-max">{{ temp.temperature.min }}° - {{ temp.temperature.max }}°</p>
      </div>
      <div class="hum">
        <p class="legend">Humidité</p>
        <p>{{ temp.last.humidity }}%</p>
        <p class="min-max">{{ temp.humidity.min }}% - {{ temp.humidity.max }}%</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Home',
  data () {
    return {
      temp: Object,
      date: ""
    }
  },
  methods: {
    loadTemp () {
      axios.get('https://temp.martitom.ch/api/measures').then(response => {
        this.temp = response.data;
      })
    },
    setDate () {
      const weekDay = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
      let date = new Date();
      this.date += weekDay[date.getDay()] + ", ";
      this.date += date.getDate() + " ";
      const month = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
      this.date += month[date.getMonth()];
    }
  },
  created () {
    this.setDate();
    this.loadTemp();
  }
}
</script>

<style lang="scss" scoped>
  p {
    margin: 0;
  }
  .home {
    max-width: 250px;
    text-align: left;
    margin: auto;
    padding-top: 20px;
    .editbutton {
      background-color:  #42b983;
      position:absolute;
      padding: 17px 17px 15px 20px;
      border-radius: 100%;
      bottom: 30px;
      right: 30px;
      z-index: 100;
      a {
        img {
          width: 30px;
          height: 30px;
        }
      }
    }
    .editbutton:hover {
      background-color: rgba(66, 185, 131, 0.58);
    }
    p {
      font-family: roboto, sans-serif;
      font-size: 18px;
    }
    .date {
      margin: 10px 0;
    }
    .temp, .hum {
      padding-top: 20px;
      p {
        font-family: 'Concert One', sans-serif;
        font-weight: bold;
        font-size: 80px;
        margin: 0;
      }
      .min-max {
        font-size: 18px;
      }
      .legend {
        font-family: Roboto, sans-serif;
        font-size: 16px;
        color: #42b983;
      }
    }
    .order {
      display: flex;
      flex-direction: column;
      align-content: space-around;
    }
  }
</style>