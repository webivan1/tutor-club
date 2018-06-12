<template>
    <form @submit.default="send">
        <Editor :language="lang" v-model="message"></Editor>
        <button :disabled="loader" class="btn btn-primary">{{ t.Send }}</button>
    </form>
</template>

<script>
  import Editor from './EditorComponent.vue'

  export default {
    props: ['t', 'lang', 'room', 'host'],
    components: {
      Editor
    },
    data() {
      return {
        message: '',
        loader: false,
      }
    },
    methods: {
      send() {
        if (this.loader === true) {
          return false;
        }

        this.loader = true;

        let messageData = {
          message: this.message
        };

        axios.post(`${this.host}/${this.lang}/classroom/${this.room}/message`, messageData)
          .then(response => {
            this.loader = false;
            this.message = '';
            this.$emit('send', response.data);
          })
          .catch(err => alert(err));
      }
    }
  }
</script>