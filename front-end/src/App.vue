<template>
  <div id="app">
    <WebForm/>
  </div>
</template>

<script>
import WebForm from './components/WebForm.vue';

export default {
  name: 'app',
  components: {
    WebForm,
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
