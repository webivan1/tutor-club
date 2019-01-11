<template>
    <div class="h-100">
        <div class="row h-100">
            <div class="col-3 pl-0 pr-0 h-100">
                <div class="flex-vertical">
                    <div>
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
            <div class="col h-100 overflow-y-auto">
                <div v-html="closeClassroomHtml"></div>
                <div class="py-3">
                    <div class="alert alert-warning">
                        In working
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Chat from "./chat/ChatComponent.vue";
  import Video from "./video/VideoComponent.vue";

  export default {
    props: ["trans", "user", "host", "room", "heading", "closeClassroomHtml"],
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
      this.isTutor = parseInt(this.roomData.tutor_model.user_id) === parseInt(this.userData.id);
    },
    mounted() {
      Echo.private(`classroom.close.${this.roomData.id}`)
        .on('close', message => {
          alert('Close lesson!');
        });

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