import Vue from 'vue';
import App from './App.vue';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import Vuelidate from 'vuelidate';
import VueTelInput from 'vue-tel-input';
import axios from 'axios';
import VueAxios from 'vue-axios';
import 'vue-toastr-2/dist/vue-toastr-2.min.css';

window.toastr = require('toastr');

Vue.use(VueAxios, axios);
Vue.use(Vuelidate);
Vue.use(VueTelInput);

Vue.config.productionTip = false;

new Vue({
  render: h => h(App),
}).$mount('#app');
