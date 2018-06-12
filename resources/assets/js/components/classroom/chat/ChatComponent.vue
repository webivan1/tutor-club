<template>
    <div>
        <Messages :user="user" :host="host" :room="room"></Messages>
    </div>
</template>

<script>
  import Form from './FormComponent.vue'
  import Messages from './MessagesComponent.vue'

  export default {
    props: ['t', 'room', 'host', 'user'],
    components: {
      Form,
      Messages
    },
    mounted() {

    },
    data() {
      return {}
    },
    methods: {}
  }
</script>