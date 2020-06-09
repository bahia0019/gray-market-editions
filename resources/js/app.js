import Vue from "vue"
import EditorComponent from "./components/EditorComponent.vue"

require("./theme-scripts.js")

Vue.component("editor-component", EditorComponent)

const app = new Vue({
    el: "#vue-app",
    data() {
        return {
            images: [],
            currentImage: {},
            showUploader: true,
            processingImage: false,
        }
    },
    mounted() {
        this.infoLinks()
        this.addToCartValidation()
    },
    updated() {
        this.addToCartValidation()
    },
    methods: {
        addToCartValidation() {
            if (this.$refs.addToCart) {
                // Disable add-to-cart button if no images have been added
                const addtoCartBtn = this.$refs.addToCart.querySelector(
                    '.variations_form [type="submit"]'
                )
                this.images.length === 0
                    ? addtoCartBtn.setAttribute("disabled", true)
                    : addtoCartBtn.removeAttribute("disabled")
            }
        },
        infoLinks() {
            let labels = document.querySelectorAll(".variations .label > label")
            let infoTabWrapper = document.querySelector(".wc-tabs-wrapper")
            infoTabWrapper.setAttribute("id", "info-tab-wrapper")

            for (let i = 0; i < labels.length; i++) {
                labels[i].innerHTML =
                    labels[i].innerHTML + '<a href="#info-tab-wrapper">*</a>'

                labels[i].addEventListener("click", (e) => {
                    let wcTabs = document.querySelectorAll(".wc-tabs li")
                    let link = labels[i].innerText
                        .replace(/\*/g, "")
                        .toLowerCase()
                    document.getElementById("info-tab-wrapper").scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                        inline: "start",
                    })

                    for (let j = 0; j < wcTabs.length; j++) {
                        wcTabs[j].classList.remove("active")
                        let tab = wcTabs[j].id.replace(/tab-title-/g, "")
                        if (link === tab) {
                            wcTabs[j].classList.add("active")
                        }
                    }
                    let infoTab = document.querySelectorAll(".wc-tab")
                    for (let k = 0; k < infoTab.length; k++) {
                        infoTab[k].style.display = "none"
                        if ("tab-" + link === infoTab[k].id) {
                            infoTab[k].style.display = "block"
                        }
                    }
                    // document
                    //     .querySelector(
                    //         "#tab-title-" +
                    //             labels[i].innerText
                    //                 .replace(/\*/g, "")
                    //                 .toLowerCase()
                    //     )
                    //     .classList.add("active")
                })
            }
        },
    },
    computed: {
        exhibitionQuantity() {
            return parseInt(this.images.length)
        },
        finData() {
            const data = this.images.map((image) => {
                return {
                    attachment_id: image.id,
                    transformations: image.transformations,
                }
            })

            return JSON.stringify(data)
        },
    },
})
