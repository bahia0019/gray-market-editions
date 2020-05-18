import Vue from 'vue'


if (document.getElementById('vue-single-product')) {
  const singleProduct = new Vue({
    el: '#vue-single-product',
    name: 'singleProduct',
    data() {
      return {
        quantity: '',
        variations: []
      }
    },
    methods: {
      setQuantity(e) {
        this.quantity = e.target.value
      },
      setVariations(e) {
        let exists = this.variations.find(variation => variation.name === e.target.id)

        if (exists) {
          exists.value = e.target.value
        } else {
          this.variations.push({
            name: e.target.id,
            value: e.target.value
          })
        }
      }
    },
  })
}