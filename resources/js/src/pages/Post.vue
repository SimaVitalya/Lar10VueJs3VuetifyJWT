<template>

  <div>
    <h1 class="text-blue text-center mb-5">Posts</h1>
    <v-text-field v-model="newPost.title" label="Title"></v-text-field>
    <v-textarea v-model="newPost.content" label="Content"></v-textarea>
    <v-btn class="mb-15" @click.prevent="storePost" size="small" color="success">Add post</v-btn>

    <div class="d-flex justify-content-between">
      <div class="d-flex justify-space-between mb-15">
        <div class="text-end ">
          <v-menu open-on-hover>
            <template v-slot:activator="{ props }">
              <v-btn  :loading="loading" color="primary" v-bind="props">
                {{ activeSortTitle }}
              </v-btn>
            </template>
            <v-list>
              <v-list-item v-for="(item, index) in sortItems"
                           :key="index"
                           @click="sort(item)"
                           :ripple="{ class: 'text-purple' }"
              >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </div>
      </div>
      <div >
        <div class="mx-auto text-center">
          <v-text-field
              v-model="searchQuery"
              clearable
              label="Search by title"
              @keydown.enter="fetchPosts(1)"
              :disabled="loading"
              class="no-bottom-border search-field"
          >
            <template #append>
              <v-btn :loading="loading" size="small" color="primary" @click="fetchPosts(1)">search</v-btn>
            </template>
          </v-text-field>

          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>

          <div v-if="!loading && searchQuery && !filteredPosts.length" class="text-error  mt-4">
            Nothing found.
          </div>
        </div>
      </div>
    </div>

    <v-card v-for="post in paginatedPosts()" :key="post.id" class="post-card">
      <v-card-title>
        <v-text-field v-model="post.title" :readonly="!post.editing" class="post-title"></v-text-field>
      </v-card-title>
      <v-card-text>
        <v-textarea v-model="post.content" :readonly="!post.editing" class="post-content"></v-textarea>
      </v-card-text>
      <v-card-actions>
        <v-btn @click.prevent="toggleEdit(post)" size="small" color="blue">{{ post.editing ? 'Save' : 'Edit' }}</v-btn>
        <v-btn @click.prevent="deletePost(post)" size="small" color="error">Delete</v-btn>
      </v-card-actions>
    </v-card>

    <v-pagination
        class="mt-10"
        :total-visible="6"
        color="error"
        v-model="page"
        :length="totalPages"
        v-if="totalPages > 1"

    ></v-pagination>


  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Posts",
  data() {
    return {
      loading: false,
      searchQuery: '',
      totalPages:0,
      newPost: {
        title: "",
        content: ""
      },
      posts: [],
      items: [],
      filteredPosts: [],
      sortItems: [
        {title: 'Стандартная сортировка'},
        {title: "Сортировать по имени от а до я"},
        {title: "Сортировать по имени от я до а"},
        {title: "Сортировать по дате >"},
        {title: "Сортировать по дате <"},
      ],
      activeSortTitle: 'Стандартная сортировка',
      order_by: 'title',
      sort_by: 'asc',
      perPage: 10,
        page: 1,
        current: 1,
        length: 10, //не 25 для удобства
    };
  },
  //тут по странному для сортировки потом переделать
  mounted() {
    this.activeSortTitle = 'Стандартная сортировка';
    this.order_by = 'date';
    this.sort_by = 'desc';
    this.fetchPosts();
  },

  methods: {
    //для того что бы работал поиск мы обращаемся к бэкэнду и правильно делаем поиск через бэк д
    async fetchPosts(page = 1) {
      this.loading = true;
      try {
        const response = await axios.post('/api/post', {
          'order_by': this.order_by,
          'sort_by': this.sort_by,
          'search_query': this.searchQuery,
          'page': page,
          'per_page': this.perPage,
        });
        console.log(response.data.posts);
        this.items = response.data.posts.data;
        this.filteredPosts = response.data.posts.data;
        this.totalPages = response.data.posts.last_page;

      } catch (error) {
        console.log(error);
      } finally {
        this.loading = false;
      }
    },
//sort через бэкэнд
    sort(item) {

      if (item.title === "Стандартная сортировка") {
        this.activeSortTitle = "Стандартная сортировка";
        this.order_by = "date";
        this.sort_by = "desc";
      } else if (item.title === "Сортировать по имени от я до а") {
        this.activeSortTitle = "Сортировать по имени от я до а";
        this.order_by = "title";
        this.sort_by = "desc";
      } else if (item.title === "Сортировать по имени от а до я") {
        this.activeSortTitle = "Сортировать по имени от а до я";
        this.order_by = "title";
        this.sort_by = "asc";
      }
      else if (item.title === "Сортировать по дате >") {
        this.activeSortTitle = "Сортировать по дате >";
        this.order_by = "date";
        this.sort_by = "desc";
      }
      else if (item.title === "Сортировать по дате <") {
        this.activeSortTitle = "Сортировать по дате >";
        this.order_by = "date";
        this.sort_by = "asc";
      }
      this.fetchPosts();
    },
    storePost() {
      axios
          .post("api/post/store", {
            title: this.newPost.title,
            content: this.newPost.content

          })
          .then((res) => {
            this.newPost.title = "";
            this.newPost.content = "";
            this.fetchPosts().then(() => {
              this.page = 1
            });//что бы при сосдание сразу переходить на нужную страницу так как у нас сортировка стоит по дате добавлении то тут параметр 1

                   });
    },
    toggleEdit(post) {
      if (post.editing) {
        // Save changes
        axios.put(`api/post/update/${post.id}`, {
          title: post.title,
          content: post.content
        }).then((res) => {
          console.log(res);
        });
      }
      post.editing = !post.editing;
    },
    //ниже прописаное правильное удаление без проблем с пагинацие и сортировкой
    deletePost(post) {
      const currentPage = this.page;
      axios.delete(`api/post/delete/${post.id}`).then((res) => {
        this.fetchPosts(currentPage).then(() => {
          if (this.filteredPosts.length === 0 && this.page > 1) {
            this.page--;
            this.fetchPosts(this.page);
          }
        });
        console.log(res);
      });
    },
    //пагинация через бэкенд
    paginatedPosts() {
      const start = (this.current - 1) * this.length;
      const end = start + this.length;
      return this.filteredPosts.slice(start, end);

    },
  },
  watch: {
    page(newPage, oldPage) {
      if (newPage !== oldPage) {
        this.fetchPosts(newPage);
      }
    }
  }
}
;
</script>

<style scoped>
.post-card {
  margin-bottom: 20px;
}

.post-title {
  font-size: 20px;
  font-weight: bold;
}

.post-content {
  min-height: 100px;
}
.no-bottom-border  {
  border-bottom: none;
}
.search-field {
  width: 600px;

}
</style>
