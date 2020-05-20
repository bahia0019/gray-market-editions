<template>
  <div>
    <cld-context cloudName="flaunt-your-site">
      <cld-image publicId="WilliamBay-PartsPerMillion_DSC5995_lit9ko" :angle="angle" ref="cldImage">
        <cld-transformation :angle="angle" />
      </cld-image>
    </cld-context>

    <div class="edit-tools">
      <button id="full-frame" @click="fullFrame">
        <svg viewBox="0 0 32 32">
          <path d="M32 0h-13l5 5-6 6 3 3 6-6 5 5z" />
          <path d="M32 32v-13l-5 5-6-6-3 3 6 6-5 5z" />
          <path d="M0 32h13l-5-5 6-6-3-3-6 6-5-5z" />
          <path d="M0 0v13l5-5 6 6 3-3-6-6 5-5z" />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">This is a tooltip</div>
        </div>
      </button>

      <button id="borders" @click="borders">
        <svg viewBox="0 0 32 32">
          <path
            d="M28 0h-24c-2.2 0-4 1.8-4 4v24c0 2.2 1.8 4 4 4h24c2.2 0 4-1.8 4-4v-24c0-2.2-1.8-4-4-4zM28 28h-24v-24h24v24z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">This is a tooltip</div>
        </div>
      </button>

      <button id="orientation" @click="orientation">
        <svg viewBox="0 0 32 32">
          <path d="M26 28h-20v-4l6-10 8.219 10 5.781-4v8z" />
          <path d="M26 15c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3c1.657 0 3 1.343 3 3z" />
          <path
            d="M28.681 7.159c-0.694-0.947-1.662-2.053-2.724-3.116s-2.169-2.030-3.116-2.724c-1.612-1.182-2.393-1.319-2.841-1.319h-15.5c-1.378 0-2.5 1.121-2.5 2.5v27c0 1.378 1.122 2.5 2.5 2.5h23c1.378 0 2.5-1.122 2.5-2.5v-19.5c0-0.448-0.137-1.23-1.319-2.841zM24.543 5.457c0.959 0.959 1.712 1.825 2.268 2.543h-4.811v-4.811c0.718 0.556 1.584 1.309 2.543 2.268zM28 29.5c0 0.271-0.229 0.5-0.5 0.5h-23c-0.271 0-0.5-0.229-0.5-0.5v-27c0-0.271 0.229-0.5 0.5-0.5 0 0 15.499-0 15.5 0v7c0 0.552 0.448 1 1 1h7v19.5z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">This is a tooltip</div>
        </div>
      </button>

      <button id="rotate" @click="rotate">
        <svg viewBox="0 0 32 32">
          <path
            d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">This is a tooltip</div>
        </div>
      </button>

      <button id="rotate" @click="rotate">
        <svg viewBox="0 0 32 32">
          <path
            d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">This is a tooltip</div>
        </div>
      </button>
    </div>

    <cld-context class="image-grid" cloudName="flaunt-your-site">
      <cld-image
        class="image-grid-thumb"
        v-for="(image, index) in images"
        :key="index"
        :publicId="image"
      >
        <cld-transformation height="300" width="300" crop="fill" />
      </cld-image>
    </cld-context>
  </div>
</template>

<script>
export default {
  data() {
    return {
      angle: 0,
      currentImage: "",
      images: this.$root.cloudinaryUrls
    };
  },
  mounted() {
    this.watchCldImage();
  },
  methods: {
    rotate() {
      this.angle = this.angle === 270 ? 0 : this.angle + 90;
    },
    setCurrentImage(index) {
      this.currentImage = this.images[index];
    },
    watchCldImage() {
      this.$watch("$refs.cldImage.imageAttrs.src", {
        handler(newUrl, oldUrl) {
          console.log(oldUrl);
          console.log(newUrl);
        },
        immediate: true
      });
    }
  }
};
</script>

<style scoped>
.image-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  grid-template-rows: 1fr;
  grid-gap: 0.5rem;
  margin-top: 2rem;
  padding: 0.5em;
  border: 1px solid #666;
}

.image-grid-thumb {
  background: black;
  display: grid;
}

.image-grid-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  grid-area: 1 / 1 / 2 / 2;
  border-radius: 0;
}

.edit-tools {
  display: flex;
  justify-content: flex-end;
}

.edit-tools button {
  width: 35px;
  height: 35px;
  display: flex;
  justify-content: center;
  align-self: center;
  padding: 0;
  margin: 0;
  position: relative;
}

.edit-tools svg {
  width: 17px;
}

/* Tooltips */

.tool-tip {
  display: none;
  width: fit-content;
  top: 34px;
  position: absolute;
  right: -30px;
}

.edit-tools button:hover .tool-tip {
  display: block;
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