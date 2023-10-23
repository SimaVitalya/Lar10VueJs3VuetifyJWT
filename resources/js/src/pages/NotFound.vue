<template>
  <v-container style="margin-top: 150px" class="text-center align-center justify-center">
    <h1>Страница не найдена :(</h1>
    <p>Прейти на  <router-link  to="/comment"> главную?</router-link>  </p>

  </v-container>
</template>

<script>
export default {
  name: "notFound.vue"
}
</script>

<style scoped>
h1{
  font-size: 50px;
  margin-bottom: 20px;
}
p{
  font-size: 25px;
}
</style>
