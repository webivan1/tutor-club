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
    props: ['user', 'host', 't', 'lang'],
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
      getUserMedia({video: true, audio: false}, (err, stream) => {
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
        this.messages.push(this.field);
        this.field = '';

        this.swarm.peers.forEach(peer => {
          peer.send(this.field);
        });
      }
    }
  }
</script>