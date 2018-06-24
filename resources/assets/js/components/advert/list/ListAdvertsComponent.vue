<template>
    <div :class="{ 'loader': loaderList === true }">
        <div v-if="loader" class="ld ld-ring ld-spin-fast text-center text-info"></div>
        <div v-else-if="models && models.length > 0" class="mb-4">
            <div class="card-columns card-offers">
                <div class="card bg-grey-100" v-for="item in models">
                    <item-advert :messages="messages" :item="item"></item-advert>
                </div>
            </div>
        </div>
        <div v-else-if="loaderList === false">
            <div class="alert alert-info">
                Empty
            </div>
        </div>
    </div>
</template>

<script>
  import Vue from 'vue';
  import ItemAdvert from './ItemAdvertComponent.vue';

  Vue.component('item-advert', ItemAdvert);

  export default {
    props: ['lang', 'messages', 'loaderList', 'models'],
    data() {
      return {
        loader: false,
      }
    }
  }
</script>