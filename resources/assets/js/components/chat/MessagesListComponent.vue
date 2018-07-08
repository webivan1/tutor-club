<template>
    <div class="flex-vertical">
        <div v-if="isActiveButtonCreateLesson">
            <classroom-register
                ref="registerClassroom"
                :to="users"
                :from="user"
                :tutor="tutor"
            ></classroom-register>
        </div>

        <div class="py-1 px-1 bg-grey-100">
            <a href="javascript:void(0)" @click="close()" class="btn btn-sm btn-block btn-link">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </div>
        <div class="px-1 py-1 text-center" v-if="loader">
            <div class="ld ld-ring ld-spin-fast fs-1 text-orange mx-auto"></div>
        </div>
        <div ref="mw" class="card-body flex-auto-height py-0" v-if="!loader">
            <div class="text-center pt-1 pb-1" v-if="nextPage">
                <div v-if="loaderMore">
                    <div class="ld ld-ring ld-spin-fast fs-1 text-orange mx-auto"></div>
                </div>
                <div v-else>
                    <button @click="nextPageLoad" class="btn btn-sm btn-info">load more</button>
                </div>
            </div>

            <div v-for="item in list.data">
                <message v-if="!item.classroom" :item="item"></message>
                <message-invite v-else :item="item"></message-invite>
            </div>
        </div>
        <div v-if="!loader">
            <form :class="{ loader: loaderSend }" class="mx-0 my-0" @submit.prevent="sendMessage">
                <div class="input-group">
                    <input v-model="messageField" required @keydown="Keydown" type="text" class="form-control" placeholder="Write message..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary">
                            <i class="fas fa-share-square"></i>
                        </button>
                    </div>
                    <div v-if="isActiveButtonCreateLesson" class="input-group-append">
                        <button @click="showModalRegisterClassroom" class="btn btn-danger" type="button">
                            <i class="far fa-calendar-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
  import Vue from 'vue'
  import MessageItem from './MessageItemComponent.vue'
  import MessageInviteLessonComponent from './MessageInviteLessonComponent.vue';

  export default {
    components: {
      'message-invite': MessageInviteLessonComponent,
      'message': MessageItem
    },
    props: ['list', 'loader', 'dialog', 'user'],
    data() {
      return {
        messageField: '',
        loaderSend: false,
        loaderMore: false,
        prependUrl: '',
        upScroll: false,
        nextPage: null,
        tutor: null,
        users: [],
        isActiveButtonCreateLesson: false
      }
    },
    watch: {
      'list': {
        handler: function (data) {
          this.loaderMore = false;

          if (this.upScroll === false) {
            setTimeout(_ => {
              this.scrollToBottom();
            });
          }

          this.nextPage = data.next_page_url;
        },
        deep: true
      },
      'loader': {
        handler: function (data) {
          if (data === false) {
            setTimeout(_ => {
              this.scrollToBottom();
            });
          }
        },
        deep: true
      },
      'tutor': {
        handler: function (data) {
          this.isActiveButtonCreateLesson = true;
        },
        deep: true
      }
    },
    created() {
      this.nextPage = this.list.next_page_url;
      this.showButtonRegisterLesson();
      this.allUsers();
    },
    mounted() {
      this.prependUrl = document.querySelector('body').getAttribute('data-url');
      this.registerEventScroll();
    },
    methods: {
      allUsers() {
        this.dialog.users.forEach(user => {
          if (parseInt(user.user_id) !== parseInt(this.user)) {
            this.users.push(parseInt(user.user_id));
          }
        });
      },

      showButtonRegisterLesson() {
        if (this.dialog.user.tutor) {
          this.tutor = this.dialog.user.tutor;
        } else {
          this.dialog.users.forEach(user => {
            if (parseInt(user.user_id) === parseInt(this.user) && user.tutor) {
              this.tutor = user.tutor;
            }
          });
        }
      },

      registerEventScroll() {
        if (this.$refs.mw) {
          this.$refs.mw.onscroll = e => {
            this.upScroll = true;

            if (this.$refs.mw.scrollTop === this.$refs.mw.scrollHeight + this.$refs.mw.clientHeight) {
              this.upScroll = false;
            }
          };
        }
      },

      showModalRegisterClassroom() {
        this.$refs.registerClassroom.showModal();
      },

      nextPageLoad() {
        this.loaderMore = true;

        if (this.nextPage) {
          this.$emit('next-page', this.nextPage);
        }
      },

      scrollToBottom() {
        console.log('DOWN', this.$refs.mw.scrollHeight, this.$refs.mw.clientHeight);

        if (this.$refs.mw && this.upScroll === false) {
          this.$refs.mw.scrollTop = this.$refs.mw.scrollHeight + this.$refs.mw.clientHeight;
        }
      },

      close() {
        this.$emit('close');
      },

      Keydown(e) {
        if (e.keyCode === 13 && e.shiftKey === true && this.messageField !== '') {
          e.preventDefault();
          this.sendMessage();
        }
      },

      sendMessage() {
        if (this.loaderSend === true) {
          return false;
        }

        this.loaderSend = true;

        axios.put(`${this.prependUrl}/chat/messages/${this.dialog.id}`, {
          message: this.messageField
        })
          .then(response => {
            this.$emit('new-message', response.data);
            this.messageField = '';
            this.loaderSend = false;
          })
          .catch(err => {
            this.loaderSend = false;
            console.log(err);
          });
      },
    }
  }
</script>