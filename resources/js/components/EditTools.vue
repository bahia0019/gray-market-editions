<template>
    <div class="edit-tools" :class="{ processingImage: $root.processingImage }">
        <button id="full-frame" @click="$emit('fullFrameClick')">
            <svg v-if="fullFrame" id="icon-shrink" viewBox="0 0 32 32">
                <path d="M18 14h13l-5-5 6-6-3-3-6 6-5-5z"></path>
                <path d="M18 18v13l5-5 6 6 3-3-6-6 5-5z"></path>
                <path d="M14 18h-13l5 5-6 6 3 3 6-6 5 5z"></path>
                <path d="M14 14v-13l-5 5-6-6-3 3 6 6-5 5z"></path>
            </svg>

            <svg v-else id="full-frame" viewBox="0 0 32 32">
                <path d="M32 0h-13l5 5-6 6 3 3 6-6 5 5z" />
                <path d="M32 32v-13l-5 5-6-6-3 3 6 6-5 5z" />
                <path d="M0 32h13l-5-5 6-6-3-3-6 6-5-5z" />
                <path d="M0 0v13l5-5 6 6 3-3-6-6 5-5z" />
            </svg>

            <div v-if="fullFrame" class="tool-tip">
                <div class="triangle"></div>
                <div class="tip">Fit to paper</div>
            </div>

            <div v-else class="tool-tip">
                <div class="triangle"></div>
                <div class="tip">Full Frame</div>
            </div>
        </button>

        <div id="border-group" @click="isHidden = !isHidden">
            <button id="border">
                <svg viewBox="0 0 32 32">
                    <path
                        d="M28 0h-24c-2.2 0-4 1.8-4 4v24c0 2.2 1.8 4 4 4h24c2.2 0 4-1.8 4-4v-24c0-2.2-1.8-4-4-4zM28 28h-24v-24h24v24z"
                    />
                </svg>
            </button>

            <div class="btn-group" v-if="!isHidden">
                <button @click="$emit('setBorder', 'zero')">0</button>
                <button @click="$emit('setBorder', 'quarterInch')">
                    1/4"
                </button>
                <button @click="$emit('setBorder', 'halfInch')">
                    1/2"
                </button>
                <button @click="$emit('setBorder', 'threeQuarterInch')">
                    3/4"
                </button>
                <button @click="$emit('setBorder', 'inch')">1"</button>
            </div>

            <div class="tool-tip" v-if="isHidden">
                <div class="triangle"></div>
                <div class="tip">Borders</div>
            </div>
        </div>

        <button id="orientation" @click="$emit('orientationClick')">
            <!-- If image is Portrait, show Landscape icon -->
            <svg v-if="portrait" id="icon-landscape" viewBox="0 0 32 32">
                <path
                    d="M29.996 4c0.001 0.001 0.003 0.002 0.004 0.004v23.993c-0.001 0.001-0.002 0.003-0.004 0.004h-27.993c-0.001-0.001-0.003-0.002-0.004-0.004v-23.993c0.001-0.001 0.002-0.003 0.004-0.004h27.993zM30 2h-28c-1.1 0-2 0.9-2 2v24c0 1.1 0.9 2 2 2h28c1.1 0 2-0.9 2-2v-24c0-1.1-0.9-2-2-2v0z"
                />
                <path
                    d="M26 9c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"
                />
                <path d="M28 26h-24v-4l7-12 8 10h2l7-6z" />
            </svg>
            <!-- If image is Landscape, show Portrait icon -->
            <svg v-else id="icon-portrait" viewBox="0 0 32 32">
                <g>
                    <path
                        d="M28.681 7.159c-.694-.947-1.662-2.053-2.724-3.116-1.062-1.063-2.169-2.03-3.116-2.724C21.229.137 20.448 0 20 0H4.5A2.503 2.503 0 002 2.5v27C2 30.878 3.122 32 4.5 32h23c1.378 0 2.5-1.122 2.5-2.5V10c0-.448-.137-1.23-1.319-2.841zm-4.138-1.702A27.334 27.334 0 0126.811 8H22V3.189a27.334 27.334 0 012.543 2.268zM28 29.5c0 .271-.229.5-.5.5h-23a.507.507 0 01-.5-.5v-27c0-.271.229-.5.5-.5H20v7a1 1 0 001 1h7v19.5z"
                    />
                    <path
                        d="M17.385 21.518v-1.142c1.525-.859 2.769-3.002 2.769-5.145 0-3.442 0-6.231-4.154-6.231s-4.154 2.789-4.154 6.231c0 2.143 1.244 4.286 2.769 5.145v1.142C9.919 21.902 6.307 24.21 6.307 27h19.386c0-2.79-3.612-5.098-8.308-5.482z"
                    />
                </g>
            </svg>

            <div v-if="portrait" class="tool-tip">
                <div class="triangle"></div>
                <div class="tip">Switch to Landscape layout</div>
            </div>
            <div v-else class="tool-tip">
                <div class="triangle"></div>
                <div class="tip">Switch to Portrait layout</div>
            </div>
        </button>

        <button id="rotate" @click="$emit('angleClick')">
            <svg viewBox="0 0 32 32">
                <path
                    d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
                />
            </svg>
            <div class="tool-tip">
                <div class="triangle"></div>
                <div class="tip">Rotate Image</div>
            </div>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        portrait: {
            type: Boolean,
            required: true,
        },
        fullFrame: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            images: this.$root.cloudinaryImages,
            isHidden: true,
        }
    },
    methods: {},
}
</script>

<style lang="scss" scoped>
.edit-tools {
    display: flex;
    justify-content: flex-end;
    position: relative;
    z-index: 100;
}

.edit-tools button {
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    margin: 0;
    position: relative;
    transition: 350ms;
}
#border-group {
    width: 35px;
    height: 35px;
    position: relative;
}

.edit-tools.processingImage button {
    filter: opacity(0.5);
    cursor: wait;
}

.edit-tools svg {
    width: 17px;
}

/* Tooltips */
.tool-tip {
    visibility: hidden;
    width: fit-content;
    top: 34px;
    position: absolute;
    right: -30px;
    z-index: 100;
}

.edit-tools button:hover .tool-tip,
#border-group:hover .tool-tip {
    visibility: visible;
}

#border + .btn-group {
    // visibility: hidden;
    position: absolute;
}

.triangle {
    width: 5px;
    height: 5px;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-bottom: 10px solid #57b8ff;
    position: relative;
    left: 60%;
}

.tip {
    color: #fff;
    background-color: #57b8ff;
    padding: 0.5em 1.5em;
    border-radius: 0.6em;
    width: fit-content;
    white-space: nowrap;
}
</style>
