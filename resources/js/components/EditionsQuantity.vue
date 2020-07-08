<template>
  <div>
    <label
      for="editions_quantity"
      class="screen-reader-text"
    >Editions Prints Quantity</label>
    <input
      type="number"
      id="editions_quantity"
      step="1"
      min="1"
      max
      name="custom_quantity"
      :value="$root.editionsQuantity"
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