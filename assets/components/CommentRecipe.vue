<template>
  <div>
    <b-container>
      <b-row>
        <b-col cols="2" >
          <img src="/images/user.png" height="40" width="40"/>
        </b-col>
        <b-col>
          <b-row>
            {{ user }} {{ date | fixDate}}
          </b-row>
          <b-row v-if="!edit">
            {{text}}
          </b-row>
          <b-row v-else>
            <b-form-input v-model="editText"> </b-form-input>
          </b-row>
        </b-col>
        <b-col v-if="logUser === user && !edit">
          <b-button @click="edit = !edit" variant="warning">Redaguoti</b-button>
          <b-button @click="deleteComment" variant="danger">X</b-button>
        </b-col>
        <b-col v-if="edit">
          <b-button @click="saveEdit" variant="success">Saugoti</b-button>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        logUser: this.$store.state.user,
        edit: false,
        editText: this.text
      }
    },
    props:['text', 'user', 'date'],
    filters: {
      fixDate (value) {
        return value.substring(0, 19)
      }
    },
    methods: {
      deleteComment () {
        this.$fetcher('comments/' + this.$vnode.key, 'DELETE').then(() => {
          this.$emit('delete-comment', this.$vnode.key)
        })
      },
      saveEdit () {
        this.edit = !this.edit
        const form = {
          text: this.editText
        }
        this.$fetcher('comments/' + this.$vnode.key, 'PATCH', form).then(() => {
          this.$emit('update-comment', this.$vnode.key, this.editText)
        })
      }
    }
  }
</script>

<style scoped>

</style>