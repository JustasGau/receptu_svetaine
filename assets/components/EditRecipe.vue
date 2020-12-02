<template>
  <div>
    <b-modal id="edit-modal" size="xl" :hide-footer="true">
      <b-form-input id="name" v-model="recipe.name" placeholder="Pavadinimas" required/>
      <b-img src="https://picsum.photos/1024/400/?image=41" fluid alt="Responsive image"></b-img>
      <b-container style="margin-top: 20px">
        <b-row>
          <b-col>
            <b-table :items="ingredients" :fields="inFields"
                     :busy="isBusy"
                     borderless
                     outlined
                     small
            >
              <template #cell(amount)="row">
                <b-form-input type="number" v-model="row.item.amount"> </b-form-input>
              </template>
              <template #cell(name)="row">
                <b-form-input v-model="row.item.name"> </b-form-input>
              </template>
              <template #cell(calories)="row">
                <b-form-input type="number" v-model="row.item.calories"> </b-form-input>
              </template>
              <template #table-busy>
                <div class="text-center text-danger my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  <strong>Krauna...</strong>
                </div>
              </template>
            </b-table>
            <b-button @click="ingredients.push({})" variant="primary">+</b-button>
          </b-col>
          <b-col>
            <b-form-textarea rows="3" v-model="recipe.text" required> </b-form-textarea>
          </b-col>
        </b-row>
      </b-container>
      <b-button id="submitButton" @click="onSubmit" variant="primary">Atnaujinti</b-button>
    </b-modal>
  </div>
</template>

<script>
export default {
  data () {
    return {
      recipe: {},
      ingredients: [],
      comment: '',
      counter: 0,
      inFields: [
        {
          key: 'amount',
          label: 'Kiekis',
          formatter: value => {
            return value + ' g'
          }
        },
        {
          key: 'name',
          label: ' Ingredientas'
        },
        {
          key: 'calories',
          label: 'Kalorijos'
        }
      ],
      isBusy: true
    }
  },
  methods: {
    onSubmit () {
      const form = {
        name: this.recipe.name,
        text: this.recipe.text
      }
      this.$fetcher('recipes/' + this.recipe.id, 'PATCH', form).then(() => {
        for (let i = 0; i < this.ingredients.length; i++) {
          let ing = this.ingredients[i]
          if (ing.id) {
            const ingForm = {
              name: ing.name,
              calories: ing.calories,
              amount: ing.amount
            }
            this.$fetcher('ingredients/' + ing.id, 'PATCH', ingForm)
          } else {
            const ingForm = {
              name: ing.name,
              recipe: this.recipe.id,
              calories: ing.calories,
              amount: ing.amount
            }
            this.$fetcher('ingredients', 'POST', ingForm)
          }
        }
        this.$emit('show-error', 'Sėkmingai atnaujintas receptas', 'success')
        this.$bvModal.hide("edit-modal")
      })
    }
  },
  watch: {
    show () {
      this.counter++
      if (this.counter == 2) {
        this.counter = 0
        this.recipe = {}
        this.comments = []
        this.ingredients = []
        this.$fetcher("recipes/" + this.id, 'GET').then((data) => {
          this.recipe = data
        })
        this.$fetcher("recipes/" + this.id + '/ingredients', 'GET').then((data) => {
          this.ingredients = data
          this.isBusy = false
        })
      }
      this.$emit("reset-edit")
    }
  },
  created() {

  },
  props: ['id', 'show']
}
</script>

<style scoped>
 #submitButton {
   margin-top: 20px;
   margin-left: 90%;
 }
 #name {
   width: 50%;
   margin-bottom: 20px;
 }
</style>
