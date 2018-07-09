<template>
    <div v-if="isInvite()" :class="{ loader: loader }">
        <div v-if="!send" class="message-chat px-2 py-2 bg-grey-100">
            <div class="message-chat-created">
                <timeago :since="filterDate(item.created_at)" :auto-update="60"></timeago>
            </div>
            <p class="text-black fs-14">
                <i class="fas fa-bell text-orange"></i> Приглашение на урок
                <span class="fs-16 text-info">
                    {{ getDateStartLesson(item.classroom.started_at) }}
                </span> (<timeago :since="filterDate(item.classroom.started_at)" :auto-update="1"></timeago>)
            </p>
            <div class="message-chat-text bg-info text-white mb-2" v-html="item.message"></div>

            <div class="row">
                <div class="col-6 pr-1">
                    <button @click="accept" :disabled="loader" class="mdc-button mdc-button--raised bg-green btn-block">
                        Принять
                    </button>
                </div>
                <div class="col-6 pl-0">
                    <button @click="reject" :disabled="loader" class="mdc-button mdc-button--raised bg-red btn-block">
                        Отклонить
                    </button>
                </div>
            </div>
        </div>
        <div v-else>
            <div v-if="method === 'accept'">
                <div class="alert alert-success">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div v-else>
                <div class="alert alert-danger">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import DateHelper from '../../helpers/DateHelper'
  import moment from 'moment'

  export default {
    props: ['item', 'user'],
    data() {
      return {
        send: false,
        method: null,
        loader: false,
        url: null,
      }
    },
    created() {
      this.url = document.body.getAttribute('data-url');
    },
    methods: {
      filterDate(date) {
        return DateHelper.getFormtatTimezone(date);
      },

      getDateStartLesson(date) {
        let dateTimezone = DateHelper.getFormtatTimezone(date);
        return moment(dateTimezone).format('DD/MM/YYYY HH:mm');
      },

      isInvite() {
        return parseInt(this.user) !== parseInt(this.item.user.id) && this.item.classroom.user;
      },

      accept() {
        this.sendData('accept');
      },

      reject() {
        if (!confirm('Are you sure?')) {
          return false;
        }

        this.sendData('reject');
      },

      sendData(method) {
        this.method = method;

        if (this.loader === true) {
          return false;
        }

        this.loader = true;

        axios.get(`${this.url}/classroom/invite/${this.item.classroom.id}/${method}`)
          .then(response => {
            this.loader = false;
            this.send = true;
          })
          .catch(err => console.error(err));
      }
    }
  }
</script>