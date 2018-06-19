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
  import SignalHub from 'signalhub'
  import swarm from 'webrtc-swarm'

  import FormChat from './FormComponent.vue'
  import Messages from './MessagesComponent.vue'

  export default {
    props: ['t', 'room', 'host', 'user', 'lang', 'localStream'],
    components: {
      FormChat,
      Messages
    },
    created() {
      this.channel = `room.${this.room.id}`;
      this.createServer();
    },
    mounted() {
      this.swarm = swarm(this.server, {
        stream: this.localStream
      });

      this.swarm.on('peer', (peer, id) => {
        peer.on('stream', stream => {
          console.log('OK STREAM');
        });

        peer.on('data', message => {
          console.log(JSON.parse(message.toString()));
          message = JSON.parse(message.toString());

          switch (message.type) {
            case 'message':
              this.addMessage(message.data);
              break;
          }
        });

        this.total = this.swarm.peers.length + 1;
      });

      this.swarm.on('disconnect', (peer, id) => {
        this.total = this.swarm.peers.length + 1;
      });
    },
    watch: {
      total: {
        handler: function (value) {
          this.changeTotalUsers(value);
        }
      }
    },
    data() {
      return {
        server: null,
        channel: null,
        swarm: null,
        total: 1
      }
    },
    methods: {
      changeTotalUsers(value) {
        let count = value;
        let element = document.getElementById('total-users');
        if (element) {
          element.innerText = count;
        }
      },

      createServer() {
        this.server = SignalHub(this.channel, [
          `${this.host || 'http://localhost'}:6004`
        ]);
      },

      getMessage() {
        return this.$refs.message;
      },

      getForm() {
        return this.$refs.form;
      },

      addMessage(message) {
        message = typeof message === 'string' ? JSON.parse(message) : message;
        this.getMessage().addMessage(message);
      },

      sendMessage(message) {
        this.swarm.peers.forEach(peer => {
          peer.send(JSON.stringify({
            type: 'message',
            data: JSON.parse(message)
          }));
        });
      },

      onMessage(message) {
        this.addMessage(message);
        // send all users
        this.sendMessage(message);
      }
    }
  }
</script>