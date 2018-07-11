<template>
    <div class="pt-4">
        <div v-if="loader">
            <div class="ld ld-ring ld-spin-fast fs-1 text-orange mx-auto"></div>
        </div>
        <div v-else>
            <div v-if="error" class="alert alert-danger">
                {{ error }}
            </div>
            <div v-else>
                <table class="table fs-13">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Date start</th>
                            <th>Subject</th>
                            <th>Price</th>
                            <th>Minutes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(model, key) in models" @click="setItem(model)" class="c-p" :class="{ 'table-success': model === item }">
                            <td>{{ model.id }}</td>
                            <td><b>{{ filterDate(model.started_at) }}</b></td>
                            <td>{{ model.subject }}</td>
                            <td><b>{{ model.price }}</b> {{ model.price_type }}</td>
                            <td>{{ model.minutes }}</td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="item" class="pt-2 text-center">
                    <button class="mdc-button mdc-button--raised">
                        Пригласить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import moment from 'moment'
  import DateHelper from '../../helpers/DateHelper'

  export default {
    props: ['to', 'from', 'tutor', 'advert', 'prependUrl'],
    data() {
      return {
        models: [],
        item: null,
        loader: true,
        error: null
      }
    },
    mounted() {
      if (this.loader === true) {
        this.getList();
      }
    },
    methods: {
      setItem(item) {
        this.item = item;
      },

      getList() {
        axios.get(`${this.prependUrl}/classroom/choose`)
          .then(response => {
            this.loader = false;
            this.models = response.data;
          })
          .catch(err => {
            this.loader = false;
            this.error = err.message;
          });
      },

      filterDate(date) {
        let dateFormat = DateHelper.getFormtatTimezone(date);
        return moment(dateFormat).format('DD/MM/YYYY HH:mm');
      }
    }
  }
</script>