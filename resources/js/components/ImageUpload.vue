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
      </div>
      <div class="loading-text" v-if="isSaving && !isMultiple">
        <p>Uploading file...</p>
        <p>Don't close window. Large files may take a couple minutes.</p>
      </div>

      <div class="progress-bar">
        <div :style="[isSaving ? { 'transition': 'width ' + estimatedUploadTime + 'ms', 'width': '97%' } : { 'width': '0%' }]"></div>
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
      currentStatus: null,
      fileList: []
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
    },
    estimatedUploadTime() {
      let totalBytes = this.fileList.reduce((total, file) => {
        return total + file.size;
      }, 0);

      // Estimate upload time in milliseconds
      return Math.round((totalBytes / 300000) * 1000);
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

      this.fileList = Array.from(fileList);

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
  flex-direction: column;

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

.progress-bar {
    width: 70%;
    max-width: 250px;
    height: 14px;
    margin-top: 1rem;

  div {
    width: 0%;
    background-color: #ffffff;
    height: 100%;
  }
}
</style>