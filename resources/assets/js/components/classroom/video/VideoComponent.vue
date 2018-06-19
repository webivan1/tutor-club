<template>
    <div class="card">
        <div class="card-body">
            <div v-if="tutor === true">
                <div v-if="streams.length === 0">loading...</div>
                <div v-else-if="streams.length > 1">
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" :src="createUrl(streams[0].stream)" autoplay></video>
                    </div>
                </div>
                <div v-else>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item" :src="createUrl(streams[0].stream)" autoplay></video>
                    </div>
                </div>
            </div>
            <div v-else>
                <div v-for="item in streams">
                    <div v-if="item.params.isTutor === true">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" :src="createUrl(item.stream)" autoplay></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import signalhub from 'signalhub'
  import P2PLite from '../webrtc/P2PLite'

  export default {
    props: ['tutor', 'room', 'user', 'host', 'localStream'],
    data() {
      return {
        server: null,
        p2p: null,
        channel: null,
        loader: true,
        streams: []
      }
    },
    created() {
      this.channel = `classroom.${this.room.id}`;

      // init server
      this.createServer();
    },
    mounted() {
      this.connect();
    },
    methods: {
      createServer() {
        this.server = signalhub(this.channel, [
          `${this.host || 'http://localhost'}:6003`
        ]);
      },

      connect() {
        console.log(this.localStream);

        this.p2p = new P2PLite(this.server, this.localStream, {
          params: {
            user: this.user,
            isTutor: this.tutor
          },
        });

        this.p2p.onStream(peer => {
          this.streams.push({
            user: peer,
            stream: peer.getStream(),
            params: peer.getParams()
          });
        });

        this.p2p.onSignal(peer => {
          if (peer.getId() !== this.p2p.getUser().getId()) {
            peer.call(true);
          }
        });

        this.p2p.onClose(uuid => {
          this.streams.forEach((item, key) => {
            if (item.user.getId() === uuid) {
              this.streams.splice(key, 1);
            }
          });
        });
      },

      createUrl(stream) {
        return stream ? URL.createObjectURL(stream) : '';
      }
    }
  }
</script>