<template>
  <div class="d-flex justify-space-between mb-15">
    <h2 class="headline ">Комментарии: ({{ comments.length }})</h2>
    <div class="text-end ">
      <v-menu open-on-hover>
        <template v-slot:activator="{ props }">
          <v-btn color="primary" v-bind="props">
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
  <div>

    <div>
      <v-card v-for="comment in paginatedComments" :key="comment.id" class="comment-card mb-4">
        <v-card-text>
          <div class="comment-header">
            <v-avatar class="comment-avatar ma-1">
<!--              <img :src="comment.user.avatar" alt="Аватар" на случай если будет аватар в базе>-->
              <img :src="imageSrc" alt="Аватар">
            </v-avatar>
            <div class="comment-info">
              <div class="comment-user">{{ comment.user.name }}</div>
<!--              <div class="comment-user">= ID:{{ comment.id }}</div>-->
              <v-list-item-subtitle class=" ml-2 mb-2 text-white"><strong></strong> {{
                  new Date(comment.updated_at).toLocaleString('en-US', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: false
                  })
                }}
              </v-list-item-subtitle>
            </div>
          </div>
          <div class="comment-content mb-10">{{ comment.content }}</div>
          <ul class="comment-replies">
            <v-card v-for="reply in getRepliesByCommentId(comment.id)" :key="reply.id" class="comment-reply">
              <div class="comment-header">
                <v-avatar class="comment-avatar  ma-1">
<!--                  <img :src="comment.user.avatar" alt="Аватар">-->
                  <img :src="imageSrc" alt="Аватар">
                </v-avatar>
                <div class="comment-info">
                  <div class="comment-user">{{ reply.user.name }}</div>
<!--                  <div class="comment-user">= ID:{{ reply.id }}</div>-->
                  <v-list-item-subtitle class=" ml-2 mb-2 text-white"><strong></strong> {{
                      new Date(reply.updated_at).toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: 'numeric',
                        minute: 'numeric',
                        hour12: false
                      })
                    }}
                  </v-list-item-subtitle>
                </div>
              </div>
              <div class="comment-content mb-10">{{ reply.content }}</div>
            </v-card>
          </ul>
          <div class="text-right">
            <v-btn class="text-center" variant="text" size="x-small" color="blue" @click="toggleReplyForm(comment.id)">
              Ответить
            </v-btn>

            <form @submit.prevent="createReply(comment)" class="reply-form" v-if="comment.showReplyForm">
              <v-textarea v-model="replyContent.content" label="Ответить на комментарий"></v-textarea>
              <v-btn size="small" type="submit" color="success">Отправить комментарий</v-btn>
            </form>
          </div>
        </v-card-text>
      </v-card>
    </div>
    <form @submit.prevent="createComment" class="comment-form">
      <v-textarea v-model="newComment.content" label="Основной комментарий"></v-textarea>
      <v-btn type="submit" color="primary">Добавить комментарий</v-btn>
    </form>
  </div>
  <v-pagination
      class="mt-10"
      v-if="comments.length > 0"
      :total-visible="6"
      color="error"
      v-model="page.current"
      :length="totalPages"
  ></v-pagination>
</template>

<script>
import axios from 'axios';
import login from "@/User/Login.vue";

export default {
  data() {
    return {
      imageSrc: '1.png',
      comments: [],
      newComment: {
        content: '',
      },
      newReply: {},
      replys: [],
      showReplyForm: false,
      replyContent: {
        content: '',
      },
      commentId: '',
      sortItems: [
        {title: 'Стандартная сортировка'},
        {title: 'сортировать по имени'},
        {title: 'сортировать по дате >'},
        {title: 'сортировать по дате <'},
      ],
      activeSortTitle: 'Сортировать',
      selectedSortItem: null,
      page: {
        current: 1,
        length: 5, //не 25 для удобства
      },
    };
  },
  mounted() {
    this.fetchComments();
    this.sortComments();
    this.fetchReply();
  },
  computed: {
    paginatedComments() {
      const start = (this.page.current - 1) * this.page.length;
      const end = start + this.page.length;
      return this.comments.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.comments.length / this.page.length);
    },
  },
  methods: {
    fetchComments() {
      axios.get('/api/comments').then((response) => {
        // console.log(response.data);
        this.comments = response.data;
      });
    },
    sort(item) {
      this.selectedSortItem = item;
      this.sortComments();
    },
    sortComments() {
      if (this.selectedSortItem === null) {
        return;
      }
      const {title} = this.selectedSortItem;
      if (title === 'Стандартная сортировка') {
        this.activeSortTitle = "Стандартная сортировка";
        this.comments.sort((a, b) => a.id - b.id);
      }
      if (title === 'сортировать по имени') {
        this.activeSortTitle = "Сортировать по имени";
        this.comments.sort((a, b) => a.user.name.localeCompare(b.user.name));
      }
      if (title === 'сортировать по дате >') {
        this.activeSortTitle = "Сортировать по дате >";
        this.comments.sort((a, b) => {
          const dateA = new Date(a.updated_at);
          const dateB = new Date(b.updated_at);
          return dateA - dateB; // Сортировка по возрастанию даты (от старых к новым)
        });
      }
      if (title === 'сортировать по дате <') {
        this.activeSortTitle = "Сортировать по дате <";
        this.comments.sort((a, b) => {
          const dateA = new Date(a.updated_at);
          const dateB = new Date(b.updated_at);
          return dateB - dateA; // Сортировка по убыванию даты (от новых к старым)
        });
      }
    },
    toggleReplyForm(commentId) {
      this.comments.forEach(comment => {
        if (comment.id === commentId) {
          comment.showReplyForm = !comment.showReplyForm;
        } else {
          comment.showReplyForm = false;
        }
      });
      this.commentId = commentId;
      console.log(this.commentId);
    },
    getRepliesByCommentId(commentId) {
      return this.replys.filter(reply => reply.comment_id === commentId);

    },
    fetchReply() {
      axios.get('/api/reply').then((response) => {
        // console.log(response.data);
        this.replys = response.data;
      });
    },
    createComment() {
      const token = localStorage.getItem('access_token');
      const headers = {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      };

      axios.post('/api/comments', this.newComment, {headers})
          .then((response) => {
            this.comments.push(response.data);
            this.newComment.content = '';
            this.page.current = this.totalPages;

          })
          .catch((error) => {
            console.error(error);
          });
    },

    createReply() {
      const token = localStorage.getItem('access_token');
      const headers = {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      };
      const requestData = {
        content: this.replyContent.content,
        comment_id: this.commentId

      };
      axios.post(`api/comments/${this.commentId}/replies`, requestData, {headers})
          .then((response) => {
            this.replys.push(response.data);
            this.replyContent.content = '';
            this.fetchReply(); // Получение обновленного списка ответов
            this.page.current = this.totalPages;

          })
          .catch((error) => {
            console.error(error);
          });
    }
  }
}
;
</script>


<style>
.headline {
  font-size: 24px;
  margin-bottom: 20px;
}

.comment-avatar {
  width: 40px;
  height: 40px;
  margin-right: 10px;
}

.comment-header {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  background: #423c3c;


}

.comment-info {
  display: flex;
  justify-content: space-between;

}

.comment-date {
  font-size: 12px;
  color: gray;
  margin-bottom: 5px;
}

.comment-user {
  font-weight: bold;
  font-size: 18px;
}

.comment-content {
  font-size: 16px;
  margin-bottom: 10px;
}

.comment-replies {
  list-style: none;
  margin-top: 10px;
  padding-left: 20px;
}

.comment-reply {
  font-size: 14px;
  margin-bottom: 5px;
}

.comment-form {
  margin-top: 20px;
}
</style>
