<template>
  <div>
    <b-form-input id="name" v-model="recipe.name" placeholder="Pavadinimas" required/>
    <b-form-file
        v-model="file"
        required
        :state="Boolean(file)"
        placeholder="Pasirinkti failą čia..."
        drop-placeholder="Įmesti failą čia..."
        browse-text="Failai"
    ></b-form-file>
    <img style="max-height: 200px;" ref="display"/>
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
          </b-table>
          <b-button @click="ingredients.push({})" variant="primary">+</b-button>
        </b-col>
        <b-col>
          <b-form-textarea rows="3" v-model="recipe.text" required> </b-form-textarea>
        </b-col>
      </b-row>
    </b-container>
    <b-button id="submitButton" @click="onSubmit" variant="primary">Pridėti</b-button>
  </div>
</template>

<script>
export default {
  data () {
    return {
      file: null,
      recipe: {
        name: '',
        text: ''
      },
      ingredients: [],
      comment: '',
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
  watch: {
    file () {
      let scope = this.$refs
      let fr=new FileReader()
      fr.onload = function(e) { scope.display.src = this.result; }
      fr.readAsDataURL(this.file)
    }
  },
  methods: {

    onSubmit () {
      let pictureLink = ''
      this.$fetcher('recipes', 'POST', this.file, true).then((data) => {
        if (data.status === 201){
          pictureLink = data.link
          const form = {
            name: this.recipe.name,
            text: this.recipe.text,
            image: pictureLink
          }
          this.$fetcher('recipes', 'POST', form).then((data) => {
            for (let i = 0; i < this.ingredients.length; i++) {
              let ing = this.ingredients[i]
              const ingForm = {
                name: ing.name,
                recipe: data.id,
                calories: ing.calories,
                amount: ing.amount
              }
              this.$fetcher('ingredients', 'POST', ingForm)
            }
            this.$emit('show-error', 'Sėkmingai sukurtas receptas', 'success')
            this.$emit('refresh-recipes')
            this.$bvModal.hide("add-modal")
          })
        }
      })
    }
  },
  created() {

  }
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
