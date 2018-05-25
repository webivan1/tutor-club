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
      }
    },
    mounted() {
      // Echo.channel(`user.${this.user}`).on('online', user => {
      //   this.isActive = true;
      //   this.lastTimerUpdate = new Date();
      // });
      // Echo.channel(`user.${this.user}`).on('disonline', user => {
      //   this.isActive = false;
      //   this.lastTimerUpdate = new Date();
      // });
      io(':6002').on('user.online.' + this.user, e => {
        this.isActive = true;
        this.lastTimerUpdate = new Date();
      });

      this.send();

      // Каждые 5 сек отправляем запрос
      setInterval(this.send.bind(this), 5000);
    },
    created() {
      setInterval(this.disableUserIsTimeoutActive.bind(this), 1000);
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