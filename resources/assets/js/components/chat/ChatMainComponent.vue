<template>
    <div>
        <audio ref="volume" src="/volume/chat.mp3"></audio>
        <audio ref="volumeSmall" src="/volume/chat-small.mp3"></audio>

        <notify
            :user="user"
            v-on:new-dialog="addDialog"
            v-on:open-dialog="checkDialog"
        ></notify>

        <div class="chat" :class="{ active: buttonToggle }">
            <div class="card flex-vertical">
                <div class="card-header" v-if="!loader" @click="toggle()">
                    <a class="float-right" href="javascript:void(0)">
                        <span v-if="buttonToggle === false">
                            <i class="fas fa-angle-up"></i>
                        </span>
                        <span v-else :title="data.messages.close">
                            <i class="fas fa-times"></i>
                        </span>
                    </a>
                    <i :class="{ 'animated infinite pulse text-indigo-700': newMessages.length > 0 }" :title="data.messages.heading" class="fas fa-comments"></i>
                </div>
                <div class="card-header" v-else>
                    <div class="ld ld-ring ld-spin-fast float-right fs-1"></div>
                </div>
                <div v-if="buttonToggle && isDialogs()" class="flex-auto-height">
                    <dialogs
                        v-on:check-dialog="checkDialog"
                        v-on:next-page-dialog="dialogNextPage"
                        v-on:search="dialogSearch"
                        :data="data"
                        :list="dialogs"
                        :nextPageLoader="nextPageLoader"
                        :searchLoader="searchLoader"
                    ></dialogs>
                </div>
                <div v-if="buttonToggle && isMessages() && dialog" class="flex-auto-height">
                    <messages
                        ref="messages"
                        :data="data"
                        :list="messages"
                        :loader="loaderMessage"
                        :dialog="dialog"
                        :user="user"
                        v-on:close="closeMessage"
                        v-on:new-message="addMessage"
                        v-on:next-page="nextPageMessages"
                    ></messages>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Vue from 'vue'
  import DialogsList from './DialogsListComponent.vue'
  import MessagesList from './MessagesListComponent.vue'
  import Notify from './NotifyComponent.vue'

  Vue.component('dialogs', DialogsList);
  Vue.component('messages', MessagesList);
  Vue.component('notify', Notify);

  // Список всех дилогов пользователя
  const SHOW_DIALOGS = 'dialogs';

  // Список сообщений конкретного диалога
  const SHOW_MESSAGES = 'messages';

  export default {
    props: ['user', 'dataJson'],
    data() {
      return {
        data: {},
        newMessages: [],

        buttonToggle: false,
        show: SHOW_DIALOGS,

        loader: true,
        nextPageLoader: false,
        searchLoader: false,
        post: {},
        dialog: null,
        dialogs: {
          data: []
        },

        loaderMessage: false,
        messages: [],
      }
    },
    created() {
      this.data = typeof this.dataJson === 'string'
        ? JSON.parse(this.dataJson)
        : {};
    },
    mounted() {
      if (this.loader === true && this.show === SHOW_DIALOGS) {
        this.fetchDialogs(_ => {
          this.init();
        });
      }

      Echo.private(`send.message.${this.user}`)
        .on('message', message => this.handlerNewMessage(message.data));
    },
    watch: {
      dialogs(newValue) {

      }
    },
    methods: {
      handlerNewMessage(message) {
        // Когда у юзера открыт диалог
        if (this.isMessages() && parseInt(this.dialog.id) === parseInt(message.dialog_id)) {
          // Просто добавляем его в массив
          this.messages.data.push(message);
          this.$refs.volumeSmall.play();
        } else {
          this.addNoReadDialog(message.dialog_id);

          ee.emit('add.custom.notify', {
            id: message.id,
            text: message.message,
            from: message.user.name,
            trigger: _ => {
              this.checkDialog(message.dialog_id);
            }
          });
          this.$refs.volume.play();
        }
      },

      addNoReadDialog(dialogId) {
        let dialog = parseInt(dialogId);

        this.dialogs.data.forEach(item => {
          if (parseInt(item.id) === dialog) {
            item.message_no_read = 1;
          }
        });

        if (this.newMessages.indexOf(dialog) === -1) {
          this.newMessages.push(dialog);
        }
      },

      removeNoReadDialog(dialogId) {
        let dialog = parseInt(dialogId);

        this.dialogs.data.forEach(item => {
          if (parseInt(item.id) === dialog) {
            item.message_no_read = null;
          }
        });

        if (this.newMessages.indexOf(dialog) !== -1) {
          this.newMessages.splice(this.newMessages.indexOf(dialog), 1);
        }
      },

      /**
       * @return boolean
       */
      isDialogs() {
        return this.show === SHOW_DIALOGS;
      },

      /**
       * @return boolean
       */
      isMessages() {
        return this.show === SHOW_MESSAGES;
      },

      /**
       * Переключение в режим просмотра чата
       * Сохраяем результат в локальное хранилище
       *
       * @return void
       */
      toggle() {
        this.buttonToggle = !this.buttonToggle;
        localStorage.setItem('chat-toggle', this.buttonToggle ? 1 : 0);
      },

      /**
       * Запускается после запроса к диалогам
       *
       * @return void
       */
      init() {
        // Устанавливаем нажатие кнопки
        let button = localStorage.getItem('chat-toggle');
        this.buttonToggle = button === null ? this.buttonToggle : (
          parseInt(button) === 1 ? true : false
        );

        // Какое окно будет попазывать
        this.show = localStorage.getItem('chat-show') || this.show;

        if (this.show === SHOW_MESSAGES) {
          let dialog = localStorage.getItem('chat-dialog');

          try {
            this.dialog = dialog === null ? null : JSON.parse(dialog);
            this.fetchMessages(this.dialog.id);
          } catch (err) {
            this.dialog = null;
          }
        }
      },

      addDialog(dialog) {
        this.dialogs.data.unshift(dialog);
      },

      /**
       * Запрос к диалогам
       *
       * @return void
       */
      fetchDialogs(callback, url) {
        axios.post(url || `${this.data.prependUrl}/chat/list`, this.post)
          .then(response => {
            if (response.data.current_page !== 1 && this.dialogs.data.length > 0) {
              response.data.data = this.dialogs.data.concat(response.data.data);
            }

            this.dialogs = response.data;
            this.loader = false;
            typeof callback === 'function' ? callback() : null;

            this.dialogs.data.forEach(item => {
              if (item.message_no_read) {
                this.addNoReadDialog(item.id);
              }
            });

            ee.on('dialog.open', data => {
              this.buttonToggle === false ? this.toggle() : null;

              // loader
              if (this.loaderMessage === true) {
                return false;
              }

              if (this.dialog && parseInt(this.dialog.id) === parseInt(data.id)) {
                return false;
              }

              this.dialogs.data.map(item => {
                if (parseInt(item.id) === parseInt(data.id)) {
                  this.checkDialog(item);
                }
              });
            });
          })
          .catch(err => console.error(err));
      },

      /**
       * Пагинация
       */
      dialogNextPage(url) {
        if (this.nextPageLoader === true) {
          return false;
        }

        this.nextPageLoader = true;

        this.fetchDialogs(_ => {
          this.nextPageLoader = false;
        }, url);
      },

      /**
       * Поиск диалога
       */
      dialogSearch(search) {
        if (this.searchLoader === true) {
          return false;
        }

        this.searchLoader = true;

        this.post.search = search;

        this.fetchDialogs(_ => {
          this.searchLoader = false;
        });
      },

      addMessage(message) {
        this.messages.data.push(message);
//        setTimeout(_ => {
//          this.$refs.messages.scrollToBottom();
//        });
      },

      nextPageMessages(pageUrl) {
        this.fetchMessages(this.dialog.id, pageUrl);
      },

      /**
       * Запрос к сообщениям
       *
       * @return void
       */
      fetchMessages(dialogId, pageUrl) {
        axios.get(pageUrl || `${this.data.prependUrl}/chat/messages/${dialogId}`)
          .then(response => {
            if (!pageUrl) {
              this.messages = response.data;
              this.loaderMessage = false;
            } else {
              let models = [...this.messages.data];
              this.messages = response.data;
              this.messages.data = [].concat(response.data.data, models);
            }
          })
          .catch(err => console.error(err));
      },

      checkDialog(dialog) {
        this.removeNoReadDialog(dialog.id);

        // режим просмотра сообщений
        this.show = SHOW_MESSAGES;
        this.loaderMessage = true;
        this.dialog = typeof dialog === 'object' ? dialog : { id: dialog };

        localStorage.setItem('chat-show', this.show);
        localStorage.setItem('chat-dialog', JSON.stringify(this.dialog));

        this.fetchMessages(this.dialog.id);
      },

      closeMessage() {
        if (this.isMessages()) {
          axios.get(`${this.data.prependUrl}/chat/dialog/${this.dialog.id}/close`);

          // режим просмотра диалогов
          this.show = SHOW_DIALOGS;
          this.dialog = null;
          this.loaderMessage = false;

          localStorage.setItem('chat-show', this.show);
          localStorage.removeItem('chat-dialog');
        }
      }
    }
  }
</script>