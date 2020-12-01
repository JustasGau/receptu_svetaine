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
          <b-row>
            {{text}}
          </b-row>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
  export default {
    props:['text', 'user', 'date'],
    filters: {
      fixDate (value) {
        return value.substring(0, 19)
      }
    }
  }
</script>

<style scoped>

</style>