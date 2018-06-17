<template>
    <div>
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
</template>

<script>
  export default {
    props: ['localStream', 'streams', 'tutor'],
    methods: {
      createUrl(stream) {
        return stream ? URL.createObjectURL(stream) : '';
      }
    }
  }
</script>