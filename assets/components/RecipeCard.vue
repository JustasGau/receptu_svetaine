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
      <b-button
          @click="$emit('open-show', $vnode.key)"
          variant="primary">
        Peržiūrėti
      </b-button>
      <b-button
          v-if="edit"
          @click="$emit('open-edit', $vnode.key)"
          variant="warning">
        Redaguoti
      </b-button>
      <b-button v-if="edit" @click="deleteRecipe" variant="danger">Trinti</b-button>
    </b-card>
  </div>
</template>

<script>
  export default {
    props: ['name', 'image', 'edit'],
    methods : {
      deleteRecipe () {
        this.$fetcher('recipes/' + this.$vnode.key, 'DELETE').then(()=>{
          this.$emit('delete-recipe', this.$vnode.key)
        })
      }
    }
  }
</script>

<style scoped>

</style>