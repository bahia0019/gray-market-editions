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
                `Utah/_DSC0873_d8bcxg.jpg`,
                `Utah/_DSC1086_vovwor.jpg`,
                `Utah/_DSC1041_jkxbgy.jpg`,
                `Utah/_DSC1078_ngpcke.jpg`,
                `Utah/_DSC0975_tyoo0t.jpg`,
                `Utah/_DSC1126_wkdwg2.jpg`,
                `Utah/_DSC0913_di0new.jpg`,
                `Utah/_DSC0841_w3hkst.jpg`,
                `Utah/_DSC1002_sijudo.jpg`,
                `Utah/_DSC0927_dtiu3g.jpg`,
                `Utah/_DSC1000_fc8j4j.jpg`,
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
