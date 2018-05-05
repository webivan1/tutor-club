<template>
    <div :class="{ 'loader': loaderList === true }">
        <div v-if="loader" class="ld ld-ring ld-spin-fast text-center text-info"></div>
        <div v-else-if="models && models.length > 0" class="mb-4">
            <div class="card-columns card-offers">
                <div class="card" v-for="item in models">
                    <div class="crop-image crop-image-200">
                        <img class="card-img-top" :src="item.profile.image.file_path" :alt="item.user.name" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ item.title }} - {{ item.user.name }}</h5>
                        <div class="card-text">
                            <div class="info-list">
                                <div class="info-list-item text-crop mb-1" v-for="price in item.prices">
                                    <div class="float-right">
                                        <b>{{ price.price_type }}</b> {{ price.price_from }} {{ price.minutes ? '/ ' + price.minutes : '' }}
                                    </div>
                                    <div class="float-left text-muted">
                                        {{ price.category.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
  export default {
    props: ['lang', 'messages', 'loaderList', 'models'],
    data() {
      return {
        loader: false,
      }
    }
  }
</script>