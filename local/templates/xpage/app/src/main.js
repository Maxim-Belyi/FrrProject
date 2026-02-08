import Vue from 'vue'
import App from './App.vue'
import store from './store'

Vue.config.productionTip = false

const vue = new Vue({
  store,
  render: h => h(App)
});

document.addEventListener('DOMContentLoaded', () => {
  vue.$mount('#app');
});