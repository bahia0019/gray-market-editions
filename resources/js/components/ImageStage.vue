<template>
    <div>
        <div
            class="stage"
            :class="{
                'processing-image': $root.processingImage,
            }"
        >
            <img
                v-if="portrait"
                :class="{ scale: scale }"
                :width="imageShortDimension"
                :height="imageLongDimension"
                :style="imageStyles"
                :src="image.editor_image.url"
            />
            <img
                v-else
                :class="{ scale: scale }"
                :width="imageLongDimension"
                :height="imageShortDimension"
                :style="imageStyles"
                :src="image.editor_image.url"
            />
            <svg viewBox="0 0 32 32" class="spinner">
                <path
                    d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
                />
            </svg>
        </div>

        <div class="tray">
            <div :class="{ warning: dpiWarning }">
                Your image is {{ imageDPI }} dpi
            </div>
            <edit-tools
                :fullFrame="fullFrame"
                @fullFrameClick="setFullFrame"
                @setBorder="setBorders"
                :portrait="portrait"
                @toggleOrientation="toggleOrientation"
                @angleClick="rotate"
            ></edit-tools>
        </div>

        <watch-image-stage></watch-image-stage>
    </div>
</template>

<script>
import EditTools from "./EditTools.vue"
import WatchImageStage from "./WatchImageStage.vue"

export default {
    components: {
        EditTools,
        WatchImageStage,
    },
    props: {
        image: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            canvasLongDimension: "",
            canvasShortDimension: "",
            border: "",
            borderTop: "",
            borderRight: "",
            borderBottom: "",
            borderLeft: "",
            portraitCanvasLongDimension: "",
            portraitCanvasShortDimension: "",
            imageLongDimension: "",
            imageShortDimension: "",
            scaleFactor: "",
            imageProportion: "",
            imageDPI: "",
            dpiWarning: false,
            fullFrame: false,
            portrait: false,
            angle: 0,
        }
    },
    mounted() {
        this.getImageInfo()
        this.setOrientation()
        this.setBorders()
        this.dpiCheck()
    },
    updated() {},
    computed: {
        imageStyles() {
            return {
                backgroundColor: "#fff",
                border: "1px solid #666",
                position: "",
                paddingTop: this.borderTop + "%",
                paddingRight: this.borderRight + "%",
                paddingBottom: this.borderBottom + "%",
                paddingLeft: this.borderLeft + "%",
                transform: `scale(${this.scaleFactor})`,
                transform: `rotate(${this.angle}deg)`,
            }
        },
    },
    methods: {
        // Takes the Print Size info from the Product Size select dropdown.
        getImageInfo() {
            let size = document.querySelector("select#size")
            size.addEventListener("change", () => {
                this.getImageInfo()
                this.setOrientation()
                this.setBorders()
                this.dpiCheck()
            })
            this.widthHeight = size.selectedOptions[0].value.split("x")
            this.longInch = parseInt(this.widthHeight[1])
            this.canvasLongDimension = 1000
            this.canvasMultiplier = 1000 / parseInt(this.widthHeight[1])
            this.canvasShortDimension = Math.round(
                parseInt(this.widthHeight[0]) * this.canvasMultiplier
            )
            this.canvasProportion =
                this.canvasShortDimension / this.canvasLongDimension
        },
        setOrientation() {
            if (
                this.image.editor_image.height > this.image.editor_image.width
            ) {
                this.portrait = true
                // Portrait.
                this.imageProportion = (
                    this.image.editor_image.width /
                    this.image.editor_image.height
                ).toFixed(4)

                this.imageLongDimension = 500
                this.imageShortDimension = Math.floor(
                    this.imageLongDimension * this.imageProportion
                )
            } else {
                // Landscape
                this.portrait = false
                this.imageProportion =
                    this.image.editor_image.height /
                    this.image.editor_image.width
                this.imageLongDimension = this.canvasLongDimension
                this.imageShortDimension = Math.floor(
                    this.canvasLongDimension * this.imageProportion
                )
            }
        },
        toggleOrientation() {
            if (true === this.portrait) {
                //Set -> Landscape
                this.portrait = false
                this.imageLongDimension = this.canvasLongDimension
                this.imageShortDimension = Math.floor(
                    this.canvasLongDimension * this.imageProportion
                )
            } else {
                //Set -> Portrait
                this.portrait = true
                this.imageLongDimension = 500
                this.imageShortDimension = Math.floor(
                    this.imageLongDimension * this.imageProportion
                )
            }
        },
        setFullFrame() {
            if (false === this.fullFrame) {
                this.fullFrame = true
                if (true === this.portrait) {
                    this.scaleFactor =
                        this.portraitCanvasShortDimension /
                        this.imageShortDimension
                } else {
                    this.scaleFactor =
                        this.canvasShortDimension / this.imageShortDimension //GOOD!
                }
            } else {
                this.fullFrame = false
                this.scaleFactor = 1
            }
        },
        //   Set and Reflect Angle
        rotate() {
            this.angle = this.angle === 270 ? 0 : this.angle + 90
        },

        dpiCheck() {
            this.imageDPI = Math.round(
                this.image.editor_image.width / this.longInch
            )
            parseInt(this.imageDPI)
            if (200 > this.imageDPI) {
                this.dpiWarning = true
            } else {
                this.dpiWarning = false
            }
        },
        setBorders(border) {
            if (!border) {
                //if no borders are selected, checks for difference in aspect ratio.
                if (true === this.portrait) {
                    this.borderTop = this.borderBottom =
                        ((this.portraitCanvasLongDimension -
                            this.imageLongDimension) /
                            2 /
                            this.portraitCanvasLongDimension) *
                        100
                } else {
                    this.borderTop = this.borderBottom =
                        ((this.canvasProportion - this.imageProportion) / 2) *
                        100 //GOOD!
                }
            } else {
                //if any borders are selected
                switch (border) {
                    case "zero":
                        border = 0
                        break
                    case "quarterInch":
                        border =
                            0.25 *
                            (this.canvasLongDimension / this.longInch) *
                            0.1
                        break
                    case "halfInch":
                        border =
                            0.5 *
                            (this.canvasLongDimension / this.longInch) *
                            0.1
                        break
                    case "threeQuarterInch":
                        border =
                            0.75 *
                            (this.canvasLongDimension / this.longInch) *
                            0.1
                        break
                    case "inch":
                        border =
                            1 * (this.canvasLongDimension / this.longInch) * 0.1
                        break

                    default:
                        border = 0
                }
                this.border = border
                this.borderTop = this.border
                this.borderRight = this.border
                this.borderBottom = this.border
                this.borderLeft = this.border
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
}
</script>

<style lang="scss" scoped>
.stage {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.frame {
    display: none;
    position: absolute;
    border: 30px solid transparent;
    width: 100%;
    height: 100%;
}
.frame-black {
    border-color: #000;
}
.frame-down-n-dirty {
    border-image: url(https://img.freepik.com/free-photo/wooden-textured-background_53876-14865.jpg?size=626&ext=jpg)
        50 round;
}
.frame.active {
    display: block;
}

@keyframes spinner {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.spinner {
    width: 25px;
    position: absolute;
    z-index: 10;
    animation-name: spinner;
    animation-duration: 1s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}

.stage .spinner {
    display: none;
}
.stage.processing-image {
    filter: opacity(0.5);
}

.stage.processing-image .spinner {
    display: block;
}

.cld-image {
    border: 1px solid #999;
}

.tray {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: green;
}

.warning {
    color: orangered;
}
</style>
