<template>
    <form @submit.prevent="send" class="row mx-0 my-0">
        <div class="col px-0">
            <input type="text" v-model="message" class="form-control" />
        </div>
        <div class="col-auto px-0">
            <button :disabled="loader" class="btn-block btn-raised btn btn-primary">
                <i class="fas fa-share-square"></i>
            </button>
        </div>
    </form>
</template>

<script>
  export default {
    props: ['t', 'lang', 'room', 'host'],
    data() {
      return {
        message: '',
        loader: false,
        editor: null
      }
    },
    mounted() {
//      setTimeout(_ => {
//        $(_ => {
//          this.editor = $(this.$refs.message).summernote({
//            height: 200,
//            toolbar: [
//              ['style', ['bold', 'italic', 'underline', 'clear']],
//              ['para', ['ul', 'ol']],
//              ['insert', ['table', 'picture']],
//              ['color', ['color']],
//            ],
//            callbacks: {
//              onChange: (contents, $editable) => {
//                this.message = contents;
//              },
//              onKeyup: (e) => {
//                if (e.keyCode === 13 && e.shiftKey) {
//                  e.preventDefault();
//                  return this.send();
//                }
//              }
//            }
//          });
//        });
//      });
    },
    methods: {
      send() {
        if (this.loader === true || this.message === '') {
          return false;
        }

        this.loader = true;

        let messageData = {
          message: this.message
        };

        axios.post(`${this.host}/classroom/${this.room.id}/message`, messageData)
          .then(response => {
            this.loader = false;
            this.message = '';
            //this.editor.summernote('reset');
            this.$emit('send', JSON.stringify(response.data));
          })
          .catch(err => alert(err));

        return false;
      }
    }
  }
</script>