<template>
    <div>
        <video ref="video" width="200"></video>

        <pre>{{ users }}</pre>

        <div v-if="!loader && !error">
            <div class="row">
                <div class="col-md-6">
                    <!--<Video-->
                    <!--ref="video"-->
                    <!--:stream="stream"-->
                    <!--:tutor="isTutor"-->
                    <!--:swarm="swarm"-->
                    <!--:peers="peers"-->
                    <!--&gt;</Video>-->
                </div>
                <div class="col-md-6">
                    <!--<Chat-->
                    <!--ref="chat"-->
                    <!--:t="t"-->
                    <!--:host="host"-->
                    <!--:room="roomData"-->
                    <!--:user="userData"-->
                    <!--:lang="lang"-->
                    <!--v-on:send="sendMessage"-->
                    <!--&gt;</Chat>-->
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
  import Peer from 'simple-peer'
  import signalhub from 'signalhub'
  import createSwarm from 'webrtc-swarm'

  import Chat from './chat/ChatComponent.vue'
  import Video from './video/VideoComponent.vue'

  export default {
    props: ['trans', 'user', 'host', 'room'],
    components: {
      Chat,
      Video
    },
    data() {
      return {
        loader: true,
        error: null,
        lang: 'en',
        isTutor: false,
        roomName: null,
        roomData: JSON.parse(this.room),
        userData: JSON.parse(this.user),
        stream: null,
        server: null,
        connect: null,
        users: [],
        connections: [],
        swarm: null,
        streams: {},
        isSendOffer: false,
        peers: {}
      }
    },
    created() {
      this.lang = document.querySelector('html').getAttribute('lang');
      this.isTutor = parseInt(this.roomData.tutor.user_id) === parseInt(this.userData.id);
      this.roomName = `room-${this.roomData.id}`;
    },
    mounted() {
      this.server = signalhub(this.roomName, [
        `${this.host || 'http://localhost'}:6003`
      ]);

      this.initMedia(stream => {
        this.stream = stream;

        this.server.broadcast('ping', { user: this.userData });

        this.server.subscribe('ping')
          .on('data', message => {
            if (this.userData.id !== message.user.id) {
              this.peers[message.user.id] = this.connectSimplePeer(message.user.id);
            }
          });
      });

      this.server.subscribe(`user-${this.userData.id}`)
        .on('data', message => {
          let peer = this.peers[message.user.id];

          if (peer === undefined) {
            peer = this.initConnection(message.user.id, false);
          }

          peer.signal(message.data);
        });
    },
    watch: {
//      streams: {
//        handler: function (value) {
//          for (id in value) {
//            this.$refs.video.srcObject = value[id];
//            this.$refs.video.play();
//          }
//        },
//        deep: true
//      }
    },
    methods: {
      initMedia(handler) {
        navigator.mediaDevices.getUserMedia({
          video: this.roomData.video,
          audio: this.roomData.audio
        })
          .then(stream => {
            this.loader = false;
            handler(stream);
          });
      },

      connectSimplePeer(userId) {
        return this.initConnection(userId, true);
      },

      remoteVideo(stream) {
        this.$refs.video.srcObject = stream;
        this.$refs.video.play();
      },

      initConnection(sendUser, initiator) {
        let peer = new Peer({
          initiator: initiator,
          stream: this.stream,
          trickle: false,
        });

        peer.on('signal', signal => {
          this.server.broadcast(`user-${sendUser}`, {
            type: 'signal',
            user: this.userData,
            data: signal
          });
        });

        peer.on('stream', stream => this.remoteVideo(stream));

        return peer;
      },
    }
  }
</script>