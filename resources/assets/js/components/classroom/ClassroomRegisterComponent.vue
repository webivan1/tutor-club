<template>
    <div>
        <b-modal ref="modal" hide-footer title="Регистрация урока">

            <h4 class="text-center text-info mb-1">
                <b>{{ startedAt }}</b>
            </h4>

            <date-picker v-model="startedAt" :config="options"></date-picker>

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
    props: ['user', 'tutor', 'dialog'],
    data() {
      return {
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

    },
    methods: {
      showModal () {
        this.$refs.modal.show()
      },
      hideModal () {
        this.$refs.modal.hide()
      }
    }
  }
</script>