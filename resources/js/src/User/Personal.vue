<template>
  <v-container>
    <h1 style="margin-left: 30px" class="text-end text-button">Personal Page
      <router-link to="">
        <v-btn class="ml-4" color="primary" dark @click.prevent="logout">
          <v-icon left>mdi-logout</v-icon>
          Logout
        </v-btn>
      </router-link>
    </h1>
  </v-container>

</template>

<script>
import api from "../../api.js";

export default {
  methods: {
     logout() {
      api.get('/api/auth/logout')
          .then(response => {
            localStorage.removeItem('access_token')
            this.$router.push({name: 'user.login'})
          })
    },

  }
};
</script>

