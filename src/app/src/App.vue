<template>
  <div id="app">
    <router-view/>
    <div id="nav">
      <router-link to="/app/">home</router-link> |
      <router-link to="/app/historic">history</router-link>
    </div>
  </div>
</template>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Concert+One&family=Roboto:wght@700&display=swap');
html {
  height: 100vh;
  background-image: linear-gradient(#42b98375, #fff);
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 10px 0 10px 0;
  background-color: #fff;

  a {
    font-weight: bold;
    color: #2c3e50;
    text-decoration: none;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
