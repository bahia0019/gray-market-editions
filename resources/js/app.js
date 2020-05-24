import Vue from "vue"
import CloudinaryComponent from "./components/CloudinaryComponent.vue"
import Cloudinary, { CldImage, CldTransformation } from "cloudinary-vue"

Vue.use(Cloudinary, {
    configuration: {
        cloudName: "flaunt-your-site",
    },
    components: [CldImage, CldTransformation],
})

Vue.component("cloudinary-component", CloudinaryComponent)

const app = new Vue({
    el: "#vue-app",
    data() {
        return {
            finalImages: [],
            cloudinaryImages: [],
            cloudinaryTestImages: [
                { public_id: "Utah/_DSC0873_d8bcxg.jpg" },
                { public_id: "Utah/_DSC1086_vovwor.jpg" },
                { public_id: "Utah/_DSC1041_jkxbgy.jpg" },
                { public_id: "Utah/_DSC1078_ngpcke.jpg" },
                { public_id: "Utah/_DSC0975_tyoo0t.jpg" },
                { public_id: "Utah/_DSC1126_wkdwg2.jpg" },
                { public_id: "Utah/_DSC0913_di0new.jpg" },
                { public_id: "Utah/_DSC0841_w3hkst.jpg" },
                { public_id: "Utah/_DSC1002_sijudo.jpg" },
                { public_id: "Utah/_DSC0927_dtiu3g.jpg" },
                { public_id: "Utah/_DSC1000_fc8j4j.jpg" },
            ],
            processingImage: false,
        }
    },
    mounted() {
        window.cloudinaryOnLoad = () => {
            this.processingImage = false
        }
    },
    methods: {
        uploadWidget() {
            var myWidget = cloudinary.createUploadWidget(
                {
                    cloudName: "flaunt-your-site",
                    uploadPreset: "ptv4bkw2",
                    showPoweredBy: false,
                    sources: ["local"],
                },
                (error, result) => {
                    if (!error && result && result.event === "success") {
                        this.cloudinaryImages.push(result.info)
                        this.finalImages.push({
                            public_id: result.info.public_id,
                            url: result.info.secure_url
                        })
                    }

                    if (error) {
                        console.log("Cloudinary upload error")
                        console.log(error)
                    }
                }
            )

            myWidget.open()
        },
    },
    computed: {
        finalUrls() {
            return this.finalImages.map(image => image.url)
        }
    },
})
