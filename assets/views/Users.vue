<template>
  <div id="content">
    <b-table :items="users"
             :fields="inFields"
             :busy="isBusy"
             :per-page="perPage"
             :current-page="currentPage"
    >
      <template #cell(ban)="row">
        <b-button v-if="!row.item.status"
                  size="sm"
                  @click="changeStatus(row.item.id)"
                  :disabled="loading"
                  variant="danger"
                  class="mr-2">
          <b-spinner v-if="loading" small type="grow"></b-spinner>
          Blokuoti
        </b-button>
        <b-button v-else size="sm"
                  variant="success"
                  :disabled="loading"
                  @click="changeStatus(row.item.id)"
                  class="mr-2">
          <b-spinner v-if="loading" small type="grow"></b-spinner>
          Atblokuoti
        </b-button>
      </template>
      <template #table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Krauna...</strong>
        </div>
      </template>
    </b-table>
    <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
    ></b-pagination>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        users: [],
        isBusy:true,
        loading: false,
        rows: 0,
        currentPage: 1,
        perPage: 5,
        inFields: [
          {
            key: 'id',
            label: 'ID'
          },
          {
            key: 'name',
            label: ' Slapyvardis'
          },
          {
            key: 'email',
            label: 'EPaštas'
          },
          {
            key: 'status',
            label: 'Statusas',
            formatter: value => {
              if (value) return 'Blokuotas'
              else return '-'
            }
          },
          {
            key: 'ban',
            label: 'Administravimas'
          }
        ],
      }
    },
    methods: {
      changeStatus(id) {
        this.loading = true
        this.$fetcher('users/ban/' + id, 'POST').then(() =>{
          this.getUsers()
        })
      },
      getUsers () {
        this.$fetcher('users', 'GET').then((data) => {
          this.users = data
          this.rows = data.length
          this.isBusy = false
          this.loading = false
        })
      }
    },
    created () {
      this.getUsers()
    }
  }
</script>

<style scoped>
  #content {
    margin: 20px;
  }
</style>
