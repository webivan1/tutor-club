<template>
    <div v-if="isInvite()" :class="{ loader: loader }">
        <pre>{{ item }}</pre>

        <div class="message-chat px-2 py-2 bg-grey-100">
            <div class="message-chat-created">
                <timeago :since="filterDate(item.created_at)" :auto-update="60"></timeago>
            </div>
            <p class="text-black fs-14">
                <i class="fas fa-bell text-orange"></i> Приглашение на урок
            </p>
            <div class="message-chat-text bg-info text-white mb-2" v-html="item.message"></div>

            <p class="text-center">
                {{ item.classroom.started_at }} - {{ getDateStartLesson(item.classroom.started_at) }}
            </p>

            <div class="row">
                <div class="col-6 pr-1">
                    <button class="btn btn-success btn-sm btn-block">Принять</button>
                </div>
                <div class="col-6 pl-0">
                    <button class="btn btn-danger btn-sm btn-block">Отклонить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import DateHelper from '../../helpers/DateHelper'

  export default {
    props: ['item', 'user'],
    data() {
      return {
        loader: false,
      }
    },
    created() {
      //console.log(this.item.classroom.started_at, DateHelper.getFormtatTimezone(this.item.classroom.started_at));
    },
    methods: {
      filterDate(date) {
        return DateHelper.getFormtatTimezone(date);
      },

      getDateStartLesson(date) {
        let dateTimezone = DateHelper.getFormtatTimezone(date);
        console.log(dateTimezone);
        return dateTimezone;
      },

      isInvite() {
        return parseInt(this.user) !== parseInt(this.item.user.id) && this.item.classroom.user;
      },

      accept() {

      },

      reject() {

      }
    }
  }
</script>