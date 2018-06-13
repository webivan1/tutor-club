<template>
    <div>
        <video autoplay v-if="stream" :src="createUrl(stream)" width="200"></video>

        <div v-for="item in peers">
            <video :src="createUrl(item.peer.stream)" autoplay width="200"></video>
        </div>

        <!--<div v-if="tutor === true">-->
            <!--<div v-if="peers.length > 1">-->
                <!--<div class="embed-responsive embed-responsive-16by9">-->
                    <!--<video class="embed-responsive-item" :src="createUrl(stream)" autoplay></video>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div v-else>-->
                <!--<div class="embed-responsive embed-responsive-16by9">-->
                    <!--<video class="embed-responsive-item" :src="createUrl(peers[0].peer.stream)" autoplay></video>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div v-else>-->
            <!--<div v-for="item in peers">-->
                <!--<div v-if="item.isTutor === true">-->
                    <!--<div class="embed-responsive embed-responsive-16by9">-->
                        <!--<video class="embed-responsive-item" :src="createUrl(item.peer.stream)" autoplay></video>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
    </div>
</template>

<script>
  export default {
    props: ['stream', 'tutor', 'peers'],
    methods: {
      createUrl(stream) {
        return URL.createObjectURL(stream);
      },
    }
  }
</script>