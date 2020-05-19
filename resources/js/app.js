import Vue from 'vue'
import Cloudinary from 'cloudinary-vue'
import CloudinaryComponent from './components/CloudinaryComponent.vue'

Vue.use(Cloudinary)

Vue.component('cloudinary-component', CloudinaryComponent)

// Temp testing function
function randomNumber(min, max) {
  return Math.floor(Math.random() * (max - min) + min)
}

const app = new Vue({
  el: '#vue-app',
  data() {
    return {
      quantity: '',
      variations: [],
      cloudinaryUrls: [
        `https://i.picsum.photos/id/${randomNumber(1, 20)}/600/400.jpg`,
        `https://i.picsum.photos/id/${randomNumber(1, 20)}/600/400.jpg`,
        `https://i.picsum.photos/id/${randomNumber(1, 20)}/600/400.jpg`,
        `https://i.picsum.photos/id/${randomNumber(1, 20)}/600/400.jpg`,
        `https://i.picsum.photos/id/${randomNumber(1, 20)}/600/400.jpg`,
        `https://i.picsum.photos/id/${randomNumber(1, 20)}/600/400.jpg`,
      ],
    }
  },
  methods: {
    setQuantity(e) {
      this.quantity = e.target.value
    },
    setVariations(e) {
      let exists = this.variations.find(
        (variation) => variation.name === e.target.id
      )

      if (exists) {
        exists.value = e.target.value
      } else {
        this.variations.push({
          name: e.target.id,
          value: e.target.value,
        })
      }
    },
  },
})
