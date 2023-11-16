<template>
  <header class="header">
    <ul class="header__menu nav nav-pills">
      <li class="nav-item">
        <router-link
            aria-current="page"
            class="nav-link"
            to="/"
        >Главная
        </router-link>

      </li>
      <li class="nav-item">
        <router-link
            v-if="accessToken"
            aria-current="page"
            class="nav-link"
            to="/post"
        >Post
        </router-link>

      </li>
      <li class="nav-item">
        <router-link
            :key="$route.fullPath"
            v-if="!accessToken"
            class="nav-link"
            to="/users/login"
        >Login
        </router-link>
      </li>
      <li class="nav-item">
        <router-link
            :key="$route.fullPath"
            v-if="!accessToken"
            class="nav-link"
            to="/users/registration"
        >Registration
        </router-link>
      </li>
      <li class="nav-item">
        <router-link
            v-if="accessToken"
            :key="$route.fullPath"
            class="nav-link"
            to="/users/personal"
        >Personal
        </router-link>
      </li>

    </ul>
  </header>
</template>

<script>
// :key="$route.fullPath" используется для обновления компонента router-link, когда путь  изменяется
// работает с updated()
export default {
  data() {
    return {
      accessToken: null
    }
  },
  mounted() {
    this.getAccessToken()
  },
  updated() {
    this.getAccessToken()
  },
  methods: {
    getAccessToken() {
      this.accessToken = localStorage.getItem('access_token')

    }
  }
}
</script>

<style scoped>
.header {
  width: 992px;
  margin: 0 auto;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
