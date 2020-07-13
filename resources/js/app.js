import Vue from "vue"
import EditorComponent from "./components/EditorComponent.vue"
import EditionsQuantity from "./components/EditionsQuantity.vue"

require("./theme-scripts.js")

Vue.component("editor-component", EditorComponent)
Vue.component("editions-quantity", EditionsQuantity)

if (GME_PRODUCT.needVue) {
    const app = new Vue({
        el: "#vue-app",
        data() {
            return {
                images: [],
                currentImage: {},
                showUploader: true,
                processingImage: false,
                frameSizeWarning: true,
            }
        },
        mounted() {
            if ("exhibition-prints" !== GME_PRODUCT.type) {
                this.infoLinks()
                this.appendToPrice()
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
                        clearInterval(interval)
                        this.infoLinks()
                        this.checkFrameSize()
                        this.addToCartValidation()
                    }
                }, 1000)
            },
            addToCartValidation() {
                // Disable add-to-cart button if no images have been added
                let addToCartBtn = document.querySelector(
                    ".single_add_to_cart_button"
                )

                if (!addToCartBtn) return

                this.images.length === 0
                    ? addToCartBtn.setAttribute("disabled", true)
                    : addToCartBtn.removeAttribute("disabled")
            },
            infoLinks() {
                let labels = document.querySelectorAll(
                    ".variations .label > label"
                )
                let editionPricingLink = document.querySelector(".edition-link")

                let infoTabWrapper = document.querySelector(".wc-tabs-wrapper")
                let infoTabLinks = infoTabWrapper.querySelectorAll("h2")
                let infoIcon =
                    '<svg id="icon-question-circle-o" viewBox="0 0 24 28"><path d="M13.75 18.75v2.5c0 0.281-0.219 0.5-0.5 0.5h-2.5c-0.281 0-0.5-0.219-0.5-0.5v-2.5c0-0.281 0.219-0.5 0.5-0.5h2.5c0.281 0 0.5 0.219 0.5 0.5zM17.75 11c0 2.219-1.547 3.094-2.688 3.734-0.812 0.469-1.313 0.766-1.313 1.266v0.5c0 0.281-0.219 0.5-0.5 0.5h-2.5c-0.281 0-0.5-0.219-0.5-0.5v-1.062c0-1.922 1.375-2.531 2.484-3.031 0.938-0.438 1.516-0.734 1.516-1.437 0-0.906-1.141-1.578-2.172-1.578-0.547 0-1.125 0.172-1.484 0.422-0.344 0.234-0.672 0.578-1.25 1.297-0.094 0.125-0.234 0.187-0.391 0.187-0.109 0-0.219-0.031-0.297-0.094l-1.687-1.281c-0.203-0.156-0.25-0.453-0.109-0.672 1.281-2.016 3.078-3 5.453-3v0c2.562 0 5.437 2.031 5.437 4.75zM12 4c-5.516 0-10 4.484-10 10s4.484 10 10 10 10-4.484 10-10-4.484-10-10-10zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12v0c6.625 0 12 5.375 12 12z"></path></svg>'
                if (infoTabWrapper) {
                    infoTabWrapper.setAttribute("id", "info-tab-wrapper")
                }
                let wcTabs = document.querySelectorAll(".wc-tabs li")

                for (let i = 0; i < infoTabLinks.length; i++) {
                    for (let j = 0; j < labels.length; j++) {
                        let link = labels[j].htmlFor
                        if (link === infoTabLinks[i].dataset.infoTab) {
                            labels[j].innerHTML =
                                labels[j].innerHTML +
                                '<a href="#info-tab-wrapper" style="display:flex;align-items: center; margin-left:10px; font-size:65%;">Learn More</a>'

                            labels[j].style.display = "flex"

                            labels[j].addEventListener("click", (e) => {
                                let clicked = labels[j].htmlFor

                                document
                                    .getElementById("info-tab-wrapper")
                                    .scrollIntoView({
                                        behavior: "smooth",
                                        block: "start",
                                        inline: "start",
                                    })
                                for (let k = 0; k < wcTabs.length; k++) {
                                    wcTabs[k].classList.remove("active")
                                }
                                for (let l = 0; l < infoTabLinks.length; l++) {
                                    infoTabLinks[
                                        l
                                    ].parentElement.style.display = "none"
                                }
                                switch (clicked) {
                                    case "paper":
                                        document.querySelector(
                                            ".woocommerce-Tabs-panel--paper"
                                        ).style.display = "block"
                                        document
                                            .querySelector("#tab-title-paper")
                                            .classList.add("active")

                                        break
                                    case "ink":
                                        document.querySelector(
                                            ".woocommerce-Tabs-panel--ink"
                                        ).style.display = "block"
                                        document
                                            .querySelector("#tab-title-ink")
                                            .classList.add("active")

                                        break
                                    case "frame-style":
                                        document.querySelector(
                                            ".woocommerce-Tabs-panel--frame"
                                        ).style.display = "block"
                                        document
                                            .querySelector("#tab-title-frame")
                                            .classList.add("active")

                                        break
                                    case "purchase-rent":
                                        document.querySelector(
                                            ".woocommerce-Tabs-panel--frame_rental"
                                        ).style.display = "block"
                                        document
                                            .querySelector(
                                                "#tab-title-frame_rental"
                                            )
                                            .classList.add("active")

                                        break
                                    case "color-correction":
                                        document.querySelector(
                                            ".woocommerce-Tabs-panel--frame_options"
                                        ).style.display = "block"
                                        document
                                            .querySelector(
                                                "#tab-title-frame_options"
                                            )
                                            .classList.add("active")

                                        break
                                }
                            })
                        }
                    }
                }
            },
            checkFrameSize() {
                const printSize = document.querySelector(
                    "#component_1592172322 #size"
                )
                const frameSize = document.querySelector(
                    "#component_1592007075 #size"
                )
                let button = document.querySelector(".composite_wrap")

                button.addEventListener("mouseenter", () => {
                    if (
                        frameSize.value !== printSize.value &&
                        true === this.frameSizeWarning
                    ) {
                        this.frameSizeWarning = false
                        let div = document.createElement("div")
                        div.className = "frame-size-warning"
                        div.innerHTML =
                            'Your Frame Size does not match your Print Size. Please check your size selection before ordering.<button type="button">OK</button>'

                        button.appendChild(div)
                        button.addEventListener("click", (e) => {
                            e.preventDefault
                            div.parentNode.removeChild(div)
                        })
                    }
                })
            },
        },
        watch: {
            images: {
                handler() {
                    this.addToCartValidation()
                },
                immediate: true,
            },
        },
        computed: {
            editionsQuantity() {
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
}
