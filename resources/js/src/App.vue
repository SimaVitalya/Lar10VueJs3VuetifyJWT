<template>
  <v-app>
    <v-btn @click="toggleTheme">Переключить тему</v-btn>
    <PrimaryHeader />
    <main class="main">
      <router-view />
    </main>
  </v-app>
</template>

<script setup>
import { useTheme } from 'vuetify'
import PrimaryHeader from './components/PrimaryHeader.vue'

const theme = useTheme()
function toggleTheme () {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}
</script>

<style lang="less" scoped>
.main {
  width: 992px;
  margin: 0 auto;
  padding: 40px;
}
</style>
