<template>
    <span>
        <span v-if="loader"></span>
        <span v-else>
            <button
                v-if="isDialog"
                @click="openDialog"
                v-b-tooltip.hover
                :title="message('buttonOpenDialog', 'Open dialog')"
                type="button"
                class="btn btn-raised btn-success"
            ><i class="fas fa-comment-alt"></i></button>

            <span v-else>
                <button
                    @click="showModal"
                    v-b-tooltip.hover
                    :title="message('writeToTutor', 'To write to the tutor')"
                    type="button"
                    class="btn btn-raised btn-success"
                ><i class="fas fa-comment-alt"></i></button>

                <b-modal ref="myModalRef" hide-footer :title="message('sendToMessage', 'Send a message')">
                    <form :class="{ loader: loaderForm }" v-if="!sendForm" @submit.prevent="submit">
                        <div class="form-group">
                            <label>{{ message('Message', 'Message') }}</label>
                            <textarea required v-model="messageField" class="form-control"></textarea>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-raised btn-info">
                                {{ message('Send', 'Send') }}
                            </button>
                        </div>
                    </form>
                    <div v-else class="alert" :class="{
                      'alert-danger': responseStatus === 'error',
                      'alert-success': responseStatus === 'success'
                    }">{{ responseMessage }}</div>
                </b-modal>
            </span>
        </span>
    </span>
</template>

<script>
  export default {
    props: ['user', 'title', 'dataJson'],
    data() {
      return {
        data: {},
        loader: true,
        loaderForm: false,
        isDialog: false,
        dialogId: null,
        prependUrl: null,
        messageField: '',

        sendForm: false,
        responseMessage: null,
        responseStatus: null,
      }
    },
    created() {
      this.prependUrl = document.querySelector('body').getAttribute('data-url');

      this.data = typeof this.dataJson === 'object'
        ? this.dataJson
        : JSON.parse(this.dataJson);

      this.exist(this.user);
    },
    mounted() {

    },
    methods: {
      exist(user) {
        axios.post(`${this.prependUrl}/chat/exist`, { user: user })
          .then(response => {
            this.isDialog = response.data.status === 'yes';
            this.dialogId = this.isDialog === true ? response.data.dialog : null;
            this.loader = false;
          })
          .catch(err => {});
      },

      openDialog() {
        ee.emit(`dialog.open`, { id: this.dialogId });
      },

      showModal() {
        this.$refs.myModalRef.show();
      },

      hideModal () {
        this.$refs.myModalRef.hide()
      },

      message(param, defaultMessage) {
        if (this.data.hasOwnProperty('messages') && this.data.messages.hasOwnProperty(param)) {
          return this.data.messages[param];
        }

        return defaultMessage;
      },

      submit() {
        if (this.loaderForm === true) {
          return false;
        }

        this.loaderForm = true;

        axios.post(`${this.prependUrl}/chat/dialog/create`, {
          message: this.messageField,
          title: this.title,
          to_id: this.user
        })
          .then(response => {
            this.sendForm = true;
            this.responseMessage = response.data.message;
            this.responseStatus = response.data.status;
            this.loaderForm = false;
          })
          .catch(err => console.log(err));
      }
    }
  }
</script>