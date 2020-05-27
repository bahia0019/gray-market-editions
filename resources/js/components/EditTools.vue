<template>
  <div class="edit-tools" :class="{ processingImage: $root.processingImage}">
    <button id="full-frame" @click="$emit('fullFrameClick')">
      <svg viewBox="0 0 32 32">
        <path d="M32 0h-13l5 5-6 6 3 3 6-6 5 5z" />
        <path d="M32 32v-13l-5 5-6-6-3 3 6 6-5 5z" />
        <path d="M0 32h13l-5-5 6-6-3-3-6 6-5-5z" />
        <path d="M0 0v13l5-5 6 6 3-3-6-6 5-5z" />
      </svg>
      <div class="tool-tip">
        <div class="triangle"></div>
        <div class="tip">Full Frame</div>
      </div>
    </button>

    <button id="borders" @click="log">
      <svg viewBox="0 0 32 32">
        <path
          d="M28 0h-24c-2.2 0-4 1.8-4 4v24c0 2.2 1.8 4 4 4h24c2.2 0 4-1.8 4-4v-24c0-2.2-1.8-4-4-4zM28 28h-24v-24h24v24z"
        />
      </svg>
      <div class="tool-tip">
        <div class="triangle"></div>
        <div class="tip">Borders</div>
      </div>
    </button>

    <button id="orientation" @click="$emit('orientationClick');">
      <svg xmlns="http://www.w3.org/2000/svg" style="isolation:isolate" viewBox="0 0 32 32">
        <g>
          <path
            d="M28.681 7.159c-.694-.947-1.662-2.053-2.724-3.116-1.062-1.063-2.169-2.03-3.116-2.724C21.229.137 20.448 0 20 0H4.5A2.503 2.503 0 002 2.5v27C2 30.878 3.122 32 4.5 32h23c1.378 0 2.5-1.122 2.5-2.5V10c0-.448-.137-1.23-1.319-2.841zm-4.138-1.702A27.334 27.334 0 0126.811 8H22V3.189a27.334 27.334 0 012.543 2.268zM28 29.5c0 .271-.229.5-.5.5h-23a.507.507 0 01-.5-.5v-27c0-.271.229-.5.5-.5H20v7a1 1 0 001 1h7v19.5z"
          />
          <path
            d="M17.385 21.518v-1.142c1.525-.859 2.769-3.002 2.769-5.145 0-3.442 0-6.231-4.154-6.231s-4.154 2.789-4.154 6.231c0 2.143 1.244 4.286 2.769 5.145v1.142C9.919 21.902 6.307 24.21 6.307 27h19.386c0-2.79-3.612-5.098-8.308-5.482z"
          />
        </g>
      </svg>

      <!-- <svg v-else viewBox="0 0 32 32">
        <path d="M26 28h-20v-4l6-10 8.219 10 5.781-4v8z" />
        <path d="M26 15c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3c1.657 0 3 1.343 3 3z" />
        <path
          d="M28.681 7.159c-0.694-0.947-1.662-2.053-2.724-3.116s-2.169-2.030-3.116-2.724c-1.612-1.182-2.393-1.319-2.841-1.319h-15.5c-1.378 0-2.5 1.121-2.5 2.5v27c0 1.378 1.122 2.5 2.5 2.5h23c1.378 0 2.5-1.122 2.5-2.5v-19.5c0-0.448-0.137-1.23-1.319-2.841zM24.543 5.457c0.959 0.959 1.712 1.825 2.268 2.543h-4.811v-4.811c0.718 0.556 1.584 1.309 2.543 2.268zM28 29.5c0 0.271-0.229 0.5-0.5 0.5h-23c-0.271 0-0.5-0.229-0.5-0.5v-27c0-0.271 0.229-0.5 0.5-0.5 0 0 15.499-0 15.5 0v7c0 0.552 0.448 1 1 1h7v19.5z"
        />
      </svg>-->
      <div class="tool-tip">
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
  data() {
    return {
      images: this.$root.cloudinaryImages
    };
  },
  methods: {
    log() {
      console.log(this.$root.currentImage, this.$root.currentImage.portrait);
    }
  }
};
</script>

<style lang="scss" scoped>
.edit-tools {
  display: flex;
  justify-content: flex-end;
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
}

.edit-tools button:hover .tool-tip {
  visibility: visible;
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