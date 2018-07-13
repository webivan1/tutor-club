<template>
    <div v-if="dateMoment">
        <b>{{ dateMoment.format('DD/MM/YYYY HH:mm') }}</b>
        <small class="text-grey">
            (<timeago :since="dateMoment" :auto-update="60"></timeago>)
        </small>
    </div>
</template>

<script>
  import moment from 'moment'
  import DataHelper from '../../helpers/DateHelper'

  export default {
    props: ['date'],
    data() {
      return {
        dateMoment: null
      }
    },
    created() {
      let dateTz = DataHelper.getFormtatTimezone(this.date);
      this.dateMoment = moment(dateTz);
    }
  }
</script>