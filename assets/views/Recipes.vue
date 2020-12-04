<template>
  <div>
    <div id="container">
      <div>
        <h1>{{ filter ? "Asmeniniai Receptai" : "Receptai" }}</h1>
      </div>
      <b-button id="add-button" v-if="filter" v-b-modal.add-modal variant="primary">Pridėti receptą</b-button>
      <b-card-group deck>
        <recipe-card
            v-for="recipe in filteredRecipes"
            :key="recipe.id"
            :name="recipe.name"
            :image="recipe.image"
            :edit="filter"
            @open-show="showModal"
            @open-edit="editModal"
            @delete-recipe="deleteRecipe"
        />
      </b-card-group>
      <show-recipe @reset-show="show=false" :show="show" :id="id"></show-recipe>
      <edit-recipe @refresh-recipes="refreshRecipes" @show-error="showAlert" @reset-edit="edit=false" :show="edit" :id="id"></edit-recipe>
      <b-modal size="xl" id="add-modal" :hide-footer="true"><add-recipe @show-error="showAlert" @refresh-recipes="refreshRecipes"></add-recipe></b-modal>
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
      refreshRecipes (){
        this.$fetcher('recipes', 'GET').then((data) => {
          this.recipes = data
        })
      },
      showModal (id) {
        this.show = true
        this.edit = false
        this.$bvModal.show('show-modal')
        this.id = String(id)
      },
      editModal (id) {
        this.show = false
        this.edit = true
        this.$bvModal.show('edit-modal')
        this.id = String(id)
      },
      showAlert (text, type) {
        this.$emit('show-error', text, type)
      },
      deleteRecipe (id) {
        let index
        for (let i = 0; i < this.recipes.length ; i++){
          if (this.recipes[i].id === id) {
            index = i
            break
          }
        }
        if (index) {
          this.recipes.splice(index, 1)
        }
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
      this.refreshRecipes()
    }
  }
</script>

<style scoped>
 #add-button {
   margin-bottom: 20px;
 }
</style>