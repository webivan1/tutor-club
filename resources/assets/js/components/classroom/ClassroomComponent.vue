<template>
    <div>
        <div v-if="!loader && !error">
            <div class="row">
                <div class="col-md-6">
                    <video ref="video"></video>

                    <!--<Video-->
                        <!--ref="video"-->
                        <!--:stream="stream"-->
                        <!--:tutor="isTutor"-->
                        <!--:swarm="swarm"-->
                        <!--:peers="peers"-->
                    <!--&gt;</Video>-->
                </div>
                <div class="col-md-6">
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
  //import getUserMedia from 'getUserMedia'
  import signalhub from 'signalhub'
  import createSwarm from 'webrtc-swarm'
  import wrtc from 'wrtc'

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
    watch: {
      peers: {
        handler: function (value) {
          this.changeTotalUsers(value);
        },
        deep: true
      }
    },
    created() {
      this.lang = document.querySelector('html').getAttribute('lang');
      this.isTutor = parseInt(this.roomData.tutor.user_id) === parseInt(this.userData.id);
    },
    mounted() {
      this.changeTotalUsers(this.peers);

      navigator.mediaDevices.getUserMedia({
        video: this.roomData.video,
        audio: this.roomData.audio
      })
        .then(stream => {
          this.loader = false;

          let hub = signalhub(`room:${this.roomData.id}`, [
            `${this.host || 'http://localhost'}:6003`
          ]);

          this.swarm = new createSwarm(hub, {
            stream: stream
          });

          this.swarm.on('peer', (peer, id) => {
            this.$refs.video.srcObject = peer.stream;
            this.$refs.video.play();

//          let exist = false;
//
//          this.peers.forEach(item => {
//            if (item.user.id === this.user.id) {
//              exist = true;
//            }
//          });

//            console.log(peer);

//            peer.on('data', data => {
//              data = JSON.parse(data.toString());
//
//              switch (data.type) {
//                case 'message':
//                  this.getChat().getMessage().addMessage(data.data);
//                  break;
//              }
//            });

//          if (exist === true) {
//            return false;
//          }

//            let data = Object.assign({}, {id: id, peer: peer});
//
//            this.peers.push(data);
          });

          this.swarm.on('disconnect', (peer, id) => {
            this.deletePeer(id);
          });
        });

//      getUserMedia({
//        video: this.roomData.video,
//        audio: this.roomData.audio
//      }, (err, stream) => {
//        if (err) {
//          this.error = err.message;
//          return console.error(err);
//        }
//
//
//      });
    },
    methods: {
      changeTotalUsers(value) {
        let count = value.length;
        let element = document.getElementById('total-users');
        if (element) {
          element.innerText = count;
        }
      },

      deletePeer(id) {
        this.peers.forEach((item, key) => {
          if (item.id === id) {
            this.peers.splice(key, 1);
          }
        });
      },

      sendMessage(message) {
        this.swarm.peers.forEach(peer => {
          peer.send(JSON.stringify({
            type: 'message',
            data: JSON.parse(message)
          }));
        });
      },

      getChat() {
        return this.$refs.chat;
      }
    }
  }
</script>