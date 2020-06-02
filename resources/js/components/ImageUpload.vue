<template>
  <form enctype="multipart/form-data" novalidate v-if="isInitial || isSaving">
    <div class="dropbox">
      <input
        type="file"
        multiple
        :disabled="isSaving"
        @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"
        accept="image/*"
        class="input-file"
      />
      <div class="dropbox__body" v-if="isInitial">
        <svg viewBox="0 0 32 32">
          <path
            d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM20 20v6h-8v-6h-6l10-8 10 8h-6zM4.828 6l2-2h18.343l2 2h-22.343z"
          />
        </svg>
        <span>Upload Images</span>
      </div>
      <p v-if="isSaving">Uploading {{ fileCount }} files...</p>
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
  data() {
    return {
      uploadedFiles: [],
      uploadError: null,
      currentStatus: null
    };
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
      const url = `${GME_DATA.ajax_url}?action=gme_ajax_image_upload&nonce=${GME_DATA.nonce}`;

      return axios.post(url, formData).then(({ data }) => {
        this.$root.images = data.map(data => data);

        if (this.$root.images.length && !this.$root.currentImage.id) {
          this.$root.currentImage = this.$root.images[0];
        }
      });
    }
  },
  mounted() {
    this.reset();
  }
};
</script>

<style lang="scss" scoped>
.dropbox {
  outline: 2px dashed #ffffff;
  outline-offset: -10px;
  background: #999999;
  color: white;
  min-height: 500px;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;

  .dropbox__body {
    display: flex;
    flex-direction: column;
    align-items: center;

    svg {
      width: 50px;
      fill: #ffffff;
    }

    span {
      margin-top: 5px;
    }
  }
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