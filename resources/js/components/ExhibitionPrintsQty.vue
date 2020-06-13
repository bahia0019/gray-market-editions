<template>
  <div>
    <label
      for="exhibition_prints_qty"
      class="screen-reader-text"
    >Exhibition Prints quantity</label>
    <input
      type="number"
      id="exhibition_prints_qty"
      step="1"
      min="1"
      max
      name="custom_quantity"
      :value="$root.exhibitionQuantity"
      title="Qty"
      size="4"
      placeholder
      inputmode="numeric"
      class="input-text custom_qty text"
      disabled
    />
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.custom_qty {
  width: 4.235801032em;
  text-align: center;
}
</style>