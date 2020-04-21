<template>
  <div>
    <svg width="22" height="31" viewBox="0 0 22 31" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M12.6286 1.31945C12.1625 -0.39957 9.7263 -0.479608 9.19798 1.31945C6.45247 10.7424 0.581635 13.303 0.581635 19.9444C0.581635 25.8242 5.20269 30.5816 10.9133 30.5816C16.6238 30.5816 21.2449 25.8242 21.2449 19.9444C21.2449 13.2696 15.387 10.7854 12.6286 1.31945ZM10.9133 26.7589C7.28839 26.7589 4.33859 23.7575 4.33859 20.0692C4.33859 19.5412 1.97597 19.9444 2.4949 19.9444C3.01383 19.9444 3.64286 22.4082 3.64286 22.9362C3.64286 25.5709 10.0392 27.5235 12.6286 27.5235C13.1475 27.5235 11.8525 25.2752 11.8525 25.8033C11.8525 26.3313 11.4322 26.7589 10.9133 26.7589Z" fill="white"/>
    </svg>
    <p><span>{{ Math.round(hum) }}</span>%</p>
  </div>
</template>

<script>
export default {
  name: 'LiveHumidity',
  props: ['hum']
}
</script>

<style scoped>
div {
  text-align: center;
  width: 40vw;
  height: 20vh;
}
div p {
  font-size: 36px;
  font-weight: bold;
  color: #fff;
}
div svg {
  padding-top:15%;
}
</style>
