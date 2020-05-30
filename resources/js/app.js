import Vue from "vue";
import CloudinaryCore from "cloudinary-core";
import CloudinaryComponent from "./components/CloudinaryComponent.vue";
import Cloudinary, {CldImage, CldTransformation} from "cloudinary-vue";

Vue.prototype.$cloudinaryCore = new CloudinaryCore.Cloudinary({
    cloud_name: "flaunt-your-site", secure: true
});

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
            cloudinaryImages: [],
            cloudinaryTestImages: require("./data.json"),
            processingImage: false,
        };
    },
    mounted() {
        window.cloudinaryOnLoad = () => {
            this.processingImage = false;
        };

        this.infoLinks();
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
                        // Push images, plus append custom 'gme_final_url' 
                        // prop to object for edit tracking
                        this.cloudinaryImages.push({
                            ...result.info,
                            gme_final_url: result.info.secure_url || result.info.url
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
        infoLinks() {
            let labels = document.querySelectorAll(".variations .label > label");
            for (let i = 0; i < labels.length; i++) {
                labels[i].innerHTML =
                    labels[i].innerHTML +
                    '<a href="#tab-title-' +
                    labels[i].innerHTML.toLowerCase() +
                    '">*</a>';

                labels[i].addEventListener("click", () => {
                    let wcTabs = document.querySelectorAll(".wc-tabs li");
                    for (let j = 0; j < wcTabs.length; j++) {
                        wcTabs[j].classList.remove("active");
                        wcTabs[j].scrollIntoView({behavior: "smooth"});
                    }
                    document
                        .querySelector(
                            "#tab-title-" +
                            labels[i].innerText.replace(/\*/g, "").toLowerCase()
                        )
                        .classList.add("active");
                });
            }
        }
    },
    computed: {
        finalUrls() {
            const urls = this.cloudinaryImages.map((image) => image.gme_final_url);

            return JSON.stringify(urls);
        },
    },
});
