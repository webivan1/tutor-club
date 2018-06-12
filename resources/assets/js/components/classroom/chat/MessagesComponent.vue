<template>
    <div>
        <pre>messages</pre>
    </div>
</template>

<script>
  export default {
    props: ['user', 'host', 'room'],
    created() {
      this.lang = document.querySelector('html').getAttribute('lang');
    },
    mounted() {
      this.getList();
    },
    data() {
      return {
        messages: [],
        loader: true,
        afterLoader: false,
        lang: 'en',
        page: 1,
      }
    },
    methods: {
      getList() {
        axios.get(`${this.host}/${this.lang}/classroom/${this.room}/message`, {page: this.page})
          .then(response => {
            this.loader = false;
            this.messages = [].concat(response.data, this.messages);
          })
          .catch(err => console.error(err));
      },

      addMessage(message) {
        this.messages.push(message);
      }
    }
  }
</script>