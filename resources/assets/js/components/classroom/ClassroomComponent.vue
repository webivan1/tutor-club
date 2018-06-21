<template>
    <div>
        <div class="row">
            <div class="col-md-7">
                <Video
                    v-if="!loader && !error"
                    ref="video"
                    :tutor="isTutor"
                    :room="roomData"
                    :user="userData"
                    :host="host"
                    :local-stream="stream"
                ></Video>
                <div v-else>
                    <div v-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>
                    <div v-else>loading...</div>
                </div>
            </div>
            <div class="col-md-5">
                <Chat
                    ref="chat"
                    :t="t"
                    :host="host"
                    :room="roomData"
                    :user="userData"
                    :lang="lang"
                ></Chat>
            </div>
        </div>
    </div>
</template>

<script>
  import Chat from "./chat/ChatComponent.vue";
  import Video from "./video/VideoComponent.vue";

  export default {
    props: ["trans", "user", "host", "room"],
    components: {
      Chat,
      Video
    },
    data() {
      return {
        loader: true,
        error: null,
        lang: "en",
        isTutor: false,
        roomData: JSON.parse(this.room),
        userData: JSON.parse(this.user),
        t: JSON.parse(this.trans)
      };
    },
    created() {
      this.lang = document.querySelector("html").getAttribute("lang");
      this.isTutor =
        parseInt(this.roomData.tutor.user_id) === parseInt(this.userData.id);
    },
    mounted() {
      this.initMedia(stream => {
        this.loader = false;
        this.stream = stream;
      });
    },
    methods: {
      initMedia(handler) {
        navigator.mediaDevices
          .getUserMedia({
            video: this.roomData.video,
            audio: this.roomData.audio
          })
          .then(stream => {
            handler(stream);
          })
          .catch(err => (this.error = err.message));
      }
    }
  };
</script>