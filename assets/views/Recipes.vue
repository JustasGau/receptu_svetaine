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
      const token = this.$store.state.token
      const requestOptions = {
        method: "GET",
        headers: {'Content-Type': 'application/json',
                  'Authorization': 'Bearer ' + token},
        body: JSON.stringify(this.form)
      };
      fetch(this.$store.state.address + "recipes", requestOptions)
          .then((response) => {
            return response.json()
          }).then((data) => {
            if (!data.code) {
              this.recipes = data
            } else {
              this.$emit('show-error', data.message)
            }
          }
      )
    }
  }
</script>

<style scoped>

</style>