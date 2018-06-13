<template>
    <div>
        <video autoplay v-if="stream" :src="createUrl(stream)" width="200"></video>

        <div v-for="item in peers">
            <video :src="createUrl(item.peer.stream)" autoplay width="200"></video>
        </div>

        <pre>{{ peers }}</pre>

        <div v-if="!loader && !error">
            <div class="row">
                <div class="col">
                    <Chat
                        ref="chat"
                        :t="t"
                        :host="host"
                        :room="roomData"
                        :user="userData"
                        :lang="lang"
                        v-on:send="sendMessage"
                    ></Chat>
                </div>
                <div class="col-auto">
                    video component
                </div>
            </div>
        </div>
        <div v-else>
            <div v-if="error" class="alert alert-danger">
                {{ error }}
            </div>
            <div v-else>loading...</div>
        </div>
    </div>
</template>

<script>
  import getUserMedia from 'getUserMedia'
  import signalhub from 'signalhub'
  import createSwarm from 'webrtc-swarm'

  import Chat from './chat/ChatComponent.vue'

  export default {
    props: ['trans', 'user', 'host', 'room'],
    components: { Chat },
    data() {
      return {
        lang: 'en',
        error: false,
        loader: true,
        hub: null,
        swarm: null,
        stream: null,
        peers: [],
        roomData: JSON.parse(this.room),
        userData: JSON.parse(this.user),
        isTutor: false,
        t: JSON.parse(this.trans)
      }
    },
    created() {
      this.lang = document.querySelector('html').getAttribute('lang');
      this.isTutor = parseInt(this.roomData.tutor.user_id) === parseInt(this.userData.id);
    },
    mounted() {
      getUserMedia({video: true, audio: false}, (err, stream) => {
        if (err) {
          this.error = err.message;
          return console.error(err);
        }

        this.loader = false;
        this.stream = stream;

        this.hub = signalhub('room', [
          `${this.host || 'http://localhost'}:6003`
        ]);

        this.swarm = new createSwarm(this.hub, {
          stream: this.stream
        });

        this.swarm.on('connect', (peer, id) => {
          peer.on('data', data => {
            data = JSON.parse(data.toString());
            this.getChat().getMessage().addMessage(data);
          });

          this.peers.push({
            id: id,
            peer: peer,
            user: this.userData,
            room: this.roomData
          });
        });

        this.swarm.on('disconnect', (peer, id) => {
          this.peers.forEach((item, key) => {
            if (item.id === id) {
              this.peers.splice(key, 1);
            }
          });
        });
      });
    },
    methods: {
      createUrl(stream) {
        return URL.createObjectURL(stream);
      },

      sendMessage(message) {
        this.swarm.peers.forEach(peer => {
          peer.send(message);
        });
      },

      getChat() {
        return this.$refs.chat;
      }
    }
  }
</script>