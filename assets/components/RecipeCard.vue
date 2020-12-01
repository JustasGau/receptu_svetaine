<template>
  <div>
    <b-card
        :title="this.name"
        img-src="https://picsum.photos/600/300/?image=25"
        img-alt="Image"
        img-top
        tag="article"
        style="max-width: 20rem;"
        class="mb-2"
    >
      <b-button href="#" @click="showRecipe" variant="primary">Peržiūrėti</b-button>
      <b-button v-if="edit" href="#" variant="warning">Redaguoti</b-button>
      <b-button v-if="remove" href="#" variant="danger">Trinti</b-button>
    </b-card>
  </div>
</template>

<script>
  export default {
    props: ['name', 'image', 'edit', 'remove'],
    methods : {
      showRecipe () {
        this.$emit('open-modal', true, false, this.$vnode.key)
        console.log(this.$vnode.key)
      }
    }
  }
</script>

<style scoped>

</style>