<template>
    <div class="message-chat" :class="{
        'own': isOwnMessage()
    }">

        <div class="message-chat-created" :class="{
            'text-left': !isOwnMessage(),
            'text-right': isOwnMessage()
        }">
            <timeago :since="filterDate(item.created_at)" :auto-update="60"></timeago>
        </div>
        <div class="row" :class="{
            'justify-content-end': isOwnMessage()
        }">
            <div class="col-10">
                <div class="message-chat-text" v-html="item.message"></div>
            </div>
        </div>
    </div>
</template>

<script>
  import DateHelper from '../../helpers/DateHelper'

  export default {
    props: ['item'],
    data() {
      return {
        user: null
      }
    },
    mounted() {
      this.user = parseInt(document.querySelector('[self-user-id]').getAttribute('self-user-id'));
    },
    methods: {
      filterDate(date) {
        return DateHelper.getFormatTimezone(date);
      },

      isOwnMessage() {
        return parseInt(this.item.user_id) === parseInt(this.user);
      }
    }
  }
</script>