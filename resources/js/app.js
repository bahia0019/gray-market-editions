import Vue from "vue"
import CloudinaryComponent from "./components/CloudinaryComponent.vue"
import Cloudinary, { CldImage, CldTransformation } from "cloudinary-vue"

Vue.use(Cloudinary, {
    configuration: { 
        cloudName: "flaunt-your-site"
    },
    components: [CldImage, CldTransformation],
})

Vue.component("cloudinary-component", CloudinaryComponent)

const app = new Vue({
    el: "#vue-app",
    data() {
        return {
            quantity: "",
            variations: [],
            cloudinaryImages: [],
            processingImage: false
        }
    },
    mounted() {
        window.cloudinaryOnLoad = () => {
            this.processingImage = false
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
        uploadWidget() {
            var myWidget = cloudinary.createUploadWidget({
                cloudName: 'flaunt-your-site',
                uploadPreset: 'ptv4bkw2',
                showPoweredBy: false,
                sources: ['local']
            }, (error, result) => {
                if (!error && result && result.event === "success") {
                    this.cloudinaryImages.push(result.info)
                }

                if (error) {
                    console.log('Cloudinary upload error');
                    console.log(error);
                }
            })

            myWidget.open()
        }
    },
})
