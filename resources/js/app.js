import Vue from "vue"
import EditorComponent from "./components/EditorComponent.vue"

Vue.component("editor-component", EditorComponent)

const app = new Vue({
    el: "#vue-app",
    data() {
        return {
            images: [],
            currentImage: {},
            processingImage: false,
        }
    },
    mounted() {
        this.infoLinks()
    },
    methods: {
        infoLinks() {
            let labels = document.querySelectorAll(".variations .label > label")
            for (let i = 0; i < labels.length; i++) {
                labels[i].innerHTML =
                    labels[i].innerHTML +
                    '<a href="#tab-title-' +
                    labels[i].innerHTML.toLowerCase() +
                    '">*</a>'

                labels[i].addEventListener("click", (e) => {
                    let wcTabs = document.querySelectorAll(".wc-tabs li")
                    let link = labels[i].innerText
                        .replace(/\*/g, "")
                        .toLowerCase()

                    for (let j = 0; j < wcTabs.length; j++) {
                        wcTabs[j].classList.remove("active")
                        let tab = wcTabs[j].id.replace(/tab-title-/g, "")
                        if (link === tab) {
                            wcTabs[j].scrollIntoView({ behavior: "smooth" })
                            wcTabs[j].classList.add("active")
                            console.log(wcTabs[j])
                        }
                    }
                    document
                        .querySelector(
                            "#tab-title-" +
                                labels[i].innerText
                                    .replace(/\*/g, "")
                                    .toLowerCase()
                        )
                        .classList.add("active")
                })
            }
        },
        getFrameInfo() {
            let frame = document.querySelector("select#frame")
            let regex = /[!"#$%&'()*+,./:;<=>?@[\]^_`{|}~]/g
            let frameStyle
            frame.addEventListener("change", (event) => {
                frameStyle = frame.selectedOptions[0].value
                    .toLowerCase()
                    .replace(regex, "")
                    .replace(/ +/g, "-")

                switch (frameStyle) {
                    case "black":
                        this.$refs.frame.classList = "frame"
                        this.$refs.frame.classList.add("frame-black", "active")
                        break
                    case "down-n-dirty":
                        this.$refs.frame.classList = "frame"
                        this.$refs.frame.classList.add(
                            "frame-down-n-dirty",
                            "active"
                        )
                        break
                    default:
                        this.$refs.frame.classList = "frame"
                }
            })
        },
    },
    computed: {
        finalUrls() {
            // const urls = this.images.map((image) => image.gme_final_url);
            // return JSON.stringify(urls);
        },
    },
})
