<template>
  <form enctype="multipart/form-data" novalidate v-if="show">
    <div class="dropbox" :class="{'is-overlay': isOverlay}">
      <input
        type="file"
        :multiple="isMultiple"
        :disabled="isSaving"
        @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"
        accept="image/*"
        class="input-file"
      />
      <div class="dropbox__body" :class="{'is-overlay': isOverlay}" v-if="!isSaving">
        <svg viewBox="0 0 32 32">
          <path
            d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM20 20v6h-8v-6h-6l10-8 10 8h-6zM4.828 6l2-2h18.343l2 2h-22.343z"
          />
        </svg>
        <span v-if="isMultiple">
          {{ isOverlay ? 'Drag Here to Upload More' : 'Drag Multiple Images here to Upload' }}
          <br />*Only JPEGs accepted
          <br />*Images should be sized to at least
          <br />200dpi at the size you’d like to print.
        </span>
        <span v-else>
          Upload Single Image
          <br />(Only JPEGs accepted)
        </span>
        <span class="cancel" @click="$root.showUploader = false" v-if="isOverlay">cancel</span>
      </div>
      <div class="loading-text" v-if="isSaving && isMultiple">
        <p>Uploading {{ fileCount }} files...</p>
        <p>Don't close window. Multiple large files may take a couple minutes.</p>
        <svg viewBox="0 0 32 32" class="spinner">
          <path
            d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
          />
        </svg>
      </div>
      <div class="loading-text" v-if="isSaving && !isMultiple">
        <p>Uploading file...</p>
        <p>Don't close window. Large files may take a couple minutes.</p>
        <svg viewBox="0 0 32 32" class="spinner">
          <path
            d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
          />
        </svg>
      </div>
    </div>
  </form>
</template>

<script>
import axios from "axios";

const STATUS_INITIAL = 0;
const STATUS_SAVING = 1;
const STATUS_SUCCESS = 2;
const STATUS_FAILED = 3;

export default {
  props: {
    show: {
      type: Boolean,
      default: true
    },
    isOverlay: {
      type: Boolean,
      default: false
    },
    isMultiple: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null
    };
  },
  mounted() {
    this.reset();
  },
  computed: {
    isInitial() {
      return this.currentStatus === STATUS_INITIAL;
    },
    isSaving() {
      return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
      return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
      return this.currentStatus === STATUS_FAILED;
    }
  },
  methods: {
    reset() {
      // reset form to initial state
      this.currentStatus = STATUS_INITIAL;
      this.uploadedFiles = [];
      this.uploadError = null;
    },
    save(formData) {
      // upload data to the server
      this.currentStatus = STATUS_SAVING;

      this.upload(formData)
        .then(index => {
          this.uploadedFiles = [].concat(index);
          this.currentStatus = STATUS_SUCCESS;
        })
        .catch(err => {
          this.uploadError = err.response;
          this.currentStatus = STATUS_FAILED;
        });
    },
    filesChange(fieldName, fileList) {
      // handle file changes
      const formData = new FormData();

      if (!fileList.length) return;

      // append the files to FormData
      Array.from(Array(fileList.length).keys()).map(index => {
        formData.append(index, fileList[index], fileList[index].name);
      });

      // save it
      this.save(formData);
    },
    upload(formData) {
      // Start loader
      const url = `${GME_DATA.ajax_url}?action=gme_ajax_image_upload&nonce=${GME_DATA.nonce}`;

      return axios.post(url, formData).then(({ data }) => {
        // Stop loader

        this.$root.images.push(...data.map(data => data));

        if (this.$root.images.length && !this.$root.currentImage.id) {
          this.$root.currentImage = this.$root.images[0];
        }

        this.$root.showUploader = false;
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.dropbox {
  position: relative;
  outline: 2px dashed #ffffff;
  outline-offset: -10px;
  background: #999999;
  color: white;
  min-height: 500px;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;

  &.is-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 999;
  }

  .dropbox__body {
    display: flex;
    flex-direction: column;
    align-items: center;
    svg {
      width: 40px;
      fill: #ffffff;
    }

    span {
      margin-top: 5px;
    }

    .cancel {
      z-index: 9999;
      padding: 5px 20px;
    }
  }
}
.loading-text {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
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
  display: block;
  width: 25px;
  height: 25px;
  margin-top: 10px;
  filter: opacity(0.5);
  z-index: 10;
  animation-name: spinner;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}

.input-file {
  opacity: 0; // invisible but it's there
  width: 100%;
  height: 100%;
  position: absolute;
  cursor: pointer;
}

.dropbox:hover {
  background: #8d8d8d;
}
</style>