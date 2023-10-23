<template>
  <v-container >
    <h1 class="text-center  text-primary" >Comment</h1>
      <v-text-field></v-text-field>
    <v-textarea>
    </v-textarea>
    <v-btn color="primary">test</v-btn>
    <tr v-for="comment in comments"></tr>
<!--    <td>{{comment.id}}</td>-->
<!--    <td>{{comment.name}}</td>-->
  </v-container>

</template>

<script >
import axios from "axios";

export default {
  name:'Comment',
  data(){
    return{
      comments:null
    }
  },
  mounted() {
    this.getComments()
  },
  methods:{
    getComments(){
      axios.get('api/comments')
      .then(res =>{
        console.log(res)
        // this.comments = res.data.data
      })
    }
  }
}
</script>

<style lang="less" scoped></style>
