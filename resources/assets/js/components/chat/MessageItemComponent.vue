<template>
    <div>
        <div v-if="item.user_id === this.user">
            <div class="pl-5 mb-2">
                <div class="row">
                    <div class="col">
                        <div class="message own-message" v-html="item.message"></div>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">
                            <timeago :since="filterDate(item.created_at)" :auto-update="60"></timeago>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="row mb-2">
                <div class="col">
                    <div class="pr-5">
                        <div class="message" v-html="item.message"></div>
                    </div>
                </div>
                <div class="col-auto">
                    <small class="text-muted">
                        <timeago :since="filterDate(item.created_at)" :auto-update="60"></timeago>
                    </small>
                </div>
            </div>
        </div>

        <!--<pre>{{ item }}</pre>-->
    </div>
</template>

<script>
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
        let serverDate = new Date(date);
        return new Date(serverDate.getTime() + (-1 * serverDate.getTimezoneOffset() * 60000));
      }
    }
  }
</script>