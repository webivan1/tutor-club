<template>
    <div class="notify">
        <div
            v-for="(dialog, key) in newDialogs"
            class="notify-block notify-dialog mb-2"
            @mouseout="mouseOut('dialog', dialog.id)"
            @mouseover="mouseOver('dialog', dialog.id)"
            @click="openDialog(dialog)"
        >
            Added new dialog!
        </div>

        <div
            v-for="(message, key) in newMessages"
            class="notify-block notify-message mb-2"
            @mouseout="mouseOut('messages', message.id)"
            @mouseover="mouseOver('messages', message.id)"
            @click="message.trigger()"
        >
            <b>From: <span class="text-info">{{ message.from }}</span></b>
            <div class="mt-1" v-html="message.text"></div>
        </div>
    </div>
</template>

<script>
    export default {
      props: ['user'],
      data() {
        return {
          newDialogs: [],
          newMessages: [],
          timer: {
            dialog: {},
            messages: {}
          }
        }
      },
      mounted() {
        console.log('INIT');

        Echo.channel(`add.dialog.${this.user}`)
          .on('dialog', e => this.newDialogEvent(e));

        ee.on('add.custom.notify', e => this.newMessageEvent(e));
      },
      methods: {
        newMessageEvent(data) {
          if (typeof data !== 'object') {
            throw new Error('Data not is object!');
          }

          if (!data.hasOwnProperty('id')) {
            throw new Error('Param `id` is required!');
          }

          this.newMessages.push(data);
          this.addTimer('messages', data.id, 5);
        },

        newDialogEvent(e) {
          this.filterData(e.data).then(data => {
            this.newDialogs.push(data);
            this.addTimer('dialog', data.id, 5);
            this.$emit('new-dialog', data);
          });
        },

        openDialog(dialog) {
          this.$emit('open-dialog', dialog);
        },

        removeElement(key, id) {
          let data = [];

          if (key === 'dialog') {
            data = this.newDialogs;
          } else if (key === 'messages') {
            data = this.newMessages;
          }

          if (data.length > 0) {
            data.map((item, key) => {
              if (item.id === id) {
                data.splice(key, 1);
              }
            });
          }
        },

        addTimer(name, id, sec) {
          this.timer[name] = this.timer[name] || {};
          this.timer[name][id] = setTimeout(_ => this.removeElement(name, id), sec * 1000);
        },

        removeTimer(key, id) {
          if (this.timer.hasOwnProperty(key) && this.timer[key].hasOwnProperty(id)) {
            clearTimeout(this.timer[key][id]);
            delete this.timer[key][id];
          }
        },

        mouseOut(key, id) {
          this.addTimer(key, id, 1);
        },

        mouseOver(key, id) {
          this.removeTimer(key, id);
        },

        filterData(data) {
          return new Promise((resolve, reject) => {
            if (!data.user) {
              data.users.map(item => {
                if (parseInt(item.user_id) !== parseInt(this.user)) {
                  data.user = item;
                  resolve(data);
                }
              });
            }
          });
        }

      }
    }
</script>