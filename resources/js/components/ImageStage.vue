<template>
    <div>
        <div
            class="stage"
            :class="{
                'processing-image': $root.processingImage,
            }"
        >
            <img
                id="canvas"
                :src="canvas"
                :style="canvasStyles"
                :width="canvasLongDimension"
                :height="canvasShortDimension"
            />
            <img
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
                @setBorder="setBorder"
                :portrait="portrait"
                @switchOrientation="switchOrientation"
                @angleClick="setAngle"
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
            canvas: "",
            canvasLongDimension: "",
            canvasShortDimension: "",
            portraitCanvasLongDimension: "",
            portraitCanvasShortDimension: "",
            imageLongDimension: "",
            imageShortDimension: "",
            imageCrop: "",
            imageProportion: "",
            imageDPI: "",
            dpiWarning: false,
            fullFrame: false,
            portrait: false,
            angle: 0,
            border: "",
        }
    },
    mounted() {
        this.getCanvasInfo()
        this.getCanvas()
        this.getImageInfo()
        this.setOrientation()
    },
    updated() {
        this.getCanvasWidth()
    },
    computed: {
        canvasStyles() {
            return {
                position: "relative",
                backgroundColor: "#fff",
                border: "1px solid #666",
            }
        },
        imageStyles() {
            return {
                backgroundColor: "#fff",
                objectFit: this.imageCrop,
                position: "absolute",
                zIndex: 1000,
            }
        },
    },
    methods: {
        // Takes the Print Size info from the Product Size select dropdown.
        getCanvasInfo() {
            let size = document.querySelector("select#size")
            size.addEventListener("change", () => {
                this.getCanvasInfo()
                this.getCanvas()
                this.getImageInfo()
                this.setOrientation()
                console.log(this.canvasProportion)
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
        getCanvas() {
            switch (this.canvasProportion) {
                case 0.714:
                    this.canvas =
                        "http://gray-market-editions.local/wp-content/uploads/2020/06/714.png"
                    break
                case 0.786:
                    this.canvas =
                        "http://gray-market-editions.local/wp-content/uploads/2020/06/786.png"
                    break
                case 0.8:
                    this.canvas =
                        "http://gray-market-editions.local/wp-content/uploads/2020/06/800.png"
                    break
                case 0.833:
                    this.canvas =
                        "http://gray-market-editions.local/wp-content/uploads/2020/06/833.png"
                    break

                default:
                    this.canvas =
                        "http://gray-market-editions.local/wp-content/uploads/2020/06/800.png"
            }
        },
        getCanvasWidth() {
            window.addEventListener("resize", function() {
                this.modHeight = document.querySelector("#canvas").offsetWidth
            })
        },
        getImageInfo() {
            // If LANDSCAPE.
            if (
                this.image.editor_image.width > this.image.editor_image.height
            ) {
                this.portrait = false
                this.dpiCheck()
                // If PORTRAIT.
            } else {
                this.portrait = true
            }
        },
        setOrientation() {
            if (true === this.portrait) {
                // Portrait.
                this.portraitCanvasLongDimension = this.canvasLongDimension / 2
                this.portraitCanvasShortDimension = Math.floor(
                    this.canvasShortDimension / 2
                )
                this.imageProportion = (
                    this.image.editor_image.width /
                    this.image.editor_image.height
                ).toFixed(4)
                this.imageLongDimension = 500
                this.imageShortDimension = Math.floor(
                    this.imageLongDimension * this.imageProportion
                )
            } else {
                //   Landscape
                this.imageProportion =
                    this.image.editor_image.height /
                    this.image.editor_image.width
                this.imageLongDimension = this.canvasLongDimension
                this.imageShortDimension = Math.floor(
                    this.canvasLongDimension * this.imageProportion
                )
            }
        },
        switchOrientation() {
            if (true === this.portrait) {
                this.portrait = false
                this.canvasLongDimension = 1000
                this.canvasShortDimension = Math.round(
                    parseInt(this.widthHeight[0]) * this.canvasMultiplier
                )
            } else {
                this.portrait = true
                this.portraitCanvasLongDimension = this.canvasLongDimension / 2
                this.portraitCanvasShortDimension = Math.floor(
                    this.canvasShortDimension / 2
                )
            }
        },
        setFullFrame() {
            if (false === this.fullFrame) {
                this.fullFrame = true
                if (true === this.portrait) {
                    this.imageLongDimension = this.portraitCanvasLongDimension
                    this.imageShortDimension = this.portraitCanvasShortDimension

                    this.imageCrop = "fill"
                } else {
                    this.imageLongDimension = this.canvasLongDimension
                    this.imageShortDimension = this.canvasShortDimension

                    this.imageCrop = "fill"
                }
            } else {
                this.fullFrame = false
                if (true === this.portrait) {
                    this.imageLongDimension = this.portraitCanvasLongDimension
                    this.imageShortDimension = Math.floor(
                        this.portraitCanvasLongDimension * this.imageProportion
                    )

                    this.imageCrop = "fit"
                } else {
                    this.imageLongDimension = this.canvasLongDimension
                    this.imageShortDimension = Math.floor(
                        this.canvasLongDimension * this.imageProportion
                    )
                    this.imageCrop = "fit"
                }
            }
        },
        //   Set and Reflect Angle
        setAngle() {
            this.angle = this.angle === 270 ? 0 : this.angle + 90
            this.reflectAngle()
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
        setBorder(border) {
            let dpi = 1000 / this.longInch

            switch (border) {
                case "zero":
                    border = 0
                    break
                case "quarterInch":
                    border = dpi * 0.25
                    break
                case "halfInch":
                    border = dpi * 0.5
                    break
                case "threeQuarterInch":
                    border = dpi * 0.75
                    break
                case "inch":
                    border = dpi * 1
                    break

                default:
                    border = 0
            }
            this.border = border + "px_solid_rgb:ffffff"
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
