import Vue from "vue"
import EditorComponent from "./components/EditorComponent.vue"
import ExhibitionPrintsQty from "./components/ExhibitionPrintsQty.vue"

require("./theme-scripts.js")

Vue.component("editor-component", EditorComponent)
Vue.component("exhibition-prints-qty", ExhibitionPrintsQty)

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
        if ("exhibition-prints" !== GME_PRODUCT.type) {
            this.infoLinks()
            this.changeFrameSize()
        } else {
            this.delayLoad()
        }
    },
    methods: {
        delayLoad() {
            let interval = setInterval(() => {
                let frameSelect = document.querySelector(
                    "#component_options_1592007075 select"
                )
                // Wait till frame select is loaded to run these functions.
                if (frameSelect) {
                    this.infoLinks()
                    this.changeFrameSize()
                    this.addToCartValidation()
                    clearInterval(interval)
                }
            }, 1000)
        },
        addToCartValidation() {
            // Disable add-to-cart button if no images have been added
            const addtoCartBtn = document.querySelector(
                '.single-product .single_add_to_cart_button'
            )

            if (!addtoCartBtn) return

            this.images.length === 0
                ? addtoCartBtn.setAttribute("disabled", true)
                : addtoCartBtn.removeAttribute("disabled")
        },
        infoLinks() {
            let labels = document.querySelectorAll(".variations .label > label")
            let infoTabWrapper = document.querySelector(".wc-tabs-wrapper")
            let infoIcon =
                '<svg id="icon-question-circle-o" viewBox="0 0 24 28"><path d="M13.75 18.75v2.5c0 0.281-0.219 0.5-0.5 0.5h-2.5c-0.281 0-0.5-0.219-0.5-0.5v-2.5c0-0.281 0.219-0.5 0.5-0.5h2.5c0.281 0 0.5 0.219 0.5 0.5zM17.75 11c0 2.219-1.547 3.094-2.688 3.734-0.812 0.469-1.313 0.766-1.313 1.266v0.5c0 0.281-0.219 0.5-0.5 0.5h-2.5c-0.281 0-0.5-0.219-0.5-0.5v-1.062c0-1.922 1.375-2.531 2.484-3.031 0.938-0.438 1.516-0.734 1.516-1.437 0-0.906-1.141-1.578-2.172-1.578-0.547 0-1.125 0.172-1.484 0.422-0.344 0.234-0.672 0.578-1.25 1.297-0.094 0.125-0.234 0.187-0.391 0.187-0.109 0-0.219-0.031-0.297-0.094l-1.687-1.281c-0.203-0.156-0.25-0.453-0.109-0.672 1.281-2.016 3.078-3 5.453-3v0c2.562 0 5.437 2.031 5.437 4.75zM12 4c-5.516 0-10 4.484-10 10s4.484 10 10 10 10-4.484 10-10-4.484-10-10-10zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12v0c6.625 0 12 5.375 12 12z"></path></svg>'

            if (infoTabWrapper) {
                infoTabWrapper.setAttribute("id", "info-tab-wrapper")
            }

            for (let i = 0; i < labels.length; i++) {
                labels[i].innerHTML =
                    '<a href="#info-tab-wrapper" style="display:flex;">' +
                    infoIcon +
                    "</a>" +
                    labels[i].innerHTML
                labels[i].style.display = "flex"

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
                })
            }
        },
        changeFrameSize() {
            let printSize = document.querySelector(
                "#component_1592172322 #size"
            )
            let frameSelect = document.querySelector(
                "#component_options_1592007075 select"
            )
            // Set default print size.
            printSize.value = "5x7"
            // Sets Frame Size to match the Print Size initially.
            if (frameSelect.options[1].selected) {
                let subInterval = setInterval(() => {
                    let frameSize = document.querySelector(
                        "#component_1592007075 #size"
                    )
                    if (frameSize) {
                        frameSize.value = printSize.selectedOptions[0].value

                        // Checks to see if Print size changes, and updates frame size.
                        printSize.addEventListener("change", () => {
                            frameSize.value = printSize.selectedOptions[0].value
                        })
                        clearInterval(subInterval)
                    }
                }, 500)
            }
        },
    },
    watch: {
        images: {
            handler() {
                this.addToCartValidation()
            },
            immediate: true 
        }
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
