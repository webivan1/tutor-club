<template>
    <div>
        <label>Your ID:</label><br/>
        <textarea id="yourId"></textarea><br/>
        <label>Other ID:</label><br/>
        <textarea id="otherId"></textarea>
        <button id="connect">connect</button><br/>

        <label>Enter Message:</label><br/>
        <textarea id="yourMessage"></textarea>
        <button id="send">send</button>
        <pre id="messages"></pre>

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
  import getUserMedia from 'getusermedia'
  import Peer from 'simple-peer'

  export default {
    props: ['user'],
    data() {
      return {
        isAccess: false,
        loaderPeerConnect: true,
        peer: null,
        data: null,
        otherData: null,
        field: '',
        messages: []
      }
    },
    mounted() {
      getUserMedia({ video: true, audio: false }, function (err, stream) {
        if (err) return console.error(err);

        var peer = new Peer({
          initiator: true,
          trickle: false,
          stream: stream
        });

        peer.on('signal', function (data) {
          document.getElementById('yourId').value = JSON.stringify(data)
        });

        document.getElementById('connect').addEventListener('click', function () {
          var otherId = JSON.parse(document.getElementById('otherId').value)
          console.log(otherId);
          peer.signal(otherId)
        });

        document.getElementById('send').addEventListener('click', function () {
          var yourMessage = document.getElementById('yourMessage').value
          peer.send(yourMessage)
        });

        peer.on('data', function (data) {
          document.getElementById('messages').textContent += data + '\n'
        });

        peer.on('stream', function (stream) {
          var video = document.createElement('video')
          document.body.appendChild(video)

          video.src = window.URL.createObjectURL(stream)
          video.play()
        });
      })


      //this.isAccess = this.isAccessWebRTC();

//      if (this.isAccess) {
//        this.onPeerConnection();
//        this.onSignal();
//
//        getUserMedia({
//          video: true,
//          audio: false
//        }, (err, stream) => {
//          if (err) {
//            return console.log(err);
//          }
//
//          this.isAccess = true;
//          this.connect(stream);
//        });
//      }
    },
    methods: {
      /**
       * @return boolean
       */
      isAccessWebRTC() {
        return typeof getUserMedia === 'function';
      },

      connect(stream) {
        this.peer = new Peer({
          initiator: true,
          trickle: false,
          stream: stream
        });

        this.peer.on('error', err => console.log('Error', err));

        this.peer.on('signal', data => {
//          if (data.hasOwnProperty('type') && data.type === 'offer') {
//            this.data = data;
//            console.log(data);
//          }

          this.data = data;
          this.loaderPeerConnect = false;

//          this.sendNewUser();
        });

        this.peer.on('data', data => this.messages.push(data));

        this.peer.on('connect', _ => {
          this.loaderPeerConnect = false;
        });
      },

      call() {
        this.peer.signal(JSON.parse(this.otherData));
      },

      sendMessage() {
        this.peer.send(this.field);
      },

      sendNewUser() {
        axios.post('/test', {data: JSON.stringify(this.data), user: this.user})
          .then(response => {
          })
          .catch(err => console.error(err));
      },

      peer2peer(to) {
        axios.post('/peer', {data: JSON.stringify(this.data), user: to})
          .then(response => {
          })
          .catch(err => console.error(err));
      },

      onPeerConnection() {
        console.log(`peer.${this.user}`);

        Echo.channel(`peer.${this.user}`)
          .on('connect', response => {
            console.log('Connect 2', this.user);
            this.peer.signal(JSON.parse(response.data));
          });
      },

      onSignal() {
        Echo.channel('peer')
          .on('signal', response => {
            if (parseInt(response.userId) !== parseInt(this.user)) {
              this.peer2peer(response.userId);
              let data = JSON.parse(response.data);
              this.peer.signal(data);
              console.log('Connect 1', response);
            }
          });
      }
    }
  }
</script>