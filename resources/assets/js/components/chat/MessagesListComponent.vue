<template>
    <div class="flex-vertical">
        <div class="py-1 px-1 bg-grey-100">
            <a href="javascript:void(0)" @click="close()" class="btn btn-sm btn-block btn-link">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </div>
        <div class="px-1 py-1 text-center" v-if="loader">
            <div class="ld ld-ring ld-spin-fast fs-1 text-orange mx-auto"></div>
        </div>
        <div ref="mw" class="card-body flex-auto-height py-0" v-if="!loader">
            <div v-for="item in list.data">
                <message :item="item"></message>
            </div>
        </div>
        <div v-if="!loader">
            <form :class="{ loader: loaderSend }" class="mx-0 my-0" @submit.prevent="sendMessage">
                <div class="input-group">
                    <input v-model="messageField" required @keydown="Keydown" type="text" class="form-control" placeholder="Write message..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-share-square"></i>
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

  Vue.component('message', MessageItem);

  export default {
    props: ['list', 'loader', 'dialog'],
    data() {
      return {
        messageField: '',
        loaderSend: false,
        prependUrl: '',
        upScroll: false,
        nextPage: null,
      }
    },
    watch: {
      'list': {
        handler: function (data) {
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
    },
    created() {
      this.nextPage = this.list.next_page_url;
    },
    mounted() {
      this.prependUrl = document.querySelector('body').getAttribute('data-url');

      if (this.$refs.mw) {
        this.$refs.mw.onscroll = e => {
          this.upScroll = true;

          if (this.$refs.mw.scrollTop === 0) {
            this.nextPageLoad();
          }
        };
      }

      console.log(this.list);
    },
    methods: {
      nextPageLoad() {
        if (this.nextPage) {
          this.$emit('next-page', this.nextPage);
        }
      },

      scrollToBottom() {
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
      }
    }
  }
</script>