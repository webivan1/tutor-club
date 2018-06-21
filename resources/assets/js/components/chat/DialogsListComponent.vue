<template>
    <div ref="wrapper" class="card-body px-0 py-0 flex-vertical" :class="{
        loader: searchLoader
    }">
        <form @submit.prevent="searchDialog()">
            <input type="text" class="form-control px-1" v-model="search" placeholder="Find dialog..." />
        </form>
        <div class="flex-auto-height py-2">
            <div class="container-fluid messages-list">
                <div v-for="(item, key) in list.data" class="row c-p mb-2" @click="checkDialog(item)">
                    <div class="col-auto pr-0">
                        <div class="position-relative">
                            <div class="avatar-block">
                                #{{ item.user.user.id }}
                            </div>
                            <online :user="item.user.user.id"></online>
                        </div>
                    </div>
                    <div class="col pl-2">
                        <small class="float-right text-grey-400">
                            <timeago :since="filterDate(item.max_updated_at)" :auto-update="60"></timeago>
                        </small>
                        <h5 class="text-indigo-300 mb-0">
                            {{ item.user.user.name }}
                            <span v-if="item.message_no_read" class="badge badge-danger badge-pill">
                                New
                            </span>
                        </h5>
                        <div class="message-content text-grey">
                            <div><small><b>Theme dialog:</b> {{ item.title }}</small></div>
                            <div><small><b>Total messages:</b> {{ item.messages_count }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

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