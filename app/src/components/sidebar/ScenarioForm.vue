<template>
  <div class="foss-fi-sidebar__inner-wrapper">
    <h2>Create a new scenario</h2>
    <form>
      <FormKit
        type="text"
        name="label"
        id="label"
        label="Label"
        help="Give your scenario a name (e.g. 'Conservative')"
        placeholder="My financial scenario"
      />
      <FormKit type="number" />
    </form>
  </div>
</template>

<style lang="css" scoped>
.foss-fi-sidebar__inner-wrapper {
  padding: 2rem;
}
</style>
