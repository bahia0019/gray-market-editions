<script>
export default {
  render() {
    if (this.$parent.$options._componentTag !== "image-stage") return;

    this.watchCldImageTrans();
  },
  methods: {
    watchCldImageTrans() {
      this.$watch("$parent.$refs.cldImage.transformations", {
        handler(newValue, oldValue) {
          // Grab canvas transformation obj
          const canvasTrans = newValue.filter(
            transformation => !transformation.overlay
          );

          // Convert to vanilla object to mutate overlay trans
          // TODO: Here would calcuate the size of the canvas relative
          // relative to the images original width and height. For now I'm
          // setting it to match image width/height to mimic 'fullFrame' for testing
          let canvasObj = { ...canvasTrans[0] };
          canvasObj.width = this.$parent.image.width;
          canvasObj.height = this.$parent.image.height;

          // Grab overlay transformation obj
          const overlayTrans = newValue.filter(
            transformation => transformation.overlay
          );

          // Convert overlayTrans to vanilla object to mutate
          const overlayObj = { ...overlayTrans[0] };
          // TODO: I've noticed that if this.$parent.image.width/this.$parent.image.height
          // are too large, Cloudinary may not render image.
          overlayObj.width = this.$parent.image.width;
          overlayObj.height = this.$parent.image.height;

          // Build new object containing both the canvasTrans, overlayTrans
          const newObj = [{ ...canvasObj }, { ...overlayObj }];

          // Create final image url using Cloudinary core JS lib
          // TODO: Should we be hard-coding "assets/16_20_bg_khaqct.jpg"?
          this.$parent.image.gme_final_url = this.$cloudinaryCore
            .imageTag("assets/16_20_bg_khaqct.jpg", { transformation: newObj })
            .getAttr("src");
        },
        deep: true,
        immediate: false
      });
    }
  }
};
</script>

<style>
</style>