<template>
  <h1>Home</h1>
</template>

<script>
import axios from "axios";

export default {
  name: "Get",
  mounted() {
    this.getData()
  },
  methods: {

    getData() {
      axios.get('/api/get')
          .then(res => {
            console.log(res)
          })
    }
  }
}
</script>

<style lang="less" scoped></style>
