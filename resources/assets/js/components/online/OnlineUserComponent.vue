<template>
    <span class="indecator-user-min" :class="{ active: isActive }">
        <i class=" fas fa-circle"></i>
    </span>
</template>

<script>
  export default {
    props: ['user'],
    data() {
      return {
        prependUrl: '',
        isActive: false,
        lastTimerUpdate: null,
      }
    },
    mounted() {
      Echo.channel(`user.${this.user}`).on('online', user => {
        this.isActive = true;
        this.lastTimerUpdate = new Date();
      });
      Echo.channel(`user.${this.user}`).on('disonline', user => {
        this.isActive = false;
        this.lastTimerUpdate = new Date();
      });
    },
    created() {
      setInterval(this.disableUserIsTimeoutActive.bind(this), 3000);
    },
    methods: {
      now() {
        let date = new Date();
        return date.getTime();
      },

      diffSec(time) {
        return (this.now() - time) / 1000;
      },

      disableUserIsTimeoutActive() {
        if (this.isActive === true && this.lastTimerUpdate && this.diffSec(this.lastTimerUpdate.getTime()) > 30) {
          this.isActive = false;
        }
      }
    }
  }
</script>