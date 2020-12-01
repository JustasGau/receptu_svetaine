<template>
  <div>
    <b-modal v-model="show" size="xl" :title="recipe.name">
      <b-img src="https://picsum.photos/1024/400/?image=41" fluid alt="Responsive image"></b-img>
      <b-container>
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
        <b-row>
          <b-col>
            <h3> Komentarai</h3>
            <comment-recipe v-for="com in comments"
                            :key="com.id"
                            :text="com.text"
                            :user="com.user.name"
                            :date="com.date.date"
            />

          </b-col>
        </b-row>
      </b-container>
      <b-form v-if="$store.state.user" @submit.stop.prevent="onSubmit">
        <b-form-group id="input-group-1" label-for="input-2">
          <b-form-input
              id="input-2"
              v-model="comment"
              placeholder="Komentaras...."
          ></b-form-input>
        </b-form-group>
        <b-button type="submit" variant="primary" :disabled="sendingComment">
          <b-spinner v-if="sendingComment" small type="grow"></b-spinner>
          Komentuoti
        </b-button>
      </b-form>
    </b-modal>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        recipe: {},
        comments: {},
        ingredients: {},
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
        this.sendingComment = true
        let form = {
          text: this.comment,
          recipe: this.id
        }
        this.$fetcher('comments','POST',form).then(() => {
          this.$fetcher("recipes/" + this.id + '/comments', 'GET').then((data) => {
            this.comments = data
            this.sendingComment = false
          })
        })
      }
    },
    watch: {
      id () {
        this.recipe = {}
        this.comments = {}
        this.ingredients = {}
        this.$fetcher("recipes/" + this.id, 'GET').then((data) => {
          this.recipe = data
          this.$fetcher("recipes/" + this.id + '/comments', 'GET').then((data) => {
            this.comments = data
          })
          this.$fetcher("recipes/" + this.id + '/ingredients', 'GET').then((data) => {
            this.ingredients = data
            this.isBusy = false
          })
        })
      }
    },
    created() {

    },
    props: ['id', 'show']
  }
</script>
