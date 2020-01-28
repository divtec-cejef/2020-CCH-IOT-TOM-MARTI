<template>
  <div>
    <div class="select">
      <select v-model="selected" @change="updateTemp">
        <option v-for="room in rooms" :key="room.id" :value="room.id">{{ room.name }}</option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RoomMenu',
  props: ['rooms'],
  data () {
    return {
      selected: 1
    }
  },
  methods: {
      updateTemp () {
        this.$parent.getTemps(this.selected);
      }
  }
}
</script>

<style scoped>
div {
  width: 60vw;
  height: 20vh;
  vertical-align: top;
}
select {
  color: #fff;
  font-size: 36px;
  background-color: transparent;
  border: none;
  font-weight: bold;
}
  .select {
    padding-top: 20%;
    padding-left: 20px;
  }
</style>
