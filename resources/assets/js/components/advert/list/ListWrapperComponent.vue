<template>
    <div class="row">
        <div class="col">
            <advert-list
                :lang="lang"
                :messages="messages"
                :loader-list="loader"
                :models="models"
            ></advert-list>

            <div :class="{ 'loader': loader === true }">
                <b-pagination
                    v-if="total > 0"
                    align="center"
                    :total-rows="total"
                    v-model="currentPage"
                    :per-page="perPage"
                    v-on:change="setPage"
                ></b-pagination>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 bg-info text-white">
                <div class="card-body text-center">
                    <advert-sort
                        :sort="sort"
                        :loader="loader"
                        v-on:action-sort="setSort"
                    ></advert-sort>
                </div>
            </div>

            <div class="card">
                <search-form-advert
                    :lang="lang"
                    :messages="messages"
                    :loader-list="loader"
                    v-on:fetch-list="setData"
                ></search-form-advert>
            </div>
        </div>
    </div>
</template>

<script>
  import Vue from 'vue'
  import SearchFormAdvert from './SearchFormAdvertComponent.vue';
  import ListAdverts from './ListAdvertsComponent.vue';
  import SortComponent from './SortAdvertComponent.vue';

  Vue.component('search-form-advert', SearchFormAdvert);
  Vue.component('advert-list', ListAdverts);
  Vue.component('advert-sort', SortComponent);

  export default {
    props: ['lang', 'messagesJson'],
    data() {
      return {
        loader: false,
        models: [],
        currentPage: 1,
        perPage: 0,
        total: 0,
        postData: {},
        sort: [],
        actionUrl: null,
        messages: typeof this.messagesJson === 'string'
          ? JSON.parse(this.messagesJson)
          : {}
      };
    },
    created() {
      this.fetch();
    },
    methods: {
      setData(form) {
        this.postData = form;
        this.postData.page = 1;
        this.fetch();
      },

      setPage(page) {
        this.postData.page = page;
        this.fetch();
      },

      setSort(item) {
        this.actionUrl = item.url;
        this.postData.page = 1;
        this.fetch();
      },

      generateUrl() {
        return this.actionUrl || window.location.pathname + '/list';
      },

      fetch() {
        if (this.loader === true) {
          return false;
        }

        this.loader = true;

        axios.post(this.generateUrl(), this.postData)
          .then(response => {
            let data = response.data;
            this.currentPage = data.currentPage;
            this.total = data.total;
            this.perPage = data.perPage;
            this.models = data.models;
            this.sort = data.sort;
            this.loader = false;
          })
          .catch(err => {
            this.models = [];
            this.loader = false;
          });
      }
    }
  }
</script>