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

      <div class="text-subtitle-1 text-medium-emphasis">Account</div>

      <v-form @submit.prevent="login">
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

        <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
          Password

          <a
              class="text-caption text-decoration-none text-primary"
              href="#"
              rel="noopener noreferrer"
              target="_blank"
          >
            Forgot login password?</a>
        </div>

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
          Log In
        </v-btn>
        <div v-if="error" class="text-error text-center">{{ this.error }}</div>
      </v-form>

      <v-card-text class="text-center">
        <router-link
            class="text-primary text-decoration-none"
            to="/users/registration"
        >
          Sign up now
          <v-icon icon="mdi-chevron-right"></v-icon>
        </router-link>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data: () => ({
    loading: false,
    visible: false,
    email: '',
    password: '',
    error: '',
    emailRules: [
      v => !!v || 'Email is required',
      v => /.+@.+\..+/.test(v) || 'Email must be valid',
    ],
    passwordRules: [
      v => !!v || 'Password is required',
      v => (v && v.length >= 8) || 'Password must contain at least 8 characters',
    ],
  }),
  methods: {
    login() {
      this.loading = true
      axios.post('/api/auth/login', {email: this.email, password: this.password})
          .then(res => {
            // console.log(res.data.access_token);
            localStorage.setItem('access_token', res.data.access_token)
            this.$router.push({name: 'user.personal'})
          })
          .catch(error => {
            this.loading = false

            this.error = error.response.data.error
          })
    }

  }
}
</script>
