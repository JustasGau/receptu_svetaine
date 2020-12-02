<template>
  <div>
    <div id="container">
      <div>
        <h1>Receptai</h1>
      </div>
      <b-button v-if="filter" @click="addRecipe" variant="primary">Pridėti receptą</b-button>
      <b-card-group deck>
        <recipe-card
            v-for="recipe in filteredRecipes"
            :key="recipe.id"
            :name="recipe.name"
            :picture="recipe.image"
            :edit="filter"
            @open-show="showModal"
            @open-edit="editModal"
            @delete-recipe="deleteRecipe"
        />

      </b-card-group>
      <show-recipe :show="this.show" :id="id"></show-recipe>
      <edit-recipe :show="this.edit" :id="id"></edit-recipe>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        token: '',
        filter: this.$attrs.filter,
        recipes: [],
        show: false,
        edit: false,
        id: ''
      }
    },
    watch: {
      '$route.path' () {
        this.filter = this.$attrs.filter
      }
    },
    methods: {
      addRecipe (){

      },
      showModal (id) {
        this.show = true
        this.edit = false
        this.id = String(id)
      },
      editModal (id) {
        this.edit = true
        this.show = false
        this.id = String(id)
      },
      deleteRecipe (id) {
        let index
        for (let i = 0; i < this.recipes.length ; i++){
          if (String(this.recipes[i].id) === id) {
            index = i
            break
          }
        }
        this.recipes.splice(index, 1)
      }
    },
    computed: {
      filteredRecipes () {
        if (this.filter === true){
          return this.recipes.filter(item => {
            if (item.user.name === this.$store.state.user) {
              return item
            }
          })
        } else {
          return this.recipes
        }
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