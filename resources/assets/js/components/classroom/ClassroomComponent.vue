<template>
    <div>
        <div v-if="!loader && !error">
            <div class="row">
                <div class="col-md-6">
                    <Video
                        ref="video"
                        :local-stream="stream"
                        :tutor="isTutor"
                        :streams="streams"
                    ></Video>
                </div>
                <div class="col-md-6" v-if="streams.length > 0">
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
  import P2PLite from './webrtc/test/P2PLite'

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
        t: JSON.parse(this.trans),
        stream: null,
        server: null,
        streams: [],
        ping: null
      }
    },
    watch: {
      streams: {
        handler: function (value) {
          this.changeTotalUsers(value.length + 1);
        },
        deep: true
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
        this.ping = new P2PLite(this.server, stream, {
          params: {
            user: this.userData,
            isTutor: this.isTutor
          }
        });

        this.ping.onStream(peer => {
          this.streams.push({
            user: peer,
            stream: peer.getStream(),
            params: peer.getParams()
          });
        });

        this.ping.onSignal(peer => {
          if (peer.getId() !== this.ping.getUser().getId()) {
            peer.call(true);
          }
        });

        this.ping.onClose(uuid => {
          this.streams.forEach((item, key) => {
            if (item.user.getId() === uuid) {
              this.streams.splice(key, 1);
            }
          })
        });

//        this.ping = new SignalP2P(this.server, {
//          params: {
//            user: this.userData,
//            isTutor: this.isTutor
//          }
//        });
//
//        this.listen(this.ping.getUser());
//
//        this.ping.onSignal(peer => {
//          if (peer.params.user.id !== this.userData.id) {
//            this.peers[peer.getUuid()] = this.connectSimplePeer(peer);
//          }
//        });
//
//        this.ping.onRealtime(peer => {
//          if (this.onlineUsers.indexOf(peer.getUuid()) === -1) {
//            this.onlineUsers.push(peer.getUuid());
//            // ...
//          }
//        });
      });
    },
    methods: {
      initMedia(handler) {
        navigator.mediaDevices.getUserMedia({
          video: this.roomData.video,
          audio: this.roomData.audio
        })
          .then(stream => {
            this.loader = false;
            this.stream = stream;

            handler(stream);
          })
          .catch(err => this.error = err.message);
      },

//      listen(user) {
//        user.on('stream', uuid => {
//          console.log('LISTENER STREAM OK');
//
//          let peer = this.ping.peers[uuid];
//
//          this.streams.push({
//            user: peer,
//            stream: peer.getStream(),
//            params: peer.getParams()
//          });
//        });
//
//        user.on('signal', message => {
//
//          if (!this.peers[message.uuid]) {
//            let user = this.ping.createUser(message.uuid, message.params);
//            this.peers[message.uuid] = this.initConnection(user, false);
//          }
//
//          this.peers[message.uuid].signal(message.signal);
//        });
//      },
//
//      connectSimplePeer(sendUser) {
//        return this.initConnection(sendUser, true);
//      },
//
//      initConnection(sendUser, initiator) {
//        let peer = new Peer({
//          initiator: initiator,
//          stream: this.stream,
//          //trickle: false,
//        });
//
//        peer.on('signal', signal => {
//          sendUser.send('signal', {
//            uuid: this.ping.getUser().getUuid(),
//            params: this.ping.getUser().getParams(),
//            signal: signal
//          });
//        });
//
//        peer.on('stream', stream => {
//          sendUser.addStream(stream);
//          this.ping.getUser().send('stream', sendUser.getUuid());
//        });
//
//        peer.on('close', _ => {
//          if (this.peers[sendUser.getUuid()]) {
//            delete this.peers[sendUser.getUuid()];
//          }
//
//          this.streams.forEach((item, key) => {
//            if (item.user.getUuid() === sendUser.getUuid()) {
//              this.streams.splice(key, 1);
//            }
//          })
//        });
//
//        return peer;
//      },

      changeTotalUsers(value) {
        let count = value;
        let element = document.getElementById('total-users');
        if (element) {
          element.innerText = count;
        }
      },

      sendMessage(message) {
        this.streams.forEach(item => {
          if (item.user.getId() !== this.ping.getUser().getId()) {
            this.server.broadcast(`listen-${item.getParams().user.id}`, {
              type: 'message',
              data: message
            });
          }
        });
      },

      getChat() {
        return this.$refs.chat;
      }
    }
  }
</script>