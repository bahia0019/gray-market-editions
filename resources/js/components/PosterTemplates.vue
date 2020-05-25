<template>
  <div class="wrapper">
    <p>Don't have a poster design? Use one of our Photoshop templates:</p>
    <div class="poster-grid">
      <div class="poster-grid-item">
        <img
          src="https://res.cloudinary.com/flaunt-your-site/image/upload/c_fill,h_300,w_400/v1/Utah/_DSC1086_vovwor.jpg"
          alt="poster option"
        />
        <a
          href="http://graymarketeditions.test/wp-content/uploads/2020/05/3032211.psd"
          download
        >Download</a>
      </div>
      <div class="poster-grid-item">
        <img
          src="https://res.cloudinary.com/flaunt-your-site/image/upload/c_fill,h_300,w_400/v1/Utah/_DSC1086_vovwor.jpg"
          alt="poster option"
        />
        <a
          href="http://graymarketeditions.test/wp-content/uploads/2020/05/3032211.psd"
          download
        >Download</a>
      </div>
      <div class="poster-grid-item">
        <img
          src="https://res.cloudinary.com/flaunt-your-site/image/upload/c_fill,h_300,w_400/v1/Utah/_DSC1086_vovwor.jpg"
          alt="poster option"
        />
        <a
          href="http://graymarketeditions.test/wp-content/uploads/2020/05/3032211.psd"
          download
        >Download</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.wrapper {
  margin-top: 2rem;

  p {
    margin-bottom: 1rem;
  }

  .poster-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 2.5rem;

    .poster-grid-item {
      display: flex;
      flex-direction: column;
      align-items: center;

      a {
        margin-top: 0.25rem;
      }
    }
  }
}
</style>