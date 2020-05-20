import Vue from "vue"
import Cloudinary from "cloudinary-vue"
import CloudinaryComponent from "./components/CloudinaryComponent.vue"

Vue.use(Cloudinary)

Vue.component("cloudinary-component", CloudinaryComponent)

// Temp testing function
function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min)
}

const app = new Vue({
    el: "#vue-app",
    data() {
        return {
            quantity: "",
            variations: [],
            cloudinaryUrls: [
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932583/Utah/_DSC0873_d8bcxg.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932597/Utah/_DSC1086_vovwor.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932600/Utah/_DSC1041_jkxbgy.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932603/Utah/_DSC1078_ngpcke.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932607/Utah/_DSC0975_tyoo0t.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932607/Utah/_DSC1126_wkdwg2.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932609/Utah/_DSC0913_di0new.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932609/Utah/_DSC0841_w3hkst.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932891/Utah/_DSC1002_sijudo.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932896/Utah/_DSC0927_dtiu3g.jpg`,
                `https://res.cloudinary.com/flaunt-your-site/image/upload/v1589932897/Utah/_DSC1000_fc8j4j.jpg`,
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
