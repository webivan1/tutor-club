<template>
    <span class="indecator-user-min" :class="{ active: isActive }" :data-update="lastTimerUpdate ? lastTimerUpdate.getTime() : 0">
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
        intervalSend: null,
        intervalClose: null
      }
    },
    mounted() {
      let key = 'user.online.' + this.user;

      io(':6002').off(key).on(key, e => {
        this.isActive = true;
        this.lastTimerUpdate = new Date();
      });

      this.send();

      // Каждые 5 сек отправляем запрос
      this.intervalSend = setInterval(this.send.bind(this), 5000);
      this.intervalClose = setInterval(this.disableUserIsTimeoutActive.bind(this), 1000);
    },
    destroyed() {
      clearInterval(this.intervalSend);
      clearInterval(this.intervalClose);
    },
    methods: {
      send() {
        io(':6002').emit(`send.user`, this.user);
      },

      now() {
        let date = new Date();
        return date.getTime();
      },

      diffSec(time) {
        return (this.now() - time) / 1000;
      },

      disableUserIsTimeoutActive() {
        if (this.isActive === true && this.lastTimerUpdate && this.diffSec(this.lastTimerUpdate.getTime()) > 10) {
          this.isActive = false;
        }
      }
    }
  }
</script>