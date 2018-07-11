<template>
    <div>
        <b-modal v-on:show="isShowModal" ref="modal" hide-footer hide-header>
            <b-tabs>
                <b-tab title="Регистрация урока" active>
                    <create-classroom
                        v-if="show"
                        :to="to"
                        :from="from"
                        :tutor="tutor"
                        :advert="advert"
                        :prependUrl="prependUrl"
                        v-on:hide="hideModal"
                    ></create-classroom>
                </b-tab>
                <b-tab title="Выбрать урок">
                    <choose-classroom
                        v-if="show"
                        :to="to"
                        :from="from"
                        :tutor="tutor"
                        :advert="advert"
                        :prependUrl="prependUrl"
                    ></choose-classroom>
                </b-tab>
            </b-tabs>
        </b-modal>
    </div>
</template>

<script>
  import CreateNewClassroomComponent from './CreateNewClassroomComponent.vue'
  import ChooseClassroomComponent from './ChooseClassroomComponent.vue'

  export default {
    props: ['to', 'from', 'tutor', 'advert'],
    components: {
      'create-classroom': CreateNewClassroomComponent,
      'choose-classroom': ChooseClassroomComponent
    },
    created() {
      this.prependUrl = document.body.getAttribute('data-url');
    },
    data() {
      return {
        show: false,
        prependUrl: null,
      }
    },
    methods: {
      showModal() {
        this.$refs.modal.show();
      },

      hideModal() {
        this.$refs.modal.hide();
      },

      isShowModal() {
        this.show = true;
      }
    }
  }
</script>