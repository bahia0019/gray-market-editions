import Vue from 'vue'

if (document.getElementById('vue-cart')) {
  const cart = new Vue({
    el: '#vue-cart',
    name: 'cart',
    data() {
      return {}
    },
  })
}
