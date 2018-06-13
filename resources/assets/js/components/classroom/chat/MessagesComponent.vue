<template>
    <div>
        <div v-if="loader">
            loading...
        </div>
        <div v-else>
            <div ref="wrapper" class="classroom-messages-list">
                <div v-if="nextPageUrl" class="text-center">
                    <div v-if="afterLoader">loading...</div>
                    <div v-else>
                        <button class="btn btn-sm btn-info" @click="nextPage">
                            {{ t.MoreMessages }}
                        </button>
                    </div>
                </div>

                <div v-for="item in messages">
                    <div :class="{
                        'message-hiring-manager': item.isTutor,
                        'message-candidate': !item.isTutor && item.user_id !== user.id,
                        'message-you': !item.isTutor && item.user_id === user.id
                    }" class="center-block container-fluid">
                        <div class="row">
                            <div class="col-auto">
                                <button
                                    :class="{
                                        'btn-danger': item.isTutor,
                                        'btn-info': !item.isTutor && item.user_id !== user.id,
                                        'btn-success': !item.isTutor && item.user_id === user.id
                                    }"
                                    class="btn bmd-btn-fab bmd-btn-fab-sm"
                                >
                                    {{ item.label }}
                                </button>
                                <h6 class="message-name">
                                    {{ item.user.name }}<br />
                                    <small>
                                        <timeago :since="filterDate(item.created_at)" :auto-update="60"></timeago>
                                    </small>
                                </h6>
                            </div>
                            <div class="col">
                                <div class="message-text" v-html="item.message"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: ['user', 'host', 'room', 'lang', 't'],
    mounted() {
      this.getList(response => setTimeout(_ => this.scrollDown()));

      if (this.$refs.wrapper) {
        this.$refs.wrapper.onscroll = e => {
          this.upScroll = true;

          if (this.bottom() === this.$refs.wrapper.scrollTop) {
            this.upScroll = false;
          }
        }
      }
    },
    data() {
      return {
        messages: [],
        loader: true,
        afterLoader: false,
        nextPageUrl: false,
        upScroll: false,
      }
    },
    methods: {
      scrollDown() {
        if (this.$refs.wrapper && this.upScroll === false) {
          this.$refs.wrapper.scrollTop = this.bottom();
        }
      },

      bottom() {
        return this.$refs.wrapper.scrollHeight + this.$refs.wrapper.clientHeight;
      },

      getList(handler) {
        axios.get(this.nextPageUrl || `${this.host}/classroom/${this.room.id}/message`)
          .then(response => {
            this.loader = false;
            this.afterLoader = false;
            this.nextPageUrl = response.data.next_page_url;
            this.messages = [].concat(response.data.data, this.messages);

            typeof handler === 'function' ? handler(response) : null;
          })
          .catch(err => console.error(err));
      },

      nextPage() {
        this.afterLoader = true;
        this.getList();
      },

      addMessage(message) {
        this.messages.push(message);
        setTimeout(_ => this.scrollDown());
      },

      filterDate(date) {
        let serverDate = new Date(date);
        return new Date(serverDate.getTime() + (-1 * serverDate.getTimezoneOffset() * 60000));
      }
    }
  }
</script>