<template>
  <div class="py-4 mt-15 mb-15">
    <v-img
      class="mx-auto mb-10"
      max-width="228"
      src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
    ></v-img>

    <v-card
      class="mx-auto pa-12 pb-8"
      elevation="8"
      max-width="448"
      rounded="lg"
    >

      <div class="text-subtitle-1 text-medium-emphasis">Register</div>

      <v-form @submit.prevent="store">
        <v-text-field
          clearable
          color="success"
          density="compact"
          placeholder="Name"
          prepend-inner-icon="mdi-account-outline"
          variant="outlined"
          v-model="name"
          :rules="nameRules"
        ></v-text-field>

        <v-text-field
          clearable
          color="success"
          density="compact"
          placeholder="Email address"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
          v-model="email"
          :rules="emailRules"
        ></v-text-field>

        <v-text-field
          clearable
          color="success"
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          v-model="password"
          :rules="passwordRules"
          @click:append-inner="visible = !visible"
        ></v-text-field>

        <v-text-field
          clearable
          color="success"
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Confirm your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          v-model="confirmPassword"
          :rules="confirmPasswordRules"
          @click:append-inner="visible = !visible"
        ></v-text-field>

        <v-card
          class="mb-6"
          color="surface-variant"
          variant="tonal"
        >

        </v-card>

        <v-btn
          block
          class="mb-2"
          color="primary"
          size="large"
          variant="tonal"
          type="submit"
          :loading="loading"

        >
          Register
        </v-btn>
        <div v-if="error" class="text-error text-center">{{ error }}</div>

      </v-form>

      <v-card-text class="text-center">
        <router-link
          class="text-primary text-decoration-none"
          to="/users/login"
        >
          Already have an account? Log in
          <v-icon icon="mdi-chevron-right"></v-icon>
        </router-link>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      loading: false,
      visible: false,
      name: null,
      email: null,
      password: null,
      confirmPassword: null,
      error: '',
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length >= 3) || 'Name must contain at least 3 characters',
      ],
      emailRules: [
        v => !!v || 'Email is required',
        v => /.+@.+\..+/.test(v) || 'Email must be valid',
      ],
      passwordRules: [
        v => !!v || 'Password is required',
        v => (v && v.length >= 8) || 'Password must contain at least 8 characters',
      ],
      confirmPasswordRules: [
        v => !!v || 'Confirm password is required',
        v => v === this.password || 'Confirm password does not match password',
      ],
    }
  },

  methods: {
    store() {
      this.loading = true
      axios.post('/api/users', {
        email: this.email,
        name: this.name,
        password: this.password,
        confirmPassword: this.confirmPassword,
      }).then(res => {
        this.email = null
        this.name = null
        this.password = null
        this.confirmPassword = null
        localStorage.setItem('access_token',res.data.access_token)
        this.$router.push({name: 'user.personal'})
        // console.log(res)
      })
        .catch(error => {
          this.loading = false
          this.error = error.response.data.error
        })
    }
  },

  // mounted() {
  //   console.log(localStorage.getItem('access_token'));
  // },
};
</script>
