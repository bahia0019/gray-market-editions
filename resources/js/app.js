import Vue from "vue";
import CloudinaryComponent from "./components/CloudinaryComponent.vue";
import Cloudinary, {CldImage, CldTransformation} from "cloudinary-vue";

Vue.use(Cloudinary, {
    configuration: {
        cloudName: "flaunt-your-site",
    },
    components: [CldImage, CldTransformation],
});

Vue.component("cloudinary-component", CloudinaryComponent);

const app = new Vue({
    el: "#vue-app",
    data() {
        return {
            currentImage: {},
            finalImages: [],
            cloudinaryImages: [],
            cloudinaryTestImages: require("./data.json"),
            processingImage: false,
        };
    },
    mounted() {
        window.cloudinaryOnLoad = () => {
            this.processingImage = false;
        };
    },
    updated() {
        if (this.cloudinaryImages.length && !this.currentImage.public_id) {
            this.currentImage = this.cloudinaryImages[0];
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
                        this.cloudinaryImages.push(result.info);

                        this.finalImages.push({
                            public_id: result.info.public_id,
                            url: result.info.secure_url,
                        });
                    }

                    if (error) {
                        console.log("Cloudinary upload error");
                        console.log(error);
                    }
                }
            );

            myWidget.open();
        },
    },
    computed: {
        finalUrls() {
            return this.finalImages.map((image) => image.url);
        },
    },
});
