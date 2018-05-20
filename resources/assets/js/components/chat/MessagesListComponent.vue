<template>
    <div>
        <!--<div class="card-header">-->
            <!--<a href="javascript:void(0)" @click="close()">Prev to dialogs</a>-->
        <!--</div>-->
        <div class="card-header px-1 py-1 text-center" v-if="loader">
            <div class="ld ld-ring ld-spin-fast fs-1 text-info mx-auto"></div>
        </div>
        <div class="card-body" v-if="!loader">
            <div v-for="item in list.data">
                <message :item="item"></message>
            </div>
        </div>
        <div class="card-footer">
            <form :class="{ loader: loaderSend }" class="row mx-0 my-0" @submit.prevent="sendMessage">
                <div class="col px-0">
                    <textarea required v-model="messageField" class="form-control chat-area-field"></textarea>
                </div>
                <div class="col-auto px-0 pl-1">
                    <button class="btn btn-primary">Send</button>
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
      }
    },
    mounted() {
      this.prependUrl = document.querySelector('body').getAttribute('data-url');
    },
    methods: {
      close() {
        this.$emit('close');
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