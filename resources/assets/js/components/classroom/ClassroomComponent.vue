<template>
    <div>
        <video autoplay ref="localVideo" width="200"></video>

        <div v-for="item in peers">
            <video :src="createUrl(item.peer.stream)" autoplay width="200"></video>
        </div>

        <div style="max-height: 250px; overflow-y: auto">
            <div v-for="message in messages">{{ message }}</div>
        </div>

        <textarea v-model="field" class="form-control"></textarea>
        <button type="button" class="btn btn-info" @click="sendMessage">Send</button>


        <!--<div v-if="isAccess">-->
        <!--<div v-if="loaderPeerConnect">-->
        <!--loading...-->
        <!--</div>-->
        <!--<div v-else>-->
        <!--<pre>{{ messages }}</pre>-->

        <!--<textarea :value="JSON.stringify(data)" placeholder="Your ID"></textarea>-->
        <!--<textarea v-model="otherData" placeholder="other ID"></textarea>-->

        <!--<button type="button" class="btn btn-primary" @click="call">Connect</button>-->

        <!--<textarea class="textarea" v-model="field"></textarea>-->
        <!--<button type="button" class="btn btn-primary" @click="sendMessage">Send</button>-->
        <!--</div>-->
        <!--</div>-->
        <!--<div v-else>-->
        <!--<div class="alert alert-danger">-->
        <!--Your browser is old! Change to browser on chrome or firefox-->
        <!--</div>-->
        <!--</div>-->
    </div>
</template>

<script>
    import getUserMedia from 'getUserMedia'
    import signalhub from 'signalhub'
    import createSwarm from 'webrtc-swarm'

    export default {
      props: ['user', 'host'],
      data() {
        return {
          hub: null,
          swarm: null,
          stream: null,
          field: null,
          peers: [],
          messages: [],
        }
      },
      mounted() {
        getUserMedia({ video: true, audio: false }, (err, stream) => {
          if (err) {
            return console.error(err);
          }

          this.stream = stream;
          this.$refs.localVideo.srcObject = stream;

          this.hub = signalhub('room', [
            `${this.host || 'http://localhost'}:6003`
          ]);

          this.swarm = new createSwarm(this.hub, {
            stream: this.stream
          });

          this.swarm.on('connect', (peer, id) => {
            peer.on('data', data => {
              this.messages.push(data.toString());
            });

            this.peers.push({
              id: id,
              peer: peer
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

        sendMessage() {
          this.swarm.peers.forEach(peer => {
            peer.send(this.field);
            this.messages.push(this.field);
            this.field = '';
          })
        }
      }
    }








  //  import getUserMedia from 'getusermedia'
  //
  //  export default {
  //    props: ['user'],
  //    data() {
  //      return {
  //        isAccess: false,
  //        errorString: null,
  //        configPeer: [
  //          {
  //            iceServers: [
  //              { url: 'stun:stun.l.google.com:19302' }
  //            ]
  //          },
  //          {
  //            optional: [
  //              { DtlsSrtpKeyAgreement: true }
  //            ]
  //          }
  //        ],
  //        stream: null,
  //        peer: null,
  //      }
  //    },
  //    mounted() {
  //      getUserMedia({ video: true, audio: true }, (err, stream) => {
  //        if (err) {
  //          return console.error(err);
  //        }
  //
  //        this.stream = stream;
  //
  //        this.peer = new RTCPeerConnection(...this.configPeer);
  //        this.peer.addStream(this.stream);
  //        this.peer.onicecandidate = this.iceCandidate.bind(this);
  //        this.peer.onaddstream = this.remoteStream.bind(this);
  //
  //        let video = document.getElementById('video');
  //        video.src = URL.createObjectURL(stream);
  //      });
  //
  //
  ////      getUserMedia({ video: true, audio: false }, function (err, stream) {
  ////        if (err) return console.error(err);
  ////
  ////        var peer = new Peer({
  ////          initiator: true,
  ////          trickle: false,
  ////          stream: stream
  ////        });
  ////
  ////        peer.on('signal', function (data) {
  ////          document.getElementById('yourId').value = JSON.stringify(data)
  ////        });
  ////
  ////        document.getElementById('connect').addEventListener('click', function () {
  ////          var otherId = JSON.parse(document.getElementById('otherId').value)
  ////          console.log(otherId);
  ////          peer.signal(otherId)
  ////        });
  ////
  ////        document.getElementById('send').addEventListener('click', function () {
  ////          var yourMessage = document.getElementById('yourMessage').value
  ////          peer.send(yourMessage)
  ////        });
  ////
  ////        peer.on('data', function (data) {
  ////          document.getElementById('messages').textContent += data + '\n'
  ////        });
  ////
  ////        peer.on('stream', function (stream) {
  ////          var video = document.createElement('video')
  ////          document.body.appendChild(video)
  ////
  ////          video.src = window.URL.createObjectURL(stream)
  ////          video.play()
  ////        });
  ////      })
  //
  //      //this.isAccess = this.isAccessWebRTC();
  //
  ////      if (this.isAccess) {
  ////        this.onPeerConnection();
  ////        this.onSignal();
  ////
  ////        getUserMedia({
  ////          video: true,
  ////          audio: false
  ////        }, (err, stream) => {
  ////          if (err) {
  ////            return console.log(err);
  ////          }
  ////
  ////          this.isAccess = true;
  ////          this.connect(stream);
  ////        });
  ////      }
  //    },
  //    methods: {
  //
  //      sendMessage(message) {
  //
  //      },
  //
  //      iceCandidate(event) {
  //        if (event.candidate) {
  //          this.sendMessage({
  //            type: 'candidate',
  //            label: event.candidate.sdpMLineIndex,
  //            id: event.candidate.sdpMid,
  //            candidate: event.candidate.candidate
  //          });
  //        }
  //      },
  //
  //      remoteStream() {
  //
  //      },
  //
  //      createOffer() {
  //        if (this.peer) {
  //          this.peer.createOffer(this.localDescription.bind(this), err => console.error(err), {
  //            mandatory: {
  //              OfferToReceiveAudio: true,
  //              OfferToReceiveVideo: true
  //            }
  //          });
  //        }
  //      },
  //
  //      createAnswer() {
  //        if (this.peer) {
  //          this.peer.createAnswer(this.localDescription.bind(this), err => console.error(err), {
  //            mandatory: {
  //              OfferToReceiveAudio: true,
  //              OfferToReceiveVideo: true
  //            }
  //          });
  //        }
  //      },
  //
  //      localDescription(description) {
  //        this.peer.setLocalDescription(description);
  //        this.sendMessage(description);
  //      },
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //      /**
  //       * @return boolean
  //       */
  //      isAccessWebRTC() {
  //        return typeof getUserMedia === 'function';
  //      },
  //
  //      connect(stream) {
  //        this.peer = new Peer({
  //          initiator: true,
  //          trickle: false,
  //          stream: stream
  //        });
  //
  //        this.peer.on('error', err => console.log('Error', err));
  //
  //        this.peer.on('signal', data => {
  ////          if (data.hasOwnProperty('type') && data.type === 'offer') {
  ////            this.data = data;
  ////            console.log(data);
  ////          }
  //
  //          this.data = data;
  //          this.loaderPeerConnect = false;
  //
  ////          this.sendNewUser();
  //        });
  //
  //        this.peer.on('data', data => this.messages.push(data));
  //
  //        this.peer.on('connect', _ => {
  //          this.loaderPeerConnect = false;
  //        });
  //      },
  //
  //      call() {
  //        this.peer.signal(JSON.parse(this.otherData));
  //      },
  //
  //      sendMessage() {
  //        this.peer.send(this.field);
  //      },
  //
  //      sendNewUser() {
  //        axios.post('/test', {data: JSON.stringify(this.data), user: this.user})
  //          .then(response => {
  //          })
  //          .catch(err => console.error(err));
  //      },
  //
  //      peer2peer(to) {
  //        axios.post('/peer', {data: JSON.stringify(this.data), user: to})
  //          .then(response => {
  //          })
  //          .catch(err => console.error(err));
  //      },
  //
  //      onPeerConnection() {
  //        console.log(`peer.${this.user}`);
  //
  //        Echo.channel(`peer.${this.user}`)
  //          .on('connect', response => {
  //            console.log('Connect 2', this.user);
  //            this.peer.signal(JSON.parse(response.data));
  //          });
  //      },
  //
  //      onSignal() {
  //        Echo.channel('peer')
  //          .on('signal', response => {
  //            if (parseInt(response.userId) !== parseInt(this.user)) {
  //              this.peer2peer(response.userId);
  //              let data = JSON.parse(response.data);
  //              this.peer.signal(data);
  //              console.log('Connect 1', response);
  //            }
  //          });
  //      }
  //    }
  //  }
</script>