<template>
  <div>
    <b-modal id="show-modal" size="xl" :title="recipe.name" :hide-footer="true">
      <b-img v-if="recipe.image" :src="recipe.image" fluid alt="Responsive image"></b-img>
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
          <b-col style="white-space: break-spaces;">{{recipe.text}}</b-col>
        </b-row>
        <b-row>
          <b-col>
            <h3> Komentarai</h3>
            <comment-recipe v-for="com in pageComments"
                            :key="com.id"
                            :text="com.text"
                            :user="com.user.name"
                            :date="com.date.date"
                            @delete-comment="deleteCommentLocal"
                            @update-comment="updateComment"
            />
            <b-pagination
                v-if="comments.length > perPage"
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                aria-controls="my-table"
            ></b-pagination>
            <div v-if="comments.length === 0">Komentarų nėra</div>
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
        recipe: [],
        comments: [],
        ingredients: [],
        comment: '',
        counter: 0,
        rows: 0,
        currentPage: 1,
        perPage: 2,
        sendingComment: false,
        inFields: [
          {
            key: 'amount',
            label: 'Kiekis'
          },
          {
            key: 'name',
            label: ' Ingredientas'
          },
          {
            key: 'calories',
            label: 'Kalorijos',
            formatter: value => {
              if (!value) return 0
              else return value
            }
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
      },
      deleteCommentLocal (id) {
        let index;
        for (let i = 0; i < this.comments.length ; i++){
          if (String(this.comments[i].id) == id) {
            index = i
            break
          }
        }
        this.comments.splice(index, 1)
      },
      updateComment (id, newText) {
        for (let i = 0; i < this.comments.length ; i++){
          if (String(this.comments[i].id) == id) {
            this.comments[i].text = newText
            break
          }
        }
      }
    },
    watch: {
      show () {
        this.counter++
        if (this.counter == 2) {
          this.counter = 0
          this.recipe = []
          this.comments = []
          this.ingredients = []
          this.$fetcher("recipes/" + this.id, 'GET').then((data) => {
            this.recipe = data
          })
          this.$fetcher("recipes/" + this.id + '/comments', 'GET').then((data) => {
            this.comments = data
            this.rows = data.length
          })
          this.$fetcher("recipes/" + this.id + '/ingredients', 'GET').then((data) => {
            this.ingredients = data
            this.isBusy = false
          })
        }
        this.$emit("reset-show")
      }
    },
    created() {

    },
    computed : {
      pageComments(){
        return this.comments.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage)
      }
    },
    props: ['id', 'show']
  }
</script>
