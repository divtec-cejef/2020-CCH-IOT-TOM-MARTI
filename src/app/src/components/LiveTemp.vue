<template>
  <div>
    <p><span>{{ temp }}</span>°</p>
    <p>{{ day }}</p>
  </div>
</template>

<script>
export default {
  name: 'LiveTemp',
  props: ['temp', 'day']
}
</script>

<style scoped>
div {
  width: 100vw;
  height: 80vh;
}
div p {
  text-align: center;
  font-size: 36px;
  color: #fff;
  font-weight: bold;
  display: block;
  margin-top: -13px;
}
div p:first-child {
  font-size: 96px;
  margin-top: 0;
  padding-top: 40%;
}
</style>
