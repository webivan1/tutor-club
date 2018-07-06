<template>
    <div>
        <b-modal v-on:show="isShowModal" ref="modal" hide-footer title="Регистрация урока">

            <h4 class="text-center text-info mb-1">
                <b>{{ startedAt }}</b>
            </h4>

            <date-picker v-model="startedAt" :config="options"></date-picker>

            <div class="text-center py-2" v-if="loaderPrices">
                <div class="ld ld-ring ld-spin-fast fs-1 text-orange mx-auto"></div>
            </div>
            <div v-else>
                ... OK ...
            </div>

            <div class="form-group">
                <label>Тема урока</label>
                <textarea v-model="subject" class="form-control"></textarea>
            </div>

            <b-btn class="mt-3" variant="outline-danger" block @click="hideModal">Close Me</b-btn>
        </b-modal>
    </div>
</template>

<script>
  import moment from 'moment'

  export default {
    props: ['dialog', 'from', 'tutor', 'advert'],
    data() {
      return {
        list: [],
        prependUrl: null,
        loaderPrices: true,
        startedAt: null,
        video: true,
        subject: null,
        options: {
          minDate: moment(),
          inline: true,
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
          tutor: this.tutor.tutor.id,
          advert: this.advert
        })
          .then(response => {
            this.loaderPrices = false;
            console.log(response);
          })
          .catch(err => console.error(err));
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