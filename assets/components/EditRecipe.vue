<template>
  <div>
    <b-modal v-model="show" size="xl" :title="recipe.name">
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
              <template #table-busy>
                <div class="text-center text-danger my-2">
                  <b-spinner class="align-middle"></b-spinner>
                  <strong>Krauna...</strong>
                </div>
              </template>
            </b-table>

          </b-col>
          <b-col>{{ recipe.text }}</b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<script>
export default {
  data () {
    return {
      recipe: [],
      comments: [],
      ingredients: [],
      comment: '',
      sendingComment: false,
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

    }
  },
  watch: {
    id () {
      this.recipe = []
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
  },
  created() {

  },
  props: ['id', 'show']
}
</script>
