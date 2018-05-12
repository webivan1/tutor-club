<template>
    <div>
        <a :href="'/offer/' + item.id" target="_blank">
            <div class="crop-image crop-image-250">
                <img class="card-img-top" :src="item.profile.image.file_path" :alt="item.user.name" />
            </div>
        </a>
        <div class="card-body">
            <h5 class="card-title">
                <span class="d-block text-crop">{{ item.title }}</span>
                <span class="d-block text-crop">@{{ item.user.name }}</span>
            </h5>
            <div class="card-text mb-2">
                <div class="row no-wrap col-inline mx-0 mb-1" v-for="price in item.prices.slice(0, 7)">
                    <div class="col-auto px-0 text-crop text-muted">
                        {{ price.category.name }}
                    </div>
                    <div class="col px-0 border-line-bottom"></div>
                    <div class="col-auto px-0">
                        <b>{{ price.price_type }}</b> {{ price.price_from }} {{ price.minutes ? '/ ' + price.minutes : '' }}
                    </div>
                </div>
            </div>

            <a :href="'/offer/' + item.id" class="btn btn-info">{{ messages.ReadMore }}</a>

            <b-btn v-b-tooltip.hover :title="messages.ReadDescription" variant="info" @click="showModal">
                <i class="material-icons">description</i>
            </b-btn>

            <b-modal ref="modelMoreRef" hide-footer :title="messages.DescriptionOffer">
                <div v-html="item.description"></div>

                <div v-if="item.files && item.files.length > 0">
                    <b-carousel
                        id="carousel1"
                        controls
                        indicators
                        :interval="0"
                    >
                        <!-- Slides with image only -->
                        <div v-for="img in item.files">
                            <b-carousel-slide
                                :img-src="img.file_path">
                            </b-carousel-slide>
                        </div>
                    </b-carousel>
                </div>
            </b-modal>

            <!--<pre>{{ item }}</pre>-->
        </div>
    </div>
</template>

<script>
  export default {
    props: ['item', 'messages'],
    methods: {
      showModal() {
        this.$refs.modelMoreRef.show();
      },
      hideModal() {
        this.$refs.modelMoreRef.hide();
      }
    }
  }
</script>