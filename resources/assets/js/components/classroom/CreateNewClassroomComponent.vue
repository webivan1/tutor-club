<template>
    <div class="pt-4">
        <form v-if="!successSend" @submit.prevent="register">
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
                    <small v-if="error && errors['theme.id']" class="text-danger d-block" v-for="err in errors['theme.id']">
                        {{ err }}
                    </small>
                </div>
            </div>

            <div class="form-group">
                <label>Выбрать дату начала урока</label>
                <date-picker v-model="startedAt" :config="options"></date-picker>
                <small v-if="error && errors['published_at']" class="text-danger d-block" v-for="err in errors['published_at']">
                    {{ err }}
                </small>
            </div>

            <checkbox v-model="video" label="Видиотрансляция" :checked="1"></checkbox>

            <div v-if="error !== null" class="alert alert-danger">
                <a class="pull-right text-danger" href="javascript:void(0)" @click="error = null">
                    <i class="fas fa-times"></i>
                </a>
                {{ error }}
            </div>

            <div class="text-right">
                <button :disabled="loaderSend" class="btn btn-info">
                    lesson is register
                </button>
            </div>
        </form>
        <div v-else>
            <div class="alert alert-success">
                {{ successMessage }}
            </div>

            <div class="text-center pt-2">
                <button class="btn btn-success" @click="hideModal">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
  import moment from 'moment'
  import momentTz from 'moment-timezone'

  export default {
    props: ['to', 'from', 'tutor', 'advert', 'prependUrl'],
    data() {
      return {
        successSend: false,
        successMessage: null,
        defaultList: [],
        list: [],
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
    mounted() {
      this.getAdvert();
    },
    methods: {
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

      hideModal() {
        this.$emit('hide');
      },

      register() {
        this.loaderSend = true;

        let post = {
          timezone: momentTz.tz.guess(),
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
          .then(response => {
            this.successSend = true;
            this.successMessage = response.data.message;
            this.error = null;
            this.loaderSend = false;
          })
          .catch(error => {
            // validation error
            if (error.status === 422) {
              this.error = error.data.message;
              this.errors = error.data.errors;
              this.loaderSend = false;
            } else {
              console.error(error);
            }
          });
      }
    }
  }
</script>