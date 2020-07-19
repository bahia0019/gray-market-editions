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
                        this.infoLinkText()
                        this.infoLinksPrints()
                        this.infoLinksFrames()
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
            infoLinkText() {
                const editionPricingLink = document.querySelector(
                    ".edition-link"
                )
                editionPricingLink.addEventListener("click", () => {
                    this.linkTarget(editionPricingLink.className)
                })
            },
            infoLinksPrints() {
                let labels = document.querySelectorAll(
                    ".variations .label > label"
                )
                let infoTabWrapper = document.querySelector(".wc-tabs-wrapper")
                let infoTabLinks = infoTabWrapper.querySelectorAll("h2")

                for (let i = 0; i < infoTabLinks.length; i++) {
                    for (let j = 0; j < labels.length; j++) {
                        let link = labels[j].htmlFor
                        if (link === infoTabLinks[i].dataset.infoTab) {
                            labels[j].innerHTML =
                                labels[j].innerHTML +
                                '<a href="#info-tab-wrapper" style="display:flex;align-items: center; margin-left:10px; font-size:65%;text-decoration: underline;">Learn More</a>'

                            labels[j].style.display = "flex"

                            labels[j].addEventListener("click", (e) => {
                                let clicked = labels[j].htmlFor
                                this.linkTarget(clicked)
                            })
                        }
                    }
                }
            },
            infoLinksFrames() {
                let frameSelect = document.querySelector(
                    "#component_options_1592007075 select"
                )
                frameSelect.addEventListener("change", () => {
                    let interval = setInterval(() => {
                        let frame = document.querySelector("select#frame-style")
                        if (frame) {
                            clearInterval(interval)
                            const frameComponent = document.querySelector(
                                "#component_1592007075"
                            )
                            let labels = frameComponent.querySelectorAll(
                                ".variations .label > label"
                            )
                            let infoTabWrapper = document.querySelector(
                                ".wc-tabs-wrapper"
                            )
                            let infoTabLinks = infoTabWrapper.querySelectorAll(
                                "h2"
                            )
                            for (let i = 0; i < infoTabLinks.length; i++) {
                                for (let j = 0; j < labels.length; j++) {
                                    let link = labels[j].htmlFor
                                    if (
                                        link === infoTabLinks[i].dataset.infoTab
                                    ) {
                                        labels[j].innerHTML =
                                            labels[j].innerHTML +
                                            '<a href="#info-tab-wrapper" style="display:flex;align-items: center; margin-left:10px; font-size:65%;text-decoration: underline;">Learn More</a>'

                                        labels[j].style.display = "flex"

                                        labels[j].addEventListener(
                                            "click",
                                            (e) => {
                                                let clicked = labels[j].htmlFor
                                                this.linkTarget(clicked)
                                            }
                                        )
                                    }
                                }
                            }
                        }
                    }, 1000)
                })
            },
            linkTarget(clicked) {
                let infoTabWrapper = document.querySelector(".wc-tabs-wrapper")
                let infoTabLinks = infoTabWrapper.querySelectorAll("h2")
                if (infoTabWrapper) {
                    infoTabWrapper.setAttribute("id", "info-tab-wrapper")
                }
                let wcTabs = document.querySelectorAll(".wc-tabs li")

                document.getElementById("info-tab-wrapper").scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                    inline: "start",
                })
                for (let k = 0; k < wcTabs.length; k++) {
                    wcTabs[k].classList.remove("active")
                }
                for (let l = 0; l < infoTabLinks.length; l++) {
                    infoTabLinks[l].parentElement.style.display = "none"
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
                            .querySelector("#tab-title-frame_rental")
                            .classList.add("active")

                        break
                    case "color-correction":
                        document.querySelector(
                            ".woocommerce-Tabs-panel--frame_options"
                        ).style.display = "block"
                        document
                            .querySelector("#tab-title-frame_options")
                            .classList.add("active")

                        break
                    case "edition-link":
                        document.querySelector(
                            "#tab-edition_pricing"
                        ).style.display = "block"
                        document
                            .querySelector("#tab-title-edition_pricing")
                            .classList.add("active")
                        break
                }
            },
            checkFrameSize() {
                const frameSelect = document.querySelector(
                    "select#component_options_1592007075"
                )

                frameSelect.addEventListener("change", () => {
                    let interval = setInterval(() => {
                        const printSize = document.querySelector(
                            "#component_1592172322 #size"
                        )
                        const frameSize = document.querySelector(
                            "#component_1592007075 #size"
                        )
                        let button = document.querySelector(".composite_wrap")

                        if (frameSize) {
                            clearInterval(interval)
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
                        }
                    }, 500)
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
