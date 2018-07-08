<template>
    <div>
        <b-modal v-on:show="isShowModal" ref="modal" hide-footer title="Регистрация урока">
            <form @submit.prevent="register">
                <div class="text-center py-2" v-if="loaderPrices">
                    <div class="ld ld-ring ld-spin-fast fs-1 text-orange mx-auto"></div>
                </div>
                <div v-else>
                    <div class="form-group">
                        <label>Выберите курс урока</label>
                        <select v-model="advertPrice" class="form-control">
                            <option v-for="item in list" :value="item">
                                {{ item.name }} | {{ item.price_from }} {{ item.price_type }} | {{ item.minutes }} min
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Выбрать дату начала урока</label>
                    <date-picker v-model="startedAt" :config="options"></date-picker>
                </div>

                <checkbox v-model="video" label="Видиотрансляция" :checked="1"></checkbox>

                <div v-if="error !== null" class="alert alert-danger">
                    <a class="text-danger" href="javascript:void(0)" @click="error = null">close</a><br />
                    {{ error }}
                </div>

                <div class="text-right">
                    <button :disabled="loaderSend" class="btn btn-info">
                        lesson is register
                    </button>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
  import moment from 'moment'

  export default {
    props: ['to', 'from', 'tutor', 'advert'],
    data() {
      return {
        defaultList: [],
        list: [],
        prependUrl: null,
        loaderPrices: true,
        loaderSend: false,
        error: null,
        errors: [],
        startedAt: null,
        advertPrice: null,
        video: true,
        subject: null,
        options: {
          minDate: moment(),
          //inline: true,
          format: 'YYYY-MM-DD HH:mm'
        }
      };
    },
    created() {
      this.prependUrl = document.body.getAttribute('data-url');
    },
    methods: {
      isShowModal() {
        this.getAdvert();
      },

      getAdvert() {
        axios.post(`${this.prependUrl}/tutor/list-prices`, {
          tutor: this.tutor.id,
          advert: this.advert
        })
          .then(response => {
            this.defaultList = [...response.data];
            this.list = response.data;
            this.loaderPrices = false;
          })
          .catch(err => console.error(err));
      },

      register() {
        this.loaderSend = true;

        let post = {
          video: this.video,
          from: this.from,
          to: this.to,
          tutor: this.tutor.id || null,
          published_at: this.startedAt,
          theme: this.advertPrice ? {
            id: this.advertPrice.id,
            name: this.advertPrice.name
          } : null,
        };

        axios.post(`${this.prependUrl}/classroom/register`, post)
          .then((response, error) => {
            console.log(response, error);
            this.error = null;
            this.loaderSend = false;
          })
          .catch(err => {
            console.log(err);
//            this.error = err.message;
//            this.loaderSend = false;
          });
      },

      showModal () {
        this.$refs.modal.show();
      },

      hideModal () {
        this.$refs.modal.hide();
      }
    }
  }
</script>