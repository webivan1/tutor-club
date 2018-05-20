<template>
    <div ref="wrapper" class="card-body px-0 py-0 flex-auto-height" :class="{
        loader: searchLoader
    }">
        <form @submit.prevent="searchDialog()">
            <input type="text" class="form-control px-1" v-model="search" placeholder="Find dialog..." />
        </form>

        <div class="list-group py-0">
            <a v-for="(item, key) in list.data" @click="checkDialog(item)" href="javascript:void(0)" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                        <i class="indecator-user-min fas fa-circle mr-0" :user-id="item.user.user.id" :user-active-date="0"></i>
                        #{{ item.user.user.id }} {{ item.user.user.name }}
                    </h5>
                    <small>
                        <timeago :since="filterDate(item.max_updated_at)" :auto-update="60"></timeago>
                    </small>
                </div>
                <p class="mb-1">
                    {{ item.title }}
                    <span class="badge badge-secondary badge-pill mr-1">
                        {{ item.messages_count }}
                    </span>
                    <span v-if="item.message_no_read" class="badge badge-danger badge-pill">
                        New
                    </span>
                </p>
            </a>

            <div v-if="nextPageLoader" class="text-center py-3">
                <div class="ld ld-ring ld-spin-fast mx-auto fs-1 text-info"></div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: ['list', 'loader', 'nextPageLoader', 'searchLoader'],
    data() {
      return {
        page: 1,
        next: null,
        search: '',
      }
    },
    watch: {
      list(newValue) {
        this.page = newValue.current_page;
        this.next = newValue.next_page_url;
      }
    },
    mounted() {
      this.page = this.list.current_page;
      this.next = this.list.next_page_url;

      if (this.$refs.wrapper) {
        this.$refs.wrapper.onscroll = this.scroll.bind(this);
      }
    },
    methods: {
      /**
       * Скролим вниз чтобы вызать пагинацию
       */
      scroll(ev) {
        if (ev.target.scrollTop === (ev.target.scrollHeight - ev.target.clientHeight)) {
          this.nextPage();
        }
      },

      /**
       * Выбираем комнату
       */
      checkDialog(item) {
        this.$emit('check-dialog', item);
      },

      /**
       * Догружаем список комнат
       */
      nextPage() {
        if (this.next) {
          this.$emit('next-page-dialog', this.next)
        }
      },

      /**
       * Поиск
       */
      searchDialog() {
        this.$emit('search', this.search);
      },

      filterDate(date) {
        let serverDate = new Date(date);
        return new Date(serverDate.getTime() + (-1 * serverDate.getTimezoneOffset() * 60000));
      }
    }
  }
</script>