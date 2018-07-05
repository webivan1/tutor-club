<template>
    <div>
        <b-modal ref="modal" hide-footer title="Регистрация урока">
            <div class="d-block text-center">
                <h3>Hello From My Modal!</h3>
            </div>
            <b-btn class="mt-3" variant="outline-danger" block @click="hideModal">Close Me</b-btn>
        </b-modal>
    </div>
</template>

<script>
  export default {
    props: ['user'],
    data() {
      return {
        startedAt: null,
        video: true,
        subject: null,
      };
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