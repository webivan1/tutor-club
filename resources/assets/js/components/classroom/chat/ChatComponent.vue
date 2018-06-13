<template>
    <div>
        <Messages
            ref="message"
            :t="t"
            :user="user"
            :host="host"
            :room="room"
            :lang="lang"
        ></Messages>

        <FormChat
            ref="form"
            :t="t"
            :host="host"
            :room="room"
            :lang="lang"
            v-on:send="onMessage"
        ></FormChat>
    </div>
</template>

<script>
  import FormChat from './FormComponent.vue'
  import Messages from './MessagesComponent.vue'

  export default {
    props: ['t', 'room', 'host', 'user', 'lang'],
    components: {
      FormChat,
      Messages
    },
    methods: {
      getMessage() {
        return this.$refs.message;
      },

      getForm() {
        return this.$refs.form;
      },

      onMessage(message) {
        this.getMessage().addMessage(JSON.parse(message));
        this.$emit('send', message);
      }
    }
  }
</script>