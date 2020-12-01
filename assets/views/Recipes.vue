<template>
  <div>
    <div id="container">
      <div>
        <h1>Receptai</h1>
      </div>
      <b-card-group deck>
        <recipe-card v-for="recipe in recipes"
                     :key="recipe.id"
                     :name="recipe.name"
                     :picture="recipe.image"
                     @open-modal="selectModal"
        />

      </b-card-group>
      <show-recipe :show="this.show" :id="id"></show-recipe>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        token: '',
        recipes: '',
        show: false,
        edit: false,
        id: ''
      }
    },
    methods: {
      selectModal (show, edit, id) {
        this.show = show
        this.edit = edit
        this.id = String(id)
      }
    },
    created () {
      this.$fetcher('recipes', 'GET').then((data) => {
        this.recipes = data
      })
    }
  }
</script>

<style scoped>

</style>